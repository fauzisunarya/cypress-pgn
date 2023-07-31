<?php
namespace Drupal\media_upload\Controller;

use Drupal;
use Drupal\Core\Url;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LandingMasterController {
  
  public function record(Request $request)
  {
    $imageNode = $request->files->get('files')['landing_page_logo'] ?? null;

    // validate subdomain, only if exist & unique
    $subdomain = $request->request->get('landing_subdomain') ?? '';
    $subdomain = Drupal::service('media_upload.slug_helper')->slug($subdomain);
    if (!empty($subdomain)) {
      $entity = Drupal::entityTypeManager()->getStorage('node');

      $query = $entity->getQuery()
                ->condition('type', 'landing') #type = bundle id (machine name)
                ->condition('field_lan_subdomain', $subdomain);
      $count = (int) $query->count()->execute();
      
      $subdomain = $count === 0 ? $subdomain : ''; // use new value if valid, use prev value if not valid
    }

    // validate domain, only if exist & unique
    $domain = $request->request->get('landing_domain') ?? '';
    $domain = Drupal::service('media_upload.slug_helper')->slugAllowDot($domain);
    if (!empty($domain)) {
      $entity = Drupal::entityTypeManager()->getStorage('node');

      $query = $entity->getQuery()
                ->condition('type', 'landing') #type = bundle id (machine name)
                ->condition('field_lan_domain', $domain);
      $count = (int) $query->count()->execute();
      
      $domain = $count === 0 ? $domain : ''; // use new value if valid, use prev value if not valid
    }

    // save data landing page project
    $landing = Node::create([
      'type'        => 'landing',
      'title'       => $request->request->get('landing_page_name'),
      'field_lan_website_logo' => "",
      'field_lan_landing_type' => $request->request->get('landing_page_type'),
      'field_lan_product_catalog' => array_map(function($res){
        preg_match('/\([^\d]*(\d+)[^\d]*\)/si', $res['name'], $output);
        return [
          'target_id' => (int) str_replace(['(',')'], '', $output[0])
        ];
      }, $request->request->get('landing_product')),
      'field_lan_website_description' => $request->request->get('landing_page_description'),
      'field_lan_subdomain' => $subdomain,
      'field_lan_domain' => $domain
    ]);

    // if user wants to upload logo
    if (!empty($imageNode)) :
      // upload process
      $filesaved = \Drupal::service('restapi_telkom.minio_helper')->uploadFile($imageNode, $landing->id());

      // register file uri location into landing master data
      if ($filesaved['status']) {
        $landing->field_lan_website_logo = $filesaved['data']['fileURI'];
      };
    endif;
    // save final data
    $landing->save();

    // save data pages for landing page
    $landing_page = Node::create([
      'type'                     => 'landing_page',
      'title'                    => $request->request->get('page_title'),
      'field_page_landing_id'    => $landing->id(),
      'field_page_type'          => 1,
      'field_website_page_label' => $request->request->get('page_label'),
      'field_website_page_slug'  => 'home',
      'field_website_page_description' => $request->request->get('page_description')
    ]);
    $landing_page->save();

    $url_redirect = Url::fromRoute('<front>')->setAbsolute()->toString();
    $url_redirect = $url_redirect."builder/builder.php?project=".$landing->uuid();

    \Drupal::messenger()->addStatus(t('Success created the landing page data!'));

    return new JsonResponse([
      'code'    => 200,
      'status'  => 'success',
      'message' => 'success making new landing data',
      'data'    => ['redirect' => $url_redirect]
    ]);
  }

  public function edit(Request $request, $landing_id = null)
  {
    // prepare variable
    $updated_node = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $landing_id]);
    $updated_node = current($updated_node);
    $imageNode    = $request->files->get('files')['landing_page_logo'] ?? null;

    // remapping the product catalog (only for PO & superadmin)
    $access = \Drupal::service('media_upload.landing_helper')->checkListlandingAccess();
    if ($access['access'] && $access['access_all']) {
      $productCatalog = array_map(function($res){
        preg_match('/\([^\d]*(\d+)[^\d]*\)/si', $res['name'], $output);
        return [
          'target_id' => (int) str_replace(['(',')'], '', $output[0])
        ];
      }, $request->request->get('landing_product'));
    }

    // check if user want to update landing logo / favicon
    if (!empty($imageNode)) :
      // retrieve file data
      $minioHelper = \Drupal::service('restapi_telkom.minio_helper');
      $imageURI    = $updated_node->field_lan_website_logo->getString();
      $searchData  = \Drupal::entityTypeManager()->getStorage('file')->loadByProperties(['uri' => $imageURI]);
      $fileData    = !empty($searchData) ? current($searchData) : null;

      // upload process
      $filesaved = !empty($fileData) && !empty($fileData->id()) 
        ? $minioHelper->replaceFile($fileData->id(), $imageNode) 
        : null;

      // register file uri location into landing master data
      if ($filesaved['status']) {
        $updated_node->set('field_lan_website_logo', $filesaved['data']['fileURI']);
      }
      else{
        $uploadSaved = $minioHelper->uploadFile($imageNode, $updated_node->id());
        
        if ($uploadSaved['status']) :
          $updated_node->set('field_lan_website_logo', $uploadSaved['data']['fileURI']);
        endif;
      };
    endif;

    // validate subdomain, only if exist & unique
    $subdomain = $request->request->get('landing_subdomain') ?? '';
    $subdomain = Drupal::service('media_upload.slug_helper')->slug($subdomain);
    if (!empty($subdomain)) {
      $entity = Drupal::entityTypeManager()->getStorage('node');

      $query = $entity->getQuery()
                ->condition('type', 'landing') #type = bundle id (machine name)
                ->condition('uuid', $landing_id, '<>') // exclude this landing
                ->condition('field_lan_subdomain', $subdomain);
      $count = (int) $query->count()->execute();
      
      $subdomain = $count === 0 ? $subdomain : $updated_node->field_lan_subdomain->getString(); // use new value if valid, use prev value if not valid
    }
    else{
      $subdomain = $updated_node->field_lan_subdomain->getString();
    }

    // validate domain, only if exist & unique
    $domain = $request->request->get('landing_domain') ?? '';
    $domain = Drupal::service('media_upload.slug_helper')->slugAllowDot($domain);
    if (!empty($domain)) {
      $entity = Drupal::entityTypeManager()->getStorage('node');

      $query = $entity->getQuery()
                ->condition('type', 'landing') #type = bundle id (machine name)
                ->condition('uuid', $landing_id, '<>') // exclude this landing
                ->condition('field_lan_domain', $domain);
      $count = (int) $query->count()->execute();
      
      $domain = $count === 0 ? $domain : $updated_node->field_lan_domain->getString(); // use new value if valid, use prev value if not valid
    }
    else{
      $domain = $updated_node->field_lan_domain->getString();
    }

    // build the update parameter
    $updated_query = array(
      'title'                         => $request->request->get('landing_page_name') ?? $updated_node->title(),
      'field_lan_website_logo'        => $image_url ?? $updated_node->field_lan_website_logo->getString(),
      'field_lan_landing_type'        => $request->request->get('landing_page_type'),
      'field_lan_product_catalog'     => $productCatalog ?? $updated_node->field_lan_product_catalog->getValue(),
      'field_lan_website_description' => $request->request->get('landing_page_description') ?? $updated_node->field_lan_website_description->getString(),
      'field_lan_subdomain'           => $subdomain,
      'field_lan_domain'              => $domain
    );

    foreach ($updated_query as $field => $newValue) {
      $updated_node->set($field, $newValue);
    };
    $updated_node->save();

    \Drupal::messenger()->addStatus(t('Success updated the landing page data!'));

    return new JsonResponse([
      'code'    => 200,
      'status'  => 'success',
      'message' => 'success edit landing with new data',
      'data'    => ['redirect' => Url::fromRoute('<front>')->setAbsolute()->toString() . 'landing-master']
    ]);
  }

  public function screenshotWeb(Request $request)
  {
    if (empty($request->request->get('landing_id'))) :
      return new JsonResponse([
        'code'    => 400,
        'status'  => 'failed',
        'message' => 'failed updated web preview, request not completed'
      ]);
    endif;

    // prepare variable
    $screenshot = new ScreenshootController();
    $landing_data = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties(['uuid' => $request->request->get('landing_id')]);
    $landing_data = current($landing_data);

    if (!empty($landing_data)) :
      // run screenshot library
      $result = $screenshot->run(
        Url::fromRoute('<front>')->setAbsolute()->toString() . $landing_data->field_lan_website_full->getString(), //  URL
        BASE_PATH . '/files/'.date("ymdhis").rand(10,1000).'.png' // Location
      );

      // release memory
      unset($screenshot);

      // check if user want to update landing logo / favicon
      if (!empty($result) && file_exists($result['data']['location'])) {
        // prepare variable
        $minioHelper = \Drupal::service('restapi_telkom.minio_helper');
        $existFile   = !$landing_data->field_lan_website_preview->isEmpty() ? $minioHelper->getFileByURI($landing_data->field_lan_website_preview->getString(), '', 'info') : null;

        // create mock uploaded file
        $mockRequest = new UploadedFile(
          $result['data']['location'],
          pathinfo($result['data']['location'])['basename'],
          mime_content_type($result['data']['location'])
        );

        // screenshot file is exist
        if (!empty($existFile) && $existFile['status']) {
          $uploaded = $minioHelper->replaceFile($existFile['data']['fid'], $mockRequest);
        }
        // upload a new one
        else{
          $uploaded = $minioHelper->uploadFile($mockRequest, $landing_data->id());
        };

        // release memory
        unset($minioHelper, $mockRequest, $existFile);

        // remove raw screenshot image
        @unlink($result['data']['location']);
      };

      $landing_data->set('field_lan_website_preview', !empty($uploaded['status']) ? $uploaded['data']['fileURI'] : '');
      $landing_data->setNewRevision(TRUE);
      $landing_data->revision_log = 'Revision to update landing preview for ' . $landing_data->id();
      $landing_data->setRevisionCreationTime(REQUEST_TIME);
      $landing_data->save();
    endif;

    return new JsonResponse([
      'code'    => 200,
      'status'  => !empty($landing_data) ? 'success' : 'failed',
      'message' => !empty($landing_data) ? 'success updated web preview' : 'failed, landing data not found'
    ]);
  }

  public function checkSubdomain(Request $request)
  {

    $landing_id = $request->request->get('landing_id');
    $subdomain = $request->request->get('subdomain');
    $subdomain = Drupal::service('media_upload.slug_helper')->slug($subdomain);

    if (!empty($subdomain)) {

      $entity = Drupal::entityTypeManager()->getStorage('node');

      if (!empty($landing_id)) {
        $query = $entity->getQuery()
                  ->condition('type', 'landing') #type = bundle id (machine name)
                  ->condition('uuid', $landing_id, '<>') // exclude this landing
                  ->condition('field_lan_subdomain', $subdomain);
        $count = (int) $query->count()->execute();
      }
      else {
        $query = $entity->getQuery()
                  ->condition('type', 'landing') #type = bundle id (machine name)
                  ->condition('field_lan_subdomain', $subdomain);
        $count = (int) $query->count()->execute();
      }

      return new JsonResponse([
        'code'    => $count === 0 ? 200 : 400,
        'status'  => $count === 0 ? 'success' : 'failed',
        'message' => $count === 0 ? 'subdomain is unique' : 'subdomain is already exist',
        'data'    => $count === 0 ? ['subdomain' => $subdomain] : ['subdomain' => $subdomain]
      ]);

    }

    return new JsonResponse([
      'code'    => 400,
      'status'  => 'failed',
      'message' => 'invalid request'
    ], 400);
  }

  public function checkDomain(Request $request)
  {

    $landing_id = $request->request->get('landing_id');
    $domain = $request->request->get('domain');
    $domain = Drupal::service('media_upload.slug_helper')->slugAllowDot($domain);

    if (!empty($domain)) {

      $entity = Drupal::entityTypeManager()->getStorage('node');

      if (!empty($landing_id)) {
        $query = $entity->getQuery()
                  ->condition('type', 'landing') #type = bundle id (machine name)
                  ->condition('uuid', $landing_id, '<>') // exclude this landing
                  ->condition('field_lan_domain', $domain);
        $count = (int) $query->count()->execute();
      }
      else {
        $query = $entity->getQuery()
                  ->condition('type', 'landing') #type = bundle id (machine name)
                  ->condition('field_lan_domain', $domain);
        $count = (int) $query->count()->execute();
      }

      return new JsonResponse([
        'code'    => $count === 0 ? 200 : 400,
        'status'  => $count === 0 ? 'success' : 'failed',
        'message' => $count === 0 ? 'domain is unique' : 'domain is already exist',
        'data'    => $count === 0 ? ['domain' => $domain] : ['domain' => $domain]
      ]);

    }

    return new JsonResponse([
      'code'    => 400,
      'status'  => 'failed',
      'message' => 'invalid request'
    ], 400);
  }

}