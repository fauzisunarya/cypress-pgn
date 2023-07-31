<?php

class Product9 {

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
      "label"         => "Book this room",
      "background"    => "#333333",
      "color"         => "#ffffff",
      "size"          => "is-normal", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-radius", //[square, rounded, pill]
      "icon"          => 'fas fa-shopping-cart',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'product-09',
      "title"      => 'Product 9',
      "screenshot" => 'product-09/product-09.jpg',
      "screenshot_size" => array( 600, 437 ),
      "template"   => '#product-09',
      "category"   => 'product',
        "data" => array(
          "products" => array(
            "type" => "repeatable",
            "label" => "Produk",
            "item_title" => "title",
            "settings" => array(
              "title" => array(
                "type"            => 'wyswyg',
                "label"           => 'Judul',
                "value"           => 'Deluxe Room',
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
                "value"           => '<p>Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus, est deserunt officia adipisci nihil iure modi.</p><p><strong>Bedroom: 1 King Size</strong></p><p><strong>Last Deal: 30 February 2022</strong></p>',
              ),
              "price_unit" => array(
                "type"            => 'text',
                "label"           => 'Price Unit',
                "value"           => 'Price per night',
              ),
              "price" => array(
                "type"            => 'text',
                "label"           => 'Price',
                "value"           => 'Rp. 200.000',
              ),
              "button" => $component->getButton(),
              "image" => array(
                "type" => "image",
                "label" => "Product Image",
                "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/langganan-baru.jpg'
              ),
            ),
            "value" => array(
              array(
                "title" => array(
                  "type"            => 'wyswyg',
                  "label"           => 'Judul',
                  "value"           => 'Standard Room',
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
                  "value"           => '<p>Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus, est deserunt officia adipisci nihil iure modi.</p><p><strong>Bedroom: 1 King Size</strong></p><p><strong>Last Deal: 30 February 2022</strong></p>',
                ),
                "price_unit" => array(
                  "type"            => 'text',
                  "label"           => 'Price Unit',
                  "value"           => 'Price per night',
                ),
                "price" => array(
                  "type"            => 'text',
                  "label"           => 'Price',
                  "value"           => 'Rp. 200.000',
                ),
                "button" => $component->getButton(),
                "image" => array(
                  "type" => "image",
                  "label" => "Product Image",
                  "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/bayar-tagihan-indihome.jpg'
                ),
              ),
              array(
                "title" => array(
                  "type"            => 'wyswyg',
                  "label"           => 'Judul',
                  "value"           => 'Deluxe Room',
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
                  "value"           => '<p>est deserunt officia adipisci nihil iure modi. Soluta velit voluptatem doloribus dolore molestiae dolor natus labore sunt delectus</p><p><strong>Bedroom: 1 King Size</strong></p><p><strong>Last Deal: 30 February 2022</strong></p>',
                ),
                "price_unit" => array(
                  "type"            => 'text',
                  "label"           => 'Price Unit',
                  "value"           => 'Price per night',
                ),
                "price" => array(
                  "type"            => 'text',
                  "label"           => 'Price',
                  "value"           => 'Rp. 200.000',
                ),
                "button" => $component->getButton(),
                "image" => array(
                  "type" => "image",
                  "label" => "Product Image",
                  "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/add-on.jpg'
                ),
              )
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
      class="icl-section icl-section--product-6"
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
          <div
            v-for="item in data.products.value"
            class="icl-product-item columns is-vcentered is-variable is-8" :class="{'row-reverse': data.reverse.value == true }">
            <div :class="`column is-6`">

              <div :class="`section-title`" :style="`--font-size: ${item.title_size.value}px`"><InlineEditor v-model="item.title.value" /></div>
              <div class="mt2 mb3" ><InlineEditor v-model="item.content.value" /></div>
              <div class="price">
                <div class="price-unit"><InlineEditor v-model="item.price_unit.value"/></div>
                <strong class="price-amount"><InlineEditor v-model="item.price.value"/></strong>
              </div>
              <Button :data="item.button.value" />
            </div>
            <div class="column is-6">
              <img :src="item.image.value" alt="item.title.value" class="image-styled">
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
      class="icl-section icl-section--product-6 content-<?php echo $style->content_color->value; ?>">
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
          <?php foreach( $data->products->value as $item ): ?>
          <div class="icl-product-item columns is-vcentered is-variable is-8 <?php echo $data->reverse->value == true ? "row-reverse" : ""; ?>">
            <div class="column is-6">

              <div data-aos="fade-up" class="section-title" style="--font-size:<?php echo $item->title_size->value; ?>px"><?php echo $item->title->value;  ?></div>
              <div data-aos="fade-up" data-aos-delay="150" class="mt2 mb3"><?php echo $item->content->value;  ?></div>
              <div class="price" data-aos="fade-up" data-aos-delay="300">
                <div class="price-unit"><?php echo $item->price_unit->value; ?></div>
                <strong class="price-amount"><?php echo $item->price->value; ?></strong>
              </div>
              <?php render_button($item->button->value, 'data-aos="fade-up" data-aos-delay="450"'); ?>
            </div>
            <div class="column is-6">
              <img src="<?php echo $item->image->value; ?>" alt="<?php echo strip_tags($item->title->value); ?>" class="image-styled">
            </div>
          </div>
          <?php endforeach; ?>
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



$product9 = new Product9();
$arrayCode[] = $product9->data();
$arrayTemplate[] = $product9->editorTemplate();