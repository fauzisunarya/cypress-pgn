<?php

namespace Drupal\restapi_telkom\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestingController extends ControllerBase{

   public function testing()
   {
      $data = [
         'status' => 'success',
         'message' => \Drupal::service('restapi_telkom.app_helper')->random_number(10)
      ];
      
      return \Drupal::service('restapi_telkom.app_helper')->response($data);
   }

}