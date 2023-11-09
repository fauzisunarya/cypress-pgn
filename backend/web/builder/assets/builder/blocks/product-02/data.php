<?php

class Product2 {

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
      "label"         => "Beli sekarang",
      "background"    => "#007bff",
      "color"         => "#ffffff",
      "size"          => "is-normal", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-radius", //[square, rounded, pill]
      "icon"          => 'fas fa-arrow-right',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'product-02',
      "title"      => 'Product 2',
      "screenshot" => 'product-02/product-02.jpg',
      "screenshot_size" => array( 600, 380 ),
      "template"   => '#product-02',
      "category"   => 'product',
        "data" => array(
          "column" => array(
            "type"  => 'slider',
            "horizontal" => true,
            "label" => 'Kolom',
            "min"   => 2,
            "max"   => 4,
            "value" => 3,
          ),
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Produk & Aksesoris Terkini</strong>',
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
            "value"           => 'Just drag and drop you\'re ready to go',
          ),
          "alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Rata Teks',
            "horizontal"      => true,
            "value"           => 'has-text-left',
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
          "products" => array(
            "type"  => "repeatable",
            "label" => "Pricing",
            "item_title" => "title",
            "label_title" => "Feature",
            "settings" => array(
              "image" => array(
                "type" => "image",
                "label" => "Image",
                "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/add-on.jpg',
              ),
              "title" => array(
                "type" => "wyswyg",
                "label" => "Judul",
                "heading" => true,
                "horizontal" => true,
                "value" => "<strong>Starter</strong>"
              ),
              "price" => array(
                "type" => "text",
                "label" => "Subtitle",
                "horizontal" => true,
                "value" => "Rp. 125.000"
              ),
              "button" => $component->getButton(),
            ),
            "value"  => array(
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Image",
                  "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/bayar-tagihan-indihome.jpg'
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>iPhone 16XXL</strong>"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "Rp. 25.000.000"
                ),
                "button" => $component->getButton(),
              ),
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Image",
                  "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/bayar-tagihan-indihome.jpg'
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Smart wrist watch</strong>"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "Rp. 5.000.000"
                ),
                "button" => $component->getButton(),
              ),
              array(
                "image" => array(
                  "type" => "image",
                  "label" => "Image",
                  "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/langganan-baru.jpg'
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Mekbuk Prof 15Inch</strong>"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "Rp. 35.000.000"
                ),
                "button" => $component->getButton(),
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
              "backgroundColor"      => "#ffffff",
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
      class="icl-section icl-section--product-2"
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

          <Sortable class="product-grid columns is-centered" v-model="data.products.value">
            <SortableItem :class="`column is-${12 / data.column.value}`" v-for="(item, index) in data.products.value"  :key="`item-${index}`" :list="data.products.value" :index="index">
              <div class="icl-product icl-product--style-2">
                <div class="icl-product__card">
                  <div class="icl-product__image">
                    <img :src="item.image.value" alt="item.title.value">
                  </div>
                  <div class="icl-product__detail">
                    <div class="icl-product__header">
                      <div class="icl-product__header-detail">
                        <h2 class="icl-product__title" ><InlineEditor v-model="item.title.value" /></h2>
                        <small class="icl-product__subtitle"><InlineEditor v-model="item.price.value"/></small>
                      </div>
                      
                    </div>
                    
                    <Button :data="item.button.value"/>
                  </div>
                </div>
                  
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
      class="icl-section icl-section--product-2 content-<?php echo $style->content_color->value; ?>">
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
          
          <div class="product-grid columns is-centered">
            <?php foreach( $data->products->value as $item ): ?>
              <div class="column is-<?= 12 / $data->column->value; ?>">
                <div class="icl-product icl-product--style-2">
                  <div class="icl-product__card">
                    <div class="icl-product__image">
                      <?php
                        $thumbnail_url = strpos( $item->image->value , "/blocks-assets/imgs") || strpos( $item->image->value , "/stock_image") ? $item->image->value : get_image_thumbnail( $item->image->value, "small" );
                      ?>
                      <img class="lazy" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php echo $thumbnail_url; ?>" alt="<?php echo strip_tags($item->title->value); ?>" />
                    </div>
                    <div class="icl-product__detail">
                      <div class="icl-product__header">
                        <div class="icl-product__header-detail">
                          <h2 class="icl-product__title"><?php echo $item->title->value;  ?></h2>
                          <small class="icl-product__subtitle"><?php echo $item->price->value;  ?></small>
                        </div>
                      </div>
                      <?php render_button($item->button->value, ''); ?>
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



$product2 = new Product2();
$arrayCode[] = $product2->data();
$arrayTemplate[] = $product2->editorTemplate();