<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class ProductKnowledgeController extends ControllerBase{

   public function searchList(Request $request)
   {
      // prepare variable
      $result = array();
      $app_helper    = \Drupal::service('restapi_telkom.app_helper');
      $search_method = $request->query->get('search_method'); 
      $search_query  = $request->query->get('search_value');

      // core query data
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery()
         ->condition('status', 1)
         ->condition('type', 'paket');

      if (!empty($search_method) || !empty($search_query)) :
         if (empty($search_query) OR empty($search_method) OR !in_array($search_method, ['title','uuid', 'package_id'])) {
            return \Drupal::service('restapi_telkom.app_helper')->response([
               'status'  => 'failed',
               'message' => 'request parameter not valid',
               'data'    => []
            ], 400);
         };

         // mapping seach method if type is package id
         if ($search_method === 'package_id') {
            // insert new condition for search data
            $query->condition('field_pkt_package_id', explode('|', $search_query), 'IN');
            // load data
            $loadedData = $entity->loadMultiple($query->execute());
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
         };

         // release memory
         unset($raw_query, $raw_result);
      else:
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'parameter cannot be empty',
            'data'    => []
         ], 400);
      endif;

      // release memory
      unset($query);

      if ($loadedData) {
         // get product knowledge list
         $queryKnowledge = $entity->getQuery()
            ->condition('status', 1)
            ->condition('type', 'paket_product_knowledge')
            ->condition('field_pkt_pknowledge_parent', array_keys($loadedData), 'IN');
         // get product knowledge group list
         $queryKnowledgeGroup = $entity->getQuery()
            ->condition('status', 1)
            ->condition('type', 'paket_knowledge_group')
            ->condition('field_pkt_knowledge_paket_ref', array_keys($loadedData), 'IN');
         // retrieve data
         $loadedKnowledge = \Drupal::service('restapi_telkom.product_helper')
            ->mappingProductKnowledge($entity->loadMultiple($queryKnowledge->execute()));
         $loadedGroup     = \Drupal::service('restapi_telkom.product_helper')
            ->mappingProductKnowGroup($entity->loadMultiple($queryKnowledgeGroup->execute()));

         // release memory
         unset($queryKnowledge, $queryKnowledgeGroup, $entity);

         // mapping data
         foreach ($loadedData as $entity_id => $entity_obj) :
            $result[] = array(
               'id'               => (int) $entity_obj->id(),
               'uuid'             => $entity_obj->uuid(),
               'name_label'       => $entity_obj->label(),
               'package_id'       => (int) $entity_obj->field_pkt_package_id->getString(),
               'package_name'     => $entity_obj->field_pkt_flag->getString(),
               'knowledge_detail' => $loadedKnowledge[$entity_obj->id()] ?? array(),
               'knowledge_order'  => $loadedGroup[$entity_obj->id()] ?? array()
            );   
         endforeach;
      };
      
      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data',
         'data'    => $result
      ]);
   }

   public function searchListV2(Request $request)
   {
    // prepare variable
    $result = array();

    $module = $request->query->get('module') ?? 'package';
    $search_method = $request->query->get('search_method'); 
    $search_query  = $request->query->get('search_value');

    // validate module
    if (empty($module) || !in_array($module, ['citem', 'package', 'ebis ncx', 'wibs ncx', 'product_catalogue', 'segment'])) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'allowed module are citem, package, ebis ncx, wibs ncx, product_catalogue, and segment ',
        'data'    => []
      ], 400);
    }

    // core query data
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery()
       ->condition('status', 1);

    switch ($module) {
      case 'citem' : // citem knowledge using same field as paket (re-use field)
        $query->condition('type', 'citem');
        $code = 'pkt';
        $ref_name = 'paket_ref';
      break;
      case 'ebis ncx' : // ebis knowledge using same field as paket (re-use field)
        $query->condition('type', 'ebis');
        $code = 'pkt';
        $ref_name = 'paket_ref';
      break;
      case 'wibs ncx' : // wibs knowledge using same field as paket (re-use field)
        $query->condition('type', 'wibs');
        $code = 'pkt';
        $ref_name = 'paket_ref';
      break;
      case 'package':
        $query->condition('type', 'paket');
        $code = 'pkt';
        $ref_name = 'paket_ref';
      break;
      case 'product_catalogue':
        $query->condition('type', 'product_catalog');
        $code = 'pct';
        $ref_name = 'catalog_ref';
      break;
      case 'segment':
        $query->condition('type', 'segment');
        $code = 'seg';
        $ref_name = 'segment_ref';
      break;
    }

    if (!empty($search_method) || !empty($search_query)) :
       if (empty($search_query) OR empty($search_method) OR !in_array($search_method, ['title','uuid', 'package_id'])) {
          return \Drupal::service('restapi_telkom.app_helper')->response([
             'status'  => 'failed',
             'message' => 'request parameter not valid',
             'data'    => []
          ], 400);
       };

       // mapping seach method if type is package id
       if ($module == 'package' && $search_method === 'package_id') {
          // insert new condition for search data
          $query->condition('field_pkt_package_id', explode('|', $search_query), 'IN');
          // load data
          $loadedData = $entity->loadMultiple($query->execute());
       }
       elseif ($search_method === 'package_id') {
          // return error
          return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'search_method package_id can be applied when module is package',
            'data'    => []
          ], 400);
       }
       else{
          // insert new condition for search data
          $query->condition($search_method, $search_query, 'LIKE');
          // limit data
          $query->range(0, 10);
          // rebuild the query suitable when user is input multiple
          $raw_query  = preg_replace('/LIKE(.*)ESCAPE.*\)/im', "REGEXP '{$search_query}')", (String)$query);
          // retrieve all suitable data
          $raw_result = \Drupal::database()->query($raw_query)->fetchAll();
          // load data based on node_id
          $loadedData = $entity->loadMultiple(array_column($raw_result, 'nid'));
       };

       // release memory
       unset($raw_query, $raw_result);
    else:
       return \Drupal::service('restapi_telkom.app_helper')->response([
          'status'  => 'failed',
          'message' => 'parameter cannot be empty',
          'data'    => []
       ], 400);
    endif;

    // release memory
    unset($query);

    if ($loadedData) {

       // get product knowledge list
       $queryKnowledge = $entity->getQuery()
          ->condition('status', 1);

       // get product knowledge group list
       $queryKnowledgeGroup = $entity->getQuery()
          ->condition('status', 1);
          
       if ($module == 'citem') {
          $queryKnowledge->condition('type', 'citem_product_knowledge');
          $queryKnowledgeGroup->condition('type', 'citem_knowledge_group');
       }
       elseif ($module == 'ebis') {
          $queryKnowledge->condition('type', 'ebis_product_knowledge');
          $queryKnowledgeGroup->condition('type', 'ebis_knowledge_group');
       }
       elseif ($module == 'wibs') {
          $queryKnowledge->condition('type', 'wibs_product_knowledge');
          $queryKnowledgeGroup->condition('type', 'wibs_knowledge_group');
       }
       elseif ($module == 'package') {
          $queryKnowledge->condition('type', 'paket_product_knowledge');
          $queryKnowledgeGroup->condition('type', 'paket_knowledge_group');
       }
       elseif ($module == 'product_catalogue') {
          $queryKnowledge->condition('type', 'product_catalog_product_knowledg');
          $queryKnowledgeGroup->condition('type', 'catalog_knowledge_group');
       }
       elseif ($module == 'segment') {
          $queryKnowledge->condition('type', 'segment_product_knowledge');
          $queryKnowledgeGroup->condition('type', 'segment_knowledge_group');
       }

       $queryKnowledge->condition("field_{$code}_pknowledge_parent", array_keys($loadedData), 'IN');

       $queryKnowledgeGroup->condition("field_{$code}_knowledge_{$ref_name}", array_keys($loadedData), 'IN');

       // retrieve data
       $loadedKnowledge = \Drupal::service('restapi_telkom.product_helper')
          ->mappingProductKnowledge($entity->loadMultiple($queryKnowledge->execute()), $code);

       $loadedGroup     = \Drupal::service('restapi_telkom.product_helper')
          ->mappingProductKnowGroup($entity->loadMultiple($queryKnowledgeGroup->execute()), $code, $ref_name);

       // release memory
       unset($queryKnowledge, $queryKnowledgeGroup, $entity);

       // mapping data
       foreach ($loadedData as $entity_id => $entity_obj) :
          $result[] = array(
             'id'               => (int) $entity_obj->id(),
             'uuid'             => $entity_obj->uuid(),
             'module'           => $module,
             'name_label'       => $entity_obj->label(),
             'package_id'       => $code == 'pkt' && $entity_obj->field_pkt_package_id ? $entity_obj->field_pkt_package_id->getString() : null,
             'package_name'     => $code == 'pkt' && $entity_obj->field_pkt_flag ? $entity_obj->field_pkt_flag->getString() : $entity_obj->label(),
             'knowledge_detail' => $loadedKnowledge[$entity_obj->id()] ?? array(),
             'knowledge_order'  => $loadedGroup[$entity_obj->id()] ?? array()
          );   
       endforeach;
    };
    
    return \Drupal::service('restapi_telkom.app_helper')->response([
       'status'  => !empty($result) ? 'success' : 'failed',
       'message' => !empty($result) ? 'success to retrieve data' : 'theres no data',
       'data'    => $result
    ]);
 }

}