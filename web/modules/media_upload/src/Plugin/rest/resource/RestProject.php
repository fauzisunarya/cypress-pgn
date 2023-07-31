<?php

namespace Drupal\media_upload\Plugin\rest\resource;

use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\rest\ModifiedResourceResponse;
use Psr\Log\LoggerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use Drupal;
use function GuzzleHttp\json_decode;
use function PHPSTORM_META\type;
use Drupal\Component\Serialization\Json;

/**
 * Provides a resource to handle rest media upload
 * @RestResource(
 *   id = "custom_rest_project",
 *   label = @Translation("Custom Rest Project"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/project",
 *     "create" = "/api/v1/project"
 *   }
 * )
 */
class RestProject extends ResourceBase {
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
   * Returns a list of media
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   * Throws exception expected.
   */
  public function get() {
    // ob_start();
    // include_once "block_templates.php";
    // $data = ob_get_contents();
    // ob_end_clean();

    return new ResourceResponse();
  }

  public function post($data){
    // Content type "landing" = project, "landing_page" = the pages for the landing page project
    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $data['site_uuid']]);
    $landing = reset($landing);
    $landing_uuid = $landing->uuid();

    $check_access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ( ! $check_access['access'] ) {
      return new JsonResponse('Not allowed, not your landing', 403);
    }

    // there is "template" & "landing" project that use the same page builder for editing page
    // if "template", process as template
    if ($landing->type->entity->get('type')==='template') {
      return $this->save_template_data($landing, $data);
    }

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $entity->getQuery()->condition('status', 1)
      ->condition('type', 'landing_page')#type = bundle id (machine name)
      ->condition('field_page_landing_id', $landing->id()) //list page for landing page
      ->accessCheck(false)
      ->execute();

    $homepage_id = null;
    foreach ($entity->loadMultiple($ids) as $page_id => $page_obj) {
      if ( (int)$page_obj->field_page_type->getString() === 1 ) {
        // 1 = homepage, for detail: see content type "landing_page" field_page_type description
        $homepage_id = $page_id;
      }
    }

    // set the homepage based on homepage_id
    foreach ($data['menus'] as $key => $menu) {
      if( (int)$menu['id']===$homepage_id ){
        $data['menus'][$key]['homepage'] = true;
      }
      else{
        $data['menus'][$key]['homepage'] = false;
      }
    }

    // release memory
    unset($entity, $ids, $homepage_id);

    // update landing page info
    $landing->title = $data['name'];
    $landing->field_lan_website_logo = $data['logo'];
    $landing->field_lan_website_full = $data['url'];
    $landing->field_lan_website_menu = json_encode($data['menus']);
    $landing->field_lan_website_style = json_encode($data['style']);
    $landing->field_lan_website_meta = json_encode($data['meta']);
    $landing->field_shortlink = \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($landing);
    // Make this change a new revision
    $landing->setNewRevision(TRUE);
    $landing->revision_log = 'Created revision for node ' . $landing->id();
    $landing->setRevisionCreationTime(REQUEST_TIME);
    $landing->setRevisionUserId($this->loggedUser->id());
    $landing->save();

    foreach ($data['pages'] as $key => $page) {
      $node_storage = \Drupal::entityTypeManager()->getStorage('node');
      $node_page = $node_storage->load($page['id']);

      // update pages data
      $node_page->field_website_page_label = $page['label'];
      $node_page->title = $page['title'];
      $node_page->field_website_page_description = $page['description'];
      $node_page->field_website_page_slug = $page['slug'];
      $node_page->field_website_page_component = json_encode($page['blocks']);
      $node_page->field_page_personalization = json_encode($page['personalization']);
      $node_page->setNewRevision(TRUE);
      $node_page->revision_log = 'Created revision for node ' . $node_page->id();
      $node_page->setRevisionCreationTime(REQUEST_TIME);
      $node_page->setRevisionUserId($this->loggedUser->id());
      $node_page->save();
    }

    // store queue screenshoot (use existing system "template screenshoot")
    Drupal::service('custom_cron.app_helper')->store_queue_template_screenshoot($landing->id());

    // release memory
    unset($landing, $node_storage, $node_page);

    return new ModifiedResourceResponse($data['pages']);
  }

  /**
   * Save template project
   * @param object $template node
   */
  public function save_template_data($template, $data){

    // update template info
    $template->title = $data['name'];
    $template->field_tem_website_menu = json_encode($data['menus']);
    $template->field_tem_website_style = json_encode($data['style']);
    $template->field_tem_website_meta = json_encode($data['meta']);

    // Make this change a new revision
    $template->setNewRevision(TRUE);
    $template->revision_log = 'Created revision for node ' . $template->id();
    $template->setRevisionCreationTime(REQUEST_TIME);
    $template->setRevisionUserId($this->loggedUser->id());
    $template->save();

    foreach ($data['pages'] as $key => $page) {
      $node_storage = \Drupal::entityTypeManager()->getStorage('node');
      $node_page = $node_storage->load($page['id']);

      // update template pages data
      $node_page->field_tem_page_label = $page['label'];
      $node_page->title = $page['title'];
      $node_page->field_tem_page_description = $page['description'];
      $node_page->field_tem_page_slug = $page['slug'];
      $node_page->field_tem_page_blocks = json_encode($page['blocks']);
      $node_page->field_tem_page_personalization = json_encode($page['personalization']);
      $node_page->setNewRevision(TRUE);
      $node_page->revision_log = 'Created revision for node ' . $node_page->id();
      $node_page->setRevisionCreationTime(REQUEST_TIME);
      $node_page->setRevisionUserId($this->loggedUser->id());
      $node_page->save();

      Drupal::service('custom_cron.app_helper')->store_queue_template_screenshoot($node_page->id());
    }

    return new ResourceResponse($data['pages']);
    
  }

}