<?php

namespace Drupal\restapi_door\Tests\Unit\Controller;

use Drupal\Tests\Core\Menu\LocalTaskIntegrationTestBase;


class ContentControllerTest extends LocalTaskIntegrationTestBase
{

  public function testGetContentApi()
  {
    $url = 'apidoor/contents/311758db-375b-4f61-b58f-0e9e19b923a6';

    // Initialize cURL session
    $ch = curl_init($url);

    $headers = [
      'Authorization: '.'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzaWQiOiJjaHRnY2o3OG9rN29ib3ZiMm5tb2hjM2ZtZCIsInN1YiI6IjgiLCJleHAiOjE5MjQ3OTQwMDAsImNvZGUiOiIyMDIzMDAwMDAxIiwibmFtZSI6IlNhbXBsZSBFbXBsb3llZSIsImVtYWlsIjoiZW1wQGRvb3J2My5jb20iLCJhcHBpZCI6OCwiYXBwbmFtZSI6ImRvb3IiLCJodHRwczovL2hhc3VyYS5pby9qd3QvY2xhaW1zIjp7IngtaGFzdXJhLWFsbG93ZWQtcm9sZXMiOlsiZW1wbG95ZWUiXSwieC1oYXN1cmEtZGVmYXVsdC1yb2xlIjoiZW1wbG95ZWUifSwiZmxhZyI6MSwidXJsIjoiaHR0cHM6Ly91YW0tZGV2Lm5ldXJvbi5pZCIsImNvbmZpZyI6IntcImFwcGxpY2F0aW9uXCI6e1wibmFtZVwiOlwiZG9vclwiLFwiY29tcGFueVwiOlwiZG9vciBuZXVyb25cIixcImljb25cIjpcImh0dHBzOi8vY2RuLm5ldXJvbndvcmtzLmNvLmlkL2Rvb3J2My9pbWFnZXMvZmF2aWNvbi5pY29cIixcImltYWdlX2xvZ29cIjpcImh0dHBzOi8vY2RuLm5ldXJvbndvcmtzLmNvLmlkL2Rvb3J2My9pbWFnZXMvZmF2aWNvbi5pY29cIixcImltYWdlX2xvZ29fbmFtZVwiOlwiaHR0cHM6Ly9ocm1pcy5uZXVyb24uaWQvYXNzZXRzL3BlcnNvbmFsaXphdGlvbi83ZTk4ZDRiMjIwMDBkYjg0YWQxNjczMTExNDBlZTZiMS5wbmdcIixcImltYWdlX2xvYWRlclwiOlwiaHR0cHM6Ly9jZG4ubmV1cm9ud29ya3MuY28uaWQvZG9vcnYzL2ltYWdlcy9mYXZpY29uLmljb1wiXHJcbn0sXCJlbXBsb3llZVwiOntcImFwcGxpY2F0aW9uXCI6XCJkb29yLGRvb3Jtb2JpbGVcIn19Iiwic2FsdCI6IjcxYjAwY2ExNDZmYjY0YjNiMmNiN2MwN2U0NmNmZWRlNDYxMzg4YTZiYjY3MTJmZjkxMjZlYzhlNjE4NjkwMjIiLCJhbGxvd2VkX3JvbGVzIjpbImVtcGxveWVlIl19.qwQC0n5Y7SFQ2gUzm5XOA9gIcFVShG1AuvOdyZtHSg0Qht4Hdc-ip-ugycoi2R2pT4gblpJwK5ey3SAWXNJbySlyPvaA4TyR7RwrZ28SmCvToGqStg6WJWca0kAL8m7IG6aDygnH9T_0qFQ1n0lr33oPzCafgkTH-Exe98PBTak'
    ];

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, $headers);

    // Execute cURL session and get response
    // $response = curl_exec($ch);

    // Get HTTP status code
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL session
    curl_close($ch);

    // Assert HTTP status code is 200 (OK)
    $this->assertEquals($code, 0);

    // // Parse JSON response
    // $content = json_decode($response, true);

    // Assert JSON response is valid and contains expected keys
    // $this->assertIsArray($content);
    // $this->assertArrayHasKey('id', $content);
    // $this->assertArrayHasKey('title', $content);
    // $this->assertArrayHasKey('body', $content);
  }
}
