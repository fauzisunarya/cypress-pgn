<?php

class Content7 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'content-07',
      "title"      => 'Content 7',
      "screenshot" => 'content-07/content-07.jpg',
      "screenshot_size" => array( 600, 337 ),
      "template"   => '#content-07',
      "category"   => 'content',
        "data" => array(
          
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Your question answered</strong>',
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
          "qa" => array(
            "type" => "repeatable",
            "label" => "Question & Answer",
            "item_title" => "question",
            "settings"=> array(
              "question" => array(
                  "type" => "text",
                  "label" => "Question",
                  "value" => "Lorem ipsum dolor sit amet, consectetur adipisicing"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Answer",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error ea dolor sapiente excepturi expedita molestias qui asperiores ex ullam ad, assumenda vel tenetur aliquam, aperiam et velit possimus cumque fugiat.</p>'
                )
            ),
            "value" => array(
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Question",
                  "value" => "Lorem ipsum dolor sit amet, consectetur adipisicing"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Answer",
                  "value" => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error ea dolor sapiente excepturi expedita molestias qui asperiores ex ullam ad, assumenda vel tenetur aliquam, aperiam et velit possimus cumque fugiat.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Question",
                  "value" => "Ad vero expedita, odio illo ex eligendi"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Answer",
                  "value" => '<p>Molestiae blanditiis voluptates placeat totam nesciunt fugiat ex dolores quasi. Blanditiis consequuntur nobis cupiditate perferendis fugiat quisquam veniam quidem praesentium reprehenderit facere quos laudantium atque nulla, adipisci accusamus impedit ad.</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Question",
                  "value" => "Similique omnis modi labore, beatae repudiandae reprehenderit"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Answer",
                  "value" => '<p>Beatae architecto laborum perspiciatis totam necessitatibus magni aliquam repellat, quibusdam expedita eum veritatis cumque dignissimos explicabo animi deserunt obcaecati. Reiciendis hic temporibus, fugit ipsum earum ad obcaecati, repellat tenetur voluptate!</p>'
                )
              ),
              array(
                "question" => array(
                  "type" => "text",
                  "label" => "Question",
                  "value" => "Veritatis veniam culpa facere, magnam. Rerum, reprehenderit"
                ),
                "answer" => array(
                  "type" => "wyswyg",
                  "label" => "Answer",
                  "value" => '<p>Sed, in. Maiores reprehenderit inventore vitae sed deserunt, atque dignissimos voluptate maxime aspernatur unde veniam expedita iste consectetur, dolor repellat error doloribus ratione odio obcaecati perferendis quo odit optio. Sunt.</p>'
                )
            ) ),
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
        <div :class="`content-spacer`" :style="{'padding': `${style.padding.value.top}px ${style.padding.value.right}px ${style.padding.value.bottom}px ${style.padding.value.left}px`}">
          <div :class="`section-title ${data.alignment.value} mb4`" :style="`--font-size: ${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></div>
          
          <Sortable class="qa-grid" v-model="data.qa.value">
            <SortableItem class="qa-item" v-for="(qa, index) in data.qa.value" :key="`item-${index}`" :list="data.qa.value" :index="index">
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

      <div class="content-spacer" style="padding: <?php echo implode(" ", $padding); ?>">
        <div data-aos="fade-up" class="section-title <?php echo $data->alignment->value; ?> mb4" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></div>
        <div class="qa-grid">
          <?php
          $delay = 150;
          foreach( $data->qa->value as $qa): ?>
          <div class="qa-item" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
            <h3 class="qa-q"><?php echo $qa->question->value; ?></h3>
            <div class="qa-a"><?php echo $qa->answer->value; ?></div>
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

$content7 = new Content7();
$arrayCode[] = $content7->data();
$arrayTemplate[] = $content7->editorTemplate();