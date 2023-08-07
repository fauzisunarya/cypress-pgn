<?php

class Feature5 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'feature-05',
      "title"      => 'feature 5',
      "screenshot" => 'feature-05/feature-05.jpg',
      "screenshot_size" => array( 600, 154 ),
      "template"   => '#feature-05',
      "category"   => 'feature',
        "data" => array(
          "features" => array(
            "type"  => "repeatable",
            "label" => "Feature",
            "item_title" => "title",
            "label_title" => "Feature",
            "settings" => array(
              "icon" => array(
                "type" => "icon",
                "label" => "Ikon",
                "value" => 'fas fa-star',
              ),
              "title" => array(
                "type" => "text",
                "label" => "Judul",
                "horizontal" => true,
                "value" => "Tempore numquam impedit"
              ),
            ),
            "value"  => array(
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Ikon",
                  "value" => 'fas fa-hotel',
                ),
                "title" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "horizontal" => true,
                  "value" => "Villa Premium"
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Ikon",
                  "value" => 'fas fa-comments',
                ),
                "title" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "horizontal" => true,
                  "value" => "Layanan 24/7"
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Ikon",
                  "value" => 'fas fa-hand-holding-heart',
                ),
                "title" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "horizontal" => true,
                  "value" => "Pelayanan Ramah"
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Ikon",
                  "value" => 'fas fa-medal',
                ),
                "title" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "horizontal" => true,
                  "value" => "Jaminan Mutu"
                ),
              ),
            ),
          ),

          "icon_style" => array(
            "type" => "select",
            "label" => "Feature icon style",
            "value" => "style-circle-outline",
            "horizontal" => true,
            "options" => array(
              "style-normal" => "Regular",
              "style-circle" => "Circle",
              "style-circle-outline" => "Circle outline",
              "style-square" => "Square",
              "style-rounded" => "rounded",
            )
          ),
          "icon_size" => array(
            "type" => "slider",
            "label" => "Icon Size",
            "value" => 30,
            "horizontal" => true,
            "min" => 10,
            "max" => 100,
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
              "backgroundColor"      => "#FFB400",
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
      class="icl-section icl-section--feature-1"
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
          <Sortable class="feature-grid columns" v-model="data.features.value">
            <SortableItem class="column is-3" v-for="(item, index) in data.features.value" :key="`item-${index}`" :list="data.features.value" :index="index">
              <div class="icl-feature icl-feature--style-1">
                <div class="icl-feature__card">

                  <span :class="`icl-feature__icon ${data.icon_style.value}`" :style="{fontSize:data.icon_size.value+'px'}"><Icon v-model="item.icon.value" /></span>
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
      class="icl-section icl-section--feature-1 content-<?php echo $style->content_color->value; ?>">
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
          
          <div class="feature-grid columns">
            <?php foreach( $data->features->value as $item ): ?>
              <div class="column is-3">
                <div class="icl-feature icl-feature--style-1">
                  <div class="icl-feature__card">

                    <span class="icl-feature__icon <?php echo $data->icon_style->value; ?>" style="font-size:<?php echo $data->icon_size->value; ?>px"><?php echo get_font_awesome_svg($item->icon->value); ?></span>
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

$feature5 = new Feature5();
$arrayCode[] = $feature5->data();
$arrayTemplate[] = $feature5->editorTemplate();