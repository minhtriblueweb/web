<?php
$redirect_url = $_GET['page'] ?? '';
$type = $_GET['type'] ?? '';
$name_page = $fn->convert_type($type)['vi'];
$status =  ['hienthi' => 'Hiển thị'];
$result = $db->rawQueryOne("SELECT * FROM tbl_static WHERE type = ? LIMIT 1", [$type]);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $fn->save_data($_POST, $_FILES, $result['id'], [
    'table'         => 'tbl_static',
    'fields_multi'  => ['name', 'desc', 'content'],
    'fields_common' => ['type'],
    'status_flags' => array_keys($status),
    'redirect_page' => 'index.php?page=static&type=' . $type,
  ]);
}
$breadcrumb = [['label' => $name_page]];
include TEMPLATE . LAYOUT . 'breadcrumb.php';
include TEMPLATE . "static/static.php";
