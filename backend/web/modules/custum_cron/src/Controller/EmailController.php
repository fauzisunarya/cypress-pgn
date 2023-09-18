<?php
namespace Drupal\custom_cron\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\workflow\Entity\WorkflowTransition;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Drupal\Core\Database\Database;

class EmailController {

  private $conn;

  public function __construct()
  {
    $this->conn = Database::getConnection();
  }

  public function send_email(){

    // get queue email
    $query = $this->conn->select('custom_cron_email', 'e')
      ->condition('e.status', 0, '=')
      ->condition('e.tried', 10, '<')
      ->fields('e', ['id', 'email','subject', 'body', 'status', 'response', 'tried', 'created_at'])
      ->orderBy('created_at', 'ASC');

    $data = $query->execute()->fetchAll();

    if (!empty($data)) {

      $client = new Client([
        'base_uri' => $_ENV["APIGW_BASE_URL"]
      ]);
  
      $accessToken   = null;
      $authToken = null;
  
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
  
            }
          }
  
        };
      endif;
  
      // send the email
      if (!empty($authToken) && !empty($accessToken)) {
        foreach ($data as $value) {
          $this->conn->update('custom_cron_email')->fields(['tried'=> $value->tried+1])->condition('id', $value->id, '=')->execute();
    
          $emailResponse = null;
          $email = $value->email;
          if(!empty($email)) {
  
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
                      'param' => [
                        'subject' => $value->subject,
                        'body' => $value->body
                      ],
                      'profile_name' => "CMS DMP notify mailer"
                    ]
                  ]
                ]  
              ])
            ]);
  
            $body_response = json_decode($response->getBody());
  
            if ($response->getStatusCode() == 200 && $body_response->authResponse->eaiStatus->srcResponseCode == 0) {
              $emailResponse = ['status' => 'success', 'message' => 'success to sent email @ ' . $email];
              $success = true;
            }
            else if($response->getStatusCode() == 200 && $body_response->authResponse->eaiStatus->srcResponseCode != 0){
              $emailResponse = ['failed' => 'success', 'message' => 'failed to send email : ' . $body_response->authResponse->eaiStatus->srcResponseMsg];
              $success = false;
            }
            else{
              $emailResponse = ['status' => 'failed', 'message' => 'something error '. $response->getStatusCode()];
              $success = false;
            }
  
            if ($success) {
              $this->conn->update('custom_cron_email')->fields(['status'=> 1, 'response'=> json_encode($emailResponse)])->condition('id', $value->id, '=')->execute();
            }
            else{
              $this->conn->update('custom_cron_email')->fields(['response'=> json_encode($emailResponse)])->condition('id', $value->id, '=')->execute();
            }
          }
        }
      }
    }

    return new JsonResponse('finished');
  }

}