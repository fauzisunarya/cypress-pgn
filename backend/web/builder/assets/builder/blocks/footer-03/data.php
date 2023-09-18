<?php

class Footer3 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'footer-03',
      "title"      => 'Footer 3',
      "screenshot" => 'footer-03/footer-03.jpg',
      "screenshot_size" => array( 600, 131 ),
      "template"   => '#footer-03',
      "category"   => 'footer',
        "data" => array(
          "logo" => array(
            "type"            => 'image',
            "description"     => 'Lebar & Tinggi Maksimum Logo 200px',
            "label"           => 'Logo',
            "value"           => $this->base_url . 'assets/builder/blocks-assets/imgs/telkom-logo.png',
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
                  "value" => "Like on Facebook"
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
                  "value" => "Follow on Twitter"
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
                  "value" => "Checkout on Instagram"
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
          "vertical_spacing" => array(
            "type" => "slider",
            "horizontal" => true,
            "label" => "Vertical Spacing",
            "value" => 60,
            "min" => 0,
            "max" => 100
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
              "backgroundColor"      => "#fbfbfb",
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
      :class="`icl-section icl-footer icl-footer--style-3 content-${style.content_color.value}`">
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
        <div
          class="flex flex-column justify-center items-center"
          :style="{paddingTop: style.vertical_spacing.value+'px', paddingBottom: style.vertical_spacing.value+'px'}">

          <a class="brand" @click.prevent>
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
    <header
      id="block-<?php echo $id; ?>"
        class="icl-section icl-footer icl-footer--style-3  content-<?php echo $style->content_color->value; ?>">
        <div class="icl-section__bg" style="
          background-color: <?php echo $style->background->value->backgroundColor; ?>;
          background-image : url(<?php echo $style->background->value->backgroundImage; ?>);
          background-repeat : <?php echo $style->background->value->backgroundRepeat; ?>;
          background-size : <?php echo $style->background->value->backgroundSize; ?>;
          background-position : <?php echo $style->background->value->backgroundPosition; ?>;
          background-attachment : <?php echo $style->background->value->backgroundAttachment; ?>;
          ">
          <?php $render = new Render();?>
          <?php echo $render->render_overlay( $style->overlay_color->value ); ?>
        </div>

      <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">
        <div
          class="flex flex-column justify-center items-center"
          style="padding-top: <?php echo $style->vertical_spacing->value.'px';?>; padding-bottom: <?php echo $style->vertical_spacing->value.'px';?>">

          <a class="brand">
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
    </header>
  <?php
    return ob_get_clean();
  }

  static function renderCSS(){
    return file_get_contents('style.css', FILE_USE_INCLUDE_PATH);
  }
}

$footer3 = new Footer3();
$arrayCode[] = $footer3->data();
$arrayTemplate[] = $footer3->editorTemplate();