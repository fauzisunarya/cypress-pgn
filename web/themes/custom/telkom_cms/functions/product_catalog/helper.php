<?php


use Drupal\node\Entity\Node;

class CatalogHelper {

  /**
   * Process data in detail product catalog
   */
  public function detail_catalog(&$variables, &$node){

    $catalog_type = !empty($node->field_pct_type->getString()) ? strtolower($node->field_pct_type->referencedEntities()[0]->label()) : 'paket';
    $variables['catalog_type'] = $catalog_type;

    // check is in detail catalog citem
    $current_path = \Drupal::service('path.current')->getPath();
    if (preg_match("/^\/node\/\d+$/i", $current_path) && $node->bundle()==='product_catalog' && $catalog_type === 'citem') {
      $all_price_selected = Drupal::service('custom_cron.citem_helper')->checkForCitemPrice($node);
      if (empty($all_price_selected)) {
        \Drupal::service('media_upload.page_helper')->redirect($_ENV['APP_URL'] . "/product-catalog/{$node->id()}/citem-price");
      }
    }

    $variables['data'] = [
      'product_catalog_id' => $node->id(),
      'base_url' => $_ENV['APP_URL'],
      'request_uri' => explode('?',$_SERVER['REQUEST_URI'])[0],
      'title' => $node->title->getString(),
      'catalog_type' => !empty($node->field_pct_type->getString()) ? $node->field_pct_type->referencedEntities()[0]->label() : '',
      'category' => implode( ', ', array_map(fn($val)=>$val->label(), $node->field_pct_category->referencedEntities()) ),
      'tags' => $node->field_pct_tags->getString(),
      'description' => !empty($node->field_pct_description->getValue()) ? $node->field_pct_description->getValue()[0]['value'] : '',
      'paket' => [] // filled in function tab_detail_catalog_xxx
    ];

    switch ($catalog_type) {
      case 'paket':
      case 'addon':
      case 'retail+ebis':
        include_custom_functions('/product_catalog/detail/','detail_catalog_paket');
        (new DetailCatalogPaket)->process_detail($variables, $node);

        include_custom_functions('/product_catalog/template_pricing/','catalog_pricing_paket');
        $CatalogPricingpaket = new CatalogPricingPaket();
        $variables['template_pricing_catalog'] = $CatalogPricingpaket->tab_template_pricing_paket($node);
        $variables['setting_template_pricing'] = $CatalogPricingpaket->tab_template_pricing_setting_paket($node);
        break;
      case 'citem':
        include_custom_functions('/product_catalog/detail/','detail_catalog_citem');
        (new DetailCatalogCitem)->process_detail($variables, $node);

        include_custom_functions('/product_catalog/template_pricing/','catalog_pricing_citem');
        $variables['template_pricing_catalog'] = (new CatalogPricingCitem())->tab_template_pricing($node);
        $variables['setting_template_pricing'] = false;
        break;
      case 'ebis ncx':
      case 'wibs ncx':
        include_custom_functions('/product_catalog/detail/','detail_catalog_ebis_wibs');
        (new DetailCatalogEbisWibs)->process_detail($variables, $node);

        include_custom_functions('/product_catalog/template_pricing/','catalog_pricing_ebis_wibs');
        $variables['template_pricing_catalog'] = (new CatalogPricingEbisWibs())->tab_template_pricing($node);
        $variables['setting_template_pricing'] = false;
        break;
      default:
        $variables['template_pricing_catalog'] = [];
        $variables['setting_template_pricing'] = false;
        break;
    }

    $variables['is_approved'] = $node->field_workflow_status->getString()==='workflow_status_approve' ? true : false; 

  }

  /**
   * Data for page Select product catalog citem price
   */
  public function page_catalog_citem_price(&$variables, Node &$node) {
    if ($node->bundle()==='product_catalog') {

      $catalog_type = !empty($node->field_pct_type->getString()) ? $node->field_pct_type->referencedEntities()[0]->label() : '';
      if (strtolower($catalog_type)!='citem') {
        Drupal::service('media_upload.page_helper')->redirect($_ENV['APP_URL']."/product-catalog");
      }

      // catalog citem which have been approved, can only adited by superadmin
      if ($node->field_workflow_status->getString()==='workflow_status_approve' && !in_array('administrator',\Drupal::currentUser()->getRoles())) {
        \Drupal::messenger()->addError('Not allowed to change approved citem price');
        \Drupal::service('media_upload.page_helper')->redirect($_ENV['APP_URL'] . \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$node->id()));
      }


      // is submit edit form, process & exit
      if (!empty($_POST['submit-citem-price'])) {
        $this->submit_catalog_citem_price($node);
        return;
      }

      // check & update/set citem price
      Drupal::service('custom_cron.citem_helper')->checkForCitemPrice($node);

      $prices = json_decode($node->field_pct_citem_price->getString(), true);
      $prices = !empty($prices) ? $prices : []; // format : [ '$citem_id' => ["active" => null | tariff_id, price=>[dari api]] ]

      $citem_multi_price = array_filter($prices, function($val, $key){
        return count($val['list']) > 1;
      }, ARRAY_FILTER_USE_BOTH);
      
      // redirect to detail catalog if doesn't has multiple price
      $alias = \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$node->id()); // output: /citem/slug
      if (empty($citem_multi_price)) {
        \Drupal::messenger()->addStatus("All Citem has only one price. Auto selected");
        \Drupal::service('media_upload.page_helper')->redirect($_ENV['APP_URL'] . $alias);
      }

      \Drupal::messenger()->addStatus("Only show citem which has multiple price");

      // prepare variable
      $variables['form'] = [
        'title' => $node->label(),
        'fields' => []
      ];
      foreach ($citem_multi_price as $citem_id => $price) {
        $variables['form']['fields'][] = [
          'title' => $price['data_citem']['title'],
          'name' => $citem_id,
          'selected' => $price['active'],
          'option' => array_map(function($val){
            return [
              'title' => $val['tariff_name'],
              'value' => $val['tariff_id']
            ];
          },$price['list'])
        ];
      }

      $variables['page']['title'] = [
        '#type' => 'markup',
        '#markup' => '<a class="navbar-brand col-md-3 col-lg-2 me-0 fw-bold" href="'.$_ENV['APP_URL'] . $alias.'">'.$node->label().' (Select Price)</a>'
      ];

    }
    else {
      Drupal::service('media_upload.page_helper')->redirect($_ENV['APP_URL']."/product-catalog");
    }
  }

  private function submit_catalog_citem_price(&$node) {

    $prices = json_decode($node->field_pct_citem_price->getString(), true);
    $prices = !empty($prices) ? $prices : []; // format : [ '$citem_id' => ["active" => null | tariff_id, price=>[dari api]] ]

    $citem_multi_price = array_filter($prices, function($val, $key){
      return count($val['list']) > 1;
    }, ARRAY_FILTER_USE_BOTH);

    foreach ($citem_multi_price as $citem_code => $price) {
      if (!empty($_POST[$citem_code])) {
        $prices[$citem_code]['active'] = $_POST[$citem_code];
      }
    }

    $node->field_pct_citem_price = json_encode($prices);
    $node->save();

    // redirect
    \Drupal::messenger()->addStatus("Success select citem price");
    $alias = \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$node->id()); // output: /citem/slug
    Drupal::service('media_upload.page_helper')->redirect($_ENV['APP_URL'] . $alias);
  }

   /**
   * Data for page Select product catalog citem price
   */
  public function sync_catalog_citem_price(Node &$node) {
    if ($node->bundle()==='product_catalog') {

      $catalog_type = !empty($node->field_pct_type->getString()) ? $node->field_pct_type->referencedEntities()[0]->label() : '';
      if (strtolower($catalog_type)!='citem') {
        Drupal::service('media_upload.page_helper')->redirect($_ENV['APP_URL']."/product-catalog");
      }

      // check & update/set citem price
      Drupal::service('custom_cron.citem_helper')->checkForCitemPrice($node, true); // true = re-get citem price from pefita (update price)

      // add message & redirect to detail catalog citem
      \Drupal::messenger()->addStatus("Success sync citem price");
      \Drupal::service('media_upload.page_helper')->redirect($_ENV['APP_URL'] . \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$node->id()));

    }
    else {
      Drupal::service('media_upload.page_helper')->redirect($_ENV['APP_URL']."/product-catalog");
    }
  }

}