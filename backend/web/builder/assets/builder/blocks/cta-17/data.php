<?php

class CTA17 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Component.php";
    require_once BASE_BUILDER_PATH . "/Render.php";

    $component = new Component();
    $component->setButton(array(
      "enable" => true,
      "url"           => "http://example.com",
      "new_window"    => true,
      "label"         => "Get in touch",
      "background"    => "#ffffff",
      "color"         => "#272727",
      "size"          => "is-medium", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-rounded", //[square, rounded, pill]
      "icon"          => 'fas fa-angle-right',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'cta-17',
      "title"      => 'CTA 17',
      "screenshot" => 'cta-17/cta-17.jpg',
      "screenshot_size" => array( 600, 109 ),
      "template"   => '#cta-17',
      "category"   => 'call-to-action',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul Kiri',
            "heading"         => true,
            "value"           => 'Lorem ipsum dolor sit amet',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul Kiri',
            "value"           => 36,
            "min"             => 18,
            "max"             => 72,
          ),
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Konten Kiri',
            "value"           => 'Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus, est deserunt officia adipisci nihil iure modi reprehenderit ad voluptas',
          ),
          "alignment" => array(
            "type"       => 'radio-icon',
            "label"      => 'Alignment Text Kiri',
            "value"      => 'has-text-left',
            "horizontal" => true,
            "options"    => array(
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
          "title2" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul Kanan',
            "heading"         => true,
            "value"           => 'Lorem ipsum dolor sit amet',
          ),
          "title_size2" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul Kanan',
            "value"           => 36,
            "min"             => 18,
            "max"             => 72,
          ),
          "content2" => array(
            "type"            => 'wyswyg',
            "label"           => 'Konten Kanan',
            "value"           => 'Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus, est deserunt officia adipisci nihil iure modi reprehenderit ad voluptas',
          ),
          "alignment2" => array(
            "type"       => 'radio-icon',
            "label"      => 'Alignment Text Kanan',
            "value"      => 'has-text-left',
            "horizontal" => true,
            "options"    => array(
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
            "value"           => false,
          ),
        ),
        "style" => array(
          "fullwidth" => array(
            "type"       => "switch",
            "horizontal" => true,
            "label"      => "Lebar Konten Penuh",
            "value"      => true,
          ),
          "padding" => array(
            "type" => "directional",
            "label" => "Jarak Konten",
            "value" => array(
              "top" => 0,
              "bottom" => 0,
              "left" => 0,
              "right" => 0,
            )
          ),
          "content_color" => array(
            "type" => "select",
            "horizontal" => true,
            "label" => "Warna Konten",
            "value" => "light",
            "options" => array(
              "light" => "Light",
              "dark" => "Dark",
              "default" => "Default"
            )
          ),
          "background" => array(
            "type"            => 'background',
            "value"           => array(
              "backgroundColor"      => "#3597fd",
              "backgroundImage"      => "",
              "backgroundPosition"   => "center",
              "backgroundSize"       => "cover",
              "backgroundRepeat"     => "no-repeat",
              "backgroundAttachment" => "fixed",
            ),
          ),
          "background2" => array(
            "type"            => 'background',
            "value"           => array(
              "backgroundColor"      => "red",
              "backgroundImage"      => "",
              "backgroundPosition"   => "center",
              "backgroundSize"       => "cover",
              "backgroundRepeat"     => "no-repeat",
              "backgroundAttachment" => "fixed",
            ),
          ),
        )
    );
  }

  public function editorTemplate() {
    ob_start(); ?>
    <div
      :id="`block-${id}`"
      class="icl-section icl-section--cta-17"
      :class="`content-${style.content_color.value}`">

      <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
        <div :class="`content-spacer`" :style="{'padding': `${style.padding.value.top}px ${style.padding.value.right}px ${style.padding.value.bottom}px ${style.padding.value.left}px`}">
          <div class="columns is-vcentered" :class="{'row-reverse': data.reverse.value == true }">
            <div :class="`column is-half ${data.alignment.value}`" :style="{
                'backgroundColor'      : style.background.value.backgroundColor,
                'backgroundImage'      : `url(${style.background.value.backgroundImage})`,
                'backgroundRepeat'     : style.background.value.backgroundRepeat,
                'backgroundSize'       : style.background.value.backgroundSize,
                'backgroundPosition'   : style.background.value.backgroundPosition,
                'backgroundAttachment' : style.background.value.backgroundAttachment,
                'padding'              : '50px',
                }">

              <!-- content -->
              <div :class="`section-title font-light mb3`" :style="`--font-size: ${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></div>
              <div ><InlineEditor v-model="data.content.value" /></div>
            </div>
            <div :class="`column is-half ${data.alignment2.value}`" :style="{
                'backgroundColor'      : style.background2.value.backgroundColor,
                'backgroundImage'      : `url(${style.background2.value.backgroundImage})`,
                'backgroundRepeat'     : style.background2.value.backgroundRepeat,
                'backgroundSize'       : style.background2.value.backgroundSize,
                'backgroundPosition'   : style.background2.value.backgroundPosition,
                'backgroundAttachment' : style.background2.value.backgroundAttachment,
                'padding'              : '50px',
                }">

              <!-- content -->
              <div :class="`section-title font-light mb3`" :style="`--font-size: ${data.title_size2.value}px`"><InlineEditor v-model="data.title2.value" /></div>
              <div ><InlineEditor v-model="data.content2.value" /></div>
            </div>
          </div>
        </div>
      </div>

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
    <div
      id="block-<?php echo $id; ?>"
      class="icl-section icl-section--cta-17 content-<?php echo $style->content_color->value; ?>">
      <?php
        $background_style1  = "";
        $background_style1 .= !empty( $style->background->value->backgroundColor ) ? 'background-color:' . $style->background->value->backgroundColor .';' : '';
        $background_style1 .= !empty( $style->background->value->backgroundImage ) ? 'background-image: url(' . $style->background->value->backgroundImage .');' : '';
        if ( !empty( $style->background->value->backgroundImage ) ) {
          $background_style1 .= !empty( $style->background->value->backgroundRepeat ) ? 'background-repeat:' . $style->background->value->backgroundRepeat .';' : '';
          $background_style1 .= !empty( $style->background->value->backgroundSize ) ? 'background-size:' . $style->background->value->backgroundSize .';' : '';
          $background_style1 .= !empty( $style->background->value->backgroundPosition ) ? 'background-position:' . $style->background->value->backgroundPosition .';' : '';
          $background_style1 .= !empty( $style->background->value->backgroundAttachment ) ? 'background-attachment:' . $style->background->value->backgroundAttachment .';' : '';
        }

        $background_style2  = "";
        $background_style2 .= !empty( $style->background2->value->backgroundColor ) ? 'background-color:' . $style->background2->value->backgroundColor .';' : '';
        $background_style2 .= !empty( $style->background2->value->backgroundImage ) ? 'background-image: url(' . $style->background2->value->backgroundImage .');' : '';
        if ( !empty( $style->background2->value->backgroundImage ) ) {
          $background_style2 .= !empty( $style->background2->value->backgroundRepeat ) ? 'background-repeat:' . $style->background2->value->backgroundRepeat .';' : '';
          $background_style2 .= !empty( $style->background2->value->backgroundSize ) ? 'background-size:' . $style->background2->value->backgroundSize .';' : '';
          $background_style2 .= !empty( $style->background2->value->backgroundPosition ) ? 'background-position:' . $style->background2->value->backgroundPosition .';' : '';
          $background_style2 .= !empty( $style->background2->value->backgroundAttachment ) ? 'background-attachment:' . $style->background2->value->backgroundAttachment .';' : '';
        }
      ?>

      <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">

        <div class="content-spacer" style="padding: <?php echo implode(" ", $padding); ?>">
          <div class="columns is-vcentered <?php echo $data->reverse->value == true ? "row-reverse" : ""; ?>">
            <div class="column is-half <?php echo $data->alignment->value; ?>" style="padding: 50px;<?= $background_style1 ?>">
              <div data-aos="fade-up" class="section-title font-light mb3" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></div>
              <div><?php echo $data->content->value; ?></div>
            </div>
            <div class="column is-half <?php echo $data->alignment2->value; ?>" style="padding: 50px;<?= $background_style2 ?>">
              <div data-aos="fade-up" class="section-title font-light mb3" style="--font-size:<?php echo $data->title_size2->value; ?>px"><?php echo format_heading( $data->title2->value); ?></div>
              <div><?php echo $data->content2->value; ?></div>
            </div>
          </div>
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


$CTA17 = new CTA17();
$arrayCode[] = $CTA17->data();
$arrayTemplate[] = $CTA17->editorTemplate();