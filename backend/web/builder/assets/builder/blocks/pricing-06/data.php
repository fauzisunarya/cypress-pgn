<?php

class Pricing6 {

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
      "label"         => "View All Packages",
      "background"    => "#000",
      "color"         => "#fff",
      "size"          => "is-normal", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-square", //[square, rounded, pill]
      "icon"          => 'fas fa-arrow-right',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'pricing-06',
      "title"      => 'Pricing 6',
      "screenshot" => 'pricing-06/pricing-06.jpg',
      "screenshot_size" => array( 600, 268 ),
      "template"   => '#pricing-06',
      "category"   => 'pricing',
        "data" => array(
          "subtitle" => array(
            "type"            => 'wyswyg',
            "label"           => 'Sub Judul',
            "value"           => 'Packages',
          ),
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Pricing Models For Every Needs</strong>',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "horizontal"      => true,
            "value"           => 40,
            "min"             => 18,
            "max"             => 72,
            "horizontal" => true,
          ),
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Konten',
            "value"           => '<p>Sit rem aliquam hic voluptates perspiciatis distinctio aliquid tempore expedita ea ut.</p>',
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
                "value" => "<strong>Basic</strong>"
              ),
              "price" => array(
                "type" => "text",
                "label" => "Harga",
                "horizontal" => true,
                "value" => "$400"
              ),
              "price_prefix" => array(
                "type"=>"text",
                "label" => "Prefix Harga",
                "horizontal"=>true,
                "value" => "Rp"
              ),
              "price_suffix" => array(
                "type"=>"text",
                "label" => "Satuan Harga",
                "horizontal"=>true,
                "value" => "/Bln"
              ),
              "content" => array(
                "type" => "wyswyg",
                "label" => "Deskripsi",
                "value" => "<ul>
                  <li>Lorem ipsum.</li>
                  <li>Soluta molestiae</li>
                  <li>Adipisci harum</li>
                  <li>Repellat adipisci</li>
                </ul>"
              ),
              "background" => array(
                "type" => "color",
                "label" => "Warna Latar Item",
                "horizontal" => true,
                "value" => "#FFF"
              ),
              "color" => array(
                "type" => "color",
                "label" => "Warna Konten Item",
                "horizontal" => true,
                "value" => '#333'
              ),
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
                  "value" => "<strong>Basic</strong>"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "400"
                ),
                "price_prefix" => array(
                  "type"=>"text",
                  "label" => "Prefix Harga",
                  "horizontal"=>true,
                  "value" => "Rp"
                ),
                "price_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Harga",
                  "horizontal"=>true,
                  "value" => "/Bln"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Deskripsi",
                  "value" => "<ul>
                    <li>Lorem ipsum.</li>
                    <li>Illo nesciunt</li>
                    <li>Quidem perferendis</li>
                    <li>Libero veritatis</li>
                  </ul>"
                ),
                "background" => array(
                  "type" => "color",
                  "label" => "Warna Latar Item",
                  "horizontal" => true,
                  "value" => "#FFF"
                ),
                "color" => array(
                  "type" => "color",
                  "label" => "Warna Konten Item",
                  "horizontal" => true,
                  "value" => '#333'
                ),
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
                  "value" => "<strong>Pro</strong>"
                ),
                "price" => array(
                  "type" => "text",
                  "label" => "Harga",
                  "horizontal" => true,
                  "value" => "600"
                ),
                "price_prefix" => array(
                  "type"=>"text",
                  "label" => "Prefix Harga",
                  "horizontal"=>true,
                  "value" => "Rp"
                ),
                "price_suffix" => array(
                  "type"=>"text",
                  "label" => "Satuan Harga",
                  "horizontal"=>true,
                  "value" => "/Bln"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Deskripsi",
                  "value" => "<ul>
                    <li>Lorem ipsum</li>
                    <li>Sunt atque</li>
                    <li>Ipsa voluptate</li>
                    <li>Voluptatibus</li>
                  </ul>"
                ),
                "background" => array(
                  "type" => "color",
                  "label" => "Warna Latar Item",
                  "horizontal" => true,
                  "value" => "#fff"
                ),
                "color" => array(
                  "type" => "color",
                  "label" => "Warna Konten Item",
                  "horizontal" => true,
                  "value" => '#333'
                ),
                "featured" => array(
                  "type" => "switch",
                  "label" => "Featured?",
                  "value" => true,
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
              "backgroundColor"      => "#fff",
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
          <div class="columns is-vcentered">
            <div class="column is-4">
              <div :class="`section-header mb4 ${data.alignment.value}`">
                <span class="section-title--top" ><InlineEditor v-model="data.subtitle.value" /></span>
                <div class="section-title mb3" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value"/></div>
                <div class="mb3" ><InlineEditor v-model="data.content.value" /></div>
                <Button :data="data.button.value"/>
              </div>
            </div>
            <div class="column is-8">
              <Sortable class="columns flex-wrap" v-model="data.pricing.value">
                <SortableItem class="column is-6" v-for="(item, index) in data.pricing.value"  :key="`item-${index}`" :list="data.pricing.value" :index="index">
                  <div
                    class="icl-pricing icl-pricing--style-6"
                    :class="{'is-featured': item.featured.value }"
                    :key="`item-${index}`"
                    :style="{backgroundColor: item.background.value, color: item.color.value }">
                    <h2 class="icl-pricing__title" ><InlineEditor v-model="item.title.value" /></h2>
                    <small class="icl-pricing__price">
                      <span class="prefix"><InlineEditor v-model="item.price_prefix.value"/></span>
                      <InlineEditor v-model="item.price.value"/>
                      <span class="suffix"><InlineEditor v-model="item.price_suffix.value"/></span>
                    </small>
                    <div class="icl-pricing__content" ><InlineEditor v-model="item.content.value" /></div>
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

          <div class="columns flex-wrap">
            <div class="column is-4">
              <div class="section-header mb4 <?php echo $data->alignment->value ?>">
                <?php if (!empty($data->subtitle->value)): ?>
                  <span class="section-title--top"><?php echo $data->subtitle->value; ?></span>
                <?php endif; ?>
                <h2 class="section-title mb3" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo format_heading( $data->title->value); ?></h2>
                <div class="mb3"><?php echo $data->content->value; ?></div>
                <?php render_button($data->button->value, ''); ?>
              </div>
              
            </div>
            <div class="column is-8">
              <div class="columns flex-wrap">
                <?php foreach( $data->pricing->value as $item ): ?>
                <div class="column is-6">
                  <div class="icl-pricing icl-pricing--style-6 <?php echo $item->featured->value ? 'is-featured': ''; ?>"  style="background-color:<?php echo $item->background->value; ?>;color:<?php echo $item->color->value; ?>">
                    <h2 class="icl-pricing__title"><?php echo $item->title->value;  ?></h2>
                      <small class="icl-pricing__price">
                        <span class="prefix"><?php echo $item->price_prefix->value; ?></span><?php echo $item->price->value; ?><span class="suffix"><?php echo $item->price_suffix->value; ?></span>
                      </small>
                    <?php echo $item->content->value;  ?>

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



$pricing6 = new Pricing6();
$arrayCode[] = $pricing6->data();
$arrayTemplate[] = $pricing6->editorTemplate();