<?php

class Header8 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'header-08',
      "title"      => 'Header 8',
      "screenshot" => 'header-08/header-08.jpeg',
      "screenshot_size" => array( 600, 312 ),
      "template"   => '#header-08',
      "category"   => 'header',
        "data" => array(
          "logo" => array(
            "type"            => 'image',
            "description"     => 'Lebar & Tinggi Maksimum Logo 200px',
            "label"           => 'Logo',
            "value"           => $this->base_url . 'assets/builder/blocks-assets/imgs/telkom-logo-white.png',
          ),
          "logo_url" => array(
            "type"            => 'text',
            "horizontal"      => true,
            "label"           => 'Upload Logo',
            "input_type"      => 'link',
          ),
          "title" => array(
            "type" => "wyswyg",
            "label" => "Judul Hero",
            "heading" => true,
            "value" => "<strong>A new and better way to build a web</strong>"
          ),
          "title_size" => array(
            "type" => "slider",
            "label" => "Ukuran Judul",
            "horizontal" => true,
            "value" => 48,
            "min" => 16,
            "max" => 100,
          ),
          "description" => array(
            "type" => "wyswyg",
            "label" => "Description",
            "value" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium voluptatibus doloremque dicta aliquam sint possimus optio voluptas adipisci odio expedita."
          ),
          
          "cta" => array(
            "type" => "button",
            "label" => "Tombol Aksi",
            "value" => array(
              "enable" => true,
              "url"           => "http://example.com",
              "new_window"    => true,
              "label"         => "Learn More",
              "background"    => "#007bff",
              "color"         => "#ffffff",
              "size"          => "is-normal", //[small, normal, medium, large]
              "style"         => "is-outline", //[fill, outline ]
              "corner"        => "is-rounded", //[square, rounded, pill]
              "icon"          => '',
              "icon_position" => "right"
            )
          ),
          "alignment" => array(
            "type"            => 'radio-icon',
            "label"           => 'Rata Judul',
            "horizontal"      => true,
            "value"           => 'has-text-centered',
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
        ),
        "style" => array(
          "fullwidth" => array(
            "type"       => "switch",
            "horizontal" => true,
            "label"      => "Lebar Konten Penuh",
            "value"      => false,
          ),
          "navbar_spacing" => array(
            "type" => "slider",
            "horizontal" => true,
            "label" => "Jarak vertikal navigasi",
            "value" => 30,
            "min" => 0,
            "max" => 60
          ),
          "hero_spacing" => array(
            "type" => "directional",
            "label" => "Jarak konten Hero",
            "value" => array(
              "top" => 100,
              "bottom" => 150,
              "left" => 250,
              "right" => 250,
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
              "backgroundColor"      => "#FFFFFF",
              "backgroundImage"      => $this->base_url . 'builder/assets/builder/blocks-assets/imgs/photos/workplace.jpeg',
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
            "value" => "rgba(255,0,0,.6)"
            // "value" => "rgba(0,0,200,.6)"
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
    <header
      :id="`block-${id}`"
      class="icl-header icl-header--style-8"
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
      <div class="page-navbar">
        <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
          <nav
            class="navbar"
            role="navigation"
            aria-label="main navigation">

            <div class="navbar-brand">
              <a class="navbar-item brand" @click.prevent
                :style="{paddingTop: style.navbar_spacing.value+'px', paddingBottom: style.navbar_spacing.value+'px'}">
                <img :src="$store.state.project.logo">
              </a>

              <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" :data-target="`navbar-${id}`">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
              </a>
            </div>
            
            <div :id="`navbar-${id}`" class="navbar-menu">
              <navbar-menu className="navbar-end" />
            </div>
          </nav>
        </div>
      </div>
      <div :class="`page-hero ${data.alignment.value}`">
        <div
          :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }"
          :style="{'padding': `${style.hero_spacing.value.top}px ${style.hero_spacing.value.right}px ${style.hero_spacing.value.bottom}px ${style.hero_spacing.value.left}px`}">
          <h2 class="hero-title" :style="{fontSize:`${data.title_size.value}px`}"><InlineEditor v-model="data.title.value" /></h2>
          <div class="hero-description" ><InlineEditor v-model="data.description.value" /></div>
          <Button :data="data.cta.value" />
        </div>
      </div>
    </header>
    <?php
    return ob_get_clean();

  }

  static function render($id, $data, $style, $header) {
    ob_start();
    ?>
    <header
      id="block-<?php echo $id; ?>"
      class="icl-header icl-header--style-8  content-<?php echo $style->content_color->value; ?>">
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
      <div class="page-navbar">
        <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>">
          <nav
            class="navbar"
            role="navigation"
            aria-label="main navigation">

            <div class="navbar-brand">
              <?php $logo_url = !empty($data->logo_url->value) ? $data->logo_url->value : user_site_base_url(); ?>
              <a class="navbar-item brand" href="<?php echo $logo_url; ?>"
                style="padding-top: <?php echo $style->navbar_spacing->value.'px';?>; padding-bottom: <?php echo $style->navbar_spacing->value.'px';?>">
                <img src="<?php echo $data->logo->value; ?>">
              </a>

              <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbar-<?php echo $id; ?>">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
              </a>
            </div>

            <div id="navbar-<?php echo $id; ?>" class="navbar-menu">
              <div class="navbar-end">
                <?php
                $current_slug = substr(strrchr(parse_url($_SERVER["REQUEST_URI"])['path'], '/'), 1);
                foreach( $header as $menu ): 
                  $is_active = "";
                  if ( $menu->single ) continue;
                  if ( ! $menu->hidden ) : 
                  if ( isset( $menu->slug ) ) {
                if ( $menu->homepage ) {
                  $is_active = "/" == $_SERVER["REQUEST_URI"] ? "is-active": '';
                } else {
                  $is_active = $current_slug == $menu->slug ?'is-active' : '';
                }
              }
                ?>

                <?php if ($menu->type == "product"): ?>
                  <div
                    class="navbar-item has-dropdown is-hoverable"
                    style="background-color: inherit;"
                  >
                    <a
                      class="navbar-link"
                      href="#"
                      style="background-color: transparent;"
                    >
                      <?= $menu->label; ?>
                    </a>
                    
                    <div
                      class="navbar-dropdown"
                      style="background-color: <?= $menu->backgroundColor; ?>;"
                    >
                      <?php if ($menu->category_id > 0): ?>
                        <div class="nested navbar-item dropdown">
                          <div class="dropdown-trigger">
                            <a
                              href="//<?= $menu->catalog->url; ?>"
                              style="color: <?= $menu->color; ?>;"
                              aria-haspopup="true"
                              aria-controls="dropdown-menu"
                            >
                              <span><?= $menu->catalog->label; ?></span>
                            </a>
                          </div>
                          <?php if (isset($menu->catalog->product) && !empty($menu->catalog->product)): ?>
                            <div class="dropdown-menu" id="dropdown-menu" role="menu">
                              <div class="dropdown-content" style="background-color: <?= $menu->backgroundColor; ?>;">
                                <?php foreach( $menu->catalog->product as $product ): ?>
                                  <a href="//<?= $product->url; ?>" class="dropdown-item" style="background-color: <?= $menu->backgroundColor; ?>; color: <?= $menu->color; ?>;">
                                    <?= $product->label; ?>
                                  </a>
                                <?php endforeach; ?>
                              </div>
                            </div>
                          <?php endif; ?>
                        </div>
                      <?php else: ?>
                        <?php foreach( $menu->catalog as $category ): ?>
                          <div class="nested navbar-item dropdown">
                            <div class="dropdown-trigger">
                              <a
                                href="//<?= $category->url; ?>"
                                style="color: <?= $menu->color; ?>;"
                                aria-haspopup="true"
                                aria-controls="dropdown-menu"
                              >
                                <span><?= $category->label; ?></span>
                              </a>
                            </div>
                            <?php if (isset($category->product) && !empty($category->product)): ?>
                              <div class="dropdown-menu" id="dropdown-menu" role="menu">
                                <div class="dropdown-content" style="background-color: <?= $menu->backgroundColor; ?>;">
                                  <?php foreach( $category->product as $product ): ?>
                                    <a href="//<?= $product->url; ?>" class="dropdown-item" style="background-color: <?= $menu->backgroundColor; ?>; color: <?= $menu->color; ?>;">
                                      <?= $product->label; ?>
                                    </a>
                                  <?php endforeach; ?>
                                </div>
                              </div>
                            <?php endif; ?>
                          </div>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php else: ?>
                  <a class="navbar-item <?php echo $is_active; ?>" href="<?php echo ($menu->type == "page") ? $menu->homepage ? base_url() : base_url() . $menu->slug : $menu->url; ?>">
                    <?php echo $menu->label; ?>
                  </a>
                <?php endif; ?>

                <?php 
                  endif;
                endforeach;
                ?>
              </div>

            </div>
          </nav>
        </div>
      </div>
      <?php
        $padding = array(
          "top" => $style->hero_spacing->value->top.'px',
          "right" => $style->hero_spacing->value->right.'px',
          "bottom" => $style->hero_spacing->value->bottom.'px',
          "left" => $style->hero_spacing->value->left.'px',
        );
      ?>
      <div class="page-hero  <?php echo $data->alignment->value; ?>">
        <div class="<?php echo (!$style->fullwidth->value) ? 'container' : 'container-fluid'; ?>" style="padding: <?php echo implode(" ", $padding); ?>">
          <h2 class="hero-title" style="font-size:<?php echo $data->title_size->value; ?>px;"><?php echo format_heading( $data->title->value); ?></h2>
          <div class="hero-description"><?php echo $data->description->value; ?></div>
          <?php render_button($data->cta->value, ''); ?>
        </div>
      </div>
    </header>
  <?php
    return ob_get_clean();
  }

  static function renderCSS(){
    return file_get_contents('style.css', FILE_USE_INCLUDE_PATH);
  }

}



$header8 = new Header8();
$arrayCode[] = $header8->data();
$arrayTemplate[] = $header8->editorTemplate();