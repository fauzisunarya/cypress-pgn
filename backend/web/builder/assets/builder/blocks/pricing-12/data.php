<?php

class Pricing12 {

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
      "background"    => "#56c681",
      "color"         => "#ffffff",
      "size"          => "is-normal", // [small, normal, medium, large]
      "style"         => "is-fill", // [fill, outline ]
      "corner"        => "is-radius", // [square, rounded, pill]
      "icon"          => 'fas fa-arrow-right',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'pricing-12',
      "title"      => 'Pricing 12',
      "screenshot" => 'pricing-12/pricing-12.png',
      "screenshot_size" => array( 944, 579 ),
      "template"   => '#pricing-12',
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

          "column_title" => array(
            "type"    => 'wyswyg',
            "label"   => 'Data Title',
            "heading" => true,
            "value"   => '<strong>List Package</strong>',
          ),

          "spacer-1"=> array("type"=>"spacer"),

          "pricing" => array(
            "type"  => "repeatable",
            "label" => "Pricing",
            "item_title" => "title",
            "label_title" => "Feature",
            "label_promo" => "Promo",
            "settings" => array(
              "identifier" => array(
                "type" => "text",
                "label" => "Identifier",
                "heading" => true,
                "horizontal" => true,
                "value" => "1"
              ),
              "title" => array(
                "type" => "text",
                "label" => "Judul",
                "heading" => true,
                "horizontal" => true,
                "value" => "$0/month"
              ),
              "speed" => array(
                "type" => "text",
                "label" => "Kecepatan",
                "horizontal" => true,
                "value" => "30Mbps"
              ),
              "price" => array(
                "type" => "text",
                "label" => "Harga",
                "horizontal" => true,
                "value" => "Rp.400"
              ),
            ),
            "value"  => array(
              array(
                "identifier" => array(
                  "type" => "text",
                  "label" => "Identifier",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "1"
                ),
                "title" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "$0/month"
                ),
                "speed" => array(
                  "type" => "text",
                  "label" => "Kecepatan",
                  "horizontal" => true,
                  "value" => "30Mbps"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "Rp.400"
                ),
              )
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
            "value" => "dark",
            "options" => array(
              "light" => "Light",
              "dark" => "Dark",
              "default" => "Default"
            )
          ),
          "background" => array(
            "type"            => 'background',
            "value"           => array(
              "backgroundColor"      => "#f4f4f4",
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
      class="icl-section icl-section--pricing-12"
      :class="`content-${style.content_color.value}`">
      <div class="icl-section__bg" :style="{
        backgroundColor      : style.background.value.backgroundColor,
        backgroundImage      : `url(${style.background.value.backgroundImage})`,
        backgroundRepeat     : style.background.value.backgroundRepeat,
        backgroundSize       : style.background.value.backgroundSize,
        backgroundPosition   : style.background.value.backgroundPosition,
        backgroundAttachment : style.background.value.backgroundAttachment,
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

          <div class="card pricing-type-3">
            <div class="card-body table-responsive">
              <div class="card-title">
                <div class="list-package-title">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z" />
                    <path
                      d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"
                      fill="rgba(255,0,0,1)" />
                  </svg>
                  <h5>
                    <InlineEditor v-model="data.column_title.value" />
                  </h5>
                </div>
                <table class="table my-5">
                  <thead>
                    <tr>
                      <th>
                        <InlineEditor value="ID" />
                      </th>
                      <th>
                        <InlineEditor value="Name" />
                      </th>
                      <th>
                        <InlineEditor value="Speed" />
                      </th>
                      <th>
                        <InlineEditor value="Price" />
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in data.pricing.value" :key="`item-${index}`" :list="data.pricing.value" :index="index">
                      <td>
                        <InlineEditor v-model="item.identifier.value" />
                      </td>
                      <td>
                        <InlineEditor v-model="item.title.value" />
                      </td>
                      <td>
                        <InlineEditor v-model="item.speed.value" />
                      </td>
                      <td>
                        <InlineEditor v-model="item.price.value" />
                      </td>
                    </tr>
                  </tbody>
                </table>
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
      class="icl-section icl-section--pricing-12 content-<?php echo $style->content_color->value; ?>">
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
            <div class="column">
              <div class="card pricing-type-3">
                <div class="card-body table-responsive">
                  <div class="card-title">
                    <div class="list-package-title">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path
                          d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"
                          fill="rgba(255,0,0,1)" />
                      </svg>
                      <h5><?= $data->column_title->value ?></h5>
                    </div>
                    <table class="table my-5">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Speed</th>
                          <th>Price</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach( $data->pricing->value as $item ): ?>
                        <tr>
                          <td><?= $item->identifier->value ?></td>
                          <td><?= $item->title->value ?></td>
                          <td><?= $item->speed->value ?></td>
                          <td><?= $item->price->value ?></td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
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

$pricing12 = new Pricing12();
$arrayCode[] = $pricing12->data();
$arrayTemplate[] = $pricing12->editorTemplate();