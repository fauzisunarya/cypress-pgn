<?php

class DetailCatalogCitem {

  public function process_detail(&$variables, &$node) {
    $this->tab_detail_catalog_citem($variables, $node);
  }

  private function tab_detail_catalog_citem(&$variables, &$node){
    
    // set the result data detail product catalog
    $price_title = 'Product Price';

    $current_path = \Drupal::service('path.current')->getPath();
    if (preg_match("/^\/node\/\d+$/i", $current_path) && $node->bundle()==='product_catalog') {
      // in detail catalog page
      $edit_price_url = $_ENV['APP_URL'] . "/product-catalog/{$node->id()}";
      $price_title = [
        '#type' => 'markup',
        '#markup' => '
          Product Price 
          <a href="'.$edit_price_url.'/citem-price" class="btn-citem-price" title="Edit Citem Price"> <i class="bi bi-pencil"></i></a>
          <a href="'.$edit_price_url.'/sync-citem-price" class="btn-citem-price" title="Sync Price"> <i class="bi bi-arrow-repeat"></i></a>
        ',
        '#cache' => ['max-age' => 0]
      ];
    }
    
    $variables['data']['paket'] = [
      // format = "show name", value paket 1, value paket 2, value paket 3, etc
      'title' => ['Product  Name'], 
      'field_ctm_product_type' => ['Product Type'],
      'field_ctm_product_id' => ['Product Id'],
      'field_ctm_product_rel' => ['Product Rel'],
      'field_ctm_product_rel_id' => ['Product Rel Id'],
      'field_ctm_product_code' => ['Product Code'],
      'price' => [  // custom citem price
        $price_title
      ],
      'field_ctm_status' => ['Status'],
      'field_pkt_package_detail' => ['Description'],
      'field_pkt_category' => ['Category'],
      'field_pkt_tags' => ['Tags']
    ];

    // get citem data
    $prices = json_decode($node->field_pct_citem_price->getString(), true);
    $prices = !empty($prices) ? $prices : []; // format : [ '$citem_id' => ["active" => null | tariff_id, price=>[dari api]] ]

    foreach ($node->field_pct_list_paket->referencedEntities() as $citem) {
      foreach ($variables['data']['paket'] as $key => $value) {
        if ($key==='title') {
          $title = $citem->{$key}->getString();
          $link = $_ENV['APP_URL'] . \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$citem->id());
          $variables['data']['paket'][$key][] = "<a href='$link'>$title</a>";
        }
        elseif ( in_array($key, ['field_pkt_category', 'field_ctm_product_type']) ) {
          $variables['data']['paket'][$key][] = implode( ', ', array_map(fn($val)=>$val->label(), $citem->{$key}->referencedEntities()) );
        }
        elseif ( $key === 'price' ) {
          $citem_price = '-';

          $citem_id = $citem->field_ctm_product_id->getString();
          if (!empty($prices[$citem_id]) && !empty($prices[$citem_id]['active'])) {
            foreach ($prices[$citem_id]['list'] as $each_price) {
              if ($each_price['tariff_id'] === $prices[$citem_id]['active']) {
                $citem_price = $each_price['price'] ?? 0;
                break;
              }
            }
          }

          $variables['data']['paket'][$key][] = $citem_price;
        }
        else{
          $variables['data']['paket'][$key][] = $citem->{$key}->getString();
        }
      }
    }
  }
}