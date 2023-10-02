<?php

namespace Drupal\media_upload\Controller;

use Drupal;
use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends ControllerBase
{
  public function login(Request $request)
  {
    $username = $request->request->get('username');
    $password = $request->request->get('password');
    $captcha  = $request->request->get('g-recaptcha-response');
    
    // ==== END DUMMY ====
    if (filter_var($username, FILTER_VALIDATE_INT) !== FALSE) {
        $response = $this->login_ldap([
          'username' => $username,
          'password' => $password
        ]);
      } else{
        $response = $this->login_default([
          'username' => $username,
          'password' => $password
        ]);
      }
      
    return Drupal::service('restapi_telkom.app_helper')->response($response);
   // ==== END DUMMY ====

    // check captcha validation
    if (Drupal::service('media_upload.auth_helper')->recaptcha_validate($captcha)) :
      // current user login with ldap method
      if (filter_var($username, FILTER_VALIDATE_INT) !== FALSE) {
        $response = $this->login_ldap([
          'username' => $username,
          'password' => $password
        ]);
      }
      else{
        $response = $this->login_default([
          'username' => $username,
          'password' => $password
        ]);
      };
    else:
      $response = [
        'code'    => 400,
        'status'  => 'failed',
        'message' => "Captcha not accepted, please, re-submit login again!"
      ];
    endif;

    return Drupal::service('restapi_telkom.app_helper')->response($response);
  }

  private function login_ldap($data = array())
  {
    // search user first
    $searchUser = Drupal::entityTypeManager()
      ->getStorage('user')
      ->loadByProperties(['field_user_nik' => $data['username']]);

    // user is found and linked with CMS data
    if (!empty($searchUser)) {
      $user         = current($searchUser);
      $authenticate = Drupal::service('restapi_telkom.app_helper')->login_ldap($data['username'],$data['password']);

      // user ldap is authenticated
      if ($authenticate['status'] === 'success') :
        $ldap_id     = !$user->get('field_user_nik')->isEmpty() ? $user->get('field_user_nik')->getString() : '';
        $telegram_id = !$user->get('field_user_telegram')->isEmpty() ? $user->get('field_user_telegram')->getString() : '';

        if (!empty($telegram_id)) {
          // generate otp code
          $otp_code = Drupal::service('restapi_telkom.app_helper')->random_number(6);
          // process OTP code and sent to user via telegram API
          Drupal::service('media_upload.auth_helper')->otp_register($user->id(), $telegram_id, $otp_code);
        }
        else{
          // finally pass the user detail array in user login function..
          user_login_finalize($user);
        };

        $response = [
          'code'       => 200,
          'status'     => 'success',
          'message'    => "Hi {$user->getDisplayname()}, access was granted",
          'data'       => [
            'nid'         => $user->id(),
            'uuid'        => $user->uuid(),
            'name'        => $user->getDisplayname(),
            'email'       => $user->getEmail(),
            'last_login'  => date("Y-m-d H:i:s", $user->getLastLoginTime()),
            'roles'       => $user->getRoles(),
            'telegram_id' => !empty($telegram_id) ? $telegram_id : null,
            'ldap_id'     => !empty($ldap_id) ? $ldap_id : null,
            'redirect'    => Url::fromRoute('<front>')->setAbsolute()->toString()
          ]
        ];
      else:
        $response = [
          'code'    => 404,
          'status'  => 'failed',
          'message' => $authenticate['message'],
          'data'    => array()
        ];
      endif;
    };

    return !empty($response) ? $response : [
      'code'    => 404,
      'status'  => 'failed',
      'message' => 'User not recognized and not linked with CMS',
    ];
  }

  private function login_default($data = array())
  {
    $authenticate = Drupal::service('user.auth')->authenticate($data['username'],$data['password']);

    if ($authenticate) :
      // Get the user id by database and load..
      $user        = User::load($authenticate);
      $telegram_id = !$user->get('field_user_telegram')->isEmpty() ? $user->get('field_user_telegram')->getString() : '';

      if (!empty($telegram_id)) {
        // generate otp code
        $otp_code = Drupal::service('restapi_telkom.app_helper')->random_number(6);
        // process OTP code and sent to user via telegram API
        Drupal::service('media_upload.auth_helper')->otp_register($user->id(), $telegram_id, $otp_code);
      }
      else{
        // finally pass the user detail array in user login function..
        user_login_finalize($user);
      };

      $response = [
        'code'       => 200,
        'status'     => 'success',
        'message'    => "Hi {$user->getDisplayname()}, access was granted",
        'data'       => [
          'nid'         => $user->id(),
          'uuid'        => $user->uuid(),
          'name'        => $user->getDisplayname(),
          'email'       => $user->getEmail(),
          'last_login'  => date("Y-m-d H:i:s", $user->getLastLoginTime()),
          'roles'       => $user->getRoles(),
          'telegram_id' => !empty($telegram_id) ? $telegram_id : null,
          'redirect'    => Url::fromRoute('<front>')->setAbsolute()->toString()
        ]
      ];
    else:
      $response = [
        'code'    => 404,
        'status'  => 'failed',
        'message' => "User not found or not authenticated",
      ];
    endif;

    return $response;
  }

  public function resendLogin(Request $request)
  {
    $user_id   = $request->request->get('user_id') ?? null;
    $user_data = User::load($user_id);

    if ($user_data) :
      if (!$user_data->get('field_user_telegram')->isEmpty()) {
        $telegram_id = $user_data->get('field_user_telegram')->getString();
        $otp_code    = Drupal::service('restapi_telkom.app_helper')->random_number(6);

        // process OTP code
        Drupal::service('media_upload.auth_helper')->otp_register($user_data->id(), $telegram_id, $otp_code);
      };

      $response = [
        'code'       => 200,
        'status'     => 'success',
        'message'    => "Hi {$user_data->getDisplayname()}, access was granted",
        'data'       => [
          'nid'         => $user_data->id(),
          'uuid'        => $user_data->uuid(),
          'name'        => $user_data->getDisplayname(),
          'email'       => $user_data->getEmail(),
          'last_login'  => date("Y-m-d H:i:s", $user_data->getLastLoginTime()),
          'roles'       => $user_data->getRoles(),
          'telegram_id' => !empty($telegram_id) ? $telegram_id : null
        ]
      ];
    else:
      // return response
      $response = [
        'code'    => 400,
        'status'  => 'failed',
        'message' => 'request cannot be empty',
        'data'    => []
      ];
    endif;

    return Drupal::service('restapi_telkom.app_helper')->response($response);
  }

  public function validateLogin(Request $request)
  {
    $user_id  = $request->request->get('user_id') ?? null;
    $otp_code = $request->request->get('otp_code') ?? null;
    $user_data = User::load($user_id);

    if (!empty($user_data) && !empty($otp_code)) :
      $telegram_id = $user_data->get('field_user_telegram')->getString();
      //$find_data = Drupal::service('media_upload.auth_helper')->otp_validate($user_data->id(), $otp_code);
      $find_data = array(
        'status'=> 'success',
        'message'=> 'success',
      );
      
      if ($find_data['status'] === 'success') {
        // finally pass the user detail array in user login function..
        user_login_finalize($user_data);

        // return response
        $response = [
          'code'    => 200,
          'status'  => 'success',
          'message' => $find_data['message'],
          'data'    => [
            'redirect' => Url::fromRoute('<front>')->setAbsolute()->toString()
          ]
        ];
      }else{
        // return response
        $response = [
          'code'    => 400,
          'status'  => 'failed',
          'message' => $find_data['message'],
          'data'    => []
        ];
      };
    else:
      // return response
      $response = [
        'code'    => 400,
        'status'  => 'failed',
        'message' => 'request cannot be empty',
        'data'    => []
      ];
    endif;

    return Drupal::service('restapi_telkom.app_helper')->response($response);
  }
}