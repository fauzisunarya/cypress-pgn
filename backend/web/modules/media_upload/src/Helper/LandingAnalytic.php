<?php

namespace Drupal\media_upload\Helper;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class LandingAnalytic {
  
  public function storeLog(Node $landing, Node $page){

    // create session if not exist
    if ( empty(session_id()) ) {
      session_start(['cookie_secure' => true, 'cookie_httponly' => true]);
    }

    // create cookie if not exist (for project landing page)
    if ( empty($_COOKIE[$landing->uuid()]) ) {

      $uuid = Drupal::service('uuid')->generate();
      setcookie($landing->uuid(), $uuid, array (
        'expires' => time()+100*100*100*100*100, 
        'path' => '/', 
        'domain' => '.'.$_SERVER['HTTP_HOST'], // leading dot for compatibility or use subdomain
        'secure' => true,     // or false
        'httponly' => true,    // or false
        'samesite' => 'Strict' // None || Lax  || Strict
      ));
      $_COOKIE[$landing->uuid()] = $uuid;

      $is_return = 0; // new visitor
    }
    else{
      $is_return = 1; // old visitor
    }

    // create cookie if not exist (for global/all landing page)
    if ( empty($_COOKIE['cms']) ) {

      $uuid = Drupal::service('uuid')->generate();
      setcookie('cms', $uuid, array (
        'expires' => time()+100*100*100*100*100, 
        'path' => '/', 
        'domain' => '.'.$_SERVER['HTTP_HOST'], // leading dot for compatibility or use subdomain
        'secure' => true,     // or false
        'httponly' => true,    // or false
        'samesite' => 'Strict' // None || Lax  || Strict
      ));
      $_COOKIE['cms'] = $uuid;

    }

    // get user
    $user_id = Drupal::currentUser()->id();
    if ( $user_id > 0 ) {
      $user = User::load($user_id);
      $user_email = $user->getEmail();
    }
    else{
      $user_email = '';
    }

    $log = [
      'referer'     => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
      'cookies_landing' => $_COOKIE[$landing->uuid()],
      'cookies_cms' => $_COOKIE['cms'],
      'session'     => session_id(),
      'user_id'     => $user_id > 0 ? $user_id : '',
      'user_email'  => $user_email,
      'ip_address'  => $_SERVER['REMOTE_ADDR'],
      'is_return'   => $is_return,
      'url'         => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
      'path'        => explode('?', $_SERVER['REQUEST_URI'], 2)[0],
      'query_string'=> $_SERVER['QUERY_STRING'],
      'website_id'  => $landing->uuid(),
      'page_id'     => $page->uuid(),
      'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
      'browser_type'=> $this->getBrowser(),
      'os'          => $this->getOS(),
      'platform'    => $_ENV['ANALYTIC_PLATFORM']
    ];

    return $this->sendLog($log);
  }

  public function getLog(Node $landing){
    
    $client = new Client([
      'base_uri' => $_ENV["ANALYTIC_BASE_URL"]
    ]);

    $accessToken   = null;
    try{
      $authResponse  = $client->post('/api/login', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json',
        ],
        'body' => json_encode([
          "email"    => $_ENV['ANALYTIC_USER_EMAIL'],
          "password"     => $_ENV["ANALYTIC_USER_PASSWORD"]
        ])
      ]);

      if ($authResponse->getStatusCode() == 200) {
        $response = json_decode($authResponse->getBody());
        $accessToken = 'Bearer ' . $response->data->token;

        if (!empty($accessToken)) {
          $logResponse  = $client->get('/api/landing/log', [
            'headers' => [
              'Authorization'=> $accessToken,
              'Content-Type' => 'application/json',
              'Accept'       => 'application/json',
            ],
            'query' => [
              'website_id' => $landing->uuid(),
              'platform'   => $_ENV['ANALYTIC_PLATFORM']
            ]
          ]);

          if ($logResponse->getStatusCode() == 200){
            $response = json_decode($logResponse->getBody(), true);

            return $response['data'];
          }
        } 
      }

    }
    catch(ClientException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return false;
    }
    catch(ConnectException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return false;
    }
    catch(\Throwable $e) {
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return false;
    }

    return false;
  }

  public function getBrowser() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
  
    $browser        = "";
    $browser_array  = array(
      '/msie/i'       =>  'Internet Explorer',
      '/firefox/i'    =>  'Firefox',
      '/safari/i'     =>  'Safari',
      '/chrome/i'     =>  'Chrome',
      '/edge/i'       =>  'Edge',
      '/opera/i'      =>  'Opera',
      '/netscape/i'   =>  'Netscape',
      '/maxthon/i'    =>  'Maxthon',
      '/konqueror/i'  =>  'Konqueror',
      '/mobile/i'     =>  'Handheld Browser'
    );
  
    foreach ( $browser_array as $regex => $value ) { 
      if ( preg_match( $regex, $user_agent ) ) {
        $browser = $value;
      }
    }
    return $browser ? $browser : $user_agent;
  }

  public function getOS() { 
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
  
    $os_platform =   "";
    $os_array =   array(
      '/windows nt 10/i'      =>  'Windows 10',
      '/windows nt 6.3/i'     =>  'Windows 8.1',
      '/windows nt 6.2/i'     =>  'Windows 8',
      '/windows nt 6.1/i'     =>  'Windows 7',
      '/windows nt 6.0/i'     =>  'Windows Vista',
      '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
      '/windows nt 5.1/i'     =>  'Windows XP',
      '/windows xp/i'         =>  'Windows XP',
      '/windows nt 5.0/i'     =>  'Windows 2000',
      '/windows me/i'         =>  'Windows ME',
      '/win98/i'              =>  'Windows 98',
      '/win95/i'              =>  'Windows 95',
      '/win16/i'              =>  'Windows 3.11',
      '/macintosh|mac os x/i' =>  'Mac OS X',
      '/mac_powerpc/i'        =>  'Mac OS 9',
      '/linux/i'              =>  'Linux',
      '/ubuntu/i'             =>  'Ubuntu',
      '/iphone/i'             =>  'iPhone',
      '/ipod/i'               =>  'iPod',
      '/ipad/i'               =>  'iPad',
      '/android/i'            =>  'Android',
      '/blackberry/i'         =>  'BlackBerry',
      '/webos/i'              =>  'Mobile'
    );
  
    foreach ( $os_array as $regex => $value ) { 
      if ( preg_match($regex, $user_agent ) ) {
        $os_platform = $value;
      }
    }   
    return $os_platform ? $os_platform : $user_agent;
  }

  private function sendLog($log){

    $client = new Client([
      'base_uri' => $_ENV["ANALYTIC_BASE_URL"]
    ]);

    $accessToken   = null;
    try{
      $authResponse  = $client->post('/api/login', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json',
        ],
        'body' => json_encode([
          "email"    => $_ENV['ANALYTIC_USER_EMAIL'],
          "password"     => $_ENV["ANALYTIC_USER_PASSWORD"]
        ])
      ]);

      if ($authResponse->getStatusCode() == 200) {
        $response = json_decode($authResponse->getBody());
        $accessToken = 'Bearer ' . $response->data->token;

        if (!empty($accessToken)) {
          $logResponse  = $client->post('/api/landing/log', [
            'headers' => [
              'Authorization'=> $accessToken,
              'Content-Type' => 'application/json',
              'Accept'       => 'application/json',
            ],
            'body' => json_encode($log)
          ]);

        } 
      }

    }
    catch(ClientException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
    }
    catch(ConnectException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
    }
    catch(\Exception $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
    }
    catch(\Throwable $e) {
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
  }

  public function topWebVisitor($page=1, $perpage=10, $list_unique_ip=false){
    $client = new Client([
      'base_uri' => $_ENV["ANALYTIC_BASE_URL"]
    ]);

    $accessToken   = null;
    try{
      $authResponse  = $client->post('/api/login', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json',
        ],
        'body' => json_encode([
          "email"    => $_ENV['ANALYTIC_USER_EMAIL'],
          "password"     => $_ENV["ANALYTIC_USER_PASSWORD"]
        ])
      ]);

      if ($authResponse->getStatusCode() == 200) {
        $response = json_decode($authResponse->getBody());
        $accessToken = 'Bearer ' . $response->data->token;

        if (!empty($accessToken)) {

          $payload = [
            "platform"        => $_ENV['ANALYTIC_PLATFORM'],
            "list_unique_ip"  => $list_unique_ip,
            "page"            => $page,
            "perpage"         => $perpage
          ];

          $resultResponse  = $client->post('/api/analytic/topWebVisitor', [
            'headers' => [
              'Authorization'=> $accessToken,
              'Content-Type' => 'application/json',
              'Accept'       => 'application/json',
            ],
            'body' => json_encode($payload)
          ]);
          
          if ($resultResponse->getStatusCode() == 200) {
            $response = json_decode($resultResponse->getBody(), true);

            return $this->processTopWebVisitor($response);
          }

        } 
      }

    }
    catch(ClientException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
    catch(ConnectException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
    catch(\Throwable $e) {
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
  }

  private function processTopWebVisitor($response) {
    return [
      'data' => array_filter( array_map(function($each){

        $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $each['website_id']]);
        $landing = reset($landing);

        if (!$landing) {
          return null;
        }

        $loginPercent = ($each['user_login']/$each['visitor'])*100;
        $nonLoginPercent = ($each['user_not_login']/$each['visitor'])*100;

        $each = array_merge($each, [
          'uuid' => $landing->uuid(),
          'name' => $landing->label(),
          'description'=> $landing->field_lan_website_description->getString(),
          'url'  => \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($landing),
          'user_login' => $each['user_login'] . " (".number_format($loginPercent,2,',','.')."%)" ,
          'user_not_login' => $each['user_not_login'] . " (".number_format($nonLoginPercent,2,',','.')."%)",
          'created_date' => date("Y-m-d H:i:s", $landing->getCreatedTime()),
          'last_update'  => date("Y-m-d H:i:s", $landing->getChangedTime())
        ]);

        unset($landing, $loginPercent, $nonLoginPercent);

        return $each;

      }, $response['data']) ),
      'pagination' => $response['pagination']
    ];
  }

  public function topPageVisitor($page=1, $perpage=10, $list_unique_ip=false){
    $client = new Client([
      'base_uri' => $_ENV["ANALYTIC_BASE_URL"]
    ]);

    $accessToken   = null;
    try{
      $authResponse  = $client->post('/api/login', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json',
        ],
        'body' => json_encode([
          "email"    => $_ENV['ANALYTIC_USER_EMAIL'],
          "password"     => $_ENV["ANALYTIC_USER_PASSWORD"]
        ])
      ]);

      if ($authResponse->getStatusCode() == 200) {
        $response = json_decode($authResponse->getBody());
        $accessToken = 'Bearer ' . $response->data->token;

        if (!empty($accessToken)) {

          $payload = [
            "platform"        => $_ENV['ANALYTIC_PLATFORM'],
            "list_unique_ip"  => $list_unique_ip,
            "page"            => $page,
            "perpage"         => $perpage
          ];

          $resultResponse  = $client->post('/api/analytic/topPageVisitor', [
            'headers' => [
              'Authorization'=> $accessToken,
              'Content-Type' => 'application/json',
              'Accept'       => 'application/json',
            ],
            'body' => json_encode($payload)
          ]);
          
          if ($resultResponse->getStatusCode() == 200) {
            $response = json_decode($resultResponse->getBody(), true);

            return $this->processTopPageVisitor($response);
          }

        } 
      }

    }
    catch(ClientException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
    catch(ConnectException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
    catch(\Throwable $e) {
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
  }

  private function processTopPageVisitor($response){
    return [
      'data' => array_filter( array_map(function($each){

        $page = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $each['page_id']]);
        $page = reset($page);

        if (!$page) {
          return null;
        }

        $landing = Node::load($page->field_page_landing_id->getString());

        $loginPercent = ($each['user_login']/$each['visitor'])*100;
        $nonLoginPercent = ($each['user_not_login']/$each['visitor'])*100;

        $each = array_merge($each, [
          'uuid' => $page->uuid(),
          'url'  => explode('?', $each['url'], 2)[0],
          'landing_page' => $landing->label(),
          'description'=> $page->field_website_page_description->getString(),
          'user_login' => $each['user_login'] . " (".number_format($loginPercent,2,',','.')."%)" ,
          'user_not_login' => $each['user_not_login'] . " (".number_format($nonLoginPercent,2,',','.')."%)",
          'created_date' => date("Y-m-d H:i:s", $landing->getCreatedTime()),
          'last_update'  => date("Y-m-d H:i:s", $landing->getChangedTime())
        ]);

        unset($page, $landing, $loginPercent, $nonLoginPercent);

        return $each;

      }, $response['data']) ),
      'pagination' => $response['pagination'] ?? null
    ];
  }

  /**
   * Get website visit duration
   */
  public function topWebDuration($page=1, $perpage=10){
    $client = new Client([
      'base_uri' => $_ENV["ANALYTIC_BASE_URL"]
    ]);

    $accessToken   = null;
    try{
      $authResponse  = $client->post('/api/login', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json',
        ],
        'body' => json_encode([
          "email"    => $_ENV['ANALYTIC_USER_EMAIL'],
          "password"     => $_ENV["ANALYTIC_USER_PASSWORD"]
        ])
      ]);

      if ($authResponse->getStatusCode() == 200) {
        $response = json_decode($authResponse->getBody());
        $accessToken = 'Bearer ' . $response->data->token;

        if (!empty($accessToken)) {

          $payload = [
            "platform"        => $_ENV['ANALYTIC_PLATFORM'],
            "page"            => $page,
            "perpage"         => $perpage
          ];

          $resultResponse  = $client->post('/api/analytic/topWebDuration', [
            'headers' => [
              'Authorization'=> $accessToken,
              'Content-Type' => 'application/json',
              'Accept'       => 'application/json',
            ],
            'body' => json_encode($payload)
          ]);
          
          if ($resultResponse->getStatusCode() == 200) {
            $response = json_decode($resultResponse->getBody(), true);

            return $this->processTopWebDuration($response);
          }

        } 
      }

    }
    catch(ClientException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
    catch(ConnectException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
    catch(\Throwable $e) {
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
  }

  private function processTopWebDuration($response){
    return [
      'data' => array_filter( array_map(function($each){

        $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid' => $each['website_id']]);
        $landing = reset($landing);

        if (!$landing) {
          return null;
        }

        $each = array_merge($each, [
          'uuid' => $landing->uuid(),
          'name' => $landing->label(),
          'url'  => \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($landing),
          'duration' => $each['duration'] . " {$each['duration_unit']}s",
          'created_date' => date("Y-m-d H:i:s", $landing->getCreatedTime()),
          'last_update'  => date("Y-m-d H:i:s", $landing->getChangedTime())
        ]);

        unset($page, $landing, $loginPercent, $nonLoginPercent);

        return $each;

      }, $response['data']) ),
      'pagination' => $response['pagination']
    ];
  }

  /**
   * Get Specific website visitor with additional data
   */
  public function webVisitor(Node $landing, array $params=[],array $rangeDate=[]){
    $client = new Client([
      'base_uri' => $_ENV["ANALYTIC_BASE_URL"]
    ]);

    $accessToken   = null;
    try{
      $authResponse  = $client->post('/api/login', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Accept'       => 'application/json',
        ],
        'body' => json_encode([
          "email"    => $_ENV['ANALYTIC_USER_EMAIL'],
          "password"     => $_ENV["ANALYTIC_USER_PASSWORD"]
        ])
      ]);

      if ($authResponse->getStatusCode() == 200) {
        $response = json_decode($authResponse->getBody());
        $accessToken = 'Bearer ' . $response->data->token;

        if (!empty($accessToken)) {

          $payload = [
            "platform"        => $_ENV['ANALYTIC_PLATFORM'],
            "website_id"      => $landing->uuid()
          ];

          if (!empty($rangeDate)) {
            $payload['startDate'] = $rangeDate[0];
            $payload['endDate']   = $rangeDate[1];
          }

          $payload = array_merge($payload, $params);

          $resultResponse  = $client->post('/api/analytic/webVisitor', [
            'headers' => [
              'Authorization'=> $accessToken,
              'Content-Type' => 'application/json',
              'Accept'       => 'application/json',
            ],
            'body' => json_encode($payload)
          ]);
          
          if ($resultResponse->getStatusCode() == 200) {
            $response = json_decode($resultResponse->getBody(), true);

            if (empty($response['data'])) {
              // return empty data
              return $response['data'] = [
                'uuid' => $landing->uuid(),
                'name' => $landing->label(),
                'description'=> $landing->field_lan_website_description->getString(),
                'url'  => \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($landing),
                'duration' => '-',
                'unique_visitor' => '-',
                'visitor' => '-',
                'user_login' => '-' ,
                'user_not_login' => '-',
                'created_date' => date("Y-m-d H:i:s", $landing->getCreatedTime()),
                'last_update'  => date("Y-m-d H:i:s", $landing->getChangedTime()),
                'pages' => [
                  'data' => [],
                  'pagination' => null
                ]
              ];
            }

            $loginPercent = ($response['data']['user_login']/$response['data']['visitor'])*100;
            $nonLoginPercent = ($response['data']['user_not_login']/$response['data']['visitor'])*100;            

            $response['data'] = array_merge($response['data'], [
              'uuid' => $landing->uuid(),
              'name' => $landing->label(),
              'description'=> $landing->field_lan_website_description->getString(),
              'url'  => \Drupal::service('media_upload.shortlink_helper')->get_landing_shortlink($landing),
              'duration' => $response['data']['duration'] . " {$response['data']['duration_unit']}s",
              'user_login' => $response['data']['user_login'] . " (".number_format($loginPercent,2,',','.')."%)" ,
              'user_not_login' => $response['data']['user_not_login'] . " (".number_format($nonLoginPercent,2,',','.')."%)",
              'created_date' => date("Y-m-d H:i:s", $landing->getCreatedTime()),
              'last_update'  => date("Y-m-d H:i:s", $landing->getChangedTime())
            ]);

            // process (add additional data)
            $response['data']['pages'] = $this->processTopPageVisitor(['data' => $response['data']['pages']]); 

            if (!empty($response['data']['explored_page'])) {
              // process (add additional data)
              $response['data']['explored_page']['home_page'] = $this->processTopPageVisitor([
                'data'=> $response['data']['explored_page']['home_page']
              ])['data'];

              $response['data']['explored_page']['other_page'] = $this->processTopPageVisitor([
                'data'=> $response['data']['explored_page']['other_page']
              ])['data'];
            }

            return $response['data'];
          }

        } 
      }

    }
    catch(ClientException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
    catch(ConnectException $e){
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
    catch(\Throwable $e) {
      Drupal::logger('cms_analytic')->error("{$e->getMessage()} on line {$e->getLine()} in {$e->getFile()}");
      return null;
    }
  }

}