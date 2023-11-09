<?php
namespace Drupal\media_upload\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\workflow\Entity\WorkflowTransition;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

/**
 * Flow for workflow status
 * See :  Drupal\media_upload\Helper\Workflow 
 *        Drupal\media_upload\Controller\ApprovalController
 *        media_upload_node_presave in media_upload.module
 */

class ApprovalController {

  public function approve($id){
    $message = !empty($_GET['message']) ? strip_tags($_GET['message']) : '';
    $node = \Drupal\node\Entity\Node::load($id);

    if ($node===null) {
      return new JsonResponse('invalid content id', 422);
    }
    else if(!$node->hasField('field_workflow_status')){
      return new JsonResponse('invalid content id', 422);
    }

    $workflow_helper = \Drupal::service('media_upload.workflow_helper');
    $approved_id = $workflow_helper->get_approved_id_by_revision_id($node->id());
    if ($approved_id) {
      $approved_content = \Drupal\node\Entity\Node::load($approved_id);
      if ($approved_content) {
        $approved_title = $approved_content->title->getString();
        $result = $this->replace_approved_content_with_revision($approved_content, $node);
        if ($result) {
          \Drupal::messenger()->addStatus("Success replace published content \"$approved_title\" with revision data");
          return new RedirectResponse($_ENV['APP_URL'].'/approval', 301);
        }
      }
    }

    $current_sid = $node->field_workflow_status->getString();
    $current_sid = empty($current_sid) ? 'workflow_status_creation' : $current_sid;

    $transition = WorkflowTransition::create([$current_sid, 'field_name' => 'field_workflow_status']);
    $transition->setTargetEntity($node);
    $transition->setValues('workflow_status_approve', \Drupal::currentUser()->id(), \Drupal::time()->getRequestTime(), $message, TRUE);
    $transition->execute(TRUE);
    if ($tid = $transition->id()) {
      // Set the new value of the workflow field
      $node->field_workflow_status->value = 'workflow_status_approve';
      $node->setPublished();
      $node->save();
    }

    \Drupal::messenger()->addStatus('Status "'.$node->title->getString().'" changed to Approve');
    return new RedirectResponse($_ENV['APP_URL'].'/approval', 301);
  }

  public function reject($id){
    $message = !empty($_GET['message']) ? strip_tags($_GET['message']) : '';
    $node = \Drupal\node\Entity\Node::load($id);

    if ($node===null) {
      return new JsonResponse('invalid content id', 422);
    }
    else if(!$node->hasField('field_workflow_status')){
      return new JsonResponse('invalid content id', 422);
    }

    $current_sid = $node->field_workflow_status->getString();
    $current_sid = empty($current_sid) ? 'workflow_status_creation' : $current_sid;

    $transition = WorkflowTransition::create([$current_sid, 'field_name' => 'field_workflow_status']);
    $transition->setTargetEntity($node);
    $transition->setValues('workflow_status_reject', \Drupal::currentUser()->id(), \Drupal::time()->getRequestTime(), $message, TRUE);
    $transition->execute(TRUE);
    if ($tid = $transition->id()) {
      // Set the new value of the workflow field
      $node->field_workflow_status->value = 'workflow_status_reject';
      $node->save();
    }

    \Drupal::messenger()->addStatus('Status "'.$node->title->getString().'" changed to Reject');
    return new RedirectResponse($_ENV['APP_URL'].'/approval', 301);
  }

  public function replace_approved_content_with_revision($approved_content, $revision_content, $notif_to_user = true){
    if (  method_exists(get_class($approved_content), 'bundle') && 
          method_exists(get_class($revision_content), 'bundle') &&
          $approved_content->bundle() === $revision_content->bundle()
       ) {

      $entityFieldManager = \Drupal::service('entity_field.manager');
      $fields = $entityFieldManager->getFieldDefinitions('node', $approved_content->bundle());

      // get list field
      $arr_field = [];
      foreach ($fields as $field_name => $field_definition) {
        if (!method_exists(get_class($field_definition), 'isBaseField') || !$field_definition->isBaseField()) {
          $ignored_field = ['promote', 'field_workflow_status', 'field_pkt_md5_xml_paket', 'field_pkt_package_id', 'field_pkt_setting_temp_pricing', 'field_pkt_master_data'];
          if (!in_array($field_name, $ignored_field)) {
            $arr_field[] = $field_name;
          }
        }
      }
      unset($fields);

      // replace field in approved content by revision data
      if (!in_array('title', $arr_field)) {
        array_unshift($arr_field, 'title');
      }

      foreach ($arr_field as $field) {
        $approved_content->{$field} = clone $revision_content->{$field};
      }
      
      $revision_user_id = $revision_content->getRevisionUser()->id();
      $current_user = \Drupal::currentUser();

      if ($notif_to_user) {
        $this->notif_to_user($approved_content, $revision_content);
      }

      // delete revision
      $revision_content->delete();

      $approved_content->setNewRevision(TRUE);
      $approved_content->revision_log = 'Replaced with revision data. Approved by '.$current_user->getDisplayName()." (user id ".$current_user->id().")";
      $approved_content->setRevisionCreationTime(\Drupal::time()->getRequestTime());
      $approved_content->setRevisionUserId($revision_user_id);
      $approved_content->save();

      // delete link reference beetween approved and revision
      $workflow_helper = \Drupal::service('media_upload.workflow_helper');
      $revision_reference = $workflow_helper->load_revision_by_approved_id($approved_content->id());
      if (count($revision_reference)===1) {
        foreach ($revision_reference as $reference) {
          $reference->delete();
        }
      }

      return true;
    }
    return false;
  }

  private function notif_to_user($approved_content, $revision_content){
    $query = \Drupal::entityTypeManager()->getStorage('user')->getQuery();
    $ids = $query->condition('status', 1)->condition('roles', 'approval')->execute();
    $users = User::loadMultiple($ids);

    // notif internal & email
    $notif_to_user = [
      $revision_content->getOwnerId(), 
      $revision_content->getRevisionUser()->id(),
      $approved_content->getOwnerId(), 
    ];
    $users_email = [
      $revision_content->getOwner()->getEmail(), //owner
      $revision_content->getRevisionUser()->getEmail(), //last editor
      $approved_content->getOwner()->getEmail() //owner aproved content
    ];

    $approval = [];
    foreach ($users as $user) {
      //approval
      // $users_email[] = $user->getEmail(); // email notif
      // $notif_to_user[] = $user->id(); // internal notif

      $approval[] = ['target_id' => $user->id()];
    }

    // send mail to user (author, last editor, and approval)
    $notif_title = "Replaced ".$approved_content->title->getString()." by newest revision";
    $email_to = array_filter(array_unique($users_email), fn($val) => !empty($val));
    $statusEmail = \Drupal::service('restapi_telkom.app_helper')->sendEmailV2(
      $email_to, 
      "Notice : Notification CMS", 
      ['body' => "<div>$notif_title</div>"]
    );

    // save data notification
    $notification = Node::create([
      'type'        => 'notification',
      'title'       => $notif_title,
      'field_notif_message' => $_GET['message'] ?? '',
      'field_notif_content' => [['target_id'=> $approved_content->id()]],
      'field_notif_approval' => $approval,
      'field_notif_user' => array_map(function($id){
        return [
          'target_id' => $id
        ];
      }, array_unique($notif_to_user)),
    ]);
    $notification->save();
  }

  // Reject external request
  // Note : process,update,done request is processed in media_upload_node_presave
  public function rejectRequest(Request $request, $id){
    $message = $request->query->get('message');
    $node = \Drupal\node\Entity\Node::load($id);

    if ($node===null) {
      return new JsonResponse('invalid content id', 422);
    }
    else if($node->gettype()!=='external_request'){
      return new JsonResponse('invalid content id', 422);
    }

    $status = 'reject';
    $prev_status = $node->field_req_status->getString();
    $node->field_req_status = $status;
    
    $log_status = json_decode($node->field_req_response_log->getString(),true);
    array_unshift($log_status, [
      'date' => \Drupal::service('date.formatter')->format(\Drupal::time()->getRequestTime(), 'medium'),
      'status' => 'reject',
      'message' => $message
    ]);
    $node->field_req_response_log = json_encode($log_status);

    $request_token = $node->field_req_request_token->getString();
    $request_data = [
      'title' => $node->title->getString(),
	    'message' => $node->field_req_message->getString(),
	    'callback_url' => $node->field_req_callback_url->getString()
    ];
    
    $node->save();

    $client = new Client();

    try {   
      $response = $client->post($request_data['callback_url'], [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json',
        ],
        'body' => json_encode([
          'status' => $status,
          'message' => $message,
          'log' => $log_status,
          'request_token' => $request_token,
          'content' => [],
          'request_data' => $request_data 
        ])
      ]);
    }
    catch (ClientException $e) {
      // do something ?
    }
    catch(ConnectException $e){
      // do something ?
    }

    $redirect_url = \Drupal::service('media_upload.workflow_helper')->get_url_alias($node->id());
    \Drupal::messenger()->addStatus(t('External Request <a href=":url">:title</a> has been updated.', [':url' => $redirect_url, ':title'=> $node->title->getString()]));
    return \Drupal::service('media_upload.page_helper')->redirect($redirect_url);
    
  }

  public function approve_all(){
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()
                  ->condition('type', 'content_revision') #type = bundle id (machine name)
                  ->execute();

    $revision_link = $entity->loadMultiple($ids);

    // replace approved content with revision
    foreach ($revision_link as $revision) {
      $rev_id = $revision->field_cr_revision_content_id->getString();
      $app_id = $revision->field_cr_approved_content_id->getString();

      $rev_node = \Drupal\node\Entity\Node::load($rev_id);
      $app_node = \Drupal\node\Entity\Node::load($app_id);
      if ($rev_node!==null && $app_node!==null) {
        if ($rev_node->field_workflow_status->getString()!=='workflow_status_reject') {
          // ignore for rejected revision
          $this->replace_approved_content_with_revision($app_node, $rev_node, false);
        }
      }
    }

    // change all to approve (except for already approved/rejected)
    $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();

    $or_condition = $query->orConditionGroup()
                  ->condition('field_workflow_status', ['workflow_status_reject','workflow_status_approve'], 'NOT IN')
                  ->notExists('field_workflow_status'); //if empty field
    $ids          = $query
                  ->condition($or_condition)
                  ->execute();

    $all_node = $entity->loadMultiple($ids);
    foreach ($all_node as $node) {
        $node->field_workflow_status = 'workflow_status_approve';
        $node->setPublished();
        $node->save();
    }

    return new JsonResponse('oke');
  }
}