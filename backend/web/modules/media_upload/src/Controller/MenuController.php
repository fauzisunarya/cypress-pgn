<?php
namespace Drupal\media_upload\Controller;

use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Menu for landing page
 */
class MenuController {

  /**
   * Process menu update
   */
  public function update(Request $request) {

    // get the site id and load the landing page project
    $uuid = $_POST['site_id'];
    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $uuid]);
    $landing = reset($landing);

    $check_access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ( ! $check_access['access'] ) {
      return new JsonResponse('Not allowed, not your landing', 403);
    }

    // update menu field in $landing
    $landing->field_lan_website_menu = $_POST['menus'];
    $landing->save();

    $return = [
      'status' => true,
      'message' => 'success',
      'menu' => json_decode($_POST['menus'], true)
    ];

    return new JsonResponse($return);
  }


}