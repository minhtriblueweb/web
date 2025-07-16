<?php
$type  = $_GET['type'] ?? '';
$act   = $_GET['act'] ?? '';
$table = 'tbl_photo';
$name_page = '';
$result = $db->rawQueryOne("SELECT * FROM $table WHERE type = ? LIMIT 1", [$type]) ?? '';
$linkMan    = "index.php?page=photo&act=photo_man&type=$type";
switch ($act) {
  case 'delete':
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
      $fn->delete_data([
        'id' => (int)$_GET['id'],
        'table' => $table,
        'type' => $type,
        'redirect_page' => $linkMan
      ]);
    } else {
      $fn->transfer("ID không hợp lệ!", $linkMan, false);
    }
    break;
  case 'delete_multiple':
    if (!empty($_GET['listid'])) {
      $fn->deleteMultiple_data([
        'listid' => $_GET['listid'],
        'table' => $table,
        'type' => $type,
        'redirect_page' => $linkMan
      ]);
    } else {
      $fn->transfer("Không có danh sách ID cần xoá!", $linkMan, false);
    }
    break;

  case 'photo_static':
    $photoConfig = $config['photo']['photo_static'][$type] ?? null;
    if (!$photoConfig) $fn->transfer("Trang không tồn tại!", "index.php", false);
    $zc = 1;
    if (!empty($photoConfig['thumb'])) {
      $thumbParts = explode('x', $photoConfig['thumb']);
      $zc = isset($thumbParts[2]) ? (int)$thumbParts[2] : 1;
    }
    $fields_options = !empty($photoConfig['watermark-advanced'])
      ? ['position', 'per', 'small_per', 'max', 'min', 'opacity', 'offset_x', 'offset_y']
      : ['width', 'height', 'zc'];

    $options = json_decode($result['options'] ?? '', true);
    foreach ($fields_options as $opt) {
      $$opt = $options[$opt] ?? ($$opt ?? '');
    }

    $breadcrumb = [['label' => $name_page]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "photo/static/photo_static.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
      $fn->save_data($_POST, $_FILES, $result['id'] ?? null, [
        'table' => $table,
        'fields_options' => $fields_options,
        'status_flags' => array_keys($photoConfig['status']),
        'redirect_page' => "index.php?page=photo&act=photo_static&type=$type"
      ]);
    }
    break;

  case 'photo_man':
    $photoConfig = $config['photo']['photo_man'][$type] ?? null;
    if (!$photoConfig) $fn->transfer("Trang không tồn tại!", "index.php", false);

    $name_page = $photoConfig['title_main_photo'] ?? ucfirst($type);
    $records_per_page = 10;
    $current_page = max(1, (int)($_GET['p'] ?? 1));
    $keyword = $_GET['keyword'] ?? '';
    $total_rows = $fn->count_data(['table' => $table, 'type'  => $type, 'keyword' => $keyword]);
    $total_pages = ceil($total_rows / $records_per_page);
    $paging = $fn->renderPagination($current_page, $total_pages);
    $photo_list = $fn->show_data(['table' => $table, 'type' => $type, 'records_per_page' => $records_per_page, 'current_page' => $current_page, 'keyword' => $keyword]);

    $linkForm   = "index.php?page=photo&act=photo_form&type=$type";
    $linkEdit   = "$linkForm&id=";
    $linkDelete = "index.php?page=photo&act=delete&type=$type&id=";
    $linkMulti  = "index.php?page=photo&act=delete_multiple&type=$type";

    $breadcrumb = [['label' => $name_page]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "photo/man/photo_man.php";
    break;

  case 'photo_form':
    $photoConfig = $config['photo']['photo_man'][$type] ?? null;
    if (!$photoConfig) $fn->transfer("Trang không tồn tại!", "index.php", false);
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
    $result = ($id !== null) ? $db->rawQueryOne("SELECT * FROM $table WHERE type = ? AND id = ?", [$type, $id]) : null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
      $fn->save_data($_POST, $_FILES, $id, [
        'table'           => $table,
        'fields_multi'    => ['name', 'desc', 'content'],
        'fields_common'   => ['numb', 'type', 'link'],
        'fields_options'  => ['width', 'height', 'zc'],
        'status_flags'    => array_keys($photoConfig['status_photo']),
        'redirect_page'   => $linkMan,
        'convert_webp'    => true
      ]);
    }
    $breadcrumb = [['label' => ($id ? "Cập nhật " : "Thêm mới ") . $photoConfig['title_main_photo']]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "photo/man/photo_form.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại!", "index.php", false);
    break;
}
