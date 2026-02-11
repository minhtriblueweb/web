<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit;
}
require_once __DIR__ . '/../init.php';
$db = new Database();
if (!empty($_POST["id"])) {
  $level = (!empty($_POST["level"])) ? htmlspecialchars($_POST["level"]) : 0;
  $table = (!empty($_POST["table"])) ? htmlspecialchars($_POST["table"]) : '';
  $id = (!empty($_POST["id"])) ? htmlspecialchars($_POST["id"]) : 0;
  $type = (!empty($_POST["type"])) ? htmlspecialchars($_POST["type"]) : '';
  $row = null;

  switch ($level) {
    case '0':
      $id_temp = "id_list";
      break;

    case '1':
      $id_temp = "id_cat";
      break;

    case '2':
      $id_temp = "id_item";
      break;

    default:
      echo 'error ajax';
      exit();
      break;
  }

  if ($id > 0) {
    $row = $db->rawQuery("SELECT namevi, id FROM {$table} WHERE {$id_temp} = ? AND type = ? ORDER BY numb ASC, id DESC", [$id, $type]);
  }

  $str = '<option value="0">' . chondanhmuc . '</option>';
  if (!empty($row)) {
    foreach ($row as $v) {
      $str .= '<option value=' . $v["id"] . '>' . $v["namevi"] . '</option>';
    }
  }
} else {
  $str = '<option value="0">' . chondanhmuc . '</option>';
}

echo $str;
