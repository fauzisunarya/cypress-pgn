<?php

class Testimonial9 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'testimonial-09',
      "title"      => 'Testimonial 9',
      "screenshot" => 'testimonial-09/testimonial-09.jpg',
      "screenshot_size" => array( 600, 253 ),
      "template"   => '#testimonial-09',
      "category"   => 'testimonial',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Our Happy Clients</strong>',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "horizontal"      => true,
            "value"           => 48,
            "min"             => 18,
            "max"             => 72,
            "horizontal" => true,
          ),
          "subtitle" => array(
            "type"            => 'wyswyg',
            "label"           => 'Sub Judul',
            "value"           => 'we created the most simple way to create a website, just drag and drop you\'re ready to go',
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
          "testimonials" => array(
            "type"  => "repeatable",
            "label" => "Testimonial",
            "item_title" => "author",
            "label_title" => "author",
            "settings" => array(
              "rating" => array(
                "type" => "slider",
                "label" => "Peringkat Bintang",
                "horizontal" => true,
                "value" => 5,
                "min" => 1,
                "max" => 5,
              ),
              "testimonial" => array(
                "type" => "wyswyg",
                "label" => "Testimonial",
                "value" => "<p>People often say that motivation doesn't last. Well, neither does bathing -- that's why we recommend it daily</p>"
              ),
              "author" => array(
                "type" => "text",
                "label" => "Penulis",
                "horizontal" => true,
                "value" => "Zig Ziglar"
              ),
              "author_title" => array(
                  "type" => "text",
                  "label" => "Author title",
                  "horizontal" => true,
                  "value" => "Web Developer"
                ),
            ),
            "value"  => array(
              array(
                "rating" => array(
                  "type" => "slider",
                  "label" => "Peringkat Bintang",
                  "horizontal" => true,
                  "value" => 4,
                  "min" => 1,
                  "max" => 5,
                ),
                "testimonial" => array(
                  "type" => "wyswyg",
                  "label" => "Testimonial",
                  "value" => "<p>People often say that motivation doesn't last. Well, neither does bathing -- that's why we recommend it daily</p>"
                ),
                "author" => array(
                  "type" => "text",
                  "label" => "Penulis",
                  "horizontal" => true,
                  "value" => "Zig Ziglar"
                ),
                "author_title" => array(
                  "type" => "text",
                  "label" => "Author title",
                  "horizontal" => true,
                  "value" => "Web Developer"
                ),
              ),
              array(
                "rating" => array(
                  "type" => "slider",
                  "label" => "Peringkat Bintang",
                  "horizontal" => true,
                  "value" => 5,
                  "min" => 1,
                  "max" => 5,
                ),
                "testimonial" => array(
                  "type" => "wyswyg",
                  "label" => "Testimonial",
                  "value" => "<p>Move out of your comfort zone. You can only grow if you are willing to feel awkward and uncomfortable when you try something new.</p>"
                ),
                "author" => array(
                  "type" => "text",
                  "label" => "Penulis",
                  "horizontal" => true,
                  "value" => "Simon Sinek"
                ),
                "author_title" => array(
                  "type" => "text",
                  "label" => "Author title",
                  "horizontal" => true,
                  "value" => "Web Developer"
                ),
              ),
            ),
          ),
          "content_size" => array(
            "type"  => 'slider',
            "label" => 'Testimonial Text Size',
            "value" => 18,
            "min"   => 14,
            "max"   => 48,
            "horizontal" => true,
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
              "backgroundColor"      => "#F7F7F7",
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
      class="icl-section icl-section--testimonial-9"
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
          <div :class="`section-header mb4 ${data.alignment.value}`">
            <div class="section-title" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value"/></div>
            <span class="section-subtitle" ><InlineEditor v-model="data.subtitle.value" /></span>
          </div>

          <Sortable class="testimonial-grid" v-model="data.testimonials.value">
            <SortableItem class="slide icl-testimonial icl-testimonial--style-9" v-for="(item, index) in data.testimonials.value"  :key="`item-${index}`" :list="data.testimonials.value" :index="index">
              <div class="icl-testimonial__card">
                <div class="icl-testimonial__cite">
                  <span class="icl-testimonial__author"><InlineEditor v-model="item.author.value"/></span>
                  <span class="icl-testimonial__author-title"><InlineEditor v-model="item.author_title.value"/></span>
                </div>

                <blockquote class="icl-testimonial__content mb2" :style="`--font-size:${data.content_size.value}px`"><InlineEditor v-model="item.testimonial.value" /></blockquote>

                <div :class="`star-rating star-rating--${item.rating.value}`"></div>
              </div>
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
      class="icl-section icl-section--testimonial-9 content-<?php echo $style->content_color->value; ?>">
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
        <div class="section-header mb4 <?php echo $data->alignment->value ?>">
          <div data-aos="fade-up" class="section-title" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo format_heading( $data->title->value); ?></div>
          <span data-aos="fade-up" data-aos-delay="150" class="section-subtitle"><?php echo $data->subtitle->value; ?></span>
        </div>

        <div class="testimonial-grid">
          <?php
            $timer = 300;
            foreach( $data->testimonials->value as $item ): ?>
            <div class="slide icl-testimonial icl-testimonial--style-9" data-aos="fade-up" data-aos-delay="<?php echo $timer; ?>">
              <div class="icl-testimonial__card">
                <div class="icl-testimonial__cite">
                  <span class="icl-testimonial__author"><?php echo $item->author->value;  ?></span>
                  <span class="icl-testimonial__author-title"><?php echo $item->author_title->value;  ?></span>
                </div>

                <blockquote class="icl-testimonial__content mb2"  style="--font-size:<?php echo $data->content_size->value;  ?>px"><?php echo $item->testimonial->value; ?></blockquote>
                
                <div class="star-rating star-rating--<?php echo $item->rating->value; ?>"></div>
              </div>
            </div>
          <?php
            $timer+=150;
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

$testimonial9 = new Testimonial9();
$arrayCode[] = $testimonial9->data();
$arrayTemplate[] = $testimonial9->editorTemplate();