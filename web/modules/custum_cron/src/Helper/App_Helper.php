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

class App_Helper {

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

      // get package data
      if (!empty($accessToken)) {

        $data_package = [];
        $parameters = ['CONS', 'RETAIL+EBIS'];

        // get data
        foreach ($parameters as $parameter) {
          $responseData  = $client->post('gateway/telkom-feasplatform/1.0/apiProduct/list-product', [
            'headers' => [
              'Content-Type' => 'application/json',
              'Accept'       => 'application/json',
              'Authorization' => $accessToken
            ],
            'body' => json_encode([
              'apiProductRequest' => [
                'eaiHeader' => [
                  'externalId' => '',
                  'timestamp' =>''
                ],
                'eaiBody' => [
                  'guid' => 0,
                  'code' => 0,
                  'data' => [
                    'speed'  => null,
                    'offset' => 0,
                    'limit'  => null,
                    'core_system' => $parameter
                  ]
                ]
              ]  
            ])
          ]);
  
          if ($responseData->getStatusCode() == 200){
            $response = json_decode($responseData->getBody(), true);
  
            if ($response['apiProductResponse']['eaiStatus']['srcResponseCode'] == 200) {
              $data_package[$parameter] = $response['apiProductResponse']['eaiBody']['data'];
            }
            unset($response);
          }
          unset($responseData);
        }
        // general list term in taxonomy "tipe_paket"
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'tipe_paket']);
        $arr_tipe_paket = [];
        foreach ($terms as $term) {
            $arr_tipe_paket[$term->label()] = $term->id();
        }
        unset($terms);

        // general list term in taxonomy "source_paket"
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'source_paket']);
        $arr_source_paket = [];
        foreach ($terms as $term) {
            $arr_source_paket[$term->label()] = $term->id();
        }
        unset($terms);

        foreach ($data_package as $data_by_parameter) {
          foreach ($data_by_parameter as $speed => $list_package) {
            $this->process_sync_package($list_package['data'], $arr_tipe_paket, $arr_source_paket);
          }
        }

      }

    }

    return true;

  }

  /**
   * Store package data
   * 
   * @param array $packages indexed by their package_id
   */
  private function process_sync_package($packages, array &$arr_tipe_paket, array &$arr_source_paket){

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    foreach ($packages as $package) {

      // add term if not exist in taxonomy "tipe_paket"
      $tipe_paket = $package['tipe_paket'];
      if (!empty($tipe_paket) && empty($arr_tipe_paket[$tipe_paket])) {
          $term = Term::create(array(
              'name' => $tipe_paket,
              'vid' => 'tipe_paket'
          ));
          $term->save();

          $arr_tipe_paket[$term->label()] = $term->id();
      }

      // add term if not exist in taxonomy "source_paket"
      $source_paket = $package['source'];
      if (!empty($source_paket) && empty($arr_source_paket[$source_paket])) {
          $term = Term::create(array(
              'name' => $source_paket,
              'vid' => 'source_paket'
          ));
          $term->save();

          $arr_source_paket[$term->label()] = $term->id();
      }

      // will be used in page edit paket
      // to appear in that page, "key" must be same as listed in list_field_edit in web\themes\custom\telkom_cms\functions\paket\helper.php
      // If "key" not exist in master_data, it will show empty
      $master_data = [
        'title' => $package['flag'],
        'sub_title' => '',
        'speed' => (string) $package['speed'],
        'flag' => $package['flag'],
        'promo_text' => '',
        'detail_voice' => $package['detail_voice'],
        'detail_inet' => $package['detail_inet'],
        'price_voice' => $package['price_voice'],
        'price_internet' => $package['price_inet'],
        'price_total' => $package['price_total'],
        'billing_period' => '',
        'tipe_paket' => $package['tipe_paket'], // store as text
        'kuota' => $package['kuota'],
        'flag_json' => $package['flag_json'],
        'trans_type' => $package['trans_type'],
        'service' => $package['service'],
        'indihome_indicator' => $package['indihomeindicator'],
        'package_id' => $package['package_id'],
        'package_detail' => str_replace('|',"\n", $package['package_details']),
        'source' => $source_paket,
        "actual_from_pefita" => $package
      ];

      $query = $entity->getQuery();

      $ids = $query
        ->condition('type', 'paket')#type = bundle id (machine name)
        ->condition('field_pkt_package_id', $package['package_id'])
        ->range(0, 1)
        #->sort('created', 'ASC') #sorted
        #->pager(15) #limit 15 items
        ->execute();
      $id = reset($ids);
      $package_data = $id!==false ? Node::load($id) : null;

      if ($package_data) {

        // update package

        if (
          $package_data->field_pkt_created_dtm->getString() != $package['created_dtm'] || 
          $package_data->field_pkt_updated_dtm->getString() != $package['updated_dtm'] || 
          empty($package_data->field_pkt_source->getString())
        ) {

          // update mandatory field like price (field which is disabled from edit/customized)
          $package_data->field_pkt_speed = (string) $package['speed'];
          $package_data->field_pkt_price_voice = $package['price_voice'];
          $package_data->field_pkt_price_internet = $package['price_inet'];
          $package_data->field_pkt_price_total = $package['price_total'];
          $package_data->field_pkt_tipe_paket =  !empty($package['tipe_paket']) ? $arr_tipe_paket[$package['tipe_paket']] : null; // store taxonomy id
          $package_data->field_pkt_kuota = $package['kuota'];
          $package_data->field_pkt_trans_type = $package['trans_type'];
          $package_data->field_pkt_indihome_indicator = $package['indihomeindicator'];

          $package_data->field_pkt_source =  !empty($source_paket) ? $arr_source_paket[$source_paket] : null; // store taxonomy id

          $package_data->field_pkt_master_data = json_encode($master_data);
          $package_data->field_pkt_created_dtm = $package['created_dtm'];
          $package_data->field_pkt_updated_dtm = $package['updated_dtm'];

          $package_data->save();
          
        }
      }
      else{

        $json_master_data = json_encode($master_data);

        $data_to_insert = [
          'type' => 'paket',
          'title' => !empty($package['flag']) ? substr($package['flag'],0,255) : 'paket',
          'field_pkt_sub_title' => '',
          'field_pkt_speed' => (string) $package['speed'],
          'field_pkt_flag' => $package['flag'],
          'field_pkt_promo_text' => '',
          'field_pkt_xml_paket' => '',
          'field_pkt_md5_xml_paket' => '',
          'field_pkt_detail_voice' => $package['detail_voice'],
          'field_pkt_detail_inet' => $package['detail_inet'],
          'field_pkt_price_voice' => $package['price_voice'],
          'field_pkt_price_internet' => $package['price_inet'],
          'field_pkt_price_total' => $package['price_total'],
          'field_pkt_billing_period' => 'bulan',
          'field_pkt_tipe_paket' => !empty($package['tipe_paket']) ? $arr_tipe_paket[$package['tipe_paket']] : null, // store taxonomy id
          'field_pkt_kuota' => $package['kuota'],
          'field_pkt_flag_json' => $package['flag_json'],
          'field_pkt_trans_type' => $package['trans_type'],
          'field_pkt_service' => $package['service'],
          'field_pkt_indihome_indicator' => $package['indihomeindicator'],
          'field_pkt_package_id' => $package['package_id'],
          'field_pkt_package_detail' => str_replace('|',"\n", $package['package_details']),
          'field_pkt_master_data' => $json_master_data,
          'field_pkt_master_data_edited' => '',
          'field_pkt_is_customized' => false,
          'field_pkt_created_dtm' => $package['created_dtm'],
          'field_pkt_updated_dtm' => $package['updated_dtm'],
          'field_pkt_source' =>  !empty($source_paket) ? $arr_source_paket[$source_paket] : null, // store taxonomy id
          'field_workflow_status' => 'workflow_status_approve'
        ];

        $query = \Drupal::entityTypeManager()->getStorage('user')->getQuery();
        $ids = $query->condition('status', 1)->condition('roles', 'administrator')->range(0,1)->execute();

        $package_data = Node::create($data_to_insert);
        $package_data->uid = array_keys(User::loadMultiple($ids))[0];
        $package_data->save();
      }
    }
  }

  /**
   * @param $notif_type int 0 = new, 1 = modified
   */
  public function store_queue_notif_package($node_id = 0, $notif_type = 0){

    if (!empty($node_id) && in_array($notif_type, [0, 1])) {
      $conn = Database::getConnection();

      $conn->insert('custom_cron_notify_package')
            ->fields([
              'status'     => 0,
              'tried'      => 0,
              'node_id'    => $node_id,
              'notif_type' => $notif_type,
              'response'   => null,
              'created_at' => date("Y-m-d H:i:s"),
              'updated_at' => date("Y-m-d H:i:s")
            ])
            ->execute();

      return true;
    }

    return false;
  }

  /**
   * Update progress in inbox paket (progress customizing)
   */
  public function update_paket_inbox_progress(Node $node) {

    // paket is already customized, update inbox progress

    if ($node && $node->bundle() === 'paket' && (int) $node->field_pkt_is_customized->getString() ) {
      $package_id = $node->field_pkt_package_id->getString();
      if ($package_id) {
        // core query data
        $entity = \Drupal::entityTypeManager()->getStorage('node');
        $query = $entity->getQuery()
          ->condition('status', 1)
          ->condition('type', 'paket_inbox')
          ->condition('field_pin_package', $package_id) // contains this package id
          ->condition('field_pin_finished', false);

        // load data
        $loadedData = $entity->loadMultiple($query->execute());

        foreach ($loadedData as $inbox) {
          $list_is_customized = array_map(fn($val)=> $val['value'], $inbox->field_pin_package_customized->getValue());
          
          if (in_array($package_id, $list_is_customized)) {
            // already customized, skip
            continue;
          }

          // add to list
          $list_is_customized[] = $package_id;
          $inbox->field_pin_package_customized = $list_is_customized;

          // change finished status if all package already customized
          $list_package = array_map(fn($val)=> $val['value'], $inbox->field_pin_package->getValue());
          if (count($list_package) === count($list_is_customized)) {
            $inbox->field_pin_finished = true;
          }

          $inbox->save();

          $this->submit_to_kafka(json_encode(
            [
              'title' => $inbox->label(),
              'description' => $inbox->field_pin_description->getString(),
              'package' => array_map(fn($val)=> $val['value'], $inbox->field_pin_package->getValue()),
              'package_customized' => $list_is_customized,
              'attachment' => array_map(fn($val)=> $val['value'], $inbox->field_pin_attachment->getValue())
            ]
          ));

        }
      }
    }
  }

  public function submit_to_kafka(string $message) {
    try {
      //code...
      $conf = new \RdKafka\Conf();
      $conf->set('log_level', (string) LOG_DEBUG);
      $conf->set('debug', 'all');
      $rk = new \RdKafka\Producer($conf);
      $rk->addBrokers($_ENV['KAFKA_HOST']);

      
      $topic = $rk->newTopic($_ENV['KAFKA_TOPIC']);
      $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
      
      $rk->flush(1000);
    } catch (\Throwable $e) {
      Drupal::logger('kafka')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
    } catch (\Exception $e) {
      Drupal::logger('kafka')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
    }
  }

  public function store_queue_template_screenshoot(int $node_id){

    if (!empty($node_id)) {
      $conn = Database::getConnection();

      $conn->insert('custom_cron_template_page_screenshoot')
            ->fields([
              'node_id'    => $node_id,
              'crawl'      => 0,
              'status'     => 0,
              'tried'      => 0,
              'created_at' => date("Y-m-d H:i:s"),
              'updated_at' => date("Y-m-d H:i:s")
            ])
            ->execute();

      return true;
    }

    return false;
  }

}