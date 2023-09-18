<?php
namespace Drupal\custom_cron\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Database\Database;
use Drupal;

class KibanaController {

  private $conn;

  public function __construct()
  {
    $this->conn = Database::getConnection();
  }

  public function submitLanding() {

    //return new JsonResponse('not processed', 200);

    // get queue pages
    $query = $this->conn->select('custom_cron_extract_pages_to_kibana', 'e')
      ->condition('e.status', 0, '=')
      ->condition('e.tried', 10, '<')
      ->fields('e', ['id', 'status','tried', 'node_id'])
      ->range(0,1);

    $dataPages = null;
    foreach ($query->execute()->fetchAll() as $data) {
      if ($data->node_id) {
        $id = $data->node_id;
    
        if ($landing = Drupal::service('custom_cron.kibana_helper')->setLanding($id)) {
          $pages = $landing->getPages();
      
          $url = [
            'regular'   => $landing->getLandingUrl('regular'),
            'subdomain' => $landing->getLandingUrl('subdomain'),
            'domain'    => $landing->getLandingUrl('domain')
          ];
          $url['preview'] = str_replace($_ENV['APP_URL'], $_ENV['APP_URL'].'/preview', $url['regular']);
    
          $dataPages = $landing->preparePages($pages, $url);

          $success = true;
          if ($dataPages === null) {
            // there is a problem
            $success = false;
          }
          else {

            try {
              // submit to kibana
              $this->submitToKibana($dataPages);

            } catch (\Exception $e) {
              Drupal::logger('comprehensive_search')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
              $success = false;
            }

          }

          // update data
          $this->conn->update('custom_cron_extract_pages_to_kibana')
            ->fields([
              'tried'     => $data->tried+1,
              'status'    => $success ? 1 : 0,
              'updated_at'=> date("Y-m-d H:i:s")
            ])
            ->condition('id', $data->id, '=')->execute();
      
        }
      }
    }
      
    return new JsonResponse([
      'status' => (isset($success) && $success) || !isset($success) ? 200 : 400,
      'message'=> (isset($success) && $success) || !isset($success) ? 'success' : 'failed',
    ], 200);

  }

  private function submitToKibana(array $dataPages = []) {

    $indexName = $_ENV['KIBANA_INDEX_NAME'];

    if ( !empty($indexName) && !empty($dataPages) ) {

      $client = (new \OpenSearch\ClientBuilder())
      ->setHosts([$_ENV['KIBANA_URL']])
      ->setBasicAuthentication($_ENV['KIBANA_USERNAME'], $_ENV['KIBANA_PASSWORD']) // For testing only. Don't store credentials in code.
      ->setSSLVerification(false) // For testing only. Use certificate for validation
      ->build();

      $ids = array_column($dataPages, 'id');

      // Search for it
      $result = $client->search([
        'index' => $indexName,
        'body' => [
          'query' => [
            'bool' => [
              'filter' => [
                [
                  'terms' => [
                    '_id' => $ids
                  ]
                ]
              ]
            ]
          ],
          '_source' => false
        ]
      ]);

      $id_exists = array_column($result['hits']['hits'], '_id');

      foreach ($dataPages as $page) {

        if (in_array($page['id'], $id_exists)) { 
          // update
          $client->update([
            'index' => $indexName,
            'id' => $page['id'],
            'body' => [
              'doc' => $page
            ]
          ]);
        }
        else {
          // create
          $client->create([
            'index' => $indexName,
            'id' => $page['id'],
            'body' => $page
          ]);
        }

      }

    }
    else if( empty($indexName) ) {
      throw new \Exception('Not processed, env kibana not configured');
    }
  }

}