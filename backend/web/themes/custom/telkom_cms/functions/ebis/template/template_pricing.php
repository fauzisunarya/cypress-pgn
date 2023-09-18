<?php

use Drupal\node\Entity\Node;

class TemplatePricingEbis {

  public function get_list(Node $node, $used_for_api=false) {

    // get the list template pricing html
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
                ->condition('type', 'template_pricing_paket');#type = bundle id (machine name)

    Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($query, 'field_tem_pkt_type', ['ebis']);
    
    $templates = $entity->loadMultiple($query->execute());

    $paket_data = [
        'title' => $node->title->getString(),
        'sub_title' => $node->field_non_ret_prod_desc->getString()
    ];

    $themeHandler = \Drupal::service('theme_handler');
    $themePath    = $themeHandler->getTheme($themeHandler->getDefault())->getPath();
    $link_css = $_ENV['APP_URL']."/". $themePath ."/css/pricing-table.css"; 
    $link_js = $_ENV['APP_URL']."/". $themePath ."/dist/js/bootstrap.bundle.min.js"; 

    return array_map(function($template, $template_id) use($paket_data, $link_js, $link_css, $used_for_api){
      
      $template_html = $template->field_temp_pkt_html->getString();

      $template_html = str_replace('%title%', $paket_data['title'], $template_html);
      $template_html = str_replace('%sub_title%', $paket_data['sub_title'], $template_html);

      if ($used_for_api) {
        $template_html = str_replace("\"", "'", $template_html);
        return [
          'template_id' => $template_id,
          'template_name' => $template->title->getString(),
          'template_html' => str_replace(["\r\n", "\r", "\n", "  "], '', html_entity_decode($template_html) ),
          'css_link' => "<link rel='stylesheet' href='$link_css'>",
          'js_link' => "<script src='$link_js'></script>"
        ];
      }
      else {
        return [
          'template_id' => $template_id,
          'title' => $template->title->getString(),
          'html' => '<div style="display:flex;justify-content:center;flex-wrap:wrap;margin:20px 10px;gap:50px;">'.$template_html.'</div>',
          'css_link' => "<link rel='stylesheet' href='$link_css'>",
          'js_link' => "<script src='$link_js'></script>"
        ];
      }

    }, $templates, array_keys($templates));

  }
}