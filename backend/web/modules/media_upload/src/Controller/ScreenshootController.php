<?php
namespace Drupal\media_upload\Controller;

use Drupal\Core\Url;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\file\Entity\File;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;

ini_set('memory_limit', '256M');

class ScreenshootController {

   private $webport = '';

   private $webDriver;

   public function __construct()
   {
      $this->webport = $_ENV['WEBPORT'] ?? 'http://localhost:4444';

      $this->handlerDriver();
   }

   public function run($web_link = null, $image_location = null)
   {
      $this->webDriver->get($web_link);

      $this->takeFullScreenshot($image_location);

      $this->webDriver->quit();

      return [
         'message' => 'success take screenshoot for selected page',
         'data'    => ['location' => $image_location]
      ];
   }

   private function handlerDriver($optional = []){
      $this->webDriver = RemoteWebDriver::create(
         $this->webport, // host
         $this->handleCapabilities($optional), // capabilities
         60 * 1000, // Connection timeout in miliseconds
         60 * 1000  // Request timeout in miliseconds
      );

      $this->webDriver->manage()->window()->maximize();
   }

   private function handleCapabilities($optional = [])
   {
      $options = new ChromeOptions();
      $options->addArguments(array_merge([
         '--headless',
         'window-size=1600,900',
         '--disable-gpu',
      ], $optional));

      return DesiredCapabilities::chrome()
         ->setCapability(ChromeOptions::CAPABILITY, $options);
   }

   private function takeFullScreenshot($screenshot_name)
   {
      $total_width = $this->webDriver->executeScript('return Math.max.apply(null, [document.body.clientWidth, document.body.scrollWidth, document.documentElement.scrollWidth, document.documentElement.clientWidth])');
      $total_height = $this->webDriver->executeScript('return Math.max.apply(null, [document.body.clientHeight, document.body.scrollHeight, document.documentElement.scrollHeight, document.documentElement.clientHeight])');

      $viewport_width = $this->webDriver->executeScript('return document.documentElement.clientWidth');
      $viewport_height = $this->webDriver->executeScript('return Math.max( window.innerHeight, document.body.clientHeight )');

      $this->webDriver->executeScript('window.scrollTo(0, 0)');

      $full_capture = imagecreatetruecolor($total_width, $total_height);

      $repeat_x = ceil($total_width / $viewport_width);
      $repeat_y = ceil($total_height / $viewport_height);

      for ($x = 0; $x < $repeat_x; $x ++) {
         $x_pos = $x * $viewport_width;

         $before_top = -1;
         for ($y = 0; $y < $repeat_y; $y++) {
            $y_pos = $y * $viewport_height;

            $this->webDriver->executeScript("window.scrollTo({$x_pos}, {$y_pos})");

            $scroll_left = $this->webDriver->executeScript("return window.pageXOffset");
            $scroll_top = $this->webDriver->executeScript("return window.pageYOffset");
            if ($before_top == $scroll_top) {
               break;
            }

            // wait before taking screenshoot or else the screen will go blank
            sleep(2);
            $this->webDriver->wait(2);

            $tmp_name = "{$screenshot_name}.tmp";
            $this->webDriver->takeScreenshot($tmp_name);
            if (!file_exists($tmp_name)) {
               throw new \Exception('Could not save screenshot');
            }

            $tmp_image = imagecreatefrompng($tmp_name);
            imagecopy($full_capture, $tmp_image, $scroll_left, $scroll_top, 0, 0, $viewport_width, $viewport_height);
            imagedestroy($tmp_image);
            unlink($tmp_name);

            $before_top = $scroll_top;
         }
      }

      // release memory
      unset($tmp_image, $tmp_name, $repeat_x, $repeat_y, $viewport_width, $viewport_height, $x_pos, $y_pos, $total_width, $total_height, $before_top, $scroll_top);

      imagejpeg($full_capture, $screenshot_name, 50);
      imagedestroy($full_capture);
   }

}