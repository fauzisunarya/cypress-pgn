<?php

namespace Drupal\restapi_telkom\Helper;

use GuzzleHttp\Client;
use Drupal\Core\Database\Database;

class Project_helper {

  public function response(array $data = [], int $status_code = 200, array $headers = array())
  {
    $response = new \Symfony\Component\HttpFoundation\JsonResponse();
    
    $response->headers->set('Content-Type', 'application/json');
    
    if (!empty($headers)) :
      foreach ($headers as $key => $value) {
        $response->headers->set($key, $value);
      };
    endif;

    $response->setStatusCode($status_code);
    $response->setData($data);

    return $response;
  }

  public function getLoggedinUser()
  {
    // get current request params
    $request = \Drupal::request();

    // cancel process
    if (!$request->headers->has('Authorization')) return false;

    /** @var \Drupal\Core\Database\Connection $connection */
    $connection = \Drupal::service('database');
    $token      = str_replace('Bearer ', '', $request->headers->get('Authorization'));

    // retrieve token data based on supplied token string
    $result = $connection->select('auth_tokens', 'atn')
      ->fields('atn', ['id', 'user_id', 'token', 'expired_at'])
      ->condition('atn.token', $token)
      ->execute()
      ->fetchObject();

    if (!empty($result) && $result->user_id) {
      // retrieve user data
      $user = \Drupal\user\Entity\User::load($result->user_id);

      return [
        'nid'         => (int) $user->id(),
        'uuid'        => $user->uuid(),
        'name'        => $user->getDisplayname(),
        'email'       => $user->getEmail(),
        'last_login'  => date("Y-m-d H:i:s", $user->getLastLoginTime()),
        'roles'       => $user->getRoles(),
        'telegram_id' => !$user->get('field_user_telegram')->isEmpty() ? $user->get('field_user_telegram')->getString() : null
      ];
    };

    return null;
  }

  public function sendEmail(string $email_address = '', string $subject = '', array $body = array())
  {
    $client = new Client([
      'base_uri' => $_ENV["APIGW_BASE_URL"]
    ]);

    $accessToken   = null;
    $emailResponse = null;
    $authResponse  = $client->post('invoke/pub.apigateway.oauth2/getAccessToken', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
      'body' => json_encode([
        "grant_type"    => "client_credentials",
        "client_id"     => $_ENV["APIGW_CLIENT_ID"],
        "client_secret" => $_ENV["APIGW_CLIENT_SECRET"]
      ])
    ]);

    // get token
    if ($authResponse->getStatusCode() == 200) :
      $response = json_decode($authResponse->getBody());
      $accessToken = $response->token_type .' '. $response->access_token;

      if (!empty($accessToken)) {
        $emailResponse = $client->post('gateway/telkom-notifplatform/1.0/apiNotif/api/send-email', [
          'headers' => [
            'Authorization' => $accessToken,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
          ],
          'body' => json_encode([
            'apiRequest' => [
              'eaiHeader' => ["externalId" => "", "timestamp" => ""],
              'eaiBody'   => [
                'data' => [
                  "template" => "NOTIFY_CMS_01",
                  "tujuan" => $email_address,
                  "param" => array_merge(["subject" => $subject], $body)
                ]
              ]
            ]
          ])
        ]);
      };
    endif;

    if (!empty($accessToken) && $emailResponse->getStatusCode() == 200) {
      return ['status' => 'success', 'message' => 'success to sent email @ ' . $email_address];
    }else{
      return ['status' => 'failed', 'message' => 'failed to sent email'];
    }
  }

  public function sendEmailV2(array $email_address = [], string $subject = '', array $body = array())
  {
    // if (str_contains($_ENV['APP_URL'],'local')) {
    //   return ['status' => 'failed', 'message' => 'not sending in local environtment'];
    // }

    if (count($email_address)===0) {
      return ['status' => 'failed', 'message' => 'empty email'];
    }

    $conn = Database::getConnection();
    foreach (array_unique(array_filter($email_address, fn($val)=>!empty($val))) as $email) {
      $conn->insert('custom_cron_email')
            ->fields([
              'email' => $email,
              'subject' => $subject,
              'body' => $body['body'],
              'status' => 0,
              'response' => null,
              'tried' => 0,
              'created_at' => date("Y-m-d H:i:s"),
            ])
            ->execute();
    }

    return ['status' => 'success', 'message' => 'email has been added into cron table'];

    $client = new Client([
      'base_uri' => $_ENV["APIGW_BASE_URL"]
    ]);

    $accessToken   = null;
    $authToken = null;
    $emailResponse = [];

    // get access to apigw
    $authResponse  = $client->post('invoke/pub.apigateway.oauth2/getAccessToken', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
      'body' => json_encode([
        "grant_type"    => "client_credentials",
        "client_id"     => $_ENV["APIGW_CLIENT_ID"],
        "client_secret" => $_ENV["APIGW_CLIENT_SECRET"]
      ])
    ]);

    // get access token
    if ($authResponse->getStatusCode() == 200) :
      $response = json_decode($authResponse->getBody());
      $accessToken = $response->token_type .' '. $response->access_token;

      // get auth token
      if (!empty($accessToken)) {
        $tokenResponse = $client->post('gateway/telkom-notifplatform/1.0/auth/get-token', [
          'headers' => [
            'Authorization' => $accessToken,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
          ],
          'body' => json_encode([
            'authRequest' => [
              'eaiHeader' => [
                "externalId" => "",
                "timestamp" => ""
              ],
              'eaiBody' => [
                "guid" => "459865480tr632v2",
                "data" => "Hello I'am CMSDMP and today is ".date('d/m/Y')."."
              ]
            ]
          ])
        ]);

        if ($tokenResponse->getStatusCode() == 200) {
          $response = json_decode($tokenResponse->getBody());

          if ($response->authResponse->eaiStatus->srcResponseCode == 0) {
            $authToken = $response->authResponse->eaiBody->guid;

            if (!empty($authToken)) {
              // send email to user
              foreach ($email_address as $email) {
                if (empty($email)) {
                  continue;
                }
  
                $response = $client->post('gateway/telkom-notifplatform/1.0/apiNotif/api/send-email/v2', [
                  'headers' => [
                    'Authorization' => $accessToken,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                  ],
                  'body' => json_encode([
                    'apiRequest' => [
                      'eaiHeader' => [
                        'externalId' => '',
                        'timestamp' =>''
                      ],
                      'eaiBody' => [
                        'guid' => $authToken,
                        'data' => [
                          'template' => 'NOTIFY_CMS_01',
                          'tujuan' => $email,
                          'param' => array_merge(["subject" => $subject], $body),
                          'profile_name' => "CMS DMP notify mailer"
                        ]
                      ]
                    ]  
                  ])
                ]);

                if ($response->getStatusCode() == 200) {
                  $emailResponse[$email] = ['status' => 'success', 'message' => 'success to sent email @ ' . $email];
                }
                else{
                  $emailResponse[$email] = ['status' => 'failed', 'message' => 'failed to sent email'];
                }

              }
            }
          }
        }

      };
    endif;

    return $emailResponse;
  }

  public function sendTelegram(int $type = 0, $userId = null, $body = '')
  {
    $client = new Client([
      'base_uri' => $_ENV["APIGW_BASE_URL"]
    ]);

    $accessToken   = null;
    $emailResponse = null;
    $authResponse  = $client->post('invoke/pub.apigateway.oauth2/getAccessToken', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
      'body' => json_encode([
        "grant_type"    => "client_credentials",
        "client_id"     => $_ENV["APIGW_CLIENT_ID"],
        "client_secret" => $_ENV["APIGW_CLIENT_SECRET"]
      ])
    ]);

    // get token
    if ($authResponse->getStatusCode() == 200) :
      $response = json_decode($authResponse->getBody());
      $accessToken = $response->token_type .' '. $response->access_token;

      if (!empty($accessToken)) {
        switch ($type) {
          case '2':
            $sentRes = array(
              "template_code" => "cms_dmp_otp_01",
              "subject" => $userId,
              "param" => ["otp" => $body]
            );
          break;

          case '1':
          default:
            $sentRes = array(
              "template_code" => "NOTIFY_TELE_CMS_01",
              "subject" => $userId,
              "param" => ["text" => $body]
            );
          break;
        };
        $telegramResponse = $client->post('gateway/telkom-notifplatform/1.0/apiNotif/api/send-telegram', [
          'headers' => [
            'Authorization' => $accessToken,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
          ],
          'body' => json_encode([
            'apiRequest' => [
              'eaiHeader' => ["externalId" => "", "timestamp" => ""],
              'eaiBody'   => ["data" => $sentRes]
            ]
          ])
        ]);
      };
    endif;

    if (!empty($accessToken) && $telegramResponse->getStatusCode() == 200) {
      return ['status' => 'success', 'message' => 'success to sent message @ ' . $userId];
    }else{
      return ['status' => 'failed', 'message' => 'failed to sent message'];
    }
  }

  public function sendTelegramV2(int $type = 0, $userId = null, $body = '')
  {
    $client = new Client([
      'base_uri' => $_ENV["APIGW_BASE_URL"]
    ]);

    $accessToken   = null;
    $authToken = null;
    $telegramResponse = null;

    // get access to apigw
    $authResponse  = $client->post('invoke/pub.apigateway.oauth2/getAccessToken', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
      'body' => json_encode([
        "grant_type"    => "client_credentials",
        "client_id"     => $_ENV["APIGW_CLIENT_ID"],
        "client_secret" => $_ENV["APIGW_CLIENT_SECRET"]
      ])
    ]);

    // get access token
    if ($authResponse->getStatusCode() == 200) :
      $response = json_decode($authResponse->getBody());
      $accessToken = $response->token_type .' '. $response->access_token;

      // get auth token
      if (!empty($accessToken)) {
        $tokenResponse = $client->post('gateway/telkom-notifplatform/1.0/auth/get-token', [
          'headers' => [
            'Authorization' => $accessToken,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
          ],
          'body' => json_encode([
            'authRequest' => [
              'eaiHeader' => [
                "externalId" => "",
                "timestamp" => ""
              ],
              'eaiBody' => [
                "guid" => "459865480tr632v2",
                "data" => "Hello I'am CMSDMP and today is ".date('d/m/Y')."."
              ]
            ]
          ])
        ]);

        if ($tokenResponse->getStatusCode() == 200) {
          $response = json_decode($tokenResponse->getBody());

          if ($response->authResponse->eaiStatus->srcResponseCode == 0) {
            $authToken = $response->authResponse->eaiBody->guid;

            if (!empty($authToken)) {
              // send to telegram
              switch ($type) {
                case '2':
                  $sentRes = array(
                    "template_code" => "cms_dmp_otp_01",
                    "subject" => $userId,
                    "param" => ["otp" => $body]
                  );
                break;
      
                case '1':
                default:
                  $sentRes = array(
                    "template_code" => "NOTIFY_TELE_CMS_01",
                    "subject" => $userId,
                    "param" => ["text" => $body]
                  );
                break;
              };

              $telegramResponse = $client->post('gateway/telkom-notifplatform/1.0/apiNotif/api/send-telegram/v2', [
                'headers' => [
                  'Authorization' => $accessToken,
                  'Content-Type'  => 'application/json',
                  'Accept'        => 'application/json',
                ],
                'body' => json_encode([
                  'apiRequest' => [
                    'eaiHeader' => [
                      'externalId' => '',
                      'timestamp' =>''
                    ],
                    'eaiBody' => [
                      'guid' => $authToken,
                      'data' => $sentRes
                    ]
                  ]  
                ])
              ]);
            }
          }
        }

      };
    endif;

    if (!empty($accessToken) && $telegramResponse!==null && $telegramResponse->getStatusCode() == 200) {
      return ['status' => 'success', 'message' => 'success to sent message @ ' . $userId];
    }else{
      return ['status' => 'failed', 'message' => 'failed to sent message'];
    }
  }

  public function login_ldap(string $username, string $password)
  {
    if (empty($username) OR empty($password)) return false;

    $client = new Client([
      'base_uri' => $_ENV["APIGW_BASE_URL"]
    ]);

    $accessToken   = null;
    $emailResponse = null;
    $authResponse  = $client->post('invoke/pub.apigateway.oauth2/getAccessToken', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
      'body' => json_encode([
        "grant_type"    => "client_credentials",
        "client_id"     => $_ENV["APIGW_CLIENT_ID"],
        "client_secret" => $_ENV["APIGW_CLIENT_SECRET"]
      ])
    ]);

    // get token
    if ($authResponse->getStatusCode() == 200) :
      $response = json_decode($authResponse->getBody());
      $accessToken = $response->token_type .' '. $response->access_token;

      if (!empty($accessToken)) {
        try {
          $ldap_login = $client->post('gateway/telkom-auth/1.0/authValidate', [
            'headers' => [
              'Authorization' => $accessToken,
              'Content-Type'  => 'application/json',
              'Accept'        => 'application/json',
            ],
            'body' => json_encode([
              "username" => $username,
              "password" => $password
            ])
          ]);

          if ($ldap_login->getStatusCode() == 200) :
            $result = json_decode($ldap_login->getBody());
            return (!empty($result->status) && $result->status !== 'fail') ? 
              ['status' => 'success', 'message' => $result->note, 'data' => $result]: 
              ['status' => 'failed', 'message' => $result->note, 'data' => $result];
          else:
            return ['status' => 'failed', 'message' => 'failed no make a request', 'data' => array()];
          endif;
        } 
        catch (\Exception $e) {
          return ['status' => 'failed', 'message' => $e->getMessage()];
        }
      };
    endif;

    return ['status' => 'failed', 'message' => 'failed to login using ldap method, either access token was not granted, or error has occured in the server'];
  }

  public function token_generate()
  {
    $raw_text = bin2hex(openssl_random_pseudo_bytes(16)) . $this->random_number(10) . date("Ymdhis");

    return md5($raw_text);
  }

  public function page_type(int $page_type)
  {
    switch ($page_type) {
      case 1:
        return 'home page';
      break;

      case 2:
        return 'landing page';
      break;

      case 3:
        return 'post index';
      break;

      case 4:
        return 'post single';
      break;

      case 5:
        return 'catalog index';
      break;

      case 6:
        return 'catalog single';
      break;
      
      case 0:
      default:
        return 'normal page';
      break;
    }
  }

  public function convertSpeedTelkom($text_speed)
  {
    $speed = (int) preg_replace("/\D/", '', $text_speed);
    return $speed>1000 ? $speed/1000 : $speed;
  }

  public function convertPriceTelkom($text_price)
  {
    $price = (int) preg_replace("/\D/", '', $text_price);
    return number_format($price, 0, ',', '.');
  }

  public function isValidUuid( $uuid )
  {
    if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
      return false;
    }
    return true;
  }

  public function random_number(int $digits)
  {
    $min = pow(10, $digits - 1);
    $max = pow(10, $digits) - 1;
    return mt_rand($min, $max);
  }

  public function isJson(string $str)
  {
    $json = json_decode($str);
    return $json && $str !== $json;
  }

}