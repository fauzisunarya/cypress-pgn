<?php

require_once "api.php";

?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset=utf-8>
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <link rel=icon href=/favicon.ico> <link rel=stylesheet href=https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css integrity=sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO crossorigin=anonymous>
    <link href="https://fonts.googleapis.com/css?family=Lato" rel=stylesheet>
    <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css> <link rel=stylesheet href=https://use.fontawesome.com/releases/v5.3.1/css/all.css> <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel=stylesheet>
    <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/froala-design-blocks/2.0.1/css/froala_blocks.min.css> <title>builder</title>
    <link href=assets/css/app.css rel=preload as=style>
    <link href=assets/js/app.js rel=preload as=script>
    <link href=assets/js/chunk-vendors.js rel=preload as=script>
    <link href=assets/css/app.css rel=stylesheet>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.snow.css">
    <script>
      window.builder_vars = {
        assets: 'http://localhost/builder/blocks-assets',
        api: "http://localhost/builder/api.php?function="
      }
    </script>
</head>

<body><noscript><strong>We're sorry but builder doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></noscript>
    <div id=app></div>

    <?php render_templates(); ?>
    
    <script src=assets/js/chunk-vendors.js></script>
    <script src=assets/js/app.js></script>
</body>
</html>