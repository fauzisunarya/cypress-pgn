<?php
namespace Drupal\media_upload\Controller;

use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Process file uploads.
 */
class FileUploadController {

  /**
   * Process posted files (image)
   * 
   * This function is used for uploding image for landing & template content type
   * 
   * for landing, uploaded image will be created as new node (content type = paket_media). Each node has 1 image.
   * The node will be referenced to Paket in field "field_media_paket_ref". The list image will be called from media endpoint
   * 
   * for template, uploaded image will be processed same as landing. The difference is field "field_media_paket_ref" is empty.
   * So, The list image will be called from image-asset endpoint as global asset. global asset = node paket_media with empty paket reference
   */
  public function create(Request $request) {
    if (strpos($request->headers->get('Content-Type'), 'multipart/form-data;') !== 0) {
      $res = new JsonResponse();
      $res->setStatusCode(400, 'must submit multipart/form-data');
      return $res;
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    // get the file data
    $imageUpload = $request->files->get('image');

    // get the project uuid
    $http_referer = $_SERVER['HTTP_REFERER']; //ex: http://localhost/drupal/builder/builder.php?project=uuid
    $project_uuid = explode('project=',$http_referer)[1];

    // get the project landing page
    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $project_uuid]);
    $landing = reset($landing);

    // there is "template" & "landing" project that use the same page builder for editing page
    // in builder, "template" & "landing", saved the uploaded image as global image (doesn't have paket_id)

    // create node "Paket Media" with attached file id
    // without paket reference to become global image
    $paket_media = Node::create([
      'type'        => 'paket_media',
      'title' => 'Global Image Asset', 
      'field_media_description' => '',
      'field_media_image'       => [],
      'field_workflow_status' => 'workflow_status_approve'
    ]);

    // upload process
    $filesaved = \Drupal::service('restapi_telkom.minio_helper')->uploadFile($imageUpload, $paket_media->id());

    // register file uri location into paket_media master data
    if ($filesaved['status']) {
      $paket_media->set('field_media_image', [
        [
          'target_id' => $filesaved['data']['fid'],
          'alt' => 'Image'
        ]
      ]);
    };

    // save latest paket media data
    $paket_media->save();

    // get rendered image
    // $findOrig  = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($filesaved['data']['fileURI'], 'original');
    // $findThumb = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($filesaved['data']['fileURI'], 'thumbnail');
    $image_url = "{$_ENV['APP_URL']}/restapi/v1/media_render/{$paket_media->field_media_image->referencedEntities()[0]->uuid()}";

    // set the uploaded image detail
    $image = [
      'id' => $filesaved['data']['fid'],
      'filename' => $filesaved['data']['filename'],
      'uri' => $image_url,//$findOrig['status'] ? $findOrig['data'] : $filesaved['data']['fileURI'],
      'thumbnail' => $image_url,//$findThumb['status'] ? $findThumb['data'] : $filesaved['data']['fileURI'],
      'filesize' => $filesaved['data']['filesize'],
      'date' => $filesaved['data']['created_date']
    ];

    return new JsonResponse([
      'status' => true,
      'message' => 'complete',
      'content' => [$image]
    ]);
  }


}