<?php

class DetailCatalogPaket {

  public function process_detail(&$variables, &$node) {
    $this->tab_detail_catalog_paket($variables, $node);
  }

  private function tab_detail_catalog_paket(&$variables, &$node){
    // set the result data detail product catalog
    $variables['data']['paket'] = [
      // format = "show name", value paket 1, value paket 2, value paket 3, etc
      'title' => ['Title'],
      'field_pkt_package_id' => ['Package Id'],
      'field_pkt_sub_title' => ['Sub Title'],
      'field_pkt_category' => ['Category'],
      'field_pkt_tags' => ['Tags'],
      'field_pkt_flag' => ['Flag'],
      'field_pkt_speed' => ['Speed'],
      'field_pkt_promo_text' => ['Promo Text'],
      // 'field_pkt_detail_voice' => ['Detail Voice'],
      // 'field_pkt_detail_inet' => ['Detail Internet'],
      'field_pkt_price_voice' => ['Price Voice'],
      'field_pkt_price_internet' => ['Price Internet'],
      'field_pkt_price_total' => ['Price Total'],
      'field_pkt_billing_period' => ['Billing Period'],
      'field_pkt_tipe_paket' => ['Tipe Paket'],
      'field_pkt_kuota' => ['Kuota'],
      // 'field_pkt_flag_json' => ['Flag JSON'],
      'field_pkt_trans_type' => ['Trans Type'],
      'field_pkt_service' => ['Service'],
      'field_pkt_indihome_indicator' => ['Indihome Indicator'],
      'field_pkt_source' => ['Source']
      // 'field_pkt_package_detail' => ['Package Detail'],
      // 'field_pkt_addon_list' => ['Addon'], // not used anymore
    ];

    // get paket data
    foreach ($node->field_pct_list_paket->referencedEntities() as $paket) {
      foreach ($variables['data']['paket'] as $key => $value) {
        if ($key==='title') {
          $title = $paket->{$key}->getString();
          $link = $_ENV['APP_URL'] . \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$paket->id());
          $variables['data']['paket'][$key][] = "<a href='$link'>$title</a>";
        }
        elseif ($key==='field_pkt_category') {
          $variables['data']['paket'][$key][] = implode( ', ', array_map(fn($val)=>$val->label(), $paket->{$key}->referencedEntities()) );
        }
        elseif ($key==='field_pkt_tipe_paket') {
          $variables['data']['paket']['field_pkt_tipe_paket'][] = !empty($paket->field_pkt_tipe_paket->referencedEntities()) ? $paket->field_pkt_tipe_paket->referencedEntities()[0]->label() : '';
        }
        elseif ($key==='field_pkt_source') {
          $variables['data']['paket']['field_pkt_source'][] = !empty($paket->field_pkt_source->referencedEntities()) ? $paket->field_pkt_source->referencedEntities()[0]->label() : '';
        }
        else{
          $variables['data']['paket'][$key][] = $paket->{$key}->getString();
        }
      }
    }
  }
}