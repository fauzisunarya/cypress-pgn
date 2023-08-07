<?php

namespace Drupal\restapi_telkom\Controller;

use CatalogPricingCitem;
use CatalogPricingEbisWibs;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal;

class ProductCatalogController extends ControllerBase{

    /**
     * Allowed catalog type
     * @param string $keys id|label|increment
     */
    private function getCatalogType(string $keys = 'increment'){

      $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'product_catalog_type']);
      $list = [];
      foreach ($terms as $term) {
        switch ($keys) {
          case 'id':
            $list[$term->id()] = strtolower($term->label());
            break;
          case 'label':
            $list[strtolower($term->label())] = $term->id() ;
            break;
          default:
            $list[] = strtolower($term->label());
            break;
        }
        
      }
      return $list;

    }

    private function onlyLoadCatalogForSelectedType(&$query, $allowedType = ['paket', 'addon']) {

      $availableType = $this->getCatalogType('label'); // ex format : ['paket'=> "240", "addon"=>"241"]
      $idsType = array_filter($availableType, function($val, $key) use($allowedType){
        return in_array($key, $allowedType);
      }, ARRAY_FILTER_USE_BOTH);

      if (!empty($idsType)) {
        $query->condition('field_pct_type', $idsType, 'IN');
      }
    }
    

   /**
    * For : /restapi/v1/product
    */
   public function list(Request $request)
   {
      $result = [];
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery();

      $search_query = $request->query->get('search');
      $req_catalog_type = trim(strtolower($request->query->get('catalog_type') ?? ''));
      $perpage = $request->query->get('perpage') ?? 10;
      $page = $request->query->get('page') ?? 1;

      // validate catalog type
      if ($req_catalog_type === 'retail ebis') {
        $req_catalog_type = 'retail+ebis';
      }
      $arr_catalog_type = $this->getCatalogType();
      if (!empty($req_catalog_type) && !in_array($req_catalog_type, $arr_catalog_type)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'allowed catalog_type are ' . implode(', ', $arr_catalog_type),
            'data'    => []
         ], 400);
      }

      $query = $query
         ->condition('status', 1)
         ->condition('type', 'product_catalog')
         ->condition('title', "%{$search_query}%", 'LIKE');

      if (!empty($req_catalog_type)) {
         $this->onlyLoadCatalogForSelectedType($query, [$req_catalog_type]);
      }

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

      $raw_query = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', (String)$query);
      $raw_total = \Drupal::database()->query($raw_query)->fetchObject();

      $datas = $query->range((($page * $perpage) - $perpage), $perpage)->execute();

      foreach ($entity->loadMultiple($datas) as $entity_id => $entity_obj) {
         $result[] = array(
            'uuid' => $entity_obj->uuid(),
            'name' => $entity_obj->label(),
            'module' => $entity_obj->getType(),
            'catalog_type' => !empty($entity_obj->field_pct_type->getString()) ? strtolower($entity_obj->field_pct_type->referencedEntities()[0]->label()) : 'paket',
            'status' => $entity_obj->isPublished() ? 'published' : 'hidden',
            'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         );
      }
      
      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'     => !empty($result) ? 'success' : 'failed',
         'message'    => !empty($result) ? 'success to retrieve data' : 'theres no data available',
         'pagination' => [
            'total_page' => (int) ceil($raw_total->total / $perpage),
            'total_data' => (int) $raw_total->total,
            'perpage' => (int) $perpage,
            'page'    => (int) $page
         ],
         'data'       => $result
      ]);
   }

   /**
    * For : /restapi/v1/product/:uuid
    */
   public function detail(Request $request, $catalog_id = null)
   {
      if (empty($catalog_id)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'catalogue id cannot be empty',
            'data'    => []
         ], 400);
      };

      $result = [];
      $entity = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $catalog_id]);

      if (!empty($entity)) :
         $entity_obj = current($entity);

         $catalog_type = !empty($entity_obj->field_pct_type->getString()) ? strtolower($entity_obj->field_pct_type->referencedEntities()[0]->label()) : 'paket';
         $prices = [];
         if ($catalog_type==='citem') {
            // prices for catalog_type === citem
            $prices = json_decode($entity_obj->field_pct_citem_price->getString(), true);
            $prices = !empty($prices) ? $prices : []; // format : [ '$citem_id' => ["active" => null | tariff_id, price=>[dari api]] ]
         }
         
         $result = array(
            'uuid'   => $entity_obj->uuid(),
            'name'   => $entity_obj->label(),
            'module' => $entity_obj->getType(),
            'catalog_type' => $catalog_type,
            'description'  => $entity_obj->field_pct_description->getValue()[0]['value'],
            'tags'         => $entity_obj->field_pct_tags->getString(),
            'category'     => implode(',', array_map(function($res){
               return $res->getName();
            }, $entity_obj->get('field_pct_category')->referencedEntities())),

            'package_list' => array_map(function($paket) use($prices){

               switch ($paket->bundle()) {           
                  case 'citem':
                     $citem_price = '-';

                     $citem_id = $paket->field_ctm_product_id->getString();
                     if (!empty($prices[$citem_id]) && !empty($prices[$citem_id]['active'])) {
                        foreach ($prices[$citem_id]['list'] as $each_price) {
                           if ($each_price['tariff_id'] === $prices[$citem_id]['active']) {
                              $citem_price = $each_price['price'] ?? 0;
                              break;
                           }
                        }
                     }

                     return [
                        'uuid'            => $paket->uuid(),
                        'product_name'    => $paket->label(),
                        'product_type'    => implode( ', ', array_map(fn($val)=>$val->label(), $paket->field_ctm_product_type->referencedEntities()) ),
                        'product_id'      => $paket->field_ctm_product_id->getString(),
                        'product_rel'     => $paket->field_ctm_product_rel->getString(),
                        'pruduct_rel_id'  => $paket->field_ctm_product_rel_id->getString(),
                        'product_code'    => $paket->field_ctm_product_code->getString(),
                        'product_price'   => $citem_price,
                        'product_description'=> $paket->field_pkt_package_detail->getString(),
                        'product_status'  => $paket->field_ctm_status->getString()
                     ];
                     break;

                  case 'ebis':
                  case 'wibs':
                     return [
                        'uuid'            => $paket->uuid(),
                        'product_name'    => $paket->label(),
                        'product_type'    => implode( ', ', array_map(fn($val)=>$val->label(), $paket->field_non_ret_prod_type->referencedEntities()) ),
                        'product_id'      => $paket->field_non_ret_prod_id->getString(),
                        'product_code'    => $paket->field_non_ret_prod_code->getString(),
                        'product_description'=> $paket->field_non_ret_prod_desc->getString(),
                     ];
                     break;
                  
                  default: //this is paket
                     return [
                        'uuid' => $paket->uuid(),
                        'package_id' => current($paket->field_pkt_package_id->getValue()[0]),
                        'package_flag' => $paket->field_pkt_flag->getString(),
                        'package_tipe_paket' => !empty($paket->field_pkt_tipe_paket->referencedEntities()) ? $paket->field_pkt_tipe_paket->referencedEntities()[0]->label() : '',
                        'package_price_internet' => $paket->field_pkt_price_internet->getString(),
                        'package_price_total' => $paket->field_pkt_price_total->getString(),
                        'package_price_voice' => $paket->field_pkt_price_voice->getString(),
                        'package_speed' => $paket->field_pkt_speed->getString(),
                        'package_service' => $paket->field_pkt_service->getString(),
                        'package_transtype' => $paket->field_pkt_trans_type->getString(),
                        'package_detail' => $paket->field_pkt_package_detail->getString()
                     ];
                     break;
               }

            }, $entity_obj->get('field_pct_list_paket')->referencedEntities()),

            'package_view' => $this->loadTemplateCatalog($entity_obj)['package_view'],

            'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         );
      endif;

      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data related to selected paket id',
         'data'    => $result
      ]);
   }

   /**
    * For : /restapi/v1/product-catalogue/searchExist
    */
   public function catalogByProduct(Request $request)
   {
      // prepare variable
      $result      = array();
      $product_ids = $request->query->get('product_ids');

      // core query data
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery()
         ->condition('status', 1)
         ->condition('type', 'paket')
         ->condition('field_pkt_package_id', explode('|', $product_ids), 'IN');

      // load data
      $loadedData = $entity->loadMultiple($query->execute());

      // release memory
      unset($query);

      if ($loadedData) :

         $ids_package = array_keys($loadedData); //array id package
         $count_package = count($ids_package); //total package loaded
         $last_idx_package = $count_package-1; //last idx of array package

         $queryCatalogue = $entity->getQuery()
            ->condition('status', 1)
            ->condition('type', 'product_catalog')
            ->condition('field_pct_list_paket', $ids_package, 'IN')

            // this is to make sure, the amount of package in each catalog node is equal to amount of array package
            ->exists("field_pct_list_paket.{$last_idx_package}")
            ->notExists("field_pct_list_paket.{$count_package}");
          
         // now protect to only load product catalog for "paket" & "addon" (currently format are same). Skip citem, ebis ncx, wibs ncx
         $this->onlyLoadCatalogForSelectedType($queryCatalogue, ['paket', 'addon', 'retail+ebis']);

         // load data
         $loadedCatalogue = $entity->loadMultiple($queryCatalogue->execute());
         
         // filter data, array id package must equal with data in catalog
         $loadedCatalogue = array_filter($loadedCatalogue, function($catalog)use($ids_package) {
            $packages_catalog = array_map( fn($paket) => (int)$paket->id(), $catalog->field_pct_list_paket->referencedEntities());
            
            return count(array_intersect($ids_package, $packages_catalog)) === count($ids_package);
         });

         unset($queryCatalogue, $entity);

         if (!empty($loadedCatalogue)) {
            // mapping catalogue
            foreach ($loadedCatalogue as $entity_id => $entity_obj) :

               $categoryList = !$entity_obj->get('field_pct_category')->isEmpty() ? $entity_obj->get('field_pct_category')->referencedEntities() : array();
               $description = !$entity_obj->field_pct_description->isEmpty() ? $entity_obj->field_pct_description->getValue()[0]['value'] : ''; 
               $tagsList = !$entity_obj->field_pct_tags->isEmpty() ? $entity_obj->field_pct_tags->getString() : '';

               $result[] = array(
                  'id'   => (int) $entity_obj->id(),
                  'uuid' => $entity_obj->uuid(),
                  'name' => $entity_obj->label(),
                  'module' => $entity_obj->getType(),
                  'status' => $entity_obj->isPublished() ? 'published' : 'hidden',
                  'description'  => $description,
                  'tags'         => $tagsList,
                  'package_list' => array_map(function($paket){
                     return [
                        'id'         => (int) $paket->id(),
                        'uuid'       => $paket->uuid(),
                        'title'      => $paket->label(),
                        'package_id' => !$paket->field_pkt_package_id->isEmpty() ? $paket->field_pkt_package_id->getString() : 0
                     ];
                  }, $entity_obj->field_pct_list_paket->referencedEntities()),
                  'category'     => implode(',', array_map(fn($res) => $res->getName() , $categoryList)),
                  'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
                  'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
               );
            endforeach;
         };
      endif;

      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'     => !empty($result) ? 'success' : 'failed',
         'message'    => !empty($result) ? 'success to retrieve data' : 'theres no data available',
         'data'       => $result
      ]);
   }

   /**
    * For : /restapi/v1/product-package
    */
   public function packageList(Request $request)
   {
      // prepare variable
      $result = array();
      $req_catalog_type = trim(strtolower($request->query->get('catalog_type') ?? ''));
      $search_method = $request->query->get('search_method'); 
      $search_query  = $request->query->get('search_value');
      $perpage       = $request->query->get('perpage') ?? 10;
      $page          = $request->query->get('page') ?? 1;

      // validate catalog type
      if ($req_catalog_type === 'retail ebis') {
        $req_catalog_type = 'retail+ebis';
      }
      $arr_catalog_type = $this->getCatalogType();
      if (!empty($req_catalog_type) && !in_array($req_catalog_type, $arr_catalog_type)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'allowed catalog_type are ' . implode(', ', $arr_catalog_type),
            'data'    => []
         ], 400);
      }

      // core query data
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery()
         ->condition('status', 1)
         ->condition('type', 'product_catalog');

      if (!empty($req_catalog_type)) {
         $this->onlyLoadCatalogForSelectedType($query, [$req_catalog_type]);
      }

      if (!empty($search_method) && !empty($search_query)) :
         if (empty($search_method) OR !in_array($search_method, ['title','uuid'])) {
            return \Drupal::service('restapi_telkom.app_helper')->response([
               'status'  => 'failed',
               'message' => 'request parameter not valid',
               'data'    => []
            ], 400);
         };

         // insert new condition for search data
         $query->condition($search_method, $search_query, 'LIKE');
         // rebuild the query suitable when user is input multiple
         $raw_query  = preg_replace('/LIKE(.*)ESCAPE.*\)/im', "REGEXP '{$search_query}')", (String)$query);
         // retrieve all suitable data
         $raw_result = \Drupal::database()->query($raw_query)->fetchAll();
         // get pagination result
         $query_total = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', (String)$raw_query);
         $raw_total   = \Drupal::database()->query($query_total)->fetchObject();
         // load data based on node_id
         $loadedData = array_column($raw_result, 'nid');

         // release memory
         unset($query_total, $raw_query, $raw_result);
      else:
         $raw_query = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', (String)$query);
         $raw_total = \Drupal::database()->query($raw_query)->fetchObject();

         $loadedData = $query->range((($page * $perpage) - $perpage), $perpage)->execute();
      endif;

      // release memory
      unset($query);
      
      foreach ($entity->loadMultiple($loadedData) as $entity_id => $entity_obj) :
         $packageData = $entity_obj->get('field_pct_list_paket')->referencedEntities();

         $catalog_type = !empty($entity_obj->field_pct_type->getString()) ? strtolower($entity_obj->field_pct_type->referencedEntities()[0]->label()) : 'paket';
         $prices = [];
         if ($catalog_type==='citem') {
            // prices for catalog_type === citem
            $prices = json_decode($entity_obj->field_pct_citem_price->getString(), true);
            $prices = !empty($prices) ? $prices : []; // format : [ '$citem_id' => ["active" => null | tariff_id, price=>[dari api]] ]
         }

         $result[] = array(
            'uuid'   => $entity_obj->uuid(),
            'name'   => $entity_obj->label(),
            'catalog_type' => $catalog_type,
            'package_list' => array_map(function($res) use($prices){

               switch ($res->bundle()) {           
                  case 'citem':
                     $citem_price = '-';

                     $citem_id = $res->field_ctm_product_id->getString();
                     if (!empty($prices[$citem_id]) && !empty($prices[$citem_id]['active'])) {
                        foreach ($prices[$citem_id]['list'] as $each_price) {
                           if ($each_price['tariff_id'] === $prices[$citem_id]['active']) {
                              $citem_price = $each_price['price'] ?? 0;
                              break;
                           }
                        }
                     }

                     return [
                        'uuid'            => $res->uuid(),
                        'product_name'    => $res->label(),
                        'product_type'    => implode( ', ', array_map(fn($val)=>$val->label(), $res->field_ctm_product_type->referencedEntities()) ),
                        'product_id'      => $res->field_ctm_product_id->getString(),
                        'product_rel'     => $res->field_ctm_product_rel->getString(),
                        'pruduct_rel_id'  => $res->field_ctm_product_rel_id->getString(),
                        'product_code'    => $res->field_ctm_product_code->getString(),
                        'product_price'   => $citem_price,
                        'product_description'=> $res->field_pkt_package_detail->getString(),
                        'product_status'  => $res->field_ctm_status->getString()
                     ];
                     break;

                  case 'ebis':
                  case 'wibs':
                     return [
                        'uuid'            => $res->uuid(),
                        'product_name'    => $res->label(),
                        'product_type'    => implode( ', ', array_map(fn($val)=>$val->label(), $res->field_non_ret_prod_type->referencedEntities()) ),
                        'product_id'      => $res->field_non_ret_prod_id->getString(),
                        'product_code'    => $res->field_non_ret_prod_code->getString(),
                        'product_description'=> $res->field_non_ret_prod_desc->getString(),
                     ];
                     break;
                  
                  default: //this is paket
                     return [
                        'uuid' => $res->uuid(),
                        'package_id' => !empty($res->field_pkt_package_id->getValue()) ? current($res->field_pkt_package_id->getValue()[0]) : null,
                        'package_flag' => $res->field_pkt_flag->getString(),
                        'package_tipe_paket' => !empty($res->field_pkt_tipe_paket->referencedEntities()) ? $res->field_pkt_tipe_paket->referencedEntities()[0]->label() : '',
                        'package_price_internet' => $res->field_pkt_price_internet->getString(),
                        'package_price_total' => $res->field_pkt_price_total->getString(),
                        'package_price_voice' => $res->field_pkt_price_voice->getString(),
                        'package_speed' => $res->field_pkt_speed->getString(),
                        'package_service' => $res->field_pkt_service->getString(),
                        'package_transtype' => $res->field_pkt_trans_type->getString(),
                        'package_detail' => $res->field_pkt_package_detail->getString()
                     ];
                     break;
               }

            }, $packageData)
         );
      endforeach;

      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data related to selected paket id',
         'pagination' => [
            'total_page' => (int) ceil($raw_total->total / $perpage),
            'total_data' => (int) $raw_total->total,
            'perpage' => (int) $perpage,
            'page'    => (int) $page
         ],
         'data'    => $result
      ]);
   }

   /**
    * For : /restapi/v1/product-html
    */
   public function packageHtml(Request $request)
   {
      // prepare variable
      $result  = array();
      $perpage = 10;
      $page    = !empty($request->query->get('page')) ? (int) $request->query->get('page') : 1;
      $search  = $request->query->get('search') ?? '';
      $req_catalog_type = trim(strtolower($request->query->get('catalog_type') ?? ''));

      // validate catalog type
      if ($req_catalog_type === 'retail ebis') {
        $req_catalog_type = 'retail+ebis';
      }
      $arr_catalog_type = $this->getCatalogType();
      if (!empty($req_catalog_type) && !in_array($req_catalog_type, $arr_catalog_type)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'allowed catalog_type are ' . implode(', ', $arr_catalog_type),
            'data'    => []
         ], 400);
      }

      // core drupal service
      $entity       = \Drupal::entityTypeManager()->getStorage('node');
      $app_helper   = \Drupal::service('restapi_telkom.app_helper');
      $themeHandler = \Drupal::service('theme_handler');
      $themePath    = $themeHandler->getTheme($themeHandler->getDefault())->getPath();

      $templateQuery = $entity
         ->getQuery()
         ->condition('status', 1)
         ->condition('type', 'template_pricing_catalog');

      Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($templateQuery, 'field_tem_pct_type', ['paket']);
      $templates = $entity->loadMultiple($templateQuery->execute());

      $configQuery = $entity
         ->getQuery()
         ->condition('status', 1)
         ->condition('type', 'template_pricing_setting');
      $templatesConfig = $entity->loadMultiple($configQuery->execute());

      $query = $entity
         ->getQuery()
         ->condition('status', 1)
         ->condition('type', 'product_catalog')
         ->condition('title', "%{$search}%", 'LIKE');
      
      if (!empty($req_catalog_type)) {
         $this->onlyLoadCatalogForSelectedType($query, [$req_catalog_type]);
      }
      
      // pagination config
      $raw_query = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', (String)$query);
      $raw_total = \Drupal::database()->query($raw_query)->fetchObject();
      $loadedData = $entity->loadMultiple($query->range((($page * $perpage) - $perpage), $perpage)->execute());

      // release memory
      unset($templateQuery, $configQuery, $query, $themeHandler, $raw_query);

      // load product catalog html template
      foreach ($loadedData as $entity_obj) :
         $result[] = $this->loadTemplateCatalog($entity_obj, $app_helper, $themePath, $templates, $templatesConfig);
      endforeach;

      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'there is no data to be shown',
         'pagination' => [
            'total_page' => (int) ceil($raw_total->total / $perpage),
            'total_data' => (int) $raw_total->total,
            'perpage' => (int) $perpage,
            'page'    => (int) $page
         ],
         'data'    => $result
      ]);
   }

   /**
    * Load catalog template based on type (paket, citem, ebis ncx, wibs ncx)
    */
   private function loadTemplateCatalog($entity_obj, $app_helper=null, $themePath=null, $templates=null, $templatesConfig=null) {

      $catalog_type = !empty($entity_obj->field_pct_type->getString()) ? strtolower($entity_obj->field_pct_type->referencedEntities()[0]->label()) : 'paket';

      // process another catalog
      if ($catalog_type === 'citem') {
         include_once __DIR__ . "/../../../../themes/custom/telkom_cms/functions/product_catalog/template_pricing/catalog_pricing_citem.php";
         
         return array(
            'uuid'   => $entity_obj->uuid(),
            'name'   => $entity_obj->label(),
            'catalog_type' => $catalog_type,
            'package_view' => (new CatalogPricingCitem)->tab_template_pricing($entity_obj, true)
         );
      }
      else if (in_array($catalog_type, ['ebis ncx', 'wibs ncx'])) {
         include_once __DIR__ . "/../../../../themes/custom/telkom_cms/functions/product_catalog/template_pricing/catalog_pricing_ebis_wibs.php";
         
         return array(
            'uuid'   => $entity_obj->uuid(),
            'name'   => $entity_obj->label(),
            'catalog_type' => $catalog_type,
            'package_view' => (new CatalogPricingEbisWibs)->tab_template_pricing($entity_obj, true)
         );
      }

      // Load catalog type === paket

      $entity = \Drupal::entityTypeManager()->getStorage('node');

      if (empty($app_helper)) {
         $app_helper   = \Drupal::service('restapi_telkom.app_helper');
      }
      if (empty($themePath)) {
         $themeHandler = \Drupal::service('theme_handler');
         $themePath    = $themeHandler->getTheme($themeHandler->getDefault())->getPath();
      }
      if (empty($templates)) {
         $templateQuery = $entity
                           ->getQuery()
                           ->condition('status', 1)
                           ->condition('type', 'template_pricing_catalog');

         Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($templateQuery, 'field_tem_pct_type', ['paket']);
         $templates = $entity->loadMultiple($templateQuery->execute());
      }
      if (empty($templatesConfig)) {
         $configQuery = $entity
                        ->getQuery()
                        ->condition('status', 1)
                        ->condition('type', 'template_pricing_setting');
         $templatesConfig = $entity->loadMultiple($configQuery->execute());
      }

      // prepare html
      $htmlTemplate = array();

      // preparing settings data config
      $catalogSetting = !$entity_obj->get('field_pct_setting_temp_pricing')->isEmpty() ? json_decode($entity_obj->get('field_pct_setting_temp_pricing')->getString(), true) : [];

      // remove list setting if it's "id setting" in catalog set to false
      $settingsConfig = !empty($templatesConfig) ? array_filter(
         array_map(function($setting, $setting_id) use($catalogSetting){
            return isset($catalogSetting[$setting_id]) && $catalogSetting[$setting_id] === false ? null : $setting;
         }, $templatesConfig, array_keys($templatesConfig))
      ) : [];

      // preparing package data based on active config
      $packageData = !$entity_obj->get('field_pct_list_paket')->isEmpty() ? 
         array_map(function($res) use ($settingsConfig, $app_helper) {
            // prepare futher process package variable
            $speedValue     = (int) $res->field_pkt_speed->getString();
            $settingPackage = !$res->get('field_pkt_setting_temp_pricing')->isEmpty() ? json_decode($res->get('field_pkt_setting_temp_pricing')->getString(), TRUE) : [];
            $listBenefit    = !empty($settingsConfig) ? 
               array_map(function($setting) use ($settingPackage) {
                  return !empty($settingPackage[$setting->id()]) ? "<li>{$settingPackage[$setting->id()]}</li>" : null;
            }, $settingsConfig) : [];

            return [
               'uuid'       => $res->uuid(),
               'package_id' => !$res->field_pkt_package_id->isEmpty() ? current($res->field_pkt_package_id->getValue()[0]) : null,
               'title'      => $res->label(),
               'sub_title'  => $res->field_pkt_sub_title->getString(),
               'promo_text' => $res->field_pkt_promo_text->getString(),
               'speed'      => $app_helper->convertSpeedTelkom($speedValue),
               'price'      => $app_helper->convertPriceTelkom($res->field_pkt_price_total->getString()),
               'billing_period' => strtolower($res->field_pkt_billing_period->getString()),
               'link_image'     => !in_array($speedValue, [30,50,100]) ? 30 : $speedValue,
               'promo_label'    => !$res->field_pkt_promo_text->isEmpty() ? 'promo-package-label' : '',
               'list_benefit'   => !empty($listBenefit) ? array_filter($listBenefit) : [],
               'list_benefit_non_formatted' => preg_replace("/<.+>/sU", "", $listBenefit)
            ];
         }, $entity_obj->get('field_pct_list_paket')->referencedEntities()) : [];

      // only print html view when package data & setting data is present
      if (!empty($packageData) && !empty($settingsConfig)) :
         // make array of html view based on package data list
         foreach ($templates as $template_id => $template_data) {
            $templateName   = $template_data->field_temp_cat_html->getString();
            $templateType   = 'catalogTemplate' . preg_replace('~\D~', '', $templateName);
            $templateHTML   = html_entity_decode(\Drupal::service('restapi_telkom.html_helper')->$templateType($packageData, $settingsConfig, $entity_obj->label()));
            $templateCss    = "<link rel='stylesheet' href='{$_ENV['APP_URL']}/{$themePath}/css/pricing-table.css'>";

            if ($templateType === 'catalogTemplate6') {
               $templateCss .= "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css'/>";
            }

            $htmlTemplate[] = array(
               'template_id'   => $template_data->id(),
               'template_name' => $templateName,
               'template_html' => str_replace(["\r\n", "\r", "\n", "  "], '', $templateHTML),
               'css_link'      => $templateCss,
               'js_link'       => "<script src='{$_ENV['APP_URL']}/{$themePath}/dist/js/bootstrap.bundle.min.js'></script>"
            );
         };

         // clear memory
         unset($templateName, $templateType, $templateHTML, $template_id, $template_data);
      endif;

      // return data
      return array(
         'uuid'   => $entity_obj->uuid(),
         'name'   => $entity_obj->label(),
         'catalog_type' => $catalog_type,
         'package_view' => $htmlTemplate
      );
   }

   public function catalogCategory()
   {
      $terms = \Drupal::entityTypeManager()
         ->getStorage('taxonomy_term')
         ->loadByProperties([
            'vid'  => 'product_catalog_category'
         ]);

      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'     => !empty($terms) ? 'success' : 'failed',
         'message'    => !empty($terms) ? 'success to retrieve data' : 'theres no data available',
         'data'       => !empty($terms) ? array_map(function($category){
            return [
               'id'    => (int) $category->id(),
               'label' => $category->label()
            ];
         }, array_values($terms)) : array()
      ]);
   }

   public function createCatalog(Request $request)
   {
      // prepare request
      $title    = $request->request->get('title');
      $body     = $request->request->get('body');
      $tags     = $request->request->get('tags') ?? '';
      $packages = $request->request->get('package_list');
      $category = $request->request->get('categories') ?? [];
      $catalog_type = $request->request->get('catalog_type') ?? 'paket';

      if (empty($title) || empty($body) || empty($packages)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'request parameter not valid. title, body, package_list cannot be empty!',
            'data'    => []
         ], 400);
      };

      // validate catalog type
      if (!in_array($catalog_type, ['paket', 'retail+ebis'])) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'allowed catalog_type are paket, retail+ebis',
            'data'    => []
         ], 400);
      }

      // core data
      $catalogData = array();
      $entity      = \Drupal::entityTypeManager()->getStorage('node');
      $query  = $entity->getQuery()
         ->condition('status', 1);
      
      switch ($catalog_type) {
         case 'retail+ebis':
            // general list term in taxonomy "source_paket"
            $term_source_retail_ebis = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'source_paket', 'name'=> 'retail+ebis']);
            $term_source_retail_ebis = current($term_source_retail_ebis);
            if (!$term_source_retail_ebis) {
               return \Drupal::service('restapi_telkom.app_helper')->response([
                  'status'  => 'failed',
                  'message' => 'package retail+ebis is not exist',
                  'data'    => []
               ], 400);
            }
            $query->condition('type', 'paket')
                  ->condition('field_pkt_package_id', $packages, 'IN')
                  ->condition('field_pkt_source', [$term_source_retail_ebis->id()], 'IN');
            break;
         
         default:
            // default paket 
            $query->condition('type', 'paket')
                  ->condition('field_pkt_package_id', $packages, 'IN');
            break;
      }

      // load data
      $loadedDataItems = $entity->loadMultiple($query->execute());

      // release memory
      unset($query);

      if (!empty($loadedDataItems)) :
         $currentUser = \Drupal::service('restapi_telkom.app_helper')->getLoggedinUser();
         $terms = !empty($category) ? \Drupal::entityTypeManager()
            ->getStorage('taxonomy_term')
            ->loadByProperties([
               'vid'  => 'product_catalog_category',
               'name' => $category
            ]) : [];
         
         $catalog_types = $this->getCatalogType('label');

         // create new catalogue
         $catalogData = Node::create([
            'status' => 1,
            'type'   => 'product_catalog',
            'title'  => $title,
            'field_pct_type' => !empty($catalog_types[$catalog_type]) ? $catalog_types[$catalog_type] : [],
            'field_pct_category'    => !empty($terms) ? array_map(fn($category) => ['target_id' => $category->id()], array_values($terms)) : [],
            'field_pct_description' => $body,
            'field_pct_list_paket'  => array_map(fn($data) => ['target_id' => $data->id()], array_values($loadedDataItems)),
            'field_pct_tags'        => $tags,
            'field_workflow_status' => 'workflow_status_approve',
            'uid' => $currentUser['nid']
         ]);
         $catalogData->save();
      endif;
      
      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'     => !empty($catalogData) ? 'success' : 'failed',
         'message'    => !empty($catalogData) ? 'success to create new data' : 'failed to record data. Please check your submitted package_list and catalog_type',
         'data'       => !empty($catalogData) ? [
            'id'    => (int) $catalogData->id(),
            'uuid'  => $catalogData->uuid(),
            'title' => $catalogData->label()
         ] : array()
      ]);
   }
}