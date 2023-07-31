<?php
namespace Drupal\media_upload\Controller;

use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Landing Page Saved Templates
 * saved templates is attached to landing page
 * load after save templates (function get) or for the first time when load page builder (data is in property user_templates)
 * Changed: saved templates can use globally (in another landing page), field_st_paket_ref will be ignored. see Drupal\media_upload\Plugin\rest\resource\RestProjectPageBuilder
 */
class SavedTemplatesController {

  /**
   * Process save templates for landing page (saved templates is attached to landing page)
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
    
    // save data templates for landing page
    $landing_saved_templates = Node::create([
      'type'        => 'landing_saved_templates',
      //'field_st_paket_ref' => $landing->id(), //this field will be ignored when get data. saved templates can use globally in another landing. see desctription above
      'title' => $_POST['title'],
      'field_st_thumbnail'       => null,
      'field_st_blocks' => $_POST['blocks'],
      'field_st_owner' => $user_id
    ]);
    $landing_saved_templates->save();

    $return = [
      'status' => true,
      'message' => 'saved',
      'id' => $landing_saved_templates->id()
    ];

    return new JsonResponse($return);
  }

  /**
   * Get the list saved templates for landing page (saved templates is attached to landing page)
   */
  public function get() {

    if (empty($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], 'project') === false)) {
      return new JsonResponse('invalid referer', 403);
    }

    $project_id = preg_replace('/.*project=/is', '', $_SERVER['HTTP_REFERER']);

    // load the saved templates for the landing page
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $project_id]);
    $landing = current($landing);
    $access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ($access['access'] && $access['access_all']) {
      // access all saved blocks
      $ids = $query->condition('status', 1)
              ->condition('type', 'landing_saved_templates')#type = bundle id (machine name)
              ->notExists('field_st_owner')
              ->execute();
    }
    else if($access['access']){
      // access own saved blocks
      $ids = $query->condition('status', 1)
              ->condition('type', 'landing_saved_templates')#type = bundle id (machine name)
              ->condition('field_st_owner', $access['user_id'])
              ->execute();
    }
    else {
      $ids = [];
    }

    $saved_templates = $entity->loadMultiple($ids);

    // define the result data
    $return = [
      'statue' => true,
      'message' => 'complete',
      'content' => []
    ];

    foreach ($saved_templates as $template_id => $saved_template) {
      $return['content'][] = [
        'id' => $saved_template->id(),
        'name' => $saved_template->title->getString(),
        'thumbnail' => $saved_template->field_st_thumbnail->getString(),
        'blocks' => json_decode($saved_template->field_st_blocks->getString(), true),
      ];
    }

    return new JsonResponse($return);
  }

  public function delete($template_id) {

    if (empty($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], 'project') === false)) {
      return new JsonResponse('invalid referer', 403);
    }

    $project_id = preg_replace('/.*project=/is', '', $_SERVER['HTTP_REFERER']);

    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $project_id]);
    $landing = current($landing);

    $access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ($access['access']) {
      $saved_template = \Drupal::entityTypeManager()->getStorage('node')->load($template_id);
      $saved_template->delete();
    }
    else{
      return new JsonResponse('forbidden, not your landing', 403);
    }

    $return = [
      'status' => true,
      'message' => 'Complete',
      'content' => 'Template Deleted'
    ];

    return new JsonResponse($return);
  }
}