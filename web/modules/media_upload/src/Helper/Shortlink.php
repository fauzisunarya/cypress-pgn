<?php

namespace Drupal\media_upload\Helper;
use GuzzleHttp\Client;
use Drupal\node\Entity\Node;

class Shortlink {

  public function get_landing_shortlink($node=null){
    if ($node===null || $node->type->entity->get('type')!=='landing' ) {
      return '';
    }

    $path = \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$node->id()); // output: /landing/slug
    $landing_page_url = $node->field_lan_website_full->getString(); // expected output: landingpage/slug

    $slug = explode( 'landing/',$path)[1];

    $shortlink = $node->field_shortlink->getString();

    if($landing_page_url !== "landingpage/".$slug){
      // re-generate shortlink, because slug was changed
      $new_url = "landingpage/" . $slug;
      $node->field_lan_website_full = $new_url;
      $node->field_shortlink = $this->generate_shortlink($_ENV['APP_URL']. "/". $new_url);
      $node->save();

      return $node->field_shortlink->getString();
    }
    else if (empty($shortlink)) {
      // first generate shortlink
      $node->field_shortlink = $this->generate_shortlink($_ENV['APP_URL']. "/". $landing_page_url);
      $node->save();

      return $node->field_shortlink->getString();
    }

    return $shortlink;
  }

  public function generate_shortlink($link){

    $client = new Client([
      'base_uri' => $_ENV["APIGW_BASE_URL"]
    ]);

    $accessToken   = null;
    $authToken = null;

    // get access to apigw
    $authResponse  = $client->post('invoke/pub.apigateway.oauth2/getAccessToken', [
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
      'body' => json_encode([
        "grant_type"    => "client_credentials",
        "client_id"     => $_ENV["APIGW_CLIENT_ID"],
        "client_secret" => $_ENV["APIGW_CLIENT_SECRET"]
      ])
    ]);

    // get access token
    if ($authResponse->getStatusCode() == 200) :
      $response = json_decode($authResponse->getBody());
      $accessToken = $response->token_type .' '. $response->access_token;

      // get auth token
      if (!empty($accessToken)) {
        $tokenResponse = $client->post('gateway/telkom-url-shortlink/1.0/apiShortlink/get-token', [
          'headers' => [
            'Authorization' => $accessToken,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
          ],
          'body' => json_encode([
            'apiShortlinkRequest' => [
              'eaiHeader' => [
                "externalId" => "",
                "timestamp" => date("Y-m-d H:i:s")
              ],
              'eaiBody' => [
                "code" => "0",
                "guid" => "0",
                "data" => [
                  "username" => $_ENV["APIGW_SHORTLINK_USERNAME"],
                  "password" => $_ENV["APIGW_SHORTLINK_PASSWORD"]
                ]
              ]
            ]
          ])
        ]);

        if ($tokenResponse->getStatusCode() == 200) {
          $response = json_decode($tokenResponse->getBody());

          if ($response->apiShortlinkRespone->eaiStatus->srcResponseCode == 200) {
            $authToken = $response->apiShortlinkRespone->eaiBody->data;

            if (!empty($authToken)) {

              $generateToken = $client->post('gateway/telkom-url-shortlink/1.0/apiShortlink/gen', [
                'headers' => [
                  'Authorization' => $accessToken,
                  'Content-Type'  => 'application/json',
                  'Accept'        => 'application/json',
                ],
                'body' => json_encode([
                  'apiShortlinkRequest' => [
                    'eaiHeader' => [
                      "externalId" => "",
                      "timestamp" => date("Y-m-d H:i:s")
                    ],
                    'eaiBody' => [
                      "code" => "1",
                      "guid" => $authToken,
                      "data" => [
                        "url"       => $link,
                        "user_name" => $_ENV["APIGW_SHORTLINK_USERNAME"],
                        "hash" => "cobasaja",
                        "timer" => "1",
                        "password" => "",
                        "expired" => ""
                      ]
                    ]
                  ]
                ])
              ]);

              if ($generateToken->getStatusCode() == 200) {
                $response = json_decode($generateToken->getBody());

                if ($response->apiShortlinkRespone->eaiStatus->srcResponseCode == 200) {
                  $link = $response->apiShortlinkRespone->eaiBody->data->short_link;
                }
              }

            }
          }
        }

      };
    endif;

    return $link;
  }

  public function get_landing_full_link(Node $landing, $only_alias=false, $end_slash=false){
    if ($landing->type->entity->get('type')!=='landing' ) {
      return '';
    }

    $alias = \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$landing->id()); // output: /landing/slug
    $alias = preg_replace("/^\/landing/", "/landingpage", $alias);
    if ($end_slash) {
      $alias .= "/";
    }
    
    if ($only_alias) {
      return $alias;
    }

    return $_ENV['APP_URL'] . $alias;
  }
}