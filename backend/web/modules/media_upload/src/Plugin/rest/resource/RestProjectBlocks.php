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

use Drupal\Component\Serialization\Json;

/**
 * Provides a resource to handle rest media upload
 * @RestResource(
 *   id = "custom_rest_project_blocks",
 *   label = @Translation("Custom Rest Project Blocks"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/project/blocks",
 *   }
 * )
 */
class RestProjectBlocks extends ResourceBase {
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
    if (empty($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], 'project') === false)) {
      return new JsonResponse(['data'=> 'kosong']);exit;
    }

    $project_id = preg_replace('/.*project=/is', '', $_SERVER['HTTP_REFERER']);

    require_once BASE_BUILDER_PATH . '/helpers/builder_function.php';
    require_once BASE_BUILDER_PATH . '/helpers/builder_helper.php';
    require_once BASE_BUILDER_PATH . '/helpers/font_awesome_helper.php';

    $componentArray = glob(BASE_BUILDER_PATH . '/blocks/*/data.php');
    $arrayCode = [];

    foreach($componentArray as $key => $item){
      require_once $item;
    }

    $arrayCode = \Drupal::service('media_upload.landing_helper')->retrievePricing($project_id, $arrayCode); //$this->retrievePricing($project_id, $arrayCode);
    
    return new JsonResponse( $arrayCode );
  }

  private function retrievePricing(string $project_id = '', array $arrayCode = array())
  {
    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $project_id]);
    $landing = current($landing);

    $product_catalog = $landing->hasField('field_lan_product_catalog') ? $landing->get('field_lan_product_catalog')->referencedEntities() : [];

    if (!empty($product_catalog)) :
      $package_list = $this->mappingProducts($product_catalog);

      return array_map(function($block) use ($package_list) {
        if (strpos($block['blockID'], 'pricing') === false) return $block;

        $formattedBlock = $block;
        $basePricing = $block['data']['pricing']['value'][0];

        $pricingList = array_map(function($price) use($basePricing, $block) {
          $formatted = $basePricing;

          // format pricing data body
          $formatted['title']['value'] = "<strong>{$price['package_title']}</strong>";
          $formatted['content']['value'] = "<p>{$price['package_tipe_paket']}, {$price['package_service']}, {$price['package_transtype']}</p>";
          $formatted['detail']['value'] = $price['package_detail'];
          $formatted['price_suffix']['value'] = "/ {$price['package_billing_period']}";

          switch ($block['blockID']) {
            case 'pricing-01':
            case 'pricing-02':
            case 'pricing-07':
            case 'pricing-08':
              $formatted['title']['value'] = "<strong>Rp.".number_format($price['package_price_total'])."/Bulan</strong>";
              $formatted['subtitle']['value'] = trim($price['package_flag']);
              $formatted['button']['value']['label'] = "Pilih";
            break;

            case 'pricing-04':
            case 'pricing-05':
              $formatted['price']['value'] = "Rp.".number_format($price['package_price_total']);
              $formatted['button']['value']['label'] = "Pilih";
            break;

            case 'pricing-06':
              $formatted['price']['value'] = number_format($price['package_price_total']);
              $formatted['button']['value']['label'] = "Pilih";
            break;

            case 'pricing-11':
            case 'pricing-09':
              $formatted['content']['value'] = $price['package_subtitle'];
              $formatted['speed']['value'] = round($price['package_speed'] / 1000);
              $formatted['price']['value'] = " ".number_format($price['package_price_total'])." ";
              $formatted['footer_content']['value'] = '<ul>'. implode('', array_map(function($benefitText){
                return "<li>". $benefitText['benefit_package_name'] ."</li>";
              }, $price['benefit_package_setting'])) .'</ul>';
            break;

            case 'pricing-10':
              $formatted['content']['value'] = $price['package_subtitle'];
              $formatted['speed']['value'] = round($price['package_speed'] / 1000);
              $formatted['price']['value'] = "Rp.".number_format($price['package_price_total'])." ";
              $formatted['footer_content']['value'] = '<ul>'. implode('', array_map(function($benefitText){
                return "<li>". $benefitText['benefit_package_name'] ."</li>";
              }, $price['benefit_package_setting'])) .'</ul>';
            break;

            case 'pricing-12':
              $formatted['identifier']['value'] = $price['package_id'];
              $formatted['speed']['value'] = round($price['package_speed'] / 1000).'Mbps';
              $formatted['price']['value'] = "Rp.".number_format($price['package_price_total'])." ";
            break;

            case 'pricing-13':
              $formatted['telp_head']['value'] = "Rp." . number_format($price['package_price_voice']);
              $formatted['telp_body']['value'] = $price['package_transtype'];
              $formatted['tv_body']['value'] = $price['package_service'];
              $formatted['speed']['value'] = round($price['package_speed'] / 1000);
              $formatted['price']['value'] = " ".number_format($price['package_price_total'])." ";
            break;

            case 'pricing-03':
            default:
              $formatted['subtitle']['value'] = "<strong>Rp.".number_format($price['package_price_total'])."/{$price['package_billing_period']}</strong>";
              $formatted['button']['value']['label'] = "Pilih";
            break;
          }

          return $formatted;
        }, $package_list);

        // replace pricing data list based on real time available data
        $formattedBlock['data']['pricing']['value'] = $pricingList;

        return $formattedBlock;
      }, $arrayCode);
    endif;
    
    return $arrayCode;
  }

  private function mappingProducts($product_catalog = array())
  {
    // prepare variable
    $package_list = array();
    $benefit_list = $this->retrieveBenefit();

    // loop product data
    foreach ($product_catalog as $product) {
      // loop mapping package data
      foreach ($product->field_pct_list_paket->referencedEntities() as $package) {
        // mapping benefit product setting if any
        if ($product->hasField('field_pct_setting_temp_pricing') && !$product->get('field_pct_setting_temp_pricing')->isEmpty()) {
          // convert into array from json format
          $benefitList = json_decode($product->get('field_pct_setting_temp_pricing')->getString(),TRUE);

          foreach ($benefitList as $key => $value) :
            if ($value) {
              $benefitSetting[$key] = $benefit_list[$key];
            };
          endforeach;
        };

        // mapping benefit package setting if any
        if ($package->hasField('field_pkt_setting_temp_pricing') && !$package->get('field_pkt_setting_temp_pricing')->isEmpty()) {
          // convert into array from json format
          $benefitList = json_decode($package->get('field_pkt_setting_temp_pricing')->getString(),TRUE);

          foreach ($benefitList as $key => $value) :
            if ($value) {
              $benefitPackage[$key] = array_merge(['benefit_package_name' => $value], $benefit_list[$key]);
            };
          endforeach;
        };

        $package_list[] = [
          'uuid' => $package->uuid(),
          'package_id' => !empty($package->field_pkt_package_id->getValue()) ? current($package->field_pkt_package_id->getValue()[0]) : null,
          'package_title' => $package->label(),
          'package_subtitle' => $package->field_pkt_sub_title->getString(),
          'package_flag' => $package->field_pkt_flag->getString(),
          'package_tipe_paket' => !empty($package->field_pkt_tipe_paket->referencedEntities()) ? $package->field_pkt_tipe_paket->referencedEntities()[0]->label() : '',
          'package_price_internet' => $package->field_pkt_price_internet->getString(),
          'package_price_total' => $package->field_pkt_price_total->getString(),
          'package_price_voice' => $package->field_pkt_price_voice->getString(),
          'package_billing_period' => $package->field_pkt_billing_period->getString(),
          'package_speed' => $package->field_pkt_speed->getString(),
          'package_service' => $package->field_pkt_service->getString(),
          'package_transtype' => $package->field_pkt_trans_type->getString(),
          'package_detail' => $package->field_pkt_package_detail->getString(),
          'product_id' => $product->id(),
          'product_uuid' => $product->uuid(),
          'product_name' => $product->label(),
          'benefit_product_setting' => !empty($benefitSetting) ? $benefitSetting : [],
          'benefit_package_setting' => !empty($benefitPackage) ? $benefitPackage : [],
        ];
      };
    };

    // release memory
    unset($product_catalog, $product, $package, $benefitList, $benefitSetting, $benefitPackage, $benefit_list);

    return $package_list;
  }

  private function retrieveBenefit()
  {
    $benefitData = array();
    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery()
      ->condition('status', 1)
      ->condition('type', 'template_pricing_setting')
      ->execute();

    foreach ($entity->loadMultiple($query) as $key => $benefit) {
      // skip when image is empty
      if (empty($benefit->field_temp_set_logo->getValue()[0])) continue;

      // retrieve image data
      $image_file = File::load($benefit->field_temp_set_logo->getValue()[0]['target_id']);
      $image_uri = $image_file->getFileUri();

      if (str_contains($image_uri, 's3')) {
        $findS3 = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($image_uri, 'original');

        $image_url = $findS3['status'] ? $findS3['data'] : $image_uri;
      }
      else{
        $image_url = \Drupal::request()->getSchemeAndHttpHost() . \Drupal::service('file_url_generator')->generateString($image_uri);
      };

      $benefitData[$benefit->id()] = [
        'benefit_uuid' => $benefit->uuid(),
        'benefit_name' => $benefit->label(),
        'image_id'     => $benefit->field_temp_set_logo->getValue()[0]['target_id'],
        'filename'     => str_replace(' ', '_', $image_file->getFilename()),
        'uri'          => $image_url,
        'thumbnail'    => $image_url,
        'width'        => "{$benefit->field_temp_set_logo->getValue()[0]['width']}px",
        'height'       => "{$benefit->field_temp_set_logo->getValue()[0]['height']}px",
      ];
    }

    unset($entity, $query, $image_file, $image_url, $benefit);

    return $benefitData;
  }

}