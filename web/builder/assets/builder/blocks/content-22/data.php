<?php

class Content22 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Component.php";
    require_once BASE_BUILDER_PATH . "/Render.php";

    $component = new Component();
    $component->setButton(array(
      "enable" => true,
      "url"           => "http://example.com",
      "new_window"    => true,
      "label"         => "Learn More",
      "background"    => "#fff",
      "color"         => "#000",
      "size"          => "is-normal", //[small, normal, medium, large]
      "style"         => "is-fill", //[fill, outline ]
      "corner"        => "is-square", //[square, rounded, pill]
      "icon"          => '',
      "icon_position" => "right"
    ));

    return array(
      "blockID"    => 'content-22',
      "title"      => 'content 22',
      "screenshot" => 'content-22/content-22.jpg',
      "screenshot_size" => array( 600, 330 ),
      "template"   => '#content-22',
      "category"   => 'content',
        "data" => array(
          "toptitle" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>DON\'t SETTLE FOR LESS</strong>',
          ),
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Our Consulting Services</strong>',
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
          "content" => array(
            "type"            => 'wyswyg',
            "label"           => 'Konten',
            "value"           => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio veritatis et, dicta eveniet totam at vero enim autem. Labore, perspiciatis nemo iusto porro ipsam animi neque officiis sequi voluptates dolor.</p>',
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

          "spacer-1" =>  array( "type" => "spacer" ),

          "features" => array(
            "type"  => "repeatable",
            "label" => "Features",
            "item_title" => "title",
            "settings" => array(
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
                "value" => "<p>Eligendi facere rem nostrum optio pariatur, nobis tempore numquam impedit, recusandae libero</p>"
              ),
              "button" => $component->getButton(),
              "background" => array(
                "type" => "color",
                "label" => "Warna Latar Item",
                "horizontal" => true,
                "value" => "#000"
              ),
              "color" => array(
                "type" => "color",
                "label" => "Warna Konten Item",
                "horizontal" => true,
                "value" => '#fff'
              ),
              "featured" => array(
                "type" => "switch",
                "label" => "Favorit",
                "horizontal" => true,
                "value" => false
              )
            ),
            "value"  => array(
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Block Components</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>Eligendi facere rem nostrum optio pariatur, nobis tempore odit quo porro molestias error mollitia.</p>"
                ),
                "button" => $component->getButton(),
                "background" => array(
                  "type" => "color",
                  "label" => "Warna Latar Item",
                  "horizontal" => true,
                  "value" => "#e9b85e"
                ),
                "color" => array(
                  "type" => "color",
                  "label" => "Warna Konten Item",
                  "horizontal" => true,
                  "value" => '#fff'
                ),
                "featured" => array(
                  "type" => "switch",
                  "label" => "Favorit",
                  "horizontal" => true,
                  "value" => false
                )
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Responsive</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>nobis tempore numquam impedit deleniti nostrum optio pariatur, odit quo porro molestias error mollitia.</p>"
                ),
                "button" => $component->getButton(),
                "background" => array(
                  "type" => "color",
                  "label" => "Warna Latar Item",
                  "horizontal" => true,
                  "value" => "#3d3d3d"
                ),
                "color" => array(
                  "type" => "color",
                  "label" => "Warna Konten Item",
                  "horizontal" => true,
                  "value" => '#fff'
                ),
                "featured" => array(
                  "type" => "switch",
                  "label" => "Favorit",
                  "horizontal" => true,
                  "value" => true
                )
              ),
              array(
                "title" => array(
                  "type" => "wyswyg",
                  "label" => "Judul",
                  "heading" => true,
                  "horizontal" => true,
                  "value" => "<strong>Responsive</strong>"
                ),
                "content" => array(
                  "type" => "wyswyg",
                  "label" => "Content",
                  "value" => "<p>nobis tempore numquam impedit deleniti nostrum optio pariatur, odit quo porro molestias error mollitia.</p>"
                ),
                "button" => $component->getButton(),
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
                  "value" => '#000'
                ),
                "featured" => array(
                  "type" => "switch",
                  "label" => "Favorit",
                  "horizontal" => true,
                  "value" => false
                )
              ),
            ),
          ),

          "item_title_size" => array(
            "type" => "slider",
            "label" => "Ukuran Judul",
            "value" => 30,
            "min" => 10,
            "max" => 100
          )

          
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
      class="icl-section icl-section--content-22"
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
            <div class="section-title--top" ><InlineEditor v-model="data.toptitle.value" /></div>
            <div class="section-title mb3" :style="`--font-size:${data.title_size.value}px`"><InlineEditor v-model="data.title.value"/></div>
            <div ><InlineEditor v-model="data.content.value" /></div>
          </div>

          <Sortable class="feature-grid columns is-variable is-0 is-vcentered is-centered" v-model="data.features.value">
            <SortableItem class="column is-4" v-for="(item, index) in data.features.value" :key="`item-${index}`" :list="data.features.value" :index="index">
              <div class="icl-feature icl-feature--style-2" :class="{'featured': item.featured.value}" :style="{backgroundColor: item.background.value, color: item.color.value }">
                <div class="icl-feature__card">

                  <h2 class="icl-feature__title" :style="{fontSize: `${data.item_title_size.value}px`}" ><InlineEditor v-model="item.title.value" /></h2>
                  <div class="icl-feature__content mb3" ><InlineEditor v-model="item.content.value" /></div>
                  <Button :data="item.button.value" />

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
      class="icl-section icl-section--content-22 content-<?php echo $style->content_color->value; ?>">
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
            <?php if(format_heading($data->toptitle->value) != ""): ?>
              <span class="section-title--top" data-aos="fade-up"><?php echo format_heading($data->toptitle->value); ?></span>
            <?php endif; ?>

            <?php if(format_heading($data->title->value) != ""): ?>
              <h2 class="section-title mb3" data-aos="fade-up" data-aos-delay="150" style="--font-size: <?php echo $data->title_size->value;  ?>px"><?php echo format_heading($data->title->value); ?></h2>
            <?php endif; ?>

            <?php if(format_heading($data->content->value) != ""): ?>
              <div data-aos="fade-up" data-aos-delay="300"><?php echo $data->content->value; ?></div>
            <?php endif; ?>
          </div>
          
          <div class="feature-grid columns is-variable is-0 is-vcentered is-centered" data-aos="fade-up" data-aos-delay="450">
            <?php foreach( $data->features->value as $item ): ?>
              <div class="column is-4">
                <div class="icl-feature icl-feature--style-2 <?php echo true == $item->featured->value ? 'featured' : ''; ?>" style="background-color:<?php echo $item->background->value; ?>;color:<?php echo $item->color->value; ?>">
                  <div class="icl-feature__card">
                    <?php if(format_heading($item->title->value) != ''): ?>
                      <h2 class="icl-feature__title" style="font-size:<?php echo $data->item_title_size->value; ?>px"><?php echo $item->title->value;  ?></h2>
                    <?php endif; ?>

                    <?php if(format_heading($item->content->value) != ''): ?>
                      <div class="icl-feature__content mb3">
                        <?php echo $item->content->value;  ?>
                      </div>
                    <?php endif; ?>

                    <?php render_button($item->button->value, ''); ?>
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



$content22 = new Content22();
$arrayCode[] = $content22->data();
$arrayTemplate[] = $content22->editorTemplate();