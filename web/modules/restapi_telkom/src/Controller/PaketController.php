<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use PaketHelper;
use Symfony\Component\HttpFoundation\Request;

class PaketController extends ControllerBase{

   public function paket_list(Request $request)
   {
      $result = [];
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery();

      $search_query = $request->query->get('search');
      $perpage = $request->query->get('perpage') ?? 10;
      $page = $request->query->get('page') ?? 1;

      $query = $query
         ->condition('status', 1)
         ->condition('type', 'paket')
         ->condition('title', "%{$search_query}%", 'LIKE')
         ->condition('field_pkt_master_data', '', '<>');

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
            'package_id'   => $entity_obj->field_pkt_package_id->getString(),
            'package_flag' => $entity_obj->field_pkt_flag->getString(),
            'package_type' => !empty($entity_obj->field_pkt_tipe_paket->referencedEntities()) ? $entity_obj->field_pkt_tipe_paket->referencedEntities()[0]->label() : '',
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

   public function paket_detail(Request $request, $paket_id = null)
   {
      if (empty($paket_id)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'paket id cannot be empty',
            'data'    => []
         ], 400);
      };

      $result       = array();
      $html_data    = array();
      $app_helper   = \Drupal::service('restapi_telkom.app_helper');
      $themeHandler = \Drupal::service('theme_handler');
      $entityNode   = \Drupal::entityTypeManager()->getStorage('node');
      $themePath    = $themeHandler->getTheme($themeHandler->getDefault())->getPath();

      // check is this paket id using uuid or raw id?
      if (\Drupal::service('restapi_telkom.app_helper')->isValidUuid($paket_id)) {
         $data = $entityNode->loadByProperties(['uuid' => $paket_id]);
      }else{
         $query = $entityNode->getQuery()
            ->condition('status', 1)
            ->condition('type', 'paket')
            ->condition('field_pkt_package_id', $paket_id)
            ->execute();

         $loaded_nid = current($query);
         $data = $loaded_nid ? [$loaded_nid => $entityNode->load($loaded_nid)] : null;
      };

      if (!empty($data)) :
         // prepare main variable
         $result_toggle = array();
         $entity_obj    = current($data);
         $paketSetting  = json_decode($entity_obj->field_pkt_setting_temp_pricing->getString(), true);

         // get template & settings query
         $templateQuery = $entityNode->getQuery()
            ->condition('status', 1)
            ->condition('type', 'template_pricing_paket');

         Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($templateQuery, 'field_tem_pkt_type', ['paket']);
         $templateQuery = $templateQuery->execute();
         $templates     = $entityNode->loadMultiple($templateQuery);

         $settingQuery = $entityNode->getQuery()
            ->condition('status', 1)
            ->condition('type', 'template_pricing_setting')
            ->execute();
         $settings = $entityNode->loadMultiple($settingQuery);

         // release memory
         unset($templateQuery, $settingQuery, $data);

         // import PaketHelper
         require_once __DIR__ . "/../../../../themes/custom/telkom_cms/functions/paket/helper.php";
         foreach (PaketHelper::list_field_detail($entity_obj) as $field) {
            $result_toggle[$field['name']] = $field['value'];
         }

         // register major package data first
         $result = array_merge(array(
            'uuid'         => $entity_obj->uuid(),
            'name'         => $entity_obj->label(),
            'module'       => $entity_obj->getType(),
            'package_id'   => $entity_obj->field_pkt_package_id->getString(),
            'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         ), $result_toggle);

         // release memory
         unset($result_toggle, $showingMode, $entity_obj);

         // process to built html data
         if (!empty($templates)) {
            // listing benefiy
            $list_benefit = array_map(function($setting) use ($paketSetting) {
               return !empty($paketSetting[$setting]) ? "<li>{$paketSetting[$setting]}</li>" : null;
            }, array_keys($settings));
            $list_benefit  = !empty($list_benefit) ? implode('', array_filter($list_benefit)) : [];

            // built html data
            foreach ($templates as $template_id => $template) {
               $template_html = str_replace('"', "'", $template->field_temp_pkt_html->getString());

               $template_html = str_replace('%title%', $result['title'], $template_html);
               $template_html = str_replace('%sub_title%', $result['sub_title'], $template_html);
               $template_html = !empty($paket_data['promo_text']) ? 
                  str_replace('%promo_text%', $result['promo_text'], $template_html) : 
                  str_replace(['promo-package-label', '%promo_text%'], '', $template_html);
               $template_html = str_replace('%speed%', $result['speed'], $template_html);
               $template_html = str_replace('%price%', $result['price_total'], $template_html);
               $template_html = str_replace('%billing_period%', $result['billing_period'], $template_html);

               if (!empty($list_benefit)) {
                  $template_html = str_replace('%list_benefit%', $list_benefit, $template_html);
               };

               $html_data[] = [
                  'template_id' => (int) $template_id,
                  'title'       => $template->label(),
                  'element'     => str_replace(["\r\n", "\r", "\n", "  "], '', html_entity_decode($template_html)),
                  'css_link'    => "<link rel='stylesheet' href='{$_ENV['APP_URL']}/{$themePath}/css/pricing-table.css'>",
                  'js_link'     => "<script src='{$_ENV['APP_URL']}/{$themePath}/dist/js/bootstrap.bundle.min.js'></script>"
               ];
            }
         };
      endif;

      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data related to selected paket id',
         'data'    => $result,
         'html'    => $html_data
      ]);
   }

   public function paket_search(Request $request)
   {
      // prepare variable
      $result = array();
      $app_helper   = \Drupal::service('restapi_telkom.app_helper');
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

      if (!empty($loadedData)) {
         $themeHandler = \Drupal::service('theme_handler');
         $themePath    = $themeHandler->getTheme($themeHandler->getDefault())->getPath();

         // get template & settings query
         $templateQuery = $entity->getQuery()
            ->condition('status', 1)
            ->condition('type', 'template_pricing_paket');

         Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($templateQuery, 'field_tem_pkt_type', ['paket']);
         $templateQuery = $templateQuery->execute();
         $templates     = $entity->loadMultiple($templateQuery);

         $settingQuery = $entity->getQuery()
            ->condition('status', 1)
            ->condition('type', 'template_pricing_setting')
            ->execute();
         $settings = $entity->loadMultiple($settingQuery);

         // release memory
         unset($themeHandler, $templateQuery, $settingQuery);

         foreach ($loadedData as $entity_id => $entity_obj) :
            // prepare main variable
            $result_toggle = array();
            $showingMode   = json_decode($entity_obj->field_pkt_master_data_edited->getString(), TRUE);
            $paketSetting  = json_decode($entity_obj->field_pkt_setting_temp_pricing->getString(), true);

            // built major package data
            foreach ($showingMode['field'] as $index => $value) {
               if ($value['hidden']) continue;

               switch ($index) {
                  case 'addon_list':
                     $result_toggle[$index] = array_map(function($res){
                        return [
                           'uuid' => $res->uuid(),
                           'name' => $res->label(),
                           'description' => $res->field_add_detail_package->getString(),
                           'price_total' => (float) $res->field_add_price_total->getString()
                        ];
                     }, $entity_obj->get('field_pkt_addon_list')->referencedEntities());
                  break;

                  case 'speed':
                     $result_toggle['speed'] = (int) $app_helper->convertSpeedTelkom($entity_obj->{'field_pkt_'.$index}->getString());
                  break;

                  case 'price_voice':
                  case 'price_internet':
                  case 'price_total':
                     $result_toggle[$index] = $app_helper->convertPriceTelkom(!$entity_obj->get("field_pkt_{$index}")->isEmpty() ? $entity_obj->{'field_pkt_'.$index}->getString() : 0);
                  break;

                  case 'tipe_paket':
                     $result_toggle['package_type'] = !empty($entity_obj->{'field_pkt_'.$index}->getString()) ? $entity_obj->{'field_pkt_'.$index}->referencedEntities()[0]->label() : '';
                  break;
                  
                  case 'title':
                     $result_toggle[$index] = $entity_obj->label();
                  break;

                  default:
                     $result_toggle[$index] = !$entity_obj->get("field_pkt_{$index}")->isEmpty() ? $entity_obj->get("field_pkt_{$index}")->getString() : '';
                  break;
               };
            };

            // register major package data first
            $result_toggle = array_merge(array(
               'uuid'         => $entity_obj->uuid(),
               'name'         => $entity_obj->label(),
               'module'       => $entity_obj->getType(),
               'package_id'   => (int) $entity_obj->field_pkt_package_id->getString(),
               'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
               'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
            ), $result_toggle);

            // release memory
            unset($showingMode, $entity_obj);

            // process to built html data
            if (!empty($templates)) {
               // listing benefiy
               $list_benefit = array_map(function($setting) use ($paketSetting) {
                  return !empty($paketSetting) && !empty($paketSetting[$setting]) ? "<li>{$paketSetting[$setting]}</li>" : null;
               }, array_keys($settings));
               $list_benefit  = !empty($list_benefit) ? implode('', array_filter($list_benefit)) : [];

               // built html data
               foreach ($templates as $template_id => $template) {
                  $template_html = str_replace('"', "'", $template->field_temp_pkt_html->getString());

                  $template_html = str_replace('%title%', $result_toggle['title'], $template_html);
                  $template_html = str_replace('%sub_title%', $result_toggle['sub_title'], $template_html);
                  $template_html = !empty($paket_data['promo_text']) ? 
                     str_replace('%promo_text%', $result_toggle['promo_text'], $template_html) : 
                     str_replace(['promo-package-label', '%promo_text%'], '', $template_html);
                  $template_html = str_replace('%speed%', $result_toggle['speed'], $template_html);
                  $template_html = str_replace('%price%', $result_toggle['price_total'], $template_html);
                  $template_html = str_replace('%billing_period%', $result_toggle['billing_period'], $template_html);

                  if (!empty($list_benefit)) {
                     $template_html = str_replace('%list_benefit%', $list_benefit, $template_html);
                  };

                  $html_data[] = [
                     'template_id' => (int) $template_id,
                     'title'       => $template->label(),
                     'element'     => str_replace(["\r\n", "\r", "\n", "  "], '', html_entity_decode($template_html)),
                     'css_link'    => "<link rel='stylesheet' href='{$_ENV['APP_URL']}/{$themePath}/css/pricing-table.css'>",
                     'js_link'     => "<script src='{$_ENV['APP_URL']}/{$themePath}/dist/js/bootstrap.bundle.min.js'></script>"
                  ];
               }

               unset($list_benefit, $template_html);
            };

            // register major package data first
            $result_toggle['html_data'] = !empty($html_data) ? $html_data : [];

            // register major result data
            $result[] = $result_toggle;
         endforeach;
      };
      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data related to selected paket id',
         'data'    => $result
      ]);
   } 

}