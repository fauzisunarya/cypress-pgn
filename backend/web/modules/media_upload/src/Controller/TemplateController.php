<?php
namespace Drupal\media_upload\Controller;

use CTA15;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Feature12;
use Robo\Task\File\Replace;
use Symfony\Component\HttpFoundation\Response;

/**
 * template (whatsapp, email, sms) for content type "paket" or "product_catalog"
 */
class TemplateController {

  /**
   * Get the list template
   * @param id paket/product catalog, template_type (wa/email/sms)
   */
  public function get_template(){

    // perform validation
    $id = $_POST['id'];
    $template_type = strtolower($_POST['template_type']);
    $available_template = ['email', 'sms', 'whatsapp', 'facebook', 'instagram', 'twitter'];
    if (empty($id)) {
      return new JsonResponse('id is required', 422);
    }
    elseif (empty($template_type)) {
      return new JsonResponse('template_type is required', 422);
    }
    elseif (!in_array($template_type, $available_template)) {
      return new JsonResponse('template_type value is invalid', 422);
    }

    $node_reference = \Drupal::entityTypeManager()->getStorage('node')->load($id);

    if ($node_reference===null) {
      return new JsonResponse('invalid id', 422);
    }

    $node_type = $node_reference->type->entity->get('type');
    if ($node_type!=='paket' && $node_type!=='product_catalog') {
      return new JsonResponse('invalid id', 422);
    }

    // get the query instance
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $query = $query->condition('status', 1);

    // process request
    if ($node_type==='paket') {
      if ($template_type==='sms') {
        $query = $query->condition('type', "paket_template_sms")
                  ->condition('field_pkt_tem_sms_parent', $id);
      }
      elseif ($template_type==='email') {
        $query = $query->condition('type', "paket_template_email")
                  ->condition('field_pkt_tem_email_parent', $id);
      }
      elseif ($template_type==='facebook') {
        $query = $query->condition('type', "paket_template_facebook")
                  ->condition('field_pkt_tem_fb_parent', $id);
      }
      elseif ($template_type==='instagram') {
        $query = $query->condition('type', "paket_template_instagram")
                  ->condition('field_pkt_tem_ig_parent', $id);
      }
      elseif ($template_type==='twitter') {
        $query = $query->condition('type', "paket_template_twitter")
                  ->condition('field_pkt_tem_twitter_parent', $id);
      }
      else{
        $query = $query->condition('type', "paket_template_whatsapp")
                  ->condition('field_pkt_tem_wa_parent', $id);
      }
    }
    elseif ($node_type==='product_catalog') {
      if ($template_type==='sms') {
        $query = $query->condition('type', "product_catalogue_template_sms")
                  ->condition('field_pct_tem_sms_parent', $id);
      }
      elseif ($template_type==='email') {
        $query = $query->condition('type', "product_catalogue_template_email")
                  ->condition('field_pct_tem_email_parent', $id);
      }
      elseif ($template_type==='facebook') {
        $query = $query->condition('type', "product_catalog_template_fb")
                  ->condition('field_pct_tem_fb_parent', $id);
      }
      elseif ($template_type==='instagram') {
        $query = $query->condition('type', "product_catalog_template_ig")
                  ->condition('field_pct_tem_ig_parent', $id);
      }
      elseif ($template_type==='twitter') {
        $query = $query->condition('type', "product_catalog_template_twitter")
                  ->condition('field_pct_tem_twitter_parent', $id);
      }
      else{
        $query = $query->condition('type', "product_catalogue_template_wa")
                  ->condition('field_pct_tem_wa_parent', $id);
      }
    }

    $ids = $query->execute();
    $template_obj = $entity->loadMultiple($ids);

    $return_data = [
      'status' => 'success',
      'message' => "list template $template_type",
      'template' => []
    ];
    
    foreach ($template_obj as $id => $template) {
      $return_data['template'][] = [
        'title' => $template->title->getString(),
        'url' => $_ENV['APP_URL'] . "/node/$id"
      ];
    }

    return new JsonResponse($return_data);
  }
}