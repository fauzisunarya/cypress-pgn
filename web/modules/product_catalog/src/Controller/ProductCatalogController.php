<?php
namespace Drupal\product_catalog\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal;

class ProductCatalogController {
  
  public function search(Request $request) {
    $search_query = $request->query->get('search');
    $landing_page_type_id = (int) ($request->query->get('landing_page_type') ?? 0);

    $result = array();
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query  = $entity->getQuery();

    $query->condition('status', 1)
      ->condition('type', 'product_catalog')
      ->condition('title', "%{$search_query}%", 'LIKE');

    $catalog_type_id = $landing_page_type_id === 0 ? 0 : $this->getCatalogTypeId($landing_page_type_id);
    if ( $catalog_type_id > 0 ) {
      $query->condition('field_pct_type', $catalog_type_id);
    }

    $data = $query->range(0,10)->execute();
    
    foreach ($entity->loadMultiple($data) as $entity_id => $entity_obj) :
      $result[] = array(
        'nid'  => $entity_obj->id(),
        'uuid' => $entity_obj->uuid(),
        'name' => $entity_obj->label()
      );
    endforeach;

    return new JsonResponse($result);
  }

  private function getCatalogTypeId(int $landing_page_type_id) {
    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($landing_page_type_id);
    if ($term && $term->bundle()==='landing_page_type') {
      
      $page_type = strtolower($term->label());

      switch ($page_type) {
        case 'ao/psb retail':
          $arr_catalog_type = Drupal::service('product_catalog.catalog_helper')->getAllowedType('label');
          if (!empty($arr_catalog_type['paket'])) {
            return (int) $arr_catalog_type['paket']; // ex format : ['paket'=> "240", "addon"=>"241"]
          }
          break;

        case 'ao/psb retail+ebis':
          $arr_catalog_type = Drupal::service('product_catalog.catalog_helper')->getAllowedType('label');
          if (!empty($arr_catalog_type['retail+ebis'])) {
            return (int) $arr_catalog_type['retail+ebis'];
          }
          break;

        case 'mo':
          $arr_catalog_type = Drupal::service('product_catalog.catalog_helper')->getAllowedType('label');
          if (!empty($arr_catalog_type['addon'])) {
            return (int) $arr_catalog_type['addon'];
          }
          break;
        
        default:

          return 0;
          
          break;
      }
    }
    return 0;
  }

  /**
   * Setting Template Pricing save data
   */
  public function save_setting_template_pricing(){
    if (empty($_POST['product_catalog_id'])) {
      return new JsonResponse('product_catalog_id is required', 422);
    }

    $catalog = \Drupal::entityTypeManager()->getStorage('node')->load($_POST['product_catalog_id']);

    if ($catalog===null) {
      return new JsonResponse('invalid product catalog id', 422);
    }
    elseif ($catalog->type->entity->get('type')!=='product_catalog') {
      return new JsonResponse('invalid product catalog id', 422);
    }

    $setting_template_pricing = json_decode($catalog->field_pct_setting_temp_pricing->getString(), true);

    if (!is_array($setting_template_pricing)) {
      $setting_template_pricing = [];
    }

    // format = ['setting_id_1'=> true, 'setting_id_2'=> false, etc] true = this setting is showed
    foreach ($_POST['data'] as $setting) {
      $setting_template_pricing[$setting['id']] = $setting['value']==='false' ? false : true;
    }

    // save the updated data
    $catalog->field_pct_setting_temp_pricing = json_encode($setting_template_pricing);
    $catalog->save();

    $return = [
      'status' => 'success',
      'message' => 'Data Updated'
    ];

    return new JsonResponse($return);
  }

}