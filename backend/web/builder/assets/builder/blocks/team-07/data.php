<?php

class Team7 {

  public function __construct(){
    $this->base_url = get_base_url();
  }
  
  public function data() {

    require_once BASE_BUILDER_PATH . "/Render.php";

    return array(
      "blockID"    => 'team-07',
      "title"      => 'Team 7',
      "screenshot" => 'team-07/team-07.jpg',
      "screenshot_size" => array( 941, 533 ),
      "template"   => '#team-07',
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
                "value" => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minus, repellat?"
              ),
              // social media icons
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
                  "value" => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minus, repellat?"
                ),
                // social media icons
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
                  "value" => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minus, repellat?"
                ),
                // social media icons
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
                  "value" => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minus, repellat?"
                ),
                // social media icons
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
                  "value" => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minus, repellat?"
                ),
                // social media icons
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
                  "value" => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Minus, repellat?"
                ),
                // social media icons
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
                  "value" => "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quidem, saepe."
                ),
                // social media icons
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
              )
            ),
          ),
          "controls" => array(
            "type"            => 'switch',
            "label"           => 'Munculkan Panah',
            "value"           => false,
            "horizontal" => true,
          ),
          "nav" => array(
            "type"            => 'switch',
            "label"           => 'Munculkan titik navigasi',
            "value"           => true,
            "horizontal" => true,
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
              "backgroundColor"      => "#2C17AC",
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
      class="icl-section icl-section--team-1"
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
          <carousel
            :id="id"
            :items="`${data.teams.value.length < 3 ? data.teams.value.length : 3}`"
            :controls="data.controls.value"
            :nav="data.nav.value"
            :slides="data.teams.value"
            :gutter="30"
            :center="true"
            :slideBy="1"
            >
            <div class="slide icl-team icl-team--style-1" v-for="item in data.teams.value">
              <div class="icl-team__card">
                <img class="icl-team__avatar" :src="item.avatar.value">
                <span class="icl-team__name">{{item.name.value}}</span>
                <span class="icl-team__title">{{item.title.value}}</span>
              </div>

              <!-- multi sosmed button -->
              <div class="navbar-social-icons" style="margin-top: 10px;">
                <a href="#" v-for="social in item.social.value" :href="social.url.value"><Icon v-model="social.icon.value" /><span v-if="social.label.value"><InlineEditor v-model="social.label.value"/></span></a>
              </div>
            </div>
          </carousel>
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
      class="icl-section icl-section--team-1 content-<?php echo $style->content_color->value; ?>">
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

        <div 
          class="icl-carousel"
          data-items="<?= count($data->teams->value) < 3 ? count($data->teams->value) : 3; ?>"
          data-controls="<?php echo $data->controls->value; ?>"
          data-nav="<?php echo $data->nav->value; ?>"
          data-gutter="30"
          data-center="true"
          data-slide-by="1"
          >
          <?php foreach( $data->teams->value as $item ): ?>
            <div class="slide icl-team icl-team--style-1">
              <div class="icl-team__card">
                <?php $thumbnail_url = strpos( $item->avatar->value , "/blocks-assets/imgs") || strpos( $item->avatar->value , "/stock_image") ? $item->avatar->value : get_image_thumbnail( $item->avatar->value, "small" ); ?>
                <img class="icl-team__avatar" src="<?php echo $thumbnail_url;?>">
                <span class="icl-team__name"><?php echo $item->name->value;  ?></span>
                <span class="icl-team__title"><?php echo $item->title->value;  ?></span>
              </div>

              <!-- multi sosmed button -->
              <div class="navbar-social-icons" style="margin-top: 10px;">
                <?php foreach( $item->social->value as $social ): ?>
                  <a href="<?php echo $social->url->value; ?>">
                    <?php echo get_font_awesome_svg($social->icon->value,"el-icon"); ?>
                    <?php if (!empty($social->label->value)): ?>
                    <span><?php echo $social->label->value; ?></span>
                    <?php endif; ?>
                  </a>
                <?php endforeach; ?>
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

$team1 = new Team7();
$arrayCode[] = $team1->data();
$arrayTemplate[] = $team1->editorTemplate();