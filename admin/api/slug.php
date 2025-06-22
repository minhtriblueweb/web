<?php
session_start();
require_once __DIR__ . '/../init.php';

$db = new Database();
$functions = new Functions($db);

$dataSlug = [];
$dataSlug['slug'] = !empty($_POST['slug']) ? trim($_POST['slug']) : '';
$dataSlug['table'] = !empty($_POST['table']) ? trim($_POST['table']) : '';
$dataSlug['id'] = !empty($_POST['id']) ? (int)$_POST['id'] : '';
$dataSlug['lang'] = !empty($_POST['lang']) ? trim($_POST['lang']) : 'vi';

// Gọi hàm kiểm tra đa ngôn ngữ
$check = $functions->isSlugDuplicated(
  $dataSlug['slug'],
  $dataSlug['table'],
  $dataSlug['id'],
  $dataSlug['lang']
);

if ($check !== false) {
  echo json_encode(['status' => 0, 'message' => $check]);
} else {
  echo json_encode(['status' => 1]);
}
exit;
