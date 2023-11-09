<?php

class Contact8 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'contact-08',
      "title"      => 'Contact 8',
      "screenshot" => 'contact-08/contact-08.jpg',
      "screenshot_size" => array( 944, 588 ),
      "template"   => '#contact-08',
      "category"   => 'contact',
        "data" => array(
          "contact" => array(
            "type"  => "repeatable",
            "label" => "Contact Data",
            "item_title" => "label",
            "settings" => array(
              "icon" => array(
                "type" => "icon",
                "label" => "Icon",
                "horizontal"=> true,
                "value" => "fas fa-envelope"
              ),
              "data" => array(
                "type" => "text",
                "label" => "Data",
                "horizontal" => true,
                "value" => "hello@email.com"
              ),
              "label" => array(
                "type" => "text",
                "label" => "Label",
                "horizontal" => true,
                "value" => "Email"
              ),
              "icon_color" => array(
                "type" => "color",
                "label" => "Warna Icon",
                "horizontal" => true,
                "value" => ""
              ),
            ),
            "value"  => array(
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Icon",
                  "horizontal"=> true,
                  "value" => "fas fa-envelope"
                ),
                "data" => array(
                  "type" => "text",
                  "label" => "Data",
                  "horizontal" => true,
                  "value" => "hello@email.com"
                ),
                "label" => array(
                  "type" => "text",
                  "label" => "Label",
                  "horizontal" => true,
                  "value" => "Email"
                ),
                "icon_color" => array(
                  "type" => "color",
                  "label" => "Warna Icon",
                  "horizontal" => true,
                  "value" => ""
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Icon",
                  "horizontal"=> true,
                  "value" => "fas fa-phone"
                ),
                "data" => array(
                  "type" => "text",
                  "label" => "Data",
                  "horizontal" => true,
                  "value" => "+62 78967 9869"
                ),
                "label" => array(
                  "type" => "text",
                  "label" => "Label",
                  "horizontal" => true,
                  "value" => "Phone"
                ),
                "icon_color" => array(
                  "type" => "color",
                  "label" => "Warna Icon",
                  "horizontal" => true,
                  "value" => ""
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Icon",
                  "horizontal"=> true,
                  "value" => "fas fa-map-marker"
                ),
                "data" => array(
                  "type" => "text",
                  "label" => "Data",
                  "horizontal" => true,
                  "value" => "Main Street 32b, NYC"
                ),
                "label" => array(
                  "type" => "text",
                  "label" => "Label",
                  "horizontal" => true,
                  "value" => "Address",
                ),
                "icon_color" => array(
                  "type" => "color",
                  "label" => "Warna Icon",
                  "horizontal" => true,
                  "value" => ""
                ),
              )
            ),
          ),
          "separator" => array(
            "type" => "spacer"
          ),
          "icon_size" => array(
            "type" => "slider",
            "label" => "Ukuran Icon",
            "horizontal" => true,
            "min" => 0,
            "max" => 100,
            "value" => 20
          ),
          "label_size" => array(
            "type" => "slider",
            "label" => "Ukuran kontak label",
            "horizontal" => true,
            "min" => 0,
            "max" => 100,
            "value" => 14
          ),
          "value_size" => array(
            "type" => "slider",
            "label" => "Ukuran kontak data",
            "horizontal" => true,
            "min" => 0,
            "max" => 100,
            "value" => 20
          ),
          "spacing" => array( 'type' => "spacer" ),
          "title_2" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => 'Send us message',
          ),
          "subtitle_2" => array(
            "type"            => 'wyswyg',
            "label"           => 'Sub Judul',
            "value"           => 'Our team of designers & developers make a custom digital product for startup and brand',
          ),
          "spacing-1" => array( 'type' => "spacer" ),
          "form" => array(
            "type" => 'form',
            "label" => "Formulir",
            "value" => "",
            "horizontal" => true,
            "settings" => array(
              "label" => true
            )
          ),
          "button_alignment" => array(
            "type"       => 'radio-icon',
            "label"      => 'Rata Tombol Form',
            "value"      => 'right',
            "horizontal" => true,
            "options"    => array(
              array(
                "icon" => "format_align_left",
                "label" => "left",
                "value" => "left"
              ),
              array(
                "icon" => "format_align_center",
                "label" => "center",
                "value" => "center"
              ),
              array(
                "icon" => "format_align_right",
                "label" => "right",
                "value" => "right"
              )
            )
          ),
          "button_bg" => array(
            "type" => "color",
            "label" => "Warna Latar Tombol",
            "horizontal" => true,
            "value" => '#007bff'
          ),
          "button_color" => array(
            "type" => "color",
            "label" => "Warna Teks Tombol",
            "horizontal" => true,
            "value" => '#fff'
          ),
          "button_style" => array(
            "type" => "select",
            "label" => "Gaya Tombol",
            "horizontal" => true,
            "value" => "is-fill",
            "options" => array(
              "is-fill" => "Blok warna",
              "is-outline" => "Garis Pinggir",
            )
          ),
          "button_corner" => array(
            "type" => "select",
            "label" => "Gaya Sudut",
            "horizontal" => true,
            "value" => "is-rounded",
            "options" => array(
              "is-sharp" => "Tajam",
              "is-radius" => "Melengkung",
              "is-rounded" => "Pil"
            )
          ),
          "spacing-2" => array( 'type' => "spacer" ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "value"           => 36,
            "min"             => 18,
            "max"             => 72,
            "horizontal" => true,
          ),
          "alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Rata Teks',
            "horizontal"      => true,
            "value"           => 'has-text-left',
            "horizontal" => true,
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
          "padding" => array(
            "type" => "directional",
            "label" => "Jarak Konten",
            "value" => array(
              "top" => 100,
              "bottom" => 100,
              "left" => 15,
              "right" => 15,
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
              "backgroundColor"      => "#ffffff",
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
      class="icl-section icl-section--contact-8"
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
          <div class="columns is-variable is-5 is-centered" :class="{'row-reverse': data.reverse.value == true }">
            <div class="column is-6 has-background-white box">
              <div :class="`section-header mb4 ${data.alignment.value}`">
                <div class="section-title" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title_2.value" /></div>
                <span class="section-subtitle" ><InlineEditor v-model="data.subtitle_2.value" /></span>
              </div>
              <div
                class="form-wrapper button-parent-style"
                :class="`${data.button_style.value} ${data.button_corner.value} submit-button-${data.button_alignment.value}`"
                :style="{
                  '--background': data.button_bg.value,
                  '--foreground': data.button_color.value,
                }">
                <ContactForm :id="data.form.value" :showLabel="data.form.settings.label" /> 
              </div>
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
    // var_dump($id, $data, $style);
    ob_start();
    ?>
    <div
      id="block-<?php echo $id; ?>"
      class="icl-section icl-section--contact-8 content-<?php echo $style->content_color->value; ?>">
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
          <div class="columns is-variable is-5 is-centered <?php echo $data->reverse->value == true ? "row-reverse" : ""; ?>">
            <div class="column is-6">
              <div class="section-header mb4 <?php echo $data->alignment->value ?>">
                <h2 class="section-title" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo $data->title_2->value; ?></h2>
                <span class="section-subtitle"><?php echo $data->subtitle_2->value; ?></span>
              </div>
              <div class="form-wrapper button-parent-style <?php echo $data->button_style->value; ?> <?php echo $data->button_corner->value; ?> submit-button-<?php echo $data->button_alignment->value;  ?>" style="--background:<?php echo $data->button_bg->value; ?>;--foreground:<?php echo $data->button_color->value; ?>">
                  <?php echo $render->render_form_custom( $data->form->value, array("show-label" => $data->form->settings->label ) ) ?>
                </div>
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
$contact8 = new Contact8();
$arrayCode[] = $contact8->data();
$arrayTemplate[] = $contact8->editorTemplate();