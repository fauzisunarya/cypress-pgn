<?php

namespace Drupal\restapi_telkom\Helper;

use Drupal;
use Drupal\node\Entity\Node;

class Landing_Helper {

  private ?Node $landing = null;

  public function setLanding(Node $node) {
    if ($node && $node->bundle() === 'landing') {
      $this->landing = $node;
      unset($node);

      return $this;
    }

    return null;
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
        $slug = $homePage ? '/' : '/'.$page->field_website_page_slug->getString();

        if ( ! $homePage && in_array($slug, $arr_slug)) {
          return [];
        }
        $arr_slug[] = $slug;

        return [
          'uuid'      => $page->uuid(),
          'page_id' => (int) $page->id(),
          'page_name' => $page->label(),
          'page_description' => $page->field_website_page_description->getString(),
          'page_slug' => $slug,
          'page_url' => $this->getLandingUrl('regular') . $slug
        ];

      }, $pages));

    }

    return $data;

  }

}