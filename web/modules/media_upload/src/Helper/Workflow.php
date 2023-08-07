<?php

namespace Drupal\media_upload\Helper;

use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\node\Entity\Node;

/**
 * Flow for workflow status
 * See :  Drupal\media_upload\Helper\Workflow 
 *        Drupal\media_upload\Controller\ApprovalController
 *        media_upload_node_presave in media_upload.module
 */

class Workflow {
  /**
   * redirect node/id/edit to node/newest_revision_id/edit if node/id is already approved
   * 
   * this is to protect published content from being edit. 
   * 
   * If content revision alredy approved, it will replace the published content
   */
  public function check_edit_revision(){
  
    // check if in edit page & user is not admin (only admin can change published data)
    $list_checked = [
      'citem' => "/.*citem\/\d+\/edit.*/i",
      'ebis' => "/.*ebis\/\d+\/edit.*/i",
      'wibs' => "/.*wibs\/\d+\/edit.*/i",
      'paket' => "/.*paket\/\d+\/edit.*/i",
      'node' => "/.*node\/\d+\/edit.*/i", //general edit page
    ];

    $route_match = false;
    $edit_page_name = ''; 
    foreach ($list_checked as $key => $regex_path) {
      if (preg_match($regex_path, $_SERVER['REQUEST_URI'])) {
        $route_match = true;
        $edit_page_name = $key;
        break;
      }
    }

    if($route_match && !in_array('administrator',\Drupal::currentUser()->getRoles())){

      $current_id = explode('/edit', explode("$edit_page_name/",$_SERVER['REQUEST_URI'])[1])[0];

      // check if have revision. content that have revision only if status = approved
      $revision_id = $this->get_revision_id_by_approved_id($current_id);
      
      if (!empty($revision_id)) {
        // redirect to edit revision
        return $this->redirect_to_revision($revision_id);
      }
      else {
        $current_content = \Drupal::entityTypeManager()->getStorage('node')->load($current_id);

        if ($current_content !== null && $current_content->hasField('field_workflow_status')) {
          // duplicate content (for revision) if already approve, except for landing (ignored)
          if ($current_content->field_workflow_status->getString() === 'workflow_status_approve' && $current_content->type->entity->get('type')!=='landing') {
            // clone the current content, set status to revision, and assign to content_revision (for linking)
            $storage = \Drupal::entityTypeManager()->getStorage('node');

            $original_values = $current_content->toArray();
            // assign content type as string, the array causes an error when creating a new node
            $original_values['type'] = $current_content->bundle();

            // remove nid and uuid, the cloned node is assigned new ones when saved
            unset($original_values['nid']);
            unset($original_values['uuid']);

            // remove revision data, the latest revision becomes the only one in the new node
            unset($original_values['vid']);
            unset($original_values['revision_translation_affected']);
            unset($original_values['revision_uid']);
            unset($original_values['revision_log']);
            unset($original_values['revision_timestamp']);

            $cloned_content = $storage->create($original_values);
            $cloned_content->field_workflow_status = 'workflow_status_revision'; //set status to revision
            $cloned_content->uid = \Drupal::currentUser()->id();
            $cloned_content->created = \Drupal::time()->getRequestTime();
            $cloned_content->changed = \Drupal::time()->getRequestTime();
            $cloned_content->save();

            // linking the revision with current published content
            $linking = Node::create([
              'type'        => 'content_revision',
              'title' => 'Add revision (id '.$cloned_content->id().') for approved content id '. $current_content->id(),
              'field_cr_approved_content_id' => $current_content->id(),
              'field_cr_revision_content_id' => $cloned_content->id(),
            ]);
            $linking->save();

            // redirect to edit revision
            return $this->redirect_to_revision($cloned_content->id());
          }
        }
      }
    }
  }

  public function redirect_to_revision($revision_id){
    $node = Node::load($revision_id);
    if ($node!==null) {
      if ($node->gettype()==='citem') {
        $redirect_url = getenv('APP_URL') . preg_replace("/citem\/\d+\/edit/i", "citem/$revision_id/edit", $_SERVER['REQUEST_URI']);
      }
      else if ($node->gettype()==='ebis') {
        $redirect_url = getenv('APP_URL') . preg_replace("/ebis\/\d+\/edit/i", "ebis/$revision_id/edit", $_SERVER['REQUEST_URI']);
      }
      else if ($node->gettype()==='wibs') {
        $redirect_url = getenv('APP_URL') . preg_replace("/wibs\/\d+\/edit/i", "wibs/$revision_id/edit", $_SERVER['REQUEST_URI']);
      }
      else if ($node->gettype()==='paket') {
        $redirect_url = getenv('APP_URL') . preg_replace("/paket\/\d+\/edit/i", "paket/$revision_id/edit", $_SERVER['REQUEST_URI']);
      }
      else{
        $redirect_url = getenv('APP_URL') . preg_replace("/node\/\d+\/edit/i", "node/$revision_id/edit", $_SERVER['REQUEST_URI']);
      }
      
      $response_headers = [
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
      ];
    
      $response = new TrustedRedirectResponse($redirect_url, 301, $response_headers);
      $response->send();exit;
    }
  }

  public function load_revision_by_approved_id($approved_id){
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()
                  ->condition('type', 'content_revision') #type = bundle id (machine name)
                  ->condition('field_cr_approved_content_id', $approved_id, 'IN')
                  ->sort('created' , 'DESC')
                  ->range(0, 1)
                  ->execute();

    return $entity->loadMultiple($ids);
  }

  public function load_revision_by_revision_id($revision_id){
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()
                  ->condition('type', 'content_revision') #type = bundle id (machine name)
                  ->condition('field_cr_revision_content_id', $revision_id, 'IN')
                  ->sort('created' , 'DESC')
                  ->range(0, 1)
                  ->execute();

    return $entity->loadMultiple($ids);
  }

  public function get_revision_id_by_approved_id($approved_id){
    $content_revision = $this->load_revision_by_approved_id($approved_id);
    $revision_id = '';
    if (count($content_revision)===1) {
      foreach ($content_revision as $revision) {
        $id = $revision->field_cr_revision_content_id->getString();
        if (Node::load($id)===null) {
          $revision->delete();
        }
        else{
          $revision_id = $id;
        }
      }
    }
    return $revision_id!=='' ? (int)$revision_id : '';
  }

  public function get_approved_id_by_revision_id($revision_id){
    $content_revision = $this->load_revision_by_revision_id($revision_id);
    $approved_id = '';
    if (count($content_revision)===1) {
      foreach ($content_revision as $revision) {
        $id = $revision->field_cr_approved_content_id->getString();
        if (Node::load($id)===null) {
          $revision->delete();
        }
        else{
          $approved_id = $id;
        }
      }
    }
    return $approved_id!=='' ? (int)$approved_id : '';
  }

  public function get_url_revision_content_by_approved_id($approved_id){
    $revision_id = $this->get_revision_id_by_approved_id($approved_id);
    return $this->get_url_alias($revision_id);
  }

  public function get_url_approved_content_by_revision_id($revision_id){
    $approved_id = $this->get_approved_id_by_revision_id($revision_id);
    return $this->get_url_alias($approved_id);
  }

  public function get_url_alias($node_id){
    return (int)$node_id ? $_ENV['APP_URL'] . \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$node_id) : '';
  }

}