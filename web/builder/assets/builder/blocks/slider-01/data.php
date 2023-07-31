<?php

class Slider1 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'slider-01',
      "title"      => 'slider 01',
      "screenshot" => 'slider-01/slider-01.jpg',
      "screenshot_size" => array( 600, 321 ),
      "template"   => '#slider-01',
      "category"   => 'slider',
      "data" => array(
        "slides" => array(
          "type"       => "repeatable",
          "label"      => "Slide",
          "item_title" => "title",
          "settings"   => array(
            "image" => array(
              "type" => "image",
              "label" => "Gambar",
              "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/staf.png'
            ),
            "title" => array(
              "type" => "wyswyg",
              "label" => "Judul Hero",
              "heading" => true,
              "value" => "<strong>A new and better way to build a web</strong>"
            ),
            "title_size" => array(
              "type"            => 'slider',
              "label"           => 'Ukuran Judul',
              "horizontal"      => true,
              "value"           => 48,
              "min"             => 18,
              "max"             => 72,
            ),
            "description" => array(
              "type" => "wyswyg",
              "label" => "Description",
              "value" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium voluptatibus doloremque dicta aliquam sint possimus optio voluptas adipisci odio expedita."
            ),
            
            "cta" => array(
              "type" => "button",
              "label" => "Tombol Aksi",
              "value" => array(
                "enable" => true,
                "url"           => "http://example.com",
                "new_window"    => true,
                "label"         => "Learn More",
                "background"    => "#006EFF",
                "color"         => "#ffffff",
                "size"          => "is-medium", //[small, normal, medium, large]
                "style"         => "is-fill", //[fill, outline ]
                "corner"        => "is-rounded", //[square, rounded, pill]
                "icon"          => '',
                "icon_position" => "right"
              )
            ),
            "alignment" => array(
              "type"            => 'radio-icon',
              "label"           => 'Rata konten',
              "horizontal"      => true,
              "value"           => 'has-text-left',
              "options" => array(
                array(
                  "icon" => "format_align_left",
                  "label" => "left",
                  "value" => "has-text-left"
                ),
                array(
                  "icon" => "format_align_center",
                  "label" => "center",
                  "value" => "has-text-centered"
                ),
                array(
                  "icon" => "format_align_right",
                  "label" => "right",
                  "value" => "has-text-right"
                )
              )

            ),
            "reverse" => array(
              "type"            => 'switch',
              "label"           => 'Tukar Layout',
              "horizontal"      => true,
              "value"           => true,
            )
          ),
          "value" => array(
            array(
              "image" => array(
                "type" => "image",
                "label" => "Gambar",
                "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/staf.png'
              ),
              "title" => array(
                "type" => "wyswyg",
                "label" => "Judul Hero",
                "heading" => true,
                "value" => "<strong>A new and better way to build a web</strong>"
              ),
              "title_size" => array(
                "type"            => 'slider',
                "label"           => 'Ukuran Judul',
                "horizontal"      => true,
                "value"           => 48,
                "min"             => 18,
                "max"             => 72,
              ),
              "description" => array(
                "type" => "wyswyg",
                "label" => "Description",
                "value" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium voluptatibus doloremque dicta aliquam sint possimus optio voluptas adipisci odio expedita."
              ),
              
              "cta" => array(
                "type" => "button",
                "label" => "Tombol Aksi",
                "value" => array(
                  "enable" => true,
                  "url"           => "http://example.com",
                  "new_window"    => true,
                  "label"         => "Learn More",
                  "background"    => "#006EFF",
                  "color"         => "#ffffff",
                  "size"          => "is-medium", //[small, normal, medium, large]
                  "style"         => "is-fill", //[fill, outline ]
                  "corner"        => "is-rounded", //[square, rounded, pill]
                  "icon"          => '',
                  "icon_position" => "right"
                )
              ),
              "alignment" => array(
                "type"            => 'radio-icon',
                "label"           => 'Rata konten',
                "horizontal"      => true,
                "value"           => 'has-text-left',
                "options" => array(
                  array(
                    "icon" => "format_align_left",
                    "label" => "left",
                    "value" => "has-text-left"
                  ),
                  array(
                    "icon" => "format_align_center",
                    "label" => "center",
                    "value" => "has-text-centered"
                  ),
                  array(
                    "icon" => "format_align_right",
                    "label" => "right",
                    "value" => "has-text-right"
                  )
                )

              ),
              "reverse" => array(
                "type"            => 'switch',
                "label"           => 'Tukar Layout',
                "horizontal"      => true,
                "value"           => true,
              )
            )
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
          "value"      => false,
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
      class="icl-section icl-section--cta-13 icl-section-slider"
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
      <carousel
        :id="id"
        :items="1"
        :mode="data.mode.value"
        :controls="data.controls.value"
        :nav="data.nav.value" 
        :slides="data.slides.value"
        :style="{'--item-padding': `${style.hero_spacing.value.top}px ${style.hero_spacing.value.right}px ${style.hero_spacing.value.bottom}px ${style.hero_spacing.value.left}px`}">
        <div :class="`page-hero ${item.alignment.value}`" v-for="item in data.slides.value">
          <div
            :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
            <div class="columns is-vcentered"  :class="{'row-reverse': item.reverse.value == true }">
              <div class="column is-5">
                <h2 class="hero-title" :style="{fontSize:`${item.title_size.value}px`}" v-html="item.title.value"></h2>
                <div class="hero-description" v-html="item.description.value"></div>
                <Button :data="item.cta.value" />
              </div>
              <div class="column is-2"></div>
              <div class="column is-5">
                <img :src="item.image.value" alt="">
              </div>
            </div>
          </div>
        </div>
      </carousel>
    </div>
    <?php
    return ob_get_clean();

  }

  static function render($id, $data, $style, $header) {
    ob_start();
    ?>
    <div
      id="block-<?php echo $id; ?>"
      class="icl-section icl-section--cta-13 icl-section-slider  content-<?php echo $style->content_color->value; ?>">
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
      <?php
        $padding = array(
          "top" => $style->hero_spacing->value->top.'px',
          "right" => $style->hero_spacing->value->right.'px',
          "bottom" => $style->hero_spacing->value->bottom.'px',
          "left" => $style->hero_spacing->value->left.'px',
        );
      ?>
      <div class="icl-carousel" data-mode="<?php echo $data->mode->value; ?>" data-items="1"  data-controls="<?php echo $data->controls->value; ?>" data-nav="<?php echo $data->nav->value; ?>" style="--item-padding: <?php echo implode(" ", $padding); ?>">
        <?php foreach( $data->slides->value as $item ): ?>
        <div class="page-hero  <?php echo $item->alignment->value; ?>">
          <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">
            <div class="columns is-vcentered <?php echo $item->reverse->value == true ? "row-reverse" : ""; ?>">
              <div class="column is-5">
                <h2  data-aos="fade-up" data-aos-detail="150" class="hero-title" style="font-size:<?php echo $item->title_size->value; ?>px;"><?php echo format_heading( $item->title->value); ?></h2>
                <div  data-aos="fade-up" data-aos-detail="300" class="hero-description"><?php echo $item->description->value; ?></div>
                <?php render_button($item->cta->value, ' data-aos="fade-up" data-aos-detail="450"'); ?>
              </div>
              <div class="column is-2"></div>
              <div class="column is-5">
                <?php if(!empty($item->image->value)): ?>
                  <?php $thumbnail_url = strpos( $item->image->value , "/blocks-assets/imgs") || strpos( $item->image->value , "/stock_image") ? $item->image->value : get_image_thumbnail( $item->image->value, "half" ); ?>
                  <img  data-aos="fade-up" src="<?php echo $thumbnail_url; ?>" alt="<?php echo strip_tags($item->title->value); ?>">
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php
    return ob_get_clean();
  }

  static function renderCSS(){
    return file_get_contents('style.css', FILE_USE_INCLUDE_PATH);
  }
}



$Slider1 = new Slider1();
$arrayCode[] = $Slider1->data();
$arrayTemplate[] = $Slider1->editorTemplate();