<?php

namespace Drupal\restapi_door\Tests\Unit\Controller;

use Drupal\Tests\UnitTestCase; 
use Drupal\restapi_door\Controller\ContentController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;

class UpdateContentControllerTest extends UnitTestCase
{
    public function testUpdateContentApi()
    {
        $url = 'https://content-management-service.test/apidoor/contents/update';

        $headers = [
            'Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzaWQiOiJidTFlNTFvbjhzYm9tYTRocG1rNjc1MWFlcCIsInN1YiI6IjMiLCJleHAiOjE3OTAzNDc3NjEsImNvZGUiOiIxMDAxODA0MTkxIiwibmFtZSI6InVuaXRcclxuZW1wIiwiZW1haWwiOiJ1bml0LnRlc3QuZW1wQG5ldXJvbndvcmtzLmNvLmlkIiwibGFuZyI6ImlkIiwiYXBwaWQiOjgsImFwcG5hbWUiOiJkb29yIiwiaHR0cHM6Ly9oYXN1cmEuaW8vand0L2NsYWltcyI6eyJ4LWhhc3VyYS1hbGxvd2VkLXJvbGVzIjpbImVtcGxveWVlIl0sIngtaGFzdXJhLWRlZmF1bHQtcm9sZSI6ImVtcGxveWVlIn0sImZsYWciOjEsInVybCI6Imh0dHBzOlxcL1xcL3VhbS1kZXYubmV1cm9uLmlkIiwiY29uZmlnIjoie1wiYXBwbGljYXRpb25cIjphcnJheShcIm5hbWVcIjpcImRvb3JcIixcImNvbXBhbnlcIjpcImRvb3JcclxubmV1cm9uXCIsXCJpY29uXCI6XCJodHRwczpcXC9cXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcXC9kb29ydjNcXC9pbWFnZXNcXC9mYXZpY29uLmljb1wiLFwiaW1hZ2VfbG9nb1wiOlwiaHR0cHM6XFwvXFwvY2RuLm5ldXJvbndvcmtzLmNvLmlkXFwvZG9vcnYzXFwvaW1hZ2VzXFwvZmF2aWNvbi5pY29cIixcImltYWdlX2xvZ29fbmFtZVwiOlwiaHR0cHM6XFwvXFwvY2RuLm5ldXJvbndvcmtzLmNvLmlkXFwvZG9vcnYzXFwvaW1hZ2VzXFwvaG9yaXpvbnRhbC1sb2dvLnBuZ1wiLFwiaW1hZ2VfbG9hZGVyXCI6XCJodHRwczpcXC9cXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcXC9kb29ydjNcXC9pbWFnZXNcXC9mYXZpY29uLmljb1wiXHJcbiksXCJlbXBsb3llZVwiOmFycmF5KFwiYXBwbGljYXRpb25cIjpcImRvb3IsZG9vcm1vYmlsZVwiKSxcImF0dGVuZGFuY2VcIjphcnJheShcImN1dG9mZlwiOlxyXG5cIjI2XCIpfSIsInNhbHQiOiJhZWE5N2Q1YmI3Njc0NWY0ZTFlNzA4ZWFmMWFhYjczN2M5Mzc5N2E0Yjk0NWE0NGM5ZmZiMzczNzYxN2NlZWYyIiwiYWxsb3dlZF9yb2xlcyI6WyJlbXBsb3llZSJdfQ.Ce07InGDJFatQaCWJHPRYmv6xGrCLOI6DhIW7egODe1Rv-Vdf4pXR0ARn-Dy04HDZSI_YD-kJP8JK0qbaKtt_H8qOBsGVOca1vQW1yJ7ulzWlFDO6CxODEwwSfLfoGvod7gkwTUfnHYERwpeZ2kH_Uw9ZWhtLzpjRuhvsEwISiU'
        ];

        $body = [
            "data" => [
                "uuid" => "84d90d4b-f6c9-459f-b98e-68a02779bea8",
                "name" => "background for training edit",
                "lang" => "en",
                "module" => "article",
                "content_body" => [
                    [
                        "value" => "<h4>[edit edit] please use a background like the image below</h4> <br> <img src=\"https://content-management-service.test/apidoor/contents/image?id=uploads/cms/2023/08/14/training.jpg\"> <br> <h4> while participating in the training event </h4>",
                        "summary" => "",
                        "format" => "basic_html"
                    ]
                ],
                "status" => "published",
                "created_date" => "2023-08-09 15:20:43",
                "last_update" => "2023-08-09 20:17:11"
            ]
        ];

        // Create HTTP client
        $client = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);

        // Send POST request
        $response = $client->request(
            'POST',
            $url,
            [
                'headers' => $headers,
                'json' => $body,
            ]
        );

        // Parse JSON response
        $content = $response->toArray();
        $this->assertEquals($content['code'], 0);
    }
}
