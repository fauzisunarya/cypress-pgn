<?php

class Feature12 {

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
      "label"         => "CHAT WITH AN EXPERTS",
      "background"    => "#5e89fb",
      "color"         => "#fff",
      "size"          => "is-medium", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-square", //[square, rounded, pill]
      "icon"          => 'fas fa-arrow-right',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'feature-12',
      "title"      => 'feature 12',
      "screenshot" => 'feature-12/feature-12.jpg',
      "screenshot_size" => array( 600, 409 ),
      "template"   => '#feature-12',
      "category"   => 'feature',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>We Design Custom Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis, quidem.</strong>',
          ),
          "title_size" => array(
            "type"       => 'slider',
            "label"      => 'Ukuran Judul',
            "horizontal" => true,
            "value"      => 30,
            "min"        => 18,
            "max"        => 72,
            "horizontal" => true,
          ),
          "image" => array(
            "type" => "image",
            "label" => "Feature Image",
            "value" => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/tech.jpeg'
          ),
          "button" => $component->getButton(),
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
          

          "spacer-1" => array( "type"=>"spacer" ),
          "features" => array(
            "type"  => "repeatable",
            "label" => "Features",
            "item_title" => "title",
            "label_title" => "Feature",
            "settings" => array(
              "icon" => array(
                "type" => "icon",
                "label" => "Feature Icon",
                "value" => 'fas fa-check',
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
                  "type" => "icon",
                  "label" => "Feature Icon",
                  "value" => 'fas fa-american-sign-language-interpreting',
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Similique nisi</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Eligendi facere rem nostrum optio pariatur, nobis tempore numquam impedit odit quo porro molestias error mollitia.</p>"
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Feature Icon",
                  "value" => 'fas fa-biking',
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Minima facilis</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>nobis tempore numquam impedit deleniti nostrum optio pariatur, odit quo porro molestias error mollitia.</p>"
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Feature Icon",
                  "value" => 'fas fa-horse',
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Aliquam itaque</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Odit quo porro molestias error mollitia eligendi recusandae libero temporibus quibusdam obcaecati deleniti.</p>"
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Feature Icon",
                  "value" => 'fas fa-crow',
                ),
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Voluptates dolorem</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Odit quo porro molestias error mollitia eligendi recusandae libero temporibus quibusdam obcaecati deleniti.</p>"
                ),
              ),
            ),
          ),

          "icon_style" => array(
            "type" => "select",
            "label" => "Feature icon style",
            "value" => "style-normal",
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
            "value" => 40,
            "horizontal" => true,
            "min" => 10,
            "max" => 100,
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
              "backgroundColor"      => "#FFFFFF",
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
            "value" => "rgba(255,255,255,0)"
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
      class="icl-section icl-section--feature-12"
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

          <div class="columns is-variable is-8 is-vcentered" :class="{'row-reverse': data.reverse.value == true }">
            <div class="column">
              <div :class="`section-header mb4 ${data.alignment.value}`">
                <h2 class="section-title mb3" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value" /></h2>
                <img class="mb3" :src="data.image.value" alt="">
                <Button :data="data.button.value"/>
              </div>
            </div>
            <div class="column">
              <Sortable class="feature-grid" v-model="data.features.value">
                <SortableItem class="icl-feature icl-feature--style-1" v-for="(item, index) in data.features.value" :key="`item-${index}`" :list="data.features.value" :index="index">
                  <span :class="`icl-feature__icon ${data.icon_style.value}`" :style="{fontSize:data.icon_size.value+'px'}"><Icon v-model="item.icon.value" /></span>
                  <div class="icl-feature__detail">
                    <h2 class="icl-feature__title" ><InlineEditor v-model="item.title.value" /></h2>
                    <div class="icl-feature__content" ><InlineEditor v-model="item.content.value" /></div>
                  </div>
                </SortableItem>
              </Sortable>
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
      class="icl-section icl-section--feature-12 content-<?php echo $style->content_color->value; ?>">
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
          
          <div class="columns is-variable is-8 is-vcentered <?php echo $data->reverse->value == true ? 'row-reverse' : ''; ?>">

            <div class="column">
              <div class="section-header mb4 <?php echo $data->alignment->value ?>">
                <h2 class="section-title mb3" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo format_heading( $data->title->value); ?></h2>
                <?php $thumbnail_url = strpos( $data->image->value , "/blocks-assets/imgs") || strpos( $data->image->value , "/stock_image") ? $data->image->value : get_image_thumbnail( $data->image->value, "medium" ); ?>
                <img class="mb3" src="<?php echo $thumbnail_url; ?>" alt="<?php echo strip_tags($data->title->value); ?>">
                <?php render_button($data->button->value, ''); ?>
              </div>
            </div>

            <div class="column">
              <div class="feature-grid">
                <?php foreach( $data->features->value as $item ): ?>
                  <div class="icl-feature icl-feature--style-1">
                    <span class="icl-feature__icon <?php echo $data->icon_style->value; ?>" style="font-size:<?php echo $data->icon_size->value; ?>px"><?php echo get_font_awesome_svg($item->icon->value); ?></span>
                    <div class="icl-feature__detail">
                      <h2 class="icl-feature__title"><?php echo $item->title->value;  ?></h2>
                      <div class="icl-feature__content">
                        <?php echo $item->content->value;  ?>
                      </div>
                    </div>
                  </div>
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





$feature12 = new Feature12();
$arrayCode[] = $feature12->data();
$arrayTemplate[] = $feature12->editorTemplate();