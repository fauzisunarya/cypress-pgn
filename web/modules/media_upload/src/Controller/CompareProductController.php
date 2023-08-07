<?php

namespace Drupal\media_upload\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CompareProductController extends ControllerBase
{
  public function compareProduct(Request $request) {
    $product = (array) $request->request->get('product');
    $product = array_filter($product);

    $entityNode = Drupal::entityTypeManager()->getStorage('node');
    if (count($product)<2) {
      return New JsonResponse("Min product are 2", 422);
    }

    // convert to node object & get value
    $product = array_map(function($val) use($entityNode){
      $query = $entityNode->getQuery()
                ->condition('status', 1)
                ->condition('type', 'paket')
                ->condition('field_pkt_package_id', $val)
                ->execute();
      $node = $entityNode->load(current($query));

      return [
        'title' => $node ? $node->title->getString() : '',
        'sub_title' => $node ? $node->field_pkt_sub_title->getString() : '',
        'package_id' => $node ? (int) $node->field_pkt_package_id->getString() : '',
        'category' => $node ? $node->field_pkt_category->getString() : '',
        'tags' => $node ? $node->field_pkt_tags->getString() : '',
        'flag' => $node ? $node->field_pkt_flag->getString() : '',
        'speed' => $node ? $node->field_pkt_speed->getString() : '',
        'promo_text' => $node ? $node->field_pkt_promo_text->getString() : '',
        'detail_voice' => $node ? $node->field_pkt_detail_voice->getString() : '',
        'detail_internet' => $node ? $node->field_pkt_detail_inet->getString() : '',
        'price_voice' => $node ? $node->field_pkt_price_voice->getString() : '',
        'price_internet' => $node ? $node->field_pkt_price_internet->getString() : '',
        'price_total' => $node ? $node->field_pkt_price_total->getString() : '',
        'billing_period' => $node ? $node->field_pkt_billing_period->getString() : '',
        'tipe_paket' => $node ? (!empty($node->field_pkt_tipe_paket->referencedEntities()) ? $node->field_pkt_tipe_paket->referencedEntities()[0]->label() : '') : '',
        'kuota' => $node ? $node->field_pkt_kuota->getString() : '',
        'flag_json' => $node ? $node->field_pkt_flag_json->getString() : '',
        'trans_type' => $node ? $node->field_pkt_trans_type->getString() : '',
        'service' => $node ? $node->field_pkt_service->getString() : '',
      ];
    }, $product);

    $label = [
      'title' => 'Title',
      'sub_title' => 'Sub title',
      'package_id' => 'Package id',
      'category' => 'Category',
      'tags' => 'Tags',
      'flag' => 'Flag',
      'speed' => 'Speed',
      'promo_text' => 'Promo Text',
      'detail_voice' => 'Detail Voice',
      'detail_internet' => 'Detail Internet',
      'price_voice' => 'Price Voice',
      'price_internet' => 'Price Internet',
      'price_total' => 'Price Total',
      'billing_period' => 'Periode Pembayaran',
      'tipe_paket' => 'Tipe Paket',
      'kuota' => 'Kuota',
      'flag_json' => 'Flag Json',
      'trans_type' => 'Trans Type',
      'service' => 'Service',
    ];

    return New JsonResponse([
      'label' => $label,
      'product' => $product
    ]);
  }
}
?>
