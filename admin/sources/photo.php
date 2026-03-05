<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_photo';
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
  $options = [
    'table' => $table,
    'type' => $type,
    'redirect' => $linkMan
  ];
  if ($id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
    $func->delete_data($options + ['id' => $id]);
    return;
  }
  if ($listid = ($_GET['listid'] ?? '')) {
    $func->deleteMultiple_data($options + ['listid' => $listid]);
    return;
  }
}

function savePhotoStatic()
{
  global $d, $func, $table, $type, $config, $item, $options, $act;
  if (!$config['photo']['photo_static'][$type]) $func->transfer(trangkhongtontai, "index.php", false);
  $item = $d->rawQueryOne("SELECT * FROM `$table` WHERE type = ? LIMIT 0,1", [$type]) ?? '';
  $options = [];
  if (is_array($item) && isset($item['options'])) {
    $options = json_decode($item['options'], true);
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $_POST['data']['act'] = $act;
    $_POST['data']['options']['width'] = $config['photo']['photo_static'][$type]['width'];
    $_POST['data']['options']['height'] = $config['photo']['photo_static'][$type]['height'];
    $_POST['data']['options']['zc'] = substr($config['photo']['photo_static'][$type]['thumb'], -1) ?? 1;
    $func->save_data($_POST['data'], $_FILES, $item['id'] ?? null, [
      'table' => $table,
      'type' => $type
    ]);
    $func->transfer(capnhatdulieuthanhcong, "index.php?com=photo&act=photo_static&type=$type");
  }
}
function viewPhotos()
{
  global $func, $table, $curPage, $perPage, $type, $paging, $item, $config;
  if (!$config['photo']['photo_man'][$type]) $func->transfer(trangkhongtontai, "index.php", false);
  $perPage = 10;
  $options = [
    'table'       => $table,
    'type'        => $type,
    'keyword'     => $_GET['keyword'] ?? '',
    'pagination'  => [$perPage, $curPage]
  ];
  $total = $func->count_data($options);
  $item = $func->show_data($options);
  $paging = $func->pagination($total, $perPage, $curPage);
}
function savePhoto()
{
  global $d, $func, $table, $linkMan, $type, $config, $id, $item;
  if (!$config['photo']['photo_man'][$type]) $func->transfer(trangkhongtontai, "index.php", false);
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  $item = ($id !== null) ? $d->rawQueryOne("SELECT * FROM `$table` WHERE type = ? AND id = ?", [$type, $id]) : null;
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
    $_POST['data']['act'] = "photo_multi";
    $_POST['data']['options']['width'] = $config['photo']['photo_man'][$type]['width_photo'];
    $_POST['data']['options']['height'] = $config['photo']['photo_man'][$type]['height_photo'];
    $_POST['data']['options']['zc'] = substr($config['photo']['photo_man'][$type]['thumb_photo'], -1) ?? 1;
    $func->save_data($_POST['data'], $_FILES, $id, [
      'table'        => $table,
      'type'         => $type,
      'convert_webp' => $config['photo']['photo_man'][$type]['convert_webp']
    ]);
    $func->transfer(capnhatdulieuthanhcong, $linkMan);
  }
}
