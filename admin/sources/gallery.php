<?php
$act       = $_GET['act'] ?? 'man';
$id        = (int)($_GET['id'] ?? 0);
$id_child  = (int)($_GET['id_child'] ?? 0);
$type      = $_GET['type'] ?? '';
$keyword   = $_GET['keyword'] ?? '';
$table     = "tbl_gallery";
$parent    = $id ? $d->rawQueryOne("SELECT id, name{$lang}, slug{$lang} FROM tbl_product WHERE id = ? LIMIT 1", [$id]) : [];

$linkBase  = "index.php?com=gallery&type=$type&id=$id";
$linkMan   = "$linkBase&act=man";
$linkForm  = "$linkBase&act=form";
$linkEdit  = "$linkBase&act=form&id_child=";
$linkDelete = "$linkBase&act=delete&id_child=";
$linkMulti = "$linkBase&act=delete_multiple";

switch ($act) {
  case 'delete':
    delete();
    break;

  case 'delete_multiple':
    deleteMultiple();
    break;

  case 'man':
    view();
    $template = "gallery/gallery_man";
    break;

  case 'form':
    add();
    $template = "gallery/gallery_form";
    break;

  default:
    $func->transfer(trangkhongtontai, "index.php", false);
    break;
}
function save_gallery($data, $files, $id_parent)
{
  global $d, $func, $linkBase, $lang, $config, $type;
  $id_parent = (int)$id_parent;
  $table = 'tbl_gallery';
  $result = false;
  $redirect_com = "$linkBase&act=man&id=$id_parent";
  $parent = $d->rawQueryOne("SELECT name$lang FROM tbl_product WHERE id = ? LIMIT 1", [$id_parent]);
  $parent_name = $parent["name$lang"] ?? 'gallery';
  if (!empty($data['id-filer'])) {
    foreach ($data['id-filer'] as $i => $gid) {
      $gid = (int)$gid;
      $numb = (int)($data['numb-filer'][$i] ?? 0);
      $name = trim($data['name-filer'][$i] ?? '');
      $d->execute("UPDATE $table SET numb = ?, name = ? WHERE id = ?", [$numb, $name, $gid]);
    }
  }
  $total = count($files['files']['name'] ?? []);
  for ($i = 0; $i < $total; $i++) {
    if (!empty($files['files']['name'][$i]) && $files['files']['error'][$i] === 0) {
      $file = [
        'name' => $files['files']['name'][$i],
        'type' => $files['files']['type'][$i],
        'tmp_name' => $files['files']['tmp_name'][$i],
        'error' => $files['files']['error'][$i],
        'size' => $files['files']['size'][$i]
      ];
      $thumb_filename = $func->uploadImage([
        'file' => $file,
        'custom_name' => $parent_name,
        'old_file_path' => '',
        'convert_webp' => $config['product'][$type]['convert_webp']
      ]);
      if (!empty($thumb_filename)) {
        $numb = (int)($data['numb-filer'][$i] ?? 0);
        $name = trim($data['name-filer'][$i] ?? '');
        $fields = ['id_parent', 'type', 'file', 'numb', 'name', 'status'];
        $params = [$id_parent, $type, $thumb_filename, $numb, $name, !empty($data['hienthi_all']) ? 'hienthi' : ''];
        $result = $d->execute("INSERT INTO `$table` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', array_fill(0, count($fields), '?')) . ")", $params);
      }
    }
  }
  $func->transfer($result ? capnhathinhanhthanhcong : capnhathinhanhthatbai,  $redirect_com, $result);
  return $result;
}
function upload_gallery($data, $files, $id, $id_parent)
{
  global $d, $func, $linkBase, $lang, $config, $type;

  $redirect_com = "$linkBase&act=man&id=$id_parent";
  $id = (int)$id;
  $id_parent = (int)$id_parent;
  $table = 'tbl_gallery';

  $parent = $d->rawQueryOne("SELECT name$lang FROM tbl_product WHERE id = ? LIMIT 1", [$id_parent]);
  $parent_name = $parent["name$lang"] ?? '';

  $fields = [
    'numb' => $data['numb'] ?? 0,
    'status' => !empty($data['hienthi']) ? 'hienthi' : ''
  ];

  $thumb_filename = '';
  if (!empty($files['file']['name']) && !empty($files['file']['tmp_name'])) {
    $old = $d->rawQueryOne("SELECT file FROM `$table` WHERE id = ?", [$id]);
    $old_file_path = !empty($old['file']) ? UPLOADS . $old['file'] : '';

    $thumb_filename = $func->uploadImage([
      'file' => $files['file'],
      'custom_name' => $parent_name,
      'old_file_path' => $old_file_path,
      'convert_webp' => $config['product'][$type]['convert_webp']
    ]);

    if (!$thumb_filename) {
      $func->transfer(capnhatdulieubiloi, $redirect_com, false);
    }

    $fields['file'] = $thumb_filename;
  }

  $sqlFields = [];
  $params = [];
  foreach ($fields as $k => $v) {
    $sqlFields[] = "`$k` = ?";
    $params[] = $v;
  }

  $params[] = $id;
  $success = $d->execute("UPDATE `$table` SET " . implode(', ', $sqlFields) . " WHERE id = ?", $params);

  $func->transfer($success ? capnhathinhanhthanhcong : capnhathinhanhthatbai, $redirect_com, $success);
}
function view()
{
  global $func, $id, $table, $type, $keyword, $paging, $show_data;
  $percom = 10;
  $curcom = max(1, (int)($_GET['p'] ?? 1));
  $options = [
    'table' => $table,
    'type' => $type,
    'id_parent' => $id,
    'keyword' => $keyword,
    'pagination' => [$percom, $curcom]
  ];
  $total = $func->count_data($options);
  $show_data = $func->show_data($options);
  $paging = $func->pagination($total, $percom, $curcom);
}
function add()
{
  global $d, $table, $id_child, $id, $result;
  $isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');
  $isAdd  = ($isPost && isset($_POST['add']) && $id);
  $isEdit = ($isPost && isset($_POST['edit']) && $id_child > 0);
  if ($isAdd) save_gallery($_POST, $_FILES, $id);
  $result = $d->rawQueryOne("SELECT * FROM $table WHERE id = ? LIMIT 1", [$id_child]);
  if ($isEdit && $result && isset($result['id_parent'])) {
    upload_gallery($_POST, $_FILES, $id_child, $result['id_parent']);
  }
}
function delete()
{
  global $func, $table, $type,  $id_child, $linkMan;
  if (is_numeric($_GET['id'] ?? null)) {
    $func->delete_data([
      'id' => $id_child,
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } else {
    $func->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
function deleteMultiple()
{
  global $func, $table, $type, $linkMan;
  if (!empty($_GET['listid'])) {
    $func->deleteMultiple_data([
      'listid' => $_GET['listid'] ?? '',
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } else {
    $func->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
