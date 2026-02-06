<?php
ob_start();
session_start();
include_once 'config/autoload.php';
require_once 'config/type/config-type.php';
include_once 'lib/router.php';
// SOURCES
require_once SOURCES . "allpage.php";
$sources_file = SOURCES . $sources . ".php";
if (file_exists($sources_file)) {
  include_once $sources_file;
  if (!isset($template)) {
    $template = $sources . "/" . $sources;
  }
} else {
  $fn->abort_404();
}
include TEMPLATE . "index.php";
