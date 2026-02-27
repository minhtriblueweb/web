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
switch ($act) {
  case 'delete':
    delete();
    break;

  case 'man':
    view();
    $template = "gallery/gallery_man";
    break;

  case 'add':
    add();
    $template = "gallery/gallery_form";
    break;

  case 'edit':
    edit();
    $template = "gallery/gallery_form";
    break;

  default:
    $func->transfer(trangkhongtontai, "index.php", false);
    break;
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

function delete()
{
  global $func, $table, $type,  $id_child, $linkMan;
  if (!empty($_GET['listid'])) {
    $func->deleteMultiple_data([
      'listid'   => $_GET['listid'],
      'table'    => $table,
      'type'     => $type,
      'redirect' => $linkMan
    ]);
  } elseif (!empty($_GET['id'])) {
    $func->delete_data([
      'id'       => $id_child,
      'table'    => $table,
      'type'     => $type,
      'redirect' => $linkMan
    ]);
  }
}

function edit()
{
  global $func, $d, $table, $id_child, $type, $linkMan, $result;
  $result = $d->rawQueryOne("SELECT * FROM $table WHERE id = ? LIMIT 1", [$id_child]);
  if (!$result) return;
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
  if (!isset($_POST['edit'])) return;
  $success = $func->save_gallery($_POST, $_FILES, $result['id_parent'], $type, $id_child);
  $func->transfer($success ? capnhathinhanhthanhcong : capnhathinhanhthatbai, $linkMan, $success);
}

function add()
{
  global $func, $id, $type, $linkMan;
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
  if (!isset($_POST['add']) || $id <= 0) return;
  $data  = $_POST['data'] ?? [];
  $files = $_FILES;
  $success = $func->save_gallery($data, $files, $id, $type);
  $func->transfer($success ? capnhathinhanhthanhcong : capnhathinhanhthatbai, $linkMan, $success);
}
