<?php $type  = $_GET['type'] ?? '';
$act   = $_GET['act'] ?? '';
$table = 'tbl_photo';
$name_page = '';
$status = ['hienthi' => 'Hiển thị'];
$fields_options = [];
$width = $height = $zc = '';
$img_type_list = '.jpg|.gif|.png|.jpeg|.webp';
$result = $db->rawQueryOne("SELECT * FROM $table WHERE type = ? LIMIT 1", [$type]) ?? '';

switch ($act) {
  /* --- 1. Ảnh Tĩnh: Watermark, Logo, Favicon --- */
  case 'photo_static':
    switch ($type) {
      case 'watermark':
        $name_page = 'Watermark';
        $width = 300;
        $height = 120;
        $fields_options = ['position', 'per', 'small_per', 'max', 'min', 'opacity', 'offset_x', 'offset_y'];
        break;

      case 'logo':
        $name_page = 'Logo';
        $width = 300;
        $height = 120;
        $zc = 1;
        $fields_options = ['width', 'height', 'zc'];
        break;

      case 'favicon':
        $name_page = 'Favicon';
        $width = 48;
        $height = 48;
        $zc = 1;
        $fields_options = ['width', 'height', 'zc'];
        break;

      default:
        $fn->transfer("Loại ảnh tĩnh không tồn tại!", "index.php", false);
        break;
    }
    $options = json_decode($result['options'] ?? '', true);
    foreach ($fields_options as $opt) {
      $$opt = $options[$opt] ?? ($$opt ?? '');
    }

    // Hiển thị & xử lý
    $breadcrumb = [['label' => $name_page]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "photo/static/photo_static.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
      $fn->save_data($_POST, $_FILES, $result['id'] ?? null, [
        'table' => $table,
        'fields_options' => $fields_options,
        'status_flags' => array_keys($status),
        'redirect_page' => "index.php?page=photo&act=photo_static&type=" . $type
      ]);
    }
    break;

  /* --- 2. Danh sách ảnh động: slideshow, gallery... --- */
  case 'photo_man':
    $records_per_page = 10;
    $current_page = max(1, (int)($_GET['p'] ?? 1));
    $keyword = $_GET['keyword'] ?? '';
    $name_page = '';
    $photo_list = [];

    switch ($type) {
      case 'slideshow':
        $name_page = 'Slideshow';
        break;

      case 'social':
        $name_page = 'Social';
        break;

      case 'ads':
        $name_page = 'Quảng cáo';
        break;

      default:
        $fn->transfer("Loại ảnh danh sách không tồn tại!", "index.php", false);
        break;
    }
    $total_rows = $fn->count_data([
      'table' => $table,
      'type'  => $type,
      'keyword' => $keyword
    ]);
    $total_pages = ceil($total_rows / $records_per_page);
    $paging = $fn->renderPagination($current_page, $total_pages);
    $photo_list = $fn->show_data([
      'table' => $table,
      'type'  => $type,
      'records_per_page' => $records_per_page,
      'current_page' => $current_page,
      'keyword' => $keyword
    ]);
    $linkMan    = "index.php?page=photo&act=photo_man&type=$type";
    $linkForm   = "index.php?page=photo&act=photo_form&type=$type";
    $linkEdit   = "$linkForm&id=";
    $linkDelete = "$linkMan&act=delete&id=";
    $linkMulti  = "$linkMan&act=delete_multiple";

    // Hiển thị giao diện
    $breadcrumb = [['label' => $name_page]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "photo/man/photo_man.php";
    break;
  /* --- 3. Form thêm/sửa ảnh dạng danh sách --- */
  case 'photo_form':
    $width = 1366;
    $height = 600;
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
    $result = $seo_data = [];
    if ($id !== null) {
      $result = $db->rawQueryOne("SELECT * FROM $table WHERE type = ? AND id = ?", [$type, $id]);
    }
    $linkMan = "index.php?page=photo&act=photo_man&type=$type";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
      $fn->save_data($_POST, $_FILES, $id, [
        'table'           => $table,
        'fields_multi'    => ['name', 'desc', 'content'],
        'fields_common'   => ['numb', 'type', 'link'],
        'status_flags'    => array_keys($status),
        'redirect_page'   => $linkMan,
        'convert_webp'    => true
      ]);
    }
    $breadcrumb = [['label' => ($id ? "Cập nhật " : "Thêm mới ") . $name_page]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "photo/man/photo_form.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại!", "index.php", false);
    break;
}
