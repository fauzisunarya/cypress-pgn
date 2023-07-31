<?php
namespace Drupal\custom_cron\Controller;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class PackageController {

  private $conn;

  public function __construct()
  {
    $this->conn = Database::getConnection();
  }

  /**
   * Sync now
   */
  public function sync(){
    Drupal::service('custom_cron.app_helper')->sync();
    return new Response('success sync');
  }

  /**
   * syncron package when there is data in queue package table
   */
  public function sync_queue(){

    // get queue syncronize
    $query = $this->conn->select('custom_cron_sync_package', 'e')
      ->condition('e.status', 0, '=')
      ->condition('e.tried', 10, '<')
      ->fields('e', ['id', 'status','tried', 'created_at'])
      ->range(0, 1);

    $data = $query->execute()->fetchAll();
    foreach ($data as $value) {
      $this->conn->update('custom_cron_sync_package')->fields(['tried'=> $value->tried+1, 'updated_at' => date("Y-m-d H:i:s")])->condition('id', $value->id, '=')->execute();
      
      Drupal::service('custom_cron.app_helper')->sync();

      $this->conn->update('custom_cron_sync_package')->fields(['status'=> 1, 'updated_at' => date("Y-m-d H:i:s")])->condition('id', $value->id, '=')->execute();
    }

    return new Response('success sync');
    
  }

  /**
   * Endpoint sync now, store to queue table first and directly return response 
   */
  public function sync_now(Request $request){

    $title = $request->request->get('title');
    $description = $request->request->get('description');
    $package = $request->request->get('package');
    $attachment = $request->request->get('attachment');

    if (!empty($title)) {
      if ( ! is_array($package) || ! is_array($attachment)) {
        return \Drupal::service('restapi_telkom.app_helper')->response([
          'status' => 'failed',
          'message' => 'package and attachment must be an array'
        ], 422);
      }

      $package = array_filter($package);
      $attachment = array_filter($attachment);

      if (empty($description) || empty($package)) {
        return \Drupal::service('restapi_telkom.app_helper')->response([
          'status' => 'failed',
          'message' => 'description and package are required'
        ], 422);
      }

      $inbox = Node::create([
        'type' => 'paket_inbox',
        'title' => $title,
        'field_pin_description' => $description,
        'field_pin_package' => $package,
        'field_pin_attachment' => $attachment
      ]);

      $inbox->save();
    }

    $this->conn->insert('custom_cron_sync_package')
          ->fields([
            'status' => 0,
            'tried' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
          ])
          ->execute();

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status' => 'success',
      'message' => 'Please wait about 5 - 10 minutes to see the results'
    ]);
  }

  public function send_notif_package(){

    // get queue syncronize
    $query = $this->conn->select('custom_cron_notify_package', 'e')
      ->condition('e.status', 0, '=')
      ->condition('e.tried', 10, '<')
      ->fields('e', ['id', 'status','tried', 'node_id', 'notif_type']);

    $client = new Client();

    foreach ($query->execute()->fetchAll() as $data) {
      $node = Node::load($data->node_id);
      // dd($node);
      if ($node) {
        $message = (int) $data->notif_type === 1 ? "Modified" : "New";
        
        $payload = [
          "message" => "$message package",
          "data"    => [
            "uuid"                => $node->uuid(),
            "name"                => $node->label(),
            "module"              => "paket",
            "package_id"          => (int) $node->field_pkt_package_id->getString(),
            "created_date"        => date("Y-m-d H:i:s", $node->getCreatedTime()),
            "last_update"         => date("Y-m-d H:i:s", $node->getChangedTime()),
            "title"               => $node->label(),
            "sub_title"           => $node->field_pkt_sub_title->getString(),
            "flag"                => $node->field_pkt_flag->getString(),
            "speed"               => $node->field_pkt_speed->getString(),
            "detail_inet"         => $node->field_pkt_detail_inet->getString(),
            "detail_voice"        => $node->field_pkt_detail_voice->getString(),
            "price_voice"         => (int) $node->field_pkt_price_voice->getString(),
            "price_internet"      => (int) $node->field_pkt_price_internet->getString(),
            "price_total"         => (int) $node->field_pkt_price_total->getString(),
            "billing_period"      => $node->field_pkt_billing_period->getString(),
            "package_type"        => !empty($node->field_pkt_tipe_paket->referencedEntities()) ? $node->field_pkt_tipe_paket->referencedEntities()[0]->label() : '',
            "kuota"               => $node->field_pkt_kuota->getString(),
            "flag_json"           => $node->field_pkt_flag_json->getString(),
            "trans_type"          => $node->field_pkt_trans_type->getString(),
            "service"             => $node->field_pkt_service->getString(),
            "indihome_indicator"  => $node->field_pkt_indihome_indicator->getString(),
            "package_detail"      => $node->field_pkt_package_detail->getString()
          ]
        ];

          // try send notif 
          $success = true;
          $err_response = '';

          try{
            $response = $client->post( $_ENV['DMP_NOTIF_PACKAGE'], [
              'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
              ],
              'body' => json_encode($payload)
            ]);
          }
          catch (ClientException $e) {
            $success = false;
            $err_response = $e->getMessage();
          }
          catch(ConnectException $e){
            $success = false;
            $err_response = $e->getMessage();
          }
          catch(Exception $e){
            $success = false;
            $err_response = $e->getMessage();
          }

          // update data
          $this->conn->update('custom_cron_notify_package')
            ->fields([
              'tried'     => $data->tried+1,
              'response'  => $success ? $response->getBody() : $err_response,
              'status'    => $success ? 1 : 0,
              'updated_at'=> date("Y-m-d H:i:s")
            ])
            ->condition('id', $data->id, '=')->execute();
          
      }
      else{
        $this->conn->update('custom_cron_notify_package')
          ->fields([
            'tried'     => $data->tried+1,
            'response'  => 'not send, because invalid node_id',
            'status'    => 1,
            'updated_at'=> date("Y-m-d H:i:s")
          ])
          ->condition('id', $data->id, '=')->execute();
        continue;
      }
    }

    return new Response('success send notif');

  }

}