<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit;
}
require_once __DIR__ . '/../init.php';
$db = new Database();
$str = '<option value="0">Chọn danh mục</option>';

if (!empty($_POST["id"])) {
  $level = isset($_POST["level"]) ? (int)$_POST["level"] : 0;
  $table = isset($_POST["table"]) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_POST["table"]) : '';
  $id = (int)$_POST["id"];

  switch ($level) {
    case 0:
      $id_temp = "id_list";
      break;
    case 1:
      $id_temp = "id_cat";
      break;
    case 2:
      $id_temp = "id_item";
      break;
    default:
      echo 'error ajax';
      exit();
  }

  if ($id && $table && $id_temp) {
    $result = $db->rawQuery("SELECT namevi, id FROM `$table` WHERE `$id_temp` = ? ORDER BY numb, id DESC", [$id]);
    if (!empty($result)) {
      foreach ($result as $row) {
        $str .= '<option value="' . $row["id"] . '">' . htmlspecialchars($row["namevi"]) . '</option>';
      }
    }
  }
}

echo $str;
