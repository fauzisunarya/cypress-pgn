<?php

class CTA14 {

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
      "label"         => "Pesan Online",
      "background"    => "#FA2B56",
      "color"         => "#ffffff",
      "size"          => "is-normal", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-rounded", //[square, rounded, pill]
      "icon"          => 'fas fa-angle-right',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'cta-14',
      "title"      => 'CTA 14',
      "screenshot" => 'cta-14/cta-14.jpg',
      "screenshot_size" => array( 600, 204 ),
      "template"   => '#cta-14',
      "category"   => 'call-to-action',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => 'Siap untuk mendaki gunung?',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "horizontal"      => true,
            "value"           => 48,
            "min"             => 18,
            "max"             => 72,
          ),
          "button" => $component->getButton(),
          "content" => array(
            "type" => "wyswyg",
            "label" => "Konten",
            "value" => "<p>Atau telepon <a href='#'>+6278979662</a></p>"
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
              "top" => 100,
              "bottom" => 100,
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
              "backgroundImage"      => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/photo-1505799225497-3f545a699266_1080_auto.jpeg',
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
            "value" => "rgba(0,0,0,0.4)"
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
      class="icl-section icl-section--cta-14"
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
          <h2 class="section-title mb3" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></h2>
          <Button :data="data.button.value" />
          <div class="my3" ><InlineEditor v-model="data.content.value" /></div>
          <div class="navbar-social-icons">
            <a href="#" v-for="social in data.social.value" :href="social.url.value"><Icon v-model="social.icon.value"/><span v-if="social.label.value"><InlineEditor v-model="social.label.value"/></span></a>
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
      class="icl-section icl-section--cta-14 content-<?php echo $style->content_color->value; ?>">
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
          <h2 data-aos="fade-up" class="section-title mb3" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></h2>
          <?php echo render_button($data->button->value," data-aos='fade-up' data-aos-delay='150'");  ?>
          <div class="my3"  data-aos="fade-up" data-aos-delay="300"><?php echo $data->content->value; ?></div>
          <div class="navbar-social-icons" data-aos="fade-up" data-aos-delay="450">
            <?php foreach( $data->social->value as $social ): ?>
            <a  href="<?php echo $social->url->value; ?>">
              <?php echo get_font_awesome_svg($social->icon->value,"el-icon"); ?>
              <?php if (!empty($social->label->value)): ?>
              <span><?php echo $social->label->value; ?></span>
              <?php endif; ?>
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



$CTA14 = new CTA14();
$arrayCode[] = $CTA14->data();
$arrayTemplate[] = $CTA14->editorTemplate();