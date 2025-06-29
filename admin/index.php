<?php
require_once 'init.php';
Session::checkSession();

$page = $_GET['page'] ?? 'dashboard';
$page = preg_replace('/[^a-zA-Z0-9_-]/', '', basename($page));
if ($page === 'delete') {
  $fn->delete(
    $_GET['id'] ?? 0,
    $_GET['table'] ?? '',
    $_GET['type'] ?? '',
    $_GET['id_parent'] ?? null
  );
  exit;
}

if ($page === 'deleteMulti') {
  $fn->deleteMultiple(
    $_GET['listid'] ?? '',
    $_GET['table'] ?? '',
    $_GET['type'] ?? '',
    $_GET['id_parent'] ?? null
  );
  exit;
}
$page_file = __DIR__ . "/pages/{$page}.php";
// Tự động load config cho form nếu có
// $configFile = __DIR__ . "/setting/{$page}.php";
// $setting_page = [];

// if (file_exists($configFile)) {
//   $setting_page = include $configFile;
//   extract($setting_page); // Biến ra dùng trong file page
// }

if (!defined('IS_TRANSFER') && $page !== 'transfer') {
  include_once __DIR__ . '/templates/header.php';
  include_once __DIR__ . '/templates/sidebar.php';
}
if (file_exists($page_file)) {
  include_once $page_file;
} else {
  http_response_code(404);
  include __DIR__ . '/404.php';
}
if (!defined('IS_TRANSFER') && $page !== 'transfer') {
  include_once __DIR__ . '/templates/footer.php';
}
