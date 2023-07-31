<?php

class Content11 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'content-11',
      "title"      => 'Content 11',
      "screenshot" => 'content-11/content-11.jpeg',
      "screenshot_size" => array( 600, 303 ),
      "template"   => '#content-11',
      "category"   => 'content',
        "data" => array(
          "subtitle" => array(
            "type"            => 'text',
            "label"           => 'Sub Judul',
            "horizontal"      => true,
            "value"           => 'Layanan Kami',
          ),
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Apa yang kami tawarkan</strong>',
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
          "spacer-1"=> array("type"=>"spacer"),
          "features" => array(
            "type"  => "repeatable",
            "label" => "Fiture/Servis",
            "item_title" => "title",
            "label_title" => "Feature",
            "limit" => 4,
            "settings" => array(
              "icon" => array(
                "type" => "image",
                "label" => "Image",
                "value" =>  $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/gamer.jpg',
              ),
              "title" => array(
                "type" => "wyswyg",
                "label" => "Judul",
                "heading" => true,
                "horizontal" => true,
                "value" => "<strong>Tempore numquam impedit</strong>"
              ),
              "content" => array(
                "type" => "wyswyg",
                "label" => "Content",
                "value" => "<p>Eligendi facere rem nostrum optio pariatur, nobis tempore numquam impedit, recusandae libero temporibus quibusdam obcaecati deleniti, odit quo porro molestias error mollitia.</p>"
              ),
            ),
            "value"  => array(
              array(
                "icon" => array(
                  "type" => "image",
                  "label" => "Image",
                  "value" =>  $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/group.jpg',
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Lorem ipsum dolor</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Eligendi facere rem nostrum optio pariatur, nobis tempore numquam impedit odit quo porro molestias error mollitia.</p>"
                ),
              ),
              array(
                "icon" => array(
                  "type" => "image",
                  "label" => "Image",
                  "value" =>  $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/watching-tv.jpg',
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Lorem ipsum dolor</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>nobis tempore numquam impedit deleniti nostrum optio pariatur, odit quo porro molestias error mollitia.</p>"
                ),
              ),
              array(
                "icon" => array(
                  "type" => "image",
                  "label" => "Image",
                  "value" =>  $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/gamer.jpg',
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Lorem ipsum dolor</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Odit quo porro molestias error mollitia eligendi recusandae libero temporibus quibusdam obcaecati deleniti.</p>"
                ),
              ),
            ),
          ),
          "feature_title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul Item',
            "horizontal"      => true,
            "value"           => 20,
            "min"             => 10,
            "max"             => 72,
            "horizontal" => true,
          ),
          "item_alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Rata Teks Item',
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
      class="icl-section icl-section--content-11"
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
            <span class="section-subtitle"><InlineEditor v-model="data.subtitle.value"/></span>
            <h2 class="section-title" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></h2>
          </div>

          <Sortable class="feature-grid columns justify-content-center" v-model="data.features.value">
            <SortableItem class="column is-3" v-for="(item, index) in data.features.value"  :key="`item-${index}`" :list="data.features.value" :index="index">
              <div class="icl-feature icl-feature--style-1">
                <div :class="`icl-feature__card ${data.item_alignment.value}`">

                  <img :src="item.icon.value" class="mb2" />
                  <h2 class="icl-feature__title" :style="{fontSize: `${data.feature_title_size.value}px`}" ><InlineEditor v-model="item.title.value" /></h2>
                  <div class="icl-feature__content" ><InlineEditor v-model="item.content.value" /></div>
                  
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
      class="icl-section icl-section--content-11 content-<?php echo $style->content_color->value; ?>">
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

        <?php $dataTitleValue = format_heading($data->title->value); ?>
        <div class="content-spacer" style="padding: <?php echo implode(" ", $padding); ?>">
          <div class="section-header mb4 <?php echo $data->alignment->value ?>">
            <?php if(format_heading($data->subtitle->value) != ''): ?>
              <span class="section-subtitle"><?php echo $data->subtitle->value; ?></span>
            <?php endif; ?>

            <?php if( trim($dataTitleValue) != "" ): ?>
              <h2 class="section-title" style="--font-size: <?php echo $data->title_size->value; ?>px"><?php echo $dataTitleValue; ?></h2>
            <?php endif; ?>
          </div>
          
          <div class="feature-grid columns justify-content-center">
            <?php foreach( $data->features->value as $item ): ?>
              <div class="column is-3">
                <div class="icl-feature icl-feature--style-1">
                  <div class="icl-feature__card <?php echo $data->item_alignment->value; ?>">
                    <?php if(!empty($item->icon->value)) : ?>
                      <?php $thumbnail_url = strpos( $item->icon->value , "/blocks-assets/imgs") || strpos( $item->icon->value , "/stock_image") ? $item->icon->value : get_image_thumbnail( $item->icon->value, "medium" );?>
                      <figure class="icl-feature__image"><img src="<?php echo $thumbnail_url;  ?>"/></figure>
                    <?php endif; ?>

                    <?php if(format_heading($item->title->value)) : ?>
                      <h2 class="icl-feature__title" style="font-size:<?php echo $data->feature_title_size->value; ?>px;"><?php echo $item->title->value;  ?></h2>
                    <?php endif; ?>
                    
                    <?php if(format_heading($item->content->value)) : ?>
                      <div class="icl-feature__content">
                        <?php echo $item->content->value;  ?>
                      </div>
                    <?php endif; ?>
                    
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

$content11 = new Content11();
$arrayCode[] = $content11->data();
$arrayTemplate[] = $content11->editorTemplate();