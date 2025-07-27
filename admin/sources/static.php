<?php
if (!defined('SOURCES')) die("Error");
$linkUpload = "index.php?page=static&type=$type";
if (!isset($config['static'][$type])) $fn->transfer(trangkhongtontai, "index.php", false);
$result = $db->rawQueryOne("SELECT * FROM tbl_static WHERE type = ? LIMIT 1", [$type]);
if (!$result) $fn->transfer(dulieukhongcothuc, $linkUpload, false);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $fn->save_data($_POST, $_FILES, $result['id'], [
    'table'         => 'tbl_static',
    'fields_multi'  => ['name', 'desc', 'content'],
    'fields_common' => ['type'],
    'status_flags'  => $config['static'][$type]['check'],
    'redirect_page' => $linkUpload
  ]);
}
$template = "static/static";
