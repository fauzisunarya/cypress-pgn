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
use Feature12;
use Robo\Task\File\Replace;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use stdClass;

/**
 * Process rendering landing page
 */
class TemplatePageController {
  
  // show template page
  public function show($node_id) {

    $page_404 = \Drupal::service('media_upload.page_helper')->page_404();

    // get the landing object
    $page = \Drupal::entityTypeManager()->getStorage('node')->load($node_id);

    if ($page===null) {
      return new Response($page_404);
    }

    $content_type = $page->type->entity->get('type');
    if ($content_type !== 'template_page') {
      return new Response($page_404);
    }

    $template = $page->field_tem_page_template_id->referencedEntities()[0];

    $title = 'template';
    $description = 'description';
    $landing_page_url = '';

    // this is the landing page menu
    $header = json_decode($template->field_tem_website_menu->getString());
    if ($header) {
      foreach ($header as $key => $menu) {
        $header[$key]->single = false;
      }
    }

    // require file to render blocks
    require_once BASE_BUILDER_PATH . '/helpers/builder_function.php';
    require_once BASE_BUILDER_PATH . '/helpers/builder_helper.php';
    require_once BASE_BUILDER_PATH . '/helpers/font_awesome_helper.php';

    // json blocks
    $json_blocks = $page->field_tem_page_blocks->getString();
    $page_blocks = json_decode($json_blocks) ?? [];

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

    // get the global theme style
    $str_website_style = "";
    $website_style = json_decode($template->field_tem_website_style->getString(), true);
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
    $meta = [];
    // print_r($meta);
    $meta['custom_meta_tag'] = '';
    $meta['whatsapp_button'] = '';
    $meta['google_analytics'] = '';
    $meta['google_tagmanager'] = '';
    $meta['facebook_pixel'] = '';
    $meta['google_searchconsole'] = '';
    $meta['custom_style'] = '';
    $meta['custom_script'] = '';

    $whatsapp_button = '';
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
          <link type='text/css' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css' rel='stylesheet'> 
          <link rel='stylesheet' type='text/css' href='".$themePath."/js/vendor/signature/jquery.signature.css'>

        </head>
        <body>
          <!-- Google Tag Manager (noscript) -->
          <noscript><iframe src='https://www.googletagmanager.com/ns.html?id=".$meta['google_tagmanager']."'
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
        <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>  
        <script type='text/javascript' src='".$themePath."/js/vendor/signature/jquery.signature.min.js'></script>

        <script src='".$themePath."/js/scripts-landing-form.js'></script>
      </html>
    ";

    return new Response($html, 200, ['content-type'=>'text/html']);

  }
}