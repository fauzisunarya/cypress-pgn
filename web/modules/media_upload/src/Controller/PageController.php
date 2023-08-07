<?php
namespace Drupal\media_upload\Controller;

use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Process Page in landing page
 */
class PageController {

  /**
   * Process add page in landing page.
   */
  public function create(Request $request) {
    /**
     * content type "landing" = project landing page, content type "landing_page" = list page in the landing page
     */
    if ($_POST['is_template']==="false") {
      
      $uuid = $_POST['site_id'];
      $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $uuid]);
      $landing = reset($landing);

      $check_access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
      if ( ! $check_access['access'] ) {
        return new JsonResponse('Not allowed, not your landing', 403);
      }

      // there is "template" & "landing" project that use the same page builder for editing page
      // if "template", process as template
      if ($landing->type->entity->get('type')==='template') {

        // create page for 'template' content type
        $landing_page = Node::create([
          'type'        => 'template_page', // this is the difference
          'field_tem_page_template_id' => $landing->id(),
          'field_tem_page_type' => 0, // 0 = normal page. See in content type "template_page" field_tem_page_type description
          'field_tem_page_label' => $_POST['label'],
          'title' => $_POST['title'],
          'field_tem_page_description' => $_POST['description'],
          'field_tem_page_slug' => $_POST['slug'],
          'field_tem_page_blocks' => $_POST['blocks'],
          'field_tem_page_image_link' => '', //this is for page screenshoot
        ]);

      }
      else{

        // create page for 'landing' content type
        $landing_page = Node::create([
          'type'        => 'landing_page', // this is the difference
          'field_page_landing_id' => $landing->id(),
          'field_page_type'       => 0, // 0 = normal page. See in content type "landing_page" field_page_type description
          'field_website_page_label' => $_POST['label'],
          'title' => $_POST['title'],
          'field_website_page_description' =>  $_POST['description'],
          'field_website_page_slug' =>  $_POST['slug'],
          'field_website_page_component' => $_POST['blocks']
        ]);

      }

      $landing_page->save();

      $return = [
        'status' => true,
        'message' => 'saved',
        'slug' => $_POST['slug'],
        'id' => (int) $landing_page->id()
      ];

      return new JsonResponse($return);
    }
    return new JsonResponse("oke");
  }

  public function delete($page_id) {
    $landing_page = \Drupal::entityTypeManager()->getStorage('node')->load($page_id);

    $landing = Node::load($landing_page->field_page_landing_id->getString());
    $check_access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ( ! $check_access['access'] ) {
      return new JsonResponse('Not allowed, not your landing', 403);
    }

    $landing_page->delete();

    $return = [
      'status' => true,
      'message' => 'success'
    ];

    return new JsonResponse($return);
  }

  public function updateHomepage() {
    $site_id = $_POST['site_id'];
    $homepage_id = (int) $_POST['page_id'];

    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $site_id]);
    $landing = reset($landing);

    $check_access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ( ! $check_access['access'] ) {
      return new JsonResponse('Not allowed, not your landing', 403);
    }

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    // there is "template" & "landing" project that use the same page builder for editing page
    // if "template", process as template
    if ($landing->type->entity->get('type')==='template') {
      $ids = $query->condition('status', 1)
        ->condition('type', 'template_page')#type = bundle id (machine name)
        ->condition('field_tem_page_template_id', $landing->id()) //list page for 'template' page
        #->sort('created', 'ASC') #sorted
        #->pager(15) #limit 15 items
        ->execute();

      $pages_arrobj = $entity->loadMultiple($ids);

      foreach ($pages_arrobj as $page_id => $page_obj) {
        if ( (int)$page_id===$homepage_id ) {
          $page_obj->field_tem_page_type = 1; // 1 = homepage, for detail: see content type "landing_page" field_page_type description
        }
        else{
          $page_obj->field_tem_page_type = 0; // 0 = normal page
        }
        $page_obj->save();
      }
    }
    else{
      $ids = $query->condition('status', 1)
        ->condition('type', 'landing_page')#type = bundle id (machine name)
        ->condition('field_page_landing_id', $landing->id()) //list page for landing page
        #->sort('created', 'ASC') #sorted
        #->pager(15) #limit 15 items
        ->execute();

      $pages_arrobj = $entity->loadMultiple($ids);
  
      foreach ($pages_arrobj as $page_id => $page_obj) {
        if ( (int)$page_id===$homepage_id ) {
          $page_obj->field_page_type = 1; // 1 = homepage, for detail: see content type "landing_page" field_page_type description
        }
        else{
          $page_obj->field_page_type = 0; // 0 = normal page
        }
        $page_obj->save();
      }
    }


    $return = [
      'status' => true,
      'message' => 'updated'
    ];

    return new JsonResponse($return);
  }
}