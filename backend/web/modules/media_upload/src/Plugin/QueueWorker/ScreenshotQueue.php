<?php

namespace Drupal\media_upload\Plugin\QueueWorker;

use Drupal\file\Entity\File;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Annotation\QueueWorker;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\media_upload\Controller\ScreenshootController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
* Custom Queue Worker.
*
* @QueueWorker(
*   id = "screenshot_queue",
*   title = @Translation("Screenshot Queue"),
*   cron = {"time" = 120}
* )
*/

final class ScreenshotQueue extends QueueWorkerBase implements ContainerFactoryPluginInterface {
	/**
	* The entity type manager.
	*
	* @var \Drupal\Core\Entity\EntityTypeManagerInterface
	*/
	protected $entityTypeManager;

	/**
	* The database connection.
	*
	* @var \Drupal\Core\Database\Connection
	*/
	protected $database;

	/**
	* Main constructor.
	*
	* @param array $configuration
	*   Configuration array.
	* @param mixed $plugin_id
	*   The plugin id.
	* @param mixed $plugin_definition
	*   The plugin definition.
	* @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
	*   The entity type manager.
	* @param \Drupal\Core\Database\Connection $database
	*   The connection to the database.
	*/
	public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, Connection $database) {
		parent::__construct($configuration, $plugin_id, $plugin_definition);
		$this->entityTypeManager = $entity_type_manager;
		$this->database = $database;
	}

	/**
	* Used to grab functionality from the container.
	*
	* @param \Symfony\Component\DependencyInjection\ContainerInterface $container
	*   The container.
	* @param array $configuration
	*   Configuration array.
	* @param mixed $plugin_id
	*   The plugin id.
	* @param mixed $plugin_definition
	*   The plugin definition.
	*
	* @return static
	*/
	public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
		return new static(
			$configuration,
			$plugin_id,
			$plugin_definition,
			$container->get('entity_type.manager'),
			$container->get('database'),
		);
	}

	/**
	* Processes an item in the queue.
	*
	* @param mixed $data
	*   The queue item data.
	*
	* @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
	* @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
	* @throws \Drupal\Core\Entity\EntityStorageException
	* @throws \Exception
	*/
	public function processItem($landing_data) {

		if (!empty($landing_data) && $landing_data->bundle() === 'landing') :
			// init library
			$screenshot = new ScreenshootController();

      		// run screenshot library
	      	$result = $screenshot->run(
	        	$_ENV['APP_URL'] .'/preview/'. $landing_data->field_lan_website_full->getString(),
	        	$_ENV['SERVER_ROOT'] . '/files/'.date("ymdhis").rand(10,1000).'.jpg'
	      	);

	      	// release memory
	      	unset($screenshot);

	      	// check if user want to update landing logo / favicon
		    if (!empty($result) && file_exists($result['data']['location'])) {
		        // prepare variable
		        $minioHelper = \Drupal::service('restapi_telkom.minio_helper');
		        $existFile   = !$landing_data->field_lan_website_preview->isEmpty() ? $minioHelper->getFileByURI($landing_data->field_lan_website_preview->getString(), '', 'info') : null;

		        // create mock uploaded file
		        $mockRequest = new UploadedFile(
		          $result['data']['location'],
		          pathinfo($result['data']['location'])['basename'],
		          mime_content_type($result['data']['location'])
		        );

		        // screenshot file is exist
		        if (!empty($existFile) && $existFile['status']) {
		          $uploaded = $minioHelper->replaceFile($existFile['data']['fid'], $mockRequest);
		        }
		        // upload a new one
		        else{
		          $uploaded = $minioHelper->uploadFile($mockRequest, $landing_data->id());
		        };

		        // release memory
		        unset($minioHelper, $mockRequest, $existFile);

		        // remove raw screenshot image
		        @unlink($result['data']['location']);
		    };

      		$landing_data->set('field_lan_website_preview', !empty($uploaded['status']) ? $uploaded['data']['uuid'] : '');
      		$landing_data->setNewRevision(TRUE);
      		$landing_data->revision_log = 'Revision to update landing preview for ' . $landing_data->id();
      		$landing_data->setRevisionCreationTime(REQUEST_TIME);
      		$landing_data->save();
	    endif;
	}

  /**
	* Processes an item in the queue.
	*
	* @param mixed $data
	*   The queue item data.
	*
	* @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
	* @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
	* @throws \Drupal\Core\Entity\EntityStorageException
	* @throws \Exception
	*/
	public function templateScreenshoot($template_page) {

		if (!empty($template_page) && $template_page->bundle() === 'template_page') :
			// init library
			$screenshot = new ScreenshootController();

      // run screenshot library
      $result = $screenshot->run(
        $_ENV['APP_URL'] .'/preview/templatepage/'. $template_page->id(),
        $_ENV['SERVER_ROOT'] . '/files/'.date("ymdhis").rand(10,1000).'.jpg'
      );

      // release memory
      unset($screenshot);

      // check if user want to update landing logo / favicon
      if (!empty($result) && file_exists($result['data']['location'])) {
        // prepare variable
        $minioHelper = \Drupal::service('restapi_telkom.minio_helper');
        $existFile = null;
        if (!$template_page->field_tem_page_image_link->isEmpty()) {
          $existFileUrl = $template_page->field_tem_page_image_link->getString();
          if (str_contains($existFileUrl, '/media_render/')) {
            $existFileUuid = explode('/media_render/', $existFileUrl)[1];
            $existFile = $minioHelper->getFileByURI($existFileUuid, '', 'info');
          }
        }

        // create mock uploaded file
        $mockRequest = new UploadedFile(
          $result['data']['location'],
          pathinfo($result['data']['location'])['basename'],
          mime_content_type($result['data']['location'])
        );

        // screenshot file is exist
        if (!empty($existFile) && $existFile['status']) {
          $uploaded = $minioHelper->replaceFile($existFile['data']['fid'], $mockRequest);
        }
        // upload a new one
        else{
          $uploaded = $minioHelper->uploadFile($mockRequest, $template_page->id());
        };

        // release memory
        unset($minioHelper, $mockRequest, $existFile);

        // remove raw screenshot image
        @unlink($result['data']['location']);
      };

      // save home page as template preview
      if ($template_page->field_tem_page_type->getString() == 1) {
        $template = $template_page->field_tem_page_template_id->referencedEntities()[0];
        if ($template) {
          $template->set('field_tem_image_link', !empty($uploaded['status']) ? "{$_ENV['APP_URL']}/restapi/v1/media_render/{$uploaded['data']['uuid']}" : '');
          $template->setNewRevision(TRUE);
          $template->revision_log = 'Revision to update preview for ' . $template->id();
          $template->setRevisionCreationTime(REQUEST_TIME);
          $template->save();
        }
      }

      $template_page->set('field_tem_page_image_link', !empty($uploaded['status']) ? "{$_ENV['APP_URL']}/restapi/v1/media_render/{$uploaded['data']['uuid']}" : '');
      $template_page->setNewRevision(TRUE);
      $template_page->revision_log = 'Revision to update preview for ' . $template_page->id();
      $template_page->setRevisionCreationTime(REQUEST_TIME);
      $template_page->save();
    endif;
	}

}