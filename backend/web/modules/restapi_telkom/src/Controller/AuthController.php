<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends ControllerBase
{
  private $connection;

  public function __construct()
  {
    $this->connection = Database::getConnection();
  }

  public function login(Request $request)
  {
    $username = $request->request->get('username');
    $password = $request->request->get('password');

    $authenticate = Drupal::service('user.auth')->authenticate($username,$password);

    if ($authenticate) {
      // Get the user id by database and load..
      $user = User::load($authenticate);

      // generate data to insert into database based on current loggedin user
      $insert_data = [
        'user_id'    => $user->id(),
        'token'      => Drupal::service('restapi_telkom.app_helper')->token_generate(),
        'expired_at' => date('Y-m-d H:i:s', strtotime('+2 day')),
        'created_at' => date('Y-m-d H:i:s'),
      ];

      // register login token into database
      $this->connection->insert('auth_tokens')->fields($insert_data)->execute();

      $response = [
        'code'       => 200,
        'status'     => 'success',
        'message'    => "Hi {$user->getDisplayname()}, access was granted",
        'token'      => $insert_data['token'], 
        'token_type' => 'Bearer',
        'expired_at' => $insert_data['expired_at'],
        'user'       => [
          'nid'   => $user->id(),
          'uuid'  => $user->uuid(),
          'name'  => $user->getDisplayname(),
          'email' => $user->getEmail(),
          'last_login' => date("Y-m-d H:i:s", $user->getLastLoginTime()),
          'roles' => $user->getRoles()
        ]
      ];
    }else{
      $response = [
        'code'    => 404,
        'status'  => 'failed',
        'message' => "User not found or not authenticated",
      ];
    }

    return Drupal::service('restapi_telkom.app_helper')->response($response);
  }

  public function logout(Request $request)
  {
    $token = str_replace('Bearer ', '', $request->headers->get('authorization'));

    $this->connection->delete('auth_tokens')->condition('token', $token)->execute();

    return Drupal::service('restapi_telkom.app_helper')->response([
      'status' => 'success',
      'message' => 'You have successfully logged out'
    ]);    
  }

}