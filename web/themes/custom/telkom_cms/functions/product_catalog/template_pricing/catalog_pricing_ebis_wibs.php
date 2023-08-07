<?php

use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

Class CatalogPricingEbisWibs {

  public function tab_template_pricing(Node $catalog, $used_for_api=false){
    // get the list template pricing html
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1)
                ->condition('type', 'template_pricing_catalog');#type = bundle id (machine name)
    
    Drupal::service('product_catalog.catalog_helper')->onlyLoadForSelectedCatalogType($query, 'field_tem_pct_type', ['ebis ncx', 'wibs ncx']);
    
    $templates = $entity->loadMultiple($query->execute());

    // return data list template pricing 
    $items = array_map(function($item){
      return [
        'title' => $item->title->getString(),
        'sub_title' => $item->field_non_ret_prod_desc->getString()
      ];
    }, $catalog->field_pct_list_paket->referencedEntities());

    // get the css & javascript pricing file url
    $themeHandler = \Drupal::service('theme_handler');
    $themePath    = $themeHandler->getTheme($themeHandler->getDefault())->getPath();
    $link_css = $_ENV['APP_URL']."/". $themePath ."/css/pricing-table.css"; 
    $link_js = $_ENV['APP_URL']."/". $themePath ."/dist/js/bootstrap.bundle.min.js"; 

    return array_map(function($template, $template_id) use($items, $link_css, $link_js, $used_for_api){

      $template_html = trim($template->field_temp_cat_html->getString());

      $another_link_css = "";

      if (strpos($template_html, '%ebis_wibs_pricing_1%')!==false) {
        $template_html = $this->template_1($items);
      }

      if ($used_for_api) {
         $template_html = str_replace("\"", "'", $template_html);
         return [
           'template_id' => $template_id,
           'template_name' => $template->title->getString(),
           'template_html' => str_replace(["\r\n", "\r", "\n", "  "], '', html_entity_decode($template_html) ),
           'css_link' => "<link rel='stylesheet' href='$link_css'>$another_link_css",
           'js_link' => "<script src='$link_js'></script>"
         ];
      }
      else {
         return [
           'template_id' => $template_id,
           'title' => $template->title->getString(),
           'html' => $template_html,
           'css_link' => "<link rel='stylesheet' href='$link_css'>$another_link_css",
           'js_link' => "<script src='$link_js'></script>"
         ];
      }
    }, $templates, array_keys($templates));
  }

  /**
   * Get Template pricing catalog 1
   */
  function template_1($items){

    $html = '';
    foreach ($items as $item) {
      $html .= '
        <div class="pricing-ebis-wibs-1">
          <a style="box-sizing: border-box;" class="btn-pricing-primary" href="#">
            <div class="content">
              <p class="title">'.$item['title'].'</p>
              <p class="description">'.$item['sub_title'].'</p>
            </div>
          </a>
        </div>`
      ';
    }

    return '<div style="display:flex;justify-content:center;flex-wrap:wrap;margin:20px 10px;gap:50px;">'.$html.'</div>';
  }

}