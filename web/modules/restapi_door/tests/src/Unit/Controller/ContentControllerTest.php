<?php

namespace Drupal\restapi_door\Tests\Unit\Controller;

use Drupal\Tests\Core\Menu\LocalTaskIntegrationTestBase;


class ContentControllerTest extends LocalTaskIntegrationTestBase
{

  public function testGetContentApi()
  {
    $url = 'https://content-management-service.test/apidoor/contents/311758db-375b-4f61-b58f-0e9e19b923a6';

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    // Execute cURL session and get response
    $response = curl_exec($ch);

    // Get HTTP status code
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL session
    curl_close($ch);

    // Assert HTTP status code is 200 (OK)
    $this->assertEquals($code, 0);

    // Parse JSON response
    $content = json_decode($response, true);

    // Assert JSON response is valid and contains expected keys
    $this->assertIsArray($content);
    $this->assertArrayHasKey('id', $content);
    $this->assertArrayHasKey('title', $content);
    $this->assertArrayHasKey('body', $content);
  }
}
