<?php
function views($formView, $dataPost){
    ob_start();
    extract($dataPost);
    require $formView;
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

echo views('pages/test.php',array("a"=>"Cat","b"=>"C"));
?>