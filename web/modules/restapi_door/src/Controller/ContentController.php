<?php

namespace Drupal\restapi_door\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Drupal\restapi_door\Helper\Result;

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
            $body = $entity_obj->body->getValue();
            $urlGetImage = Drupal::request()->getSchemeAndHttpHost().'/apidoor/contents/image?id=';
            if(is_array($body)){
                $dataBody= [];
                foreach($body as $item){
                    $item['value'] = str_replace('[doorimageurl]', $urlGetImage, $item['value']);
                    $dataBody = $item;
                }
                $body = $dataBody;
            }else{
                $body['value'] = str_replace('[doorimageurl]', $urlGetImage, $body['value']);
            }
            $result = array(
                'uuid' => $entity_obj->uuid(),
                'name' => $entity_obj->label(),
                'lang' => $entity_obj->langcode->getString(),
                'module' => $entity_obj->getType(),
                'content_body'  => $body,
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
        $user  = $request->get('user');
        $hasGrant = Drupal::service('restapi_door.app_helper')->hasGrant($user['grants'], 'cms_create_content');
    
        if(!$hasGrant){
            return \Drupal::service('restapi_door.app_helper')->response([
                'status'  => 'failed',
                'code'  => '403',
                'message' => 'You do not have permission to create content',
                'data'    => []
            ], 403);
        }

        try {
            $parameters = json_decode($parameters, true, 512, \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \JsonException('Could not decode request body.', $e->getCode(), $e);
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
            'status' => $parameters['data']['status']? $parameters['data']['lang']:1,
            'created_date' => $parameters['data']['created_date']? $parameters['data']['created_date']:date('Y-m-d H:i:s'),
            'last_update' => $parameters['data']['status']? $parameters['data']['lang']:date('Y-m-d H:i:s'),
            'field_image' => []
        ]);

        // cek jika ada parameter 
        if(isset($parameters['data']['content_image'])&& !empty($parameters['data']['content_image'])){
            $contentBody = $parameters['data']['content_body'];
            foreach($parameters['data']['content_image'] as $file){
                // upload file ke minio
                $filesaved = \Drupal::service('restapi_door.minio_helper')->upload($file, null);
                if($filesaved->code == $filesaved::STATUS_SUCCESS){
                    // marker semua value content yang ada nama filenya
                    $newBodyLooping = [];
                    foreach ($contentBody as $body) {
                        $marker = '[doorimageurl]'.$filesaved->data.$file['filename'];
                        $body['value'] = str_replace($file['filename'], $marker, $body['value']);
                        $newBodyLooping[] = $body;
                    }
                    $contentBody = $newBodyLooping;
                }
            }
            $node->set('body', $contentBody);
        }
        $node->save();

        return Drupal::service('restapi_door.app_helper')->response([
            'status'  => !empty($node->uuid()) ? 'success' : 'failed',
            'code'  => !empty($node->uuid()) ? '0' : '1',
            'info' => !empty($node->uuid()) ? 'success to save content' : 'failed to save content',
            'data'    => $node->uuid()
        ]);

    }

    public function image(Request $request) {                
        /* get key */
        $key = $request->get('id');

        if(!$key){
            return \Drupal::service('restapi_door.app_helper')->response([
                'status'  => 'failed',
                'code'    => '400',
                'message' => 'request parameter not valid. id cannot be empty!',
                'data'    => []
            ], 400);
        }
        try{
            $fileObject = \Drupal::service('restapi_door.minio_helper')->getObject($key);
            $body = $fileObject['Body'];
            if ($body instanceof \GuzzleHttp\Psr7\Stream) {
                $content = $body->getContents();
            } else {
                $content = $body;
            }
            header('Content-type: ' . $fileObject['ContentType']);
            header('Content-Disposition: inline; filename=' . basename($fileObject['@metadata']['effectiveUri']));
            echo $content;
            die();
        }catch(\Exception $ex){
            return Drupal::service('restapi_door.app_helper')->response([
                'status'  => 'failed',
                'code'  => 99,
                'info' =>  $ex->getMessage(),
                'data'    => null
            ]);
        }

        // return $this->getREST()->getResponse($result);
    }

    public function updateContent(Request $request)
    {
        // prepare request
        $parameters    = $request->getContent();
        $user  = $request->get('user');
        $hasGrant = Drupal::service('restapi_door.app_helper')->hasGrant($user['grants'], 'cms_create_content');
    
        if(!$hasGrant){
            return \Drupal::service('restapi_door.app_helper')->response([
                'status'  => 'failed',
                'code'  => 403,
                'message' => 'You do not have permission to create content',
                'data'    => []
            ], 403);
        }

        try {
            $parameters = json_decode($parameters, true, 512, \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \JsonException('Could not decode request body.', $e->getCode(), $e);
        }

        // general validation
        if (empty($parameters['data']['uuid']) || empty($parameters['data']['name']) || empty($parameters['data']['module']) || empty($parameters['data']['content_body'])) {
            return \Drupal::service('restapi_door.app_helper')->response([
                'status'  => 'failed',
                'code'  => 400,
                'message' => 'request parameter not valid. data, data.uuid, data.name, data.module, data.content_body cannot be empty!',
                'data'    => []
            ], 400);
        };

        $entity = Drupal::entityTypeManager()->getStorage('node');
        $node = current($entity->loadByProperties(['type'=>$parameters['data']['module'],'uuid' => $parameters['data']['uuid']]));

        if (empty($node)) {
            return \Drupal::service('restapi_door.app_helper')->response([
                'status'  => 'failed',
                'code'  => 400,
                'message' => 'Invalid uuid',
                'data'    => []
            ], 400);
        }

        // update product
        $node->title = $parameters['data']['name'];
        $node->body = $parameters['data']['content_body'];
        $node->langcode = $parameters['data']['lang'];
        $node->type = $parameters['data']['module'];
        $node->status = $parameters['data']['status']? $parameters['data']['lang']:1;
        $node->last_update = date('Y-m-d H:i:s');
        // Make this change a new revision
        $node->setNewRevision(TRUE);
        $node->revision_log = 'Created revision for node ' . $node->id();
        $node->setRevisionCreationTime(strtotime(date('Y-m-d H:i:s')));
        $node->setRevisionUserId($user['sub']);

        // cek jika ada parameter 
        if(isset($parameters['data']['content_image'])&& !empty($parameters['data']['content_image'])){
            $contentBody = $parameters['data']['content_body'];
            foreach($parameters['data']['content_image'] as $file){
                // upload file ke minio
                $filesaved = \Drupal::service('restapi_door.minio_helper')->upload($file, null);
                if($filesaved->code == $filesaved::STATUS_SUCCESS){
                    // marker semua value content yang ada nama filenya
                    $newBodyLooping = [];
                    foreach ($parameters['data']['content_body'] as $body) {
                        $marker = '[doorimageurl]'.$filesaved->data.$file['filename'];
                        $body['value'] = str_replace($file['filename'], $marker, $body['value']);
                        $newBodyLooping[] = $body;
                    }
                    $contentBody = $newBodyLooping;
                }
            }
            $node->body = $contentBody;
        }
        
        $node->save();

        return Drupal::service('restapi_door.app_helper')->response([
            'status'  => !empty($node->uuid()) ? 'success' : 'failed',
            'code'  => !empty($node->uuid()) ? '0' : '1',
            'info' => !empty($node->uuid()) ? 'success to update content' : 'failed to update content',
            'data'    => $node->uuid()
        ]);

    }

}
