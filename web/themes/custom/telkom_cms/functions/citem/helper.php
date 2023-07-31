<?php

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Component\Utility\Html;

include_custom_functions('/', 'helper');

class CitemHelper {

  public static function detail_citem(&$variables=[], &$node=null) {

    $variables['#cache']['max-age'] = 0;

    $citem_id = $node->id();
    
    $variables['data_citem'] = [
      'citem_id' => $citem_id, // will be created as input type hidden, the value is used when doing ajax request
      'base_url' => getenv('APP_URL'), // will be created as input type hidden, the value is used when doing ajax request
      'detail' => self::list_field_detail_citem($node)
    ];

    $variables['request_uri'] = explode('?',$_SERVER['REQUEST_URI'])[0];

    $setting_showhide = json_decode($node->field_pkt_master_data_edited->getString(), true); // [$key=>[showname: "", hidden:""], etc]
    $fields = !empty($setting_showhide) && is_array($setting_showhide) && !empty($setting_showhide['field']) ? $setting_showhide['field'] : [];

    $variables['data_citem']['detail'] = array_filter(
      array_map(function($field) use($fields) {
        if(!empty($fields[$field['name']])) {
          if($fields[$field['name']]['hidden'] === true) {
            return null;
          }
          $field['title'] = $fields[$field['name']] ['showname'];
        }
        unset($field['name']);
        return $field;
      }, $variables['data_citem']['detail'])
    );

    unset($variables['content']);
    
    // load template pricing
    include_custom_functions('/citem/template/', 'template_pricing');
    
    (new TemplatePricingCitem)->get_list($variables, $node);

  }

  public static function edit_citem(Node $citem) {

    $master_data_edited = json_decode($citem->field_pkt_master_data_edited->getString(), true);
    $master_data_edited = !empty($master_data_edited) && is_array($master_data_edited) && !empty($master_data_edited['field']) ? $master_data_edited : ['field'=>[]];

    $submitted_data = self::list_field_edit_citem($citem);
    foreach ($submitted_data as $key_field => $value) {
      // $value contains "showname" & current "value" of $key_field
      unset($submitted_data[$key_field]['value']);

      // if in list "disabled" field, showname = default, hidden = false;
      if (in_array($key_field, self::field_disabled_edit_citem())) {
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
    
    // update paket ( field that can be updated, List = list_field_edit_citem - field_disabled_edit_citem )
    $citem->title = Html::escape($_POST['title_value']);
    $citem->field_pkt_package_detail = Html::escape($_POST['description_value']);
    $citem->field_pkt_master_data_edited = json_encode($master_data_edited);

    $category = [];
    if (!empty($_POST['category'])) {
        foreach ($_POST['category'] as $term_id) {
            $category[] = ['target_id' => $term_id];
        }
    }

    $citem->field_pkt_category = $category;
    $citem->field_pkt_tags = Html::escape($_POST['tags']);

    $citem->field_pkt_is_customized = 1;

    // Make this change a new revision
    $citem->setNewRevision(TRUE);
    $citem->revision_log = '';
    $citem->setRevisionCreationTime(REQUEST_TIME);
    $citem->setRevisionUserId(\Drupal::currentUser()->id());
    
    $citem->save();

    $alias = \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$citem->id()); // output: /citem/slug

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

  public static function page_edit_citem(&$variables, Node $citem ) {
    /**
     * "data" is the field that will be showed 
     * "master data" is the data from feasibility platform (api to get citem)
     * "master data edited" is field to change showname of item in "data" & hide/show item in "data"
     *  in page detail citem, item in "data" only showed if the same item name in "master data edited" hidden===false 
     */

    $data_citem = [
      'data' => self::list_field_edit_citem($citem),
      'master_data' => [],
      'tags' => ['value' => $citem->field_pkt_tags->getString()],
      'category' => [
        'selected' => [

        ],
        'option' => [

        ]
      ]
    ];

    // get master data (real data from API Pefita)
    $master_data = json_decode($citem->field_pkt_master_data->getString(), true);
    $master_data = !empty($master_data) && is_array($master_data) ? $master_data : [];

    // get setting showname & show/hide
    $master_data_edited = json_decode($citem->field_pkt_master_data_edited->getString(), true);
    $master_data_edited = !empty($master_data_edited) && is_array($master_data_edited) && !empty($master_data_edited['field']) 
      ? $master_data_edited['field'] : [];

    foreach ($data_citem['data'] as $key => $value) {
      // generate master data
      if (!empty($master_data[$key])) {
        $data_citem['master_data'][$key] = $master_data[$key];
      }
      else {
        $data_citem['master_data'][$key] = '';
      }

      // generate parameter of each field
      if (!empty($master_data_edited[$key])) {
        $data_citem['data'][$key]['showname'] = ucwords(strtolower($master_data_edited[$key]['showname'])); // replace showname
        $data_citem['data'][$key]['hidden'] = $master_data_edited[$key]['hidden']; // add hidden "true/false"
      }
      else {
        $data_citem['data'][$key]['hidden'] = false; //default (show)
      }

      // enable or disable field
      $data_citem['data'][$key]['disabled'] = in_array($key, CitemHelper::field_disabled_edit_citem()) ? true : false;
    }

    // selected category
    foreach ($citem->field_pkt_category->referencedEntities() as $cat) {
      $data_citem['category']['selected'][$cat->label()]['id'] = $cat->id();
    }
    // category option
    $vid = 'paket_category';
    $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => $vid]);
    foreach ($terms as $term) {
      $data_citem['category']['option'][$term->label()] = array(
        'id' => $term->id()
      );
    }

    $variables['citem'] = $data_citem;
  }

  public static function field_disabled_edit_citem() {
    return ['product_type', 'product_id', 'product_code', 'product_rel', 'product_rel_id', 'product_status'];
  }

  private static function list_field_edit_citem(Node $citem) {
    return [
      "title" => [
        "showname" => "Product Name",
        "value" => $citem->label()
      ],
      "product_type" => [
        "showname" => "Product Type",
        "value" => !empty($citem->field_ctm_product_type->getString()) ? $citem->field_ctm_product_type->referencedEntities()[0]->label() : '-'
      ],
      "product_id" => [
        "showname" => "Product Id",
        "value" => $citem->field_ctm_product_id->getString()
      ],
      "product_rel" => [
        "showname" => "Product Rel",
        "value" => $citem->field_ctm_product_rel->getString()
      ],
      "product_rel_id" => [
        "showname" => "Product Rel Id",
        "value" => $citem->field_ctm_product_rel_id->getString()
      ],
      "product_code" => [
        "showname" => "Product Code",
        "value" => $citem->field_ctm_product_code->getString()
      ],
      "description" => [
        "showname" => "Description",
        "value" => ThemeHelper::show_text($citem->field_pkt_package_detail->getString(), '')
      ],
      "product_status" => [
        "showname" => "Status",
        "value" => $citem->field_ctm_status->getString()
      ]
    ];
  }

  private static function list_field_detail_citem(Node $node) {
    return [
      [
        "title" => "Product Name",
        "value" => $node->label(),
        "name" => "title"
      ],
      [
        "title" => "Product Type",
        "value" => !empty($node->field_ctm_product_type->getString()) ? $node->field_ctm_product_type->referencedEntities()[0]->label() : '-',
        "name" => "product_type"
      ],
      [
        "title" => "Product Id",
        "value" => $node->field_ctm_product_id->getString(),
        "name" => "product_id"
      ],
      [
        "title" => "Product Rel",
        "value" => $node->field_ctm_product_rel->getString(),
        "name" => "product_rel"
      ],
      [
        "title" => "Product Rel Id",
        "value" => $node->field_ctm_product_rel_id->getString(),
        "name" => "product_rel_id"
      ],
      [
        "title" => "Product Code",
        "value" => $node->field_ctm_product_code->getString(),
        "name" => "product_code"
      ],
      [
        "title" => "Status",
        "value" => $node->field_ctm_status->getString(),
        "name" => "product_status"
      ],
      [
        "title" => "Description",
        "value" => ThemeHelper::show_text($node->field_pkt_package_detail->getString()),
        "name" => "description"
      ],
    ];
  }

}