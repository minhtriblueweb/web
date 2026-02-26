<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_photo';
$result = $d->rawQueryOne("SELECT * FROM `$table` WHERE type = ? LIMIT 0,1", [$type]) ?? '';
// if (!$result) $func->transfer(dulieukhongcothuc, $linkMan, false);
$linkMan = "index.php?com=photo&act=photo_man&type=" . $type;
switch ($act) {
  case 'delete':
    delete();
    break;

  case 'photo_static':
    savePhotoStatic();
    $template = "photo/static/photo_static";
    break;

  case 'photo_man':
    viewPhotos();
    $template = "photo/man/photo_man";
    break;

  case 'photo_form':
    savePhoto();
    $template = "photo/man/photo_form";
    break;

  default:
    $func->transfer(trangkhongtontai, "index.php", false);
    break;
}
function delete()
{
  global $func, $table, $type, $linkMan;
  if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $func->delete_data([
      'id' => (int)$_GET['id'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } elseif (!empty($_GET['listid'])) {
    $func->deleteMultiple_data([
      'listid' => $_GET['listid'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } else {
    $func->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}

function savePhotoStatic()
{
  global $func, $table, $type, $config, $result, $options;
  if (!$config['photo']['photo_static'][$type]) $func->transfer(trangkhongtontai, "index.php", false);
  $options = json_decode($result['options'] ?? '', true);
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $func->save_data($_POST['data'], $_FILES, $result['id'] ?? null, [
      'table' => $table,
      'type' => $type,
      'redirect' => "index.php?com=photo&act=photo_static&type=$type"
    ]);
  }
}
function viewPhotos()
{
  global $func, $table, $curPage, $perPage, $type, $paging, $show_data, $config;
  if (!$config['photo']['photo_man'][$type]) $func->transfer(trangkhongtontai, "index.php", false);
  $curPage = max(1, (int)($_GET['p'] ?? 1));
  $perPage = 10;
  $options = [
    'table'       => $table,
    'type'        => $type,
    'keyword'     => $_GET['keyword'] ?? '',
    'pagination'  => [$perPage, $curPage]
  ];
  $total = $func->count_data($options);
  $show_data = $func->show_data($options);
  $paging = $func->pagination($total, $perPage, $curPage);
}
function savePhoto()
{
  global $d, $func, $table, $linkMan, $type, $config, $id, $result;
  if (!$config['photo']['photo_man'][$type]) $func->transfer(trangkhongtontai, "index.php", false);
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  $result = ($id !== null) ? $d->rawQueryOne("SELECT * FROM $table WHERE type = ? AND id = ?", [$type, $id]) : null;
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
    $func->save_data($_POST['data'], $_FILES, $id, [
      'table'        => $table,
      'type'         => $type,
      'convert_webp' => $config['photo']['photo_man'][$type]['convert_webp'],
      'redirect'     => $linkMan
    ]);
  }
}
