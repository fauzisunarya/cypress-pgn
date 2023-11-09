<?php

class Product12 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Component.php";
    require_once BASE_BUILDER_PATH . "/Render.php";

    $component = new Component();
    $component->setButton(array(
      "enable"        => true,
      "url"           => "http://example.com",
      "new_window"    => true,
      "label"         => "Buy Now",
      "background"    => "#FA2B56",
      "color"         => "#ffffff",
      "size"          => "is-medium", // [small, normal, medium, large]
      "style"         => "is-fill", // [fill, outline]
      "corner"        => "is-radius", // [square, rounded, pill]
      "icon"          => '',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'product-12',
      "title"      => 'Product 12',
      "screenshot" => 'product-12/product-12.jpg',
      "screenshot_size" => array( 600, 295 ),
      "template"   => '#product-12',
      "category"   => 'product',
        "data" => array(
          "top_title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul Atas',
            "heading"         => true,
            "value"           => '<strong>PRODUCT OF THE WEEK</strong>',
          ),
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>White Sneaker</strong>',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "value"           => 48,
            "min"             => 18,
            "max"             => 72,
          ),
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Konten',
            "value"           => 'White sneaker with red accent color',
          ),
          "content_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "value"           => 20,
            "min"             => 18,
            "max"             => 72,
          ),
          "price" => array(
            "type" => "wyswyg",
            "label" => "Harga",
            "value" => "<strong>Rp. 154.000</strong> <s>Rp. 300.000</s>"
          ),
          "button" => $component->getButton(),

          "alignment" => array(
            "type"       => 'radio-icon',
            "label"      => 'Alignment',
            "value"      => 'has-text-left',
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
            "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/promo-indihome.jpg'
          ),
          "images" => array(
            "type" => "repeatable",
            "label" => "Gallery",
            "settings"=> array(
              "image" => array(
                "type" => "image",
                "label" => "Gambar",
                "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/add-on.jpg"
              ),
              "caption" => array(
                "type" => "text",
                "label" => "Keterangan Gambar",
                "value" => "Keterangan Gambar"
              )
            ),
            "value" => array(
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Gambar",
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/langganan-baru.jpg"
                ),
                "caption" => array(
                  "type" => "text",
                  "label" => "Keterangan Gambar",
                  "value" => "Keterangan Gambar"
                )
              ),
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Gambar",
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/bayar-tagihan-indihome.jpg"
                ),
                "caption" => array(
                  "type" => "text",
                  "label" => "Keterangan Gambar",
                  "value" => "Keterangan Gambar"
                )
              ),
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Gambar",
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/add-on.jpg"
                ),
                "caption" => array(
                  "type" => "text",
                  "label" => "Keterangan Gambar",
                  "value" => "Keterangan Gambar"
                )
              ),
            )
          ),
          "reverse" => array(
            "type"            => 'switch',
            "label"           => 'Tukar Layout',
            "horizontal"      => true,
            "value"           => true,
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
              "top" => 0,
              "bottom" => 0,
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
              "backgroundColor"      => "#902635",
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
      class="icl-section icl-section--product-11"
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
          <div class="columns is-vcentered is-variable is-8" :class="{'row-reverse': data.reverse.value == true }">
            <div class="column is-half">
              <img :src="data.image.value">
            </div>
            <div :class="`column is-half column-content ${data.alignment.value}`">
              <div class="section-title --top"><InlineEditor v-model="data.top_title.value"/></div>
              <div :class="`section-title font-light mb3`" :style="`--font-size: ${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></div>
              <div class="mb3" :style="`font-size: ${data.content_size.value}px`"><InlineEditor v-model="data.content.value" /></div>
              
              <div class="product-action">
                <Button :data="data.button.value"/>
                <span class="product-price"><InlineEditor v-model="data.price.value" /></span>
              </div>

              <div class="icl-image-gallery">
                <a :href="item.image.value" v-for="item in data.images.value" title="item.caption.value">
                  <span class="image-ratio"><img :src="item.image.value" alt="item.caption.value"></span>
                </a>
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
      class="icl-section icl-section--product-11 content-<?php echo $style->content_color->value; ?>">
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
          <div class="columns is-vcentered is-variable is-8 <?php echo $data->reverse->value == true ? "row-reverse" : ""; ?>">
            <div class="column is-half">
              <img src="<?php echo $data->image->value; ?>">
            </div>
            <div class="column is-half column-content <?php echo $data->alignment->value; ?>">
              <div data-aos="fade-up" class="section-title --top"><?php echo $data->top_title->value; ?></div>
              <div data-aos="fade-up" data-aos-delay="150" class="section-title font-light mb3" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></div>
              <div data-aos="fade-up" data-aos-delay="300" style="font-size:<?php echo $data->content_size->value; ?>px" class="mb3"><?php echo $data->content->value; ?></div>

              <div data-aos="fade-up" data-aos-delay="450" class="product-action">
                <?php
                  render_button($data->button->value, "");
                ?>
                <span class="product-price"><?php echo $data->price->value; ?></span>    
              </div>

              <div class="icl-image-gallery" data-aos="fade-up" data-aos-delay="750">
                <?php foreach( $data->images->value as $item ): ?>
                <a href="<?php echo $item->image->value; ?>" title="<?php echo $item->caption->value; ?>">
                  <span class="image-ratio">
                    <?php
                      $thumbnail_url = strpos( $item->image->value , "/blocks-assets/imgs") || strpos( $item->image->value , "/stock_image") ? $item->image->value : get_image_thumbnail( $item->image->value, "small" );
                    ?>
                    <img class="lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php echo $thumbnail_url; ?>" alt="<?php echo strip_tags($item->caption->value); ?>">
                  </span>
                </a>
                <?php endforeach; ?>
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


$product12 = new Product12();
$arrayCode[] = $product12->data();
$arrayTemplate[] = $product12->editorTemplate();