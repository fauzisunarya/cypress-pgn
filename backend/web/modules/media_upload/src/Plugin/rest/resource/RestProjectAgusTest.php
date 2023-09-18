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

use function GuzzleHttp\json_decode;
use function PHPSTORM_META\type;
use Drupal\Component\Serialization\Json;

/**
 * Provides a resource to handle rest media upload
 * @RestResource(
 *   id = "custom_rest_project_agus_test",
 *   label = @Translation("Custom Rest Project Agus Test"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/project/agus-test",
 *   }
 * )
 */
class RestProjectAgusTest extends ResourceBase {
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
    $data = ["halo"=>"hai"];
    // ob_start();
    // include_once "agus.php";
    // $data = ob_get_contents();
    // ob_end_clean();

    return new JsonResponse( $data );
  }

}
