<?php
namespace Drupal\custom_cron\Controller;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class TemplatePageController {

  private $conn;

  public function __construct()
  {
    $this->conn = Database::getConnection();
  }

  /**
   * Run screenshoot template-template page or preview landing page
   */
  public function process(){

    // get queue syncronize
    $query = $this->conn->select('custom_cron_template_page_screenshoot', 'e')
      ->condition('e.status', 0, '=')
      ->condition('e.crawl', 0, '=')
      ->condition('e.tried', 10, '<')
      ->fields('e', ['id', 'node_id', 'crawl', 'status', 'tried']);

    // initiate screenshot queue job
    $queue = \Drupal::service('plugin.manager.queue_worker')->createInstance('screenshot_queue');

    foreach ($query->execute()->fetchAll() as $data) {

      // update data
      $this->conn->update('custom_cron_template_page_screenshoot')
      ->fields([
        'crawl'    => 1,
        'updated_at'=> date("Y-m-d H:i:s")
      ])
      ->condition('id', $data->id, '=')->execute();

      $node = Node::load($data->node_id);

      if ($node && in_array($node->bundle(), ['landing', 'template_page'])) {

          // try process screenshoot
          $success = true;

          try{
            if ($node->bundle() === 'landing') {
              //screenshoot landing page
              $queue->processItem($node);
            }
            else{
              // screenshoot template page
              $queue->templateScreenshoot($node);
            }
          }
          catch (Exception $e) {
            $success = false;
            Drupal::logger('template_page_screenshoot')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
          }

          // update data
          $this->conn->update('custom_cron_template_page_screenshoot')
            ->fields([
              'tried'     => $data->tried+1,
              'crawl'     => 0,
              'status'    => $success ? 1 : 0,
              'updated_at'=> date("Y-m-d H:i:s")
            ])
            ->condition('id', $data->id, '=')->execute();
      
      }
      else{
        $this->conn->update('custom_cron_template_page_screenshoot')
          ->fields([
            'tried'     => $data->tried+1,
            'crawl'    => 0,
            'status'    => 1,
            'updated_at'=> date("Y-m-d H:i:s")
          ])
          ->condition('id', $data->id, '=')->execute();
        continue;
      }
    }

    return new Response('success');

  }

}