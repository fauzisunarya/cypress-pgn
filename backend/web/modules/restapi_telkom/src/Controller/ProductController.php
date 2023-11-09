<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
use Drupal;

class ProductController extends ControllerBase
{

  public function listCategory(Request $request){
    $entity = Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    $search_method = $request->query->get('search_method');
    $search_query  = $request->query->get('search_value');
    $page          = $request->query->get('page') ?? 1;
    $perpage       = $request->query->get('perpage') ?? 10;

    $query = $query
      ->condition('status', 1)
      ->condition('type', 'product_category');

    if (!empty($search_method) && !in_array($search_method, ['title', 'keyword', 'uuid'])) {
      // invalid input
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Allowed search_method are title, keyword, or uuid',
        'data'    => []
      ], 400);
    }
    else if(!empty($search_method)) {
      if ($search_method == 'title' && !empty($search_query)) {
        $query->condition('title', explode('|', $search_query), 'IN');
      }
      else if ($search_method == 'keyword' && !empty($search_query)) {
        $query->condition('title', "%{$search_query}%", 'LIKE');
      }
      else if($search_method == 'uuid' && !empty($search_query)) {
        $query->condition('uuid', $search_query);
      }
    }

    // for pagination
    $count_query = clone $query;
    $totalData = $count_query->count()->execute();
    unset($count_query);

    $query->range( ($page-1)*$perpage , $perpage );

    // prepare data
    $data = array_map(function($category){
      return [
        'uuid'      => $category->uuid(),
        'title'   => $category->label(),
        'fields'   => array_map(function($field){
          return [
            'label' => $field['value'],
            'name'  => trim(strtolower(preg_replace("/\s/",'_',$field['value'])))
          ];
        }, $category->field_procat_list_specification->getValue())
      ];
    }, array_values( $entity->loadMultiple( $query->execute() ) ) );

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status' => 'success',
      'message' => 'Success to retrieve data',
      'pagination' => [
        'total_page' => (int) ceil($totalData / $perpage),
        'total_data' => (int) $totalData,
        'perpage' => (int) $perpage,
        'page'    => (int) $page
     ],
      'data' => $data
    ]);

  }

  public function storeCategory(Request $request){

    $title = $request->request->get('title');
    $fields = $request->request->get('fields');

    if (empty($title) || empty($fields) ) {
      // invalid input
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'title and fields are required',
        'data'    => []
      ], 400);
    }
    else if(!is_array($fields)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Property fields must be an array',
        'data'    => []
      ], 400);
    }

    $currentUser = \Drupal::service('restapi_telkom.app_helper')->getLoggedinUser();
    
    // save
    $category = Node::create([
      'type'        => 'product_category',
      'title'       => $title,
      'field_procat_list_specification' => $fields,
      'uid' => $currentUser['nid']
    ]);
    $category->save();

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status' => 'success',
      'message' => 'Success to store data',
      'data' => [
        'uuid' => $category->uuid(),
        'title'=> $category->label(),
        'fields' => array_map(function($field){
          return [
            'label' => $field['value'],
            'name'  => trim(strtolower(preg_replace("/\s/",'_',$field['value'])))
          ];
        }, $category->field_procat_list_specification->getValue())
      ]
    ]);

  }

  public function editCategory(Request $request){
    $entity = Drupal::entityTypeManager()->getStorage('node');

    $category_uuid = $request->request->get('uuid');
    $title = $request->request->get('title');
    $fields = $request->request->get('fields');

    if (empty($category_uuid) || empty($title) || empty($fields) ) {
      // invalid input
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'uuid, title, and fields are required',
        'data'    => []
      ], 400);
    }
    else if(!is_array($fields)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Property fields must be an array',
        'data'    => []
      ], 400);
    }

    // validate category
    $category = current($entity->loadByProperties(['type'=> 'product_category', 'uuid' => $category_uuid]));
    if (empty($category)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Invalid uuid',
        'data'    => []
      ], 400);
    }

    $currentUser = \Drupal::service('restapi_telkom.app_helper')->getLoggedinUser();
    
    // update
    $category->title = $title;
    $category->set('field_procat_list_specification', $fields);
    // Make this change a new revision
    $category->setNewRevision(TRUE);
    $category->revision_log = 'Created revision for node ' . $category->id();
    $category->setRevisionCreationTime(REQUEST_TIME);
    $category->setRevisionUserId($currentUser['nid']);
    $category->save();

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status' => 'success',
      'message' => 'Success to store data',
      'data' => [
        'uuid' => $category->uuid(),
        'title'=> $category->label(),
        'fields' => array_map(function($field){
          return [
            'label' => $field['value'],
            'name'  => trim(strtolower(preg_replace("/\s/",'_',$field['value'])))
          ];
        }, $category->field_procat_list_specification->getValue())
      ]
    ]);

  }

  public function listProduct(Request $request){
    $entity = Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    $search_method = $request->query->get('search_method');
    $search_query  = $request->query->get('search_value');
    $page          = $request->query->get('page') ?? 1;
    $perpage       = $request->query->get('perpage') ?? 10;

    $query = $query
        ->condition('status', 1)
        ->condition('type', 'product');

    if (!empty($search_method) && !in_array($search_method, ['title', 'uuid', 'category', 'category_uuid'])) {
      // invalid input
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Allowed search_method are title, uuid, category, or category_uuid',
        'data'    => []
     ], 400);
    }
    else if(!empty($search_method)) {

      if(!empty($search_query)){
        switch ($search_method) {
          
          case 'title':
            $query->condition('title', "%{$search_query}%", 'LIKE');
          break;

          case 'uuid':
            $query->condition('uuid', $search_query);
          break;

          case 'category':
            $query_category = $entity->getQuery();
            $id = $query_category
              ->condition('status', 1)
              ->condition('type', 'product_category')
              ->condition('title', $search_query, 'STARTS_WITH')
              ->condition('title', $search_query, 'ENDS_WITH')
              ->range(0,1)
              ->execute();

            if (!empty(current($id))) {
              $query->condition('field_pro_product_category', current($id));
            }
            else{
              $query->condition('field_pro_product_category', 1); // category with id 1 is doesn't exist
            }
          break;

          case 'category_uuid':
            $query_category = $entity->getQuery();
            $id = $query_category
              ->condition('status', 1)
              ->condition('type', 'product_category')
              ->condition('uuid', $search_query)
              ->range(0,1)
              ->execute();

            if (!empty(current($id))) {
              $query->condition('field_pro_product_category', current($id));
            }
            else{
              $query->condition('field_pro_product_category', 1); // category with id 1 is doesn't exist
            }
          break;
          
          default:
          break;
        }
      }

    }

    // for pagination
    $count_query = clone $query;
    $totalData = $count_query->count()->execute();
    unset($count_query);

    $query->range( ($page-1)*$perpage , $perpage );

    $data = array_map(function($product){

      // load list property "specification" from referenced category
      $category = $product->field_pro_product_category->referencedEntities()[0];

      $data_specification = json_decode($product->field_pro_specification->getString(), true);

      // mappting value with product specification, empty if data doesn't exist
      $specification = [];
      foreach ($category->field_procat_list_specification->getValue() as $val) {
        $key = trim(strtolower(preg_replace( "/\s/",'_',$val['value'] )));
        $specification[] = [
          'label' => $val['value'],
          'value' => !empty($data_specification[$key]) ? $data_specification[$key] : ''
        ];
      }

      return [
        'uuid' => $product->uuid(),
        'title' => $product->label(),
        'category' => [
          'uuid' => $category->uuid(),
          'title' => $category->label()
        ],
        'specification' => $specification
      ];

    }, array_values( $entity->loadMultiple( $query->execute() ) ) );

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status' => 'success',
      'message' => 'Success to retrieve data',
      'pagination' => [
        'total_page' => (int) ceil($totalData / $perpage),
        'total_data' => (int) $totalData,
        'perpage' => (int) $perpage,
        'page'    => (int) $page
     ],
      'data' => $data
    ]);

  }

  public function storeProduct(Request $request){
    $entity = Drupal::entityTypeManager()->getStorage('node');

    // process submit product
    $title = $request->request->get('title');
    $category = $request->request->get('category');
    $specification = $request->request->get('specification');

    // validate
    if (empty($title) || empty($category) || !isset($specification)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Property title, category, and specification are required',
        'data'    => []
      ], 400);
    }
    else if (!is_array($specification)){
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Property specification must be an array',
        'data'    => []
      ], 400);
    }

    $query = $entity->getQuery();
    $id = $query
      ->condition('status', 1)
      ->condition('type', 'product_category')
      ->condition('title', $category, 'STARTS_WITH')
      ->condition('title', $category, 'ENDS_WITH')
      ->range(0,1)
      ->execute();

    if (empty(current($id))) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Invalid category, see list product specification category',
        'data'    => []
      ], 400);
    }
    
    // process request
    $category_id = current($id);

    // get detail category
    $category = Node::load($category_id);

    // prepare specification based on available list
    $data_specification = [];
    $response_specification = [];
    foreach ($category->field_procat_list_specification->getValue() as $val) {
      $key = trim(strtolower(preg_replace( "/\s/",'_',$val['value'] )));
      $data_specification[$key] = !empty($specification[$key]) ? $specification[$key] : '';
      $response_specification[] = [
        'label' => $val['value'],
        'value' => !empty($specification[$key]) ? $specification[$key] : ''
      ];
    }

    $currentUser = \Drupal::service('restapi_telkom.app_helper')->getLoggedinUser();

    // save product
    $product = Node::create([
      'type'        => 'product',
      'title'       => $title,
      'field_pro_product_category' => $category_id,
      'field_pro_specification' => json_encode($data_specification),
      'uid' => $currentUser['nid']
    ]);
    $product->save();

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status' => 'success',
      'message' => 'Success to store data',
      'data' => [
        'uuid' => $product->uuid(),
        'title'=> $product->label(),
        'category' => [
          'uuid' => $category->uuid(),
          'title' => $category->label()
        ],
        'specification' => $response_specification
      ]
    ]);
  }

  public function editProduct(Request $request){
    $entity = Drupal::entityTypeManager()->getStorage('node');

    // process submit product
    $product_uuid = $request->request->get('uuid');
    $title = $request->request->get('title');
    $category = $request->request->get('category');
    $specification = $request->request->get('specification');

    // validate
    if (empty($product_uuid) || empty($title) || empty($category) || !isset($specification)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Property uuid, title, category, and specification are required',
        'data'    => []
      ], 400);
    }
    else if (!is_array($specification)){
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Property specification must be an array',
        'data'    => []
      ], 400);
    }

    // validate product
    $product = current($entity->loadByProperties(['type'=> 'product', 'uuid' => $product_uuid]));
    if (empty($product)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Invalid uuid',
        'data'    => []
      ], 400);
    }

    $query = $entity->getQuery();
    $id = $query
      ->condition('status', 1)
      ->condition('type', 'product_category')
      ->condition('title', $category, 'STARTS_WITH')
      ->condition('title', $category, 'ENDS_WITH')
      ->range(0,1)
      ->execute();

    if (empty(current($id))) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'Invalid category, see list product specification category',
        'data'    => []
      ], 400);
    }
    
    // process request
    $category_id = current($id);

    // get detail category
    $category = Node::load($category_id);

    // prepare specification based on available list
    $data_specification = [];
    $response_specification = [];
    foreach ($category->field_procat_list_specification->getValue() as $val) {
      $key = trim(strtolower(preg_replace( "/\s/",'_',$val['value'] )));
      $data_specification[$key] = !empty($specification[$key]) ? $specification[$key] : '';
      $response_specification[] = [
        'label' => $val['value'],
        'value' => !empty($specification[$key]) ? $specification[$key] : ''
      ];
    }

    $currentUser = \Drupal::service('restapi_telkom.app_helper')->getLoggedinUser();

    // update product
    $product->title = $title;
    $product->field_pro_product_category = $category_id;
    $product->field_pro_specification = json_encode($data_specification);
    // Make this change a new revision
    $product->setNewRevision(TRUE);
    $product->revision_log = 'Created revision for node ' . $product->id();
    $product->setRevisionCreationTime(REQUEST_TIME);
    $product->setRevisionUserId($currentUser['nid']);
    $product->save();

    return \Drupal::service('restapi_telkom.app_helper')->response([
      'status' => 'success',
      'message' => 'Success to store data',
      'data' => [
        'uuid' => $product->uuid(),
        'title'=> $product->label(),
        'category' => [
          'uuid' => $category->uuid(),
          'title' => $category->label()
        ],
        'specification' => $response_specification
      ]
    ]);
  }

}