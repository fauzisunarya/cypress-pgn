<?php

class DetailCatalogEbisWibs {

  public function process_detail(&$variables, &$node) {
    $this->tab_detail_catalog_ebis_wibs($variables, $node);
  }

  private function tab_detail_catalog_ebis_wibs(&$variables, &$node){
    // set the result data detail product catalog
    $variables['data']['paket'] = [
      // format = "show name", value paket 1, value paket 2, value paket 3, etc
      'title' => ['Product  Name'], 
      'field_non_ret_prod_type' => ['Product Type'],
      'field_non_ret_prod_id' => ['Product Id'],
      'field_non_ret_prod_code' => ['Product Code'],
      'field_non_ret_prod_desc' => ['Product Description'],
      'field_pkt_category' => ['Category'],
      'field_pkt_tags' => ['Tags']
    ];

    // get paket data
    foreach ($node->field_pct_list_paket->referencedEntities() as $paket) {
      foreach ($variables['data']['paket'] as $key => $value) {
        if ($key==='title') {
          $title = $paket->{$key}->getString();
          $link = $_ENV['APP_URL'] . \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$paket->id());
          $variables['data']['paket'][$key][] = "<a href='$link'>$title</a>";
        }
        elseif ( in_array($key, ['field_pkt_category', 'field_non_ret_prod_type']) ) {
          $variables['data']['paket'][$key][] = implode( ', ', array_map(fn($val)=>$val->label(), $paket->{$key}->referencedEntities()) );
        }
        else{
          $variables['data']['paket'][$key][] = $paket->{$key}->getString();
        }
      }
    }
  }
}