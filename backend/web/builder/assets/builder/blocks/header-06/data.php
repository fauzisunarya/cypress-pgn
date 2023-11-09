<?php

class Header6 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'header-06',
      "title"      => 'Header 6',
      "screenshot" => 'header-06/header-06.jpeg',
      "screenshot_size" => array( 600, 54 ),
      "template"   => '#header-06',
      "category"   => 'header',
        "data" => array(
          "logo" => array(
            "type"            => 'image',
            "description"     => 'Lebar & Tinggi Maksimum Logo 200px',
            "label"           => 'Logo',
            "value"           => $this->base_url . 'assets/builder/blocks-assets/imgs/telkom-logo.png',
          ),
          "logo_url" => array(
            "type"            => 'text',
            "horizontal"      => true,
            "label"           => 'Upload Logo',
            "input_type"      => 'link',
          ),
          "cta" => array(
            "type" => "button",
            "label" => "Tombol Aksi",
            "value" => array(
              "enable" => true,
              "url"           => "http://example.com",
              "new_window"    => true,
              "label"         => "+6281-234-5678",
              "background"    => "#007bff",
              "color"         => "#ffffff",
              "size"          => "is-normal", //[small, normal, medium, large]
              "style"         => "is-fill", //[fill, outline ]
              "corner"        => "is-rounded", //[square, rounded, pill]
              "icon"          => 'fas fa-phone',
              "icon_position" => "left"
            )
          ),
        ),
        "style" => array(
          "fullwidth" => array(
            "type"       => "switch",
            "horizontal" => true,
            "label"      => "Lebar Konten Penuh",
            "value"      => false,
          ),
          "vertical_spacing" => array(
            "type" => "slider",
            "horizontal" => true,
            "label" => "Vertical Spacing",
            "value" => 30,
            "min" => 0,
            "max" => 60
          ),
          "background_color" => array(
            "type" => "color",
            "horizontal" => true,
            "label" => "Warna Latar",
            "value" => "#FFFFFF"
          ),
          "content_color" => array(
            "type" => "select",
            "horizontal" => true,
            "label" => "Warna Konten",
            "value" => "default",
            "options" => array(
              "light" => "Light",
              "dark" => "Dark",
              "default" => "Default"
            )
          )
        )
    );
  }

  public function editorTemplate() {
    ob_start(); ?>
    <div
      :id="`block-${id}`"
      class="icl-header icl-header--style-6"
      :class="`content-${style.content_color.value}`"
      :style="{'backgroundColor': style.background_color.value}">
      <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
        <nav
          class="navbar"
          role="navigation"
          aria-label="main navigation">

          <div class="navbar-brand">
            <a class="navbar-item brand" @click.prevent
              :style="{paddingTop: style.vertical_spacing.value+'px', paddingBottom: style.vertical_spacing.value+'px'}">
              <img :src="$store.state.project.logo">
            </a>
          </div>
          
          <div class="navbar-end items-center">
            <Button :data="data.cta.value" />
          </div>
        </nav>
      </div>
    </div>
    <?php
    return ob_get_clean();

  }

  static function render($id, $data, $style, $header) {
    ob_start();
    ?>
    <header
      id="block-<?php echo $id; ?>"
      class="icl-header icl-header--style-6  content-<?php echo $style->content_color->value; ?>"
      style="background-color: <?php echo $style->background_color->value; ?>">
      <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">
      <nav
        class="navbar"
        role="navigation"
        aria-label="main navigation">

        <div class="navbar-brand">
          <?php $logo_url = !empty($data->logo_url->value) ? $data->logo_url->value : user_site_base_url(); ?>
          <a class="navbar-item brand" href="<?php echo $logo_url; ?>"
            style="padding-top: <?php echo $style->vertical_spacing->value.'px';?>; padding-bottom: <?php echo $style->vertical_spacing->value.'px';?>">
            <img src="<?php echo $data->logo->value; ?>">
          </a>
        </div>

        <div class="navbar-end items-center">
          <?php render_button($data->cta->value, ''); ?>
        </div>
      </nav>
      <?php echo !$style->fullwidth->value ? "</div>":""; ?>
    </header>
  <?php
    return ob_get_clean();
  }

  static function renderCSS(){
    return file_get_contents('style.css', FILE_USE_INCLUDE_PATH);
  }
}




$header6 = new Header6();
$arrayCode[] = $header6->data();
$arrayTemplate[] = $header6->editorTemplate();