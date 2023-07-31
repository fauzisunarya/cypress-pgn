<?php

class Pricing14 {

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
      "label"         => "Berlangganan",
      "background"    => "#dc3545",
      "color"         => "#ffffff",
      "size"          => "is-normal", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-rounded", //[square, rounded, pill]
      "icon"          => "",
      "icon_position" => ""
    ));

    return array(
      "blockID"    => 'pricing-14',
      "title"      => 'Pricing 14',
      "screenshot" => 'pricing-14/pricing-14.png',
      "screenshot_size" => array( 600, 413 ),
      "template"   => '#pricing-14',
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
                "value" => "Indihome Paket 2P Internet + Phone Diskon Biaya PSB"
              ),
              "speed" => array(
                "type" => "text",
                "label" => "Kecepatan",
                "horizontal" => true,
                "value" => "50"
              ),
              "speed_suffix" => array(
                "type" => "text",
                "label" => "Satuan Kecepatan",
                "horizontal" => true,
                "value" => "Mbps"
              ),
            ),
            "value"  => array(
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "Indihome Paket 2P Internet + Phone Diskon Biaya PSB"
                ),
                "speed" => array(
                  "type" => "text",
                  "label" => "Kecepatan",
                  "horizontal" => true,
                  "value" => "50"
                ),
                "speed_suffix" => array(
                  "type" => "text",
                  "label" => "Satuan Kecepatan",
                  "horizontal" => true,
                  "value" => "Mbps"
                ),
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "Indihome Paket 2P Internet + Phone Diskon Biaya PSB"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "$600"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Deskripsi",
                  "value" => "<p>Nobis tempore numquam impedit, recusandae libero temporibus quibusdam obcaecati deleniti, odit quo porro.</p>"
                ),
                "detail" => array(
                  "type" => "wyswyg",
                  "label" => "Detail",
                  "value" => "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi adipisci quam consectetur eveniet assumenda officiis rem iusto et eius quas, consequuntur explicabo animi, fuga incidunt. Optio sint fugiat, cumque excepturi!</p><ul><li>Lorem ipsum dolor sit amet.</li><li>Blanditiis repellendus, illo tempore libero!</li><li>Asperiores minima officia possimus tenetur.</li><li>Alias aut deserunt deleniti hic!</li></ul>"
                ),
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "Indihome Paket 2P Internet + Phone Diskon Biaya PSB"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "$800"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Deskripsi",
                  "value" => "<p>Recusandae libero temporibus quibusdam obcaecati deleniti, odit quo porro molestias error mollitia.</p>"
                ),
                "detail" => array(
                  "type" => "wyswyg",
                  "label" => "Detail",
                  "value" => "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi adipisci quam consectetur eveniet assumenda officiis rem iusto et eius quas, consequuntur explicabo animi, fuga incidunt. Optio sint fugiat, cumque excepturi!</p><ul><li>Lorem ipsum dolor sit amet.</li><li>Blanditiis repellendus, illo tempore libero!</li><li>Asperiores minima officia possimus tenetur.</li><li>Alias aut deserunt deleniti hic!</li></ul>"
                ),
              ),
            ),
          ),
          "spacer-2"=> array("type"=>"spacer"),
          "side_title" => array(
            "type"            => 'text',
            "label"           => 'Judul Side block',
            "heading"         => true,
            "value"           => 'Bebas Telepon 100 Menit',
          ),
          "side_body" => array(
            "type"            => 'wyswyg',
            "label"           => 'Body Side block',
            "value"           => 'Indihome Dual Play layanan internet cepat',
          ),
          "side_notice" => array(
            "type"            => 'text',
            "label"           => 'Notice Side block',
            "value"           => '*Harga Belum Termasuk PPN',
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
            "value" => "275.000"
          ),
          "price_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Price',
            "horizontal"      => true,
            "value"           => 13,
            "min"             => 5,
            "max"             => 50,
            "horizontal" => true,
          ),
          "side_button" => $component->getButton(),
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
            <div class="section-title" :style="`--font-size:${data.title_size.value}px`">
              <InlineEditor v-model="data.title.value"/>
            </div>
            <span class="section-subtitle" ><InlineEditor v-model="data.subtitle.value" /></span>
          </div>

          <div class="pricing-accordion">
            <div class="columns pricing-type-6">
              <div class="column is-9">
                <div class="icl-pricing icl-pricing--style-4" v-for="(item, index) in data.pricing.value" :key="`item-${index}`">
                  <div class="icl-pricing__detail">
                    <h5 class="icl-pricing__detail-title">
                      <InlineEditor v-model="item.title.value" />
                    </h5>
                    <div class="icl-pricing__detail-content">
                    <div class="accordion-body d-flex align-items-center justify-content-around">
                      <div class="text-center my-5">
                        <h1 class="mb-0 fw-bold">20</h1>
                        <span><InlineEditor v-model="item.speed_suffix.value" /></span>
                      </div>
                      <div class="text-center">
                        <h1 class="mb-0 fw-bold">30</h1>
                        <span><InlineEditor v-model="item.speed_suffix.value" /></span>
                      </div>
                      <div class="text-center">
                        <h1 class="mb-0 fw-bold">40</h1>
                        <span><InlineEditor v-model="item.speed_suffix.value" /></span>
                      </div>
                      <div class="text-center">
                        <h1 class="mb-0 fw-bold">50</h1>
                        <span><InlineEditor v-model="item.speed_suffix.value" /></span>
                      </div>
                      <div class="text-center">
                        <h1 class="mb-0 fw-bold">100</h1>
                        <span><InlineEditor v-model="item.speed_suffix.value" /></span>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-3">
                <div class="card pricing-type-6">
                  <div class="card-body">
                    <p class="fw-bold"><InlineEditor v-model="data.side_title.value" /></p>
                    <div class="text-center mt-5">
                      <div class="price" :style="`font-size:${data.price_size.value}px`">
                        <span><InlineEditor v-model="data.price_prefix.value" /></span>
                        <h1><InlineEditor v-model="data.price.value" /></h1>
                        <span><InlineEditor v-model="data.price_suffix.value" /></span>
                      </div>
                      <div class="description">
                        <h6><InlineEditor v-model="data.side_body.value" /></h6>
                      </div>
                      <div class="call-to-action">
                        <small><InlineEditor v-model="data.side_notice.value" /></small>
                        <Button class="btn block" :data="data.side_button.value"/>
                      </div>
                    </div>
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
          
          <div class="pricing-accordion">
            <div class="columns pricing-type-6">
              <div class="column is-9">
                <div class="icl-pricing icl-pricing--style-4">
                  <?php foreach( $data->pricing->value as $item ): ?>
                  <div class="icl-pricing__detail">
                    <h5 class="icl-pricing__detail-title">
                      <?= $item->title->value ?>
                    </h5>
                    <div class="icl-pricing__detail-content">
                    <div class="accordion-body d-flex align-items-center justify-content-around">
                      <div class="text-center my-5">
                        <h1 class="mb-0 fw-bold">20</h1>
                        <span><?= $item->speed_suffix->value ?></span>
                      </div>
                      <div class="text-center">
                        <h1 class="mb-0 fw-bold">30</h1>
                        <span><?= $item->speed_suffix->value ?></span>
                      </div>
                      <div class="text-center">
                        <h1 class="mb-0 fw-bold">40</h1>
                        <span><?= $item->speed_suffix->value ?></span>
                      </div>
                      <div class="text-center">
                        <h1 class="mb-0 fw-bold">50</h1>
                        <span><?= $item->speed_suffix->value ?></span>
                      </div>
                      <div class="text-center">
                        <h1 class="mb-0 fw-bold">100</h1>
                        <span><?= $item->speed_suffix->value ?></span>
                      </div>
                    </div>
                    </div>
                  </div>
                <?php endforeach; ?>
                </div>
              </div>
              <div class="column is-3">
                <div class="card pricing-type-6">
                  <div class="card-body">
                    <p class="fw-bold"><?= $data->side_title->value ?></p>
                    <div class="text-center mt-5">
                      <div class="price" style="font-size: <?= $data->price_size->value ?>px;">
                        <span><?= $data->price_prefix->value ?></span>
                        <h1><?= $data->price->value ?></h1>
                        <span><?= $data->price_suffix->value ?></span>
                      </div>
                      <div class="description">
                        <h6><?= $data->side_body->value ?></h6>
                      </div>
                      <div class="call-to-action">
                        <small><?= $data->side_notice->value ?></small>
                        <?php render_button($data->button->value, '', 'btn block'); ?>
                      </div>
                    </div>
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



$pricing14 = new Pricing14();
$arrayCode[] = $pricing14->data();
$arrayTemplate[] = $pricing14->editorTemplate();