<?php

class Slider7 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Component.php";
    require_once BASE_BUILDER_PATH . "/Render.php";

    $component = new Component();
    $component->setButton(array(
      "enable"        => true,
      "url"           => "http://example.com",
      "new_window"    => true,
      "label"         => "Create yours now",
      "background"    => "#FA2B56",
      "color"         => "#ffffff",
      "size"          => "is-large", // [small, normal, medium, large]
      "style"         => "is-fill", // [fill, outline ]
      "corner"        => "is-rounded", // [square, rounded, pill]
      "icon"          => 'fas fa-angle-right',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'slider-07',
      "title"      => 'slider 07',
      "screenshot" => 'slider-07/slider-07.jpg',
      "screenshot_size" => array( 499, 210 ),
      "template"   => '#slider-07',
      "category"   => 'slider',
      "data" => array(
        "slides" => array(
          "type"       => "repeatable",
          "label"      => "Slide",
          "settings"   => array(
            "title" => array(
              "type"            => 'wyswyg',
              "label"           => 'Judul',
              "heading"         => true,
              "value"           => '<strong>Instant Web using our builder</strong>',
            ),
            "title_size" => array(
              "type"            => 'slider',
              "label"           => 'Ukuran Judul',
              "horizontal"      => true,
              "value"           => 48,
              "min"             => 18,
              "max"             => 72,
            ),
            "content" => array(
              "type"            => 'wyswyg',
              "label"           => 'Sub Judul',
              "value"           => 'Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus, est deserunt officia adipisci nihil iure modi reprehenderit ad voluptas. Libero aut quos quas perferendis, ipsa doloribus fugiat ea',
            ),
            "content_size" => array(
              "type"            => 'slider',
              "label"           => 'Content Size',
              "value"           => 18,
              "min"             => 18,
              "max"             => 72,
            ),
            "button" => $component->getButton(),
            "alignment" => array(
              "type"            => 'radio-icon',
              "label"           => 'Rata Teks',
              "horizontal"      => true,
              "value"           => 'flex-start',
              "options" => array(
                array(
                  "icon" => "format_align_left",
                  "label" => "left",
                  "value" => "flex-start"
                ),
                array(
                  "icon" => "format_align_center",
                  "label" => "center",
                  "value" => "center"
                ),
                array(
                  "icon" => "format_align_right",
                  "label" => "right",
                  "value" => "flex-end"
                )
              )
            ),
            "reverse" => array(
              "type"            => 'switch',
              "label"           => 'Tukar Layout',
              "horizontal"      => true,
              "value"           => true,
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
          ),
          "value" => array(
            array(
              "title" => array(
                "type"            => 'wyswyg',
                "label"           => 'Judul',
                "heading"         => true,
                "value"           => '<strong>Instant Web using our builder</strong>',
              ),
              "title_size" => array(
                "type"            => 'slider',
                "label"           => 'Ukuran Judul',
                "horizontal"      => true,
                "value"           => 48,
                "min"             => 18,
                "max"             => 72,
              ),
              "content" => array(
                "type"            => 'wyswyg',
                "label"           => 'Sub Judul',
                "value"           => 'Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus, est deserunt officia adipisci nihil iure modi reprehenderit ad voluptas. Libero aut quos quas perferendis, ipsa doloribus fugiat ea',
              ),
              "content_size" => array(
                "type"            => 'slider',
                "label"           => 'Content Size',
                "value"           => 18,
                "min"             => 18,
                "max"             => 72,
              ),
              "button" => $component->getButton(),
              "alignment" => array(
                "type"            => 'radio-icon',
                "label"           => 'Rata Teks',
                "horizontal"      => true,
                "value"           => 'flex-start',
                "options" => array(
                  array(
                    "icon" => "format_align_left",
                    "label" => "left",
                    "value" => "flex-start"
                  ),
                  array(
                    "icon" => "format_align_center",
                    "label" => "center",
                    "value" => "center"
                  ),
                  array(
                    "icon" => "format_align_right",
                    "label" => "right",
                    "value" => "flex-end"
                  )
                )
              ),
              "reverse" => array(
                "type"            => 'switch',
                "label"           => 'Tukar Layout',
                "horizontal"      => true,
                "value"           => true,
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
                  "backgroundImage"      => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/photo-1500122497987-96abce325586.jpeg',
                  "backgroundPosition"   => "center",
                  "backgroundSize"       => "cover",
                  "backgroundRepeat"     => "no-repeat",
                  "backgroundAttachment" => "scroll",
                ),
              ),
            ),
            array(
              "title" => array(
                "type"            => 'wyswyg',
                "label"           => 'Judul',
                "heading"         => true,
                "value"           => '<strong>Instant Web using our builder</strong>',
              ),
              "title_size" => array(
                "type"            => 'slider',
                "label"           => 'Ukuran Judul',
                "horizontal"      => true,
                "value"           => 48,
                "min"             => 18,
                "max"             => 72,
              ),
              "content" => array(
                "type"            => 'wyswyg',
                "label"           => 'Sub Judul',
                "value"           => 'Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus, est deserunt officia adipisci nihil iure modi reprehenderit ad voluptas. Libero aut quos quas perferendis, ipsa doloribus fugiat ea',
              ),
              "content_size" => array(
                "type"            => 'slider',
                "label"           => 'Content Size',
                "value"           => 18,
                "min"             => 18,
                "max"             => 72,
              ),
              "button" => $component->getButton(),
              "alignment" => array(
                "type"            => 'radio-icon',
                "label"           => 'Rata Teks',
                "horizontal"      => true,
                "value"           => 'flex-start',
                "options" => array(
                  array(
                    "icon" => "format_align_left",
                    "label" => "left",
                    "value" => "flex-start"
                  ),
                  array(
                    "icon" => "format_align_center",
                    "label" => "center",
                    "value" => "center"
                  ),
                  array(
                    "icon" => "format_align_right",
                    "label" => "right",
                    "value" => "flex-end"
                  )
                )
              ),
              "reverse" => array(
                "type"            => 'switch',
                "label"           => 'Tukar Layout',
                "horizontal"      => true,
                "value"           => true,
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
        "padding" => array(
          "type" => "directional",
          "label" => "Jarak Konten",
          "value" => array(
            "top" => 100,
            "bottom" => 150,
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
      class="icl-section icl-section-slider icl-section-slider-4"
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
        :controls="data.controls.value" :nav="data.nav.value" 
        :slides="data.slides.value"
        :style="{'--item-padding': `${style.padding.value.top}px ${style.padding.value.right}px ${style.padding.value.bottom}px ${style.padding.value.left}px`}">
        <div
          v-for ="item in data.slides.value"
          :class="`slide content-${item.content_color.value}`"
          :style="{
            'backgroundColor'      : item.background.value.backgroundColor,
            'backgroundImage'      : `url(${item.background.value.backgroundImage})`,
            'backgroundRepeat'     : item.background.value.backgroundRepeat,
            'backgroundSize'       : item.background.value.backgroundSize,
            'backgroundPosition'   : item.background.value.backgroundPosition,
            'backgroundAttachment' : item.background.value.backgroundAttachment,
            }">
          <div :class="{
            'container'       : !style.fullwidth.value,
            'container-fluid' : style.fullwidth.value,
            'flex-start'      : item.alignment.value == 'flex-start',
            'center'          : item.alignment.value == 'center',
            'flex-end'        : item.alignment.value == 'flex-end',
            }"
          >
              <div class="columns is-vcentered"  :class="{'row-reverse': item.reverse.value == true }">
                <div class="column is-6">
                  <h2 class="section-title mb2" :style="`--font-size:${item.title_size.value}px`" v-html="item.title.value"></h2>
                  <div class="mb3" :style="`font-size:${item.content_size.value}px`" v-html="item.content.value"></div>
                  <Button :data="item.button.value" />
                </div>
                <div class="column is-6">
                  <!-- empty space -->
                </div>
              </div>
          </div>
        </div>
      </carousel>
    </div>
    <?php
    return ob_get_clean();

  }

  static function render($id, $data, $style) {
    $padding = array(
      "top" => $style->padding->value->top.'px',
      "right" => $style->padding->value->right.'px',
      "bottom" => $style->padding->value->bottom.'px',
      "left" => $style->padding->value->left.'px',
    );
    ob_start();
    ?>
    <div id="block-<?php echo $id; ?>" class="icl-section icl-section-slider icl-section-slider-4 content-<?php echo $style->content_color->value; ?>"  style="--item-padding: <?php echo implode(" ", $padding); ?>">
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
      
      <div
        class="icl-carousel" data-mode="<?php echo $data->mode->value; ?>" data-items="1"  data-controls="<?php echo $data->controls->value; ?>" data-nav="<?php echo $data->nav->value; ?>">
        <?php foreach( $data->slides->value as $item ): ?>
          <?php

            $mobile_slide_bg = null;
            $slide_bg = "";
            $slide_bg .= !empty( $item->background->value->backgroundColor ) ? 'background-color:' . $item->background->value->backgroundColor .';' : '';
            $slide_bg .= !empty( $item->background->value->backgroundImage ) ? 'background-image: url(' . $item->background->value->backgroundImage .');' : '';

            if ( !empty( $item->background->value->backgroundImage ) ) {
              $mobile_slide_bg = strpos(  $item->background->value->backgroundImage , "/blocks-assets/imgs") && !empty($item->background->value->backgroundImage) ?  $item->background->value->backgroundImage : get_image_thumbnail(  $item->background->value->backgroundImage, "half" );
              
              $slide_bg .= !empty( $item->background->value->backgroundRepeat ) ? 'background-repeat:' . $item->background->value->backgroundRepeat .';' : '';
              $slide_bg .= !empty( $item->background->value->backgroundSize ) ? 'background-size:' . $item->background->value->backgroundSize .';' : '';
              $slide_bg .= !empty( $item->background->value->backgroundPosition ) ? 'background-position:' . $item->background->value->backgroundPosition .';' : '';
              $slide_bg .= !empty( $item->background->value->backgroundAttachment ) ? 'background-attachment:' . $item->background->value->backgroundAttachment .';' : '';
            }
          ?>
          <div class="slide content-<?php echo $item->content_color->value; ?>" style="<?php echo $slide_bg; ?> --mobile-slide: url('<?php echo $mobile_slide_bg; ?>')">
            <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?> <?php echo $item->alignment->value; ?>">
              <div class="columns is-vcentered <?php echo $item->reverse->value ? "row-reverse" : ""; ?>">
                <div class="column is-6">
                  <div data-aos="fade-up" class="section-title mb2" style="--font-size:<?php echo $item->title_size->value; ?>px"><?php echo format_heading( $item->title->value); ?></div>
                  <div data-aos="fade-up" data-aos-delay="150" style="font-size:<?php echo $item->content_size->value; ?>px" class="mb3"><?php echo $item->content->value;  ?></div>
                  <?php echo render_button($item->button->value,"");  ?>
                </div>
                <div class="column is-6">
                  <!-- empty space -->
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



$Slider7 = new Slider7();
$arrayCode[] = $Slider7->data();
$arrayTemplate[] = $Slider7->editorTemplate();