<?php
require_once "helpers/builder_function.php";
require_once "helpers/builder_helper.php";
require_once "helpers/font_awesome_helper.php";
function current_url(){
    return "";
}
function base_url($path='false'){
    if ($path){
        return $_ENV['APP_URL'] . "/builder/{$path}";
    }
    return base_url();
}
function get_builder_url(){
    return $_ENV['APP_URL'] . '/builder/';
}

function get_base_path(){
    return dirname('/');
}

$api_url		= $_ENV['APP_URL'] . '/api/v1/project';//base_url("api/v1/project");
$preview_url    = base_url("api/v1/project");
$componentArray = glob(BASE_BUILDER_PATH . '/blocks/*/data.php');
$site_id = $_GET['project'];
// $componentArray = array('block/content-1/data.php', 'block/content-2/data.php', 'block/content-3/data.php');
$arrayCode = [];

if(isset($_GET['function'])){
    $_GET['function']();
}

function get_blocks(){
    global $componentArray;
    global $arrayCode;
    
    foreach($componentArray as $key => $item){
        require_once $item;
    }
    
    header('Content-Type: application/json');
    echo json_encode($arrayCode);
}

function render_templates(){
    global $componentArray;
    global $arrayCode;

    $arrayTemplate = array();
    
    foreach($componentArray as $key => $item){
        require_once $item;
        header('Content-Type:text/plain');
        ?>
		
        <script id="<?php echo $arrayCode[$key]['blockID']; ?>" type="x-template"><?php echo $arrayTemplate[$key]; ?></script>

        <?php
    }
}