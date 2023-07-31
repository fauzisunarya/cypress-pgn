<?php

namespace Drupal\restapi_telkom\Helper;

use Drupal;
use Drupal\file\Entity\File;
use Contact8;
use Drupal\Component\Utility\Crypt;

class FormTemplate_helper {

  public function getFormHtml($formUuid, $formId, $formTitle='', $formDescription='', array $template = []) {
    $formTitle = !empty($formTitle) ? $formTitle : 'Form Berlangganan';
    $formDescription = !empty($formDescription) ? $formDescription : 'Silahkan isi form di bawah ini';

    try {

      if (empty($template['html'])) {
        $template['html'] = $this->getHtml($formUuid, $formId, $formTitle, $formDescription);
      }

      if (empty($template['css'])) {
        $template['css'] = $this->getCss();
      }

      if (empty($template['js_head'])) {
        $template['js_head'] = $this->getJs('head');
      }

      if (empty($template['js_bottom'])) {
        $template['js_bottom'] = $this->getJs('bottom');
      }

      $template['html'] = $this->addHiddenInput($template['html']);

      return [
        'template' => [
          'html' => $template['html'],
          'css' => $template['css'],
          'js_head' => $template['js_head'],
          'js_bottom' =>  $template['js_bottom']
        ]
      ];

    } catch (\Throwable $th) {
      return [];
    }

  }

  public function getHtml($formUuid, $formId, $formTitle='', $formDescription='') {
    
    if (! class_exists('Contact8')) {
      $this->loadFormBlock();
    }

    $formTitle = !empty($formTitle) ? $formTitle : 'Form Berlangganan';
    $formDescription = !empty($formDescription) ? $formDescription : 'Silahkan isi form di bawah ini';

    $Contact8 = new Contact8();
    $jsonBlock = json_decode(json_encode($Contact8->data()));

    // change/replace form title based on input
    $jsonBlock->data->title_2->value = $formTitle;
    $jsonBlock->data->subtitle_2->value = $formDescription;
    $jsonBlock->data->form->value = $formId;

    // for identifying landing. Used in block php (also in Contact8)
    global $landing_home_slug;
    $landing = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['uuid'=> $_ENV['FORM_EMBEDDED_LANDING_ID']]);
    $landing = current($landing);
    if($landing) {
      $landing_slug = \Drupal::service('path_alias.manager')->getAliasByPath("/node/{$landing->id()}");
      if (str_starts_with($landing_slug, '/landing/')) {
        $landing_home_slug = str_replace('/landing/', '', $landing_slug);
      }
    }

    
    return $this->minimizeHtml(Contact8::render($formUuid, $jsonBlock->data, $jsonBlock->style));
  }

  public function getCss() {

    if (! class_exists('Contact8')) {
      $this->loadFormBlock();
    }

    $themeHandler = \Drupal::service('theme_handler');
    $themePath    = $_ENV['APP_URL'].'/'.$themeHandler->getTheme($themeHandler->getDefault())->getPath();

    $css = "
      <link rel='stylesheet' href='".get_builder_path('assets/builder/blocks-assets/css/bootstrap.min.css')."'>
      <link rel='stylesheet' href='".get_builder_path('assets/builder/blocks-assets/css/global.css')."'>
      <link rel='stylesheet' href='".get_builder_path('assets/builder/blocks-assets/css/fontawesome.min.css')."'>
      <link rel='stylesheet' href='".get_builder_path('assets/builder/blocks-assets/css/material-icons.min.css')."'>
      <style>".Contact8::renderCSS()."</style>
      <link rel='stylesheet' href='".$themePath."/css/landing-page.css' />
      <link rel='stylesheet' href='".$themePath."/js/vendor/leaflet/leaflet.css' />
      <link type='text/css' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/themes/south-street/jquery-ui.css' rel='stylesheet'> 
      <link rel='stylesheet' type='text/css' href='".$themePath."/js/vendor/signature/jquery.signature.css'>
    ";

    return $this->minimizeHtml($css);
  }

  /**
   * Load block file for rendering form
   */
  private function loadFormBlock() {
    // require file to render blocks
    require_once BASE_BUILDER_PATH . '/helpers/builder_function.php';
    require_once BASE_BUILDER_PATH . '/helpers/builder_helper.php';
    require_once BASE_BUILDER_PATH . '/helpers/font_awesome_helper.php';

    $componentArray = glob(BASE_BUILDER_PATH . '/blocks/contact-08/data.php');
    foreach($componentArray as $item){
      require_once $item;
    }
  }

  /**
   * Add input hidden to html (for security)
   */
  private function addHiddenInput($html) {

    $separator = "<div class='form-submit-control";
    $arr = explode($separator, $html);
    if (count($arr)===2) {

      $block_id = \Drupal::service('uuid')->generate();
      $expired = time()+(60*15);
      $result_hash = $this->hash($block_id . $_ENV['FORM_EMBEDDED_LANDING_ID'] . $expired . $_ENV['FORM_EMBEDDED_SECRET_KEY']);

      // info: cst is "custom"
      $input = "
        <input type='hidden' class='input' name='cst_block_id' value='".$block_id."' />
        <input type='hidden' class='input' name='cst_landing_id' value='".$_ENV['FORM_EMBEDDED_LANDING_ID']."' />
        <input type='hidden' class='input' name='cst_expired' value='".$expired."' />
        <input type='hidden' class='input' name='cst_result_hash' value='".$result_hash."' />
      ";
      $arr[0] .= $this->minimizeHtml($input);

      $html = implode($separator, $arr);
    }

    return $html;
  }

  /**
   * Custom hashing
   */
  public function hash(string $text) {
    return Crypt::hashBase64($text);
  }

  public function getJs($location='head') {
    
    $themeHandler = \Drupal::service('theme_handler');
    $themePath    = $_ENV['APP_URL'].'/'.$themeHandler->getTheme($themeHandler->getDefault())->getPath();

    $js = '';
    if ($location==='head') {
      $js = "<script src='".$themePath."/js/vendor/leaflet/leaflet.js'></script>";
    }
    else {
      $js = "
        <script src='".$themePath."/js/vendor/leaflet/leaflet-search.js'></script>
        <script src='".get_builder_path('assets/builder/src/frontend.js')."'></script>
        <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.0/jquery-ui.min.js'></script>  
        <script type='text/javascript' src='".$themePath."/js/vendor/signature/jquery.signature.min.js'></script>
        <script type='text/javascript' src='".$themePath."/js/vendor/signature/jquery-signature-touch-support.js'></script>
        <script src='".$themePath."/js/scripts-landing-form.js'></script>
      ";
    }

    return $this->minimizeHtml($js);
  }

  public function minimizeHtml($html) {
    $html = str_replace("\"", "'", $html);
    $html = str_replace(["\r\n", "\r", "\n", "  "], ' ', html_entity_decode($html) );
    return preg_replace('!\s+!', ' ', $html); // remove multiple spaces
  }

}