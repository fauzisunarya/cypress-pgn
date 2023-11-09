<?php

namespace Drupal\media_upload\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\file\Entity\File;

use function GuzzleHttp\json_decode;
use function PHPSTORM_META\type;
use Drupal\Component\Serialization\Json;

/**
 * Provides a resource to handle rest media upload
 * @RestResource(
 *   id = "custom_rest_project_image_asset",
 *   label = @Translation("Custom Rest Project Image Asset"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/project/image-asset",
 *   }
 * )
 */
class RestProjectImageAsset extends ResourceBase {
  /**
   * A current user instance which is logged in the session.
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $loggedUser;
  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $config
   *   A configuration array which contains the information about the plugin instance.
   * @param string $module_id
   *   The module_id for the plugin instance.
   * @param mixed $module_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A currently logged user instance.
   */
  public function __construct(
    array $config,
    $module_id,
    $module_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
    parent::__construct($config, $module_id, $module_definition, $serializer_formats, $logger);

    $this->loggedUser = $current_user;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $config, $module_id, $module_definition) {
    return new static(
      $config,
      $module_id,
      $module_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('sample_rest_resource'),
      $container->get('current_user')
    );
  }
  /**
   * Responds to GET request.
   * Returns a list of media where paket reference is empty (become global asset)
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   * Throws exception expected.
   */
  public function get() {

    // get the list paket media where empty paket references
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $ids = $query->condition('status', 1)
      ->condition('type', 'paket_media')#type = bundle id (machine name)
      ->notExists('field_media_paket_ref') //list image asset (global) for landing page
      ->condition('field_workflow_status', 'workflow_status_approve')
      #->sort('created', 'ASC') #sorted
      #->pager(15) #limit 15 items
      ->sort('created' , 'DESC')
      ->range(0, 50)
      ->execute();

    $media_arrobj = $entity->loadMultiple($ids);

    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'paket_media']);
    $arr_category = [];
    foreach ($terms as $term) {
        $arr_category[] = $term->label();
    }
    unset($terms);

    // define the result array
    $return = [
      'status' => true,
      'message' => 'complete',
      'content' => [],
      'category'=> $arr_category
    ];

    foreach ($media_arrobj as $key => $media_obj) {
      $list_image = $media_obj->field_media_image->getValue();

      foreach ($list_image as $image) {

        $image_file = File::load($image['target_id']);

        if ($image_file) {
          $image_url = "{$_ENV['APP_URL']}/restapi/v1/media_render/{$image_file->uuid()}";
          // list($width, $height) = getimagesize($image_url);

          $image = [
            'id' => $image['target_id'],
            'filename' => $image_file->getFilename(),
            'uri' => $image_url,
            'thumbnail' => $image_url,
            'filesize' => $this->formatSizeUnits($image_file->getSize()),
            'width' => '',//$width . "px",
            'height' => '',//$height . "px",
            'category' => implode(',', array_map(function($res){
              return $res->label();
            }, $media_obj->field_media_category->referencedEntities())),
            'date' => '',
            'title'     => $media_obj->label(),
            'description'=> $media_obj->field_media_description->getString()
          ];
          $return['content'][] = $image;
        }
      }
    }


    return new JsonResponse($return);
  }

  public static function formatSizeUnits($bytes){
      if ($bytes >= 1073741824)
      {
          $bytes = number_format($bytes / 1073741824, 2) . ' GB';
      }
      elseif ($bytes >= 1048576)
      {
          $bytes = number_format($bytes / 1048576, 2) . ' MB';
      }
      elseif ($bytes >= 1024)
      {
          $bytes = number_format($bytes / 1024, 2) . ' KB';
      }
      elseif ($bytes > 1)
      {
          $bytes = $bytes . ' bytes';
      }
      elseif ($bytes == 1)
      {
          $bytes = $bytes . ' byte';
      }
      else
      {
          $bytes = '0 bytes';
      }

      return $bytes;
  }

}