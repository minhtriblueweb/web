<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_static';
$linkUpload = "index.php?com=static&type=" . $type;
if (!isset($config['static'][$type])) $func->transfer(trangkhongtontai, "index.php", false);
$result = $d->rawQueryOne("SELECT * FROM $table WHERE type = ? LIMIT 0,1", [$type]);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $func->save_data($_POST['data'] ?? [], $_FILES, $result['id'], [
    'table'    => $table,
    'type'     => $type,
    'redirect' => $linkUpload
  ]);
}
$template = "static/static";
