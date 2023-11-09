<?php
namespace Drupal\custom_cron\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\workflow\Entity\WorkflowTransition;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Drupal\Core\Database\Database;
use stdClass;

class DummyController {

  public function make_paket_media_only_one_image(){
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()
                  ->condition('type', 'paket_media') #type = bundle id (machine name)
                  ->execute();

    $paket_media = $entity->loadMultiple($ids);
    foreach ($paket_media as $media) {
      if (count($media->field_media_image->getValue())>1) {
        $media->field_media_image = [$media->field_media_image->getValue()[0]];
        $media->save();
      }
    }
    return new JsonResponse('oke');
  }

  public function modify_landing_form_field_address_to_coordinate(){

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()
                  ->condition('type', 'landing_custom_form') #type = bundle id (machine name)
                  ->condition("field_lcf_form_scheme", '%"field":"address"%', 'LIKE')
                  ->sort('changed', 'DESC')
                  ->execute();
    
    $forms = $entity->loadMultiple($ids);

    foreach ($forms as $form) {

      // purpose : split field "address" (map coordinate + textarea address) to 2 field : map coordinate & textarea address

      $scheme = json_decode($form->field_lcf_form_scheme->getString(), true);

      if ($scheme) {
        $fields = $scheme['fields'];

        $field_map_idx = null;
        $field_map = null;
        foreach ($fields as $key => $field) {
          if ($field['field'] === 'address') {
            $field_map_idx = $key;
            $field_map = $field;
            break;
          }
        }

        if ($field_map_idx!==null && $field_map!==null) {
          // add field
          array_splice($fields, $field_map_idx+1, 0, [$field_map]);
          
          unset($fields[$field_map_idx]['coordinate'], $fields[$field_map_idx+1]['coordinate']);
          
          $fields[$field_map_idx]['name'] = $field_map['coordinate']['name'];
          $fields[$field_map_idx]['label'] = $field_map['coordinate']['label'];
          $fields[$field_map_idx]['placeholder'] = $field_map['coordinate']['placeholder'];
          $fields[$field_map_idx]['field'] = 'coordinate';
  
          $fields[$field_map_idx+1]['field'] = 'textarea';
          
          $scheme['fields'] = $fields;
  
          $form->field_lcf_form_scheme = json_encode($scheme);
          $form->save();
        }
      }
    }

    return new JsonResponse('oke');
  }

  public function add_another_field_inside_field_form(){
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()
                  ->condition('type', 'landing_custom_form') #type = bundle id (machine name)
                  ->execute();
    
    $forms = $entity->loadMultiple($ids);
    foreach ($forms as $form) {

      // purpose : add "another field" inside each field, to make all field property equal.
      // another field currently used for coordinate field

      $scheme = json_decode($form->field_lcf_form_scheme->getString(), true);

      if ($scheme) {
        $scheme['fields'] = array_map(function($field){
          $field['otherfield'] = [
            'name' => '',
            'label' => 'Label',
            'placeholder' => 'Text sementara',
            'show' => false
          ];
          return $field;
        }, $scheme['fields']);

        $form->field_lcf_form_scheme = json_encode($scheme);
        $form->save();
      }
    }

    return new Response('update field success');
  }

  public function remove_addon_from_paket(){
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()
                  ->condition('type', 'paket') #type = bundle id (machine name)
                  ->condition('field_pkt_master_data', '"addon_list":', 'CONTAINS')
                  ->execute();
    foreach ($entity->loadMultiple($ids) as $paket) {
      // reset addon (addon not used anymore)
      $paket->field_pkt_addon_list = [];

      $master_data = json_decode($paket->field_pkt_master_data->getString(), true);
      if ($master_data) {
        unset($master_data['addon_list']);
      }

      $master_data_edited = json_decode($paket->field_pkt_master_data_edited->getString(), true);
      if ($master_data_edited) {
        unset($master_data_edited['field']['addon_list']);
      }

      $paket->field_pkt_master_data = json_encode($master_data);
      $paket->field_pkt_master_data_edited = json_encode($master_data_edited);

      $paket->save();

    }

    return new Response('success remove addon from paket');
  }

  public function move_tipe_paket_to_taxonomy_backup(){

    // general list term in taxonomy "tipe_paket"
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'tipe_paket']);
    $arr_tipe_paket = [];
    foreach ($terms as $term) {
        $arr_tipe_paket[$term->label()] = $term->id();
    }
    unset($terms);

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()
                  ->condition('type', 'paket') #type = bundle id (machine name)
                  ->notExists('field_pkt_tipe_paket_backup')
                  ->execute();

    foreach ($entity->loadMultiple($ids) as $paket) {
      // get value tipe paket
      $tipe_paket = $paket->field_pkt_tipe_paket->getString();

      if (!empty($tipe_paket)) {
        
        if (empty($arr_tipe_paket[$tipe_paket])) {
          $term = Term::create(array(
            'name' => $tipe_paket,
            'vid' => 'tipe_paket'
          ));
          $term->save();

          $arr_tipe_paket[$term->label()] = $term->id();
        }
        $paket->field_pkt_tipe_paket_backup = $arr_tipe_paket[$tipe_paket];
  
        $paket->save();
      }

    }

    return new Response('success move tipe paket');
  }

  public function restore_tipe_paket_from_backup(){

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()
                  ->condition('type', 'paket') #type = bundle id (machine name)
                  ->notExists('field_pkt_tipe_paket')
                  ->exists('field_pkt_tipe_paket_backup')
                  ->execute();

    foreach ($entity->loadMultiple($ids) as $paket) {
      // get value tipe paket
      $tipe_paket = $paket->field_pkt_tipe_paket_backup->getValue()[0]['target_id'];

      if (!empty($tipe_paket)) {
        $paket->field_pkt_tipe_paket = $tipe_paket;
        $paket->save();
      }

    }

    return new Response('success restore tipe paket');
  }

  public function assign_empty_landing_type_to_ao(){
    // list terms
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'landing_page_type']);
    $ao_term_id = null;
    foreach ($terms as $term) {
      if (str_starts_with($term->label(), 'AO')) {
        $ao_term_id = $term->id();
        break;
      }
    }
    unset($terms);

    if (!empty($ao_term_id)) {
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $ids = $entity->getQuery()
                    ->condition('type', 'landing') #type = bundle id (machine name)
                    ->notExists('field_lan_landing_type')
                    ->execute();

      foreach ($entity->loadMultiple($ids) as $landing) {
        $landing->field_lan_landing_type = $ao_term_id;
        $landing->save();
      }
    }

    return new Response('success assign empty landing type to AO/PSB');
  }

  public function regenerate_shortlink($shortlink_domain){

    $entity = \Drupal::entityTypeManager()->getStorage('node');

    $query = $entity->getQuery();

    $or_condition = $query->orConditionGroup()
                          ->condition('field_shortlink', "%{$shortlink_domain}%", 'NOT LIKE')
                          ->notExists('field_shortlink'); //if empty field

    $ids = $query->condition('type', 'landing') #type = bundle id (machine name)
                ->condition($or_condition)
                ->execute();

    foreach ($entity->loadMultiple($ids) as $landing) {
      $link = \Drupal::service('media_upload.shortlink_helper')->get_landing_full_link($landing);
      $landing->field_shortlink = \Drupal::service('media_upload.shortlink_helper')->generate_shortlink($link);
      $landing->save();
    }
    
    return new Response('success generate shortlink');
  }

  public function fill_citem_type_customized_null_to_false(){

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $ids = $query
          ->condition('status', 1)
          ->condition('type', ['citem', 'ebis', 'wibs'], 'IN') #type = bundle id (machine name)
          // ->condition('field_pkt_is_customized', null, 'IS NULL')
          ->condition('field_pkt_is_customized', null, 'IS NULL')
          ->execute();

    foreach ($entity->loadMultiple($ids) as $key => $each) {
      $each->field_pkt_is_customized = false;
      $each->save();
    }

    return new Response('success');
  }

  // content type "landing_page" copy value field_website_id to field_page_landing_id
  public function copy_and_fill_landing_page_parent() {

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $ids = $query
          ->condition('type', 'landing_page') #type = bundle id (machine name)
          ->notExists('field_page_landing_id')
          ->execute();

    foreach ($entity->loadMultiple($ids) as $key => $each) {
      $each->field_page_landing_id = $each->field_website_id->getString();
      $each->save();
    }

    return new Response('success');
  }

}