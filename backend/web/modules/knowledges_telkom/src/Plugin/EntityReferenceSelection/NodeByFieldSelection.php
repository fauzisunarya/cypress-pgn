<?php

namespace Drupal\knowledges_telkom\Plugin\EntityReferenceSelection;

use Drupal;
use Drupal\node\Plugin\EntityReferenceSelection\NodeSelection;

/**
 * Provides specific access control for the node entity type.
 *
 * @EntityReferenceSelection(
 *   id = "default:node_by_field",
 *   label = @Translation("Node by field selection"),
 *   entity_types = {"node"},
 *   group = "default",
 *   weight = 3
 * )
 */
class NodeByFieldSelection extends NodeSelection
{
    /**
     * {@inheritdoc}
     */
    protected function buildEntityQuery($match = NULL, $match_operator = 'CONTAINS') {
        $query = parent::buildEntityQuery($match, $match_operator);
        if (array_key_exists('filter', $this->configuration)) {
            $filter_settings = $this->configuration['filter'];
            foreach ($filter_settings as $field_name => $value) {
                $query->condition($field_name, $value, '=');
            }
        }
        return $query;
    }
}
