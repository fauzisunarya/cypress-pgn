<?php

class Feature13 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'feature-13',
      "title"      => 'feature 13',
      "screenshot" => 'feature-13/feature-13.jpg',
      "screenshot_size" => array( 894, 525 ),
      "template"   => '#feature-13',
      "category"   => 'feature',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Wonderful Experiences</strong>',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "horizontal"      => true,
            "value"           => 30,
            "min"             => 18,
            "max"             => 72,
            "horizontal" => true,
          ),
          "subtitle" => array(
            "type"            => 'wyswyg',
            "label"           => 'Sub Judul',
            "value"           => 'Enjoy our most memorable attraction for adventurer & family',
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
          "column" => array(
            "type"  => 'slider',
            "horizontal" => true,
            "label" => 'Kolom',
            "min"   => 2,
            "max"   => 4,
            "value" => 4,
          ),
          "features" => array(
            "type"  => "repeatable",
            "label" => "Features",
            "item_title" => "title",
            "label_title" => "Feature",
            "settings" => array(
              "title" => array(
                "type" => "wyswyg",
                "label" => "Judul",
                "heading" => true,
                "horizontal" => true,
                "value" => "<strong>Tempore numquam impedit</strong>"
              ),
              "background" => array(
                "type" => "image",
                "label" => "Background",
                "value" => "https://images.unsplash.com/photo-1519501025264-65ba15a82390?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1080&fit=max&ixid=eyJhcHBfaWQiOjk3MjkwfQ"
              ),
              "background2" => array(
                "type" => "color",
                "label" => "Warna Background Judul",
                "horizontal" => true,
                "value" => "#007bff"
              ),
            ),
            "value"  => array(
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Panduan Diving</strong>"
                ),
                "background" => array(
                  "type" => "image",
                  "label" => "Background",
                  "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/photo-1544551763-92ab472cad5d.jpeg'
                ),
                "background2" => array(
                  "type" => "color",
                  "label" => "Warna Background Judul",
                  "horizontal" => true,
                  "value" => "#007BFF"
                ),
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Muncak Bersama</strong>"
                ),
                "background" => array(
                  "type" => "image",
                  "label" => "Background",
                  "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/photo-1501555088652-021faa106b9b.jpeg'
                ),
                "background2" => array(
                  "type" => "color",
                  "label" => "Warna Background Judul",
                  "horizontal" => true,
                  "value" => "#23FF00"
                ),
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Professional Surfing</strong>"
                ),
                "background" => array(
                  "type" => "image",
                  "label" => "Background",
                  "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/photo-1502680390469-be75c86b636f.jpeg'
                ),
                "background2" => array(
                  "type" => "color",
                  "label" => "Warna Background Judul",
                  "horizontal" => true,
                  "value" => "#5D5D5D"
                ),
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Taman Bermain</strong>"
                ),
                "background" => array(
                  "type" => "image",
                  "label" => "Background",
                  "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/photo-1498196645145-687fd3bfe912.jpeg'
                ),
                "background2" => array(
                  "type" => "color",
                  "label" => "Warna Background Judul",
                  "horizontal" => true,
                  "value" => "#FF00D7"
                ),
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
      class="icl-section icl-section--feature-3"
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

          <Sortable class="feature-grid columns justify-content-center" v-model="data.features.value">
            <SortableItem :class="`column is-${12 / data.column.value}`" v-for="(item, index) in data.features.value" :key="`item-${index}`" :list="data.features.value" :index="index">
              <div class="icl-feature icl-feature--style-3" :style="`background-image: url(${item.background.value}); justify-content: center; background-position: center;`">
                <div class="icl-feature__card" :style="`margin-bottom: 100px; padding: 10px 15px 0; border-radius: 10px; background-color: ${item.background2.value};`">
                  <h2 class="icl-feature__title" ><InlineEditor v-model="item.title.value" /></h2>
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
      class="icl-section icl-section--feature-3 content-<?php echo $style->content_color->value; ?>">
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
          
          <div class="feature-grid columns" style="justify-content: center;">
            <?php foreach( $data->features->value as $item ): ?>
              <?php
                $thumbnail_url = strpos( $item->background->value , "/blocks-assets/imgs") || strpos( $item->background->value , "/stock_image") ? $item->background->value : get_image_thumbnail( $item->background->value, "medium" );
              ?>
              <div class="column is-<?= 12 / $data->column->value; ?>">
                <div class="icl-feature icl-feature--style-3" style="background-image: url(<?php echo $thumbnail_url; ?>); justify-content: center; background-position: center;">
                  <div class="icl-feature__card" style="margin-bottom: 100px; padding: 10px 15px 0; border-radius: 10px; background-color: <?= $item->background2->value; ?>;">
                    <h2 class="icl-feature__title"><?php echo $item->title->value;  ?></h2>
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

$feature13 = new Feature13();
$arrayCode[] = $feature13->data();
$arrayTemplate[] = $feature13->editorTemplate();