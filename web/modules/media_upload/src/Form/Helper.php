<?php

namespace Drupal\media_upload\Form;

use Drupal;

/**
 * Provides the form for adding countries.
 */
class Helper {

  /**
   * @param string $form_type = tipe form landing "add" or "edit"
   * @param int $current_type_id = required if $form_type==='edit'
   */
  public static function validate_landing_page_type(string $form_type = 'add', int $current_type_id = 0) {

    $referer = parse_url(urldecode($_SERVER['REQUEST_URI']));

    // check catalog type in query string ( ?type=...)
    $type = null;
    $type_id = null;
    $list_type = Helper::setQueryStringType($referer, $type, $type_id);

    if (empty($type)) {

      // redirect to use correct type
      $query = $_GET;
      if ($form_type === 'add') {

        $query['type'] = array_values($list_type)[0];
      }
      else {
        // form edit : catalog type from DB
        $current_type = !empty($current_type_id) ? $list_type[$current_type_id] : null;
        $query['type'] = $current_type ?? array_values($list_type)[0];
      }
      
      $query = http_build_query($query);

      return [
        'status' => false,
        'data' => [
          'redirect' => $_ENV['APP_URL']. $referer['path'] ."?$query"
        ]
      ];
    }

    return [
      'status' => true,
      'data' => [
        'landing_page_type_id' => (int) $type_id
      ]
    ];
  }

  private static function setQueryStringType(array &$referer, ?string &$type, ?int &$type_id) {

    $allowed_type_ids = Helper::getAllowedType('label'); // array label => id
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

  private static function getAllowedType(string $keys = 'increment'){

    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'landing_page_type']);
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

}

