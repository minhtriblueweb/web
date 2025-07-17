<?php
$type = $_GET['type'] ?? '';
$act  = $_GET['act'] ?? '';
$table = 'tbl_seopage';
$linkMan = "index.php?page=seopage&act=update&type=$type";

/* Kiểm tra cấu hình seopage hợp lệ */
if (!isset($config['seopage']) || empty($config['seopage']['page']) || !array_key_exists($type, $config['seopage']['page'])) {
  $fn->transfer("Trang không tồn tại!", "index.php", false);
}
$result = $db->rawQueryOne("SELECT * FROM $table WHERE type = ? LIMIT 1", [$type]) ?? '';

switch ($act) {
  case 'update':
    $seo_data = $db->rawQueryOne("SELECT * FROM $table WHERE type = ? LIMIT 1", [$type]);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
      $fn->save_data($_POST, $_FILES, $seo_data['id'], [
        'table' => $table,
        'fields_multi' => ['title', 'keywords', 'description'],
        'fields_common' => ['type'],
        'fields_options' => ['width', 'height', 'zc'],
        'redirect_page' => $linkMan,
        'enable_seo' => true,
      ]);
    }
    $breadcrumb = [['label' => $config['seopage']['page'][$type]]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "seopage/seopage.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại!", "index.php", false);
    break;
}
