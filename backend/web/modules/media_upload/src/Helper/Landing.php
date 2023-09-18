<?php

namespace Drupal\media_upload\Helper;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use stdClass;
use Drupal;

class Landing {

  /**
   * Delete relation when delete landing page
   */
  public function delete_landing_data($landing=null){
    if (!$landing || $landing->bundle()!=='landing') {
      return;
    }
    $landing_id = $landing->id();

    $entity = \Drupal::entityTypeManager()->getStorage('node');

    $query = $entity->getQuery();
    $ids = $query
      ->condition('type', 'landing_page')#type = bundle id (machine name)
      ->condition('field_page_landing_id', $landing_id)
      ->execute();
    
    // delete pages
    foreach ($entity->loadMultiple($ids) as $page) {
      $page->delete();
    }

    // get form
    $query = $entity->getQuery();
    $ids = $query
      ->condition('type', 'landing_custom_form')
      ->condition('field_lcf_landing_ref', $landing_id) //list form for landing page
      ->execute();
    
    foreach ($entity->loadMultiple($ids) as $form_id => $custom_form) {
      // delete form scheme
      $custom_form->delete(); // form post data will be deleted from hook delete for condition type = landing_custom_form
    }
  }

  /**
   * Delete post data when delete form scheme
   */
  public function delete_landing_form($form=null){
    if (!$form || $form->bundle()!=='landing_custom_form') {
      return;
    }

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $form_id = $form->id();
    // form post
    $query = $entity->getQuery();
    $ids = $query
      ->condition('type', 'landing_custom_form_post')
      ->condition('field_lcfp_form_id', $form_id) //list form post for landing page
      ->execute();
    
    foreach ($entity->loadMultiple($ids) as $post_id => $post) {
      // form post meta data
      $query = $entity->getQuery();
      $ids = $query
        ->condition('type', 'landing_custom_form_post_meta')
        ->condition('field_lcfpm_form_post_id', $post_id) //list form post meta for landing page
        ->execute();
      
      foreach ($entity->loadMultiple($ids) as $post_meta) {
        // delete meta data
        $post_meta->delete();
      }

      // delete post data
      $post->delete();
    }
  }

  /**
   * clone landing
   * 
   * @param object $landing to be cloned
   * @param int $user_id to assign in cloned node
   * 
   * @return object $new_node
   */
  public function clone_landing($landing=null, int $user_id=null){
    if ($landing===null || $landing->type->entity->get('type')!=='landing' ) {
      return '';
    }

    $new_landing = $this->clone_node($landing, $user_id);

    if ($new_landing) {
      $entity = \Drupal::entityTypeManager()->getStorage('node');
  
      // get the form id to replace in new page. flow = ['old_form_id'=> 'new_form_id'], new_form_id will replace the old, in block, in new page
      $formList = [];
  
      $query = $entity->getQuery();
      $ids = $query->condition('status', 1)
        ->condition('type', 'landing_custom_form')
        ->condition('field_lcf_landing_ref', $landing->id()) //list form for landing page
        ->execute();
      
      foreach ($entity->loadMultiple($ids) as $custom_form) {
        // clone form and set to new landing
        $new_custom_form = $this->clone_node($custom_form, $user_id, false);
        $new_custom_form->field_lcf_landing_ref = $new_landing->id();
        $new_custom_form->save();
  
        $formList[$custom_form->id()] = $new_custom_form->id();
        
        unset($custom_form);
      }

      // clone pages
      $query = $entity->getQuery();
      $ids = $query->condition('status', 1)
        ->condition('type', 'landing_page')#type = bundle id (machine name)
        ->condition('field_page_landing_id', $landing->id())
        ->execute();
      
      $pages = $entity->loadMultiple($ids);

      // replace page_id in menu with new_page_id
      $pageList = []; //['old_id'=>'new_id']

      $newPages = [];
      foreach ($pages as $page) {
        $new_page = $this->clone_node($page, $user_id, false);
        $new_page->field_page_landing_id = $new_landing->id();
        $new_page->save();

        $pageList[$page->id()] = $new_page->id();
        $newPages[] = $new_page;

        unset($page, $newPage);
      }

      foreach ($newPages as $newPage) {
        // replace blocks value to new one
        $page_blocks = json_decode($newPage->field_website_page_component->getString());
        if ($page_blocks) {

          $page_blocks = array_map(function($block) use($formList, $pageList) {
            
            // the form (is in contact from)
            if (str_contains(strtolower($block->blockID),'contact-') && !empty($block->data) && !empty($block->data->form) && !empty($block->data->form->value)) {
              $form_id = $block->data->form->value;
              $block->data->form->value = $formList[$form_id];
              return $block;
            }
            // link redirect in pricing table
            else if (str_contains(strtolower($block->blockID),'pricing-') && !empty($block->data) && !empty($block->data->package_link) && !empty($block->data->package_link->value)) {
              $block->data->package_link->value = $pageList[ $block->data->package_link->value ];
              return $block;
            }
            return $block;

          }, $page_blocks);

          $newPage->field_website_page_component = json_encode($page_blocks);
          $newPage->save();

          unset($newPage);
        }
      }

      // replace page_id in menu with new_page_id
      $website_menu = json_decode($new_landing->field_lan_website_menu->getString(), true);
      $website_menu = array_map(function($menu)use($pageList){
        if (!empty($menu['id']) && preg_match("/^\d+$/", $menu['id'])) {
          $menu['id'] = $pageList[(int) $menu['id']];
        }
        return $menu;
      }, $website_menu);
      $new_landing->field_lan_website_menu = json_encode($website_menu);
      $new_landing->save();

      return $new_landing;
    }

    return null;
  }

  /**
   * clone node
   * 
   * @param object $node to be cloned
   * @param int $user_id to assign in cloned node
   * 
   * @return object $new_node
   */
  public function clone_node($node = null, int $user_id=null, $save_cloned_node = true){
    if ($node===null) {
      return null;
    }

    $storage = \Drupal::entityTypeManager()->getStorage('node');

    $original_values = $node->toArray();
    // assign content type as string, the array causes an error when creating a new node
    $original_values['type'] = $node->bundle();

    // remove nid and uuid, the cloned node is assigned new ones when saved
    unset($original_values['nid']);
    unset($original_values['uuid']);

    // remove revision data, the latest revision becomes the only one in the new node
    unset($original_values['vid']);
    unset($original_values['revision_translation_affected']);
    unset($original_values['revision_uid']);
    unset($original_values['revision_log']);
    unset($original_values['revision_timestamp']);

    $cloned_content = $storage->create($original_values);

    if ($cloned_content->hasField('field_workflow_status')) {
      $cloned_content->field_workflow_status = 'workflow_status_approve';
    }

    $cloned_content->uid = !empty($user_id) ? $user_id : \Drupal::currentUser()->id();
    $cloned_content->created = \Drupal::time()->getRequestTime();
    $cloned_content->changed = \Drupal::time()->getRequestTime();

    if ($save_cloned_node) {
      $cloned_content->save();
    }

    return $cloned_content;
  }

  /**
   * Replace block pricing with actual catalog data
   */
  public function retrievePricing(string $project_id = '', array $arrayCode = array(), bool $associative = true )
  {
    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $project_id]);
    $landing = current($landing);

    if ($associative === false) {
      // process arrayCode as object
      return $this->retrievePricing_arrObj($landing, $arrayCode);
    }

    //process arrayCode as associative array 

    $product_catalog = $landing->hasField('field_lan_product_catalog') ? $landing->get('field_lan_product_catalog')->referencedEntities() : [];

    if (!empty($product_catalog)) :
      $package_list = $this->mappingProducts($product_catalog);

      return array_map(function($block) use ($package_list) {
        if (strpos($block['blockID'], 'pricing') === false) return $block;

        $formattedBlock = $block;
        $basePricing = $block['data']['pricing']['value'][0];

        $pricingList = array_map(function($price) use($basePricing, $block) {
          $formatted = $basePricing;

          // format pricing data body
          $formatted['title']['value'] = "<strong>{$price['package_title']}</strong>";
          $formatted['content']['value'] = "<p>{$price['package_tipe_paket']}, {$price['package_service']}, {$price['package_transtype']}</p>";
          $formatted['detail']['value'] = $price['package_detail'];
          $formatted['price_suffix']['value'] = "/ {$price['package_billing_period']}";

          // set identifier (package id)
          $formatted['package_id'] = array(
            // see \web\builder\assets\builder\src\components\fields\empty.vue for description
            'type' => 'empty',
            'value' => $price['package_id']
          );

          switch ($block['blockID']) {
            case 'pricing-01':
            case 'pricing-02':
            case 'pricing-07':
            case 'pricing-08':
              $formatted['title']['value'] = "<strong>Rp.".number_format($price['package_price_total'])."/Bulan</strong>";
              $formatted['subtitle']['value'] = trim($price['package_flag']);
              $formatted['button']['value']['label'] = "Pilih";
            break;

            case 'pricing-04':
            case 'pricing-05':
              $formatted['price']['value'] = "Rp.".number_format($price['package_price_total']);
              $formatted['button']['value']['label'] = "Pilih";
            break;

            case 'pricing-06':
              $formatted['price']['value'] = number_format($price['package_price_total']);
              $formatted['button']['value']['label'] = "Pilih";
            break;

            case 'pricing-11':
            case 'pricing-09':
              $formatted['content']['value'] = $price['package_subtitle'];
              $formatted['speed']['value'] = round($price['package_speed'] / 1000);
              $formatted['price']['value'] = " ".number_format($price['package_price_total'])." ";
              $formatted['footer_content']['value'] = '<ul>'. implode('', array_map(function($benefitText){
                return "<li>". $benefitText['benefit_package_name'] ."</li>";
              }, $price['benefit_package_setting'])) .'</ul>';
            break;

            case 'pricing-10':
              $formatted['content']['value'] = $price['package_subtitle'];
              $formatted['speed']['value'] = round($price['package_speed'] / 1000);
              $formatted['price']['value'] = "Rp.".number_format($price['package_price_total'])." ";
              $formatted['footer_content']['value'] = '<ul>'. implode('', array_map(function($benefitText){
                return "<li>". $benefitText['benefit_package_name'] ."</li>";
              }, $price['benefit_package_setting'])) .'</ul>';
            break;

            case 'pricing-12':
              $formatted['identifier']['value'] = $price['package_id'];
              $formatted['speed']['value'] = round($price['package_speed'] / 1000).'Mbps';
              $formatted['price']['value'] = "Rp.".number_format($price['package_price_total'])." ";
            break;

            case 'pricing-13':
              $formatted['telp_head']['value'] = "Rp." . number_format($price['package_price_voice']);
              $formatted['telp_body']['value'] = $price['package_transtype'];
              $formatted['tv_body']['value'] = $price['package_service'];
              $formatted['speed']['value'] = round($price['package_speed'] / 1000);
              $formatted['price']['value'] = " ".number_format($price['package_price_total'])." ";
            break;

            case 'pricing-15':
              $formatted['speed']['value'] = round($price['package_speed'] / 1000);
              $formatted['price']['value'] = " ".number_format($price['package_price_total'])." ";
              $formatted['content']['value'] = implode('', array_map(function($benefitText){
                return "<div class='content border-bottom'>". $benefitText['benefit_package_name'] ."</div>";
              }, $price['benefit_package_setting']));
              $formatted['button']['value']['label'] = "Pilih Paket Ini";
            break;

            case 'pricing-03':
            default:
              $formatted['subtitle']['value'] = "<strong>Rp.".number_format($price['package_price_total'])."/{$price['package_billing_period']}</strong>";
              $formatted['button']['value']['label'] = "Pilih";
            break;
          }

          return $formatted;
        }, $package_list);

        // replace pricing data list based on real time available data
        $formattedBlock['data']['pricing']['value'] = $pricingList;

        return $formattedBlock;
      }, $arrayCode);
    endif;
    
    return $arrayCode;
  }

  private function retrievePricing_arrObj(Node $landing, array $arrayCode){

    $product_catalog = $landing->hasField('field_lan_product_catalog') ? $landing->get('field_lan_product_catalog')->referencedEntities() : [];

    if (!empty($product_catalog)) :
      $package_list = $this->mappingProducts($product_catalog);

      return array_map(function($block) use ($package_list) {
        if (strpos($block->blockID, 'pricing') === false) return $block;

        $formattedBlock = $block;
        $basePricing = clone $block->data->pricing->value[0];

        $pricingList = array_map(function($price) use($basePricing, $block) {

          $formatted = clone $basePricing;

          // format pricing data body
          if (!empty($formatted->title)) {
            $formatted->title->value = "<strong>{$price['package_title']}</strong>";
          }
          if (!empty($formatted->content)) {
            $formatted->content->value = "<p>{$price['package_tipe_paket']}, {$price['package_service']}, {$price['package_transtype']}</p>";
          }
          if (!empty($formatted->detail)) {
            $formatted->detail->value = $price['package_detail'];
          }
          if (!empty($formatted->price_suffix)) {
            $formatted->price_suffix->value = "/ {$price['package_billing_period']}";
          }

          // // set identifier (package id)
          $formatted->package_id = new stdClass();
          $formatted->package_id->type  = 'empty';
          $formatted->package_id->value = $price['package_id'];

          switch ($block->blockID) {
            case 'pricing-01':
            case 'pricing-02':
            case 'pricing-07':
            case 'pricing-08':
              $formatted->title->value = "<strong>Rp.".number_format($price['package_price_total'])."/Bulan</strong>";
              $formatted->subtitle->value = trim($price['package_flag']);
              $formatted->button->value->label = "Pilih";
            break;

            case 'pricing-04':
            case 'pricing-05':
              $formatted->price->value = "Rp.".number_format($price['package_price_total']);
              $formatted->button->value->label = "Pilih";
            break;

            case 'pricing-06':
              $formatted->price->value = number_format($price['package_price_total']);
              $formatted->button->value->label = "Pilih";
            break;

            case 'pricing-11':
            case 'pricing-09':
              $formatted->content->value = $price['package_subtitle'];
              $formatted->speed->value = round($price['package_speed'] / 1000);
              $formatted->price->value = " ".number_format($price['package_price_total'])." ";
              if (!empty($formatted->footer_content)) {
                $formatted->footer_content->value = '<ul>'. implode('', array_map(function($benefitText){
                  return "<li>". $benefitText['benefit_package_name'] ."</li>";
                }, $price['benefit_package_setting'])) .'</ul>';
              }
            break;

            case 'pricing-10':
              $formatted->content->value = $price['package_subtitle'];
              $formatted->speed->value = round($price['package_speed'] / 1000);
              $formatted->price->value = "Rp.".number_format($price['package_price_total'])." ";
              $formatted->footer_content->value = '<ul>'. implode('', array_map(function($benefitText){
                return "<li>". $benefitText['benefit_package_name'] ."</li>";
              }, $price['benefit_package_setting'])) .'</ul>';
            break;

            case 'pricing-12':
              $formatted->identifier->value = $price['package_id'];
              $formatted->speed->value = round($price['package_speed'] / 1000).'Mbps';
              $formatted->price->value = "Rp.".number_format($price['package_price_total'])." ";
            break;

            case 'pricing-13':
              $formatted->telp_head->value = "Rp." . number_format($price['package_price_voice']);
              $formatted->telp_body->value = $price['package_transtype'];
              $formatted->tv_body->value = $price['package_service'];
              $formatted->speed->value = round($price['package_speed'] / 1000);
              $formatted->price->value = " ".number_format($price['package_price_total'])." ";
            break;

            case 'pricing-15':
              $formatted->speed->value = round($price['package_speed'] / 1000);
              $formatted->price->value = " ".number_format($price['package_price_total'])." ";
              $formatted->content->value = implode('', array_map(function($benefitText){
                return "<div class='content border-bottom'>". $benefitText['benefit_package_name'] ."</div>";
              }, $price['benefit_package_setting']));
              $formatted->button->value->label = "Pilih Paket Ini";
            break;

            case 'pricing-03':
            default:
              $formatted->subtitle->value = "<strong>Rp.".number_format($price['package_price_total'])."/{$price['package_billing_period']}</strong>";
              $formatted->button->value->label = "Pilih";
            break;
          }

          return json_decode(json_encode($formatted));

        }, $package_list);

        // replace pricing data list based on real time available data
        $formattedBlock->data->pricing->value = $pricingList;

        return $formattedBlock;
      }, $arrayCode);
    endif;
    
    return $arrayCode;
  }

  private function mappingProducts($product_catalog = array())
  {
    // prepare variable
    $package_list = array();
    $benefit_list = $this->retrieveBenefit();

    // loop product data
    foreach ($product_catalog as $product) {
      // loop mapping package data
      foreach ($product->field_pct_list_paket->referencedEntities() as $package) {
        // mapping benefit product setting if any
        if ($product->hasField('field_pct_setting_temp_pricing') && !$product->get('field_pct_setting_temp_pricing')->isEmpty()) {
          // convert into array from json format
          $benefitList = json_decode($product->get('field_pct_setting_temp_pricing')->getString(),TRUE);

          foreach ($benefitList as $key => $value) :
            if ($value) {
              $benefitSetting[$key] = $benefit_list[$key];
            };
          endforeach;
        };

        // mapping benefit package setting if any
        if ($package->hasField('field_pkt_setting_temp_pricing') && !$package->get('field_pkt_setting_temp_pricing')->isEmpty()) {
          // convert into array from json format
          $benefitList = json_decode($package->get('field_pkt_setting_temp_pricing')->getString(),TRUE);

          foreach ($benefitList as $key => $value) :
            if ($value) {
              $benefitPackage[$key] = array_merge(['benefit_package_name' => $value], $benefit_list[$key]);
            };
          endforeach;
        };

        $package_list[] = [
          'uuid' => $package->uuid(),
          'package_id' => !empty($package->field_pkt_package_id->getValue()) ? current($package->field_pkt_package_id->getValue()[0]) : null,
          'package_title' => $package->label(),
          'package_subtitle' => $package->field_pkt_sub_title->getString(),
          'package_flag' => $package->field_pkt_flag->getString(),
          'package_tipe_paket' => !empty($package->field_pkt_tipe_paket->referencedEntities()) ? $package->field_pkt_tipe_paket->referencedEntities()[0]->label() : '',
          'package_price_internet' => !empty($package->field_pkt_price_internet->getString()) ? $package->field_pkt_price_internet->getString() : 0,
          'package_price_total' => !empty($package->field_pkt_price_total->getString()) ? $package->field_pkt_price_total->getString() : 0,
          'package_price_voice' => !empty($package->field_pkt_price_voice->getString()) ? $package->field_pkt_price_voice->getString() : 0,
          'package_billing_period' => $package->field_pkt_billing_period->getString(),
          'package_speed' => !empty($package->field_pkt_speed->getString()) ? $package->field_pkt_speed->getString() : 0,
          'package_service' => $package->field_pkt_service->getString(),
          'package_transtype' => $package->field_pkt_trans_type->getString(),
          'package_detail' => $package->field_pkt_package_detail->getString(),
          'product_id' => $product->id(),
          'product_uuid' => $product->uuid(),
          'product_name' => $product->label(),
          'benefit_product_setting' => !empty($benefitSetting) ? $benefitSetting : [],
          'benefit_package_setting' => !empty($benefitPackage) ? $benefitPackage : [],
        ];
      };
    };

    // release memory
    unset($product_catalog, $product, $package, $benefitList, $benefitSetting, $benefitPackage, $benefit_list);

    return $package_list;
  }

  private function retrieveBenefit()
  {
    $benefitData = array();
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery()
      ->condition('status', 1)
      ->condition('type', 'template_pricing_setting')
      ->execute();

    foreach ($entity->loadMultiple($query) as $key => $benefit) {
      // skip when image is empty
      if (empty($benefit->field_temp_set_logo->getValue()[0])) continue;

      // retrieve image data
      $image_file = File::load($benefit->field_temp_set_logo->getValue()[0]['target_id']);
      $image_uri = $image_file->getFileUri();

      if (str_contains($image_uri, 's3')) {
        $findS3 = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($image_uri, 'original');

        $image_url = $findS3['status'] ? $findS3['data'] : $image_uri;
      }
      else{
        $image_url = \Drupal::request()->getSchemeAndHttpHost() . \Drupal::service('file_url_generator')->generateString($image_uri);
      };

      $benefitData[$benefit->id()] = [
        'benefit_uuid' => $benefit->uuid(),
        'benefit_name' => $benefit->label(),
        'image_id'     => $benefit->field_temp_set_logo->getValue()[0]['target_id'],
        'filename'     => str_replace(' ', '_', $image_file->getFilename()),
        'uri'          => $image_url,
        'thumbnail'    => $image_url,
        'width'        => "{$benefit->field_temp_set_logo->getValue()[0]['width']}px",
        'height'       => "{$benefit->field_temp_set_logo->getValue()[0]['height']}px",
      ];
    }

    unset($entity, $query, $image_file, $image_url, $benefit);

    return $benefitData;
  }

  public function checkBuilderAccess(Node $landing) {
    $currentUser = Drupal::currentUser();
    $userRoles = $currentUser->getRoles();
    if (in_array('administrator', $userRoles) || in_array('admin_content', $userRoles)) {
      return [
        'access' => true, // access current request
        'access_all' => true, // access all page or all landing
        'user_id' => $currentUser->id()
      ];
    }
    else if(in_array('landing_owner', $userRoles)){
      // only own landing
      return [
        'access' => (int) $currentUser->id() === (int) $landing->field_lan_owner->getString(),
        'access_all' => false,
        'user_id' => $currentUser->id()
      ];
    }

    return [
      'access' => false,
      'access_all' => false,
      'user_id' => 0
    ];
  }

  public function checkListlandingAccess() {
    $currentUser = Drupal::currentUser();
    $userRoles = $currentUser->getRoles();
    if (in_array('administrator', $userRoles) || in_array('admin_content', $userRoles)) {
      return [
        'access' => true, // access current request
        'access_all' => true, // access all page or all landing
        'user_id' => $currentUser->id()
      ];
    }
    else if(in_array('landing_owner', $userRoles)){
      // only own landing
      return [
        'access' => true,
        'access_all' => false,
        'user_id' => $currentUser->id()
      ];
    }

    return [
      'access' => false,
      'access_all' => false,
      'user_id' => 0
    ];
  }

}