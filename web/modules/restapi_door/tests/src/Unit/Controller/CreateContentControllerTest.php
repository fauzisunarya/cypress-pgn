<?php

namespace Drupal\restapi_door\Tests\Unit\Controller;

use Drupal\Tests\Core\Menu\LocalTaskIntegrationTestBase;
use Drupal\restapi_door\Controller\ContentController; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpClient\HttpClient; 


class CreateContentControllerTest extends LocalTaskIntegrationTestBase
{

    public function testCreateContentApi()
    {
        $url = 'https://content-management-service.test/apidoor/contents/create';
 
        $headers = [ 
          'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzaWQiOiJjaHRnY2o3OG9rN29ib3ZiMm5tb2hjM2ZtZCIsInN1YiI6IjgiLCJleHAiOjE5MjQ3OTQwMDAsImNvZGUiOiIyMDIzMDAwMDAxIiwibmFtZSI6IlNhbXBsZSBFbXBsb3llZSIsImVtYWlsIjoiZW1wQGRvb3J2My5jb20iLCJhcHBpZCI6OCwiYXBwbmFtZSI6ImRvb3IiLCJodHRwczovL2hhc3VyYS5pby9qd3QvY2xhaW1zIjp7IngtaGFzdXJhLWFsbG93ZWQtcm9sZXMiOlsiZW1wbG95ZWUiXSwieC1oYXN1cmEtZGVmYXVsdC1yb2xlIjoiZW1wbG95ZWUifSwiZmxhZyI6MSwidXJsIjoiaHR0cHM6Ly91YW0tZGV2Lm5ldXJvbi5pZCIsImNvbmZpZyI6IntcImFwcGxpY2F0aW9uXCI6e1wibmFtZVwiOlwiZG9vclwiLFwiY29tcGFueVwiOlwiZG9vciBuZXVyb25cIixcImljb25cIjpcImh0dHBzOi8vY2RuLm5ldXJvbndvcmtzLmNvLmlkL2Rvb3J2My9pbWFnZXMvZmF2aWNvbi5pY29cIixcImltYWdlX2xvZ29cIjpcImh0dHBzOi8vY2RuLm5ldXJvbndvcmtzLmNvLmlkL2Rvb3J2My9pbWFnZXMvZmF2aWNvbi5pY29cIixcImltYWdlX2xvZ29fbmFtZVwiOlwiaHR0cHM6Ly9ocm1pcy5uZXVyb24uaWQvYXNzZXRzL3BlcnNvbmFsaXphdGlvbi83ZTk4ZDRiMjIwMDBkYjg0YWQxNjczMTExNDBlZTZiMS5wbmdcIixcImltYWdlX2xvYWRlclwiOlwiaHR0cHM6Ly9jZG4ubmV1cm9ud29ya3MuY28uaWQvZG9vcnYzL2ltYWdlcy9mYXZpY29uLmljb1wiXHJcbn0sXCJlbXBsb3llZVwiOntcImFwcGxpY2F0aW9uXCI6XCJkb29yLGRvb3Jtb2JpbGVcIn19Iiwic2FsdCI6IjcxYjAwY2ExNDZmYjY0YjNiMmNiN2MwN2U0NmNmZWRlNDYxMzg4YTZiYjY3MTJmZjkxMjZlYzhlNjE4NjkwMjIiLCJhbGxvd2VkX3JvbGVzIjpbImVtcGxveWVlIl19.qwQC0n5Y7SFQ2gUzm5XOA9gIcFVShG1AuvOdyZtHSg0Qht4Hdc-ip-ugycoi2R2pT4gblpJwK5ey3SAWXNJbySlyPvaA4TyR7RwrZ28SmCvToGqStg6WJWca0kAL8m7IG6aDygnH9T_0qFQ1n0lr33oPzCafgkTH-Exe98PBTak' 
        ]; 

        $body = [
          "data" => [
              "name" => "test content dengan api module door",
              "lang" => "en",
              "module" => "news",
              "content_body" => [
                  "value" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                  "summary" => "",
                  "format" => "basic_html"
              ],
              "content_image" => [
                  "filename" => "039886000_1593101008-Tips_Bisnis.jpeg",
                  "mimeType" => "image/jpeg",
                  "location" => "https://content-management-service.test/sites/default/files/2022-03/039886000_1593101008-Tips_Bisnis.jpeg"
              ],
              "status" => "published",
              "created_date" => "2023-08-09 15:20:43",
              "last_update" => "2023-08-09 20:17:11"
          ],
      ];
     
        // // Execute cURL session and get response 
        // $response =  $this->http_request($url, $headers); 
        $client = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        $response = $client->request(
            'POST',
            $url,
            [
                'headers' => $headers,
                'json' => $body,
            ]
        );
     
     
        // // Get HTTP status code 
        // $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
     
        // Assert HTTP status code is 200 (OK) 
        // var_dump($code); 
     
        // Parse JSON response 
        $content = $response->toArray(); 
        $this->assertEquals($content['code'], 0);
        
        
    }
}
