<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_photo';
$result = $db->rawQueryOne("SELECT * FROM $table WHERE type = ? LIMIT 1", [$type]) ?? '';
if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
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
    if (!$config['photo']['photo_static'][$type]) $fn->transfer(trangkhongtontai, "index.php", false);
    $fields_options = !empty($config['photo']['photo_static'][$type]['watermark-advanced'])
      ? ['position', 'per', 'small_per', 'max', 'min', 'opacity', 'offset_x', 'offset_y']
      : ['width', 'height', 'zc'];

    $options = json_decode($result['options'] ?? '', true);
    foreach ($fields_options as $opt) {
      $$opt = $options[$opt] ?? ($$opt ?? '');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
      $fn->save_data($_POST, $_FILES, $result['id'] ?? null, [
        'table' => $table,
        'fields_options' => $fields_options,
        'status_flags' => $config['photo']['photo_static'][$type]['status'],
        'redirect_page' => "index.php?page=photo&act=photo_static&type=$type"
      ]);
    }
    $template = "photo/static/photo_static";
    break;

  case 'photo_man':
    if (!$config['photo']['photo_man'][$type]) $fn->transfer(trangkhongtontai, "index.php", false);
    $curPage = max(1, (int)($_GET['p'] ?? 1));
    $perPage = 10;
    $keyword = $_GET['keyword'] ?? '';
    $options = [
      'table'       => $table,
      'type'        => $type,
      'keyword'     => $keyword,
      'pagination'  => [$perPage, $curPage]
    ];
    $total = $fn->count_data($options);
    $show_data = $fn->show_data($options);
    $paging = $fn->pagination($total, $perPage, $curPage);
    $linkForm   = "index.php?page=photo&act=photo_form&type=$type";
    $linkEdit   = "$linkForm&id=";
    $linkDelete = "index.php?page=photo&act=delete&type=$type&id=";
    $linkMulti  = "index.php?page=photo&act=delete_multiple&type=$type";
    $template = "photo/man/photo_man";
    break;

  case 'photo_form':
    if (!$config['photo']['photo_man'][$type]) $fn->transfer(trangkhongtontai, "index.php", false);
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
    $result = ($id !== null) ? $db->rawQueryOne("SELECT * FROM $table WHERE type = ? AND id = ?", [$type, $id]) : null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
      $fn->save_data($_POST, $_FILES, $id, [
        'table'           => $table,
        'fields_multi'    => ['name', 'desc', 'content'],
        'fields_common'   => ['numb', 'type', 'link'],
        'fields_options'  => ['width', 'height', 'zc'],
        'status_flags'    => $config['photo']['photo_man'][$type]['status_photo'],
        'redirect_page'   => $linkMan,
        'convert_webp'    => $config['photo']['photo_man'][$type]['convert_webp']
      ]);
    }
    $template = "photo/man/photo_form";
    break;

  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
