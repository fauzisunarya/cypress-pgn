<?php

class CTA15 {

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
      "label"         => "Shop Now",
      "background"    => "#007bff",
      "color"         => "#ffffff",
      "size"          => "is-medium", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-radius", //[square, rounded, pill]
      "icon"          => 'fas fa-shopping-cart',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'cta-15',
      "title"      => 'CTA 15',
      "screenshot" => 'cta-15/cta-15.jpg',
      "screenshot_size" => array( 600, 208 ),
      "template"   => '#cta-15',
      "category"   => 'call-to-action',
        "data" => array(
          "title_top" => array(
            "type"            => 'text',
            "label"           => 'Top Title',
            "horizontal"      => true,
            "value"           => 'Storewide discount',
          ),
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '20% OFF',
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
            "label"           => 'Rata judul',
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
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Konten',
            "value"           => '<p>Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus, est deserunt officia adipisci nihil iure modi reprehenderit ad voluptas. Libero aut quos quas perferendis, ipsa doloribus fugiat ea</p>',
          ),
          "button" => $component->getButton(),
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
              "backgroundImage"      => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/indihome-promo.png',
              "backgroundPosition"   => "center left",
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
      class="icl-section icl-section--cta-1"
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
          <div class="columns is-vcentered row-reverse">
            <div :class="`column is-half ${data.alignment.value}`">
              <div class="section-title--top"><InlineEditor v-model="data.title_top.value"/></div>
              <div :class="`section-title`" :style="`--font-size: ${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></div>
              <div class="mt2 mb3" ><InlineEditor v-model="data.content.value" /></div>
              <Button :data="data.button.value" />
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
      class="icl-section icl-section--cta-1 content-<?php echo $style->content_color->value; ?>">
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

        <div class="content-spacer" style="padding: <?php echo implode(" ", $padding); ?>">
          <div class="columns is-vcentered row-reverse">
            <div class="column is-half <?php echo $data->alignment->value; ?>">
              <div :class="`section-title`" :style="`--font-size: ${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></div>

              <div data-aos="fade-up" class="section-title--top"><?php echo $data->title_top->value; ?></div>
              <div data-aos="fade-up" data-aos-delay="150" class="section-title" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></div>
              <div data-aos="fade-up" data-aos-delay="300" class="mt2 mb3"><?php echo $data->content->value;  ?></div>
              
              <?php render_button($data->button->value, 'data-aos="fade-up" data-aos-delay="450"'); ?>
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



$CTA15 = new CTA15();
$arrayCode[] = $CTA15->data();
$arrayTemplate[] = $CTA15->editorTemplate();