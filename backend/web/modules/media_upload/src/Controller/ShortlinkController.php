<?php
namespace Drupal\media_upload\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Drupal;

class ShortlinkController {

  public function get_shortlink(Request $request){
    $node_id = $request->request->get('node_id');
    if (empty($node_id)) {
      return new JsonResponse('invalid node_id', 422);
    }

    $node = Node::load($node_id);
    if ($node===null) {
      return new JsonResponse('invalid node_id', 422);
    }

    if ($node->type->entity->get('type')==='landing') {
      return new JsonResponse([
        'shortlink' => Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($node)
      ]);
    }

    return new JsonResponse('', 422);
  }

}