<?php 

require_once __DIR__ . '/../load.basepath.php';
require_once('assets/builder/api.php');

?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Builder | Telkom CMS </title>
    <meta content="Cara Membuat Website UKM jadi Mudah & Banyak Trafik - UKMDigital" property="og:title">
    <meta name="description" content="Dibuat untuk para UKM, & pebisnis online. Cara mudah membuat website tanpa koding, tinggal pilih blok yang Anda butuhkan, sesuaikan halaman, & publikasikan."/>
    <meta name="author" content="Telkom CMS"/>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url('favicon.ico'); ?>"/>
    <meta property="og:locale" content="id-ID"/>
    <meta property="og:site_name" content="Telkom CMS"/>
    <meta property="og:image" content="<?php echo base_url('favicon.ico'); ?>"/>
    <meta property="og:image:width" content="200"/>
    <meta property="og:image:height" content="41"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Cara Membuat Website UKM jadi Mudah & Banyak Trafik - UKMDigital"/>
    <meta property="og:description" content="Dibuat untuk para UKM, & pebisnis online. Cara mudah membuat website tanpa koding, tinggal pilih blok yang Anda butuhkan, sesuaikan halaman, & publikasikan."/>
    <meta property="og:url" content="<?php echo base_url(); ?>"/>
    <meta property="fb:app_id" content=""/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="Cara Membuat Website UKM jadi Mudah & Banyak Trafik - UKMDigital"/>
    <meta name="twitter:description" content="Dibuat untuk para UKM, & pebisnis online. Cara mudah membuat website tanpa koding, tinggal pilih blok yang Anda butuhkan, sesuaikan halaman, & publikasikan."/>
    <meta name="twitter:image" content="<?php echo base_url('favicon.ico'); ?>"/>
    <link rel="canonical" href="<?php echo current_url(); ?>"/>
    <link rel="stylesheet" href="<?php echo base_url('assets/builder/blocks-assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/builder/blocks-assets/css/fontawesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/builder/blocks-assets/css/material-icons.min.css'); ?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href=<?php echo base_url("assets/builder/assets/css/app.css") ?> rel=preload as=style>
    <link href=<?php echo base_url("assets/builder/assets/js/app.js") ?> rel=preload as=script>
    <link href=<?php echo base_url("assets/builder/assets/js/chunk-vendors.js") ?> rel=preload as=script>
    <link href=<?php echo base_url("assets/builder/assets/css/app.css") ?> rel=stylesheet>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.snow.css" rel="stylesheet">
    <script>
      window.builder_vars = {
        path: "<?php echo base_url("assets/builder") ?>",
        api: "<?php echo $api_url; ?>",
        preview : "<?php echo $preview_url; ?>",
        back_url : "<?php echo "{$_ENV['APP_URL']}/landing-master"  ?>",
        extra: {
          site_id: "<?php echo $site_id; ?>",
          is_template: <?php echo $is_template ?? 'false';?>
        }
      }
    </script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>

<body>
  <noscript>
    <strong>We're sorry but builder doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
  </noscript>
    <div id=app></div>
    
    <script src=<?php echo base_url("assets/builder/assets/js/chunk-vendors.js") ?>></script>
    <script src=<?php echo base_url("assets/builder/assets/js/app.js") ?>></script>
</body>
</html>