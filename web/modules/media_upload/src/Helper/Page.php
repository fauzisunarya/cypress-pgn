<?php

namespace Drupal\media_upload\Helper;

use Drupal\Core\Routing\TrustedRedirectResponse;

class Page {
  
  public function page_404($return=true){
    $page = '
      <!DOCTYPE html>
      <html lang="en">
          <head>
              <meta charset="utf-8" />
              <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
              <meta name="description" content="" />
              <meta name="author" content="" />
              <title>Telkom Dashboard</title>
              <!-- Favicon-->
              <link rel="icon" type="image/x-icon" href="'.$_ENV['APP_URL'].'/themes/custom/telkom_cms/assets/icons/favicon.ico" />
              <!-- Core theme CSS (includes Bootstrap)-->
              <link rel="stylesheet" href="'.$_ENV['APP_URL'].'/themes/custom/telkom_cms/css/styles.css" />
          </head>
          <body>
              <div id="page_404">
                  <div class="container">
                      <div class="row">
                          <div class="col-sm-12 ">
                              <div class="text-center">
                                  <div class="four_zero_four">
                                      <h1>404</h1>
                                  </div>
                      
                                  <div class="contant_box_404">
                                      <h3 class="h2">
                                          Look like you\'re lost
                                      </h3>
                                      <p>the page you are looking for is not available!</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </body>
      </html>
    ';

    if ($return) {
      return $page;
    }
    else{
      echo $page;
    }
  }

  public function redirect($redirect_url){
    $response_headers = [
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
    ];
    
    $response = new TrustedRedirectResponse($redirect_url, 301, $response_headers);
    $response->send();exit;
  }
}