<?php
$linkMan = "index.php?page=setting&act=update";

if (!($row = $db->rawQueryOne("SELECT * FROM tbl_setting LIMIT 1"))) {
  $fn->transfer(dulieukhongcothuc, $linkMan, false);
}

$options = json_decode($row['options'] ?? '{}', true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fn->save_data($_POST['data'] ?? [], null, 1, [
    'table'     => "tbl_setting",
    'redirect'  => $linkMan
  ]);
}

$template = "setting/setting";
