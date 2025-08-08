<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
<?php
$cssFiles = [
  "fontawesome611/all.css",
  "confirm/confirm.css",
  "select2/select2.css",
  "sumoselect/sumoselect.css",
  "datetimepicker/jquery.datetimepicker.css",
  "daterangepicker/daterangepicker.css",
  "rangeSlider/ion.rangeSlider.css",
  "filer/jquery.filer.css",
  "filer/jquery.filer-dragdropbox-theme.css",
  "holdon/HoldOn.css",
  "holdon/HoldOn-style.css",
  "simplenotify/simple-notify.css",
  "comment/comment.css",
  "fancybox5/fancybox.css",
  "css/adminlte.css",
  "css/adminlte-style.css",
];

foreach ($cssFiles as $file) {
  echo '<link href="./assets/' . $file . '?v=' . VERSION . '" rel="stylesheet" />' . PHP_EOL;
}
?>
