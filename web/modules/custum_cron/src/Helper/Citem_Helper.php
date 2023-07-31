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

class Citem_Helper {

  public function sync($citem_type=''){

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

        $arr_available_citem_type = ['ADDON_INTERNET', 'ADDON_IPTV', 'ADDON_VOICE', 'INTERNET', 'IPTV', 'VOICE', 'WIFI'];
        $arr_available_citem_type = !empty($citem_type) && in_array(strtoupper($citem_type), $arr_available_citem_type) ? [strtoupper($citem_type)] : $arr_available_citem_type;

        // general list term in taxonomy "citem_product_type"
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'citem_product_type']);
        $citem_type_terms = [];
        foreach ($terms as $term) {
            $citem_type_terms[$term->label()] = $term->id();
        }
        unset($terms);

        foreach ($arr_available_citem_type as $type) {
          $responseData  = $client->post('gateway/telkom-feasplatform/1.0/apiProduct/list-feature-package', [
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
                    'type'=> $type
                  ]
                ]
              ]  
            ])
          ]);

          if ($responseData->getStatusCode() == 200){
            $response = json_decode($responseData->getBody(), true);

            if ($response['apiProductResponse']['eaiStatus']['srcResponseCode'] == 200) {
              $data = $response['apiProductResponse']['eaiBody']['data'];


              foreach ($data as $citem) {
                $this->process_sync_citem($citem, $citem_type_terms);
              }
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
  private function process_sync_citem($citem, array &$arr_citem_type){

    $entity = \Drupal::entityTypeManager()->getStorage('node');

    // add term if not exist in taxonomy "tipe_paket"
    $citem_type = $citem['product_type'];
    if (!empty($citem_type) && empty($arr_citem_type[$citem_type])) {
        $term = Term::create(array(
            'name' => $citem_type,
            'vid' => 'citem_product_type'
        ));
        $term->save();

        $arr_citem_type[$term->label()] = $term->id();
    }


    $query = $entity->getQuery();

    $ids = $query
      ->condition('type', 'citem')#type = bundle id (machine name)
      ->condition('field_ctm_product_id', $citem['product_id'])
      ->range(0, 1)
      ->execute();

    $id = reset($ids);
    $citem_data = $id!==false ? Node::load($id) : null;

    // will be used in page edit citem
    // to appear in that page, "key" must be same as listed in list_field_edit_citem
    // If "key" not exist in master_data, it will show empty
    $master_data = [
      "title" => $citem['prod_name'],
      "product_type" => $citem['product_type'],
      "product_id" => $citem['product_id'],
      "product_rel" => $citem['product_rel'],
      "product_rel_id" => $citem['product_rel_id'],
      "product_code" => $citem['prod_code'],
      "product_status" => $citem['status_cd'],
      "start_date" => $citem['eff_start_date'],
      "end_date" => $citem['eff_end_date'],
      "actual_from_pefita" => $citem
    ];

    if ($citem_data) {

      // update citem

      if ($citem_data->field_ctm_start_date->getString()!=$citem['eff_start_date'] || $citem_data->field_ctm_end_date->getString()!=$citem['eff_end_date']) {
        // update mandatory field
        $citem_data->field_ctm_product_type = $arr_citem_type[$citem['product_type']];
        $citem_data->field_ctm_product_id = $citem['product_id'];
        $citem_data->field_ctm_product_rel = $citem['product_rel'];
        $citem_data->field_ctm_product_rel_id = $citem['product_rel_id'];
        $citem_data->field_ctm_product_code = $citem['prod_code'];
        $citem_data->field_ctm_product_name = $citem['prod_name'];
        $citem_data->field_ctm_status = $citem['status_cd'];
        $citem_data->field_ctm_start_date = $citem['eff_start_date'];
        $citem_data->field_ctm_end_date = $citem['eff_end_date'];
        $citem_data->field_pkt_master_data = json_encode($master_data);

        $citem_data->save();
        
      }
    }
    else{

      // add citem to database

      $data_to_insert = [
        'type' => 'citem',
        'title'=> $citem['prod_name'],
        'field_ctm_product_type' => $arr_citem_type[$citem['product_type']],
        'field_ctm_product_id' => $citem['product_id'],
        'field_ctm_product_rel' => $citem['product_rel'],
        'field_ctm_product_rel_id' => $citem['product_rel_id'],
        'field_ctm_product_code' => $citem['prod_code'],
        'field_ctm_product_name' => $citem['prod_name'],
        'field_ctm_status' => $citem['status_cd'],
        'field_ctm_start_date' => $citem['eff_start_date'],
        'field_ctm_end_date' => $citem['eff_end_date'],
        'field_pkt_master_data' => json_encode($master_data),
        'field_pkt_is_customized' => false,
        // 'field_pkt_created_dtm' => $package['created_dtm'],
        // 'field_pkt_updated_dtm' => $package['updated_dtm'],
        'field_workflow_status' => 'workflow_status_approve'
      ];

      $query = \Drupal::entityTypeManager()->getStorage('user')->getQuery();
      $ids = $query->condition('status', 1)->condition('roles', 'administrator')->range(0,1)->execute();

      $citem_data = Node::create($data_to_insert);
      $citem_data->uid = array_keys(User::loadMultiple($ids))[0];
      $citem_data->save();
    }
  }

  public function checkForCitemPrice(Node $node, $update_exist_price = false) {

    $all_price_selected = false; //default false, indicates to redirect to edit citem price

    // check this is catalog for citem
    if ($node->bundle()==='product_catalog') {

      $catalog_type = !empty($node->field_pct_type->getString()) ? $node->field_pct_type->referencedEntities()[0]->label() : '';
      if (strtolower($catalog_type)==='citem') {
        
        // reassign
        $all_price_selected = true;

        $prices = json_decode($node->field_pct_citem_price->getString(), true);
        $prices = !empty($prices) ? $prices : []; // format : [ '$citem_id' => ["active" => null | tariff_id, price=>[dari api]] ]

        $citem_ids = [];
        foreach ($node->field_pct_list_paket->referencedEntities() as $citem) {
          
          $citem_id = $citem->field_ctm_product_id->getString();
          $citem_ids[] = $citem_id;

          $citem_price = !empty($prices[$citem_id]) ? $prices[$citem_id] : [
            'active' => null,
            'list' => []
          ];

          // get available citem price from pefite
          if (empty($citem_price['list']) || $update_exist_price) {
            // get from API
            $citem_price['list'] = $this->getCitemPrice($citem->field_ctm_product_code->getString());
          }

          if (count($citem_price['list']) === 1) {
            $citem_price['active'] = $citem_price['list'][0]['tariff_id']; // set tarif id
          }
          else{
            if (empty($citem_price['active'])) {
              // multiple price and price is not selected
              $all_price_selected = false;
            }
            else if (!in_array($citem_price['active'], array_column($citem_price['list'], 'tariff_id'))){
              // selected tarif is not found in list available tarif
              $citem_price['active'] = null;
              $all_price_selected = false;
            }
          }

          $citem_price['data_citem'] = [
            'id' => $citem->id(),
            'title' => $citem->label()
          ];

          $prices[$citem_id] = $citem_price;
        }

        $prices = array_filter($prices, function($key) use($citem_ids){
          return in_array($key, $citem_ids);
        }, ARRAY_FILTER_USE_KEY);

        $node->field_pct_citem_price = json_encode($prices);
        $node->save();

      }
    }

    return $all_price_selected;
  }

  private function getCitemPrice(string $citemCode) {

    if (!empty($citemCode)) {
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
  
          $responseData  = $client->post('gateway/telkom-feasplatform/1.0/apiProduct/list-tarif-feature', [
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
                    "tariff_code" => $citemCode,
                    "product_code" => $citemCode
                  ]
                ]
              ]  
            ])
          ]);

          if ($responseData->getStatusCode() == 200){
            $response = json_decode($responseData->getBody(), true);

            if ($response['apiProductResponse']['eaiStatus']['srcResponseCode'] == 200) {
              return $response['apiProductResponse']['eaiBody']['data'];
            }
          }
        }
  
      }
      
    }

    return [];
  }

}