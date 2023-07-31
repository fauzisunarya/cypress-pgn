<?php

class CTA5 {

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
      "size"          => "is-medium", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-radius", //[square, rounded, pill]
      "icon"          => 'fas fa-angle-right',
      "icon_position" => "right"
    ));


    return array(
      "blockID"    => 'cta-05',
      "title"      => 'CTA 5',
      "screenshot" => 'cta-05/cta-05.jpg',
      "screenshot_size" => array( 600, 293 ),
      "template"   => '#cta-05',
      "category"   => 'call-to-action',
        "data" => array(
          "icon" => array(
            "type"            => 'icon',
            "label"           => 'Icon',
            "value"           => 'fas fa-heart',
          ),
          "icon_size" => array(
            "type"  => 'slider',
            "label" => 'Icon Size',
            "horizontal" => true,
            "value" => 60,
            "min"   => 10,
            "max"   => 100
          ),
          "icon_color" => array(
            "type"  => 'color',
            "label" => 'Icon Color',
            "horizontal" => true,
            "value" => '#FA2B56',
          ),
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
          "padding" => array(
            "type" => "directional",
            "label" => "Jarak Konten",
            "value" => array(
              "top" => 150,
              "bottom" => 150,
              "left" => 250,
              "right" => 250,
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
      class="icl-section icl-section--cta-5"
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
          <div class="mb3" :style="{fontSize: `${data.icon_size.value}px`,color: `${data.icon_color.value}`}">
            <Icon v-model="data.icon.value" />
          </div>
          <div class="section-title mb2" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value"/></div>
          <div class="mb3" :style="`font-size:${data.content_size.value}px`"><InlineEditor v-model="data.content.value" /></div>
          <Button :data="data.button.value" />
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
      class="icl-section icl-section--cta-5 content-<?php echo $style->content_color->value; ?>">
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
        <div class="el-icon mb3" style="font-size: <?php echo $data->icon_size->value; ?>px;color: <?php $data->icon_color->value; ?>;color: <?php echo $data->icon_color->value; ?>">
          <i class="<?php echo $data->icon->value; ?>"></i>
        </div>
        <div data-aos="fade-up" class="section-title mb2" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></div>
        <div data-aos="fade-up" data-aos-delay="150" style="font-size:<?php echo $data->content_size->value; ?>px" class="mb3"><?php echo $data->content->value;  ?></div>
        <?php echo render_button($data->button->value,"");  ?>
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



$CTA5 = new CTA5();
$arrayCode[] = $CTA5->data();
$arrayTemplate[] = $CTA5->editorTemplate();