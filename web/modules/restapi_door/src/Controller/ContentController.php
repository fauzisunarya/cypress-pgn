<?php

namespace Drupal\restapi_door\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

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

}
