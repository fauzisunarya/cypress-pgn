<?php

class Pricing15 {

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
      "label"         => "Choose",
      "background"    => "red",
      "color"         => "#ffffff",
      "size"          => "is-normal", // [small, normal, medium, large]
      "style"         => "is-fill", // [fill, outline ]
      "corner"        => "is-rounded", // [square, rounded, pill]
      "icon"          => '',
      "icon_position" => ''
    ));

    return array(
      "blockID"    => 'pricing-15',
      "title"      => 'Pricing 15',
      "screenshot" => 'pricing-15/pricing-15.png',
      "screenshot_size" => array( 600, 351 ),
      "template"   => '#pricing-15',
      "category"   => 'pricing',
        "data" => array(
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
                "value" => "<strong>Starter</strong>"
              ),
              "speed" => array(
                "type" => "text",
                "label" => "Kecepatan",
                "horizontal" => true,
                "value" => "30"
              ),
              "speed_suffix" => array(
                "type"=>"text",
                "label" => "Satuan Kecepatan",
                "horizontal"=>true,
                "value" => "GB"
              ),
              "content" => array(
                "type" => "wyswyg",
                "label" => "Content",
                "value" => "<p>Eligendi facere rem nostrum optio pariatur, nobis tempore numquam impedit, recusandae libero quibusdam.</p>"
              ),
              "price_prefix" => array(
                "type"=>"text",
                "label" => "Format Harga",
                "horizontal"=>true,
                "value" => "Rp."
              ),
              "price_suffix" => array(
                "type"=>"text",
                "label" => "Satuan Harga",
                "horizontal"=>true,
                "value" => "/bulan"
              ),
              "price" => array(
                "type" => "text",
                "label" => "Harga",
                "horizontal" => true,
                "value" => "75.000"
              ),
              "button" => $component->getButton()
            ),
            "value"  => array(
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Starter</strong>"
                ),
                "speed" => array(
                  "type" => "text",
                  "label" => "Kecepatan",
                  "horizontal" => true,
                  "value" => "30"
                ),
                "speed_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Kecepatan",
                  "horizontal"=>true,
                  "value" => "GB"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Eligendi facere rem nostrum optio pariatur, nobis tempore numquam impedit, recusandae libero quibusdam.</p>"
                ),
                "price_prefix" => array(
                  "type"=>"text",
                  "label" => "Format Harga",
                  "horizontal"=>true,
                  "value" => "Rp."
                ),
                "price_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Harga",
                  "horizontal"=>true,
                  "value" => "/bulan"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "75.000"
                ),
                "button" => $component->getButton()
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Professional</strong>"
                ),
                "speed" => array(
                  "type" => "text",
                  "label" => "Kecepatan",
                  "horizontal" => true,
                  "value" => "30"
                ),
                "speed_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Kecepatan",
                  "horizontal"=>true,
                  "value" => "GB"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Nobis tempore numquam impedit, recusandae libero temporibus quibusdam obcaecati deleniti, odit quo porro.</p>"
                ),
                "price_prefix" => array(
                  "type"=>"text",
                  "label" => "Format Harga",
                  "horizontal"=>true,
                  "value" => "Rp."
                ),
                "price_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Harga",
                  "horizontal"=>true,
                  "value" => "/bulan"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "75.000"
                ),
                "button" => $component->getButton()
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Team</strong>"
                ),
                "speed" => array(
                  "type" => "text",
                  "label" => "Kecepatan",
                  "horizontal" => true,
                  "value" => "30"
                ),
                "speed_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Kecepatan",
                  "horizontal"=>true,
                  "value" => "GB"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Recusandae libero temporibus quibusdam obcaecati deleniti, odit quo porro molestias error mollitia.</p>"
                ),
                "price_prefix" => array(
                  "type"=>"text",
                  "label" => "Format Harga",
                  "horizontal"=>true,
                  "value" => "Rp."
                ),
                "price_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Harga",
                  "horizontal"=>true,
                  "value" => "/bulan"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "75.000"
                ),
                "button" => $component->getButton()
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
              "backgroundColor"      => "red",
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
      class="icl-section icl-section--pricing-3"
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
          <Sortable class="pricing-15 is-multiline is-centered" v-model="data.pricing.value">
            <SortableItem 
              class="column pricing-15-card"
              v-for="(item, index) in data.pricing.value"
              :key="`item-${index}`"
              :list="data.pricing.value"
              :index="index"
            >
              <div class="icl-pricing">
                <div class="title border-bottom">
                  <span class="packet-type"><InlineEditor v-model="item.title.value" /></span>
                  <h1 class="packet-speed">
                    <InlineEditor v-model="item.speed.value" /> 
                    <span><InlineEditor v-model="item.speed_suffix.value"/></span>
                  </h1>
                </div>
                <div class="pricing-15-card-body">
                  <InlineEditor v-model="item.content.value" />
                  <div class="price">
                    <span class="currency"><InlineEditor v-model="item.price_prefix.value" /></span>
                    <span class="nominal"><InlineEditor v-model="item.price.value" /></span>
                    <span class="month"><InlineEditor v-model="item.price_suffix.value" /></span>
                  </div>
                </div>
                <div class="pricing-15-card-footer">
                  <Button :data="item.button.value"/>
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
      class="icl-section icl-section--pricing-3 content-<?php echo $style->content_color->value; ?>">
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
          <div class="pricing-15 columns is-multiline is-centered">
            <?php foreach( $data->pricing->value as $item ): ?>
              <div class="pricing-15-card column">
                <div class="title border-bottom">
                  <span class="packet-type"><?php echo $item->title->value; ?></span>
                  <h1 class="packet-speed">
                    <?php echo $item->speed->value; ?>
                    <span><?php echo $item->speed_suffix->value; ?></span>
                  </h1>
                </div>
                <div class="pricing-15-card-body">
                  <?php echo $item->content->value; ?>
                  <div class="price">
                    <span class="currency"><?php echo $item->price_prefix->value; ?></span>
                    <span class="nominal"><?php echo $item->price->value; ?></span>
                    <span class="month"><?php echo $item->price_suffix->value; ?></span>
                  </div>
                </div>
                <div class="pricing-15-card-footer">
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

$pricing15 = new Pricing15();
$arrayCode[] = $pricing15->data();
$arrayTemplate[] = $pricing15->editorTemplate();
