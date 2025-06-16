<?php
require_once 'init.php';
Session::checkSession();

$page = $_GET['page'] ?? 'dashboard';
$page = preg_replace('/[^a-zA-Z0-9_-]/', '', basename($page));
if ($page === 'delete') {
  $id = $_GET['id'] ?? 0;
  $table = $_GET['table'] ?? '';
  $imageColumn = $_GET['image'] ?? '';
  $redirect = $_GET['redirect'] ?? 'dashboard';
  $id_parent = $_GET['id_parent'] ?? null;
  if ($id && $table && $imageColumn) {
    $functions->delete($id, $table, $imageColumn, $redirect, $id_parent);
  } else {
    $functions->transfer("Thiếu dữ liệu để xóa!", "index.php?page=$redirect", false);
  }
  exit();
}

if ($page === 'deleteMulti') {
  $listid = $_GET['listid'] ?? '';
  $table = $_GET['table'] ?? '';
  $imageColumn = $_GET['image'] ?? '';
  $redirect = $_GET['redirect'] ?? 'dashboard';
  $id_parent = $_GET['id_parent'] ?? null;

  if ($listid && $table && $imageColumn) {
    $functions->deleteMultiple($listid, $table, $imageColumn, $redirect, $id_parent);
  } else {
    $functions->transfer("Thiếu dữ liệu để xóa!", "index.php?page=$redirect", false);
  }
  exit();
}
$page_file = __DIR__ . "/pages/{$page}.php";

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
