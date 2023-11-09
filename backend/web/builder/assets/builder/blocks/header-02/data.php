<?php

class Header2 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'header-02',
      "title"      => 'Header 2',
      "screenshot" => 'header-02/header-02.jpeg',
      "screenshot_size" => array( 600, 55 ),
      "template"   => '#header-02',
      "category"   => 'header',
        "data" => array(
          "logo" => array(
            "type"            => 'image',
            "description"     => 'Lebar & Tinggi Maksimum Logo 200px',
            "label"           => 'Logo',
            "value"           => $this->base_url . 'assets/builder/blocks-assets/imgs/telkom-logo.png',
          ),
          "logo_url" => array(
            "type"            => 'text',
            "horizontal"      => true,
            "label"           => 'Upload Logo',
            "input_type"      => 'link',
          ),
          "social" => array(
            "type" => "repeatable",
            "label" => "Social Icons",
            "item_title" => "label",
            "settings" => array(
              "label"=> array(
                "type" => "text",
                "horizontal" => true,
                "label"=> "Label",
                "value" => ""
              ),
              "url" => array(
                "type" => "text",
                "horizontal" => true,
                "label" => "URL",
                "value" => "#"
              ),
              "icon" => array(
                "type" => "icon",
                "horizontal" => true,
                "label" => "Icon",
                "value" => "fab fa-facebook"
              )
            ),
            "value" => array(
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => ""
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                ),
                "icon" => array(
                  "type" => "icon",
                  "horizontal" => true,
                  "label" => "Icon",
                  "value" => "fab fa-facebook"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => ""
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                ),
                "icon" => array(
                  "type" => "icon",
                  "horizontal" => true,
                  "label" => "Icon",
                  "value" => "fab fa-twitter"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => ""
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#",
                ),
                "icon" => array(
                  "type" => "icon",
                  "horizontal" => true,
                  "label" => "Icon",
                  "value" => "fab fa-instagram"
                )
              ),
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
          "vertical_spacing" => array(
            "type" => "slider",
            "horizontal" => true,
            "label" => "Vertical Spacing",
            "value" => 30,
            "min" => 0,
            "max" => 60
          ),
          "background_color" => array(
            "type" => "color",
            "horizontal" => true,
            "label" => "Warna Latar",
            "value" => "#FFFFFF"
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
          )
        )
    );
  }

  public function editorTemplate() {
    ob_start(); ?>
    <header
      :id="`block-${id}`"
      class="icl-header icl-header--style-2"
      :class="`content-${style.content_color.value}`"
      :style="{'backgroundColor': style.background_color.value}"
      >
      <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
        <nav
          class="navbar"
          role="navigation"
          aria-label="main navigation">

          <div class="navbar-brand">
            <a class="navbar-item brand" @click.prevent
              :style="{paddingTop: style.vertical_spacing.value+'px', paddingBottom: style.vertical_spacing.value+'px'}">
              <img :src="$store.state.project.logo">
            </a>

            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" :data-target="`navbar-${id}`">
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
            </a>
          </div>

          <div :id="`navbar-${id}`" class="navbar-menu">
            <navbar-menu className="navbar-start" />
            
            <div class="navbar-end">
              <div class="navbar-social-icons">
                <a href="#" v-for="social in data.social.value" :href="social.url.value"><Icon v-model="social.icon.value" /><span v-if="social.label.value"><InlineEditor v-model="social.label.value"/></span></a>
              </div>
            </div>

          </div>
        </nav>
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
      class="icl-header icl-header--style-2 content-<?php echo $style->content_color->value; ?>"
      style="background-color: <?php echo $style->background_color->value; ?>">
      <?php echo (!$style->fullwidth->value) ? "<div class=\"container\">" : ""; ?>
        <nav
          class="navbar"
          role="navigation"
          aria-label="main navigation">

          <div class="navbar-brand">
            <?php $logo_url = !empty($data->logo_url->value) ? $data->logo_url->value : user_site_base_url(); ?>
            <a class="navbar-item" href="<?php echo $logo_url; ?>"
              style="padding-top: <?php echo $style->vertical_spacing->value.'px';?>; padding-bottom: <?php echo $style->vertical_spacing->value.'px';?>">
              <img src="<?php echo $data->logo->value; ?>">
            </a>

            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbar-<?php echo $id; ?>">
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
            </a>
          </div>

          <div id="navbar-<?php echo $id; ?>" class="navbar-menu">
            <div class="navbar-start">
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

            <div class="navbar-end">
              <div class="navbar-social-icons">
                <?php foreach( $data->social->value as $social ): ?>
                <a  href="<?php echo $social->url->value; ?>">
                  <?php echo get_font_awesome_svg($social->icon->value,"el-icon"); ?>
                  <?php if (!empty($social->label->value)): ?>
                  <span><?php echo $social->label->value; ?></span>
                  <?php endif; ?>
                </a>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </nav>
      <?php echo (!$style->fullwidth->value) ? "</div>" : ""; ?>
    </header>
  <?php
    return ob_get_clean();
  }

  static function renderCSS(){
    return file_get_contents('style.css', FILE_USE_INCLUDE_PATH);
  }
}

$header2 = new Header2();
$arrayCode[] = $header2->data();
$arrayTemplate[] = $header2->editorTemplate();