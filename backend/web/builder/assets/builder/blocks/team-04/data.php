<?php

class Team4 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'team-04',
      "title"      => 'Team 4',
      "screenshot" => 'team-04/team-04.jpg',
      "screenshot_size" => array( 600, 336 ),
      "template"   => '#team-04',
      "category"   => 'team',
        "data" => array(
          "title" => array(
            "type"            => 'wyswyg',
            "label"           => 'Judul',
            "heading"         => true,
            "value"           => '<strong>Executive Team</strong>',
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
            "value"           => 'we created the most simple way to create a website, just drag and drop you\'re ready to go',
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
          "teams" => array(
            "type"  => "repeatable",
            "label" => "Team",
            "item_title" => "name",
            "settings" => array(
              "avatar" => array(
                "type" => "image",
                "label" => "Gambar Penulis",
                "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/1.jpg',
              ),
              "name" => array(
                "type" => "text",
                "label" => "Name",
                "horizontal" => true,
                "value" => "Zig Ziglar"
              ),
              "title" => array(
                "type" => "text",
                "label" => "Title Penulis",
                "horizontal" => true,
                "value" => "Google Dev"
              ),
            ),
            "value"  => array(
              array(
                "avatar" => array(
                  "type" => "image",
                  "label" => "Gambar Penulis",
                  "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/1.jpg',
                ),
                "name" => array(
                  "type" => "text",
                  "label" => "Name",
                  "horizontal" => true,
                  "value" => "Zig Ziglar"
                ),
                "title" => array(
                  "type" => "text",
                  "label" => "Title Penulis",
                  "horizontal" => true,
                  "value" => "Google Dev"
                )
              ),
              array(
                "avatar" => array(
                  "type" => "image",
                  "label" => "Gambar Penulis",
                  "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/2.jpg',
                ),
                "name" => array(
                  "type" => "text",
                  "label" => "Name",
                  "horizontal" => true,
                  "value" => "Simon Sinek"
                ),
                "title" => array(
                  "type" => "text",
                  "label" => "Title Penulis",
                  "horizontal" => true,
                  "value" => "The Verge"
                )
              ),
              array(
                "avatar" => array(
                  "type" => "image",
                  "label" => "Gambar Penulis",
                  "value" => $this->base_url . 'assets/builder/blocks-assets/imgs/people/3.jpg',
                ),
                "name" => array(
                  "type" => "text",
                  "label" => "Name",
                  "horizontal" => true,
                  "value" => "Jonathan Doe"
                ),
                "title" => array(
                  "type" => "text",
                  "label" => "Title Penulis",
                  "horizontal" => true,
                  "value" => "Market Analyst"
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
              "backgroundColor"      => "#ffffff",
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
      class="icl-section icl-section--team-4"
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
          <Sortable class="icl-team-grid justify-content-center" v-model="data.teams.value">
            <SortableItem class="slide icl-team icl-team--style-4" v-for="(item, index) in data.teams.value" :key="`item-${index}`" :list="data.teams.value" :index="index">
              <div class="icl-team__card">
                <img class="icl-team__avatar" :src="item.avatar.value">
                <h3 class="icl-team__name"><InlineEditor v-model="item.name.value"/></h3>
                <span class="icl-team__title"><InlineEditor v-model="item.title.value"/></span>
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
      class="icl-section icl-section--team-4 content-<?php echo $style->content_color->value; ?>">
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

        <div class="icl-team-grid" style="justify-content: center;">
          <?php foreach( $data->teams->value as $item ): ?>
            <div class="slide icl-team icl-team--style-4">
              <div class="icl-team__card">
                <?php $thumbnail_url = strpos( $item->avatar->value , "/blocks-assets/imgs") || strpos( $item->avatar->value , "/stock_image") ? $item->avatar->value : get_image_thumbnail( $item->avatar->value, "small" ); ?>
                <img class="icl-team__avatar" src="<?php echo $thumbnail_url;?>">
                <h3 class="icl-team__name"><?php echo $item->name->value;  ?></h3>
                <span class="icl-team__title"><?php echo $item->title->value;  ?></span>
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

$team4 = new Team4();
$arrayCode[] = $team4->data();
$arrayTemplate[] = $team4->editorTemplate();