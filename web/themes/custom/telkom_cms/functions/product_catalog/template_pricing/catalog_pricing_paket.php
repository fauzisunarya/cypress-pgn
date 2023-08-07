<?php

use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

require_once __DIR__ . '/../../helper.php';
Class CatalogPricingPaket extends TemplatePricing {

  public function tab_template_pricing_paket(Node $catalog){
    // get the list template pricing html
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
                ->condition('type', 'template_pricing_catalog');#type = bundle id (machine name)
    // $query = $query->orderBy('created', 'ASC');
    Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($query, 'field_tem_pct_type', ['paket']);
    $ids = $query->execute();
    
    $templates = $entity->loadMultiple($ids);

    // get the list paket for this product_catalog
    $arr_pkt_obj = [];
    $arr_list_paket = $catalog->field_pct_list_paket->getValue();
    foreach ($arr_list_paket as $value) {
      if (!empty($value['target_id'])) {
        $arr_pkt_obj[$value['target_id']] = \Drupal::entityTypeManager()->getStorage('node')->load($value['target_id']);
      }
    }

    // get the list available template setting
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
                  ->condition('type', 'template_pricing_setting');#type = bundle id (machine name)
    $ids = $query->execute();
    
    $settings = $entity->loadMultiple($ids);

    // get the value setting stored in database. example value: ['setting_id_1'=>true, etc], true = this setting id is showed
    $catalog_setting_template_pricing = json_decode($catalog->field_pct_setting_temp_pricing->getString(), true);

    if (!is_array($catalog_setting_template_pricing)) {
      $catalog_setting_template_pricing = [];
    }

    // loop the list available setting template pricing, then delete the setting id if is set hidden by catalog setting 
    $arr_settings_hidden = [];
    foreach ($settings as $setting_id => $setting) {
      if (isset($catalog_setting_template_pricing[$setting_id]) && $catalog_setting_template_pricing[$setting_id]===false) {
        $arr_settings_hidden[] = $setting_id;
      }
    }
    foreach ($arr_settings_hidden as $id) {
      unset($settings[$id]);
    }

    // return data list template pricing 
    $return_data = [];
    foreach ($templates as $template_id => $template) {
      $template_html = trim($template->field_temp_cat_html->getString());

      $another_link_css = "";

      if (strpos($template_html, '%template_pricing_catalog_1%')!==false) {
        $template_html = $this->template_1($arr_pkt_obj, $settings);
      }
      elseif (strpos($template_html, '%template_pricing_catalog_2%')!==false) {
        $template_html = $this->template_2($arr_pkt_obj, $settings);
      }
      elseif (strpos($template_html, '%template_pricing_catalog_3%')!==false) {
        $template_html = $this->template_3($arr_pkt_obj, $settings);
      }
      elseif (strpos($template_html, '%template_pricing_catalog_4%')!==false) {
        $template_html = $this->template_4($arr_pkt_obj, $settings);
      }
      elseif (strpos($template_html, '%template_pricing_catalog_5%')!==false) {
        $template_html = $this->template_5($arr_pkt_obj, $settings, $catalog->title->getString());
      }
      elseif (strpos($template_html, '%template_pricing_catalog_6%')!==false) {
        $template_html = $this->template_6($arr_pkt_obj, $settings);
        $another_link_css .= "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css'/>";
      }

      // get the css & javascript pricing file url
      $themes_path = '/themes'. explode('themes', __DIR__)[1];
      $themes_path = preg_replace("/functions.*$/", '', $themes_path);
      $link_css = $_ENV['APP_URL']. $themes_path ."css/pricing-table.css"; 
      $link_js = $_ENV['APP_URL']. $themes_path ."dist/js/bootstrap.bundle.min.js"; 

      $return_data[] = [
        'template_id' => $template_id,
        'title' => $template->title->getString(),
        'html' => $template_html,
        'css_link' => "<link rel='stylesheet' href='$link_css'>$another_link_css",
        'js_link' => "<script src='$link_js'></script>"
      ];

    }
    return $return_data;
  }

  public function tab_template_pricing_setting_paket(Node $catalog){
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

      // get data setting template pricing stored in product catalog
      // example stored data = ['$setting_id'=> true, etc] true = this setting is showed
      $catalog_setting_temp_data = $catalog->field_pct_setting_temp_pricing->getString();
      $array_setting_catalog = !empty($catalog_setting_temp_data) ? json_decode($catalog_setting_temp_data, true) : [];

      $return[] = [
        'id' => $setting_id,
        'element_id' => 'setting_template_pricing_catalog_' . $setting_id,
        'logo' => $logo_url ?? '',
        'value' => isset($array_setting_catalog[$setting_id]) ? $array_setting_catalog[$setting_id] : true //value is from stored data in paket
      ];
    }

    return $return;
  }

  /**
   * Get Template pricing catalog 1
   * 
   * parameter : array of paket object, and template_pricing_setting node
   * template pricing setting is the list of available template pricing setting
   */
  function template_1($arr_pkt_obj, $settings){

    $items = '';
    foreach ($arr_pkt_obj as $paket) {
      // get the list benefit (value by tab "setting template pricing")
      $list_benefit = [];

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
        'price' => $this->convert_price_format($paket->field_pkt_price_total->getString()),
        'billing_period' => strtolower($paket->field_pkt_billing_period->getString()),
        'list_benefit' => implode('', $list_benefit)
      ];

      $link_image_bar = $paket_data['speed'] != 30 && $paket_data['speed'] != 50 && $paket_data['speed'] != 100 ? 30 : $paket_data['speed'];

      $class_promo_text = !empty($paket_data['promo_text']) ? 'promo-package-label' : '';

      $items .= '
        <div class="pricing-type-1">
          <div class="pricing-body">
            <div class="pricing-title" style="height:31.5px;">
              <div class="'.$class_promo_text.'">'.$paket_data['promo_text'].'</div>
            </div>
            <div class="pricing-content">
              <div class="pricing-speed">
                <img src="https://www.indihome.co.id/images/speed-gauge/speed-'.$link_image_bar.'.svg" alt="">
                <div class="speed">
                  <span>up to</span>
                  <h2 class="speed-label">'.$paket_data['speed'].'</h2>
                  <span>Mbps</span>
                </div>
              </div>
              <div class="pricing-description">
                <h6 class="title">'.$paket_data['title'].'</h6>
                <small class="sub-title">'.$paket_data['sub_title'].'</small>
              </div>
            </div>
            <div class="price-cta">
              <div class="price">
                <span class="currency">Rp</span>
                  <h4 class="number">'.$paket_data['price'].'</h4>
                <span class="month">/'.$paket_data['billing_period'].'</span>
              </div>
              <button class="btn-pricing-primary">Berlangganan</button>
            </div>
          </div>
        </div>
      ';
    }

    return '<div style="display:flex;justify-content:space-around;flex-wrap:wrap;margin:20px 10px;">'.$items.'</div>';
  }

  /**
  * Get Template pricing catalog 2
  * 
  * parameter : array of paket object, and template_pricing_setting node
  * template pricing setting is the list of available template pricing setting
  */
  function template_2($arr_pkt_obj, $settings){
    
    $items = '';
    foreach ($arr_pkt_obj as $paket) {
      // get the list benefit (value by tab "setting template pricing")
      $list_benefit = [];

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
        'price' => $this->convert_price_format($paket->field_pkt_price_total->getString()),
        'billing_period' => strtolower($paket->field_pkt_billing_period->getString()),
        'list_benefit' => implode('', $list_benefit)
      ];

      $items .= '
        <div class="pricing-type-2">
          <div class="pricing-body">
            <div class="pricing-title">
              <div class="speed-title">
                <img src="https://www.indihome.co.id/images/ic-wifi-red.svg" alt="speed">
                <span>Speed</span>
              </div>
            </div>
            <div class="speed">
              <h4 class="speed-label">'.$paket_data['speed'].'</h4>
              <span>Mbps</span>
            </div>
            <div class="price">
              <h4 class="number">Rp'.$paket_data['price'].'</h4>
              <span class="month">/'.$paket_data['billing_period'].'</span>
            </div>
            <div class="pricing-description">
              <p>'.$paket_data['title'].'</p>
              <small>'.$paket_data['sub_title'].'</small>
            </div>
            <hr>
            <div class="benefit-title">
              <img src="https://www.indihome.co.id/images/icon/icon-benefit.svg" alt="benefit">
              <span>Benefit</span>
            </div>
            <div class="benefit-list">
              <ul>
              '.$paket_data['list_benefit'].'
              </ul>
            </div>
            <button class="btn-pricing-primary">Pilih Paket</button>
          </div>
        </div>
      ';
    }

    return '<div style="display:flex;justify-content:space-around;flex-wrap:wrap;margin:20px 10px;">'.$items.'</div>';
  }

  /**
  * Get Template pricing catalog 3
  * 
  * parameter : array of paket object, and template_pricing_setting node
  * template pricing setting is the list of available template pricing setting
  */
  function template_3($arr_pkt_obj, $settings){

    $items = '';
    $loop = 1;
    foreach ($arr_pkt_obj as $paket) {
      // get the list benefit (value by tab "setting template pricing")
      $list_benefit = [];

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
        'price' => $this->convert_price_format($paket->field_pkt_price_total->getString()),
        'billing_period' => strtolower($paket->field_pkt_billing_period->getString()),
        'list_benefit' => implode('', $list_benefit)
      ];

      $class_item = $loop%3===2 ? 'red' : ($loop%3===0 ? 'blue' : '');
      $class_btn = $loop%3===2 || $loop%3===0 ? 'btn' : '';

      $items .= '
        <div class="pricing-type-3 '.$class_item.'">
          <div class="pricing-header">
            <div class="speed">
              <span>up to</span>
              <h1>'.$paket_data['speed'].'</h1>
              <span>Mbps</span>
            </div>
          </div>
          <div class="pricing-body">
            <div class="price">
              <span>Rp</span>
              <h1>'.$paket_data['price'].'</h1>
              <span>/'.$paket_data['billing_period'].'</span>
            </div>
            <div class="pricing-description">
              <p class="fw-bold">'.$paket_data['title'].'</p>
              <small>'.$paket_data['sub_title'].'</small>
            </div>
            <div class="benefit-list">
              <ul>
              '.$paket_data['list_benefit'].'
              </ul>
            </div>
            <button class="'.$class_btn.' btn-success">Berlangganan</button>
          </div>
        </div>
      ';
      $loop++;
    }

    return '<div style="display:flex;justify-content:space-around;flex-wrap:wrap;margin:20px 10px;">'.$items.'</div>';
  }

  /**
  * Get Template pricing catalog 4
  * 
  * parameter : array of paket object, and template_pricing_setting node
  * template pricing setting is the list of available template pricing setting
  */
  function template_4($arr_pkt_obj, $settings){

    // process table header
    $table_header = '<tr>';
    foreach ($settings as $setting) {
      $title = strtolower($setting->title->getString());
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

      $max_width = strpos($title, 'indihome')!==false ? '70' : '50';
      $table_header .= '
        <th>
          <img src="'.$logo_url.'" alt="logo-'.str_replace(' ','-',$title).'" style="max-width:'.$max_width.'px" width="100%">
        </th>
      ';
    }
    $table_header .= '
      <th class="price">
        Harga
      </th>
    ';
    $table_header .= "</tr>";

    // process table body
    $table_body = '';
    foreach ($arr_pkt_obj as $paket) {

      // get the value setting stored in database
      $pkt_setting_template_pricing = json_decode($paket->field_pkt_setting_temp_pricing->getString(), true);

      if (!is_array($pkt_setting_template_pricing)) {
        $pkt_setting_template_pricing = [];
      }

      $table_body .= "<tr>";
      
      // loop the list available setting template pricing, then get the stored value in paket
      foreach ($settings as $setting_id => $setting) {
        $title = strtolower($setting->title->getString());

        $td_data = '';
        if ( strpos($title, 'indihome')!==false ) {
          $td_data = '
            <td class="speed">
              <h4>'.convert_speed_to_mbps_value($paket->field_pkt_speed->getString()).'</h4>
              <span>Mbps</span>
            </td>
          ';
        }
        elseif (!empty($pkt_setting_template_pricing[$setting_id])) {
          $td_data .= "
            <td>
              ".$pkt_setting_template_pricing[$setting_id]."
            </td>
          ";
        }
        else{
          $td_data .= "
            <td>
              -
            </td>
          ";
        }
        $table_body .= "$td_data";
      }
      $table_body .= '
        <td>
          <div class="price">
            <span>Rp</span>
            <h5>'.$this->convert_price_format($paket->field_pkt_price_total->getString()).'</h5>
            <span>/bulan</span>
          </div>
        </td>
      ';

      $table_body .= "</tr>";
    }

    return '
      <div>
        <div class="pricing-type-5" style="width:100%">
          <div class="pricing-body table-responsive">
            <table class="table">
              <thead>
                '.$table_header.'
              </thead>
              <tbody>
                '.$table_body.'
              </tbody>
            </table>
          </div>
        </div>
      </div>
    ';
  }

  /**
  * Get Template pricing catalog 5
  * 
  * parameter : array of paket object, and template_pricing_setting node
  * template pricing setting is the list of available template pricing setting
  */
  function template_5($arr_pkt_obj, $settings, $catalog_title){

    // process table body
    $table_body = '';
    foreach ($arr_pkt_obj as $paket) {
      $table_body .= "
        <tr>
          <td>".$paket->field_pkt_package_id->getString()."</td>
          <td>".ucwords(strtolower($paket->title->getString()))."</td>
          <td>".convert_speed_to_mbps_value($paket->field_pkt_speed->getString())." Mbps</td>
          <td>".$this->convert_price_format($paket->field_pkt_price_total->getString())."</td>
        </tr>
      ";
    }

    return '
      <div>
        <div class="pricing-type-4" style="width:100%">
          <div class="pricing-body table-responsive">
            <div class="pricing-title">
              <div class="list-package-title">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                  <path fill="none" d="M0 0h24v24H0z" />
                  <path
                    d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"
                    fill="rgba(255,0,0,1)" />
                </svg>
                <h6>'.$catalog_title.'</h6>
              </div>
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Speed</th>
                    <th>Price</th>
                  </tr>
                </thead>
                <tbody>
                  '.$table_body.'
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    ';
  }

  function template_6($arr_pkt_obj, $settings){

    $paket = implode('', array_map(function($paket)use($settings){
      // note : benefit = paket setting template pricing data
      $paket_benefit = json_decode($paket->field_pkt_setting_temp_pricing->getString(), true);
      if (!is_array($paket_benefit)) {
        $paket_benefit = [];
      }
      
      // available benefit
      $ids_settings = array_map(fn($setting)=>$setting->id(), $settings);
      $benefit = implode('', array_map(function($id_setting)use($paket_benefit){
        $value = !empty($paket_benefit[$id_setting]) ? $paket_benefit[$id_setting] : '-';
        return "<div class='content border-bottom'>{$value}</div>";
      }, $ids_settings));

      return '
        <div class="pricing-15-card">
          <div class="title border-bottom">
            <span class="packet-type">'.$paket->title->getString().'</span>
            <h1>'.convert_speed_to_mbps_value($paket->field_pkt_speed->getString()).' gb</h1>
          </div>
          <div class="pricing-15-card-body">
            '.$benefit.'
            <div class="price">
              <span class="currency">Rp</span>
              <span class="nominal">'.$this->convert_price_format($paket->field_pkt_price_total->getString()).'</span>
              <span class="month">/'.$paket->field_pkt_billing_period->getString().'</span>
            </div>
          </div>
          <div class="pricing-15-card-footer">
            <button>Pilih Paket Ini</button>
          </div>
        </div>
      ';
    },$arr_pkt_obj));


    return '
      <div class="pricing-15 columns is-multiline is-centered">
        '.$paket.'
      </div>
    ';
  }
}