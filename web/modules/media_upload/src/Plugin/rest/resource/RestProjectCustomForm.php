<?php

namespace Drupal\media_upload\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\node\Entity\Node;
use Psr\Log\LoggerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Provides a resource to handle rest project custom form
 * @RestResource(
 *   id = "rest_project_custom_form",
 *   label = @Translation("Rest Project Custom Form"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/project/custom-form",
 *     "create" = "/api/v1/project/custom-form"
 *   }
 * )
 */
class RestProjectCustomForm extends ResourceBase {
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

  public function post($data){
    // Content type "landing" = project, "landing_page" = the pages for the landing page project
    if (empty($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], 'project=') === false)) {
      return new JsonResponse(['error'=> 'HTTP_REFERER error'], 422);exit;
    }

    $project_uuid = preg_replace('/.*project=/is', '', $_SERVER['HTTP_REFERER']);
    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $project_uuid]);
    $landing = reset($landing);
    if (!$landing) {
      return new JsonResponse('invalid project landing page', 422);
    }

    $landing_id = $landing->id();
    unset($landing);

    // for update
    if (!empty($data['form_id'])) {
      $custom_form = \Drupal::entityTypeManager()->getStorage('node')->load($data['form_id']);

      if (!$custom_form) {
        return new JsonResponse('invalid form id', 422);
      }
      else if($custom_form->type->entity->get('type')!=='landing_custom_form'){
        return new JsonResponse('invalid form id', 422);
      }

      $custom_form->title = $data['form_name'];
      $custom_form->field_lcf_form_scheme = $data['form_scheme'];
    }
    // for create
    else{
      $custom_form = Node::create([
        'type'        => 'landing_custom_form',
        'title' => $data['form_name'], 
        'field_lcf_form_scheme' => $data['form_scheme'],
        'field_lcf_landing_ref'       => [
          [
            'target_id' => $landing_id,
          ]
        ],
      ]);
    }
    $custom_form->save();

    return new JsonResponse([
      'status' => true,
      'data' => $custom_form->id()
    ]);
  }

}