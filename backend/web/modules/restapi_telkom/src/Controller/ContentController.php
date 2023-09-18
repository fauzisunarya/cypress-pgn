<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class ContentController extends ControllerBase{

   public function list(Request $request)
   {
      $result = [];
      $entity = Drupal::entityTypeManager()->getStorage('node');

      $search_query = $request->query->get('search');
      $perpage = $request->query->get('perpage') ?? 10;
      $page = $request->query->get('page') ?? 1;

      $query = $entity
         ->getQuery()
         ->condition('status', 1)
         ->condition('type', ['news','article'], 'IN')
         ->condition('title', "%{$search_query}%", 'LIKE');

      if(!empty($request->query->get('startDate'))){
         $query->condition('created',strtotime($request->query->get('startDate')),'>=');
      }
      if(!empty($request->query->get('endDate')) && !empty($request->query->get('startDate'))){
         $query->condition('created',strtotime($request->query->get('endDate'))+86400,'<='); 
      }
      elseif (!empty($request->query->get('endDate')) && empty($request->query->get('startDate'))) {
         return Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'Start Date cannot be empty when end date is settled',
            'data'    => []
         ], 400);
      };

      $raw_query = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', (String)$query);
      $raw_total = Drupal::database()->query($raw_query)->fetchObject();

      $datas = $query->range((($page * $perpage) - $perpage), $perpage)->execute();

      foreach ($entity->loadMultiple($datas) as $entity_id => $entity_obj) {
         $result[] = array(
            'uuid' => $entity_obj->uuid(),
            'name' => $entity_obj->label(),
            'module' => 'content',
            'status' => $entity_obj->isPublished() ? 'published' : 'hidden',
            'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         );
      }
      
      return Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data available',
         'pagination' => [
            'total_page' => (int) ceil($raw_total->total / $perpage),
            'total_data' => (int) $raw_total->total,
            'perpage' => (int) $perpage,
            'page'    => (int) $page
         ],
         'data'    => $result
      ]);
   }

   public function detail($content_id, Request $request)
   {
      if (empty($content_id)) {
         return Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'landing page id cannot be empty',
            'data'    => []
         ], 400);
      };

      $result = [];
      $fileHelper = Drupal::service('file_url_generator');
      $entity     = Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $content_id]);

      if (!empty($entity)) :
         $entity_obj = current($entity);
         $imageData  = $entity_obj->getType() === 'article' ? $entity_obj->get('field_image')->referencedEntities() : $entity_obj->get('field_news_image')->referencedEntities();

         $result = array(
            'uuid' => $entity_obj->uuid(),
            'name' => $entity_obj->label(),
            'lang' => $entity_obj->langcode->getString(),
            'module' => $entity_obj->getType(),
            'content_body'  => $entity_obj->body->getValue(),
            'content_image' => array_map(function($res) use ($fileHelper) {
               $imageLink = $res->uri->getString();

               if (str_contains($imageLink, 's3')) {
                  $findS3 = Drupal::service('restapi_telkom.minio_helper')->getFileByURI($imageLink, 'original');

                  $displayImg = $findS3['status'] ? $findS3['data'] : $imageLink;
               }
               else {
                  $displayImg = Drupal::request()->getSchemeAndHttpHost() . $fileHelper->generateString($imageLink);
               };

               return [
                  'uuid' => $res->uuid(),
                  'filename' => $res->filename->getString(),
                  'mimeType' => $res->filemime->getString(),
                  'location' => $displayImg
               ];
            }, $imageData),
            'status'       => $entity_obj->isPublished() ? 'published' : 'hidden',
            'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         );
      endif;

      return Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data related to selected paket id',
         'data'    => $result
      ]);
   }

}