<?php
namespace Drupal\media_upload\Controller;

use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Landing Page Saved Blocks
 * Saved Blocks can use globally in another landing page
 */
class SavedBlocksController {

  /**
   * Process save blocks
   */
  public function save() {

    if (empty($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], 'project') === false)) {
      return new JsonResponse('invalid referer', 403);
    }

    $project_id = preg_replace('/.*project=/is', '', $_SERVER['HTTP_REFERER']);

    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $project_id]);
    $landing = current($landing);

    $access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ($access['access'] && $access['access_all']) {
      $user_id = null;
    }
    else if($access['access']){
      $user_id = $access['user_id'];
    }
    else {
      return new JsonResponse('not allowed, not your landing', 403);
    }

    // save data pages for landing page
    $landing_saved_blocks = Node::create([
      'type'        => 'landing_saved_blocks',
      'title' => $_POST['title'],
      'field_sb_blocks' => $_POST['blocks'],
      'field_sb_owner' => $user_id
    ]);
    $landing_saved_blocks->save();

    $return = [
      'status' => true,
      'message' => 'saved',
      'id' => $landing_saved_blocks->id()
    ];

    return new JsonResponse($return);
  }

  /**
   * Get the list saved blocks
   */
  public function get() {

    if (empty($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], 'project') === false)) {
      return new JsonResponse('invalid referer', 403);
    }

    $project_id = preg_replace('/.*project=/is', '', $_SERVER['HTTP_REFERER']);

    // load the saved blocks for the landing page
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $project_id]);
    $landing = current($landing);
    $access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ($access['access'] && $access['access_all']) {
      // access all saved blocks
      $ids = $query->condition('status', 1)
              ->condition('type', 'landing_saved_blocks')#type = bundle id (machine name)
              ->notExists('field_sb_owner')
              ->execute();
    }
    else if($access['access']){
      // access own saved blocks
      $ids = $query->condition('status', 1)
              ->condition('type', 'landing_saved_blocks')#type = bundle id (machine name)
              ->condition('field_sb_owner', $access['user_id'])
              ->execute();
    }
    else {
      $ids = [];
    }

    $landing_saved_blocks = $entity->loadMultiple($ids);

    // define the result data
    $return = [
      'statue' => true,
      'message' => 'complete',
      'content' => []
    ];

    foreach ($landing_saved_blocks as $template_id => $saved_block) {
      $return['content'][] = [
        'id' => $saved_block->id(),
        'name' => $saved_block->title->getString(),
        'blocks' => json_decode($saved_block->field_sb_blocks->getString(), true),
      ];
    }

    return new JsonResponse($return);
  }

  public function delete($block_id) {
    
    if (empty($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], 'project') === false)) {
      return new JsonResponse('invalid referer', 403);
    }

    $project_id = preg_replace('/.*project=/is', '', $_SERVER['HTTP_REFERER']);

    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $project_id]);
    $landing = current($landing);

    $access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ($access['access']) {
      $saved_block = \Drupal::entityTypeManager()->getStorage('node')->load($block_id);
      $saved_block->delete();
    }
    else{
      return new JsonResponse('forbidden, not your landing', 403);
    }

    $return = [
      'status' => true,
      'message' => 'Complete',
      'content' => 'Block Deleted'
    ];

    return new JsonResponse($return);
  }
}