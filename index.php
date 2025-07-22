<?php
ob_start();
session_start();
include_once 'config/autoload.php';
include_once 'lib/router.php';
require_once "lib/lang/web/$lang.php";

// SOURCES
require_once SOURCES . "allpage.php";
$sources_file = SOURCES . $sources;
if (file_exists($sources_file)) {
  ob_start();
  include_once $sources_file;
  $page_content = ob_get_clean();
} else {
  $fn->abort_404();
}
include TEMPLATE . "index.php";
