<?php

class Content20 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'content-20',
      "title"      => 'Content 20',
      "screenshot" => 'content-20/content-20.jpg',
      "screenshot_size" => array( 600, 299 ),
      "template"   => '#content-20',
      'runScript'  => 'masonry',
      "category"   => 'content',
        "data" => array(
          
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "horizontal"      => true,
            "value"           => 48,
            "min"             => 18,
            "max"             => 72,
          ),
          "alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Title Alignment',
            "horizontal" => true,
            "value"           => 'has-text-centered',
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
          "gallery" => array(
            "type" => "gallery",
            "label" => "Image Gallery",
            "value" => array(
              $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1531975474574-e9d2732e8386.jpg",
              $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1545922421-2417f6beb2b9.jpg",
              $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1505993597083-3bd19fb75e57.jpg",
              $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1501179691627-eeaa65ea017c.jpg",
              $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1476158085676-e67f57ed9ed7.jpg",
              $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1550664255-94d114340500.jpg",
              $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1523592121529-f6dde35f079e.jpg",
              $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1469967700385-5b0140e16e33.jpg",
            )
          ),
          "column" => array(
            "type" => "slider",
            "label" => "Kolom",
            "horizontal" => true,
            "value" => 4,
            "min" => 2,
            "max" => 6
          ),
          "gutter" => array(
            "type" => "slider",
            "label" => "Jarak antar item",
            "horizontal" => true,
            "value" => 0,
            "min" => 0,
            "max" => 60
          ),
          "radius" => array(
            "type" => "slider",
            "label" => "Border radius",
            "horizontal" => true,
            "value" => 0,
            "min" => 0,
            "max" => 10
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
      class="icl-section icl-section--content-20"
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
        <div :class="`content-spacer`" :style="{'padding': `${style.padding.value.top}px ${style.padding.value.right}px ${style.padding.value.bottom}px ${style.padding.value.left}px`}">
          <div v-show="data.title.value" :class="`section-title ${data.alignment.value} mb4`" v-html="data.title.value" :style="`{--font-size: ${data.title_size.value}px}`"></div>
          <div class="image-gallery image-gallery--grid icl-image-gallery" :data-columns="data.column.value" :style="{'--border-radius':`${data.radius.value}px` }">
            <a :href="image" v-for="image in data.gallery.value" class="image-item" :style="{padding:`${data.gutter.value/2}px`}">
              <span class="image-item-ratio">
                <img :src="image">
              </span>
            </a>
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
      class="icl-section icl-section--content-20 content-<?php echo $style->content_color->value; ?>">
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

        $thumb = get_base_url() . 'img/thumbnail/500/';
      ?>
      <div class="icl-section__bg" style="<?php echo $background_style; ?>">
        <?php $render = new Render();?>
        <?php echo $render->render_overlay( $style->overlay_color->value ); ?>
        <?php echo $render->render_divider( $style->divider->value ); ?>
      </div>

      <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">

      <div class="content-spacer" style="padding: <?php echo implode(" ", $padding); ?>">
        <?php if (!empty($data->title->value)): ?>
        <div data-aos="fade-up" class="section-title <?php echo $data->alignment->value; ?> mb4" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></div>
        <?php endif; ?>
        <div class="image-gallery image-gallery--grid icl-image-gallery" data-columns="<?php echo $data->column->value; ?>" style="border-radius: <?php echo $data->radius->value; ?>px">
          <?php foreach( $data->gallery->value as $image): ?>
          <?php
            $thumbnail_url = strpos( $image , "/blocks-assets/imgs") || strpos( $image , "/stock_image") ? $image : get_image_thumbnail( $image, "small" );
          ?>
          <a class="image-item" href="<?php echo $image; ?>" style="padding:<?php echo $data->gutter->value/2; ?>px">
            <span class="image-item-ratio">
              <img class="lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php echo $thumbnail_url ?>">
            </span>
          </a>
          <?php endforeach; ?>
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

$content20 = new Content20();
$arrayCode[] = $content20->data();
$arrayTemplate[] = $content20->editorTemplate();