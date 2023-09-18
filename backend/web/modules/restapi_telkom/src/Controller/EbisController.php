<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use TemplatePricingEbis;

class EbisController extends ControllerBase{

  public function list(Request $request)
  {
    // prepare variable
    $result = array();
    $app_helper   = \Drupal::service('restapi_telkom.app_helper');
    $search_method = $request->query->get('search_method') ?? 'title'; // default title
    $search_query  = $request->query->get('search_value') ?? '';
    $page = (int) ($request->query->get('page') ?? 1);
    $perpage = (int) ($request->query->get('perpage') ?? 10);

    // validate search method
    if ( !in_array($search_method, ['title','uuid', 'product_id'])) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'allowed search_method are title, uuid, product_id',
        'data'    => []
      ], 400);
    }

    // core query data
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery()
      ->condition('status', 1)
      ->condition('type', 'ebis');

    $loaded_data = [];
    $total_data = 0;    

    $search_query = str_replace(['\'', '"', '*', '<', '>', '$'], '', $search_query);
    if (!empty($search_query)) {
      if ($search_method === 'product_id') {
        $query->condition('field_non_ret_prod_id', explode('|', $search_query), 'IN');

        // get total data (for pagination)
        $raw_query_total = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', (String)$query);
        $raw_total_data = \Drupal::database()->query($raw_query_total)->fetchObject();
        $total_data = (int) $raw_total_data->total;

        unset($raw_query_total, $raw_total_data);

        // retrieve all suitable data ( apply offset limit )
        $loaded_data = $entity->loadMultiple( $query->range(($page-1)*$perpage,$perpage)->execute() );
      }
      else {
        // for search_method title or raw_query_total
        
        // insert new condition for search data
        $query->condition($search_method, $search_query, 'LIKE');

        // rebuild the query suitable when user is input multiple
        $raw_query  = preg_replace('/LIKE(.*)ESCAPE.*\)/im', "REGEXP '{$search_query}' )", (String)$query);

        // get total data (for pagination)
        $raw_query_total = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', $raw_query);
        $raw_total_data = \Drupal::database()->query($raw_query_total)->fetchObject();
        $total_data = (int) $raw_total_data->total;

        // retrieve all suitable data ( apply offset limit )
        $offset = ($page-1)*$perpage;
        $raw_query .= " LIMIT {$perpage} OFFSET {$offset}";
        $raw_result = \Drupal::database()->query($raw_query)->fetchAll();

        unset($raw_query_total, $raw_total_data);

        // load data based on node_id
        $loaded_data = $entity->loadMultiple(array_column($raw_result, 'nid'));
      }
    }
    else {
      // allow show any data (without condition)
      if ($search_method === 'title') {
        // allow, directly get result

        // get total data (for pagination)
        $raw_query_total = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', (String)$query);
        $raw_total_data = \Drupal::database()->query($raw_query_total)->fetchObject();
        $total_data = (int) $raw_total_data->total;

        unset($raw_query_total, $raw_total_data);

        // retrieve all suitable data ( apply offset limit )
        $loaded_data = $entity->loadMultiple( $query->range(($page-1)*$perpage,$perpage)->execute() );
      }
      else {
        return \Drupal::service('restapi_telkom.app_helper')->response([
          'status'  => 'failed',
          'message' => 'search_value are required',
          'data'    => []
        ], 400);
      }
    }

    // release memory
    unset($query);

    $results = [];
    if (!empty($loaded_data)) {

      foreach ($loaded_data as $entity_obj) :

        $result = [
          'uuid'         => $entity_obj->uuid(),
          'module'       => $entity_obj->bundle(),
          'name'         => $entity_obj->label(),
          'product_id'   => $entity_obj->field_non_ret_prod_id->getString(),
          'product_code' => $entity_obj->field_non_ret_prod_code->getString(),
          'pruduct_type' => !empty($entity_obj->field_non_ret_prod_type->getString()) ? $entity_obj->field_non_ret_prod_type->referencedEntities()[0]->label() : '',
          'product_description' => $entity_obj->field_non_ret_prod_desc->getString(),
          'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
          'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
        ];

        // release memory
        unset($showingMode, $entity_obj);

        // register major result data
        $results[] = $result;
      endforeach;
    };
    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status'  => !empty($result) ? 'success' : 'failed',
      'message' => !empty($result) ? 'success to retrieve data' : 'theres no data',
      'pagination' => [
        'total_page' => (int) ceil($total_data / $perpage),
        'total_data' => (int) $total_data,
        'perpage' => (int) $perpage,
        'page'    => (int) $page
      ],
      'data'    => $results
    ]);
  } 

  public function detail(Request $request, $id = null)
  {
    if (empty($id)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'paket id cannot be empty',
        'data'    => []
      ], 400);
    };

    $result       = array();
    $html_data    = array();
    $entity   = \Drupal::entityTypeManager()->getStorage('node');

    // check is this paket id using uuid or raw id?
    if (\Drupal::service('restapi_telkom.app_helper')->isValidUuid($id)) {
        $data = $entity->loadByProperties(['uuid' => $id]);
    }else{
      $query = $entity->getQuery()
        ->condition('status', 1)
        ->condition('type', 'ebis')
        ->condition('field_non_ret_prod_id', $id)
        ->execute();

      $loaded_nid = current($query);
      $data = $loaded_nid ? [$loaded_nid => $entity->load($loaded_nid)] : null;
    };

    if (!empty($data)) :
        // prepare main variable
        $entity_obj    = current($data);
        require_once __DIR__ . "/../../../../themes/custom/telkom_cms/functions/ebis/template/template_pricing.php";

        // register major package data first
        $result = array(
          'uuid'         => $entity_obj->uuid(),
          'module'       => $entity_obj->bundle(),
          'name'         => $entity_obj->label(),
          'product_id'   => $entity_obj->field_non_ret_prod_id->getString(),
          'product_code' => $entity_obj->field_non_ret_prod_code->getString(),
          'pruduct_type' => !empty($entity_obj->field_non_ret_prod_type->getString()) ? $entity_obj->field_non_ret_prod_type->referencedEntities()[0]->label() : '',
          'product_description' => $entity_obj->field_non_ret_prod_desc->getString(),
          'html_data'         => (new TemplatePricingEbis())->get_list($entity_obj, true),
          'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
          'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
        );

    endif;

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status'  => !empty($result) ? 'success' : 'failed',
      'message' => !empty($result) ? 'success to retrieve data' : 'theres no data related',
      'data'    => $result
    ]);
  }

}