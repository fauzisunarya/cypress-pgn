<?php

class Newsletter03 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'newsletter-03',
      "title"      => 'Newsletter 03',
      "screenshot" => 'newsletter-03/newsletter-03.jpg',
      "screenshot_size" => array( 600, 344 ),
      "template"   => '#newsletter-03',
      "category"   => 'newsletter',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => 'Berlangganan',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "value"           => 36,
            "min"             => 18,
            "max"             => 72,
          ),
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Konten',
            "value"           => 'Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus.',
          ),
          "alignment" => array(
            "type"       => 'radio-icon',
            "label"      => 'Alignment',
            "value"      => 'has-text-centered',
            "horizontal" => true,
            "options"    => array(
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
          
          "image" => array(
            "type" => "image",
            "label" => "Image",
            "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/photo-1508612761958-e931d843bdd5.jpeg'
          ),
          "reverse" => array(
            "type"            => 'switch',
            "label"           => 'Tukar Layout',
            "horizontal"      => true,
            "value"           => false,
          ),

          "spacing-1" => array( "type" => "spacer" ),


          "button_bg" => array(
            "type" => "color",
            "label" => "Warna Latar Tombol",
            "horizontal" => true,
            "value" => '#5064CE'
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
            "value" => "is-radius",
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
              "top" => 200,
              "bottom" => 200,
              "left" => 0,
              "right" => 0,
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
              "backgroundColor"      => "#000000",
              "backgroundImage"      => "",
              "backgroundPosition"   => "center",
              "backgroundSize"       => "cover",
              "backgroundRepeat"     => "no-repeat",
              "backgroundAttachment" => "fixed",
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
      class="icl-section icl-section--cta-8"
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
        <div class="columns bg-half-wrapper" :class="{'row-reverse': data.reverse.value == true }">
          <div class="column is-half"></div>
            <div class="column is-half">
              <div class="bg-half" :style="{backgroundImage: `url(${data.image.value})`}"></div>
            </div>
        </div>
      </div>

        
      <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
        <div :class="`content-spacer`" :style="{'padding': `${style.padding.value.top}px ${style.padding.value.right}px ${style.padding.value.bottom}px ${style.padding.value.left}px`}">
          <div class="columns is-vcentered" :class="{'row-reverse': data.reverse.value == true }">
            <div :class="`column is-half column-content ${data.alignment.value}`">
              <div :class="`section-title font-light mb3`" :style="`--font-size: ${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></div>
              <div class="mb3" ><InlineEditor v-model="data.content.value" /></div>
              <div class="form-wrapper button-parent-style"
                :class="`${data.button_style.value} ${data.button_corner.value}`"
                :style="{
                  '--background': data.button_bg.value,
                  '--foreground': data.button_color.value,
                }">
              <?php
                $form = modules::run('api/render/render_form','form-subscribe',array("show-label"=>true)); 
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
      "top" => $style->padding->value->top.'px',
      "right" => $style->padding->value->right.'px',
      "bottom" => $style->padding->value->bottom.'px',
      "left" => $style->padding->value->left.'px',
    );
    ob_start();
    ?>
    <div
      id="block-<?php echo $id; ?>"
      class="icl-section icl-section--cta-8 content-<?php echo $style->content_color->value; ?>">
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
        <?php echo $render->render_divider( $style->divider->value ); ?>

        <div class="columns bg-half-wrapper <?php echo $data->reverse->value == true ? "row-reverse" : ""; ?>">
          <div class="column is-half"></div>
          <div class="column is-half">
            <div class="bg-half" style="background-image: url(<?php echo $data->image->value;  ?>)"></div>
          </div>
        </div>
      </div>


      <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">
        <div class="content-spacer" style="padding: <?php echo implode(" ", $padding); ?>">
          <div class="columns is-vcentered <?php echo $data->reverse->value == true ? "row-reverse" : ""; ?>">
            <div class="column is-half column-content <?php echo $data->alignment->value; ?>">
              <div data-aos="fade-up" class="section-title font-light mb3" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></div>
              <div data-aos="fade-up" data-aos-delay="150" class="mb3"><?php echo $data->content->value; ?></div>
              <div class="form-wrapper button-parent-style <?php echo $data->button_style->value; ?> <?php echo $data->button_corner->value; ?>" style="--background:<?php echo $data->button_bg->value; ?>;--foreground:<?php echo $data->button_color->value; ?>">
              <?php
                $form = modules::run('api/render/render_form','form-subscribe',array("show-label"=>true)); 
                echo $form;
              ?>
              </div>
            </div>
            <div class="column is-half"></div>
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


$newsletter3 = new Newsletter03();
$arrayCode[] = $newsletter3->data();
$arrayTemplate[] = $newsletter3->editorTemplate();