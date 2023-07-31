<?php

namespace Drupal\custom_cron\Helper;

use Drupal;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\taxonomy\Entity\Term;

class Ebis_Helper {

  public function sync(){

    $client = new Client([
      'base_uri' => $_ENV["APIGW_BASE_URL"]
    ]);

    $accessToken   = null;

    // get access to apigw
    $authResponse  = $client->post('invoke/pub.apigateway.oauth2/getAccessToken', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
      'body' => json_encode([
        "grant_type"    => "client_credentials",
        "client_id"     => $_ENV["APIGW_CLIENT_ID"],
        "client_secret" => $_ENV["APIGW_CLIENT_SECRET"]
      ])
    ]);

    // get access token
    if ($authResponse->getStatusCode() == 200){
      $response = json_decode($authResponse->getBody());
      $accessToken = $response->token_type .' '. $response->access_token;

      // get citem data
      if (!empty($accessToken)) {

        $responseData  = $client->post('gateway/telkom-feasplatform/1.0/apiProduct/list-product-crm', [
          'headers' => [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            'Authorization' => $accessToken
          ],
          'body' => json_encode([
            'apiProductRequest' => [
              'eaiHeader' => [
                'externalId' => '',
                'timestamp' => time()
              ],
              'eaiBody' => [
                'guid'  => 0,
                'coode' => 0,
                'data'  => [
                  "core_system" => "EBIS"
                ]
              ]
            ]  
          ])
        ]);

        if ($responseData->getStatusCode() == 200){
          $response = json_decode($responseData->getBody(), true);

          if ($response['apiProductResponse']['eaiStatus']['srcResponseCode'] == 200) {
            $data = $response['apiProductResponse']['eaiBody']['data'];

            // general list term in taxonomy "citem_product_type"
            $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'non_retail_product_type']);
            $terms_label_id = [];
            foreach ($terms as $term) {
                $terms_label_id[$term->label()] = $term->id();
            }
            unset($terms);

            foreach ($data as $ebis) {
              $this->process_sync($ebis, $terms_label_id);
            }
          }
        }

      }

    }

    return true;

  }

  /**
   * Store citem data
   */
  private function process_sync($ebis, array &$arr_ebis_type){

    $entity = \Drupal::entityTypeManager()->getStorage('node');

    // add term if not exist in taxonomy "tipe_paket"
    $ebis_type = $ebis['prod_type'];
    if (!empty($ebis_type) && empty($arr_ebis_type[$ebis_type])) {
      $term = Term::create(array(
          'name' => $ebis_type,
          'vid' => 'non_retail_product_type'
      ));
      $term->save();

      $arr_ebis_type[$term->label()] = $term->id();
    }


    $query = $entity->getQuery();

    $ids = $query
      ->condition('type', 'ebis')#type = bundle id (machine name)
      ->condition('field_non_ret_prod_id', $ebis['prod_id'])
      ->range(0, 1)
      ->execute();

    $id = reset($ids);
    $ebis_data = $id!==false ? Node::load($id) : null;

    // will be used in page edit ebis
    // to appear in that page, "key" must be same as listed in list_field_edit in ebis
    // If "key" not exist in master_data, it will show empty
    $master_data = [
      "title" => $ebis['prod_name'],
      "prod_id" => $ebis['prod_id'],
      "prod_name" => $ebis['prod_name'],
      "prod_code" => $ebis['prod_code'],
      "prod_type" => $ebis['prod_type'],
      "prod_desc" => $ebis['prod_desc'],
      "actual_from_pefita" => $ebis
    ];

    if ($ebis_data) {

      // update

      // update mandatory field
      $ebis_data->field_non_ret_prod_id = $ebis['prod_id'];
      $ebis_data->field_non_ret_prod_name = $ebis['prod_name'];
      $ebis_data->field_non_ret_prod_code = $ebis['prod_code'];
      $ebis_data->field_non_ret_prod_type = !empty($ebis['prod_type']) ? $arr_ebis_type[$ebis['prod_type']] : null;
      // $ebis_data->field_non_ret_prod_desc = $ebis['prod_desc'];
      
      $ebis_data->field_pkt_master_data = json_encode($master_data);

      $ebis_data->save();
    }
    else{

      // add citem to database

      $data_to_insert = [
        'type' => 'ebis',
        'title'=> $ebis['prod_name'],
        'field_non_ret_prod_id' => $ebis['prod_id'],
        'field_non_ret_prod_name' => $ebis['prod_name'],
        'field_non_ret_prod_code' => $ebis['prod_code'],
        'field_non_ret_prod_type' => !empty($ebis['prod_type']) ? $arr_ebis_type[$ebis['prod_type']] : null,
        'field_non_ret_prod_desc' => $ebis['prod_desc'],
        'field_pkt_master_data' => json_encode($master_data),
        'field_pkt_is_customized' => false,
        // 'field_pkt_created_dtm' => $package['created_dtm'],
        // 'field_pkt_updated_dtm' => $package['updated_dtm'],
        'field_workflow_status' => 'workflow_status_approve'
      ];

      $query = \Drupal::entityTypeManager()->getStorage('user')->getQuery();
      $ids = $query->condition('status', 1)->condition('roles', 'administrator')->range(0,1)->execute();

      $ebis_data = Node::create($data_to_insert);
      $ebis_data->uid = array_keys(User::loadMultiple($ids))[0];
      $ebis_data->save();
    }
  }

}