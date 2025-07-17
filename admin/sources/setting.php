<?php
$type = $_GET['type'] ?? '';
$act  = $_GET['act'] ?? '';
$linkMan = "index.php?page=setting&act=update";
$result = $db->rawQueryOne("SELECT * FROM tbl_setting LIMIT 1");
$options = json_decode($result['options'] ?? '{}', true);
switch ($act) {
  case 'update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
      $fn->save_data($_POST, null, 1, [
        'table' => 'tbl_setting',
        'fields_multi'    => ['name', 'slogan', 'copyright'],
        'fields_common'   => ['video', 'mastertool', 'analytics', 'headjs', 'bodyjs'],
        'fields_options'  => ['address', 'opendoor', 'phone', 'hotline', 'zalo', 'oaidzalo', 'email', 'website', 'fanpage', 'coords', 'coords_iframe', 'coords_link', 'slogan', 'color', 'copyright', 'lang_default'],
        'redirect_page' => $linkMan
      ]);
    }
    $breadcrumb = [['label' => 'Cập nhật thông tin website']];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "setting/setting.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại!", "index.php", false);
    break;
}
