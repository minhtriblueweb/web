<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/../init.php';

$db = new Database();
$dataSlug = [];
$dataSlug['slug'] = !empty($_POST['slug']) ? trim($_POST['slug']) : '';
$dataSlug['table'] = !empty($_POST['table']) ? trim($_POST['table']) : '';
$dataSlug['id'] = !empty($_POST['id']) ? (int)$_POST['id'] : '';
$dataSlug['lang'] = !empty($_POST['lang']) ? trim($_POST['lang']) : 'vi';

// Gọi hàm kiểm tra đa ngôn ngữ
$check = $fn->checkSlug([
  'slug' => $dataSlug['slug'],
  'table' => $dataSlug['table'],
  'exclude_id' => $dataSlug['id'],
  'lang' => $dataSlug['lang']
]);

if ($check === false) {
  echo json_encode(['status' => 1]);
} elseif (is_string($check)) {
  echo json_encode(['status' => 0, 'message' => $check]);
} else {
  echo json_encode(['status' => 0, 'message' => 'Lỗi không xác định']);
}
exit;
