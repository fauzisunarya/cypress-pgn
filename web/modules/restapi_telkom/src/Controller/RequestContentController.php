<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
use Drupal;

class RequestContentController extends ControllerBase
{

  public function request(Request $request){

    if (empty($request->request->get('title'))) {
      return $this->error("Title is required");
    }
    else if (empty($request->request->get('message'))){
      return $this->error("Message is required");
    }
    else if (empty($request->request->get('callback_url'))){
      return $this->error("callback_url is required. CMS will send updated status to callback_url");
    }

    $log_status = [
      [
        'date' => \Drupal::service('date.formatter')->format(\Drupal::time()->getRequestTime(), 'medium'),
        'status' => 'pending',
        'message' => 'Create request'
      ] 
    ];

    $currentUser = \Drupal::service('restapi_telkom.app_helper')->getLoggedinUser();

    $request_node = \Drupal\node\Entity\Node::create([
      'type' => 'external_request',
      'title' => $request->request->get('title'),
      'field_req_message' => $request->request->get('message'),
      'field_req_callback_url' => $request->request->get('callback_url'),
      'field_req_referer' => $request->headers->get('referer'),
      'field_req_requester' => $currentUser['name'] . " (id {$currentUser['nid']})",
      'field_req_status' => 'pending',
      'field_req_response_log' => json_encode($log_status),
      'created' => \Drupal::time()->getRequestTime(),
      'changed' => \Drupal::time()->getRequestTime(),
      'uid' => $currentUser['nid']
    ]);
    $request_node->save();
    $request_node->field_req_request_token = $request_node->uuid();
    $request_node->save();

    $query = \Drupal::entityTypeManager()->getStorage('user')->getQuery();
    $ids = $query->condition('status', 1)->condition('roles', 'approval')->execute();
    $users = User::loadMultiple($ids);

    // notif internal & email
    $notif_to_user = [];
    $users_email = [];

    $approval = [];
    foreach ($users as $user) {
      //approval
      $users_email[] = $user->getEmail(); // email notif
      $notif_to_user[] = $user->id(); // internal notif

      $approval[] = ['target_id' => $user->id()];
    }

    // send mail to user (author, last editor, and approval)
    $email_to = array_filter(array_unique($users_email), fn($val) => !empty($val));
    $statusEmail = \Drupal::service('restapi_telkom.app_helper')->sendEmailV2(
      $email_to, 
      "Notice : Notification CMS", 
      ['body' => "<div>New request content from external \"".$request->request->get('title')."\"</div>"]
    );

    // save data notification
    $notification = Node::create([
      'type'        => 'notification',
      'title'       => "New request content from external \"".$request->request->get('title')."\"",
      'field_notif_message' => $request->request->get('message'),
      'field_notif_content' => [['target_id'=> $request_node->id()]],
      'field_notif_approval' => $approval,
      'field_notif_user' => array_map(function($id){
        return [
          'target_id' => $id
        ];
      }, array_unique($notif_to_user)),
    ]);
    $notification->save();

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status' => 'success',
      'message' => 'Success to submit request. Update status will be sent to callback_url with request_token for identifier',
      'request_status' => 'pending',
      'request_token' => $request_node->uuid(),
      'data' => [
        'title' => $request->request->get('title'),
        'message' => $request->request->get('message'),
        'callback_url' => $request->request->get('callback_url')
      ]
    ]);
  }

  public function get(Request $request, $request_token){

    $node = Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['field_req_request_token' => $request_token]);
    $node = count($node)>0 ? reset($node) : null;
    
    if (!$node) {
      return $this->error('invalid request token');
    }

    $status = $node->field_req_status->getString();

    $log_status = json_decode($node->field_req_response_log->getString(),true);

    $request_token = $node->field_req_request_token->getString();
    $request_data = [
      'title' => $node->title->getString(),
      'message' => $node->field_req_message->getString(),
      'callback_url' => $node->field_req_callback_url->getString()
    ];

    // attached content
    $attached_content = [];
    foreach ($node->field_req_attached_content->getValue() as $content) {
      $content_id = $content['target_id'];

      if (!empty($content_id)) {
        $content_node = Node::load($content_id);

        if ($content_node->type->entity->get('type')==='landing') {
          $url = Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($content_node);
        }
        else{
          $url = Drupal::service('media_upload.workflow_helper')->get_url_alias($content_id);
        }
        
        $attached_content[] = [
          'id' => $content_id,
          'uuid' => $content_node->uuid(),
          'title' => $content_node->title->getString(),
          'url' => $url
        ];
      }
    }

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status' => $status,
      'message' => 'successfully retrieve request data',
      'log' => $log_status,
      'request_token' => $request_token,
      'content' => $attached_content,
      'request_data' => $request_data,
    ]);
  }

  private function error($message){
    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status'  => 'failed',
      'message' => $message,
      'data'    => []
    ], 400);
  }

}