<?php

class CompareProduct1 {
  public function __construct()
  {
    $this->base_url = get_base_url();
  }
  public function data()
  {
    require_once BASE_BUILDER_PATH . "/Component.php";
    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'compare-product-01',
      "title"      => 'compare product 1',
      "screenshot" => 'compare-product-01/compare.png',
      "screenshot_size" => array(944, 579),
      "template"   => '#compare-product-01',
      "category"   => 'compare-product',
      "data" => array(
        "title" => array(
          "type"            => 'wyswyg',
          "label"           => 'Judul',
          "heading"         => true,
          "value"           => '<strong>Compare Products</strong>',
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
        "spacer-1" => array("type" => "spacer"),
      ),
      "data_en" => array(
        "title" => array(
          "type"            => 'wyswyg',
          "label"           => 'Judul',
          "heading"         => true,
          "value"           => '<strong>Compare Products</strong>',
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
        "spacer-1" => array("type" => "spacer"),
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
      ),
    );
  }

  public function editorTemplate()
  {
    ob_start(); ?>
    <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
      <div class="icl-section__bg" :style="{
        backgroundColor      : style.background.value.backgroundColor,
        backgroundImage      : `url(${style.background.value.backgroundImage})`,
        backgroundRepeat     : style.background.value.backgroundRepeat,
        backgroundSize       : style.background.value.backgroundSize,
        backgroundPosition   : style.background.value.backgroundPosition,
        backgroundAttachment : style.background.value.backgroundAttachment,
        }">
        <Overlay :data="style.overlay_color.value" />
        <Divider :data="style.divider.value" />
      </div>
      <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
        <div :class="`content-spacer`" :style="{'padding': `${style.padding.value.top}px ${style.padding.value.right}px ${style.padding.value.bottom}px ${style.padding.value.left}px`}">
          <div :class="`section-header mb4 ${data.alignment.value}`">
            <div class="section-title" :style="`--font-size:${data.title_size.value}px`">
              <InlineEditor v-model="data.title.value" />
            </div>
            <span class="section-subtitle">
              <InlineEditor v-model="data.subtitle.value" />
            </span>
          </div>
          <div class="comparation-1">
            <p class="is-preview">*Only for Preview</p>
          <div class="select-product">
              <div class="form-group">
                <Label>Pilih Produk 1</Label>
                <select id="select-product-1">
                <option value="">Pilih Produk</option>
                </select>
              </div>
              <div class="form-group">
                <Label>Pilih Produk 2</Label>
                <select id="select-product-2">
                <option value="">Pilih Produk</option>
                </select>
              </div>
              <div class="form-group">
                <Label>Pilih Produk 3</Label>
                <select id="select-product-3">
                <option value="">Pilih Produk</option>
                </select>
              </div>
              <div class="form-group" style="display:flex; align-items: flex-end;">
                <button class="button" id="compare">Compare</button>
              </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Produk 1</th>
                        <th>Produk 2</th>
                        <th>Produk 3</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Title</td>
                        <td>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorem odio saepe molestiae!</td>
                        <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias, laboriosam!</td>
                        <td>Lorem ipsum dolor sit amet consectetur adipisicing elit.</td>
                    </tr>
                    <tr>
                        <td>Sub title</td>
                        <td>Lorem, ipsum dolor.</td>
                        <td>Lorem, ipsum dolor.</td>
                        <td>Lorem, ipsum dolor.</td>
                    </tr>
                    <tr>
                        <td>Package id</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Tags</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Flag</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Speed</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Promo Text</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Detail Voice</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Detail Internet</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Price Voice</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Price Internet</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Price Total</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Periode Pembayaran</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Tipe Paket</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Kuota</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Flag Json</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Trans Type</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                    <tr>
                        <td>Service</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                        <td>Lorem ipsum dolor sit amet.</td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }

  static function render($id, $data, $style)
  {
    $padding = array(
      "top" => $style->padding->value->top . 'px',
      "right" => $style->padding->value->right . 'px',
      "bottom" => $style->padding->value->bottom . 'px',
      "left" => $style->padding->value->left . 'px',
    );
    ob_start();
  ?>
  <div id="block-<?php echo $id; ?>" class="content-<?php echo $style->content_color->value; ?>">
      <?php
      $background_style  = "";
      $background_style .= !empty($style->background->value->backgroundColor) ? 'background-color:' . $style->background->value->backgroundColor . ';' : '';
      $background_style .= !empty($style->background->value->backgroundImage) ? 'background-image: url(' . $style->background->value->backgroundImage . ');' : '';
      if (!empty($style->background->value->backgroundImage)) {
        $background_style .= !empty($style->background->value->backgroundRepeat) ? 'background-repeat:' . $style->background->value->backgroundRepeat . ';' : '';
        $background_style .= !empty($style->background->value->backgroundSize) ? 'background-size:' . $style->background->value->backgroundSize . ';' : '';
        $background_style .= !empty($style->background->value->backgroundPosition) ? 'background-position:' . $style->background->value->backgroundPosition . ';' : '';
        $background_style .= !empty($style->background->value->backgroundAttachment) ? 'background-attachment:' . $style->background->value->backgroundAttachment . ';' : '';
      }
      ?>
      <div class="icl-section__bg" style="<?php echo $background_style; ?>">
        <?php $render = new Render(); ?>
        <?php echo $render->render_overlay($style->overlay_color->value); ?>
        <?php echo $render->render_divider($style->divider->value); ?>
      </div>

      <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">

        <div class="content-spacer" style="padding: <?php echo implode(" ", $padding); ?>">
          <div class="section-header mb4 <?php echo $data->alignment->value ?>">
            <h2 class="section-title" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo format_heading($data->title->value); ?></h2>
            <span class="section-subtitle"><?php echo $data->subtitle->value; ?></span>
          </div>
          <div class="comparation-1">
            <div class="select-product">
              <div class="form-group" id="product-1">
                <Label>Pilih Produk 1</Label>
                <select id="select-product-1">
                <option value="">Pilih Produk</option>
                    <?php
                      foreach ($render->get_landing_package() as $package_id => $value) {
                        echo "<option value='$package_id'>$value</option>";
                      }
                    ?>
                </select>
              </div>
              <div class="form-group" id="product-2" style="display:none ;">
                <Label>Pilih Produk 2</Label>
                <select id="select-product-2">
                <option value="">Pilih Produk</option>
                    <?php
                      foreach ($render->get_landing_package() as $package_id => $value) {
                        echo "<option value='$package_id'>$value</option>";
                      }
                    ?>
                </select>
              </div>
              <div class="form-group" id="product-3" style="display:none ;">
                <Label>Pilih Produk 3</Label>
                <select id="select-product-3">
                <option value="">Pilih Produk</option>
                    <?php
                      foreach ($render->get_landing_package() as $package_id => $value) {
                        echo "<option value='$package_id'>$value</option>";
                      }
                    ?>
                </select>
              </div>
              <div class="form-group" style="display:flex; align-items: flex-end;">
                <input type="hidden" id="ajax_url" value="<?php echo $_ENV['APP_URL']; ?>">
                <button class="button" id="compare" style="display:none ;">Compare</button>
              </div>
            </div>
          <table id="table-product-comparation">
              <thead>
                  <tr>
                      <th></th>
                      <th>Produk 1</th>
                      <th>Produk 2</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>Title</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Sub title</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Package id</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Category</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Tags</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Flag</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Speed</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Promo Text</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Detail Voice</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Detail Internet</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Price Voice</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Price Internet</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Price Total</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Periode Pembayaran</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Tipe Paket</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Kuota</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Flag Json</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Trans Type</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
                  <tr>
                      <td>Service</td>
                      <td class="text-center">-</td>
                      <td class="text-center">-</td>
                  </tr>
              </tbody>
          </table>
        </div>
        </div>
      </div>
    </div>
  <?php
    return ob_get_clean();
  }
  static function renderCSS()
  {
    return file_get_contents('style.css', FILE_USE_INCLUDE_PATH);
  }
}


$Comparation1 = new CompareProduct1();
$arrayCode[] = $Comparation1->data();
$arrayTemplate[] = $Comparation1->editorTemplate();
