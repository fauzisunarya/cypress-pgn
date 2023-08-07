<?php

namespace Drupal\product_catalog\Helper;

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Drupal\Component\Utility\Html;
use Drupal\Core\Routing\TrustedRedirectResponse;

class Catalog {

  /**
   * Allowed catalog type
   * @param string $keys id|label|increment
   */
  public function getAllowedType(string $keys = 'increment'){

    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'product_catalog_type']);
    $list = [];
    foreach ($terms as $term) {
      switch ($keys) {
        case 'id':
          $list[$term->id()] = strtolower($term->label());
          break;
        case 'label':
          $list[strtolower($term->label())] = $term->id() ;
          break;
        default:
          $list[] = strtolower($term->label());
          break;
      }
      
    }
    return $list;

  }

  /**
   * Used in product catalog form (add/edit) alter
   */
  public function setQueryStringType(array &$referer, ?string &$type, ?int &$type_id) {

    $allowed_type_ids = $this->getAllowedType('label'); // array label => id
    $allowed_type = array_keys($allowed_type_ids);

    if(!empty($referer['query'])){
      foreach (explode('&', $referer['query']) as $key_value) {
        $query_string = explode('=',$key_value, 2);
        if ( $query_string[0]==='type' && count($query_string)===2 && in_array(strtolower($query_string[1]), $allowed_type) ) {
          $type = strtolower($query_string[1]);
          $type_id = $allowed_type_ids[$type];
        }
      }
    }
    return array_flip($allowed_type_ids); // array contains : ids => value
  }

  public function redirect($redirect_url){
    $response_headers = [
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
    ];
    
    $response = new TrustedRedirectResponse($redirect_url, 301, $response_headers);
    $response->send();exit;
  }

  /**
   * Add condition to load data by "catalog type" taxonomy
   */
  public function onlyLoadForSelectedCatalogType(&$query, string $field_name, array $allowedType = ['paket']) {
    
    $availableType = $this->getAllowedType('label'); // ex format : ['paket'=> "240", "addon"=>"241"]
    $idsType = array_filter($availableType, function($val, $key) use($allowedType){
      return in_array($key, $allowedType);
    }, ARRAY_FILTER_USE_BOTH);

    if (!empty($idsType)) {
      $query->condition($field_name, $idsType, 'IN');
    }
  }

}