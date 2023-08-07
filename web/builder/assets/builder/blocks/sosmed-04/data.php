<?php

class Sosmed4 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'sosmed-04',
      "title"      => 'Social Media 04',
      "screenshot" => 'sosmed-04/sosmed-04.jpg',
      "screenshot_size" => array( 950, 462 ),
      "template"   => '#sosmed-04',
      "category"   => 'sosmed',
        "data" => array(
          "image" => array(
            "type" => "image",
            "label" => "Gambar",
            "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/person.jpg'
          ),
          "image_style" => array(
            "type" => "select",
            "label" => "Gaya gambar",
            "value" => "circle",
            "options" => array(
              "circle" => "Lingkaran",
              "rounded" => "Ujung melengkung",
              "square" => "Sudut lancip"
            )
          ),
          "username" => array(
            "type"            => 'wyswyg',
            "label"           => 'Title',
            "heading"         => true,
            "value"           => '@username',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "horizontal"      => true,
            "value"           => 22,
            "min"             => 18,
            "max"             => 72,
            "horizontal" => true,
          ),
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Konten',
            "value"           => '<p>To a degree, some methods for creating work, such as employing intuition, are shared across the disciplines within the applied arts and fine art. Mark Getlein, writer, suggests the principles of design are almost instinctive, built-in, natural, and part of our sense of rightness. However, the intended application and context of the resulting works will vary greatly.</p>',
          ),
          "social" => array(
            "type" => "repeatable",
            "label" => "Social Icons",
            "item_title" => "label",
            "settings" => array(
              "label"=> array(
                "type" => "text",
                "horizontal" => true,
                "label"=> "Label",
                "value" => ""
              ),
              "url" => array(
                "type" => "text",
                "horizontal" => true,
                "label" => "URL",
                "value" => "#"
              ),
              "icon" => array(
                "type" => "icon",
                "horizontal" => true,
                "label" => "Icon",
                "value" => "fab fa-facebook"
              )
            ),
            "value" => array(
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => ""
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                ),
                "icon" => array(
                  "type" => "icon",
                  "horizontal" => true,
                  "label" => "Icon",
                  "value" => "fab fa-facebook"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => ""
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                ),
                "icon" => array(
                  "type" => "icon",
                  "horizontal" => true,
                  "label" => "Icon",
                  "value" => "fab fa-twitter"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => ""
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#",
                ),
                "icon" => array(
                  "type" => "icon",
                  "horizontal" => true,
                  "label" => "Icon",
                  "value" => "fab fa-instagram"
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
              "top" => 50,
              "bottom" => 25,
              "left" => 100,
              "right" => 100,
            )
          ),
          "content_color" => array(
            "type"       => "select",
            "horizontal" => true,
            "label"      => "Warna Konten",
            "value"      => "light",
            "options"    => array(
              "light"   => "Light",
              "dark"    => "Dark",
              "default" => "Default"
            )
          ),
          "background" => array(
            "type"            => 'background',
            "value"           => array(
              "backgroundColor"      => "#000000",
              "backgroundImage"      => '',
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
      class="icl-section icl-section--sosmed-04"
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
        <div :class="`content-spacer has-text-centered`" :style="{'padding': `${style.padding.value.top}px ${style.padding.value.right}px ${style.padding.value.bottom}px ${style.padding.value.left}px`}">
          <img :src="data.image.value" :alt="data.username.value" :class="`image-style--${data.image_style.value}`">

          <div class="navbar-social-icons" style="margin-top: 20px">
            <a href="#" v-for="social in data.social.value" :href="social.url.value"><Icon v-model="social.icon.value" /><span v-if="social.label.value"><InlineEditor v-model="social.label.value"/></span></a>
          </div>
          
          <span class="section-title--top" :style="{fontSize: `${data.title_size.value}px`}"><InlineEditor v-model="data.username.value"/></span>

          <div ><InlineEditor v-model="data.content.value" /></div>
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
      class="icl-section icl-section--sosmed-04 content-<?php echo $style->content_color->value; ?>">
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
      <div class="content-spacer has-text-centered" style="padding: <?php echo implode(" ", $padding); ?>">
        <?php
          $thumbnail_url = strpos( $data->image->value , "/blocks-assets/imgs") || strpos( $data->image->value , "/stock_image") ? $data->image->value : get_image_thumbnail( $data->image->value, "small" );
        ?>
        <img src="<?php echo $thumbnail_url; ?>" alt="<?php echo strip_tags( $data->username->value); ?>" class="image-style--<?php echo $data->image_style->value; ?>">

        <div class="navbar-social-icons" style="margin-top: 20px">
          <?php foreach( $data->social->value as $social ): ?>
          <a  href="<?php echo $social->url->value; ?>">
            <?php echo get_font_awesome_svg($social->icon->value,"el-icon"); ?>
            <?php if (!empty($social->label->value)): ?>
            <span><?php echo $social->label->value; ?></span>
            <?php endif; ?>
          </a>
          <?php endforeach; ?>
        </div>

        <span data-aos="fade-up" class="section-title--top" style="font-size:<?php echo $data->title_size->value; ?>px;"><?php echo $data->username->value;  ?></span>

        <div data-aos="fade-up" data-aos-delay="300"><?php echo $data->content->value;  ?></div>
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

$Sosmed4 = new Sosmed4();
$arrayCode[] = $Sosmed4->data();
$arrayTemplate[] = $Sosmed4->editorTemplate();