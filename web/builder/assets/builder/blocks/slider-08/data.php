<?php

class Slider8 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'slider-08',
      "title"      => 'slider 08',
      "screenshot" => 'slider-08/slider-08.jpg',
      "screenshot_size" => array( 837, 164 ),
      "template"   => '#slider-08',
      "category"   => 'slider',
      "data" => array(
        "column" => array(
          "type"  => 'slider',
          "horizontal" => true,
          "label" => 'Kolom',
          "min"   => 1,
          "max"   => 6,
          "value" => 3,
        ),
        "slides" => array(
          "type"       => "repeatable",
          "label"      => "Slide",
          "settings"   => array(
            "image" => array(
              "type" => "image",
              "label" => "Gambar",
              "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/slide-3.jpg'
            ),
            "url" => array(
              "type" => "text",
              "horizontal" => true,
              "label" => "URL",
              "value" => "#"
            )
          ),
          "value" => array(

            array(
              "image" => array(
                "type" => "image",
                "label" => "Gambar",
                "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/slide-2.jpg'
              ),
              "url" => array(
                "type" => "text",
                "horizontal" => true,
                "label" => "URL",
                "value" => "#"
              )
            ),
           
            array(
              "image" => array(
                "type" => "image",
                "label" => "Gambar",
                "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/slide-1.jpg'
              ),
              "url" => array(
                "type" => "text",
                "horizontal" => true,
                "label" => "URL",
                "value" => "#"
              )
            ),

            array(
              "image" => array(
                "type" => "image",
                "label" => "Gambar",
                "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/slide-3.jpg'
              ),
              "url" => array(
                "type" => "text",
                "horizontal" => true,
                "label" => "URL",
                "value" => "#"
              )
            ),
          )
        ),
        "sep-1" => array(
          "type" => 'spacer'
        ),
        "mode" => array(
          "type"            => 'select',
          "label"           => 'Animasi Slider',
          "horizontal"      => true,
          "value"           => 'carousel',
          "options"         => array(
            "carousel"      => 'Slide',
            "gallery"       => 'Fade'
          )
        ),
        "controls" => array(
          "type"            => 'switch',
          "label"           => 'Munculkan Panah',
          "horizontal" => true,
          "value"           => false,
        ),
        "nav" => array(
          "type"            => 'switch',
          "label"           => 'Munculkan titik navigasi',
          "horizontal" => true,
          "value"           => true,
        ),
      ),
      "style" => array(
        "fullwidth" => array(
          "type"       => "switch",
          "horizontal" => true,
          "label"      => "Lebar Konten Penuh",
          "value"      => true,
        ),
        "navbar_spacing" => array(
          "type" => "slider",
          "horizontal" => true,
          "label" => "Jarak vertikal navigasi",
          "value" => 30,
          "min" => 0,
          "max" => 60
        ),
        "hero_spacing" => array(
          "type" => "directional",
          "label" => "Jarak konten Hero",
          "value" => array(
            "top" => 100,
            "bottom" => 100,
            "left" => 0,
            "right" => 0,
          )
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
        ),
        "background" => array(
          "type"            => 'background',
          "value"           => array(
            "backgroundColor"      => "#FFFFFF",
            "backgroundImage"      => "",
            "backgroundPosition"   => "center",
            "backgroundSize"       => "cover",
            "backgroundRepeat"     => "no-repeat",
            "backgroundAttachment" => "scroll",
          ),
        ),
        "overlay_color" => array(
          "type" => "overlay",
          "horizontal" => true,
          "label" => "Background Overlay",
          "value" => "rgba(0,0,0,0)"
        ),
        "divider" => array(
          "type" => "divider",
          "label" => "Pembatas antar block",
          "value" => array(
            "top" => array(
              "type"   => 'none',
              "color"  => 'white',
              "width"  => 100,
              "height" => 100,
              "invert" => false,
            ),
            "bottom" => array(
              "type"   => 'none',
              "color"  => 'white',
              "width"  => 100,
              "height" => 100,
              "invert" => false,
            ),
          )
        ),
      )
    );
  }

  public function editorTemplate() {
    ob_start(); ?>
    <div
      :id="`block-${id}`"
      class="icl-section icl-section-slider icl-section-slider-2"
      :class="`content-${style.content_color.value}`">
      <div class="icl-section__bg" :style="{
        'backgroundColor'      : style.background.value.backgroundColor,
        'backgroundImage'      : `url(${style.background.value.backgroundImage})`,
        'backgroundRepeat'     : style.background.value.backgroundRepeat,
        'backgroundSize'       : style.background.value.backgroundSize,
        'backgroundPosition'   : style.background.value.backgroundPosition,
        'backgroundAttachment' : style.background.value.backgroundAttachment,
        }">
        <Overlay :data="style.overlay_color.value"/>
        <Divider :data="style.divider.value"/>
      </div>
      <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
        <carousel
          :id="id"
          :items="data.column.value"
          :mode="data.mode.value"
          :controls="data.controls.value" :nav="data.nav.value" 
          :slides="data.slides.value">
          <a v-for="item in data.slides.value" href="#" :class="`page-hero`">
            <img :src="item.image.value" alt="">
          </a>
        </carousel>
      </div>
    </div>
    <?php
    return ob_get_clean();

  }

  static function render($id, $data, $style, $header) {
    ob_start();
    ?>
    <div
      id="block-<?php echo $id; ?>"
      class="icl-section icl-section-slider icl-section-slider-2  content-<?php echo $style->content_color->value; ?>">
      <?php
        $background_style  = "";
        $background_style .= !empty( $style->background->value->backgroundColor ) ? 'background-color:' . $style->background->value->backgroundColor .';' : '';
        $background_style .= !empty( $style->background->value->backgroundImage ) ? 'background-image: url(' . $style->background->value->backgroundImage .');' : '';
        if ( !empty( $style->background->value->backgroundImage ) ) {
          $background_style .= !empty( $style->background->value->backgroundRepeat ) ? 'background-repeat:' . $style->background->value->backgroundRepeat .';' : '';
          $background_style .= !empty( $style->background->value->backgroundSize ) ? 'background-size:' . $style->background->value->backgroundSize .';' : '';
          $background_style .= !empty( $style->background->value->backgroundPosition ) ? 'background-position:' . $style->background->value->backgroundPosition .';' : '';
          $background_style .= !empty( $style->background->value->backgroundAttachment ) ? 'background-attachment:' . $style->background->value->backgroundAttachment .';' : '';
        }
      ?>
      <div class="icl-section__bg" style="<?php echo $background_style; ?>">
        <?php $render = new Render();?>
        <?php echo $render->render_overlay( $style->overlay_color->value ); ?>
        <?php echo $render->render_divider( $style->divider->value ); ?>
      </div>
      <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">
        <div class="icl-carousel" data-mode="<?php echo $data->mode->value; ?>" data-items="<?= $data->column->value; ?>"  data-controls="<?php echo $data->controls->value; ?>" data-nav="<?php echo $data->nav->value; ?>">
          <?php foreach( $data->slides->value as $item ): ?>
            <?php if ($item->url->value !== "#" && !empty($item->url->value)): ?>
              <a href="<?= $item->url->value; ?>" class="page-hero">
                <img data-aos="fade-up" src="<?php echo $item->image->value; ?>">
              </a>
            <?php else: ?>
              <div class="page-hero">
                <img data-aos="fade-up" src="<?php echo $item->image->value; ?>">
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php
    return ob_get_clean();
  }

  static function renderCSS(){
    return file_get_contents('style.css', FILE_USE_INCLUDE_PATH);
  }
}



$Slider8 = new Slider8();
$arrayCode[] = $Slider8->data();
$arrayTemplate[] = $Slider8->editorTemplate();