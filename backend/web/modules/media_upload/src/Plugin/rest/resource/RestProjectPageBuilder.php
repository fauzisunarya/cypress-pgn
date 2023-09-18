<?php

namespace Drupal\media_upload\Plugin\rest\resource;

use Drupal;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

use function GuzzleHttp\json_decode;
use function PHPSTORM_META\type;
use Drupal\Component\Serialization\Json;

/**
 * Provides a resource to handle rest media upload
 * @RestResource(
 *   id = "custom_rest_project_page_builder",
 *   label = @Translation("Custom Rest Project Page Builder"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/project/{uuid}",
 *   }
 * )
 */
class RestProjectPageBuilder extends ResourceBase {
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
   * Responds to GET Landing page request.
   * Returns a JSON, containing landing page with their page
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   * Throws exception expected.
   */
  public function get($uuid) {
    // current user id
    $user_id = $this->loggedUser->id();


    // Content type "landing" = project, "landing_page" = the pages for the landing page project
    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $uuid]);
    $landing = reset($landing);

    $check_access = \Drupal::service('media_upload.landing_helper')->checkBuilderAccess($landing);
    if ( ! $check_access['access'] ) {
      return new JsonResponse('Not allowed, not your landing', 403);
    }

    // there is "template" & "landing" project that use the same page builder for editing page
    // if "template", process as template
    if ($landing->type->entity->get('type')==='template') {

      $template = $landing;

      return $this->load_template_data($template);
    }

    // get the page logo
    if (!$landing->field_lan_website_logo->isEmpty()) :
      $logo = $landing->field_lan_website_logo->getString();

      if (str_contains($logo, 's3')) {
        $findS3 = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($logo, 'thumbnail');

        $logo = $findS3['status'] ? $findS3['data'] : $logo;
      };
    endif;

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    $ids = $query->condition('status', 1)
      ->condition('type', 'landing_page')#type = bundle id (machine name)
      ->condition('field_page_landing_id', $landing->id()) //list page for landing page
      #->sort('created', 'ASC') #sorted
      #->pager(15) #limit 15 items
      ->execute();

    $pages_arrobj = $entity->loadMultiple($ids);

    // process data
    $status = $landing->status->getString() ? 'publish' : 'unpublish';
    $homepage_id = [];
    $pages = [];
    foreach ($pages_arrobj as $page_id => $page_obj) {
      if ( $page_obj->field_page_type->getString() === "1" ) { // 1 = homepage, for detail: see content type "landing_page" field_page_type description
        $homepage_id = $page_id;
      }
      $blocks_data = $page_obj->field_website_page_component->getString();
      $blocks_data = empty($blocks_data) ? array() : json_decode($blocks_data, true);
      $blocks_data = array_map(function($val){
        if (isset($val['personalization'])) {
          unset($val['personalization']); // now is not used, is exist, remove it
        }
        return $val;
      }, $blocks_data);

      $personalization = $page_obj->field_page_personalization->getString();
      if (empty($personalization)) {
        $personalization = [ 'user_tag' => [], 'rules' => [] ];
      }
      else{
        $personalization = json_decode($personalization, true);
        $personalization = !isset($personalization['user_tag']) || !isset($personalization['rules']) ? [ 'user_tag' => [], 'rules' => [] ] : $personalization;
      }

      array_push($pages, array(
        'id' => $page_id,
        'label' => $page_obj->field_website_page_label->getString(),
        'title' => $page_obj->title->getString(),
        'description' => $page_obj->field_website_page_description->getString(),
        'slug' => $page_obj->field_website_page_slug->getString(),
        'blocks' => $blocks_data,
        'personalization' => $personalization,
      ));
    }

    $style = $landing->field_lan_website_style->getString();
    $style = empty($style) ? array() : json_decode($style, true);

    $meta = $landing->field_lan_website_meta->getString();
    $meta = empty($meta) ? array() : json_decode($meta, true);

    $menu = $landing->field_lan_website_menu->getString();
    $menu = empty($menu) ? array() : json_decode($menu, true);

    // set homepage for menu list
    if(!empty($menu)){
      foreach ($menu as $key => $menu_item) { 
        if ( (int)$menu[$key]['id'] === (int)$homepage_id ) {
          $menu[$key]['homepage'] = true;
        }
        else{
          $menu[$key]['homepage'] = false;
        }
        $menu[$key]['id'] = (int)$menu[$key]['id'];
      }
    }

    // show saved templates for the landing page, display in property user_templates.
    // see \Drupal\media_upload\Controller\SavedTemplatesController::get
    //  Changed: SHOW GLOBALLY (ignore field_st_paket_ref), saved templates can use in another landing & template content type
    $user_templates = $this->get_landing_saved_templates($check_access);

    // show saved blocks, saved blocks can use in another landing & template content type
    $user_blocks = $this->get_landing_saved_blocks($check_access);

    $landing_url = getenv('APP_URL')."/".media_upload_generate_landingpage_url($landing, true);

    // custom form
    $custom_form = $this->get_landing_custom_form($landing->id());

    // default form
    $default_form = $this->get_default_form($landing);

    $personalization_category = Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'personalization_category']);
    $data = [];
    foreach ($personalization_category as $item) {
      $data[] = [
        "label" => $item->get("title")->getString(),
        "options" => array_map( function($val){
          return $val['value'];
        },$item->field_pec_list->getValue()),
      ];
    }

    // create variable to return json
    $return_data = [
      'site_uuid' => $uuid,
      'name' => $landing->title->getString(),
      'logo' => !empty($logo) ? $logo : get_builder_path('favicon.png'),
      'url' => $landing_url,
      'status' => $status,
      'quota' => 999,
      'homepage' => $homepage_id,
      'post_index' => "", //gatau
      'post_single' => "", //gatau
      'catalog_index' => "", //gatau
      'catalog_single' => "", //gatau
      'pages' => $pages,
      'menus' => $menu,
      'style' => $style,
      'meta' => $meta, // gatau "meta" or "custom meta" ?
      'user_blocks' => $user_blocks,
      'user_templates' =>  $user_templates,
      'forms' => [...$default_form, ...$custom_form],
      'posts' => [], //gatau
      'post_category' => [], //gatau
      'catalogs' => [], //gatau
      'catalog_category' => [], //gatau
      'menu_category' => [], //gatau
      'personalization' => $data,
    ];

    return new JsonResponse($return_data);
  }

  /**
   * Load template project
   * @param object $template node
   */
  public function load_template_data($template){

    // get list pages for the template
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    $ids = $query->condition('status', 1)
      ->condition('type', 'template_page')#type = bundle id (machine name)
      ->condition('field_tem_page_template_id', $template->id()) //list page for landing page
      #->sort('created', 'ASC') #sorted
      #->pager(15) #limit 15 items
      ->execute();

    $pages_arrobj = $entity->loadMultiple($ids);

    // process data
    $homepage_id = [];
    $pages = [];
    foreach ($pages_arrobj as $page_id => $page_obj) {
      if ( $page_obj->field_tem_page_type->getString() === "1" ) { // 1 = homepage, for detail: see content type "landing_page" field_page_type description
        $homepage_id = $page_id;
      }
      $blocks_data = $page_obj->field_tem_page_blocks->getString();
      $blocks_data = empty($blocks_data) ? array() : json_decode($blocks_data, true);
      $blocks_data = array_map(function($val){
        if (isset($val['personalization'])) {
          unset($val['personalization']); // now is not used, is exist, remove it
        }
        return $val;
      }, $blocks_data);
      
      $personalization = $page_obj->field_tem_page_personalization->getString();
      if (empty($personalization)) {
        $personalization = [ 'user_tag' => [], 'rules' => [] ];
      }
      else{
        $personalization = json_decode($personalization, true);
        $personalization = !isset($personalization['user_tag']) || !isset($personalization['rules']) ? [ 'user_tag' => [], 'rules' => [] ] : $personalization;
      }

      array_push($pages, array(
        'id' => $page_id,
        'label' => $page_obj->field_tem_page_label->getString(),
        'title' => $page_obj->title->getString(),
        'description' => $page_obj->field_tem_page_description->getString(),
        'slug' => $page_obj->field_tem_page_slug->getString(),
        'blocks' => $blocks_data,
        'personalization' => $personalization,
      ));
    }

    // saved templates can use globally in all landing & all template content type
    $user_templates = $this->get_landing_saved_templates();

    // saved blocks can use globally in all landing & all template content type
    $user_blocks = $this->get_landing_saved_blocks();

    $personalization_category = Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'personalization_category']);
    $data = [];
    foreach ($personalization_category as $item) {
      $data[] = [
        "label" => $item->get("title")->getString(),
        "options" => array_map( function($val){
          return $val['value'];
        },$item->field_pec_list->getValue()),
      ];
    }

    $menu = $template->field_tem_website_menu->getString();
    $menu = empty($menu) ? array() : json_decode($menu, true);

    $style = $template->field_tem_website_style->getString();
    $style = empty($style) ? array() : json_decode($style, true);

    $meta = $template->field_tem_website_meta->getString();
    $meta = empty($meta) ? array() : json_decode($meta, true);

    // create variable to return json
    $return_data = [
      'site_uuid' => $template->uuid(),
      'name' => $template->title->getString(),
      'quota' => 999,
      'homepage' => $homepage_id,
      'post_index' => "", //just blank, it's just a template (only have template name and pages)
      'post_single' => "", //just blank, it's just a template (only have template name and pages)
      'catalog_index' => "", //just blank, it's just a template (only have template name and pages)
      'catalog_single' => "", //just blank, it's just a template (only have template name and pages)
      'pages' => $pages,
      'menus' => $menu,
      'style' => $style,
      'meta' => $meta,
      'user_blocks' => $user_blocks,
      'user_templates' =>  $user_templates,
      'forms' => [], //just blank, it's just a template (only have template name and pages)
      'posts' => [], //just blank, it's just a template (only have template name and pages)
      'post_category' => [], //just blank, it's just a template (only have template name and pages)
      'catalogs' => [], //just blank, it's just a template (only have template name and pages)
      'catalog_category' => [], //just blank, it's just a template (only have template name and pages)
      'menu_category' => [], //just blank, it's just a template (only have template name and pages)
      'personalization' => $data,
    ];

    return new JsonResponse($return_data);
  }

  /**
   * Get landing page saved templates, can use globally in all landing & all template content type
   * @return array $saved_templates
   */
  public function get_landing_saved_templates(array $access = ['access'=> false, 'access_all'=> false, 'user_id' => 0]){
    $user_templates = [];

    // load the saved templates for the landing page
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    if ($access['access'] && $access['access_all']) {
      $ids = $query->condition('status', 1)
              ->condition('type', 'landing_saved_templates')#type = bundle id (machine name)
              ->notExists('field_st_owner')
              // ->condition('field_st_paket_ref', $landing->id()) //list saved templates for landing page
              ->execute();
    }
    else if($access['access']){
      // access own templates
      $ids = $query->condition('status', 1)
              ->condition('type', 'landing_saved_templates')#type = bundle id (machine name)
              ->condition('field_st_owner', $access['user_id'])
              // ->condition('field_st_paket_ref', $landing->id()) //list saved templates for landing page
              ->execute();
    }
    else {
      $ids = [];
    }

    $saved_templates = $entity->loadMultiple($ids);
    foreach ($saved_templates as $template_id => $saved_template) {
      $user_templates[] = [
        'id' => $saved_template->id(),
        'name' => $saved_template->title->getString(),
        'thumbnail' => $saved_template->field_st_thumbnail->getString(),
        'blocks' => json_decode($saved_template->field_st_blocks->getString(), true),
      ];
    }

    return $user_templates;
  }

  /**
   * Get Landing page saved blocks, can use globally in all landing & all template content type
   * @return array $saved_blocks
   */
  public function get_landing_saved_blocks(array $access = ['access'=> false, 'access_all'=> false, 'user_id' => 0]){
    $user_blocks = [];

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    if ($access['access'] && $access['access_all']) {
      // access all saved blocks
      $ids = $query->condition('status', 1)
              ->condition('type', 'landing_saved_blocks')#type = bundle id (machine name)
              ->notExists('field_sb_owner')
              ->execute();
    }
    else if($access['access']){
      // access own saved blocks
      $ids = $query->condition('status', 1)
              ->condition('type', 'landing_saved_blocks')#type = bundle id (machine name)
              ->condition('field_sb_owner', $access['user_id'])
              ->execute();
    }
    else {
      $ids = [];
    }

    $saved_blocks = $entity->loadMultiple($ids);
    foreach ($saved_blocks as $block_id => $saved_block) {
      $user_blocks[] = [
        'id' => $saved_block->id(),
        'name' => $saved_block->title->getString(),
        'blocks' => json_decode($saved_block->field_sb_blocks->getString(), true),
      ];
    }
    return $user_blocks;
  }

  /**
   * Get Landing custom form
   */
  function get_landing_custom_form($landing_id){
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();

    $ids = $query->condition('status', 1)
      ->condition('type', 'landing_custom_form')#type = bundle id (machine name)
      ->condition('field_lcf_landing_ref', $landing_id) //list saved templates for landing page
      ->execute();
    
    $arr_form = [];
    foreach ($entity->loadMultiple($ids) as $custom_form) {
      $arr_form[] = [
        'form_id' => $custom_form->id(),
        'form_name' => $custom_form->title->getString(),
        'form_scheme' => $custom_form->field_lcf_form_scheme->getString()
      ];
    }
    
    return $arr_form;
  }

  /**
   * Get default form for telkom cms
   */
  function get_default_form($landing){
    $landing_type = !empty($landing->field_lan_landing_type->getString()) ? $landing->field_lan_landing_type->referencedEntities()[0]->label() : '';
    return \Drupal::service('media_upload.landingform_helper')->get_default_forms('', $landing_type);
  }
}