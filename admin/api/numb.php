<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit('Forbidden');
}
require_once __DIR__ . '/../init.php';
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $table = !empty($_POST['table']) ? $_POST['table'] : '';
  $id = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
  $value = isset($_POST['value']) ? $_POST['value'] : '';

  if ($table && $id > 0) {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $result = $db->rawQuery("UPDATE `$table` SET numb = ? WHERE id = ?", [$value, $id]);
    echo $result ? 'success' : 'error';
  } else {
    http_response_code(400);
    echo 'Thiếu dữ liệu';
  }
}
