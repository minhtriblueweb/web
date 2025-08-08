<?php
if (!defined('SOURCES')) die("Error");

if (!isset($config['product'][$type])) $fn->transfer(trangkhongtontai, "index.php", false);
$linkProduct = "index.php?page=product&type=$type";
$curPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 10;
$keyword = $_GET['keyword'] ?? '';
$id_list = $_GET['id_list'] ?? '';
$id_cat  = $_GET['id_cat'] ?? '';
switch ($act) {
  case 'man':
    viewMans();
    $template = "product/man/product_man";
    break;

  case 'man_list':
    viewLists();
    $template = "product/list/product_man_list";
    break;

  case 'man_cat':
    viewCats();
    $template = "product/cat/product_man_cat";
    break;

  case 'form':
    saveMan();
    $template = "product/man/product_form";
    break;

  case 'form_list':
    saveList();
    $template = "product/list/product_form_list";
    break;

  case 'form_cat':
    saveCat();
    $template = "product/cat/product_form_cat";
    break;

  case 'delete':
    deleteMan();
    break;

  case 'delete_list':
    deleteList();
    break;

  case 'delete_cat':
    deleteCat();
    break;

  case 'delete_multiple':
    deleteMultipleMan();
    break;

  case 'delete_multiple_list':
    deleteMultipleList();
    break;

  case 'delete_multiple_cat':
    deleteMultipleCat();
    break;

  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
function viewMans()
{
  global $fn, $table, $curPage, $perPage, $lang, $type, $id_list, $id_cat, $keyword, $paging, $show_data;
  $table = 'tbl_product';
  $options = [
    'table'       => $table,
    'type'        => $type,
    'alias'       => 'p',
    'join'        => "LEFT JOIN tbl_product_cat c2 ON p.id_cat = c2.id
      LEFT JOIN tbl_product_list c1 ON p.id_list = c1.id",
    'id_list'     => $id_list,
    'id_cat'      => $id_cat,
    'select'      => "p.*, c1.name{$lang} AS name_list, c2.name{$lang} AS name_cat",
    'keyword'     => $keyword,
    'pagination'  => [$perPage, $curPage]
  ];

  $total = $fn->count_data($options);
  $show_data = $fn->show_data($options);
  $paging = $fn->pagination($total, $perPage, $curPage);
}
function viewLists()
{
  global $fn, $table, $curPage, $perPage, $keyword, $paging, $show_data;
  $table = 'tbl_product_list';
  $options = [
    'table' => $table,
    'keyword' => $keyword,
    'pagination'  => [$perPage, $curPage]
  ];

  $total = $fn->count_data($options);
  $show_data = $fn->show_data($options);
  $paging = $fn->pagination($total, $perPage, $curPage);
}
function viewCats()
{
  global $fn, $table, $curPage, $perPage, $id_list, $keyword, $paging, $show_data;
  $table = 'tbl_product_cat';
  $options = [
    'table' => $table,
    'id_list' => $id_list,
    'keyword' => $keyword,
    'pagination'  => [$perPage, $curPage]
  ];

  $total = $fn->count_data($options);
  $show_data = $fn->show_data($options);
  $paging = $fn->pagination($total, $perPage, $curPage);
}
function saveMan()
{
  global $db, $fn, $table, $linkProduct, $type, $config, $id, $result, $seo_data, $gallery;
  $table = 'tbl_product';
  $actBack = 'man';
  $linkMan = "$linkProduct&act=$actBack";
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  $fields_multi  = ['slug', 'name', 'desc', 'content'];
  $fields_common = ['numb', 'type', 'id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code'];

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
    $fn->save_data($_POST, $_FILES, $id, [
      'table'          => $table,
      'type'           => $type,
      'act'            => $actBack,
      'fields_multi'   => $fields_multi,
      'fields_common'  => $fields_common,
      'status_flags'   => $config['product'][$type]['check'],
      'redirect_page'  => $linkMan,
      'convert_webp'   => $config['product'][$type]['convert_webp'],
      'enable_slug'    => $config['product'][$type]['slug'],
      'enable_seo'     => $config['product'][$type]['seo'],
      'enable_gallery' => $config['product'][$type]['gallery']
    ]);
  }

  $result = $seo_data = [];
  if (!empty($id)) {
    $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id,]);
    if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
    $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ?", [$id, $type, $actBack]);
    $gallery = $db->rawQuery("SELECT * FROM tbl_gallery WHERE id_parent = ? AND type = ? ORDER BY numb, id DESC", [$id, $type]);
  }
}
function saveList()
{
  global $db, $fn, $table, $linkProduct, $type, $config, $id, $result, $seo_data;
  $table = 'tbl_product_list';
  $actBack = 'man_list';
  $linkMan = "$linkProduct&act=$actBack";
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  $status_flags = $config['product'][$type]['check_list'] ?? [];
  $fields_multi  = ['slug', 'name', 'desc', 'content'];
  $fields_common = ['numb', 'type'];

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
    $fn->save_data($_POST, $_FILES, $id, [
      'table'         => $table,
      'type'          => $type,
      'act'           => $actBack,
      'fields_multi'  => $fields_multi,
      'fields_common' => $fields_common,
      'status_flags'  => $status_flags,
      'redirect_page' => $linkMan,
      'convert_webp'   => $config['product'][$type]['convert_webp_list'],
      'enable_slug'    => $config['product'][$type]['slug_list'],
      'enable_seo'     => $config['product'][$type]['seo_list'],
      'enable_gallery' => $config['product'][$type]['gallery_list']
    ]);
  }

  $result = $seo_data = [];
  if (!empty($id)) {
    $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
    if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
    $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ?", [$id, $type, $actBack]);
  }
}
function saveCat()
{
  global $db, $fn, $table, $linkProduct, $type, $config, $id, $result, $seo_data;
  $table = 'tbl_product_cat';
  $actBack = 'man_cat';
  $linkMan = "$linkProduct&act=$actBack";
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  $status_flags = $config['product'][$type]['check_cat'] ?? [];
  $fields_multi  = ['slug', 'name', 'desc', 'content'];
  $fields_common = ['numb', 'type', 'id_list'];

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
    $fn->save_data($_POST, $_FILES, $id, [
      'table'          => $table,
      'type'           => $type,
      'act'            => $actBack,
      'fields_multi'   => $fields_multi,
      'fields_common'  => $fields_common,
      'status_flags'   => $status_flags,
      'redirect_page'  => $linkMan,
      'convert_webp'   => $config['product'][$type]['convert_webp_cat'],
      'enable_slug'    => $config['product'][$type]['slug_cat'],
      'enable_seo'     => $config['product'][$type]['seo_cat'],
      'enable_gallery' => $config['product'][$type]['gallery_cat']
    ]);
  }

  $result = $seo_data = [];
  if (!empty($id)) {
    $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
    if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
    $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ?", [$id, $type, $actBack]);
  }
}
function deleteMan()
{
  global $fn, $linkProduct, $type, $config;
  $fn->delete_data([
    'id'             => (int)$_GET['id'],
    'table'          => 'tbl_product',
    'type'           => $type,
    'redirect_page'  => "$linkProduct&act=man",
    'delete_seo'     => $config['product'][$type]['seo'],
    'delete_gallery' => $config['product'][$type]['gallery']
  ]);
}
function deleteList()
{
  global $fn, $linkProduct, $type, $config;
  $fn->delete_data([
    'id'             => (int)$_GET['id'],
    'table'          => 'tbl_product_list',
    'type'           => $type,
    'redirect_page'  => "$linkProduct&act=man_list",
    'delete_seo'     => $config['product'][$type]['seo_list'],
    'delete_gallery' => $config['product'][$type]['gallery_list']
  ]);
}
function deleteCat()
{
  global $fn, $linkProduct, $type, $config;
  $fn->delete_data([
    'id'             => (int)$_GET['id'],
    'table'          => 'tbl_product_cat',
    'type'           => $type,
    'redirect_page'  => "$linkProduct&act=man_cat",
    'delete_seo'     => $config['product'][$type]['seo_cat'],
    'delete_gallery' => $config['product'][$type]['gallery_cat']
  ]);
}
function deleteMultipleMan()
{
  global $fn, $linkProduct, $type, $config;
  $fn->deleteMultiple_data([
    'listid'         => $_GET['listid'],
    'table'          => 'tbl_product',
    'type'           => $type,
    'redirect_page'  => "$linkProduct&act=man",
    'delete_seo'     => $config['product'][$type]['seo'],
    'delete_gallery' => $config['product'][$type]['gallery']
  ]);
}
function deleteMultipleList()
{
  global $fn, $linkProduct, $type, $config;
  $fn->deleteMultiple_data([
    'listid'         => $_GET['listid'],
    'table'          => 'tbl_product_list',
    'type'           => $type,
    'redirect_page'  => "$linkProduct&act=man_list",
    'delete_seo'     => $config['product'][$type]['seo_list'],
    'delete_gallery' => $config['product'][$type]['gallery_list']
  ]);
}
function deleteMultipleCat()
{
  global $fn, $linkProduct, $type, $config;
  $fn->deleteMultiple_data([
    'listid'         => $_GET['listid'],
    'table'          => 'tbl_product_cat',
    'type'           => $type,
    'redirect_page'  => "$linkProduct&act=man_cat",
    'delete_seo'     => $config['product'][$type]['seo_cat'],
    'delete_gallery' => $config['product'][$type]['gallery_cat']
  ]);
}
