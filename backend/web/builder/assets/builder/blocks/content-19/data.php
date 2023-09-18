<?php

class Content19 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'content-19',
      "title"      => 'Content 19',
      "screenshot" => 'content-19/content-19.jpg',
      "screenshot_size" => array( 600, 293 ),
      "template"   => '#content-19',
      "category"   => 'content',
        "data" => array(
          
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Top Destinations</strong>',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "value"           => 48,
            "min"             => 18,
            "max"             => 72,
          ),
          "toptext" => array(
            "type"            => 'wyswyg',
            "label"           => 'Sub Judul',
            "value"           => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur nemo molestiae laboriosam in unde atque veritatis.</p>',
          ),
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Sub Judul',
            "value"           => '<p>To a degree, some methods for creating work, such as employing intuition, are shared across the disciplines within the applied arts and fine art. Mark Getlein, writer, suggests the principles of design are almost instinctive, built-in, natural, and part of our sense of rightness. However, the intended application and context of the resulting works will vary greatly.</p>',
          ),
          "alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Rata Teks',
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

          "images" => array(
            "type" => "repeatable",
            "label" => "Gallery",
            "settings"=> array(
              "image" => array(
                "type" => "image",
                "label" => "Gambar",
                "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1531975474574-e9d2732e8386.jpg"
              ),
              "caption" => array(
                "type" => "text",
                "label" => "Keterangan Gambar",
                "value" => "Keterangan Gambar"
              )
            ),
            "value" => array(
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Gambar",
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1531975474574-e9d2732e8386.jpg"
                ),
                "caption" => array(
                  "type" => "text",
                  "label" => "Keterangan Gambar",
                  "value" => "Keterangan Gambar"
                )
              ),
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Gambar",
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1545922421-2417f6beb2b9.jpg"
                ),
                "caption" => array(
                  "type" => "text",
                  "label" => "Keterangan Gambar",
                  "value" => "Keterangan Gambar"
                )
              ),
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Gambar",
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1505993597083-3bd19fb75e57.jpg"
                ),
                "caption" => array(
                  "type" => "text",
                  "label" => "Keterangan Gambar",
                  "value" => "Keterangan Gambar"
                )
              ),
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Gambar",
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1501179691627-eeaa65ea017c.jpg"
                ),
                "caption" => array(
                  "type" => "text",
                  "label" => "Keterangan Gambar",
                  "value" => "Keterangan Gambar"
                )
              ),
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
          "padding" => array(
            "type" => "directional",
            "label" => "Jarak Konten",
            "value" => array(
              "top" => 100,
              "bottom" => 100,
              "left" => 100,
              "right" => 100,
            )
          ),
          "content_color" => array(
            "type"       => "select",
            "horizontal" => true,
            "label"      => "Warna Konten",
            "value"      => "default",
            "options"    => array(
              "light"   => "Light",
              "dark"    => "Dark",
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
            "type"       => "overlay",
            "horizontal" => true,
            "label"      => "Background Overlay",
            "value"      => "rgba(0,0,0,0)"
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
      class="icl-section icl-section--content-19"
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
          <div class="columns is-variable is-8">
            <div class="column is-6">
              <h2 class="section-title mb3" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></h2>
              <span class="section-title--top" ><InlineEditor v-model="data.toptext.value" /></span>
            </div>
            <div class="column is-6">
              <div ><InlineEditor v-model="data.content.value" /></div>
            </div>
          </div>
        </div>
      </div>
    
      <div class="icl-image-gallery">
        <a :href="item.image.value" v-for="item in data.images.value" title="item.caption.value">
          <span class="image-ratio"><img :src="item.image.value" alt="item.caption.value"></span>
        </a>
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
      class="icl-section icl-section--content-19 content-<?php echo $style->content_color->value; ?>">
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
          <div class="columns is-variable is-8">
            <div class="column is-6">
              <h2 data-aos="fade-up" class="section-title mb2" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></h2>
              <span data-aos="fade-up" data-aos-delay="150" class="section-title--top"><?php echo $data->toptext->value;  ?></span>
            </div>
            <div class="column is-6">
              <div data-aos="fade-up" data-aos-delay="300"><?php echo $data->content->value;  ?></div>
            </div>
          </div>
        </div>
      </div>
      <div class="icl-image-gallery" data-aos="fade-up" data-aos-delay="750">
        <?php foreach( $data->images->value as $item ): ?>
        <?php
          $image = $item->image->value;
          $thumbnail_url = strpos( $image , "/blocks-assets/imgs") || strpos( $image , "/stock_image") ? $image : get_image_thumbnail( $image, "medium" );
        ?>
        <a href="<?php echo $item->image->value; ?>" title="<?php echo $item->caption->value; ?>">
          <span class="image-ratio">
            <img class="lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php echo $thumbnail_url; ?>" alt="<?php echo strip_tags($item->caption->value); ?>">
          </span>
        </a>
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

$content19 = new Content19();
$arrayCode[] = $content19->data();
$arrayTemplate[] = $content19->editorTemplate();