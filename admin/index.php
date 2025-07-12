<?php
require_once 'init.php';
Session::checkSession();
$page = $_GET['page'] ?? 'dashboard';
$page = preg_replace('/[^a-zA-Z0-9_-]/', '', basename($page));
if ($page === 'transfer') {
  define('IS_TRANSFER', true);
  $transferData = $_SESSION['transfer_data'] ?? ['msg' => 'Không có thông báo', 'page' => 'index.php?page=dashboard', 'numb' => false];
  unset($_SESSION['transfer_data']);
  $showtext = $transferData['msg'];
  $page_transfer = $transferData['page'];
  $numb = $transferData['numb'];
  include __DIR__ . '/' . TEMPLATE . LAYOUT . "transfer.php";
  exit();
}
$source_file = __DIR__ . '/' . SOURCES . "$page.php";
$page_file   = __DIR__ . '/' . TEMPLATE . "$page.php";
if (!file_exists($source_file) && !file_exists($page_file)) {
  define('IS_404', true);
}
if (!defined('IS_TRANSFER') && !defined('IS_404')) {
  include_once __DIR__ . '/' . TEMPLATE . LAYOUT . 'header.php';
  include_once __DIR__ . '/' . TEMPLATE . LAYOUT . 'sidebar.php';
}
if (file_exists($source_file)) {
  include_once $source_file;
} elseif (file_exists($page_file)) {
  include_once $page_file;
} else {
  http_response_code(404);
  include __DIR__ . '/404.php';
}
if (!defined('IS_TRANSFER') && !defined('IS_404')) {
  include_once __DIR__ . '/' . TEMPLATE . LAYOUT . 'footer.php';
}
