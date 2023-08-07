<?php

class Pricing10 {

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
      "label"         => "Pilih Paket",
      "background"    => "#dc3545",
      "color"         => "#ffffff",
      "size"          => "is-normal", // [small, normal, medium, large]
      "style"         => "is-fill", // [fill, outline ]
      "corner"        => "is-rounded", // [square, rounded, pill]
      "icon"          => '',
      "icon_position" => ""
    ));

    return array(
      "blockID"    => 'pricing-10',
      "title"      => 'Pricing 10',
      "screenshot" => 'pricing-10/pricing-10.png',
      "screenshot_size" => array( 944, 579 ),
      "template"   => '#pricing-10',
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

          "pricing_position" => array(
            "type" => "select",
            "horizontal" => true,
            "label" => "Posisi Pricing",
            "value" => "horizontal",
            "options" => array(
              "horizontal" => "Horizontal",
              "vertical" => "Vertical"
            )
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
            "label_promo" => "Promo",
            "settings" => array(
              "title" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "$0/month"
                ),
                "subtitle" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "Speed"
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
                  "value" => "400"
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
                  "value" => "Mbps"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<ul><li>Recusandae libero</li><li>Temporibus quibusdam</li><li>Obcaecati deleniti</li></ul>"
                ),
                "footer_title" => array(
                  "type" => "text",
                  "label" => "Footer Title",
                  "horizontal" => true,
                  "value" => "Benefit"
                ),
                "footer_content" => array(
                  "type" => "wyswyg",
                  "label" => "Footer Content",
                  "value" => "<ul><li>UseeTV Entry + Seatoday + UseeTV Go</li><li>Disney+ Hotstar</li></ul>"
                ),
                "button" => $component2->getButton(),
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
                  "type" => "text",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "$0/month"
                ),
                "subtitle" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "Speed"
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
                  "value" => "400"
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
                  "value" => "Mbps"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<ul><li>Recusandae libero</li><li>Temporibus quibusdam</li><li>Obcaecati deleniti</li></ul>"
                ),
                "footer_title" => array(
                  "type" => "text",
                  "label" => "Footer Title",
                  "horizontal" => true,
                  "value" => "Benefit"
                ),
                "footer_content" => array(
                  "type" => "wyswyg",
                  "label" => "Footer Content",
                  "value" => "<ul><li>UseeTV Entry + Seatoday + UseeTV Go</li><li>Disney+ Hotstar</li></ul>"
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
                  "type" => "text",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "$15/month"
                ),
                "subtitle" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "Speed"
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
                  "value" => "400"
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
                  "value" => "Mbps"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<ul><li>Eligendi facere</li><li>Rem nostrum optio</li><li>Pariatur nobis</li><li>Nobis tempore</li><li>Numquam impedit</li></ul>"
                ),
                "footer_title" => array(
                  "type" => "text",
                  "label" => "Footer Title",
                  "horizontal" => true,
                  "value" => "Benefit"
                ),
                "footer_content" => array(
                  "type" => "wyswyg",
                  "label" => "Footer Content",
                  "value" => "<ul><li>UseeTV Entry + Seatoday + UseeTV Go</li><li>Disney+ Hotstar</li></ul>"
                ),
                "button" => $component2->getButton(),
                "featured" => array(
                  "type" => "switch",
                  "label" => "Featured?",
                  "value" => true,
                  "horizontal" => true
                )
              ),
              array(
                "title" => array(
                  "type" => "text",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "$45/month"
                ),
                "subtitle" => array(
                  "type" => "text",
                  "label" => "Subtitle",
                  "horizontal" => true,
                  "value" => "Speed"
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
                  "value" => "400"
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
                  "value" => "Mbps"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<ul><li>Eligendi facere</li><li>Rem nostrum optio</li><li>Pariatur nobis</li><li>Nobis tempore</li><li>Numquam impedit</li><li>Recusandae libero</li><li>Temporibus quibusdam</li><li>Obcaecati deleniti</li></ul>"
                ),
                "footer_title" => array(
                  "type" => "text",
                  "label" => "Footer Title",
                  "horizontal" => true,
                  "value" => "Benefit"
                ),
                "footer_content" => array(
                  "type" => "wyswyg",
                  "label" => "Footer Content",
                  "value" => "<ul><li>UseeTV Entry + Seatoday + UseeTV Go</li><li>Disney+ Hotstar</li></ul>"
                ),
                "button" => $component2->getButton(),
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
            "value" => "dark",
            "options" => array(
              "light" => "dark",
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
      class="icl-section icl-section--pricing-10"
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
        
          <Sortable :class="`pricing-10 columns flex-wrap is-centered pricing-position-${data.pricing_position.value}`" v-model="data.pricing.value">
            <SortableItem :class="`column is-${12/data.column.value || 3}`" v-for="(item, index) in data.pricing.value" :key="`item-${index}`" :list="data.pricing.value" :index="index">
              <div class="card pricing-type-2" :class="{'is-featured': item.featured.value }">
                <div class="card-body py-4">
                  <div class="card-title">
                    <div class="speed-title">
                      <img src="https://www.indihome.co.id/images/ic-wifi-red.svg" alt="speed">
                      <span>
                        <InlineEditor v-model="item.subtitle.value" />
                      </span>
                    </div>
                  </div>
                  <div class="speed">
                    <h4 style="margin-bottom: 0px;">
                      <InlineEditor v-model="item.speed.value" />
                    </h4>
                    <span><InlineEditor v-model="item.speed_suffix.value" /></span>
                  </div>
                  <div class="price">
                    <h5 style="margin-bottom: 0px;">
                      <InlineEditor v-model="item.price.value" />
                    </h5>
                    <span><InlineEditor v-model="item.price_suffix.value" /></span>
                  </div>
                  <div class="description">
                    <p>
                      <InlineEditor v-model="item.title.value" />
                    </p>
                    <small>
                      <InlineEditor v-model="item.content.value" />
                    </small>
                  </div>
                  <hr>
                  <div class="benefit-title">
                    <img src="https://www.indihome.co.id/images/icon/icon-benefit.svg" alt="benefit">
                    <span>
                      <InlineEditor v-model="item.footer_title.value" />
                    </span>
                  </div>
                  <div class="benefit-list">
                    <InlineEditor v-model="item.footer_content.value" />
                  </div>
                  
                  <Button class="btn block" :data="item.button.value"/>
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
      class="icl-section icl-section--pricing-10 content-<?php echo $style->content_color->value; ?>">
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
          
          <div class="pricing-10 columns flex-wrap is-centered pricing-position-<?php echo $data->pricing_position->value;?>">
            <?php foreach( $data->pricing->value as $item ): ?>
              <div class="column is-<?php echo 12 / get_value($data, "column", 3); ?>">
              <div class="card pricing-type-2">
                <div class="card-body py-4">
                  <div class="card-title">
                    <div class="speed-title">
                      <img src="https://www.indihome.co.id/images/ic-wifi-red.svg" alt="speed">
                      <span><?= $item->subtitle->value ?></span>
                    </div>
                  </div>
                  <div class="speed">
                    <h2><?= $item->speed->value ?></h2>
                    <span><?= $item->speed_suffix->value ?></span>
                  </div>
                  <div class="price">
                    <h3><?= $item->price->value ?></h3>
                    <span><?= $item->price_suffix->value ?></span>
                  </div>
                  <div class="description">
                    <p><?= $item->title->value ?></p>
                    <small><?= $item->content->value ?></small>
                  </div>
                  <hr>
                  <div class="benefit-title">
                    <img src="https://www.indihome.co.id/images/icon/icon-benefit.svg" alt="benefit">
                    <span><?= $item->footer_title->value ?></span>
                  </div>
                  <div class="benefit-list">
                    <?= $item->footer_content->value ?>
                  </div>

                  <?php
                    $package_link = (!empty($data->package_link) && !empty($data->package_link->value)) && (!empty($item->package_id) && !empty($item->package_id->value)) ? Render::get_redirect_package_link($data->package_link->value, $item->package_id->value) : ''; 
                    if (!empty($package_link)) {
                      $item->button->value->url = $package_link;
                    }
                  ?>
                  <?php render_button($item->button->value, '', 'btn block'); ?>
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

$pricing10 = new Pricing10();
$arrayCode[] = $pricing10->data();
$arrayTemplate[] = $pricing10->editorTemplate();