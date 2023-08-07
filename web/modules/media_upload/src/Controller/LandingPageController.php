<?php
namespace Drupal\media_upload\Controller;

use CTA15;
use Drupal;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drush\Drush;
use Feature12;
use Robo\Task\File\Replace;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use stdClass;
use Drupal\Component\Utility\Crypt;

/**
 * Process rendering landing page
 */
class LandingPageController {

  private function get_domain() {
    $arr = explode('://',$_ENV['APP_URL']); // ex : https://ami-dev.telkom.co.id
    if (count($arr)===2) {
      $domainAmi = $arr[1]; // ex : ami-dev.telkom.co.id

      // ex : ami-dev.telkom.co.id (regular)
      // ex : subdomain.ami-dev.telkom.co.id (using subdomain)
      // ex : otherdomain.com (using custom domain)
      $host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];

      if (str_ends_with($host, $domainAmi)  && strlen($host) !== strlen($domainAmi)) {
        // using subdomain
        $subdomain = str_replace($domainAmi, '', $host);
        $subdomain = $subdomain ? preg_replace("/\.$/", '', $subdomain) : '';

        $name = $subdomain;
        $type   = 'subdomain'; 
      }
      else if ( ! str_ends_with($host, $domainAmi) ) {
        // using custom domain
        $name = $host;
        $type   = 'custom'; 
      }
      
    }

    return [
      'name' => $name ?? '',
      'type' => $type ?? ''
    ];
  }

  /**
   * Display the landing page from page builder
   * 
   * landing url = /landingpage/slug (display the landing page). To get the id landing page, there is a url aliase for the landing content type.
   * content type "landing" url = /landing/slug (for displaying detail field), has the same slug, so it can get the landing id
   * 
   * info: content type "landing" for project landing page & content type "landing_page" is the pages for the "landing" content type
   */
  public function show_landing_page($landing_home_slug='', $landing_page_slug=''){

    global $domain;

    $page_404 = \Drupal::service('media_upload.page_helper')->page_404();

    $entity = \Drupal::entityTypeManager()->getStorage('node');

    // change $_ENV['APP_URL'] to use subdomain or custom domain & revert at the end
    $default_app_url = $_ENV['APP_URL'];

    $domain = $this->get_domain();
    if (!empty($domain['type'])) {

      $query = $entity->getQuery()->condition('status', 1)->condition('type', 'landing');

      if ($domain['type'] === 'custom') {
        $query = $query->condition('field_lan_domain', $domain['name']);
      }
      else if ($domain['type'] === 'subdomain') {
        $query = $query->condition('field_lan_subdomain', $domain['name']);
      }
      else {
        // return not found
        return new Response($page_404);
      }

      $id = reset($query->execute());

      // landing is found
      if (!empty($id)) {
        // get landing object
        $landing = \Drupal::entityTypeManager()->getStorage('node')->load($id);

        // switch (see notes "request path" in below)
        $landing_page_slug = $landing_home_slug;
        $path = \Drupal::service('path_alias.manager')->getAliasByPath("/node/".$landing->id()); // output: /landing/slug
        $landing_home_slug = explode( 'landing/',$path)[1];

        // change ENV APP_URL
        $arr_app_url = explode('://', $_ENV['APP_URL']);
        if ($domain['type'] === 'custom') {
          $_ENV['APP_URL'] = $arr_app_url[0] . '://' . $domain['name'];
        }
        else if ($domain['type'] === 'subdomain') {
          $_ENV['APP_URL'] = $arr_app_url[0] . '://' . $domain['name'] . '.' . $arr_app_url[1];
        }

      }
      else{
        // return not found
        return new Response($page_404);
      }

    }

    // request path: /landingpage/landing_home_slug = homepage for the landing page, 
    // request path: /landingpage/landing_home_slug/landing_page_slug = additional page for the landing page

    // if using subdomain, structure :
    // http://$subdomain.ami.com/landingpage = homepage
    // http://$subdomain.ami.com/landingpage/landing_page_slug = additional page for the landing page
    // can also use custom domain

    // request path (for preview): /preview/landingpage/landing_home_slug
    // request path (for preview): /preview/landingpage/landing_home_slug/landing_page_slug

    if (empty($landing)) {
      // get the landing id
      $alias = \Drupal::service('path_alias.manager')->getPathByAlias("/landing/$landing_home_slug"); // output /node/id
      if (strpos($alias, 'node/')===false) {
        return new Response($page_404);
      }
      $landing_id = explode('node/', $alias)[1];
  
      // get the landing object
      $landing = \Drupal::entityTypeManager()->getStorage('node')->load($landing_id);
    }

    if ($landing===null) {
      return new Response($page_404);
    }

    $content_type = $landing->type->entity->get('type');
    if ($content_type !== 'landing') {
      return new Response($page_404);
    }

    // landing page url can be showed if approved. for approval page, use link .../preview/landingpage/... to see the preview landing page
    if (!str_contains($_SERVER['REQUEST_URI'], 'preview') && $landing->field_workflow_status->getString()!=='workflow_status_approve') {
      return new Response($page_404);
    }

    $title = $landing->title->getString();
    $description = $landing->field_lan_website_description->getString();
    $landing_page_url = \Drupal::service('media_upload.shortlink_helper')->get_landing_full_link($landing, true, true);

    // get the page logo
    if (!$landing->field_lan_website_logo->isEmpty()) :
      $logo = $landing->field_lan_website_logo->getString();

      if (str_contains($logo, 's3')) {
        $findS3 = \Drupal::service('restapi_telkom.minio_helper')->getFileByURI($logo, 'thumbnail');

        $logo = $findS3['status'] ? $findS3['data'] : $logo;
      };
    endif;

    // get the page content
    if (empty($landing_page_slug)) { //so, this condition also known as homepage

      $query = $entity->getQuery();
      $ids = $query->condition('status', 1)
        ->condition('type', 'landing_page')#type = bundle id (machine name)
        ->condition('field_page_landing_id', $landing->id())
        ->condition('field_page_type', 1) //get the homepage
        ->execute();

      $id = reset($ids);

      // condition when there is no page was setup to homepage, then display any page
      if (empty($id)) {
        $query = $entity->getQuery();
        $ids = $query->condition('status', 1)
          ->condition('type', 'landing_page')#type = bundle id (machine name)
          ->condition('field_page_landing_id', $landing->id())
          ->execute();

        $id = reset($ids);

        if (empty($id)) {
          return new Response("404 - Page Not Found");
        }
      }

      $page_obj = \Drupal::entityTypeManager()->getStorage('node')->load($id);

    }
    else{

      // get the additional page in landing page
        $query = $entity->getQuery();
        $ids = $query->condition('status', 1)
          ->condition('type', 'landing_page')#type = bundle id (machine name)
          ->condition('field_page_landing_id', $landing->id())
          ->condition('field_website_page_slug', $landing_page_slug)
          ->condition('field_page_type', 1, '<>') //not the homepage
          ->execute();

        $id = reset($ids);

        if (empty($id)) {
          return new Response("404 - Page Not Found");
        }

        $page_obj = \Drupal::entityTypeManager()->getStorage('node')->load($id);
    }

    // require file to render blocks
    require_once BASE_BUILDER_PATH . '/helpers/builder_function.php';
    require_once BASE_BUILDER_PATH . '/helpers/builder_helper.php';
    require_once BASE_BUILDER_PATH . '/helpers/font_awesome_helper.php';

    // this is the landing page menu
    $header = json_decode($landing->field_lan_website_menu->getString());
    if ($header) {
      foreach ($header as $key => $menu) {
        $header[$key]->single = false;
      }
    }

    // make global, used in function base_url in block file (/blocks/*/data.php) 
    $home_slug = $landing_home_slug;
    global $landing_home_slug; 
    $landing_home_slug = $home_slug;

    global $is_in_preview;
    $is_in_preview = str_contains($_SERVER['REQUEST_URI'], 'preview/landingpage');

    // assign personalization & process if matching redirect
    if ($is_in_preview===false) {

      // only apply in real url page
      $redirect_page = Drupal::service('media_upload.landing_personalization')->process_personalization($page_obj);
      
      if (!empty($redirect_page) && (int)$redirect_page != $page_obj->id()) {
        $query = $entity->getQuery();
        $ids = $query->condition('status', 1)
          ->condition('type', 'landing_page')#type = bundle id (machine name)
          ->condition('nid', $redirect_page)
          ->condition('field_page_landing_id', $landing->id())
          ->execute();
  
        $id = reset($ids);
  
        if (!empty($id)) {
          $page_obj = \Drupal::entityTypeManager()->getStorage('node')->load($id);
        }
        
      }
    }

    // if from platform AMANDA (DMP), assign cookie
    $this->amandaCookie();
    // if contains refferal code, assign cookie
    $this->refererCookie();
    // if contains partnerId, assign cookie
    $this->partnerIdCookie();

    // convert uri landing page "home" from web_url/landingpage/landing_home_slug/ to become base ("/")
    // $_SERVER['REQUEST_URI'] is used in block file (ex: header block) for navigate current menu
    // dont change block file. in ukm digital, url landing page "home" is web_url/ ,so the home uri is "/"  
    global $default_request_uri;
    $default_request_uri = $_SERVER['REQUEST_URI'];
    if (empty($domain['type'])) {
      // regular domain
      $request_uri = explode($landing_home_slug,$_SERVER['REQUEST_URI'])[1];
    }
    else{
      // custom domain or sub domain
      $request_uri = explode('/landingpage',$_SERVER['REQUEST_URI'])[1];
    }
    if (empty($request_uri)) {
      $request_uri = "/";
    }
    $_SERVER['REQUEST_URI'] = $request_uri;

    // json blocks
    $json_blocks = $page_obj->field_website_page_component->getString();
    $json_blocks = json_decode($json_blocks) ?? [];

    // regenerate catalog based on database (using decode assoctiave array)
    $page_blocks = \Drupal::service('media_upload.landing_helper')->retrievePricing($landing->uuid(), $json_blocks, false);

    // render the page block
    $str_blocks = "";
    $str_css = "";
    if ($page_blocks) {

      $componentArray = glob(BASE_BUILDER_PATH . '/blocks/*/data.php');
      $arrayCode = [];

      foreach($componentArray as $key => $item){
        require_once $item;
      }

      $arr_blocks = [];
      foreach ($arrayCode as $code) {

        $arr_blocks[$code['blockID']] = ucfirst(str_replace(array('-0', '-'), '', $code['blockID']));

        // get all blocks style
        $str_css .= $arr_blocks[$code['blockID']]::renderCSS();
      }

      foreach ($page_blocks as $block_obj) {
  
        $block_id = $block_obj->blockID;
  
        $post = null; // used in catalog block

        // load current logo
        if (!empty($block_obj->data) && !empty($block_obj->data->logo) && !empty($block_obj->data->logo->value) && !empty($logo)) {
          $block_obj->data->logo->value = $logo;
        }
        
        // get the block based on "data" and "style" from database
        $str_blocks .= $arr_blocks[$block_id]::render($block_id, $block_obj->data, $block_obj->style, $header, $post);
  
      }
    }

    //convert to default
    $_SERVER['REQUEST_URI'] = $default_request_uri;

    // get the global theme style
    $str_website_style = "";
    $website_style = json_decode($landing->field_lan_website_style->getString(), true);
    if ($website_style) {
      foreach ($website_style as $style) {
        foreach ($style['settings'] as $key => $arr_value) {
          if ($key==="textFontSize") {
            $str_website_style .= "font-size:".$arr_value['value'].";";
            continue;
          }
          $str_website_style .= "--".$key.":".$arr_value['value'].";";
        }
      }
    }
    $str_website_style = "<style id='editor-style'> :root{".$str_website_style."} </style>";

    // get meta data
    $meta = json_decode($landing->field_lan_website_meta->getString(), true);
    // print_r($meta);
    $meta['custom_meta_tag'] = !empty($meta['custom_meta_tag']) ? $meta['custom_meta_tag'] : '';
    $meta['whatsapp_button'] = !empty($meta['whatsapp_button']) ? $meta['whatsapp_button'] : '';
    $meta['google_analytics'] = !empty($meta['google_analytics']) ? $meta['google_analytics'] : '';
    $meta['google_tagmanager'] = !empty($meta['google_tagmanager']) ? $meta['google_tagmanager'] : '';
    $meta['facebook_pixel'] = !empty($meta['facebook_pixel']) ? $meta['facebook_pixel'] : '';
    $meta['google_searchconsole'] = !empty($meta['google_searchconsole']) ? $meta['google_searchconsole'] : '';
    $meta['custom_style'] = !empty($meta['custom_style']) ? $meta['custom_style'] : '';
    $meta['custom_script'] = !empty($meta['custom_script']) ? $meta['custom_script'] : '';

    $whatsapp_button = empty($meta['whatsapp_button']) ? "" : "<a class='wa-fab' href='https://api.whatsapp.com/send?phone=".$meta['whatsapp_button']."'><i class='ukm-icon'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><path d='M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z'></path></svg></i> </a>";

    $themeHandler = \Drupal::service('theme_handler');
    $themePath = $_ENV['APP_URL'].'/'.$themeHandler->getTheme($themeHandler->getDefault())->getPath();

    $html = "
      <html lang='en'>
        <head>

          <!-- Global site tag (gtag.js) - Google Analytics -->
          <script async src='https://www.googletagmanager.com/gtag/js?id=".$meta['google_analytics']."'></script>
          <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          
          gtag('config', '".$meta['google_analytics']."');
          </script>

          <!-- Google Tag Manager -->
          <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
          new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
          'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
          })(window,document,'script','dataLayer','".$meta['google_tagmanager']."');</script>
          <!-- End Google Tag Manager -->

          <!-- Facebook Pixel Code -->
          <script>
          !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
          n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
          t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
          document,'script','https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', '".$meta['facebook_pixel']."' );			fbq('track', 'PageView');
          
          </script>
          <noscript><img height='1' width='1' style='display:none'
          src='https://www.facebook.com/tr?id=".$meta['facebook_pixel']."&ev=PageView&noscript=1'
          /></noscript>
          <!-- DO NOT MODIFY -->
          <!-- End Facebook Pixel Code -->

          <meta charset='utf-8'>
          <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
          <title> Landing | ". (!empty($title) ? $title : 'Telkom CMS') ." </title>
          <meta content='".$title."' property='og:title'>
          <meta name='description' content='".$description."'/>
          <meta name='author' content='Telkom CMS'/>
          <link rel='shortcut icon' type='image/png' href='".(!empty($logo) ? $logo : get_builder_path('favicon.png'))."'/>
          <meta property='og:locale' content='id-ID'/>
          <meta property='og:site_name' content='Telkom CMS'/>
          <meta property='og:image' content='".get_builder_path('assets/images/logo.png')."'/>
          <meta property='og:image:width' content='200'/>
          <meta property='og:image:height' content='41'/>
          <meta property='og:type' content='website'/>
          <meta property='og:title' content='".$title."'/>
          <meta property='og:description' content='".$description."'/>
          <meta property='og:url' content='".get_builder_path()."'/>
          <meta property='fb:app_id' content=''/>
          <meta name='twitter:card' content='summary_large_image'/>
          <meta name='twitter:title' content='".$title."'/>
          <meta name='twitter:description' content='".$description."'/>
          <meta name='twitter:image' content='".get_builder_path('assets/images/logo.png')."'/>
          <link rel='anonical' href=''/>
          <link rel='stylesheet' href='".get_builder_path('assets/builder/blocks-assets/css/bootstrap.min.css')."'>
          <link rel='stylesheet' href='".get_builder_path('assets/builder/blocks-assets/css/global.css')."'>
          <link rel='stylesheet' href='".get_builder_path('assets/builder/blocks-assets/css/fontawesome.min.css')."'>
          <link rel='stylesheet' href='".get_builder_path('assets/builder/blocks-assets/css/material-icons.min.css')."'>
          <link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet'>
          <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.snow.css'>
          <link class='editor-font' rel='stylesheet' href='https://fonts.googleapis.com/css?family=Raleway:400,400i,700,700i|Caveat:400,400i,700,700i|Playfair+Display:400,400i,700,700i&amp;display=swap'>
          <link rel='canonical' href='".$landing_page_url."' />

          <!-- Google search console -->
          <meta name='google-site-verification' content='".$meta['google_searchconsole']."'>	

          <!-- Theme Style -->
          $str_website_style

          <!-- Custom Style -->
          <style>".$meta['custom_style']."</style>

          <!-- Blocks Style -->
          <style>
            $str_css
          </style>

          <script src='https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js'></script>

          <!-- Custom Meta Tag -->
          ".$meta['custom_meta_tag']."

          <!-- Landing CSS -->
          <link rel='stylesheet' href='".$themePath."/css/landing-page.css' />

          <!-- Leaflet JS -->
          <link rel='stylesheet' href='".$themePath."/js/vendor/leaflet/leaflet.css' />
          <script src='".$themePath."/js/vendor/leaflet/leaflet.js'></script>

          <!-- Signature -->
          <link type='text/css' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/themes/south-street/jquery-ui.css' rel='stylesheet'> 
          <link rel='stylesheet' type='text/css' href='".$themePath."/js/vendor/signature/jquery.signature.css'>

        </head>
        <body>
          <!-- Google Tag Manager (noscript) -->
          <noscript><iframe sandbox='allow-same-origin allow-scripts' src='https://www.googletagmanager.com/ns.html?id=".$meta['google_tagmanager']."'
          height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
          <!-- End Google Tag Manager (noscript) -->

          $str_blocks

          $whatsapp_button

          <!-- Leaflet JS Search -->
          <script src='".$themePath."/js/vendor/leaflet/leaflet-search.js'></script>

          <!-- Custom Script -->
          <script>".$meta['custom_script']."</script>

        </body>
        <script src='".get_builder_path('assets/builder/src/frontend.js')."'></script>
        
        <!-- Signature -->
        <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/jquery-ui.min.js'></script>  
        <script type='text/javascript' src='".$themePath."/js/vendor/signature/jquery.signature.min.js'></script>
        <script type='text/javascript' src='".$themePath."/js/vendor/signature/jquery-signature-touch-support.js'></script>

        <script src='".$themePath."/js/scripts-landing-form.js'></script>
      </html>
    ";
    // print_r($page_blocks);

    // log analytic
    if (empty($_SERVER['HTTP_REFERER']) || ( !empty($_SERVER['HTTP_REFERER']) && !str_ends_with($_SERVER['HTTP_REFERER'], $_SERVER['REQUEST_URI']) ) ) {
      // this conditional is to handle bugs from chrome. chrome send request twice, the first one is the valid reqeust (referer!=uri), the second is invalid (when referer=uri)
      if ($is_in_preview===false) {
        Drupal::service('media_upload.landing_analytic')->storeLog($landing, $page_obj);
      }
    }

    if (!empty($_SERVER["HTTP_PAGE"]) && str_contains($_ENV["PUBLIC_URL"], $_SERVER["HTTP_PAGE"])) {
      $html = str_replace($_ENV["APP_URL"], $_ENV["PUBLIC_URL"], $html);
    }

    // revert
    $_ENV['APP_URL'] = $default_app_url;

    return new Response($html, 200, ['content-type'=>'text/html']);
    
  }
  
  // Assign referer tracking cookie (if exist)
  private function refererCookie() {
    if (!empty($_GET['reff'])) {
      setcookie('reff', $_GET['reff'], array (
        'expires' => time()+3600, // 1 jam
        'path' => '/', 
        'domain' => '.'.$_SERVER['HTTP_HOST'], // leading dot for compatibility or use subdomain
        'secure' => true,     // or false
        'httponly' => false,    // or false
        'samesite' => 'Strict' // None || Lax  || Strict
      ));
    }
  }

  /**
   * Assign amanda tracking cookie (if exist)
   */
  private function amandaCookie() {
    preg_match( "/amdcode=AMD\d\d\d\d\d\d\d\d/i", $_SERVER['REQUEST_URI'], $amanda );
    if ( count($amanda)>0 ) {
      $amanda_value = explode('=', $amanda[0]);
      if ( count($amanda_value) === 2 ) {
        setcookie('amdcode', strtoupper($amanda_value[1]), array (
          'expires' => time()+3600, // 1 jam
          'path' => '/', 
          'domain' => '.'.$_SERVER['HTTP_HOST'], // leading dot for compatibility or use subdomain
          'secure' => true,     // or false
          'httponly' => false,    // or false
          'samesite' => 'Strict' // None || Lax  || Strict
        ));
      }
    }
  }

  // Assign partnerId tracking cookie (if exist)
  private function partnerIdCookie() {
    if (!empty($_GET['partnerId'])) {
      setcookie('partnerId', $_GET['partnerId'], array (
        'expires' => time()+3600, // 1 jam
        'path' => '/', 
        'domain' => '.'.$_SERVER['HTTP_HOST'], // leading dot for compatibility or use subdomain
        'secure' => true,     // or false
        'httponly' => false,    // or false
        'samesite' => 'Strict' // None || Lax  || Strict
      ));
    }
  }

  public function delete_custom_form($form_id){
    $custom_form = \Drupal::entityTypeManager()->getStorage('node')->load($form_id);
    if (!$custom_form) {
      return new JsonResponse('invalid form id', 422);
    }
    else if($custom_form->type->entity->get('type')!=='landing_custom_form'){
      return new JsonResponse('invalid form id', 422);
    }
    $custom_form->delete();
    // $custom_form->setUnpublished();
    // $custom_form->save();

    return new JsonResponse([
      'status' => true,
      'message' => 'success'
    ],200);
  }

  /**
   * Handling submitted form from landing page url
   */
  public function form_post(Request $request){
    $referer = $_SERVER['HTTP_REFERER'];
    $landing = null;

    // validate referer. referer = from ami domain? always ok. referer not from ami domain? check !!! 
    if (!str_contains($referer, $_ENV['APP_URL']."/landingpage/")) {

      // if from custom domain or subdomain landing, valid ? skip
      $domain = $this->get_domain();
      if (!empty($domain['type'])) {

        $entity = \Drupal::entityTypeManager()->getStorage('node');
        $query = $entity->getQuery()->condition('status', 1)->condition('type', 'landing');
  
        if ($domain['type'] === 'custom') {
          $query = $query->condition('field_lan_domain', $domain['name']);
        }
        else if ($domain['type'] === 'subdomain') {
          $query = $query->condition('field_lan_subdomain', $domain['name']);
        }
        else {
          // return not found
          return new JsonResponse('bad request (invalid domain)', 400);
        }
  
        $id = reset($query->execute());
  
        // landing is found
        if (!empty($id)) {
          // get landing object
          $landing = \Drupal::entityTypeManager()->getStorage('node')->load($id);
        }
        else{
          // return not found
          return new JsonResponse('bad request (invalid domain)', 400);
        }
  
      }

      // if from embedded form, can be from any domain, validate by "hash". valid ? skip
      else if (!empty($_POST['cst_result_hash'])) {
        
        // validate
        $check_embedded = $this->check_valid_embedded_form_post();
        if ($check_embedded['status']===false) {
          return new JsonResponse('invalid embedded form : ' . $check_embedded['message'], 400);
        }

        // get landing data. not found? error
        $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid'=> $_ENV['FORM_EMBEDDED_LANDING_ID']]);
        $landing = current($landing);
        if(!$landing) {
          return new JsonResponse('invalid embedded form : landing page not found', 400);
        }

        // no longer used
        $keys = ['cst_block_id', 'cst_landing_id', 'cst_expired', 'cst_result_hash'];
        foreach ($keys as $key) {
          unset($_POST[$key]);
        }
      }

      // if from halaman telkom.co.id , valid ? skip
      else if (
        empty($_SERVER["HTTP_PAGE"]) || 
        (!empty($_SERVER["HTTP_PAGE"]) && !str_contains($_ENV["PUBLIC_URL"], $_SERVER["HTTP_PAGE"]))
      ) {
        return new JsonResponse('bad request', 400);
      }
    }

    if (empty($landing)) {
      // get the landing id
      $landing_slug = explode('/', preg_replace("/^.+\/landingpage\//i", '', $referer))[0];
      $alias = \Drupal::service('path_alias.manager')->getPathByAlias("/landing/$landing_slug"); // output /node/id
      
      if (strpos($alias, 'node/')===false) {
        return new JsonResponse('bad request', 400);
      }
  
      $entity = \Drupal::entityTypeManager()->getStorage('node');
  
      $landing_id = explode('node/', $alias)[1];
      $landing = $entity->load($landing_id);
    }
    
    if ($landing===null) {
      return new JsonResponse('bad request', 400);
    }
    else if ($landing->type->entity->get('type') !== 'landing'){
      return new JsonResponse('bad request', 400);
    }

    $form_id = $_POST['form_id'] ?? '';
    $landing_id = $landing->id();
    unset($_POST['form_id']);
    
    if (!empty($form_id)) {
      // this is default form for telkom cms, the id is the string text, the id of custom form is integer
      if (! preg_match("/^\d+$/", $form_id)) {
        $default_form = \Drupal::service('media_upload.landingform_helper')->get_default_forms($form_id);
        if (!empty($default_form)) {
          $form_scheme = json_decode($default_form['form_scheme'], true);
        }
      }
      else{
        $form = Node::load($form_id);
        if($form && $form->bundle()==='landing_custom_form'){
          $form_scheme = json_decode($form->field_lcf_form_scheme->getString(), true);
        }
      }

      if ($form_scheme && $form_scheme['submit']['url']==='mydita') {
        // process & send data to mydita
        return Drupal::service('media_upload.landingform_helper')->send_post_to_mydita($form_id, $form_scheme, $landing);
      }
      unset($landing);
    }

    $form_post = Node::create([
      'type' => 'landing_custom_form_post',
      'title' => "Post data for landing id $landing_id",
      'field_lcfp_form_id' => $form_id,
      'field_lcfp_landing_id' => $landing_id
    ]);
    $form_post->save();
    $form_post_id = $form_post->id(); unset($form_post);

    foreach ($_POST as $input_name => $input_value) {
      $form_post_meta = Node::create([
        'type' => 'landing_custom_form_post_meta',
        'title' => "form meta from post data id $form_post_id",
        'field_lcfpm_form_post_id' => $form_post_id,
        'field_lcfpm_input_name' => $input_name,
        'field_lcfpm_input_value' => $input_value,
      ]);
      $form_post_meta->save();
    }

    return new JsonResponse(['status'=> 200, 'message'=> 'success'], 200);
  }

  private function check_valid_embedded_form_post() {

    // required input
    $keys = ['cst_block_id', 'cst_landing_id', 'cst_expired', 'cst_result_hash'];
    foreach ($keys as $key) {
      if (empty($_POST[$key])) {
        return [
          'status' => false,
          'message' => "required field was not exist"
        ];
      }
    }

    // validate landing id
    if ($_ENV['FORM_EMBEDDED_LANDING_ID'] !== $_POST['cst_landing_id']) {
      return [
        'status' => false,
        'message' => 'invalid landing id'
      ];
    }

    // validate expired form
    if (time() > $_POST['cst_expired']) {
      return [
        'status' => false,
        'message' => 'form was expired'
      ];
    }

    // validate hash (token)
    $result_hash = Crypt::hashBase64(
      $_POST['cst_block_id'] . 
      $_POST['cst_landing_id'] . 
      $_POST['cst_expired'] . 
      $_ENV['FORM_EMBEDDED_SECRET_KEY']
    );

    if ($result_hash !== $_POST['cst_result_hash']) {
      return [
        'status' => false,
        'message' => 'invalid hash token'
      ];
    }

    return [
      'status' => true,
      'message' => ''
    ];
  }

}