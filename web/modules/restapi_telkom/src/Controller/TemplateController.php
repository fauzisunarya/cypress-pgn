<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class TemplateController extends ControllerBase{

   public function list(Request $request)
   {
      $result = [];
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery();

      $query = $query
         ->condition('status', 1)
         ->condition('type', 'template')
         ->condition('title', "%{$request->query->get('search')}%", 'LIKE');

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
      }

      $ids = $query->execute();

      foreach ($entity->loadMultiple($ids) as $entity_id => $entity_obj) {
         $result[] = array(
            'id'   => $entity_obj->nid->getString(), 
            'uuid' => $entity_obj->uuid(),
            'name' => $entity_obj->label(),
            'module' => $entity_obj->getType(),
            'category' => implode(',', array_map(function($res){
               return $res->getName();
            }, $entity_obj->get('field_tem_category')->referencedEntities())),
            'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         );
      }
      
      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data available',
         'data'    => $result
      ]);
   }

}