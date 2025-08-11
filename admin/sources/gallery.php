<?php
$act       = $_GET['act'] ?? 'man';
$id        = (int)($_GET['id'] ?? 0);
$id_child  = (int)($_GET['id_child'] ?? 0);
$type      = $_GET['type'] ?? '';
$keyword   = $_GET['keyword'] ?? '';
$table     = "tbl_gallery";
$parent    = $id ? $db->rawQueryOne("SELECT id, name{$lang}, slug{$lang} FROM tbl_product WHERE id = ? LIMIT 1", [$id]) : [];

$linkBase  = "index.php?page=gallery&type=$type&id=$id";
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
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
function save_gallery($data, $files, $id_parent)
{
  global $db, $fn, $linkBase, $lang, $config, $type;
  $id_parent = (int)$id_parent;
  $table = 'tbl_gallery';
  $result = false;
  $redirect_page = "$linkBase&act=man&id=$id_parent";
  $parent = $db->rawQueryOne("SELECT name$lang FROM tbl_product WHERE id = ? LIMIT 1", [$id_parent]);
  $parent_name = $parent["name$lang"] ?? 'gallery';
  if (!empty($data['id-filer'])) {
    foreach ($data['id-filer'] as $i => $gid) {
      $gid = (int)$gid;
      $numb = (int)($data['numb-filer'][$i] ?? 0);
      $name = trim($data['name-filer'][$i] ?? '');
      $db->execute("UPDATE $table SET numb = ?, name = ? WHERE id = ?", [$numb, $name, $gid]);
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
      $thumb_filename = $fn->uploadImage([
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
        $result = $db->execute("INSERT INTO `$table` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', array_fill(0, count($fields), '?')) . ")", $params);
      }
    }
  }
  $fn->transfer($result ? capnhathinhanhthanhcong : capnhathinhanhthatbai,  $redirect_page, $result);
  return $result;
}
function upload_gallery($data, $files, $id, $id_parent)
{
  global $db, $fn, $linkBase, $lang, $config, $type;

  $redirect_page = "$linkBase&act=man&id=$id_parent";
  $id = (int)$id;
  $id_parent = (int)$id_parent;
  $table = 'tbl_gallery';

  $parent = $db->rawQueryOne("SELECT name$lang FROM tbl_product WHERE id = ? LIMIT 1", [$id_parent]);
  $parent_name = $parent["name$lang"] ?? '';

  $fields = [
    'numb' => $data['numb'] ?? 0,
    'status' => !empty($data['hienthi']) ? 'hienthi' : ''
  ];

  $thumb_filename = '';
  if (!empty($files['file']['name']) && !empty($files['file']['tmp_name'])) {
    $old = $db->rawQueryOne("SELECT file FROM `$table` WHERE id = ?", [$id]);
    $old_file_path = !empty($old['file']) ? UPLOADS . $old['file'] : '';

    $thumb_filename = $fn->uploadImage([
      'file' => $files['file'],
      'custom_name' => $parent_name,
      'old_file_path' => $old_file_path,
      'convert_webp' => $config['product'][$type]['convert_webp']
    ]);

    if (!$thumb_filename) {
      $fn->transfer(capnhatdulieubiloi, $redirect_page, false);
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
  $success = $db->execute("UPDATE `$table` SET " . implode(', ', $sqlFields) . " WHERE id = ?", $params);

  $fn->transfer($success ? capnhathinhanhthanhcong : capnhathinhanhthatbai, $redirect_page, $success);
}
function view()
{
  global $fn, $id, $table, $type, $keyword, $paging, $show_data;
  $perPage = 10;
  $curPage = max(1, (int)($_GET['p'] ?? 1));
  $options = [
    'table' => $table,
    'type' => $type,
    'id_parent' => $id,
    'keyword' => $keyword,
    'pagination' => [$perPage, $curPage]
  ];
  $total = $fn->count_data($options);
  $show_data = $fn->show_data($options);
  $paging = $fn->pagination($total, $perPage, $curPage);
}
function add()
{
  global $db, $table, $id_child, $id, $result;
  $isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');
  $isAdd  = ($isPost && isset($_POST['add']) && $id);
  $isEdit = ($isPost && isset($_POST['edit']) && $id_child > 0);
  if ($isAdd) save_gallery($_POST, $_FILES, $id);
  $result = $db->rawQueryOne("SELECT * FROM $table WHERE id = ? LIMIT 1", [$id_child]);
  if ($isEdit && $result && isset($result['id_parent'])) {
    upload_gallery($_POST, $_FILES, $id_child, $result['id_parent']);
  }
}
function delete()
{
  global $fn, $table, $type,  $id_child, $linkMan;
  if (is_numeric($_GET['id'] ?? null)) {
    $fn->delete_data([
      'id' => $id_child,
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
  global $fn, $table, $type, $linkMan;
  if (!empty($_GET['listid'])) {
    $fn->deleteMultiple_data([
      'listid' => $_GET['listid'] ?? '',
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan
    ]);
  } else {
    $fn->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
