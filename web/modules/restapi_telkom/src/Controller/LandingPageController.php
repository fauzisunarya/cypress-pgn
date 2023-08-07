<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\Component\Utility\Crypt;

class LandingPageController extends ControllerBase{

   public function list(Request $request)
   {
      $result = [];
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery();

      $search_query = $request->query->get('search');
      $perpage      = $request->query->get('perpage') ?? 10;
      $page         = $request->query->get('page') ?? 1;
      $landing_type = strtolower($request->query->get('landing_type') ?? '');
      $platform     = $request->query->get('platform') ?? '';


      // landing page type validation
      if (!empty($landing_type) && !in_array($landing_type, ['psb-retail', 'psb-retail-ebis'])) {
         // to see allowed landing page, see function landingTypeValidation description
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'allowed landing_type are ' . implode(', ', array_keys($this->landingTypeMapping())),
            'data'    => []
         ], 400);
      }

      $query = $query
         ->condition('status', 1)
         ->condition('type', 'landing')
         ->condition('title', "%{$search_query}%", 'LIKE')
         ->condition('field_lan_website_full', '', '<>');

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

      // filter by landing platform source (ex: mydita)
      if(!empty($platform)){
        // source landing page
        $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'landing_platform_source']);
        $arr_landing_source = [];
        foreach ($terms as $term) {
          $arr_landing_source[strtolower($term->label())] = $term->id();
        }
        unset($terms);

        if (!empty($arr_landing_source[strtolower($platform)])) {
          $query->condition('field_lan_platform_source', $arr_landing_source[strtolower($platform)], '=');
        }
        else{
          $query->condition('field_lan_platform_source', 0, '=');
        }
        unset($arr_landing_source);
      }

      // filter by landing type
      if (!empty($landing_type)) {
         list(
            'landing_type_id' => $landing_type_id
         ) = $this->landingTypeValidation($landing_type);
         if (!empty($landing_type_id)) {
            $query->condition('field_lan_landing_type', $landing_type_id, '=');
         }
      }

      $raw_query = preg_replace('/SELECT[^FROM]*/is', 'SELECT COUNT("base_table"."vid") AS "total" ', (String)$query);
      $raw_total = \Drupal::database()->query($raw_query)->fetchObject();

      $datas = $query->range((($page * $perpage) - $perpage), $perpage)->execute();

      $mappingLandingType = array_flip($this->landingTypeMapping());

      foreach ($entity->loadMultiple($datas) as $entity_id => $entity_obj) :
         $preview_url = !$entity_obj->get('field_lan_website_preview')->isEmpty() ? $entity_obj->field_lan_website_preview->getString() : '';
         $type = !empty($entity_obj->field_lan_landing_type->referencedEntities()) ? strtolower($entity_obj->field_lan_landing_type->referencedEntities()[0]->label()) : '';
         $result[] = array(
            'uuid'                => $entity_obj->uuid(),
            'name'                => $entity_obj->label(),
            'module'              => $entity_obj->getType(),
            'landing_type'        => !empty($type) && !empty($mappingLandingType[$type]) ? $mappingLandingType[$type] : '',
            'landing_description' => $entity_obj->field_lan_website_description->getString(),
            'landing_url'         => \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($entity_obj),
            'landing_full_url'    => \Drupal::service('media_upload.shortlink_helper')->get_landing_full_link($entity_obj),
            'landing_preview'     => !empty($preview_url) ? "{$_ENV['APP_URL']}/restapi/v1/media_render/{$preview_url}" : '',
            'created_date'        => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_edited'         => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         );
      endforeach;
      
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

   public function detail(Request $request, $landing_page_id)
   {
      if (empty($landing_page_id)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'landing page id cannot be empty',
            'data'    => []
         ], 400);
      };

      $result = [];
      $entity = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $landing_page_id]);

      if (!empty($entity)) :
         $entity_obj  = current($entity);
         $preview_url = !$entity_obj->get('field_lan_website_preview')->isEmpty() ? $entity_obj->field_lan_website_preview->getString() : '';

         $mappingLandingType = array_flip($this->landingTypeMapping());
         $type = !empty($entity_obj->field_lan_landing_type->referencedEntities()) ? strtolower($entity_obj->field_lan_landing_type->referencedEntities()[0]->label()) : '';

         $result = array(
            'uuid'   => $entity_obj->uuid(),
            'name'   => $entity_obj->label(),
            'module' => $entity_obj->getType(),
            'landing_id'          => (int) $entity_obj->id(),
            'landing_type'        => !empty($type) && !empty($mappingLandingType[$type]) ? $mappingLandingType[$type] : '',
            'landing_description' => $entity_obj->field_lan_website_description->getString(),
            'landing_url'         => \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($entity_obj),
            'landing_full_url'    => \Drupal::service('media_upload.shortlink_helper')->get_landing_full_link($entity_obj),
            'landing_meta'        => $entity_obj->field_lan_website_meta->getString(),
            'landing_preview'     => !empty($preview_url) ? "{$_ENV['APP_URL']}/restapi/v1/media_render/{$preview_url}" : '',
            'landing_styling'     => $entity_obj->field_lan_website_style->getString(),
            'product_catalog'     => array_map(function($res){
               return [
                  'nid'  => $res->id(),
                  'uuid' => $res->uuid(),
                  'name' => $res->label()
               ];
            }, $entity_obj->get('field_lan_product_catalog')->referencedEntities()),
            'pages' => Drupal::service('restapi_telkom.landing_helper')->setLanding($entity_obj)->getPages(),
            'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
            'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
         );
      endif;

      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'  => !empty($result) ? 'success' : 'failed',
         'message' => !empty($result) ? 'success to retrieve data' : 'theres no data related to selected uuid',
         'data'    => $result
      ]);
   }

   public function createLanding(Request $request)
   {
      // prepare request
      $title    = $request->request->get('title');
      $landing_uuid = $request->request->get('landing_uuid');
      $catalog_list = $request->request->get('catalog_list');
      $landing_type = $request->request->get('landing_type') ?? '';
      $parameter = $request->request->get('parameter');

      // general validation
      if (empty($title) || empty($landing_uuid) || empty($catalog_list)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'request parameter not valid. title, landing_uuid, catalog_list cannot be empty!',
            'data'    => []
         ], 400);
      };
      
      // landing validation
      $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $landing_uuid]);
      $landing = reset($landing);
      if ( empty($landing) || $landing->type->entity->get('type')!='landing') {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'invalid landing_uuid',
            'data'    => []
         ], 400);
      }

      // landing page type validation
      $landing_type = !empty($landing_type) ? $landing_type : 'psb-retail';
      list(
         'landing_type_id' => $landing_type_id,
         'landing_type_name' => $landing_type_name,
         'only_allow_catalog_type' => $only_allow_catalog_type
      ) = $this->landingTypeValidation($landing_type);

      if (empty($landing_type_id)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'allowed landing_type are ' . implode(', ', array_keys($this->landingTypeMapping())),
            'data'    => []
         ], 400);
      }

      // catalog validation
      $entity      = \Drupal::entityTypeManager()->getStorage('node');
      $queryCatalog  = $entity->getQuery()
         ->condition('status', 1)
         ->condition('type', 'product_catalog');

      $or_condition = $queryCatalog->orConditionGroup()
         ->condition('uuid', $catalog_list, 'IN')
         ->condition('nid', $catalog_list, 'IN');

      $queryCatalog->condition($or_condition);

      // only laod specific catalog type => landing ebis only has catalog ebis, landing retail only has catalog retail
      Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($queryCatalog, 'field_pct_type', $only_allow_catalog_type);

      // load data
      $loadedCatalog = $entity->loadMultiple($queryCatalog->execute());
      if (empty($loadedCatalog)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'invalid catalog id or catalog is not allowed with submitted landing_type',
            'data'    => []
         ], 400);
      }

      // landing owner validation
      $landingOwner = null;
      if (!empty($parameter) && is_array($parameter) && !empty($parameter['user']) && !empty($parameter['user']['email'])) {
         $resultUser = $this->registerUser($parameter['user']['email']);
         if ($resultUser['status'] === false) {
            return \Drupal::service('restapi_telkom.app_helper')->response([
               'status'  => 'failed',
               'message' => $resultUser['message'],
               'data'    => []
            ], 400);
         }
         $landingOwner = $resultUser['data'];
         unset($resultUser);
      }

      $currentUser = \Drupal::service('restapi_telkom.app_helper')->getLoggedinUser();
      $new_landing = \Drupal::service('media_upload.landing_helper')->clone_landing($landing, $currentUser['nid']);

      if (!empty($new_landing)) {
         $new_landing->title = $title;
         $new_landing->field_lan_product_catalog = array_map(fn($catalog_obj)=> ['target_id' => $catalog_obj->id()] , array_values($loadedCatalog));
         $new_landing->field_lan_landing_type = $landing_type_id;
         // reset field
         $new_landing->field_lan_subdomain = "";
         $new_landing->field_lan_domain = "";
         $new_landing->field_lan_owner = $landingOwner ?? null;
         $new_landing->save();

         // regenerate catalog pricing in each page
         $entity = \Drupal::entityTypeManager()->getStorage('node');
         $query = $entity->getQuery();
         $ids = $query->condition('status', 1)
                     ->condition('type', 'landing_page')#type = bundle id (machine name)
                     ->condition('field_page_landing_id', $new_landing->id()) //list page for landing page
                     #->sort('created', 'ASC') #sorted
                     #->pager(15) #limit 15 items
                     ->execute();

         // for optional parameter
         $custom_form_id = null;
         if (!empty($parameter) && !empty($parameter['platform'])) {
            if (!empty($parameter['show_block']) && is_array($parameter['show_block'])) {

               $showed_block = $parameter['show_block'];
               
               // to see available form id, check "function get_default_forms" in LandingForm.php
               if ($landing_type_name==='ao/psb retail') {
                  foreach ($showed_block as $showed) {
                     switch ((string)$showed) {
                        case 'form_basic':
                           $custom_form_id = 'psb-mydita-basic';
                           break;
                        case 'form_basic_map':
                           $custom_form_id = 'psb-mydita-basic-map';
                           break;
                        case 'form_basic_map_file':
                           $custom_form_id = 'psb-mydita-basic-map-file';
                           break;
                        default:
                           break;
                     }
                  }
               }
               elseif ($landing_type_name==='ao/psb retail+ebis'){
                  foreach ($showed_block as $showed) {
                     switch ((string)$showed) {
                        case 'form_psb_retail_ebis':
                           $custom_form_id = 'psb-retail-ebis';
                           break;
                        case 'form_psb_retail_ebis_map':
                           $custom_form_id = 'psb-retail-ebis-map';
                           break;
                        case 'form_psb_retail_ebis_map_file':
                           $custom_form_id = 'psb-retail-ebis-map-file';
                           break;
                        default:
                           break;
                     }
                  }
               }

               if (!empty($custom_form_id)) {
                $showed_block[] = 'form';
               }

            }

            if (!empty($parameter['channel_id'])) {
               $new_landing->field_lan_channel_id = trim($parameter['channel_id']);
            }

            // source landing page
            $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'landing_platform_source']);
            $arr_landing_source = [];
            foreach ($terms as $term) {
              $arr_landing_source[strtolower($term->label())] = $term->id();
            }
            unset($terms);

            if (!empty($arr_landing_source[strtolower($parameter['platform'])])) {
              $new_landing->field_lan_platform_source = $arr_landing_source[strtolower($parameter['platform'])];
            }
            unset($arr_landing_source);

            // save landing
            $new_landing->save();
         }

         foreach ($entity->loadMultiple($ids) as $page) {
            $blockData = json_decode($page->field_website_page_component->getString(), true);
            if (!empty($blockData)) {

               $newBlockData = [];
               foreach ($blockData as $block) {
                  $block_id = strtolower($block['blockID']);
                  if (str_contains($block_id,'contact-') && !empty($block['data']) && !empty($block['data']['form'])) {
                     if (!empty($custom_form_id)) {
                        $block['data']['form']['value'] = $custom_form_id;
                     }
                     if (!empty($showed_block) && in_array('form', $showed_block)) {
                        $newBlockData[] = $block;
                        unset($block);
                     }
                  }
                  // this is package block
                  elseif (str_contains($block_id,'pricing-')) {
                     if (!empty($showed_block) && in_array('pricing_template', $showed_block)) {
                        $newBlockData[] = $block;
                        unset($block);
                     }
                  }
                  else {
                     $newBlockData[] = $block;
                     unset($block);
                  }
               }
               unset($blockData);

               $page->field_website_page_component = json_encode(\Drupal::service('media_upload.landing_helper')->retrievePricing($new_landing->uuid(), $newBlockData));
               $page->save();

               unset($page);
               
            }
         }
      }

      $returnData = [
         'status'     => !empty($new_landing) ? 'success' : 'failed',
         'message'    => !empty($new_landing) ? 'success to create new data' : 'failed to record data',
         'data'       => !empty($new_landing) ? [
            'id'    => (int) $new_landing->id(),
            'uuid'  => $new_landing->uuid(),
            'title' => $new_landing->label(),
            'url' => \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($new_landing),
            'landing_full_url' => \Drupal::service('media_upload.shortlink_helper')->get_landing_full_link($new_landing),
         ] : array()
      ];

      if ($returnData['data']['id'] && $landingOwner) {
         $returnData['data']['user'] = [
            'email' => $landingOwner->getEmail(),
            'username' => $landingOwner->getDisplayName()
         ];
      }
      
      return \Drupal::service('restapi_telkom.app_helper')->response($returnData);
   }

   /**
    * Format : "showed in api" => "real name in cms"
    */
   private function landingTypeMapping() {
      return [
         'psb-retail' => 'ao/psb retail', 
         'psb-retail-ebis' => 'ao/psb retail+ebis'
      ];
   }

   /**
    * Allowed landing type are (see landingTypeMapping): 
    * 'psb-retail' => 'ao/psb retail', 
    * 'psb-retail-ebis' => 'ao/psb retail+ebis'
    */
   private function landingTypeValidation(string $landing_type) {

      if (!in_array($landing_type, array_keys($this->landingTypeMapping()))) {
         return [
            'landing_type_id' => null,
            'landing_type_name' => null,
            'only_allow_catalog_type' => null
         ];
      }

      $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'landing_page_type']);
      $arr_landing_type = [];
      foreach ($terms as $term) {
        $arr_landing_type[strtolower($term->label())] = (int)$term->id(); // Ex: ["label" => id]
      }
      unset($terms);

      $landing_type_name = 'ao/psb retail';
      $landing_type_id = $arr_landing_type['ao/psb retail']; //default for retail
      $only_allow_catalog_type = ['paket']; //default for retail

      if (!empty($landing_type)) {
        switch ($landing_type) {
          case 'psb-retail-ebis':
            $only_allow_catalog_type = ['retail+ebis'];
            $landing_type_id = $arr_landing_type['ao/psb retail+ebis'];
            $landing_type_name = 'ao/psb retail+ebis';
            break;
          case 'psb-retail':
            // do nothing
            break;
          
          default:
            # code...
            break;
        }
        unset($landing_type);
      }

      return [
         'landing_type_id' => $landing_type_id,
         'landing_type_name' => $landing_type_name,
         'only_allow_catalog_type' => $only_allow_catalog_type
      ];
   }

   /**
    * Register landing owner
    */
   private function registerUser(string $email) {

      if (!empty($email)) {
         $email = strtolower($email);

         $idsEmail = \Drupal::entityQuery('user')
               ->condition('mail', $email)
               ->execute();

         if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email) || !preg_match("/.*[a-z]+.*@.+/", $email)) {
            return [
               'status' => false,
               'message' => "invalid email address",
               'data' => null
            ];
         } 
         else if (!empty($idsEmail)) {
            return [
               'status' => false,
               'message' => "email \"{$email}\" already exist",
               'data' => null
            ];
         }

         $user = User::create();
         $user->setPassword(Crypt::hashBase64(time() . $_ENV['FORM_EMBEDDED_SECRET_KEY'] . '- oke'));
         $user->enforceIsNew();
         $user->setEmail($email);
         $user->set('field_user_name', explode('@', preg_replace("/[0-9]/", '', $email))[0], false);

         $user->setUsername('username'); //This username will be replaced by module auto username
         $user->addRole('landing_owner');
         $user->set('status', 1);

         $user->save();

         return [
            'status' => true,
            'message' => 'success',
            'data' => $user
         ];
      }

      return [
         'status' => false,
         'message' => 'user email is required',
         'data' => null
      ];
   }

   public function updateLanding(Request $request){
      $landing_uuid = $request->request->get('landing_uuid');
      $catalog_list = $request->request->get('catalog_list');

      // general validation
      if (empty($landing_uuid) || empty($catalog_list)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'request parameter not valid. landing_uuid and catalog_list cannot be empty!',
            'data'    => []
         ], 400);
      };

      $currentUser = \Drupal::service('restapi_telkom.app_helper')->getLoggedinUser();

      // landing validation
      $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $landing_uuid]);
      $landing = reset($landing);
      if ( empty($landing) || $landing->type->entity->get('type')!='landing') {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'invalid landing_uuid',
            'data'    => []
         ], 400);
      }

      // user (owner) validation
      if ( $landing->getOwnerId() != $currentUser['nid']) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'invalid user, can only update your own landing page',
            'data'    => []
         ], 400);
      }

      // landing type validation (for catalog)
      $landing_type = $landing->field_lan_landing_type->referencedEntities();
      $only_allow_catalog_type = '';
      if (!empty($landing_type)) {
         $landing_type = strtolower($landing_type[0]->label());
         $mappingLandingType = array_flip($this->landingTypeMapping());
         if (!empty($mappingLandingType[$landing_type])) {
            list(
               'only_allow_catalog_type' => $only_allow_catalog_type
            ) = $this->landingTypeValidation($mappingLandingType[$landing_type]);
         }
      }
      if (empty($only_allow_catalog_type)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'something error with landing page type',
            'data'    => []
         ], 400);
      }

      // catalog validation
      $entity      = \Drupal::entityTypeManager()->getStorage('node');
      $queryCatalog  = $entity->getQuery()
         ->condition('status', 1)
         ->condition('type', 'product_catalog');

      $or_condition = $queryCatalog->orConditionGroup()
         ->condition('uuid', $catalog_list, 'IN')
         ->condition('nid', $catalog_list, 'IN');

      $queryCatalog->condition($or_condition);

      // only laod specific catalog type => landing ebis only has catalog ebis, landing retail only has catalog retail
      Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($queryCatalog, 'field_pct_type', $only_allow_catalog_type);

      // load data
      $loadedCatalog = $entity->loadMultiple($queryCatalog->execute());
      if (empty($loadedCatalog)) {
         return \Drupal::service('restapi_telkom.app_helper')->response([
            'status'  => 'failed',
            'message' => 'invalid catalog id or catalog is not allowed with landing_type',
            'data'    => []
         ], 400);
      }

      // update landing catalog
      $landing->field_lan_product_catalog = array_map(fn($catalog_obj)=> ['target_id' => $catalog_obj->id()] , array_values($loadedCatalog));
      $landing->save();

      return \Drupal::service('restapi_telkom.app_helper')->response([
         'status'     => 'success',
         'message'    => 'success to update data',
         'data'       => [
            'id'    => (int) $landing->id(),
            'uuid'  => $landing->uuid(),
            'title' => $landing->label(),
            'url' => \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($landing),
            'landing_full_url'  => \Drupal::service('media_upload.shortlink_helper')->get_landing_full_link($landing),
         ]
      ]);

   }
}