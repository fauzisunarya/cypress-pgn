<?php

class Contact2 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'contact-02',
      "title"      => 'Contact 2',
      "screenshot" => 'contact-02/contact-02.jpg',
      "screenshot_size" => array( 600, 251 ),
      "template"   => '#contact-02',
      "category"   => 'contact',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Get in Touch</strong>',
          ),
          "title_size" => array(
            "type"            => 'slider',
            "label"           => 'Ukuran Judul',
            "value"           => 36,
            "min"             => 18,
            "max"             => 72,
            "horizontal" => true,
          ),
          "subtitle" => array(
            "type"            => 'wyswyg',
            "label"           => 'Sub Judul',
            "value"           => 'Our team of designers & developers make a custom digital product for startup and brand',
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
          "contact" => array(
            "type"  => "repeatable",
            "label" => "Contact Data",
            "item_title" => "label",
            "settings" => array(
              "icon" => array(
                "type" => "icon",
                "label" => "Icon",
                "horizontal"=> true,
                "value" => "fas fa-envelope"
              ),
              "data" => array(
                "type" => "text",
                "label" => "Data",
                "horizontal" => true,
                "value" => "hello@email.com"
              ),
              "label" => array(
                "type" => "text",
                "label" => "Label",
                "horizontal" => true,
                "value" => "Email"
              ),
              "icon_color" => array(
                "type" => "color",
                "label" => "Warna Icon",
                "horizontal" => true,
                "value" => ""
              ),
            ),
            "value"  => array(
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Icon",
                  "horizontal"=> true,
                  "value" => "fas fa-envelope"
                ),
                "data" => array(
                  "type" => "text",
                  "label" => "Data",
                  "horizontal" => true,
                  "value" => "hello@email.com"
                ),
                "label" => array(
                  "type" => "text",
                  "label" => "Label",
                  "horizontal" => true,
                  "value" => "Email"
                ),
                "icon_color" => array(
                  "type" => "color",
                  "label" => "Warna Icon",
                  "horizontal" => true,
                  "value" => ""
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Icon",
                  "horizontal"=> true,
                  "value" => "fas fa-phone"
                ),
                "data" => array(
                  "type" => "text",
                  "label" => "Data",
                  "horizontal" => true,
                  "value" => "+62 78967 9869"
                ),
                "label" => array(
                  "type" => "text",
                  "label" => "Label",
                  "horizontal" => true,
                  "value" => "Phone"
                ),
                "icon_color" => array(
                  "type" => "color",
                  "label" => "Warna Icon",
                  "horizontal" => true,
                  "value" => ""
                ),
              ),
              array(
                "icon" => array(
                  "type" => "icon",
                  "label" => "Icon",
                  "horizontal"=> true,
                  "value" => "fas fa-map-marker"
                ),
                "data" => array(
                  "type" => "text",
                  "label" => "Data",
                  "horizontal" => true,
                  "value" => "Main Street 32b, NYC"
                ),
                "label" => array(
                  "type" => "text",
                  "label" => "Label",
                  "horizontal" => true,
                  "value" => "Address",
                ),
                "icon_color" => array(
                  "type" => "color",
                  "label" => "Warna Icon",
                  "horizontal" => true,
                  "value" => ""
                ),
              )
            ),
          ),
          "separator" => array(
            "type" => "spacer"
          ),
          "icon_size" => array(
            "type" => "slider",
            "label" => "Ukuran Icon",
            "horizontal" => true,
            "min" => 0,
            "max" => 100,
            "value" => 20
          ),
          "label_size" => array(
            "type" => "slider",
            "label" => "Ukuran kontak label",
            "horizontal" => true,
            "min" => 0,
            "max" => 100,
            "value" => 14
          ),
          "value_size" => array(
            "type" => "slider",
            "label" => "Ukuran kontak data",
            "horizontal" => true,
            "min" => 0,
            "max" => 100,
            "value" => 20
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
              "backgroundImage"      => "https://images.unsplash.com/photo-1498036882173-b41c28a8ba34?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1080&fit=max&ixid=eyJhcHBfaWQiOjk3MjkwfQ",
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
      class="icl-section icl-section--contact-2"
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
          <div class="contact-panel">
            <div :class="`section-header mb4 ${data.alignment.value}`">
              <div class="section-title" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value"/></div>
              <span class="section-subtitle" ><InlineEditor v-model="data.subtitle.value" /></span>
            </div>
            <Sortable v-model="data.contact.value" class="columns flex-wrap is-centered">
              <SortableItem class="column is-4" v-for="(item, index) in data.contact.value" :key="`item-${index}`" :list="data.contact.value" :index="index">
                <div class="icl-contact icl-contact--style-1">
                  <div class="icl-contact__card">
                    <span class="icl-contact__icon" :style="{
                      color: item.icon_color ? item.icon_color.value : '',
                      fontSize: data.icon_size ? `${data.icon_size.value}px` : ''
                    }"><Icon v-model="item.icon.value" /></span>
                    <span class="icl-contact__label" :style="{fontSize: data.label_size ? `${data.label_size.value}px` : ''}"><InlineEditor v-model="item.label.value"/></span>
                    <span class="icl-contact__data" :style="{fontSize: data.value_size ? `${data.value_size.value}px` : ''}"><InlineEditor v-model="item.data.value"/></span>
                  </div>
                </div>
              </SortableItem>
            </Sortable>
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
      class="icl-section icl-section--contact-2 content-<?php echo $style->content_color->value; ?>">
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
        <div class="contact-panel">
          <div class="section-header mb4 <?php echo $data->alignment->value ?>">
            <h2 class="section-title" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo format_heading( $data->title->value); ?></h2>
            <span class="section-subtitle"><?php echo $data->subtitle->value; ?></span>
          </div>
          <div class="columns flex-wrap is-centered">
            <?php foreach( $data->contact->value as $item ): ?>
              <div class="column is-4">
                <div class="icl-contact icl-contact--style-1">
                  <div class="icl-contact__card">
                    <?php
                      $icon_color = isset($item->icon_color) ? $item->icon_color->value : "" ;
                      $icon_size  = isset($data->icon_size) ? $data->icon_size->value : "" ;
                      $label_size = isset($data->label_size) ? $data->label_size->value : "" ;
                      $value_size = isset($data->value_size) ? $data->value_size->value : "" ;
                    ?>
                    <div class="icl-contact__icon" style="<?php echo $icon_color ? "color:{$icon_color};": "";echo isset($data->icon_size) && $icon_size ? "font-size:{$data->icon_size->value}px;" : ""; ?>"><?php echo get_font_awesome_svg($item->icon->value); ?></div>
                    <span class="icl-contact__label" style="<?php echo $label_size ? "font-size:{$label_size}px" : ""; ?>"><?php echo $item->label->value;  ?></span>
                    <span class="icl-contact__data" style="<?php echo $label_size ? "font-size:{$value_size}px" : ""; ?>"><?php echo $item->data->value;  ?></span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
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
$contact2 = new Contact2();
$arrayCode[] = $contact2->data();
$arrayTemplate[] = $contact2->editorTemplate();