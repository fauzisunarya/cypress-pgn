<?php

class Header4 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'header-04',
      "title"      => 'Header 4',
      "screenshot" => 'header-04/header-04.jpeg',
      "screenshot_size" => array( 894, 83 ),
      "template"   => '#header-04',
      "category"   => 'header',
        "data" => array(
          "spacer-1" => array(
            "type" => 'spacer'
          ),
          "background" => array(
            "type" => "color",
            "label" => "Warna Background Link",
            "horizontal" => true,
            "value" => "darkslategrey"
          ),
          "secondary_menu" => array(
            "type" => "repeatable",
            "label" => "Menu",
            "item_title" => "label",
            "settings" => array(
              "label"=> array(
                "type" => "text",
                "horizontal" => true,
                "label"=> "Label",
                "value" => "Contact Us"
              ),
              "url" => array(
                "type" => "text",
                "horizontal" => true,
                "label" => "URL",
                "value" => "#"
              )
            ),
            "value" => array(
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Home"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Contact Us"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "Customer Support"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
                )
              ),
              array(
                "label"=> array(
                  "type" => "text",
                  "horizontal" => true,
                  "label"=> "Label",
                  "value" => "About Us"
                ),
                "url" => array(
                  "type" => "text",
                  "horizontal" => true,
                  "label" => "URL",
                  "value" => "#"
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
            "value" => "darkslategrey"
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
          "spacer_2" => array(
            "type" => "spacer"
          ),
        )
    );
  }

  public function editorTemplate() {
    ob_start(); ?>
    <header
      :id="`block-${id}`"
      class="icl-header icl-header--style-5">
      <div
        class="navbar-main"
        :class="`content-${style.content_color.value}`"
        :style="{'backgroundColor': style.background_color.value}">
        <div :class="{'container': !style.fullwidth.value, 'container-fluid': style.fullwidth.value }">
          <nav
            class="navbar"
            role="navigation"
            aria-label="main navigation">
            <div class="navbar-brand">
              <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" :data-target="`navbar-${id}`">
                <span aria-hidden="true" :style="`background-color: ${style.content_color.value === 'light' ? 'white' : 'black'}; height: 2px;`"></span>
                <span aria-hidden="true" :style="`background-color: ${style.content_color.value === 'light' ? 'white' : 'black'}; height: 2px;`"></span>
                <span aria-hidden="true" :style="`background-color: ${style.content_color.value === 'light' ? 'white' : 'black'}; height: 2px;`"></span>
              </a>
            </div>

            <div :id="`navbar-${id}`" class="navbar-menu justify-content-center">
              <div class="navbar-start" style="margin-right: inherit;">
                <a
                  class="navbar-item"
                  v-for="menu_s in data.secondary_menu.value"
                  :href="menu_s.url.value"
                  :style="`height: 80px; padding: 15px; background-color: ${data.background.value}; color: ${style.content_color.value === 'light' ? 'white' : 'black'};`">
                  <InlineEditor v-model="menu_s.label.value"/>
                </a>
              </div>
            </div>
          </nav>
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
      class="icl-header icl-header--style-5">
      <div
        class="navbar-main content-<?php echo $style->content_color->value; ?>"
        style="background-color: <?php echo $style->background_color->value; ?>">

        <?php echo (!$style->fullwidth->value) ? "<div class=\"container\">" : ""; ?>

        <?php
          function renderContentColor($content_color) {
            $arrChoice = array(
              "default" => "black",
              "light" => "white",
              "dark" => "black",
            );

            return $arrChoice[$content_color];
          }

          $hamburgerColor = renderContentColor($style->content_color->value);
        ?>

        <nav
          class="navbar"
          role="navigation"
          aria-label="main navigation">

          <div class="navbar-brand">
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbar-<?php echo $id; ?>">
              <span aria-hidden="true" style="background-color: <?= $hamburgerColor; ?>; height: 2px;"></span>
              <span aria-hidden="true" style="background-color: <?= $hamburgerColor; ?>; height: 2px;"></span>
              <span aria-hidden="true" style="background-color: <?= $hamburgerColor; ?>; height: 2px;"></span>
            </a>
          </div>

          <div id="navbar-<?php echo $id; ?>" class="navbar-menu" style="justify-content: center;">
            <div class="navbar-start" style="margin-right: inherit;">
              <?php foreach( $data->secondary_menu->value as $menu_s ): ?>
                <a
                  class="navbar-item"
                  href="<?php echo $menu_s->url->value; ?>"
                  style="height: 80px; padding: 15px; background-color: <?= $data->background->value; ?>; color: <?= $hamburgerColor; ?>;">
                  <?php echo $menu_s->label->value; ?>
                </a>
              <?php endforeach; ?>
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

$header4 = new Header4();
$arrayCode[] = $header4->data();
$arrayTemplate[] = $header4->editorTemplate();