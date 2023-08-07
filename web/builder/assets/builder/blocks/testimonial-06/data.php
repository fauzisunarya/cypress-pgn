<?php

class Testimonial6 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'testimonial-06',
      "title"      => 'Testimonial 6',
      "screenshot" => 'testimonial-06/testimonial-06.jpg',
      "screenshot_size" => array( 600, 308 ),
      "template"   => '#testimonial-06',
      "category"   => 'testimonial',
        "data" => array(
          "title" => array(
            "type"       => 'wyswyg',
            "label"      => 'Judul',
            "value"      => '<strong>Testimonial</strong>',
          ),
          "title_size" => array(
            "type"       => 'slider',
            "label"      => 'Title Size',
            "horizontal" => true,
            "value"      => 48,
            "min"        => 18,
            "max"        => 72,
          ),
          "content" => array(
            "type"  => "wyswyg",
            "label" => "Testimonial",
            "label_title" => "author",
            "value" => "<p>Vedanta is a varied tradition with numerous sub-schools and philosophical views. Vedanta focuses on the study of the Upanishads, and one of its early texts, the Brahma sutras. Regarding yoga or meditation, the Brahma sutras focuses on gaining spiritual knowledge of Brahman, the unchanging absolute reality or Self.</p><p>Here you can read some details about a nifty little lifecycle of your order's journey from the time you place your order to your new treasures arriving at your doorstep.</p>"
          ),
          "content_size" => array(
            "type"  => 'slider',
            "label" => 'Testimonial Text Size',
            "horizontal" => true,
            "value" => 18,
            "min"   => 14,
            "max"   => 48
          ),
          "alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Rata Teks',
            "value"           => 'has-text-left',
            "horizontal" => true,
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
          "author" => array(
            "type" => "text",
            "label" => "Penulis",
            "horizontal" => true,
            "value" => "Jeanna Doe",
          ),
          "author_title" => array(
            "type" => "text",
            "label" => "Author title",
            "horizontal" => true,
            "value" => "Yoga expert",
          ),
          "image" => array(
            "type" => "image",
            "label" => "Image",
            "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/testimonial.jpg',
          ),
          "reverse" => array(
            "type" => "switch",
            "label" => "Tukar Layout",
            "horizontal" => true,
            "value" => false,
          )
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
              "bottom" => 100,
              "left" => 15,
              "right" => 15,
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
              "backgroundColor"      => "#f7f7f7",
              "backgroundImage"      => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/hero/blue.svg',
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
      class="icl-section icl-section--testimonial-6"
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
        <div :class="`content-spacer ${data.alignment.value}`" :style="{'padding': `${style.padding.value.top}px ${style.padding.value.right}px ${style.padding.value.bottom}px ${style.padding.value.left}px`}">
          <div class="columns is-vcentered is-variable is-8" :class="{'row-reverse': data.reverse.value}">
            <div class="column">
              <h2 class="section-title" :style="{'--font-size':`${data.title_size.value}px`}" ><InlineEditor v-model="data.title.value" /></h2>
              <blockquote  :style="{'--font-size':`${data.content_size.value}px`}"><InlineEditor v-model="data.content.value"/></blockquote>
              <div class="author"><InlineEditor v-model="data.author.value"/></div>
              <div class="author-title"><InlineEditor v-model="data.author_title.value"/></div>
            </div>
            <div class="column">
              <img :src="data.image.value" alt="">
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
      class="icl-section icl-section--testimonial-6 content-<?php echo $style->content_color->value; ?>">
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

      <div class="content-spacer <?php echo $data->alignment->value; ?>" style="padding: <?php echo implode(" ", $padding); ?>">
        <div class="columns is-vcentered is-variable is-8 <?php echo $data->reverse->value ? "row-reverse": ""; ?>">
          <div class="column">
            <h2 class="section-title" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></h2>
            <blockquote style="--font-size:<?php echo $data->content_size->value; ?>px"><?php echo $data->content->value; ?></blockquote>
            <div class="author"><?php echo $data->author->value; ?></div>
            <div class="author-title"><?php echo $data->author_title->value; ?></div>
          </div>
          <div class="column">
            <?php $thumbnail_url = strpos( $data->image->value , "/blocks-assets/imgs") || strpos( $data->image->value , "/stock_image") ? $data->image->value : get_image_thumbnail( $data->image->value, "half" ); ?>
            <img src="<?php echo $thumbnail_url; ?>" alt="">
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

$testimonial6 = new Testimonial6();
$arrayCode[] = $testimonial6->data();
$arrayTemplate[] = $testimonial6->editorTemplate();