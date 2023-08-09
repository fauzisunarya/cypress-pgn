<?php

namespace Drupal\restapi_door\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;

class ContentController extends ControllerBase{
    public function detail($content_id, Request $request)
    {   

        if (empty($content_id)) {
            return Drupal::service('restapi_door.app_helper')->response([
               'status'  => 'failed',
               'code'  => '400',
               'info' => 'landing page id cannot be empty',
               'data'    => []
            ], 400);
        };
   
        $result = [];
        $fileHelper = Drupal::service('file_url_generator');
        $entity     = Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $content_id]);
   
        if (!empty($entity)) :
            $entity_obj = current($entity);
            $imageData  = $entity_obj->getType() === 'article' ? $entity_obj->get('field_image')->referencedEntities() : $entity_obj->get('field_news_image')->referencedEntities();

            $result = array(
                'uuid' => $entity_obj->uuid(),
                'name' => $entity_obj->label(),
                'lang' => $entity_obj->langcode->getString(),
                'module' => $entity_obj->getType(),
                'content_body'  => $entity_obj->body->getValue(),
                'content_image' => array_map(function($res) use ($fileHelper) {
                    $imageLink = $res->uri->getString();

                    if (str_contains($imageLink, 's3')) {
                        $findS3 = Drupal::service('restapi_telkom.minio_helper')->getFileByURI($imageLink, 'original');

                        $displayImg = $findS3['status'] ? $findS3['data'] : $imageLink;
                    }
                    else {
                        $displayImg = Drupal::request()->getSchemeAndHttpHost() . $fileHelper->generateString($imageLink);
                    };

                    return [
                        'uuid' => $res->uuid(),
                        'filename' => $res->filename->getString(),
                        'mimeType' => $res->filemime->getString(),
                        'location' => $displayImg
                    ];
                }, $imageData),
                'status'       => $entity_obj->isPublished() ? 'published' : 'hidden',
                'created_date' => date("Y-m-d H:i:s", $entity_obj->getCreatedTime()),
                'last_update'  => date("Y-m-d H:i:s", $entity_obj->getChangedTime())
            );
        endif;
   
        return Drupal::service('restapi_door.app_helper')->response([
            'status'  => !empty($result) ? 'success' : 'failed',
            'code'  => !empty($result) ? '0' : '1',
            'info' => !empty($result) ? 'success to retrieve data' : 'theres no data related to selected content id',
            'data'    => $result
        ]);

    }

    public function createContent(Request $request)
    {
        // prepare request
        $parameters    = $request->getContent();

        try {
            $parameters = json_decode($parameters, true, 512, \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new JsonException('Could not decode request body.', $e->getCode(), $e);
        }

        // general validation
        if (empty($parameters['data']['name']) || empty($parameters['data']['module']) || empty($parameters['data']['content_body'])) {
            return \Drupal::service('restapi_door.app_helper')->response([
                'status'  => 'failed',
                'message' => 'request parameter not valid. data, data.name, data.module, data.content_body cannot be empty!',
                'data'    => []
            ], 400);
        };

        $node =  \Drupal\node\Entity\Node::create([
            'title' => $parameters['data']['name'],
            'langcode' => $parameters['data']['lang']? $parameters['data']['lang'] : 'en',
            'type' => $parameters['data']['module'],
            'body' => $parameters['data']['content_body'], 
            'image' => $parameters['data']['content_image'],
            'status' => $parameters['data']['status']? $parameters['data']['lang']:1,
            'created_date' => $parameters['data']['created_date']? $parameters['data']['created_date']:date('Y-m-d H:i:s'),
            'last_update' => $parameters['data']['status']? $parameters['data']['lang']:date('Y-m-d H:i:s')
        ]);
        $node->save();

        return Drupal::service('restapi_door.app_helper')->response([
            'status'  => !empty($node->uuid()) ? 'success' : 'failed',
            'code'  => !empty($node->uuid()) ? '0' : '1',
            'info' => !empty($node->uuid()) ? 'success to save content' : 'failed to save content',
            'data'    => $node->uuid()
        ]);

    }

}
