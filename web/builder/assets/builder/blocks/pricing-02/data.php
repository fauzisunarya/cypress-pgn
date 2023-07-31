<?php

class Pricing2 {

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
      "label"         => "Choose Free",
      "background"    => "#007bff",
      "color"         => "#ffffff",
      "size"          => "is-normal", // [small, normal, medium, large]
      "style"         => "is-fill", // [fill, outline ]
      "corner"        => "is-radius", // [square, rounded, pill]
      "icon"          => 'fas fa-arrow-right',
      "icon_position" => "right"
    ));

    $component2 = new Component();
    $component2->setButton(array(
      "enable"        => true,
      "url"           => "http://example.com",
      "new_window"    => true,
      "label"         => "Choose FREE",
      "background"    => "#007bff",
      "color"         => "#ffffff",
      "size"          => "is-normal", // [small, normal, medium, large]
      "style"         => "is-fill", // [fill, outline ]
      "corner"        => "is-radius", // [square, rounded, pill]
      "icon"          => 'fas fa-arrow-right',
      "icon_position" => "right"
    ));

    $component3 = new Component();
    $component3->setButton(array(
      "enable"        => true,
      "url"           => "http://example.com",
      "new_window"    => true,
      "label"         => "Choose LIGHT",
      "background"    => "#007bff",
      "color"         => "#ffffff",
      "size"          => "is-normal", // [small, normal, medium, large]
      "style"         => "is-fill", // [fill, outline ]
      "corner"        => "is-radius", // [square, rounded, pill]
      "icon"          => 'fas fa-arrow-right',
      "icon_position" => "right"
    ));

    $component4 = new Component();
    $component4->setButton(array(
      "enable"        => true,
      "url"           => "http://example.com",
      "new_window"    => true,
      "label"         => "Choose Pro",
      "background"    => "#007bff",
      "color"         => "#ffffff",
      "size"          => "is-normal", // [small, normal, medium, large]
      "style"         => "is-fill", // [fill, outline ]
      "corner"        => "is-radius", // [square, rounded, pill]
      "icon"          => 'fas fa-arrow-right',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'pricing-02',
      "title"      => 'Pricing 2',
      "screenshot" => 'pricing-02/pricing-02.jpg',
      "screenshot_size" => array( 600, 281 ),
      "template"   => '#pricing-02',
      "category"   => 'pricing',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Pricing</strong>',
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

          "spacer-1"=> array("type"=>"spacer"),

          "column" => array(
            "type" => "slider",
            "label" => "Kolom",
            "min" => 2,
            "max" => 4,
            "value" => 3
          ),

        "package_link" => array(
          "type" => "package-link",
          "horizontal" => true,
          "label" => "Link Berlangganan",
          "value" => ""
        ),

        "pricing" => array(
            "type"  => "repeatable",
            "label" => "Pricing",
            "item_title" => "title",
            "label_title" => "Feature",
            "settings" => array(
              "title" => array(
                "type" => "wyswyg",
                "label" => "Judul",
                "heading" => true,
                "horizontal" => true,
                "value" => "<strong>$0/month</strong>"
              ),
              "subtitle" => array(
                "type" => "text",
                "label" => "Subtitle",
                "horizontal" => true,
                "value" => "FREE"
              ),
              "content" => array(
                "type" => "wyswyg",
                "label" => "Content",
                "value" => "<p>Eligendi facere rem nostrum optio pariatur, nobis tempore numquam impedit, recusandae libero temporibus quibusdam obcaecati deleniti, odit quo porro molestias error mollitia.</p>"
              ),
              "button" => $component->getButton(),
              "featured" => array(
                "type" => "switch",
                "label" => "Featured?",
                "value" => false,
                "horizontal" => true
              )
            ),
            "value"  => array(
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>$0/month</strong>"
                ),
                "subtitle" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "FREE"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Eligendi facere rem nostrum optio pariatur, nobis tempore numquam impedit, recusandae libero quibusdam.</p>"
                ),
                "button" => $component2->getButton(),
                "featured" => array(
                  "type" => "switch",
                  "label" => "Featured?",
                  "value" => false,
                  "horizontal" => true
                )
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>$15/month</strong>"
                ),
                "subtitle" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "LIGHT"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Nobis tempore numquam impedit, recusandae libero temporibus quibusdam obcaecati deleniti, odit quo porro.</p>"
                ),
                "button" => $component3->getButton(),
                "featured" => array(
                  "type" => "switch",
                  "label" => "Featured?",
                  "value" => true,
                  "horizontal" => true
                )
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>$45/month</strong>"
                ),
                "subtitle" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "PRO"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Recusandae libero temporibus quibusdam obcaecati deleniti, odit quo porro molestias error mollitia.</p>"
                ),
                "button" => $component4->getButton(),
                "featured" => array(
                  "type" => "switch",
                  "label" => "Featured?",
                  "value" => false,
                  "horizontal" => true
                )
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
      class="icl-section icl-section--pricing-1"
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

          <Sortable class="pricing-grid columns flex-wrap is-centered" v-model="data.pricing.value">
            <SortableItem :class="`column is-${12/data.column.value || 3}`" v-for="(item, index) in data.pricing.value" :key="`item-${index}`" :list="data.pricing.value" :index="index">
              <div class="icl-pricing icl-pricing--style-2"  :class="{'is-featured': item.featured.value }">
                <div class="icl-pricing__header">
                  <div class="icl-pricing__header-detail">
                    <small class="icl-pricing__subtitle"><InlineEditor v-model="item.subtitle.value"/></small>
                    <h2 class="icl-pricing__title" ><InlineEditor v-model="item.title.value" /></h2>
                  </div>
                </div>

                <div class="icl-pricing__content" ><InlineEditor v-model="item.content.value" /></div>

                <Button :data="item.button.value"/>
                  
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
      class="icl-section icl-section--pricing-2 content-<?php echo $style->content_color->value; ?>">
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
          
          <div class="pricing-grid columns flex-wrap is-centered">
            <?php foreach( $data->pricing->value as $item ): ?>
              <div class="column is-<?php echo 12 / get_value($data, "column", 3); ?>">
                <div class="icl-pricing icl-pricing--style-2 <?php echo $item->featured->value ? 'is-featured': ''; ?>">

                  <div class="icl-pricing__header">
                    <div class="icl-pricing__header-detail">
                      <small class="icl-pricing__subtitle"><?php echo $item->subtitle->value;  ?></small>
                      <h2 class="icl-pricing__title"><?php echo $item->title->value;  ?></h2>
                    </div>
                  </div>
                  <div class="icl-pricing__content">
                    <?php echo $item->content->value;  ?>
                  </div>

                  <?php
                    $package_link = (!empty($data->package_link) && !empty($data->package_link->value)) && (!empty($item->package_id) && !empty($item->package_id->value)) ? Render::get_redirect_package_link($data->package_link->value, $item->package_id->value) : ''; 
                    if (!empty($package_link)) {
                      $item->button->value->url = $package_link;
                    }
                  ?>
                  <?php render_button($item->button->value, ''); ?>
                  

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



$pricing2 = new Pricing2();
$arrayCode[] = $pricing2->data();
$arrayTemplate[] = $pricing2->editorTemplate();