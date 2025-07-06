<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit;
}
require_once __DIR__ . '/../init.php';
$dataSlug = [
  'slug' => trim($_POST['slug'] ?? ''),
  'table' => trim($_POST['table'] ?? ''),
  'exclude_id' => (int)($_POST['id'] ?? 0),
  'lang' => trim($_POST['lang'] ?? 'vi')
];
$check = $fn->checkSlug($dataSlug);
if ($check === false) {
  echo json_encode(['status' => 1]);
} elseif (is_string($check)) {
  echo json_encode(['status' => 0, 'message' => $check]);
} else {
  echo json_encode(['status' => 0]);
}
exit;
