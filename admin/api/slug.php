<?php
include "config.php";

$dataSlug = [
  'slug'       => trim($_POST['slug'] ?? ''),
  'table'      => trim($_POST['table'] ?? ''),
  'exclude_id' => (int)($_POST['id'] ?? 0),
  'lang'       => trim($_POST['lang'] ?? 'vi')
];

$check = $func->checkSlug($dataSlug);

$response = ['status' => 0];

if ($check === false) {
  $response['status'] = 1;
} elseif (is_string($check)) {
  $response['message'] = $check;
}

echo json_encode($response);
exit;
