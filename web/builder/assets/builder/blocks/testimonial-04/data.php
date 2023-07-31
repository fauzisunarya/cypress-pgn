<?php

class Testimonial4 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'testimonial-04',
      "title"      => 'Testimonial 4',
      "screenshot" => 'testimonial-04/testimonial-04.jpg',
      "screenshot_size" => array( 600, 435 ),
      "template"   => '#testimonial-04',
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
              "avatar" => array(
                "type" => "image",
                "label" => "Gambar Penulis",
                "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/1.jpg',
              ),
              "author" => array(
                "type" => "text",
                "label" => "Penulis",
                "horizontal" => true,
                "value" => "Zig Ziglar"
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
                "avatar" => array(
                  "type" => "image",
                  "label" => "Gambar Penulis",
                  "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/1.jpg',
                ),
                "author" => array(
                  "type" => "text",
                  "label" => "Penulis",
                  "horizontal" => true,
                  "value" => "Zig Ziglar"
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
                "avatar" => array(
                  "type" => "image",
                  "label" => "Gambar Penulis",
                  "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/2.jpg',
                ),
                "author" => array(
                  "type" => "text",
                  "label" => "Penulis",
                  "horizontal" => true,
                  "value" => "Simon Sinek"
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
                  "value" => "<p>Working hard for something we don't care about is called stressed; working hard for something we love is called passion.</p>"
                ),
                "avatar" => array(
                  "type" => "image",
                  "label" => "Gambar Penulis",
                  "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/3.jpg',
                ),
                "author" => array(
                  "type" => "text",
                  "label" => "Penulis",
                  "horizontal" => true,
                  "value" => "Jonathan Doe"
                ),
              ),
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
                "avatar" => array(
                  "type" => "image",
                  "label" => "Gambar Penulis",
                  "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/1.jpg',
                ),
                "author" => array(
                  "type" => "text",
                  "label" => "Penulis",
                  "horizontal" => true,
                  "value" => "Zig Ziglar"
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
                "avatar" => array(
                  "type" => "image",
                  "label" => "Gambar Penulis",
                  "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/2.jpg',
                ),
                "author" => array(
                  "type" => "text",
                  "label" => "Penulis",
                  "horizontal" => true,
                  "value" => "Simon Sinek"
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
                  "value" => "<p>Working hard for something we don't care about is called stressed; working hard for something we love is called passion.</p>"
                ),
                "avatar" => array(
                  "type" => "image",
                  "label" => "Gambar Penulis",
                  "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/3.jpg',
                ),
                "author" => array(
                  "type" => "text",
                  "label" => "Penulis",
                  "horizontal" => true,
                  "value" => "Jonathan Doe"
                ),
              )
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
      class="icl-section icl-section--testimonial-4"
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

          <masonry-grid :items="data.testimonials.value">
            <div class="slide icl-testimonial icl-testimonial--style-4" v-for="(item, index) in data.testimonials.value" :key="`item-${index}`">
              <div class="icl-testimonial__card">
                <blockquote class="icl-testimonial__content" :style="`--font-size:${data.content_size.value}px`"><InlineEditor v-model="item.testimonial.value"/></blockquote>

                <div class="icl-testimonial__cite">
                  <img class="icl-testimonial__avatar" :src="item.avatar.value">
                  <div class="icl-testimonial__cite-detail">
                    <span class="icl-testimonial__author"><InlineEditor v-model="item.author.value"/></span>
                    <div :class="`star-rating star-rating--${item.rating.value}`"></div>
                  </div>
                </div>
              </div>
            </div>
          </masonry-grid>
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
      class="icl-section icl-section--testimonial-4 content-<?php echo $style->content_color->value; ?>">
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
          <h2 class="section-title" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo format_heading( $data->title->value); ?></h2>
          <span class="section-subtitle"><?php echo $data->subtitle->value; ?></span>
        </div>

        <div class="masonry-grid">
          <?php foreach( $data->testimonials->value as $item ): ?>
            <div class="slide icl-testimonial icl-testimonial--style-4">
              <div class="icl-testimonial__card">
                <blockquote class="icl-testimonial__content"  style="--font-size:<?php echo $data->content_size->value;  ?>px"><?php echo $item->testimonial->value; ?></blockquote>
                <div class="icl-testimonial__cite">
                  <?php
                    $thumbnail_url = strpos( $item->avatar->value , "/blocks-assets/imgs") || strpos( $item->avatar->value , "/stock_image") ? $item->avatar->value : get_image_thumbnail( $item->avatar->value, "tiny" );
                  ?>
                  <img class="icl-testimonial__avatar lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php echo $thumbnail_url; ?>" alt="<?php echo strip_tags($item->author->value); ?>" />

                  <div class="icl-testimonial__cite-detail">
                    <span class="icl-testimonial__author"><?php echo $item->author->value;  ?></span>
                    <div class="star-rating star-rating--<?php echo $item->rating->value; ?>"></div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
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

$testimonial4 = new Testimonial4();
$arrayCode[] = $testimonial4->data();
$arrayTemplate[] = $testimonial4->editorTemplate();