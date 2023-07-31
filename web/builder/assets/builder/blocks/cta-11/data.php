<?php

class Cta11 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'cta-11',
      "title"      => 'CTA 11',
      "screenshot" => 'cta-11/cta-11.jpeg',
      "screenshot_size" => array( 600, 258 ),
      "template"   => '#cta-11',
      "category"   => 'call-to-action',
        "data" => array(
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
              "background"    => "#007bff",
              "color"         => "#ffffff",
              "size"          => "is-normal", //[small, normal, medium, large]
              "style"         => "is-outline", //[fill, outline ]
              "corner"        => "is-rounded", //[square, rounded, pill]
              "icon"          => '',
              "icon_position" => "right"
            )
          ),
          "alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Rata Judul',
            "horizontal"      => true,
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
              "bottom" => 150,
              "left" => 250,
              "right" => 250,
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
              "backgroundColor"      => "#FFFFFF",
              "backgroundImage"      => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/photo-1542272201-b1ca555f8505.jpeg',
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
            "value" => "rgba(200,0,165,0.27)"
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
      class="icl-section icl-section--cta-11"
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
      <div :class="`page-hero ${data.alignment.value}`">
        <div
          :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }"
          :style="{'padding': `${style.hero_spacing.value.top}px ${style.hero_spacing.value.right}px ${style.hero_spacing.value.bottom}px ${style.hero_spacing.value.left}px`}">
          <h2 class="hero-title" :style="{fontSize:`${data.title_size.value}px`}"><InlineEditor v-model="data.title.value" /></h2>
          <div class="hero-description" ><InlineEditor v-model="data.description.value" /></div>
          <Button :data="data.cta.value" />
        </div>
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
      class="icl-section icl-section--cta-11  content-<?php echo $style->content_color->value; ?>">
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
      <div class="page-hero  <?php echo $data->alignment->value; ?>">
        <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>" style="padding: <?php echo implode(" ", $padding); ?>">
          <h2 data-aos="fade-up" class="hero-title" style="font-size:<?php echo $data->title_size->value; ?>px;"><?php echo format_heading( $data->title->value); ?></h2>
          <div data-aos="fade-up" data-aos-delay="150" class="hero-description"><?php echo $data->description->value; ?></div>
          <?php render_button($data->cta->value, 'data-aos="fade-up" data-aos-delay="300"'); ?>
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



$cta11 = new Cta11();
$arrayCode[] = $cta11->data();
$arrayTemplate[] = $cta11->editorTemplate();