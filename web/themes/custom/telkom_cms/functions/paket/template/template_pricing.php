<?php

use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

class TemplatePricingPaket {

  /**
   * template pricing paket
   */
  public function get_template_pricing(Node $paket){
  
      // get the list template pricing html
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery();
      $query = $query->condition('status', 1)
                  ->condition('type', 'template_pricing_paket');#type = bundle id (machine name)
  
      Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($query, 'field_tem_pkt_type', ['paket']);
      // $query = $query->orderBy('created', 'ASC');
      $ids = $query->execute();
      
      $templates = $entity->loadMultiple($ids);
  
      // get the list benefit (value by tab "setting template pricing")
      $list_benefit = [];
  
      // get the list available setting
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery();
      $query = $query->condition('status', 1)
                  ->condition('type', 'template_pricing_setting');#type = bundle id (machine name)
      // $query = $query->orderBy('created', 'ASC');
      $ids = $query->execute();
      
      $settings = $entity->loadMultiple($ids);
  
      // get the value setting stored in database
      $pkt_setting_template_pricing = json_decode($paket->field_pkt_setting_temp_pricing->getString(), true);
  
      if (!is_array($pkt_setting_template_pricing)) {
        $pkt_setting_template_pricing = [];
      }
  
      // loop the list available setting template pricing, then get the stored value in paket
      foreach ($settings as $setting_id => $setting) {
          if (!empty($pkt_setting_template_pricing[$setting_id])) {
              $list_benefit[] = "<li>".$pkt_setting_template_pricing[$setting_id]."</li>";
          }
      }
  
  
      $paket_data = [
          'title' => $paket->title->getString(),
          'sub_title' => $paket->field_pkt_sub_title->getString(),
          'promo_text' => $paket->field_pkt_promo_text->getString(),
          'speed' => convert_speed_to_mbps_value($paket->field_pkt_speed->getString()),
          'price' => convert_price_format($paket->field_pkt_price_total->getString()),
          'billing_period' => strtolower($paket->field_pkt_billing_period->getString()),
          'list_benefit' => implode('', $list_benefit)
      ];
  
      // return data list template pricing 
      $return_data = [];
      foreach ($templates as $template_id => $template) {
          $template_html = $template->field_temp_pkt_html->getString();
  
          $template_html = str_replace('%title%', $paket_data['title'], $template_html);
          $template_html = str_replace('%sub_title%', $paket_data['sub_title'], $template_html);
          $template_html = str_replace('%promo_text%', $paket_data['promo_text'], $template_html);
          $template_html = str_replace('%speed%', $paket_data['speed'], $template_html);
          $template_html = str_replace('%price%', $paket_data['price'], $template_html);
          $template_html = str_replace('%billing_period%', $paket_data['billing_period'], $template_html);
          $template_html = str_replace('%list_benefit%', $paket_data['list_benefit'], $template_html);
  
          if (empty($paket_data['promo_text'])) {
              $template_html = str_replace('promo-package-label', '', $template_html);
          }
  
          // get the css & javascript pricing file url
          $themes_path = '/themes'. explode('themes', __DIR__)[1];
          $link_css = $_ENV['APP_URL']. $themes_path ."/css/pricing-table.css"; 
          $link_js = $_ENV['APP_URL']. $themes_path ."/dist/js/bootstrap.bundle.min.js"; 
  
          $return_data[] = [
              'template_id' => $template_id,
              'title' => $template->title->getString(),
              'html' => $template_html,
              'css_link' => "<link rel='stylesheet' href='$link_css'>",
              'js_link' => "<script src='$link_js'></script>"
          ];
  
      }
      return $return_data;
  }

  /**
   * setting template pricing for paket
   */
  public function get_template_pricing_setting(Node $paket){
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
                ->condition('type', 'template_pricing_setting');#type = bundle id (machine name)
    // $query = $query->orderBy('created', 'ASC');
    $ids = $query->execute();

    $settings = $entity->loadMultiple($ids);

    $return = [];
    // loop the list available setting template pricing
    foreach ($settings as $setting_id => $setting) {

        $logo = $setting->field_temp_set_logo->getValue()[0];
        $logo_file = File::load($logo['target_id']);

        if ($logo_file) :
          $logo_uri = $logo_file->getFileUri();

          if (str_contains($logo_uri, 's3')) {
            $findS3 = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($logo_uri, 'original');

            $logo_url = $findS3['status'] ? $findS3['data'] : $logo_uri;
          }
          else{
            $logo_url = \Drupal::request()->getSchemeAndHttpHost() . \Drupal::service('file_url_generator')->generateString($logo_uri);
          };
        endif;

        // get data setting template pricing stored in paket
        // example stored data = ['$setting_id'=>'value', etc]
        $paket_setting_temp_data = $paket->field_pkt_setting_temp_pricing->getString();
        $array_setting_paket = !empty($paket_setting_temp_data) ? json_decode($paket_setting_temp_data, true) : [];

        $return[] = [
            'id' => $setting_id,
            'element_id' => 'setting_template_pricing_paket_' . $setting_id,
            'logo' => $logo_url ?? '',
            'value' => !empty($array_setting_paket[$setting_id]) ? $array_setting_paket[$setting_id] : '' //value is from stored data in paket
        ];
    }

    return $return;
  }
}