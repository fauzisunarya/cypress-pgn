<?php

class Pricing13 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Component.php";
    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'pricing-13',
      "title"      => 'Pricing 13',
      "screenshot" => 'pricing-13/pricing-13.png',
      "screenshot_size" => array( 944, 579 ),
      "template"   => '#pricing-13',
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
            "label_promo" => "Promo",
            "settings" => array(
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
                  "value" => "0"
                ),
                "speed_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Kecepatan",
                  "horizontal"=>true,
                  "value" => "Mbps"
                ),
                "telp_head" => array(
                  "type"=>"text",
                  "label" => "Telepon Header",
                  "value" => "Gratis"
                ),
                "telp_body" => array(
                  "type"=>"text",
                  "label" => "Telepon Body",
                  "value" => "300 menit nelpon lokal/interlokal"
                ),
                "tv_head" => array(
                  "type"=>"text",
                  "label" => "Tv Header",
                  "value" => ""
                ),
                "tv_body" => array(
                  "type"=>"text",
                  "label" => "Tv Harga",
                  "value" => "Interactive TV Channels Entry + IndiKids Lite"
                ),
                "iflix_head" => array(
                  "type"=>"text",
                  "label" => "Iflix Header",
                  "value" => "1+2 Bulan"
                ),
                "iflix_body" => array(
                  "type"=>"text",
                  "label" => "Iflix Harga",
                  "value" => "Nonton Sepuasnya"
                ),
                "hooq_head" => array(
                  "type"=>"text",
                  "label" => "HOOQ Header",
                  "value" => "1+2 Bulan"
                ),
                "hooq_body" => array(
                  "type"=>"text",
                  "label" => "HOOQ Harga",
                  "value" => "Nonton Sepuasnya"
                ),
                "price_prefix" => array(
                  "type"=>"text",
                  "label" => "Format Harga",
                  "horizontal"=>true,
                  "value" => "Rp."
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "Rp.400"
                ),
                "price_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Harga",
                  "horizontal"=>true,
                  "value" => "/bulan"
                ),
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
                "telp_head" => array(
                  "type"=>"text",
                  "label" => "Telepon Header",
                  "value" => "Gratis"
                ),
                "telp_body" => array(
                  "type"=>"text",
                  "label" => "Telepon Body",
                  "value" => "300 menit nelpon lokal/interlokal"
                ),
                "tv_head" => array(
                  "type"=>"text",
                  "label" => "Tv Header",
                  "value" => ""
                ),
                "tv_body" => array(
                  "type"=>"text",
                  "label" => "Tv Harga",
                  "value" => "Interactive TV Channels Entry + IndiKids Lite"
                ),
                "iflix_head" => array(
                  "type"=>"text",
                  "label" => "Iflix Header",
                  "value" => "1+2 Bulan"
                ),
                "iflix_body" => array(
                  "type"=>"text",
                  "label" => "Iflix Harga",
                  "value" => "Nonton Sepuasnya"
                ),
                "hooq_head" => array(
                  "type"=>"text",
                  "label" => "HOOQ Header",
                  "value" => "1+2 Bulan"
                ),
                "hooq_body" => array(
                  "type"=>"text",
                  "label" => "HOOQ Harga",
                  "value" => "Nonton Sepuasnya"
                ),
                "price_prefix" => array(
                  "type"=>"text",
                  "label" => "Format Harga",
                  "horizontal"=>true,
                  "value" => "Rp."
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "Rp.400"
                ),
                "price_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Harga",
                  "horizontal"=>true,
                  "value" => "/bulan"
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
      class="icl-section icl-section--pricing-13"
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
        
          <div class="card pricing-type-4">
            <div class="card-body table-responsive">
              <table class="table my-5">
                <thead>
                  <tr>
                    <th>
                      <img src="https://i.postimg.cc/YCxkH6yY/indihome-logo.png" alt="logo-indihome" width="70px">
                    </th>
                    <th>
                      <img src="https://i.postimg.cc/fTcQc7sp/telepon-rumah-logo.png" alt="logo-indihome" width="40px">
                    </th>
                    <th>
                      <img src="https://www.useetv.com/assets/images/logo.png" alt="logo-indihome" width="35px">
                    </th>
                    <th>
                      <img src="https://i.postimg.cc/fLTGZ10h/iflix-logo.png" alt="logo-indihome" width="50px">
                    </th>
                    <th>
                      <img
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/HOOQ_logo.svg/1200px-HOOQ_logo.svg.png"
                        alt="logo-indihome" width="50px">
                    </th>
                    <th class="price">
                      Harga
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in data.pricing.value" :key="`item-${index}`" :index="index">
                    <td>
                      <h1><InlineEditor v-model="item.speed.value" /></h1>
                      <span><InlineEditor v-model="item.speed_suffix.value" /></span>
                    </td>
                    <td>
                      <h3><InlineEditor v-model="item.telp_head.value" /></h3>
                      <span><InlineEditor v-model="item.telp_body.value" /></span>
                    </td>
                    <td>
                      <InlineEditor v-model="item.tv_body.value" />
                    </td>
                    <td>
                      <h3><InlineEditor v-model="item.iflix_head.value" /></h3>
                      <span><InlineEditor v-model="item.iflix_body.value" /></span>
                    </td>
                    <td>
                      <h3><InlineEditor v-model="item.hooq_head.value" /></h3>
                      <span><InlineEditor v-model="item.hooq_body.value" /></span>
                    </td>
                    <td>
                      <div class="price">
                        <span><InlineEditor v-model="item.price_prefix.value" /></span>
                        <h2><InlineEditor v-model="item.price.value" /></h2>
                        <span><InlineEditor v-model="item.price_suffix.value" /></span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
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
      class="icl-section icl-section--pricing-13 content-<?php echo $style->content_color->value; ?>">
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
              <div class="card pricing-type-4">
                <div class="card-body table-responsive">
                  <table class="table my-5">
                    <thead>
                      <tr>
                        <th>
                          <img src="https://i.postimg.cc/YCxkH6yY/indihome-logo.png" alt="logo-indihome" width="70px">
                        </th>
                        <th>
                          <img src="https://i.postimg.cc/fTcQc7sp/telepon-rumah-logo.png" alt="logo-indihome" width="40px">
                        </th>
                        <th>
                          <img src="https://www.useetv.com/assets/images/logo.png" alt="logo-indihome" width="35px">
                        </th>
                        <th>
                          <img src="https://i.postimg.cc/fLTGZ10h/iflix-logo.png" alt="logo-indihome" width="50px">
                        </th>
                        <th>
                          <img
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/HOOQ_logo.svg/1200px-HOOQ_logo.svg.png"
                            alt="logo-indihome" width="50px">
                        </th>
                        <th class="price">
                          Harga
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach( $data->pricing->value as $item ): ?>
                      <tr>
                        <td class="speed">
                          <h1><?= $item->speed->value ?></h1>
                          <span><?= $item->speed_suffix->value ?></span>
                        </td>
                        <td class="telepon-rumah">
                          <h3><?= $item->telp_head->value ?></h3>
                          <span><?= $item->telp_body->value ?></span>
                        </td>
                        <td class="useetv">
                          <?= $item->tv_body->value ?>
                        </td>
                        <td class="telepon-rumah">
                          <h3><?= $item->iflix_head->value ?></h3>
                          <span><?= $item->iflix_body->value ?></span>
                        </td>
                        <td class="telepon-rumah">
                          <h3><?= $item->hooq_head->value ?></h3>
                          <span><?= $item->hooq_body->value ?></span>
                        </td>
                        <td>
                          <div class="price">
                            <span><?= $item->price_prefix->value ?></span>
                            <h2><?= $item->price->value ?></h2>
                            <span><?= $item->price_suffix->value ?></span>
                          </div>
                        </td>
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
  <?php
    return ob_get_clean();
  }

  static function renderCSS(){
    return file_get_contents('style.css', FILE_USE_INCLUDE_PATH);
  }
}

$pricing13 = new Pricing13();
$arrayCode[] = $pricing13->data();
$arrayTemplate[] = $pricing13->editorTemplate();