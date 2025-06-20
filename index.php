<?php
include_once 'config/autoload.php';
include_once 'lib/router.php';

// SEO mặc định
$seo = $default_seo;
// Cho phép các trang ghi đè biến SEO
$page_file = 'pages/' . $page;

if (file_exists($page_file)) {
  ob_start();
  include $page_file;
  $page_content = ob_get_clean();
} else {
  http_response_code(404);
  include '404.php';
  exit();
}

// Load template chung
include_once 'templates/header.php';
include_once 'templates/menu.php';
echo $page_content;
include_once 'templates/footer.php';
