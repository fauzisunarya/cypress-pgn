<?php

class Content23 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'content-23',
      "title"      => 'Content 23',
      "screenshot" => 'content-23/content-23.jpg',
      "screenshot_size" => array( 600, 182 ),
      "template"   => '#content-23',
      "category"   => 'content',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Our Benefits</strong>',
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
          "stats" => array(
            "type"  => "repeatable",
            "label" => "Feature",
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
              "number" => array(
                "type" => "text",
                "label" => "Nilai",
                "horizontal" => true,
                "value" => 0
              ),
              "size_number" => array(
                "type" => "slider",
                "label" => "Ukuran number",
                "horizontal" => true,
                "value" => 48,
                "min" => 10,
                "max" => 100,
              ),
              "unit" => array(
                "type" => "text",
                "label" => "Satuan",
                "horizontal" => true,
                "value" => "%"
              ),
              "size_unit" => array(
                "type" => "slider",
                "label" => "Ukuran unit",
                "horizontal" => true,
                "value" => 48,
                "min" => 10,
                "max" => 100,
              ),
            ),
            "value"  => array(
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Annual Savings</strong>"
                ),
                "number" => array(
                  "type" => "text",
                  "label" => "Nilai",
                  "horizontal" => true,
                  "value" => 500
                ),
                "size_number" => array(
                  "type" => "slider",
                  "label" => "Ukuran number",
                  "horizontal" => true,
                  "value" => 48,
                  "min" => 10,
                  "max" => 100,
                ),
                "unit" => array(
                  "type" => "text",
                  "label" => "Satuan",
                  "horizontal" => true,
                  "value" => "K"
                ),
                "size_unit" => array(
                  "type" => "slider",
                  "label" => "Ukuran unit",
                  "horizontal" => true,
                  "value" => 48,
                  "min" => 10,
                  "max" => 100,
                ),
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Return Users</strong>"
                ),
                "number" => array(
                  "type" => "text",
                  "label" => "Nilai",
                  "horizontal" => true,
                  "value" => 12
                ),
                "size_number" => array(
                  "type" => "slider",
                  "label" => "Ukuran number",
                  "horizontal" => true,
                  "value" => 48,
                  "min" => 10,
                  "max" => 100,
                ),
                "unit" => array(
                  "type" => "text",
                  "label" => "Satuan",
                  "horizontal" => true,
                  "value" => "M"
                ),
                "size_unit" => array(
                  "type" => "slider",
                  "label" => "Ukuran unit",
                  "horizontal" => true,
                  "value" => 48,
                  "min" => 10,
                  "max" => 100,
                ),
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Sales Increase</strong>"
                ),
                "number" => array(
                  "type" => "text",
                  "label" => "Nilai",
                  "horizontal" => true,
                  "value" => 20
                ),
                "size_number" => array(
                  "type" => "slider",
                  "label" => "Ukuran number",
                  "horizontal" => true,
                  "value" => 48,
                  "min" => 10,
                  "max" => 100,
                ),
                "unit" => array(
                  "type" => "text",
                  "label" => "Satuan",
                  "horizontal" => true,
                  "value" => "%"
                ),
                "size_unit" => array(
                  "type" => "slider",
                  "label" => "Ukuran unit",
                  "horizontal" => true,
                  "value" => 48,
                  "min" => 10,
                  "max" => 100,
                ),
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Traffic Boost</strong>"
                ),
                "number" => array(
                  "type" => "text",
                  "label" => "Nilai",
                  "horizontal" => true,
                  "value" => 18
                ),
                "size_number" => array(
                  "type" => "slider",
                  "label" => "Ukuran number",
                  "horizontal" => true,
                  "value" => 48,
                  "min" => 10,
                  "max" => 100,
                ),
                "unit" => array(
                  "type" => "text",
                  "label" => "Satuan",
                  "horizontal" => true,
                  "value" => "%"
                ),
                "size_unit" => array(
                  "type" => "slider",
                  "label" => "Ukuran unit",
                  "horizontal" => true,
                  "value" => 48,
                  "min" => 10,
                  "max" => 100,
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
      class="icl-section icl-section--content-23"
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

          <Sortable class="stat-grid" data-columns="4" v-model="data.stats.value">
            <SortableItem class="icl-stat" v-for="(stat,index) in data.stats.value"  :key="`item-${index}`" :list="data.stats.value" :index="index">
              <h2 class="icl-stat__title" ><InlineEditor v-model="stat.title.value" /></h2>
              <div class="icl-stat__number">
                <div class="value" :style="{fontSize: `${stat.size_number.value}px`}">{{stat.number.value}}</div><div class="unit" :style="{fontSize: `${stat.size_unit.value}px` }">{{stat.unit.value}}</div>
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
      class="icl-section icl-section--content-23 content-<?php echo $style->content_color->value; ?>">
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
            <?php if(format_heading($data->title->value) != ''): ?>
              <h2 class="section-title" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo format_heading( $data->title->value); ?></h2>
            <?php endif; ?>

            <?php if(format_heading($data->subtitle->value) != ''): ?>
              <span class="section-subtitle"><?php echo $data->subtitle->value; ?></span>
            <?php endif; ?>
          </div>
          
          <div class="stat-grid" data-columns="4">
            <?php foreach( $data->stats->value as $stat ): ?>
            <div class="icl-stat">
              <?php if(format_heading($stat->title->value) != ''): ?>
                <h2 class="icl-stat__title"><?php echo $stat->title->value; ?></h2>
              <?php endif; ?>

              <div class="icl-stat__number">
                <span class="value" style="font-size:<?php echo $stat->size_number->value; ?>px;"><?php echo $stat->number->value; ?></span><span class="unit" style="font-size:<?php echo $stat->size_unit->value; ?>px;"><?php echo $stat->unit->value; ?></span>
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

$content23 = new Content23();
$arrayCode[] = $content23->data();
$arrayTemplate[] = $content23->editorTemplate();