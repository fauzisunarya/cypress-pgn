<?php

class Product8 {

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
      "label"         => "Pesan Sekarang",
      "background"    => "#333333",
      "color"         => "#ffffff",
      "size"          => "is-normal", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-radius", //[square, rounded, pill]
      "icon"          => 'fas fa-shopping-cart',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'product-08',
      "title"      => 'Product 8',
      "screenshot" => 'product-08/product-08.jpg',
      "screenshot_size" => array( 600, 204 ),
      "template"   => '#product-08',
      "category"   => 'product',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Tour Wekaweka Land</strong>',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "horizontal"      => true,
            "value"           => 40,
            "min"             => 18,
            "max"             => 72,
          ),
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Konten',
            "value"           => '<p>3D/2N wisata absurd wekaweka land</p>',
          ),
          "rating" => array(
            "type" => "slider",
            "label" => "Peringkat Bintang",
            "horizontal" => true,
            "value" => 5,
            "min" => 1,
            "max" => 5,
          ),
          "price_unit" => array(
            "type" => "text",
            "label" => "Unit Harga",
            "horizontal" => true,
            "value" => "Per orang"
          ),
          "price" => array(
            "type" => "text",
            "label" => "Harga",
            "horizontal" => true,
            "value" => "Rp. 125.000"
          ),
          "button" => $component->getButton(),
          "images" => array(
            "type" => "repeatable",
            "label" => "Gallery",
            "settings"=> array(
              "image" => array(
                "type" => "image",
                "label" => "Gambar",
                "value" => "https://unsplash.it/100/100"
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
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1531975474574-e9d2732e8386.jpg"
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
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1545922421-2417f6beb2b9.jpg"
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
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1505993597083-3bd19fb75e57.jpg"
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
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1501179691627-eeaa65ea017c.jpg"
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
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1476158085676-e67f57ed9ed7.jpg"
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
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1550664255-94d114340500.jpg"
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
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1523592121529-f6dde35f079e.jpg"
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
                  "value" => $this->base_url . "builder/assets/builder/blocks-assets/imgs/photos/photo-1469967700385-5b0140e16e33.jpg"
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
              "backgroundPosition"   => "top right",
              "backgroundSize"       => "contain",
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
      class="icl-section icl-section--product-8"
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
          <div class="columns is-vcentered" :class="{'row-reverse': data.reverse.value == true }">
            <div :class="`column is-6`">
              <div :class="`section-title`" :style="`--font-size: ${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></div>
              <div :class="`star-rating star-rating--${data.rating.value}`"></div>
              <div class="mt2 mb3" ><InlineEditor v-model="data.content.value" /></div>
              <div class="price">
                <strong class="price-amount"><InlineEditor v-model="data.price.value"/></strong>
                <div class="price-unit"><InlineEditor v-model="data.price_unit.value"/></div>
              </div>
              <Button :data="data.button.value" />
            </div>
            
            <div class="column is-6">
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
      class="icl-section icl-section--product-8 content-<?php echo $style->content_color->value; ?>">
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
          <div class="columns is-vcentered <?php echo $data->reverse->value == true ? "row-reverse" : ""; ?>">
            <div class="column is-6">

              <div data-aos="fade-up" class="section-title" style="--font-size:<?php echo $data->title_size->value; ?>px"><?php echo format_heading( $data->title->value); ?></div>
              <div data-aos="fade-up" data-aos-delay="150" class="star-rating star-rating--<?php echo $data->rating->value;  ?>"></div>
              <div data-aos="fade-up" data-aos-delay="300" class="mt2 mb3"><?php echo $data->content->value;  ?></div>
              <div class="price" data-aos="fade-up" data-aos-delay="450">
                <div class="price-unit"><?php echo $data->price_unit->value; ?></div>
                <strong class="price-amount"><?php echo $data->price->value; ?></strong>
              </div>
              <?php render_button($data->button->value, 'data-aos="fade-up" data-aos-delay="600"'); ?>
            </div>
            
            <div class="column is-6">
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



$product8 = new Product8();
$arrayCode[] = $product8->data();
$arrayTemplate[] = $product8->editorTemplate();