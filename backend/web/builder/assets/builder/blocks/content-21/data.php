<?php

class Content21 {

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
      "label"         => "Read more",
      "background"    => "#000",
      "color"         => "#ffffff",
      "size"          => "is-normal", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-square", //[square, rounded, pill]
      "icon"          => '',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'content-21',
      "title"      => 'Content 21',
      "screenshot" => 'content-21/content-21.jpg',
      "screenshot_size" => array( 600, 359 ),
      "template"   => '#content-21',
      "category"   => 'content',
        "data" => array(
          
          "toptext" => array(
            "type"            => 'wyswyg',
            "label"           => 'Teks atas',
            "value"           => 'From our Founder',
          ),
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => 'Make <em>your own</em> <strong>opportunity</strong>',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "value"           => 40,
            "min"             => 18,
            "max"             => 72,
          ),
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Sub Judul',
            "value"           => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam sint perferendis alias voluptatum fuga delectus, non dolores est mollitia id quam minima, nam laudantium quasi, recusandae necessitatibus deserunt aspernatur eaque. At recusandae in alias, vitae commodi quod laboriosam est doloribus error, amet, eaque soluta suscipit non eveniet distinctio obcaecati numquam.</p>',
          ),
          "button" => $component->getButton(),
          "card_position" => array(
            "type"            => 'radio-icon',
            "label"           => 'Posisi Konten',
            "horizontal"      => true,
            "value"           => 'is-start',
            "options" => array(
              array(
                "icon" => "format_align_left",
                "label" => "left",
                "value" => "is-start"
              ),
              array(
                "icon" => "format_align_center",
                "label" => "center",
                "value" => "is-centered"
              ),
              array(
                "icon" => "format_align_right",
                "label" => "right",
                "value" => "is-end"
              )
            )
          ),
          "alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Rata Teks',
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
          "card_bg" => array(
            "type" => "color",
            "label" => "Warna Latar Konten",
            "horizontal" => true,
            "value" => "#FFF"
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
              "top" => 150,
              "bottom" => 150,
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
              "backgroundColor"      => "#000",
              "backgroundImage"      => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/photo-1499952127939-9bbf5af6c51c.jpeg',
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
      class="icl-section icl-section--content-21"
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
          <div :class="`columns ${data.card_position.value}`">
            <div class="column is-5">
              <div class="card" :style="{backgroundColor: data.card_bg.value}">
                <span class="section-title--top" ><InlineEditor v-model="data.toptext.value" /></span>
                <div class="section-title mb3" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value"/></div>
                <div class="mb2"><InlineEditor v-model="data.content.value" /></div>
                <Button :data="data.button.value" />
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
      class="icl-section icl-section--content-21 content-<?php echo $style->content_color->value; ?>">
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
        <div class="columns <?php echo $data->card_position->value; ?>">
          <div class="column is-5">
            <div class="card" data-aos="fade-up" style="background-color:<?php echo $data->card_bg->value; ?>">
              <span class="section-title--top"><?php echo $data->toptext->value;  ?></span>
              <h2 class="section-title mb3" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></h2>
              <div class="mb2"><?php echo $data->content->value;  ?></div>

              <?php render_button($data->button->value, ''); ?>
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



$content21 = new Content21();
$arrayCode[] = $content21->data();
$arrayTemplate[] = $content21->editorTemplate();