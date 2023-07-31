<?php

class ThemeHelper {

  public static function show_text(string $string, string $defaultValue = '-') {
    return empty($string) ? $defaultValue : $string;
  }

}

class TemplatePricing {

  public function convert_price_format($text_price){
    $price = (int)preg_replace("/\D/", '', $text_price);
    return number_format($price, 0, ',', '.');
  }
  
}