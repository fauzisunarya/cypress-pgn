<?php

namespace Drupal\product_catalog\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class DummyController {

  public function fill_product_catalog_type() {
    
    // list terms
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'product_catalog_type']);
    $term_id = null;
    
    foreach ($terms as $term) {
      if (strtolower($term->label()) === 'paket') {
        $term_id = $term->id();
        break;
      }
    }
    unset($terms);

    if (!empty($term_id)) {
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $ids = $entity->getQuery()
                    ->condition('type', 'product_catalog') #type = bundle id (machine name)
                    ->notExists('field_pct_type')
                    ->execute();

      foreach ($entity->loadMultiple($ids) as $catalog) {
        $catalog->field_pct_type = $term_id;
        $catalog->save();
      }
    }

    return new JsonResponse('success');
  }

}