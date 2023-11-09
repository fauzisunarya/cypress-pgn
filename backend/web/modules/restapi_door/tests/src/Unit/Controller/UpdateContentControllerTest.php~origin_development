<?php

namespace Drupal\restapi_door\Tests\Unit\Controller;

use Drupal\Tests\UnitTestCase;
use Drupal\restapi_door\Controller\ContentController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;

class UpdateContentControllerTest extends UnitTestCase
{
    public function testBerhasilMenyimpanDataContent()
    {
        $url = '/api/contents/update';

        $headers = [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzaWQiOiI4ODFibnU0Zm85MzM0a2hobmM5cmNzbmxjaSIsInN1YiI6IjE1IiwiZXhwIjoxNjkyNjc0ODk2LCJjb2RlIjoiMjAyMzAwMDAwNCIsIm5hbWUiOiJidWRpYW5hIGNlayIsImVtYWlsIjoidGVzMTIzMzMzM0BtYWlsLmNvbSIsImxhbmciOiJpZCIsImFwcGlkIjo4LCJhcHBuYW1lIjoiZG9vciIsImh0dHBzOlwvXC9oYXN1cmEuaW9cL2p3dFwvY2xhaW1zIjp7IngtaGFzdXJhLWFsbG93ZWQtcm9sZXMiOlsiaHIiXSwieC1oYXN1cmEtZGVmYXVsdC1yb2xlIjoiaHIifSwiZ3JhbnRzIjpbeyJhY2Nlc3Nfb2JqZWN0X3R5cGVfbmFtZSI6IkFjdGlvbiIsImFjY2Vzc19vYmplY3RfY29kZSI6ImxvZ2luIiwiYWNjZXNzX3Blcm1pc3Npb25fbmFtZSI6ImFsbCJ9LHsiYWNjZXNzX29iamVjdF90eXBlX25hbWUiOiJBY3Rpb24iLCJhY2Nlc3Nfb2JqZWN0X2NvZGUiOiJ1c2VyX2NyZWF0ZSIsImFjY2Vzc19wZXJtaXNzaW9uX25hbWUiOiJhbGwifSx7ImFjY2Vzc19vYmplY3RfdHlwZV9uYW1lIjoiQWN0aW9uIiwiYWNjZXNzX29iamVjdF9jb2RlIjoidXNlcl9wcm9maWxlIiwiYWNjZXNzX3Blcm1pc3Npb25fbmFtZSI6ImFsbCJ9LHsiYWNjZXNzX29iamVjdF90eXBlX25hbWUiOiJBY3Rpb24iLCJhY2Nlc3Nfb2JqZWN0X2NvZGUiOiJ1c2VyX3VwZGF0ZSIsImFjY2Vzc19wZXJtaXNzaW9uX25hbWUiOiJhbGwifSx7ImFjY2Vzc19vYmplY3RfdHlwZV9uYW1lIjoiQWN0aW9uIiwiYWNjZXNzX29iamVjdF9jb2RlIjoidXNlcl9hdmF0YXIiLCJhY2Nlc3NfcGVybWlzc2lvbl9uYW1lIjoiYWxsIn0seyJhY2Nlc3Nfb2JqZWN0X3R5cGVfbmFtZSI6IkFjdGlvbiIsImFjY2Vzc19vYmplY3RfY29kZSI6ImNtc19jcmVhdGVfY29udGVudCIsImFjY2Vzc19wZXJtaXNzaW9uX25hbWUiOiJhbGwifV0sImNhbl9jcmVhdGUiOiJ5ZXMiLCJjYW5fbW9kaWZ5IjoieWVzIiwiY2FuX2RlbGV0ZSI6InllcyIsImNhbl9hdXRoIjoieWVzIiwiY2FuX2FjbCI6InllcyIsImZsYWciOjEsInVybCI6Imh0dHBzOlwvXC91YW0tZGV2Lm5ldXJvbi5pZCIsImNvbmZpZyI6IntcImFwcGxpY2F0aW9uXCI6e1wibmFtZVwiOlwiZG9vclwiLFwiY29tcGFueVwiOlwiZG9vciBuZXVyb25cIixcImljb25cIjpcImh0dHBzOlwvXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcL2Rvb3J2M1wvaW1hZ2VzXC9mYXZpY29uLmljb1wiLFwiaW1hZ2VfbG9nb1wiOlwiaHR0cHM6XC9cL2Nkbi5uZXVyb253b3Jrcy5jby5pZFwvZG9vcnYzXC9pbWFnZXNcL2Zhdmljb24uaWNvXCIsXCJpbWFnZV9sb2dvX25hbWVcIjpcImh0dHBzOlwvXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcL2Rvb3J2M1wvaW1hZ2VzXC9ob3Jpem9udGFsLWxvZ28ucG5nXCIsXCJpbWFnZV9sb2FkZXJcIjpcImh0dHBzOlwvXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcL2Rvb3J2M1wvaW1hZ2VzXC9mYXZpY29uLmljb1wiXHJcbn0sXCJlbXBsb3llZVwiOntcImFwcGxpY2F0aW9uXCI6XCJkb29yLGRvb3Jtb2JpbGVcIn0sXCJhdHRlbmRhbmNlXCI6e1wiY3V0b2ZmXCI6IFwiMjZcIn19Iiwic2FsdCI6IjYwZjIwNWZkOTYzNWVjMTNhM2JjNmUxM2UxMTA0ZmI2ODlkMzQwMDkwOWU1Yjk4YTY5ODk1ZGFkYWUzYzM5MGEifQ.RcnubOgZq73kb5RHjn9w5eYpbwGdhpRuvS4jXvpFnMOaJLGCKEKs0HDR2S7ZqWQmMR8-6pUSegzoF-5bH0n2mlELssPyAi8IJWap2WqfM65UVneQrXcDxFh9iVTUXGyMtrOeiPx86UH5GjNd_8jXXiJ8OKiAK-Rd7LWQrT4LtaA'
        ];

        $body = [
            "data" => [
                "uuid" => "0d3d4dd0-0644-4631-b23b-97d3f8fffce0",
                "name" => "background for training edit",
                "lang" => "en",
                "module" => "article",
                "content_body" => [
                    [
                        "value" => "<h4>[edit edit] please use a background like the image below</h4> <br> <img src=\"https://content-management-service.test/api/contents/image?id=uploads/cms/2023/08/14/training.jpg\"> <br> <h4> while participating in the training event </h4>",
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
        $response = $this->client->request(
            'POST',
            $url,
            [
                'headers' => $headers,
                'json' => $body,
            ]
        );

        // Assert that the response is not null
        $this->assertNotNull($response);

        // Parse JSON response
        $content = $response->toArray();
        $this->assertEquals($content['code'], 0);
    }

    public function testReturnGagalUpdateContent()
    {
        $url = '/api/contents/update';

        $headers = [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzaWQiOiI4ODFibnU0Zm85MzM0a2hobmM5cmNzbmxjaSIsInN1YiI6IjE1IiwiZXhwIjoxNjkyNjc0ODk2LCJjb2RlIjoiMjAyMzAwMDAwNCIsIm5hbWUiOiJidWRpYW5hIGNlayIsImVtYWlsIjoidGVzMTIzMzMzM0BtYWlsLmNvbSIsImxhbmciOiJpZCIsImFwcGlkIjo4LCJhcHBuYW1lIjoiZG9vciIsImh0dHBzOlwvXC9oYXN1cmEuaW9cL2p3dFwvY2xhaW1zIjp7IngtaGFzdXJhLWFsbG93ZWQtcm9sZXMiOlsiaHIiXSwieC1oYXN1cmEtZGVmYXVsdC1yb2xlIjoiaHIifSwiZ3JhbnRzIjpbeyJhY2Nlc3Nfb2JqZWN0X3R5cGVfbmFtZSI6IkFjdGlvbiIsImFjY2Vzc19vYmplY3RfY29kZSI6ImxvZ2luIiwiYWNjZXNzX3Blcm1pc3Npb25fbmFtZSI6ImFsbCJ9LHsiYWNjZXNzX29iamVjdF90eXBlX25hbWUiOiJBY3Rpb24iLCJhY2Nlc3Nfb2JqZWN0X2NvZGUiOiJ1c2VyX2NyZWF0ZSIsImFjY2Vzc19wZXJtaXNzaW9uX25hbWUiOiJhbGwifSx7ImFjY2Vzc19vYmplY3RfdHlwZV9uYW1lIjoiQWN0aW9uIiwiYWNjZXNzX29iamVjdF9jb2RlIjoidXNlcl9wcm9maWxlIiwiYWNjZXNzX3Blcm1pc3Npb25fbmFtZSI6ImFsbCJ9LHsiYWNjZXNzX29iamVjdF90eXBlX25hbWUiOiJBY3Rpb24iLCJhY2Nlc3Nfb2JqZWN0X2NvZGUiOiJ1c2VyX3VwZGF0ZSIsImFjY2Vzc19wZXJtaXNzaW9uX25hbWUiOiJhbGwifSx7ImFjY2Vzc19vYmplY3RfdHlwZV9uYW1lIjoiQWN0aW9uIiwiYWNjZXNzX29iamVjdF9jb2RlIjoidXNlcl9hdmF0YXIiLCJhY2Nlc3NfcGVybWlzc2lvbl9uYW1lIjoiYWxsIn0seyJhY2Nlc3Nfb2JqZWN0X3R5cGVfbmFtZSI6IkFjdGlvbiIsImFjY2Vzc19vYmplY3RfY29kZSI6ImNtc19jcmVhdGVfY29udGVudCIsImFjY2Vzc19wZXJtaXNzaW9uX25hbWUiOiJhbGwifV0sImNhbl9jcmVhdGUiOiJ5ZXMiLCJjYW5fbW9kaWZ5IjoieWVzIiwiY2FuX2RlbGV0ZSI6InllcyIsImNhbl9hdXRoIjoieWVzIiwiY2FuX2FjbCI6InllcyIsImZsYWciOjEsInVybCI6Imh0dHBzOlwvXC91YW0tZGV2Lm5ldXJvbi5pZCIsImNvbmZpZyI6IntcImFwcGxpY2F0aW9uXCI6e1wibmFtZVwiOlwiZG9vclwiLFwiY29tcGFueVwiOlwiZG9vciBuZXVyb25cIixcImljb25cIjpcImh0dHBzOlwvXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcL2Rvb3J2M1wvaW1hZ2VzXC9mYXZpY29uLmljb1wiLFwiaW1hZ2VfbG9nb1wiOlwiaHR0cHM6XC9cL2Nkbi5uZXVyb253b3Jrcy5jby5pZFwvZG9vcnYzXC9pbWFnZXNcL2Zhdmljb24uaWNvXCIsXCJpbWFnZV9sb2dvX25hbWVcIjpcImh0dHBzOlwvXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcL2Rvb3J2M1wvaW1hZ2VzXC9ob3Jpem9udGFsLWxvZ28ucG5nXCIsXCJpbWFnZV9sb2FkZXJcIjpcImh0dHBzOlwvXC9jZG4ubmV1cm9ud29ya3MuY28uaWRcL2Rvb3J2M1wvaW1hZ2VzXC9mYXZpY29uLmljb1wiXHJcbn0sXCJlbXBsb3llZVwiOntcImFwcGxpY2F0aW9uXCI6XCJkb29yLGRvb3Jtb2JpbGVcIn0sXCJhdHRlbmRhbmNlXCI6e1wiY3V0b2ZmXCI6IFwiMjZcIn19Iiwic2FsdCI6IjYwZjIwNWZkOTYzNWVjMTNhM2JjNmUxM2UxMTA0ZmI2ODlkMzQwMDkwOWU1Yjk4YTY5ODk1ZGFkYWUzYzM5MGEifQ.RcnubOgZq73kb5RHjn9w5eYpbwGdhpRuvS4jXvpFnMOaJLGCKEKs0HDR2S7ZqWQmMR8-6pUSegzoF-5bH0n2mlELssPyAi8IJWap2WqfM65UVneQrXcDxFh9iVTUXGyMtrOeiPx86UH5GjNd_8jXXiJ8OKiAK-Rd7LWQrT4LtaA'
        ];

        $body = [
            "data" => [
                "uuid" => "",
                "name" => "",
                "lang" => "en",
                "module" => "",
                "content_body" => [
                    [
                        "value" => "",
                        "summary" => "",
                        "format" => ""
                    ]
                ],
                "status" => "published",
                "created_date" => "2023-08-09 15:20:43",
                "last_update" => "2023-08-09 20:17:11"
            ]
        ];

        $response = $this->client->request(
            'POST',
            $url,
            [
                'headers' => $headers,
                'json' => $body,
            ]
        );

        // Assert that the response is not null
        $this->assertNotNull($response);

        // Parse JSON response
        $statusCode = $response->getStatusCode();

        // Assert that the status code indicates a failed request (400 Bad Request)
        $this->assertEquals(400, $statusCode);
    }

    public function testGagalUpdateContentDenganInformasiJwtInvalid()
    {
        $url = '/api/contents/update';

        $headers = [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzaWQiOiI4ODFibnEKs0HDR2S7ZqWQmMR8-6pUSegzoF-5bH0n2mlELssPyAi8IJWap2WqfM65UVneQrXcDxFh9iVTUXGyMtrOeiPx86UH5GjNd_8jXXiJ8OKiAK-Rd7LWQrT4LtaA'
        ];

        $body = [
            "data" => [
                "uuid" => "311758db-375b-4f61-b58f-0e9e19b923a6",
                "name" => "background for training edit",
                "lang" => "en",
                "module" => "article",
                "content_body" => [
                    [
                        "value" => "<h4>[edit edit] please use a background like the image below</h4> <br> <img src=\"https://content-management-service.test/api/contents/image?id=uploads/cms/2023/08/14/training.jpg\"> <br> <h4> while participating in the training event </h4>",
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
        $response = $this->client->request(
            'POST',
            $url,
            [
                'headers' => $headers,
                'json' => $body,
            ]
        );

        // Assert that the response is not null
        $this->assertNotNull($response);

        // Parse JSON response
        $statusCode = $response->getStatusCode();

        // Assert that the status code indicates a failed request (400 Bad Request)
        $this->assertEquals(403, $statusCode);
    }
}
