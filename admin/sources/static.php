<?php
if (!defined('SOURCES')) die("Error");
$linkUpload = "index.php?page=static&type=$type";
if (!isset($config['static'][$type])) $fn->transfer(trangkhongtontai, "index.php", false);
$result = $db->rawQueryOne("SELECT * FROM tbl_static WHERE type = ? LIMIT 1", [$type]);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $fn->save_data($_POST['data'] ?? [], $_FILES, $result['id'], [
    'table'    => 'tbl_static',
    'type'     => $type,
    'redirect' => $linkUpload
  ]);
}
$template = "static/static";
