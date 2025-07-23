<?php
$page = $_GET['page'] ?? '';
$type = $_GET['type'] ?? '';

if (!isset($config['static'][$type])) {
  $fn->transfer("Trang không tồn tại!", "index.php", false);
}

$result = $db->rawQueryOne("SELECT * FROM tbl_static WHERE type = ? LIMIT 1", [$type]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $fn->save_data($_POST, $_FILES, $result['id'], [
    'table'         => 'tbl_static',
    'fields_multi'  => ['name', 'desc', 'content'],
    'fields_common' => ['type'],
    'status_flags'  => array_keys($config['static'][$type]['check']),
    'redirect_page' => 'index.php?page=static&type=' . $type,
  ]);
}

$breadcrumb = [['label' => $config['static'][$type]['title_main']]];
include TEMPLATE . LAYOUT . 'breadcrumb.php';
include TEMPLATE . "static/static.php";
