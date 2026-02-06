<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit('Forbidden');
}
require_once __DIR__ . '/../init.php';
$db = new Database();
$str = '<option value="0">Chọn danh mục</option>';
$allowTable = ['tbl_district', 'tbl_ward'];
if (!empty($_POST['id']) && isset($_POST['table'], $_POST['level'])) {
  $id    = (int)$_POST['id'];
  $level = (int)$_POST['level'];
  $table = $_POST['table'];
  if ($id <= 0 || !in_array($table, $allowTable)) {
    echo $str;
    exit;
  }
  switch ($level) {
    case 0:
      $parentCol = 'id_city';
      break;

    case 1:
      $parentCol = 'id_district';
      break;

    default:
      echo $str;
      exit;
  }
  $rows = $db->rawQuery("SELECT id, name FROM `$table` WHERE $parentCol = ? AND FIND_IN_SET(?,status) ORDER BY id ASC",[$id,'hienthi']);
  if (!empty($rows)) {
    foreach ($rows as $v) {
      $str .= '<option value="' . $v['id'] . '">' . $v['name'] . '</option>';
    }
  }
}
echo $str;
