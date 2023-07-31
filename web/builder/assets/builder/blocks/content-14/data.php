<?php

class Content14 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'content-14',
      "title"      => 'Content 14',
      "screenshot" => 'content-14/content-14.jpg',
      "screenshot_size" => array( 600, 335 ),
      "template"   => '#content-14',
      "category"   => 'content',
        "data" => array(
          "subtitle" => array(
            "type"            => 'text',
            "label"           => 'Sub Judul',
            "horizontal"      => true,
            "value"           => 'Informasi',
          ),
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Fasilitas</strong>',
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
            "label"           => 'Title Alignment',
            "horizontal" => true,
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
          "qa" => array(
            "type" => "repeatable",
            "label" => "Judul dan Detail",
            "item_title" => "question",
            "settings"=> array(
              "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Free WiFI"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
            ),
            "value" => array(
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Gratis WiFi"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Parkir Gratis"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Kamar Nyaman"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Tempat Cuci"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Sarapan Gratis"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Kolam Renang"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Lokasi Strategis"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Dekat dengan Atraksi"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "value" => "Free Ride"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Konten",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id ut, omnis. Voluptate rerum explicabo nulla eligendi.</p>'
                )
              ),
            ),
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
      class="icl-section icl-section--content-6"
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

          <div :class="`section-subtitle`"><InlineEditor v-model="data.subtitle.value"/></div>
          <h2 :class="`section-title ${data.alignment.value} mb3`" :style="`--font-size: ${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></h2>
          
          <Sortable class="qa-grid" data-columns=3 v-model="data.qa.value">
            <SortableItem class="qa-item" v-for="(qa, index) in data.qa.value"  :key="`item-${index}`" :list="data.qa.value" :index="index">
              <h3 class="qa-q" ><InlineEditor v-model="qa.question.value" /></h3>
              <div class="qa-a" ><InlineEditor v-model="qa.answer.value" /></div>
            </SortableItem>
          </Sortable>
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
      class="icl-section icl-section--content-5 content-<?php echo $style->content_color->value; ?>">
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
        <?php if(format_heading($data->subtitle->value)): ?>
          <div class="`section-subtitle`"><?php echo $data->subtitle->value; ?></div>
        <?php endif; ?>

        <?php if(format_heading($data->title->value)): ?>
          <h2 data-aos="fade-up" class="section-title mb3" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></h2>
        <?php endif; ?>

        <div class="qa-grid">
          <?php
          $delay = 150;
          foreach( $data->qa->value as $qa): ?>
          <div class="qa-item" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
            <?php if(format_heading($qa->question->value)): ?>
              <h3 class="qa-q"><?php echo $qa->question->value; ?></h3>
            <?php endif; ?>

            <?php if(format_heading($qa->answer->value)): ?>
              <div class="qa-a"><?php echo $qa->answer->value; ?></div>
            <?php endif; ?>
          </div>
          <?php
          $delay +=150;
          endforeach; ?>
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

$content14 = new Content14();
$arrayCode[] = $content14->data();
$arrayTemplate[] = $content14->editorTemplate();