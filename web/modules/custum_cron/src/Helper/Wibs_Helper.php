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

class Wibs_Helper {

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
                  "core_system" => "WIBS"
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

            foreach ($data as $wibs) {
              $this->process_sync($wibs, $terms_label_id);
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
  private function process_sync($wibs, array &$arr_wibs_type){

    $entity = \Drupal::entityTypeManager()->getStorage('node');

    // add term if not exist in taxonomy "tipe_paket"
    $wibs_type = $wibs['prod_type'];
    if (!empty($wibs_type) && empty($arr_wibs_type[$wibs_type])) {
      $term = Term::create(array(
          'name' => $wibs_type,
          'vid' => 'non_retail_product_type'
      ));
      $term->save();

      $arr_wibs_type[$term->label()] = $term->id();
    }


    $query = $entity->getQuery();

    $ids = $query
      ->condition('type', 'wibs')#type = bundle id (machine name)
      ->condition('field_non_ret_prod_id', $wibs['prod_id'])
      ->range(0, 1)
      ->execute();

    $id = reset($ids);
    $wibs_data = $id!==false ? Node::load($id) : null;

    // will be used in page edit wibs
    // to appear in that page, "key" must be same as listed in list_field_edit_wibs
    // If "key" not exist in master_data, it will show empty
    $master_data = [
      "title" => $wibs['prod_name'],
      "prod_id" => $wibs['prod_id'],
      "prod_name" => $wibs['prod_name'],
      "prod_code" => $wibs['prod_code'],
      "prod_type" => $wibs['prod_type'],
      "prod_desc" => $wibs['prod_desc'],
      "actual_from_pefita" => $wibs
    ];

    if ($wibs_data) {

      // update

      // update mandatory field
      $wibs_data->field_non_ret_prod_id = $wibs['prod_id'];
      $wibs_data->field_non_ret_prod_name = $wibs['prod_name'];
      $wibs_data->field_non_ret_prod_code = $wibs['prod_code'];
      $wibs_data->field_non_ret_prod_type = !empty($wibs['prod_type']) ? $arr_wibs_type[$wibs['prod_type']] : null;
      // $wibs_data->field_non_ret_prod_desc = $wibs['prod_desc'];
      
      $wibs_data->field_pkt_master_data = json_encode($master_data);

      $wibs_data->save();
    }
    else{

      // add citem to database

      $data_to_insert = [
        'type' => 'wibs',
        'title'=> $wibs['prod_name'],
        'field_non_ret_prod_id' => $wibs['prod_id'],
        'field_non_ret_prod_name' => $wibs['prod_name'],
        'field_non_ret_prod_code' => $wibs['prod_code'],
        'field_non_ret_prod_type' => !empty($wibs['prod_type']) ? $arr_wibs_type[$wibs['prod_type']] : null,
        'field_non_ret_prod_desc' => $wibs['prod_desc'],
        'field_pkt_master_data' => json_encode($master_data),
        'field_pkt_is_customized' => false,
        // 'field_pkt_created_dtm' => $package['created_dtm'],
        // 'field_pkt_updated_dtm' => $package['updated_dtm'],
        'field_workflow_status' => 'workflow_status_approve'
      ];

      $query = \Drupal::entityTypeManager()->getStorage('user')->getQuery();
      $ids = $query->condition('status', 1)->condition('roles', 'administrator')->range(0,1)->execute();

      $wibs_data = Node::create($data_to_insert);
      $wibs_data->uid = array_keys(User::loadMultiple($ids))[0];
      $wibs_data->save();
    }
  }

}