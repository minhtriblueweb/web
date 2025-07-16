<?php
ob_start();
session_start();
include_once 'config/autoload.php';
include_once 'lib/router.php';

// SEO mặc định
$data_seo = $default_seo;

// SOURCES
$sources_file = SOURCES . $page;
$pageFile = 'pages/' . $page;
require_once SOURCES . "allpage.php";

if (file_exists($sources_file)) {
  include_once $sources_file;
}

// Gọi view
if (file_exists($pageFile)) {
  ob_start();
  include_once $pageFile;
  $page_content = ob_get_clean();
} else {
  http_response_code(404);
  include_once '404.php';
  exit();
}

// Load template chung
include TEMPLATE . "index.php";
