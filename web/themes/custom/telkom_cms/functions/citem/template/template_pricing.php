<?php

require_once __DIR__ . '/../../helper.php';
class TemplatePricingCitem extends TemplatePricing {

  public function get_list(&$variables, &$node) {

    // get the list template pricing html
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
                ->condition('type', 'template_pricing_paket');#type = bundle id (machine name)

    Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($query, 'field_tem_pkt_type', ['citem']);
    
    $templates = $entity->loadMultiple($query->execute());

    $paket_data = [
        'title' => $node->title->getString(),
        'sub_title' => $node->field_pkt_package_detail->getString(),
        'price' => $this->convert_price_format($node->field_pkt_price_total->getString())
    ];

    $themes_path = '/themes'. explode('themes', __DIR__)[1];
    $themes_path = preg_replace("/functions.*$/", '', $themes_path);
    $link_css = $_ENV['APP_URL']. $themes_path ."css/pricing-table.css"; 
    $link_js = $_ENV['APP_URL']. $themes_path ."dist/js/bootstrap.bundle.min.js"; 

    $variables['template_pricing'] = array_map(function($template, $template_id) use($paket_data, $link_js, $link_css){
      
      $template_html = $template->field_temp_pkt_html->getString();

      $template_html = str_replace('%title%', $paket_data['title'], $template_html);
      $template_html = str_replace('%sub_title%', $paket_data['sub_title'], $template_html);
      $template_html = str_replace('%price%', $paket_data['price'], $template_html);

      return [
        'template_id' => $template_id,
        'title' => $template->title->getString(),
        'html' => '<div style="display:flex;justify-content:center;flex-wrap:wrap;margin:20px 10px;gap:50px;">'.$template_html.'</div>',
        'css_link' => "<link rel='stylesheet' href='$link_css'>",
        'js_link' => "<script src='$link_js'></script>"
      ];

    }, $templates, array_keys($templates));

  }
}