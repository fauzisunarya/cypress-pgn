<?php 
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/../load.environment.php';

// base path
define('BASE_PATH', __DIR__);

// builder file base path (builder/assets/builder)
define('BASE_BUILDER_PATH', BASE_PATH . '/builder/assets/builder');

function get_builder_path($path=false){
    if ($path) {
        return $_ENV['APP_URL'] . "/builder/$path";
    }
    else{
        return $_ENV['APP_URL'] . "/builder/";
    }
}