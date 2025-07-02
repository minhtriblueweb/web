<?php
$cssFiles = [
  "css/animate.min.css",
  "bootstrap/bootstrap.css",
  "fontawesome611/css/all.min.css",
  "confirm/confirm.css",
  "fileuploader/font-fileuploader.css",
  "fileuploader/jquery.fileuploader.min.css",
  "fileuploader/jquery.fileuploader-theme-dragdrop.css",
  "photobox/photobox.css",
  "fotorama/fotorama.css",
  "fotorama/fotorama-style.css",
  "simplenotify/simple-notify.css",
  "menu-mobile/menu-mobile.css",
  "fancybox5/fancybox.css",
  "slick/slick.css",
  "magiczoomplus/magiczoomplus.css",
  "aos/aos.css",
  "css/style.css"
];
foreach ($cssFiles as $file) {
  $css->set($file);
}
echo $css->get();

?>
<?php if (!$fn->isGoogleSpeed()) { ?>
  <!-- Js Google Analytic -->
  <?= $fn->decodeHtmlChars($setting['analytics']) ?>

  <!-- Js Head -->
  <?= $fn->decodeHtmlChars($setting['headjs']) ?>
<?php } ?>
