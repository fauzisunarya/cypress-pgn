<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends ControllerBase{

   public function paket_media(Request $request)
   {
      // prepare variable
      $result        = [];
      $entity        = \Drupal::entityTypeManager()->getStorage('node');
      $modules_query = $request->query->get('module') ? $request->query->get('module') : 'media';
      $search_query  = $request->query->get('search') ?? '';
      $perpage       = $request->query->get('perpage') ?? 10;
      $page          = $request->query->get('page') ?? 1;

      // validate module
      if (!in_array($modules_query, ['media','video','all']) ) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'Modules cannot be accepted. only media, video and all currently available',
            'data'    => []
         ], 400);
      };

      // initialize query
      $query = $entity->getQuery()->condition('status', 1)
         ->condition('type', ($modules_query === 'all' ? ['paket_media','paket_video'] : ["paket_{$modules_query}"]), 'IN')
         ->condition('title', "%{$search_query}%", 'LIKE');

      // validate date
      if(!empty($request->query->get('startDate'))){
         $query->condition('created',strtotime($request->query->get('startDate')),'>=');
      }
      if(!empty($request->query->get('endDate')) && !empty($request->query->get('startDate'))){
         $query->condition('created',strtotime($request->query->get('endDate'))+86400,'<='); 
      }elseif (!empty($request->query->get('endDate')) && empty($request->query->get('startDate'))) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'Start Date cannot be empty when end date is settled',
            'data'    => []
         ], 400);
      };

      $raw_query = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', (String)$query);
      $raw_total = \Drupal::database()->query($raw_query)->fetchObject();

      $datas = $query->range((($page * $perpage) - $perpage), $perpage)->execute();

      foreach ($entity->loadMultiple($datas) as $entity_id => $entity_obj) {
         // prepare variable case
         $selector     = $entity_obj->getType() === 'paket_media' ? 'media' : 'video';
         $media_list   = $selector === 'media' ? 'field_media_image' : 'field_video_file_video';
         $media_iframe = ($selector === 'video' && !$entity_obj->get('field_video_youtube_video')->isEmpty()) ? $entity_obj->get('field_video_youtube_video')->getString() : '';
         $refer_detail = current($entity_obj->get("field_{$selector}_paket_ref")->referencedEntities());

         $result[] = array(
            'uuid'     => $entity_obj->uuid(),
            'name'     => $entity_obj->label(),
            'type'     => $entity_obj->getType() === 'paket_media' ? 'media' : 'video',
            'category' => implode(',', array_map(function($res){
               return $res->getName();
            }, $entity_obj->get("field_{$selector}_category")->referencedEntities())),
            'paket_ref' => !empty($refer_detail) ? [
               'uuid' => $refer_detail->uuid(),
               'name' => $refer_detail->label(),
               'module' => $refer_detail->bundle()
            ] : [],
            'media_list' => array_map(function($res) {
               return [
                  'uuid'     => $res->uuid(),
                  'filename' => $res->filename->getString(),
                  'mimeType' => $res->filemime->getString(),
                  'location' => "{$_ENV['APP_URL']}/restapi/v1/media_render/{$res->uuid()}"
               ];
            }, $entity_obj->get($media_list)->referencedEntities()),
            'media_iframe' => str_replace('"', "'", $media_iframe),
            'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         );
      }
      
      return \Drupal::service('restapi_telkom.app_helper')->response([
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

   public function media_bundle(Request $request)
   {
      // prepare variable
      $result        = array();
      $app_helper    = \Drupal::service('restapi_telkom.app_helper');
      $search_method = $request->query->get('search_method'); 
      $search_query  = $request->query->get('search_value');
      $category_operator = $request->query->get('category_operator');

      // core query data
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery()
         ->condition('status', 1)
         ->condition('type', 'paket_media_bundle');

      // building core query
      if (!empty($search_method) || !empty($search_query)) :
         if (empty($search_query) OR empty($search_method) OR !in_array($search_method, ['title','uuid','category'])) {
            return \Drupal::service('restapi_telkom.app_helper')->response([
               'status'  => 'failed',
               'message' => 'request parameter not valid',
               'data'    => []
            ], 400);
         };

         if ($search_method === 'category') {
            $termList = \Drupal::entityTypeManager()
               ->getStorage('taxonomy_term')
               ->loadByProperties(['name' => explode('|', $search_query), 'vid' => 'media_bundle']);

            // search whitelist
            $termSearch = !empty($termList) ? array_map(fn($term) => $term->id(), $termList) : array(0);
            
            // load data based on category
            $query->condition('field_pmb_category', $termSearch, 'IN');
            $loadedData = $entity->loadMultiple($query->execute());

            // filter by category parameter "AND" (the previous data is "OR" condition)
            if (!empty($category_operator) && $category_operator==='and' && count($termSearch)>1) {

               $loadedData = array_filter($loadedData, function($data) use($termSearch){
                  $data_category = array_map(fn($value)=> $value->id(), $data->field_pmb_category->referencedEntities());
                  return count(array_intersect($termSearch, $data_category)) === count($termSearch) ;
               });
            }

            // release memory
            unset($termList, $termSearch);
         }
         else{
            // insert new condition for search data
            $query->condition($search_method, $search_query, 'LIKE');
            // rebuild the query suitable when user is input multiple
            $raw_query  = preg_replace('/LIKE(.*)ESCAPE.*\)/im', "REGEXP '{$search_query}')", (String)$query);
            // retrieve all suitable data
            $raw_result = \Drupal::database()->query($raw_query)->fetchAll();
            // load data based on node_id
            $loadedData = $entity->loadMultiple(array_column($raw_result, 'nid'));

            // release memory
            unset($raw_query, $raw_result);
         };
      else:
         // load data based on query
         $loadedData = $entity->loadMultiple($query->execute());
      endif;

      // release memory
      unset($query, $entity);

      // mapping data
      foreach ($loadedData as $entity_id => $entity_obj) :
         $categoryList = !$entity_obj->get('field_pmb_category')->isEmpty() ? $entity_obj->get('field_pmb_category')->referencedEntities() : array();
         $imageList = !$entity_obj->get('field_pmb_media_ref')->isEmpty() ? $entity_obj->get('field_pmb_media_ref')->referencedEntities() : array();

         $result[] = array(
            'id'         => (int) $entity_obj->id(),
            'uuid'       => $entity_obj->uuid(),
            'title'      => $entity_obj->label(),
            'category'   => !empty($categoryList) ? implode(',', array_map(fn($cat) => $cat->label(), $categoryList)) : '',
            'image_list' => !empty($imageList) ? array_map(function($res) {
               $imageId   = $res->field_media_image->getValue()[0]['target_id'];
               $findImage = \Drupal::service('restapi_telkom.minio_helper')->getFileById($imageId, '', 'info');

               return [
                  'id'         => $res->id(),
                  'uuid'       => $res->uuid(),
                  'title'      => $res->label(),
                  'redirect'   => !$res->get('field_media_redirect_to')->isEmpty() ? $res->field_media_redirect_to->getString(): '',
                  'description'=> !$res->get('field_media_description')->isEmpty() ? $res->field_media_description->getValue()[0]['value'] : '',
                  'image_data' => $findImage['status'] ? [
                     'file_id'   => $findImage['data']['fid'],
                     'file_uuid' => $findImage['data']['uuid'],
                     'filename'  => $findImage['data']['filename'],
                     'fileURI'   => "{$_ENV['APP_URL']}/restapi/v1/media_render/{$findImage['data']['uuid']}",
                     'filemime'  => $findImage['data']['filemime'],
                     'filesize'  => $findImage['data']['filesize']
                  ] : [],
                  'created_date' => date("Y-m-d H:i:s", $res->getCreatedTime()),
                  'last_edited'  => date("Y-m-d H:i:s", $res->getChangedTime())
               ];
            }, $imageList) : $imageList,
            'description'  => !$entity_obj->field_pmb_description->isEmpty() ? $entity_obj->field_pmb_description->getValue()[0]['value'] : '',
            'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_edited'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         );
      endforeach;

      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data available',
         'data'    => $result
      ]);
   }

   public function media_render(Request $request, $file_id = null)
   {
      if (empty($file_id)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'uuid cannot be empty',
            'data'    => []
         ], 400);
      };
      
      // get major image data
      $fileData = \Drupal::entityTypeManager()->getStorage('file')->loadByProperties(['uuid' => $file_id]);

      if (!empty($fileData)) :
         $fileData = current($fileData);
         $fileURI  = $fileData->getFileUri();
         $fileMime = $fileData->getMimeType();

         // check s3 location
         if (str_contains($fileURI, 's3')) {
            if (preg_match("/^s3:\/\/\d\d\d\d-\d\d\//", $fileURI)) {
               $fileURI = substr($fileURI, 0, 13) . urlencode(substr($fileURI, 13));
            }

            // retrieve image from s3
            $findS3 = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($fileURI, 'original', 'image', '+30 minutes', true);
            // get converted image
            $retrievedData = $findS3['status'] ? $findS3['data'] : $fileURI;
         }
         else{
            // retrieve image from URL
            $fileURL = \Drupal::request()->getSchemeAndHttpHost() . \Drupal::service('file_url_generator')->generateString($fileURI);
            // get converted image
            $retrievedData = file_get_contents($fileURL);
         };

         $retrievedData = $retrievedData === false ? '' : $retrievedData;

         return new Response($retrievedData, 200, [
            'content-type'=> $fileMime,
            'Content-Disposition:' => 'inline; filename="'.$fileData->getFilename().'"',
            'Cache-Control' => 'no-cache',
            'Content-Length' => strlen($retrievedData)
         ]);
      else:
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'theres no data available',
            'data'    => []
         ]);
      endif;
   }

}