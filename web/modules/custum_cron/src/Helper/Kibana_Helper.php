<?php

namespace Drupal\custom_cron\Helper;

use Drupal;
use Drupal\node\Entity\Node;

class Kibana_Helper {

  private ?Node $landing = null;
  private array $landingDetail = [];

  public function setLanding($id) {
    $landing = Node::load($id);
    if ($landing && $landing->type->entity->get('type') === 'landing') {
      $this->landing = $landing;
      unset($landing);

      return $this;
    }

    return null;
  }

  public function getLandingDetail() {

    if ($this->landing) {
      // if detail empty, or exist and different landing
      if ( empty($this->landingDetail) || (!empty($this->landingDetail) && $this->landing->id() != $this->landingDetail['id']) ) {
        $this->landingDetail = [
          'id'  => $this->landing->uuid(),
          'node_id'  => (int) $this->landing->id(),
          'title' => $this->landing->label(),
          'description' => $this->landing->field_lan_website_description->getString()
        ];
      }
    }

    return $this->landingDetail;
  }

  public function getLandingUrl($type = 'regular') {
    $url = '';
    if ($this->landing) {
      if ($type === 'regular') {
        $landing_slug = \Drupal::service('path_alias.manager')->getAliasByPath("/node/{$this->landing->id()}");
        if (str_starts_with($landing_slug, '/landing/')) {
          $url = $_ENV['APP_URL'] .  preg_replace("/^\/landing\//", '/landingpage/', $landing_slug);
        }
      }
      else if ($type === 'subdomain') {
        if ( !empty($subdomain = $this->landing->field_lan_subdomain->getString()) ) {
          $arr = explode('://', $_ENV['APP_URL']);
          if (count($arr) === 2) {
            $url = $arr[0] . '://' . $subdomain . '.' . $arr[1] . '/landingpage';
          }
        }
      }
      else if ($type === 'domain') {
        if ( !empty($domain = $this->landing->field_lan_domain->getString()) ) {
          $arr = explode('://', $_ENV['APP_URL']);
          if (count($arr) === 2) {
            $url = $arr[0] . '://' . rtrim($domain, '/') . '/landingpage';
          }
        }
      }
    }

    return $url;
  }

  public function getPages() {

    $data = [];

    if ($this->landing) {

      $entity = \Drupal::entityTypeManager()->getStorage('node');

      $query = $entity->getQuery();
      $ids = $query->condition('status', 1)
        ->condition('type', 'landing_page')
        ->condition('field_page_landing_id', $this->landing->id())
        ->execute();

      $pages = array_values($entity->loadMultiple($ids));

      $arr_slug = [];

      $data = array_filter(array_map(function($page) use(&$arr_slug){

        $homePage = $page->field_page_type->getString() == 1 ;
        $slug = $homePage ? '' : $page->field_website_page_slug->getString();

        if ( ! $homePage && in_array($slug, $arr_slug)) {
          return [];
        }
        $arr_slug[] = $slug;

        return [
          'id'      => $page->uuid(),
          'node_id' => (int) $page->id(),
          'title' => $page->label(),
          'description' => $page->field_website_page_description->getString(),
          'slug' => $slug
        ];

      }, $pages));

    }

    return $data;

  }

  public function preparePages($pages, $url) {

    try {
      $pages = array_map(function($page) use($url){

        $page['landing'] = $this->getLandingDetail();
        $page['url']  = array_map(function($val) use($page){
          if (!empty($val) && !empty($page['slug'])) {
            return $val . '/' . $page['slug'];
          }
          return $val;
        }, $url);
  
        $ch = curl_init();   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
        curl_setopt($ch, CURLOPT_URL, $page['url']['preview']);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  
        $res = curl_exec($ch);
        
        curl_close($ch); 
  
        // store html tags
        $page['html'] = $res;
        $page['text'] = $this->sanitizeHtml($res);
        $page['images'] = $this->sanitizeImages($res);
  
        unset($page['slug']);
  
        return $page;
  
      }, $pages);

    } catch (\Exception $e) {
      Drupal::logger('comprehensive_search')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }

    return $pages;

  }

  public function sanitizeHtml($res) {
    // remove internal style
    while( false !== ($start = strpos($res, '<style')) ){
      $end = strpos($res, '</style>');
      $res = substr($res, 0, $start) . substr($res, $end + 8 );
    }

    // remove script
    while( false !== ($start = strpos($res, '<script')) ){
      $end = strpos($res, '</script>'); 
      $res = substr($res, 0, $start) . substr($res, $end + 9 );
    }

    // remove tag
    $res = preg_replace("/<[\s\S]+?>/", ' ', $res);

    // remove spaces
    $res = str_replace("\n", ' ', $res); // new line
    $res = preg_replace('!\s+!', ' ', $res); // multiple spaces
    $res = trim($res);

    return $res;
  }

  public function sanitizeImages($res) {
    preg_match_all('/<img[^>]+>/i',$res, $image_tags); 

    $images = [];
    if (count($image_tags) > 0) {
      foreach ($image_tags[0] as $tag) {
        preg_match_all('/(alt|title|src)=("[^"]*"|\'[^\']*\')/i',$tag, $images[]);
      }
    }

    $images = array_map(function($image_component){

      if (empty($image_component[0]) || count($image_component) !== 3) {
        return null;
      }

      $src_idx = $title_idx = $alt_idx = null;
      foreach ($image_component[1] as $idx => $val) {
        switch ($val) {
          case 'src':
            $src_idx = $idx;
            break;
          case 'alt':
            $alt_idx = $idx;
            break;
          case 'title':
            $title_idx = $idx;
            break;
          
          default:
            # code...
            break;
        }
      }

      return [
        'src'   => $src_idx !== null && !empty($image_component[2][$src_idx]) ? trim($image_component[2][$src_idx], "'\"") : '' ,
        'alt'   => $alt_idx !== null && !empty($image_component[2][$alt_idx]) ? trim($image_component[2][$alt_idx], "'\"") : '' ,
        'title' => $title_idx !== null && !empty($image_component[2][$title_idx]) ? trim($image_component[2][$title_idx], "'\"") : '' 
      ];

    }, $images);

    $images = array_filter($images);

    return $images;
  }
}