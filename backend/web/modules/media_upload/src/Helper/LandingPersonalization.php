<?php

namespace Drupal\media_upload\Helper;

use Drupal\node\Entity\Node;
use Drupal;

class LandingPersonalization {

  /**
   * Assign cookies to user & return redirect page id based on personalization rules
   * 
   * @return int|null redirect_page_id
   */
  public function process_personalization(Node $page) {
    // this conditional is to handle bugs from chrome. chrome send request twice, the first one is the valid reqeust (referer!=uri), the second is invalid (when referer=uri)
    if (empty($_SERVER['HTTP_REFERER']) || ( !empty($_SERVER['HTTP_REFERER']) && !str_ends_with($_SERVER['HTTP_REFERER'], $_SERVER['REQUEST_URI']) ) ) {

      // get available personalization category
      $personalization_category = Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'personalization_category']);
      $personalization_available = [ 'user_tag' => [], 'rules' => [] ];
      foreach ($personalization_category as $item) {
        $personalization_available['user_tag'][] = [
          "label" => $item->label(),
          "options" => array_map( function($val){
            return $val['value'];
          },$item->field_pec_list->getValue()),
        ];
      }
  
      // get page personalization
      $personalization_page = $page->field_page_personalization->getString();
      if (empty($personalization_page)) {
        $personalization_page = [ 'user_tag' => [], 'rules' => [] ];
      }
      else{
        $personalization_page = json_decode($personalization_page, true);
        $personalization_page = !isset($personalization_page['user_tag']) || !isset($personalization_page['rules']) ? [ 'user_tag' => [], 'rules' => [] ] : $personalization_page;
      }
  
      // get tag
      $tag_available = $this->personalization_convert_to_label_options($personalization_available);
      $tag_page = $this->personalization_convert_to_label_options($personalization_page);
      
      $assign_to_user = $this->personalization_tag_intersect($tag_available, $tag_page);
  
      // assign user cookies
      $this->personalization_assign_to_user($assign_to_user);

      // get max personalization value
      $maxValue = $this->personalization_get_max_cookie_value($tag_available);

      // redirect rules
      return $this->persolization_process_rule($maxValue, $personalization_page['rules']);

    }  

    return null;
  }

  /**
   * Get same array key and value from 2 array personalization user tag
   * 
   * @param array like ["label" => [...$options]]
   */
  private function personalization_tag_intersect(array $personalization_key_val_list, array $personalization_key_val_page) {
    
    $result = [];
    foreach ($personalization_key_val_page as $label => $options) {
      if (!empty($personalization_key_val_list[$label])) {
        $intersect_option = array_intersect($options, $personalization_key_val_list[$label]);
        if (count($intersect_option)>0) {
          $result[$label] = $intersect_option;
        }
      }
    }

    return $result;
  }

  private function personalization_convert_to_label_options(array $personalization) {
    $result = [];
    foreach ($personalization['user_tag'] as $tag) {
      $label = Drupal::service('media_upload.slug_helper')->slug($tag['label']);
      foreach ($tag['options'] as $option) {
        if (empty($result[$label])) {
          $result[$label] = [];
        }
        $result[$label][] = Drupal::service('media_upload.slug_helper')->slug($option);
      }
    }
    return $result;
  }

  private function personalization_assign_to_user(array $key_val) {
    foreach ($key_val as $label => $options) {
      if (empty($_COOKIE[$label])) {
        // assign cookie value
        $cookies_value = [];
        foreach ($options as $option) {
          $cookies_value[$option] = 1;
        }
      }
      else {
        $cookies_value = json_decode($_COOKIE[$label], true);
        $cookies_value = empty($cookies_value) ? [] : $cookies_value;
        foreach ($options as $option) {
          // update counter
          $cookies_value[$option] = !empty($cookies_value[$option]) && (int) $cookies_value[$option] > 0 ?  ($cookies_value[$option] + 1) : 1;
        }
      }

      // format like : 'umur' = '{"21-25":43,"15-20":41,"26-30":41}'
      setcookie($label, json_encode($cookies_value), array (
        'expires' => time()+100*100*100*100*100, 
        'path' => '/', 
        'domain' => '.'.$_SERVER['HTTP_HOST'], // leading dot for compatibility or use subdomain
        'secure' => true,     // or false
        'httponly' => true,    // or false
        'samesite' => 'Strict' // None || Lax  || Strict
      ));
    }
  }

  private function personalization_get_max_cookie_value($user_tag) {
    $max = [];
    foreach ($user_tag as $label => $options) {
      $max[$label] = ['option'=> '', 'amount'=> 0];
      if (!empty($_COOKIE[$label])) {
        $cookies_value = json_decode($_COOKIE[$label], true);
        $cookies_value = empty($cookies_value) ? [] : $cookies_value;
        if (!empty($cookies_value)) {
          foreach ($options as $option) {
            if (!empty($cookies_value[$option]) && $cookies_value[$option] > $max[$label]['amount']) {
              $max[$label] = ['option'=> $option, 'amount'=> $cookies_value[$option]];
            }
          }
        }
      }

      if (empty($max[$label]['option'])) {
        $max[$label] = '';
      }
      else{
        $max[$label] = $max[$label]['option'];
      }
    }
    return $max;
  }

  private function persolization_process_rule($user_cookies, $rules) {
    $redirectPage = null;
    foreach ($rules as $rule) {
      $match = true;
      // check criteria
      foreach ($this->personalization_convert_to_label_options($rule) as $label => $options) {
        // check with user cookies
        if (!empty($options) && !in_array($user_cookies[$label], $options)) {
          // if user cookies not exist in options
          $match = false;
          break;
        }
      }
      if ($match) {
        // match = break, ignore next rule, there is a priority in landing builder
        $redirectPage = $rule['redirect'];
        break;
      }
    }
    return $redirectPage;
  }

}