<?php

class Newsletter1 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'newsletter-01',
      "title"      => 'Newsletter 1',
      "screenshot" => 'newsletter-01/newsletter-01.jpg',
      "screenshot_size" => array( 600, 225 ),
      "template"   => '#newsletter-01',
      "category"   => 'newsletter',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Berlangganan</strong>',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "value"           => 36,
            "min"             => 18,
            "max"             => 72,
            "horizontal" => true,
          ),
          "subtitle" => array(
            "type"            => 'wyswyg',
            "label"           => 'Sub Judul',
            "value"           => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Rem explicabo numquam quia consequuntur magnam eos expedita libero pariatur beatae veritatis, nemo eveniet ea vel fugiat, quaerat! Eligendi sint, ad et.</p>',
          ),
          "alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Rata Teks',
            "horizontal"      => true,
            "value"           => 'has-text-centered',
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

          "spacing-1" => array( "type" => "spacer" ),


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
          )

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
              "backgroundColor"      => "#FFF",
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
      class="icl-section icl-section--contact-1"
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
          

          <div :class="`columns is-centered ${data.alignment.value}`">
            <div class="column is-8">
              <div :class="`section-header mb4`">
                <div class="section-title" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value"/></div>
                <span class="section-subtitle" ><InlineEditor v-model="data.subtitle.value" /></span>
              </div>
              <div class="form-wrapper button-parent-style"
                :class="`${data.button_style.value} ${data.button_corner.value}`"
                :style="{
                  '--background': data.button_bg.value,
                  '--foreground': data.button_color.value,
                }">
              <?php
                $form = modules::run('api/render/render_form','form-subscribe-email',array("show-label"=>false)); 
                echo $form;
              ?>
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
      "top"    => $style->padding->value->top.'px',
      "right"  => $style->padding->value->right.'px',
      "bottom" => $style->padding->value->bottom.'px',
      "left"   => $style->padding->value->left.'px',
    );

    ?>
    <div
      id="block-<?php echo $id; ?>"
      class="icl-section icl-section--contact-1 content-<?php echo $style->content_color->value; ?>">
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
          
          
          <div class="columns is-centered <?php echo $data->alignment->value ?>">
            <div class="column is-8">
              <div class="section-header mb4">
                <h2 class="section-title" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo format_heading( $data->title->value); ?></h2>
                <span class="section-subtitle"><?php echo $data->subtitle->value; ?></span>
              </div>
              <div class="form-wrapper button-parent-style <?php echo $data->button_style->value; ?> <?php echo $data->button_corner->value; ?>" style="--background:<?php echo $data->button_bg->value; ?>;--foreground:<?php echo $data->button_color->value; ?>">
              <?php
                $form = modules::run('api/render/render_form','form-subscribe-email',array("show-label"=>false)); 
                echo $form;
              ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
  }
}
$newsletter7 = new Newsletter1();
$arrayCode[] = $newsletter7->data();
$arrayTemplate[] = $newsletter7->editorTemplate();