<?php

namespace Drupal\media_upload\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "crud_content_block",
 *   admin_label = @Translation("CRUD Content block"),
 *   category = @Translation("CRUD Content block"),
 * )
 */
class CrudContentBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $query = \Drupal::entityQuery('node');

    // $query->entityCondition('entity_type', 'node')
    //   ->entityCondition('bundle', 'page')
    //   ->propertyCondition('status', 1);

    $result = $query->execute();

    // if (!empty($result['node'])) {
    //   $nids = array_keys($result['node']);
    //   $nodes = node_load_multiple($nids);

    //   foreach ($nodes as $node) {
    //     // do something awesome
    //   }
    // }
    return [
      '#theme' => 'crud_content_block',
      '#data' => ['age' => '31', 'year' => '2 May 2000', 'query' => $result],
    ];
  }

}