<?php

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Component\Utility\Html;

class EbisHelper {

  public static function detail(&$variables=[], &$node=null) {

    $variables['#cache']['max-age'] = 0;
    
    $variables['data'] = [
      'id' => $node->id(), // will be created as input type hidden, the value is used when doing ajax request
      'base_url' => getenv('APP_URL'), // will be created as input type hidden, the value is used when doing ajax request
      'detail' => self::list_field_detail($node)
    ];

    $variables['request_uri'] = explode('?',$_SERVER['REQUEST_URI'])[0];

    $setting_showhide = json_decode($node->field_pkt_master_data_edited->getString(), true); // [$key=>[showname: "", hidden:""], etc]
    $fields = !empty($setting_showhide) && is_array($setting_showhide) && !empty($setting_showhide['field']) ? $setting_showhide['field'] : [];

    $variables['data']['detail'] = array_filter(
      array_map(function($field) use($fields) {
        if(!empty($fields[$field['name']])) {
          if($fields[$field['name']]['hidden'] === true) {
            return null;
          }
          $field['title'] = $fields[$field['name']] ['showname'];
        }
        unset($field['name']);
        return $field;
      }, $variables['data']['detail'])
    );

    unset($variables['content']);

    // load template pricing
    include_custom_functions('/ebis/template/', 'template_pricing');
  
    $variables['template_pricing'] = (new TemplatePricingEbis)->get_list($node);
    
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
    $node->field_non_ret_prod_desc = Html::escape($_POST['prod_desc_value']);
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
      if (!empty($master_data[$key])) {
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
      $data['data'][$key]['disabled'] = in_array($key, EbisHelper::field_disabled_edit()) ? true : false;
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

    $variables['ebis'] = $data;

  }

  public static function field_disabled_edit() {
    return ['prod_type', 'prod_id', 'prod_code'];
  }

  private static function list_field_edit(Node $node) {
    return [
      "title" => [
        "showname" => "Product Name",
        "value" => $node->label()
      ],
      "prod_type" => [
        "showname" => "Product Type",
        "value" => !empty($node->field_non_ret_prod_type->getString()) ? $node->field_non_ret_prod_type->referencedEntities()[0]->label() : ''
      ],
      "prod_id" => [
        "showname" => "Product Id",
        "value" => $node->field_non_ret_prod_id->getString()
      ],
      "prod_code" => [
        "showname" => "Product Code",
        "value" => $node->field_non_ret_prod_code->getString()
      ],
      "prod_desc" => [
        "showname" => "Product Description",
        "value" => $node->field_non_ret_prod_desc->getString()
      ]
    ];
  }

  private static function list_field_detail(Node $node) {
    return [
      [
        "name" => "title",
        "title" => "Product Name",
        "value" => $node->label()
      ],
      [
        "name" => "prod_type",
        "title" => "Product Type",
        "value" => !empty($node->field_non_ret_prod_type->getString()) ? $node->field_non_ret_prod_type->referencedEntities()[0]->label() : '-'
      ],
      [
        "name" => "prod_id",
        "title" => "Product Id",
        "value" => $node->field_non_ret_prod_id->getString()
      ],
      [
        "name" => "prod_code",
        "title" => "Product Code",
        "value" => $node->field_non_ret_prod_code->getString()
      ],
      [
        "name" => "prod_desc",
        "title" => "Product Description",
        "value" => $node->field_non_ret_prod_desc->getString()
      ]
    ];
  }

}