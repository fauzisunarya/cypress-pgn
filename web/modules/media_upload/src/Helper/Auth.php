<?php

namespace Drupal\media_upload\Helper;

use Drupal;
use DateTime;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation;
use Drupal\Core\Routing\TrustedRedirectResponse;

class Auth {
  
  public function otp_register($user_id = null, $telegram_id = null, $otp_code = 0)
  {
    /** @var \Drupal\Core\Database\Connection $connection */
    $connection = Drupal::service('database');
    $result     = $connection->insert('telegram_tokens')
      ->fields([
        'token'      => $otp_code,
        'user_nid'   => $user_id,
        'is_used'    => 0,
        'expired_at' => date("Y-m-d H:i:s", strtotime('+1 minutes')),
        'created_at' => date("Y-m-d H:i:s"),
      ])
      ->execute();

    if ($result) {
      $telegramStatus = Drupal::service('restapi_telkom.app_helper')->sendTelegramV2(2, $telegram_id, $otp_code);

      return [
        'status'  => 'success',
        'message' => 'success regiter otp data',
        'data'    => $telegramStatus
      ];
    }
    else{
      return [
        'status'  => 'failed',
        'message' => 'failed regiter otp data',
        'data'    => []
      ];
    }
  }

  public function otp_validate($user_id = 0, $code = 0)
  {
    /** @var \Drupal\Core\Database\Connection $connection */
    $connection = Drupal::service('database');
    $result = $connection->select('telegram_tokens', 'tel')
      ->fields('tel', ['id', 'token', 'is_used', 'expired_at'])
      ->condition('tel.user_nid', $user_id)
      ->condition('tel.token', $code)
      ->condition('tel.is_used', 0)
      ->execute();

    if ($result):
      $otp_data = $result->fetchObject();
      $now      = new DateTime("now");
      $expired  = new DateTime($otp_data->expired_at);

      if ( $expired > $now ) {
        $updated = $connection->update('telegram_tokens')
          ->fields(['is_used' => 1])
          ->condition('id', $otp_data->id)
          ->execute();
      }
      else{
        $deleted = $connection->delete('telegram_tokens')
          ->condition('id', $otp_data->id)
          ->execute();
      };

      return [
        'status'  => !empty($updated) ? 'success' : 'failed',
        'message' => !empty($updated) ? 'selected otp code is validated and access was granted' : 'your otp code is expired, please resend a new one',
        'data' => $otp_data
      ];
    else:
      return [
        'status'  => 'failed',
        'message' => 'failed to find otp code',
        'data'    => []
      ];
    endif;
  }

  public function recaptcha_validate($string)
  {
    $client = new Client([
      'base_uri' => "https://www.google.com/"
    ]);

    $response  = $client->post('recaptcha/api/siteverify', [
      'form_params' => [
        'secret' => getenv('RECAPTCHA_SECRETKEY'),
        'response' => $string,
        'remoteip' => Drupal::request()->getClientIp()
      ]
    ]);

    if ($response->getStatusCode() == 200) :
      $body = json_decode($response->getBody());
      
      if (!empty($body) && $body->success) {
        return true;
      }
      else{
        return false;
      };
    else:
      return false;
    endif;
  }
}