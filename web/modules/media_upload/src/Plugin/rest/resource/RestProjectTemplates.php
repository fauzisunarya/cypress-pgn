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
use Drupal\node\Entity\Node;

use function GuzzleHttp\json_decode;
use function PHPSTORM_META\type;
use Drupal\Component\Serialization\Json;

/**
 * Provides a resource to handle rest media upload
 * @RestResource(
 *   id = "custom_rest_project_templates",
 *   label = @Translation("Custom Rest Project Templates"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/project/templates",
 *   }
 * )
 */
class RestProjectTemplates extends ResourceBase {
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
   * Returns a list of templates
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   * Throws exception expected.
   */
  public function get() {
    // for saving template from dummy json (only one time for initial list template)
    // $data = $this->save_template_from_dummy_json();

    // get the list of template
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $template_ids = $query->condition('status', 1)
      ->condition('type', 'template')#type = bundle id (machine name)
      ->execute();

    $template_arrobj = $entity->loadMultiple($template_ids);

    // define return variable
    $data = [];

    foreach ($template_arrobj as $template_id => $template_obj) {
      // for push into $data
      $category = $template_obj->field_tem_category->referencedEntities();
      $data_template = [
        'image' => $template_obj->field_tem_image_link->getString(),
        'title' => $template_obj->title->getString(),
        'category' => !empty($category) ? $category[0]->label() : '', // get the category label
        'pages' => []
      ];

      // get the pages list of template
      $entity = \Drupal::entityTypeManager()->getStorage('node');
      $query = $entity->getQuery();
      $page_ids = $query->condition('status', 1)
        ->condition('type', 'template_page')#type = bundle id (machine name)
        ->condition('field_tem_page_template_id', $template_id )
        ->execute();

      $page_arrobj = $entity->loadMultiple($page_ids);

      // insert page to $data_template['pages']
      foreach ($page_arrobj as $page_id => $page_obj) {
        array_push($data_template['pages'], [
          'title' => $page_obj->title->getString(),
          'description' => $page_obj->field_tem_page_description->getString(),
          'slug' => $page_obj->field_tem_page_slug->getString(),
          'homepage' => $page_obj->field_tem_page_type->getString()==="1" ? true : false,
          'label' => $page_obj->field_tem_page_label->getString(),
          'image' => $page_obj->field_tem_page_image_link->getString(),
          'blocks' => json_decode($page_obj->field_tem_page_blocks->getString()),
          'personalization' => [ 'user_tag' => [], 'rules' => [] ] //default
        ]);
      }

      array_push($data, $data_template);
    }

    return new JsonResponse( $data );
  }

  /**
   * store templates to drupal database from dummy templates.php
   */
  public function save_template_from_dummy_json(){
    ob_start();
    include_once "templates.php";
    $data = ob_get_contents();
    ob_end_clean();

    $templates_data = json_decode($data);

    foreach ($templates_data as $template_data) {
      // save data template
      $template = Node::create([
        'type'        => 'template',
        'title'       => $template_data->title,
        'field_tem_image_link' => $template_data->image
        //skip category (manual setting after content has been created)
      ]);
      $template->save();

      foreach ($template_data->pages as $page) {
        // save data pages for template
        $template_page = Node::create([
          'type'        => 'template_page',
          'title' => $page->title,
          'field_tem_page_template_id' => $template->id(),
          'field_tem_page_description' => $page->description,
          'field_tem_page_slug' => $page->slug,
          'field_tem_page_type' => $page->homepage===true ? 1 : 0,
          'field_tem_page_label' => $page->label,
          'field_tem_page_image_link' => $page->image,
          'field_tem_page_blocks' => json_encode($page->blocks)
        ]);
        $template_page->save();
      }
    }

    return $data;

  }

}