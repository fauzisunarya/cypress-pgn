<?php
namespace Drupal\custom_cron\Controller;

use Drupal;
use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class EbisController {

  private $conn;

  public function __construct()
  {
    $this->conn = Database::getConnection();
  }

  /**
   * Sync now
   */
  public function sync(){
    Drupal::service('custom_cron.ebis_helper')->sync();
    return new Response('success sync');
  }

}