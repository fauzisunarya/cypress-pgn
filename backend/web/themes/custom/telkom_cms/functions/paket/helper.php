<?php

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Component\Utility\Html;

class PaketHelper {

  public static function detail(&$variables=[], &$node=null, $loadTemplatePricing = false) {

    $variables['#cache']['max-age'] = 0;
    
    $variables['data_paket'] = [
      'paket_id' => $node->id(), // will be created as input type hidden, the value is used when doing ajax request
      'base_url' => getenv('APP_URL'), // will be created as input type hidden, the value is used when doing ajax request
      'detail' => self::list_field_detail($node)
    ];

    $variables['request_uri'] = explode('?',$_SERVER['REQUEST_URI'])[0];

    $setting_showhide = json_decode($node->field_pkt_master_data_edited->getString(), true); // [$key=>[showname: "", hidden:""], etc]
    $fields = !empty($setting_showhide) && is_array($setting_showhide) && !empty($setting_showhide['field']) ? $setting_showhide['field'] : [];

    $variables['data_paket']['detail'] = array_filter(
      array_map(function($field) use($fields) {
        if(!empty($fields[$field['name']])) {
          if($fields[$field['name']]['hidden'] === true) {
            return null;
          }
          $field['title'] = $fields[$field['name']] ['showname'];
        }
        unset($field['name']);
        return $field;
      }, $variables['data_paket']['detail'])
    );

    unset($variables['content']);

    if ($loadTemplatePricing) {
      // load template pricing
      include_custom_functions('/paket/template/', 'template_pricing');

      $templatePricingPaket = new TemplatePricingPaket();
      // template pricing paket
      $variables['template_pricing_paket'] = $templatePricingPaket->get_template_pricing($node); 

      // setting template pricing
      $variables['setting_template_pricing'] = $templatePricingPaket->get_template_pricing_setting($node); 
    }
  
    
  }

  public static function edit(Node $node) {

    $master_data_edited = json_decode($node->field_pkt_master_data_edited->getString(), true);
    $master_data_edited = !empty($master_data_edited) && is_array($master_data_edited) && !empty($master_data_edited['field']) ? $master_data_edited : ['field'=>[]];

    $submitted_data = self::list_field_edit($node);
    foreach ($submitted_data as $key_field => $value) {
      // $value contains "showname" & current "value" of $key_field
      unset($submitted_data[$key_field]['value']);

      // if in list "disabled" field, showname = default, hidden = false;
      if (in_array($key_field, self::field_disabled_edit())) {
        $submitted_data[$key_field]['hidden'] = false;
        continue;
      }

      // change showname based on input data
      $title = Html::escape($_POST[$key_field.'_title']);
      if (!empty($title)) {
        $submitted_data[$key_field]['showname'] = $title;
      }

      // change hidden "true/false" based on input data
      $is_hidden = !empty($_POST[$key_field.'_hidden']) ? true : false;
      $submitted_data[$key_field]['hidden'] = $key_field === 'title' ? false : $is_hidden;

    }

    $master_data_edited['field'] = array_merge($master_data_edited['field'], $submitted_data); 
    
    // update paket ( field that can be updated, List = list_field_edit - field_disabled_edit )
    $node->title = Html::escape($_POST['title_value']);
    $node->field_pkt_sub_title  = Html::escape($_POST['sub_title_value']);
    $node->field_pkt_flag  = Html::escape($_POST['flag_value']);
    $node->field_pkt_promo_text  = Html::escape($_POST['promo_text_value']);
    $node->field_pkt_detail_voice  = Html::escape($_POST['detail_voice_value']);
    $node->field_pkt_detail_inet  = Html::escape($_POST['detail_inet_value']);
    $node->field_pkt_billing_period  = Html::escape($_POST['billing_period_value']);
    $node->field_pkt_package_detail  = Html::escape($_POST['package_detail_value']);

    $node->field_pkt_master_data_edited = json_encode($master_data_edited);

    $category = [];
    if (!empty($_POST['category'])) {
        foreach ($_POST['category'] as $term_id) {
            $category[] = ['target_id' => $term_id];
        }
    }

    $node->field_pkt_category = $category;
    $node->field_pkt_tags = Html::escape($_POST['tags']);

    $node->field_pkt_is_customized = 1;

    // Make this change a new revision
    $node->setNewRevision(TRUE);
    $node->revision_log = '';
    $node->setRevisionCreationTime(REQUEST_TIME);
    $node->setRevisionUserId(\Drupal::currentUser()->id());
    
    $node->save();

    $alias = \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$node->id()); // output: /citem/slug

    $redirect_url = getenv('APP_URL') . $alias;

    if (!empty($_GET['destination'])) {
        $response = new RedirectResponse($_ENV['APP_URL'].$_GET['destination'], 301);
        $response->send();exit;
    }
    else{
        // redirect to paket detail
        $response = new RedirectResponse($redirect_url, 301);
        $response->send();exit;
    }

    return;
  }

  public static function page_edit(&$variables, Node $node ) {
    /**
     * "data" is the field that will be showed 
     * "master data" is the data from feasibility platform (api to get citem)
     * "master data edited" is field to change showname of item in "data" & hide/show item in "data"
     *  in page detail citem, item in "data" only showed if the same item name in "master data edited" hidden===false 
     */

    $data = [
      'data' => self::list_field_edit($node),
      'master_data' => [],
      'tags' => ['value' => $node->field_pkt_tags->getString()],
      'category' => [
        'selected' => [

        ],
        'option' => [

        ]
      ]
    ];

    // get master data (real data from API Pefita)
    $master_data = json_decode($node->field_pkt_master_data->getString(), true);
    $master_data = !empty($master_data) && is_array($master_data) ? $master_data : [];

    // get setting showname & show/hide
    $master_data_edited = json_decode($node->field_pkt_master_data_edited->getString(), true);
    $master_data_edited = !empty($master_data_edited) && is_array($master_data_edited) && !empty($master_data_edited['field']) 
      ? $master_data_edited['field'] : [];

    foreach ($data['data'] as $key => $value) {
      // generate master data
      if (!empty($master_data[$key]) || (isset($master_data[$key]) && $master_data[$key] == "0") ) {
        $data['master_data'][$key] = $master_data[$key];
      }
      else {
        $data['master_data'][$key] = '';
      }

      // generate parameter of each field
      if (!empty($master_data_edited[$key])) {
        $data['data'][$key]['showname'] = ucwords(strtolower($master_data_edited[$key]['showname'])); // replace showname
        $data['data'][$key]['hidden'] = $master_data_edited[$key]['hidden']; // add hidden "true/false"
      }
      else {
        $data['data'][$key]['hidden'] = false; //default (show)
      }

      // enable or disable field
      $data['data'][$key]['disabled'] = in_array($key, self::field_disabled_edit()) ? true : false;
    }

    // selected category
    foreach ($node->field_pkt_category->referencedEntities() as $cat) {
      $data['category']['selected'][$cat->label()]['id'] = $cat->id();
    }
    // category option
    $vid = 'paket_category';
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => $vid]);
    foreach ($terms as $term) {
      $data['category']['option'][$term->label()] = array(
        'id' => $term->id()
      );
    }

    $variables['paket'] = $data;

  }

  public static function field_disabled_edit() {
    return [
      'package_id', 'speed', 'price_voice', 'price_internet', 
      'price_total', 'tipe_paket', 'source', 'kuota', 
      'flag_json', 'trans_type', 'service', 'indihome_indicator'
    ];
  }

  private static function list_field_edit(Node $node) {
    return [
      "title" => [
        "showname" => "Title",
        "value" => $node->label(),
      ],
      "sub_title" => [
        "showname" => "Sub Title",
        "value" => $node->field_pkt_sub_title->getString(),
      ],
      "package_id" => [
        "showname" => "Package Id",
        "value" => $node->field_pkt_package_id->getString(),
      ],
      "flag" => [
        "showname" => "Flag",
        "value" => $node->field_pkt_flag->getString(),
      ],
      "speed" => [
        "showname" => "Speed",
        "value" => $node->field_pkt_speed->getString(),
      ],
      "promo_text" => [
        "showname" => "Promo Text",
        "value" => $node->field_pkt_promo_text->getString(),
      ],
      "detail_voice" => [
        "showname" => "Detail Voice",
        "value" => $node->field_pkt_detail_voice->getString(),
      ],
      "detail_inet" => [
        "showname" => "Detail Internet",
        "value" => $node->field_pkt_detail_inet->getString(),
      ],
      "price_voice" => [
        "showname" => "Price Voice",
        "value" => $node->field_pkt_price_voice->getString(),
      ],
      "price_internet" => [
        "showname" => "Price Internet",
        "value" => $node->field_pkt_price_internet->getString(),
      ],
      "price_total" => [
        "showname" => "Price Total",
        "value" => $node->field_pkt_price_total->getString(),
      ],
      "billing_period" => [
        "showname" => "Periode Pembayaran",
        "value" => $node->field_pkt_billing_period->getString(),
      ],
      "tipe_paket" => [
        "showname" => "Tipe Paket",
        "value" => !empty($node->field_pkt_tipe_paket->referencedEntities()) ? $node->field_pkt_tipe_paket->referencedEntities()[0]->label() : '',
      ],
      "source" => [
        "showname" => "Source",
        "value" => !empty($node->field_pkt_source->getString()) ? $node->field_pkt_source->referencedEntities()[0]->label() : '',
      ],
      "kuota" => [
        "showname" => "Kuota",
        "value" => $node->field_pkt_kuota->getString(),
      ],
      "flag_json" => [
        "showname" => "Flag JSON",
        "value" => $node->field_pkt_flag_json->getString(),
      ],
      "trans_type" => [
        "showname" => "Trans Type",
        "value" => $node->field_pkt_trans_type->getString(),
      ],
      "service" => [
        "showname" => "Services",
        "value" => $node->field_pkt_service->getString(),
      ],
      "indihome_indicator" => [
        "showname" => "Indihome Indicator",
        "value" => $node->field_pkt_indihome_indicator->getString(),
      ],
      "package_detail" => [
        "showname" => "Package Detail",
        "value" => $node->field_pkt_package_detail->getString(),
      ],
    ];
  }

  public static function list_field_detail(Node $node) {
    return [
      [
        "name" => "title",
        "title" => "Title",
        "value" => $node->label(),
      ],
      [
        "name" => "sub_title",
        "title" => "Sub Title",
        "value" => $node->field_pkt_sub_title->getString(),
      ],
      [
        "name" => "package_id",
        "title" => "Package Id",
        "value" => $node->field_pkt_package_id->getString(),
      ],
      [
        "name" => "category",
        "title" => "Category",
        "value" => implode(', ', array_map(fn($cat)=>$cat->label(), $node->field_pkt_category->referencedEntities())),
      ],
      [
        "name" => "tags",
        "title" => "tags",
        "value" => $node->field_pkt_tags->getString(),
      ],
      [
        "name" => "flag",
        "title" => "Flag",
        "value" => $node->field_pkt_flag->getString(),
      ],
      [
        "name" => "speed",
        "title" => "Speed",
        "value" => $node->field_pkt_speed->getString(),
      ],
      [
        "name" => "promo_text",
        "title" => "Promo Text",
        "value" => $node->field_pkt_promo_text->getString(),
      ],
      [
        "name" => "detail_voice",
        "title" => "Detail Voice",
        "value" => $node->field_pkt_detail_voice->getString(),
      ],
      [
        "name" => "detail_inet",
        "title" => "Detail Internet",
        "value" => $node->field_pkt_detail_inet->getString(),
      ],
      [
        "name" => "price_voice",
        "title" => "Price Voice",
        "value" => $node->field_pkt_price_voice->getString(),
      ],
      [
        "name" => "price_internet",
        "title" => "Price Internet",
        "value" => $node->field_pkt_price_internet->getString(),
      ],
      [
        "name" => "price_total",
        "title" => "Price Total",
        "value" => $node->field_pkt_price_total->getString(),
      ],
      [
        "name" => "billing_period",
        "title" => "Periode Pembayaran",
        "value" => $node->field_pkt_billing_period->getString(),
      ],
      [
        "name" => "tipe_paket",
        "title" => "Tipe Paket",
        "value" => !empty($node->field_pkt_tipe_paket->referencedEntities()) ? $node->field_pkt_tipe_paket->referencedEntities()[0]->label() : '',
      ],
      [
        "name" => "kuota",
        "title" => "Kuota",
        "value" => $node->field_pkt_kuota->getString(),
      ],
      [
        "name" => "flag_json",
        "title" => "Flag JSON",
        "value" => $node->field_pkt_flag_json->getString(),
      ],
      [
        "name" => "trans_type",
        "title" => "Trans Type",
        "value" => $node->field_pkt_trans_type->getString(),
      ],
      [
        "name" => "service",
        "title" => "Services",
        "value" => $node->field_pkt_service->getString(),
      ],
      [
        "name" => "indihome_indicator",
        "title" => "Indihome Indicator",
        "value" => $node->field_pkt_indihome_indicator->getString(),
      ],
      [
        "name" => "source",
        "title" => "Source",
        "value" => !empty($node->field_pkt_source->getString()) ? $node->field_pkt_source->referencedEntities()[0]->label() : '',
      ],
      [
        "name" => "package_detail",
        "title" => "Package Detail",
        "value" => $node->field_pkt_package_detail->getString(),
      ],
    ];
  }

}