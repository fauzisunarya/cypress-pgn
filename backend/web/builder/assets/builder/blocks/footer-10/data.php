<?php

class Footer10 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'footer-10',
      "title"      => 'Footer 10',
      "screenshot" => 'footer-10/footer-10.jpg',
      "screenshot_size" => array( 553, 105 ),
      "template"   => '#footer-10',
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
          "copy" => array(
            "type"            => 'text',
            "horizontal"      => true,
            "label"           => 'Teks copyright',
            "value"           => "&copy; 2020 Telkom Indonesia. All rights reserved",
          ),
          "footer_menu" => array(
            "type" => "repeatable",
            "label" => "Menu 1",
            "item_title" => "label",
            "settings" => array(
              "label"=> array(
                "type" => "text",
                "horizontal" => true,
                "label"=> "Label",
                "value" => "Privacy Policy"
              ),
              "url" => array(
                "type" => "text",
                "horizontal" => true,
                "label" => "URL",
                "value" => "#"
              )
            ),
            "value" => array(
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Company"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "About"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Newsroom"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
            )
          ),
          "footer_menu2" => array(
            "type" => "repeatable",
            "label" => "Menu 2",
            "item_title" => "label",
            "settings" => array(
              "label"=> array(
                "type" => "text",
                "horizontal" => true,
                "label"=> "Label",
                "value" => "Education"
              ),
              "url" => array(
                "type" => "text",
                "horizontal" => true,
                "label" => "URL",
                "value" => "#"
              )
            ),
            "value" => array(
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Find a Location"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Blog"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Social"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
            )
          ),
          "footer_menu3" => array(
            "type" => "repeatable",
            "label" => "Menu 3",
            "item_title" => "label",
            "settings" => array(
              "label"=> array(
                "type" => "text",
                "horizontal" => true,
                "label"=> "Label",
                "value" => "Customer Service"
              ),
              "url" => array(
                "type" => "text",
                "horizontal" => true,
                "label" => "URL",
                "value" => "#"
              )
            ),
            "value" => array(
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Contact"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Privacy Policy"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Terms"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
            )
          ),
          "additional_text" => array(
            "type"            => 'text',
            "horizontal"      => true,
            "label"           => 'Teks Tambahan',
            "value"           => "UKM.Digital adalah situs untuk membuat halaman web dengan cepat dan mudah",
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
              "backgroundColor"      => "#FF0000",
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
      :class="`icl-section icl-footer icl-footer--style-10 content-${style.content_color.value}`"
      :style="{paddingTop: style.vertical_spacing.value+'px', paddingBottom: style.vertical_spacing.value+'px'}">
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
        <div class="footer-row --top columns">

          <a class="brand column is-3" @click.prevent>
            <img :src="$store.state.project.logo">
          </a>

          <div class="copyline ml-0 column is-4" v-html="data.copy.value"></div>

          <div class="navbar-footer-menu column">
            <a class="navbar-item" v-for="menu_s in data.footer_menu.value" :href="menu_s.url.value" style="padding-left: 0;">
              <InlineEditor v-model="menu_s.label.value"/>
            </a>
          </div>

          <div class="navbar-footer-menu column">
            <a class="navbar-item" v-for="menu_s in data.footer_menu2.value" :href="menu_s.url.value" style="padding-left: 0;">
              <InlineEditor v-model="menu_s.label.value"/>
            </a>
          </div>

          <div class="navbar-footer-menu column">
            <a class="navbar-item" v-for="menu_s in data.footer_menu3.value" :href="menu_s.url.value" style="padding-left: 0;">
              <InlineEditor v-model="menu_s.label.value"/>
            </a>
          </div>

        </div>

        <div class="footer-row --bottom">
          <div class="add-text" style="margin-bottom: 20px">
            <InlineEditor v-model="data.additional_text.value" />
          </div>
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
      class="icl-section icl-footer icl-footer--style-10  content-<?php echo $style->content_color->value; ?>"
      style="padding-top: <?php echo $style->vertical_spacing->value.'px';?>; padding-bottom: <?php echo $style->vertical_spacing->value.'px';?>">
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
        <div class="footer-row --top columns">

          <a class="brand column is-3">
            <img src="<?php echo $data->logo->value; ?>">
          </a>

          <div class="copyline ml-0 column is-4"><?php echo $data->copy->value; ?></div>

          <div class="navbar-footer-menu column">
            <?php foreach( $data->footer_menu->value as $menu_s ): ?>
            <a class="navbar-item" href="<?php echo $menu_s->url->value; ?>" style="padding-left: 0;">
              <?php echo $menu_s->label->value; ?>
            </a>
            <?php endforeach; ?>
          </div>

          <div class="navbar-footer-menu column">
            <?php foreach( $data->footer_menu2->value as $menu_s ): ?>
            <a class="navbar-item" href="<?php echo $menu_s->url->value; ?>" style="padding-left: 0;">
              <?php echo $menu_s->label->value; ?>
            </a>
            <?php endforeach; ?>
          </div>

          <div class="navbar-footer-menu column">
            <?php foreach( $data->footer_menu3->value as $menu_s ): ?>
            <a class="navbar-item" href="<?php echo $menu_s->url->value; ?>" style="padding-left: 0;">
              <?php echo $menu_s->label->value; ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="footer-row --bottom">
          <div class="add-text">
            <?php echo $data->additional_text->value; ?>
          </div>
          
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

$footer10 = new Footer10();
$arrayCode[] = $footer10->data();
$arrayTemplate[] = $footer10->editorTemplate();