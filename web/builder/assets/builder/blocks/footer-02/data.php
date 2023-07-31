<?php

class Footer2 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'footer-02',
      "title"      => 'Footer 2',
      "screenshot" => 'footer-02/footer-02.jpg',
      "screenshot_size" => array( 600, 46 ),
      "template"   => '#footer-02',
      "category"   => 'footer',
        "data" => array(
          "logo" => array(
            "type"            => 'image',
            "description"     => 'Lebar & Tinggi Maksimum Logo 200px',
            "label"           => 'Logo',
            "value"           => $this->base_url . 'assets/builder/blocks-assets/imgs/telkom-logo-white.png',
          ),
          "logo_url" => array(
            "type"            => 'text',
            "horizontal"      => true,
            "label"           => 'Upload Logo',
            "input_type"      => 'link',
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
            "value"      => false,
          ),
          "vertical_spacing" => array(
            "type" => "slider",
            "horizontal" => true,
            "label" => "Vertical Spacing",
            "value" => 30,
            "min" => 0,
            "max" => 60
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
              "backgroundColor"      => "#495057",
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
        )
    );
  }

  public function editorTemplate() {
    ob_start(); ?>
   <footer
      :id="`block-${id}`"
      :class="`icl-section icl-footer icl-footer--style-2 content-${style.content_color.value}`">
      <div class="icl-section__bg" :style="{
        'backgroundColor'      : style.background.value.backgroundColor,
        'backgroundImage'      : `url(${style.background.value.backgroundImage})`,
        'backgroundRepeat'     : style.background.value.backgroundRepeat,
        'backgroundSize'       : style.background.value.backgroundSize,
        'backgroundPosition'   : style.background.value.backgroundPosition,
        'backgroundAttachment' : style.background.value.backgroundAttachment,
        }">
        <Overlay :data="style.overlay_color.value"/>
      </div>
      <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
        <div class="flex justify-between items-center" :class="{'row-reverse': data.reverse.value == true }">

          <a class="brand" @click.prevent
            :style="{paddingTop: style.vertical_spacing.value+'px', paddingBottom: style.vertical_spacing.value+'px'}">
            <img :src="$store.state.project.logo">
          </a>

          <div class="navbar-social-icons">
            <a href="#" v-for="social in data.social.value" :href="social.url.value"><Icon v-model="social.icon.value" /><span v-if="social.label.value"><InlineEditor v-model="social.label.value"/></span></a>
          </div>
        </div>
      </div>

    </footer>
    <?php
    return ob_get_clean();

  }

  static function render($id, $data, $style, $header) {
    ob_start();
    ?>
    <footer
      id="block-<?php echo $id; ?>"
      class="icl-section icl-footer icl-footer--style-2  content-<?php echo $style->content_color->value; ?>">
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
      </div>

      <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">
        <div class="flex justify-between items-center <?php echo $data->reverse->value ? 'row-reverse' : ''; ?>">
          <a class="brand"
            style="padding-top: <?php echo $style->vertical_spacing->value.'px';?>; padding-bottom: <?php echo $style->vertical_spacing->value.'px';?>">
            <img src="<?php echo $data->logo->value; ?>">
          </a>

          <div class="navbar-social-icons">
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
    </footer>
  <?php
    return ob_get_clean();
  }

  static function renderCSS(){
    return file_get_contents('style.css', FILE_USE_INCLUDE_PATH);
  }
}

$footer2 = new Footer2();
$arrayCode[] = $footer2->data();
$arrayTemplate[] = $footer2->editorTemplate();