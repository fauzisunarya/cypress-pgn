<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;

class AnalyticController extends ControllerBase{

  public function landing(Request $request)
  {
    $landing_uuid = $request->request->get('landing_uuid');
    $parameter = $request->request->get('parameter');
    $startDate = $request->request->get('startDate');
    $endDate = $request->request->get('endDate');

    // validation
    if (empty($landing_uuid)) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'landing_uuid is required',
        'data'    => []
      ], 400);
    }
    else if (empty($startDate) && empty($endDate)) {
      // do nothing
    }
    else if (
      (!empty($startDate) && !empty($endDate)) ||
      (empty($startDate) || empty($endDate))
    ){
      if (!preg_match("/^\d\d\d\d-\d\d-\d\d$/", $startDate) || !preg_match("/^\d\d\d\d-\d\d-\d\d$/", $endDate)) {
        return \Drupal::service('restapi_telkom.app_helper')->response([
          'status'  => 'failed',
          'message' => 'startDate and endDate are required to filter by date. The format must be YYYY-mm-dd',
          'data'    => []
        ], 400);
      }

      $rangeDate = [$startDate, $endDate];
    }

    // get landing
    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $landing_uuid]);
    $landing = reset($landing);

    if (!$landing) {
      return \Drupal::service('restapi_telkom.app_helper')->response([
        'status'  => 'failed',
        'message' => 'invalid landing_uuid',
        'data'    => []
      ], 400);
    }

    // process parameter
    if (!empty($parameter) && is_array($parameter)) {
      $params = [];
      $params['list_unique_ip'] = isset($parameter['list_unique_ip']) ? (bool) $parameter['list_unique_ip'] : true;
      $params['query_string']   = !empty($parameter['query_string']) && is_array($parameter['query_string']) ? $parameter['query_string'] : [] ;

      // get page explored if exist
      if (!empty($parameter['explored_page'])) {
        $entity = Drupal::entityTypeManager()->getStorage('node');
        $query = $entity->getQuery();
        $ids = $query->condition('status', 1)
          ->condition('type', 'landing_page')
          ->condition('field_page_landing_id', $landing->id())
          ->condition('field_page_type', 1) //get the homepage
          ->execute();

        $id = reset($ids);

        if (!empty($id)) {
          $homepage = Node::load($id);
          if ($homepage) {
            $params['explored_page'] = [
              'first_page_id' => $homepage->uuid()
            ];
          }
        }
      }
    }

    $rangeDate = $rangeDate ?? [];

    $webVisitor = Drupal::service('media_upload.landing_analytic')->webVisitor($landing, $params, $rangeDate);

    $data = !empty($webVisitor) ? $this->processLandingLog($webVisitor, $landing, $params) : ['website'=>[], 'pages'=>[]];

    return Drupal::service('restapi_telkom.app_helper')->response([
      'status'  => 'success',
      'message' => 'success to retrieve data',
      'data'    => [
        'landing_detail' => [
          'uuid' => $landing->uuid(),
          'name' => $landing->label(),
          'description'=> $landing->field_lan_website_description->getString(),
          'url'  => \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($landing),
          'created_date' => date("Y-m-d H:i:s", $landing->getCreatedTime()),
          'last_update'  => date("Y-m-d H:i:s", $landing->getChangedTime())
        ],
        'analytic' => $data
      ]
    ]);

  }

  /**
   * process raw data log analytic, then group by pages
   */
  private function processLandingLog($webVisitor, Node $landing, array $params=[]){

    $entity = \Drupal::entityTypeManager()->getStorage('node');
    $query = $entity->getQuery();
    $ids = $query->condition('status', 1)
      ->condition('type', 'landing_page')#type = bundle id (machine name)
      ->condition('field_page_landing_id', $landing->id())
      ->execute();

    // default data for each page ( visitor = 0 )
    $default_pages = [];
    foreach ($entity->loadMultiple($ids) as $page) {
      $default_pages[$page->uuid()] = [
        'uuid' => $page->uuid(),
        'title'=> $page->label(),
        'description'=> $page->field_website_page_description->getString(),
        'visitor' => 0,
        'unique_visitor' => 0,
        'user_login' => 0,
        'user_not_login' => 0
      ];

      if (!empty($params['list_unique_ip'])) {
        $default_pages[$page->uuid()]['ip_address'] = [];
      }
    }

    // replace pages visitor data with analytic data
    $pages = $default_pages;
    foreach ($webVisitor['pages']['data'] as $page) {
      // replace data
      $pages[$page['page_id']] = array_merge($pages[$page['page_id']], [
        'visitor' => $page['visitor'],
        'unique_visitor' => $page['unique_visitor'],
        'user_login' => $page['user_login'],
        'user_not_login' => $page['user_not_login']
      ]);

      if (!empty($params['list_unique_ip'])) {
        $pages[$page['page_id']]['ip_address'] = $page['ip_address'];
      }
    }

    // define return data
    $return = [
      'website' => [
        'uuid' => $landing->uuid(),
        'visitor' => $webVisitor['visitor'] !== '-' ? $webVisitor['visitor'] : 0,
        'unique_visitor' => $webVisitor['unique_visitor'] !== '-' ? $webVisitor['unique_visitor'] : 0,
        'user_login' => $webVisitor['user_login'] !== '-' ? $webVisitor['user_login'] : 0,
        'user_not_login' => $webVisitor['user_not_login'] !== '-' ? $webVisitor['user_not_login'] : 0,
        'duration' => $webVisitor['duration'] !== '-' ? $webVisitor['duration'] : 0,
      ],
      'pages' => array_values($pages)
    ];

    if (!empty($webVisitor['ip_address'])) {
      $return['website']['ip_address'] = $webVisitor['ip_address'];
    }

    if (!empty($webVisitor['explored_page'])) {
      if (!empty($webVisitor['explored_page']['home_page'])) {

        $webVisitor['explored_page']['home_page'] = array_map(function($page) use($default_pages, $params){
          $default = $default_pages[$page['page_id']];
          $data = [
            'uuid' => $default['uuid'],
            'title'=> $default['title'],
            'description'=> $default['description'],
            'visitor' => $page['visitor'],
            'unique_visitor' => $page['unique_visitor'],
            'user_login' => $page['user_login'],
            'user_not_login' => $page['user_not_login']
          ];
          if (!empty($params['list_unique_ip'])) {
            $data['ip_address'] = $page['ip_address'];
          }
          return $data;
        }, $webVisitor['explored_page']['home_page']);

        $webVisitor['explored_page']['other_page'] = array_map(function($page) use($default_pages, $params){
          $default = $default_pages[$page['page_id']];
          $data = [
            'uuid' => $default['uuid'],
            'title'=> $default['title'],
            'description'=> $default['description'],
            'visitor' => $page['visitor'],
            'unique_visitor' => $page['unique_visitor'],
            'user_login' => $page['user_login'],
            'user_not_login' => $page['user_not_login']
          ];
          if (!empty($params['list_unique_ip'])) {
            $data['ip_address'] = $page['ip_address'];
          }
          return $data;
        }, $webVisitor['explored_page']['other_page']);
      }
      $return['explored_page'] = $webVisitor['explored_page'];
    }
    
    return $return;
  }

}