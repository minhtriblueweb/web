<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_photo';
$result = $db->rawQueryOne("SELECT * FROM $table WHERE type = ? LIMIT 1", [$type]) ?? '';
// if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
$linkMan = "index.php?page=photo&act=photo_man&type=$type";
switch ($act) {
  case 'delete':
    delete();
    break;
  case 'delete_multiple':
    deleteMultiple();
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
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
function delete()
{
  global $fn, $table, $type, $linkMan;
  if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $fn->delete_data([
      'id' => (int)$_GET['id'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } else {
    $fn->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
function deleteMultiple()
{
  global $fn, $table, $type, $config, $linkMan;
  if (!empty($_GET['listid'])) {
    $fn->deleteMultiple_data([
      'listid' => $_GET['listid'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } else {
    $fn->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
function savePhotoStatic()
{
  global $fn, $table, $type, $config, $result, $options;
  if (!$config['photo']['photo_static'][$type]) $fn->transfer(trangkhongtontai, "index.php", false);
  $options = json_decode($result['options'] ?? '', true);
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $fn->save_data($_POST['data'], $_FILES, $result['id'] ?? null, [
      'table' => $table,
      'type' => $type,
      'redirect' => "index.php?page=photo&act=photo_static&type=$type"
    ]);
  }
}
function viewPhotos()
{
  global $fn, $table, $curPage, $perPage, $type, $paging, $show_data, $config;
  if (!$config['photo']['photo_man'][$type]) $fn->transfer(trangkhongtontai, "index.php", false);
  $curPage = max(1, (int)($_GET['p'] ?? 1));
  $perPage = 10;
  $options = [
    'table'       => $table,
    'type'        => $type,
    'keyword'     => $_GET['keyword'] ?? '',
    'pagination'  => [$perPage, $curPage]
  ];
  $total = $fn->count_data($options);
  $show_data = $fn->show_data($options);
  $paging = $fn->pagination($total, $perPage, $curPage);
}
function savePhoto()
{
  global $db, $fn, $table, $linkMan, $type, $config, $id, $result;
  if (!$config['photo']['photo_man'][$type]) $fn->transfer(trangkhongtontai, "index.php", false);
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  $result = ($id !== null) ? $db->rawQueryOne("SELECT * FROM $table WHERE type = ? AND id = ?", [$type, $id]) : null;
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
    $fn->save_data($_POST['data'], $_FILES, $id, [
      'table'        => $table,
      'type'         => $type,
      'convert_webp' => $config['photo']['photo_man'][$type]['convert_webp'],
      'redirect'     => $linkMan
    ]);
  }
}
