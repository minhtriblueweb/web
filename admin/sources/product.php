<?php
if (!defined('SOURCES')) die("Error");

if (!isset($config['product'][$type])) $fn->transfer(trangkhongtontai, "index.php", false);
$linkProduct = "index.php?page=product&type=$type";
$curPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 10;
$keyword = $_GET['keyword'] ?? '';
$id_list = $_GET['id_list'] ?? '';
$id_cat  = $_GET['id_cat'] ?? '';
$id_item  = $_GET['id_item'] ?? '';
$id_brand  = $_GET['id_brand'] ?? '';
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

  case 'man_item':
    viewItems();
    $template = "product/item/product_man_item";
    break;

  case 'man_brand':
    viewBrands();
    $template = "product/brand/product_man_brand";
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

  case 'form_item':
    saveItem();
    $template = "product/item/product_form_item";
    break;

  case 'form_brand':
    saveBrand();
    $template = "product/brand/product_form_brand";
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

  case 'delete_item':
    deleteItem();
    break;

  case 'delete_brand':
    deleteBrand();
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

  case 'delete_multiple_item':
    deleteMultipleItem();
    break;

  case 'delete_multiple_brand':
    deleteMultipleBrand();
    break;

  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
function viewMans()
{
  global $fn, $table, $curPage, $perPage, $lang, $type, $id_list, $id_cat, $id_item, $id_brand, $keyword, $paging, $show_data;
  $table = 'tbl_product';
  $options = [
    'table'       => $table,
    'type'        => $type,
    'id_list'     => $id_list,
    'id_cat'      => $id_cat,
    'id_item'     => $id_item,
    'id_brand'    => $id_brand,
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
function viewItems()
{
  global $fn, $table, $id_list, $id_cat, $curPage, $perPage, $keyword, $paging, $show_data;
  $table = 'tbl_product_item';
  $options = [
    'table' => $table,
    'id_list' => $id_list,
    'id_cat' => $id_cat,
    'keyword' => $keyword,
    'pagination'  => [$perPage, $curPage]
  ];
  $total = $fn->count_data($options);
  $show_data = $fn->show_data($options);
  $paging = $fn->pagination($total, $perPage, $curPage);
}
function viewBrands()
{
  global $fn, $table, $curPage, $perPage, $keyword, $paging, $show_data;
  $table = 'tbl_product_brand';
  $options = [
    'table' => $table,
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
  $table   = 'tbl_product';
  $act     = 'man';
  $linkMan = "$linkProduct&act=$act";

  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

  if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && (isset($_POST['add']) || isset($_POST['edit']) || isset($_POST['save-here']))
  ) {

    $isSaveHere = isset($_POST['save-here']);
    $redirect   = $linkMan;

    if ($isSaveHere && !empty($id)) {
      $redirect = "index.php?page=product&type=$type&act=form&id=$id";
    }

    $newId = $fn->save_data($_POST['data'] ?? [], $_FILES, $id, [
      'table'          => $table,
      'type'           => $type,
      'act'            => $act,
      'redirect'       => $redirect,
      'convert_webp'   => $config['product'][$type]['convert_webp'],
      'enable_slug'    => $config['product'][$type]['slug'],
      'enable_seo'     => $config['product'][$type]['seo'],
      'enable_gallery' => $config['product'][$type]['gallery'],
      'skip_redirect'  => $isSaveHere && empty($id),
    ]);

    // THÊM MỚI + LƯU TẠI TRANG
    if ($isSaveHere && empty($id) && $newId > 0) {
      $fn->transfer(
        capnhatdulieuthanhcong,
        "index.php?page=product&type=$type&act=form&id=$newId",
        true
      );
    }
  }

  $result = $seo_data = [];
  $gallery = [];

  if (!empty($id)) {
    $result = $db->rawQueryOne(
      "SELECT * FROM `$table` WHERE id = ?",
      [$id]
    );

    if (!$result) {
      $fn->transfer(dulieukhongcothuc, $linkMan, false);
    }

    $seo_data = $db->rawQueryOne(
      "SELECT * FROM tbl_seo WHERE id_parent = ? AND type = ? AND act = ?",
      [$id, $type, $act]
    );

    $gallery = $db->rawQuery(
      "SELECT * FROM tbl_gallery WHERE id_parent = ? AND type = ? ORDER BY numb, id DESC",
      [$id, $type]
    );
  }
}

function saveList()
{
  global $db, $fn, $table, $linkProduct, $type, $config, $id, $result, $seo_data;
  $table = 'tbl_product_list';
  $act   = 'man_list';
  $linkMan = "$linkProduct&act=$act";
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && (isset($_POST['add']) || isset($_POST['edit']) || isset($_POST['save-here']))
  ) {
    $isSaveHere = isset($_POST['save-here']);
    $redirect = $linkMan;
    if ($isSaveHere && !empty($id)) {
      $redirect = "index.php?page=product&type=$type&act=form_list&id=$id";
    }
    $newId = $fn->save_data($_POST['data'] ?? [], $_FILES, $id, [
      'table'          => $table,
      'type'           => $type,
      'act'            => $act,
      'redirect'       => $redirect,
      'convert_webp'   => $config['product'][$type]['convert_webp_list'],
      'enable_slug'    => $config['product'][$type]['slug_list'],
      'enable_seo'     => $config['product'][$type]['seo_list'],
      'enable_gallery' => $config['product'][$type]['gallery_list'],
      'skip_redirect'  => $isSaveHere && empty($id),
    ]);
    if ($isSaveHere && empty($id) && $newId > 0) {
      $fn->transfer(
        capnhatdulieuthanhcong,
        "index.php?page=product&type=$type&act=form_list&id=$newId",
        true
      );
    }
  }
  $result = $seo_data = [];
  if (!empty($id)) {
    $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
    if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
    $seo_data = $db->rawQueryOne(
      "SELECT * FROM tbl_seo WHERE id_parent = ? AND type = ? AND act = ?",
      [$id, $type, $act]
    );
  }
}
function saveCat()
{
  global $db, $fn, $table, $linkProduct, $type, $config, $id, $result, $seo_data;
  $table = 'tbl_product_cat';
  $act   = 'man_cat';
  $linkMan = "$linkProduct&act=$act";
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    (isset($_POST['add']) || isset($_POST['edit']) || isset($_POST['save-here']))
  ) {
    $isSaveHere = isset($_POST['save-here']);
    $redirect = $linkMan;
    if ($isSaveHere && !empty($id)) {
      $redirect = "index.php?page=product&type=$type&act=form_cat&id=$id";
    }
    $newId = $fn->save_data($_POST['data'] ?? [], $_FILES, $id, [
      'table'          => $table,
      'type'           => $type,
      'act'            => $act,
      'redirect'       => $redirect,
      'convert_webp'   => $config['product'][$type]['convert_webp_cat'],
      'enable_slug'    => $config['product'][$type]['slug_cat'],
      'enable_seo'     => $config['product'][$type]['seo_cat'],
      'enable_gallery' => $config['product'][$type]['gallery_cat'],
      'skip_redirect'  => $isSaveHere && empty($id),
    ]);
    if ($isSaveHere && empty($id) && $newId > 0) {
      $fn->transfer(
        capnhatdulieuthanhcong,
        "index.php?page=product&type=$type&act=form_cat&id=$newId",
        true
      );
    }
  }
  $result = $seo_data = [];
  if (!empty($id)) {
    $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
    if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
    $seo_data = $db->rawQueryOne(
      "SELECT * FROM tbl_seo WHERE id_parent = ? AND type = ? AND act = ?",
      [$id, $type, $act]
    );
  }
}
function saveItem()
{
  global $db, $fn, $table, $linkProduct, $type, $config, $id, $result, $seo_data;
  $table = 'tbl_product_item';
  $act   = 'man_item';
  $linkMan = "$linkProduct&act=$act";
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    (isset($_POST['add']) || isset($_POST['edit']) || isset($_POST['save-here']))
  ) {
    $isSaveHere = isset($_POST['save-here']);
    $redirect = $linkMan;
    if ($isSaveHere && !empty($id)) {
      $redirect = "index.php?page=product&type=$type&act=form_item&id=$id";
    }
    $newId = $fn->save_data($_POST['data'] ?? [], $_FILES, $id, [
      'table'          => $table,
      'type'           => $type,
      'act'            => $act,
      'redirect'       => $redirect,
      'convert_webp'   => $config['product'][$type]['convert_webp_item'],
      'enable_slug'    => $config['product'][$type]['slug_item'],
      'enable_seo'     => $config['product'][$type]['seo_item'],
      'enable_gallery' => $config['product'][$type]['gallery_item'],
      'skip_redirect'  => $isSaveHere && empty($id),
    ]);
    if ($isSaveHere && empty($id) && $newId > 0) {
      $fn->transfer(
        capnhatdulieuthanhcong,
        "index.php?page=product&type=$type&act=form_item&id=$newId",
        true
      );
    }
  }
  $result = $seo_data = [];
  if (!empty($id)) {
    $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
    if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
    $seo_data = $db->rawQueryOne(
      "SELECT * FROM tbl_seo WHERE id_parent = ? AND type = ? AND act = ?",
      [$id, $type, $act]
    );
  }
}
function saveBrand()
{
  global $db, $fn, $table, $linkProduct, $type, $config, $id, $result, $seo_data;
  $table = 'tbl_product_brand';
  $act   = 'man_brand';
  $linkMan = "$linkProduct&act=$act";
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    (isset($_POST['add']) || isset($_POST['edit']) || isset($_POST['save-here']))
  ) {
    $isSaveHere = isset($_POST['save-here']);
    $redirect = $linkMan;
    if ($isSaveHere && !empty($id)) {
      $redirect = "index.php?page=product&type=$type&act=form_brand&id=$id";
    }
    $newId = $fn->save_data($_POST['data'] ?? [], $_FILES, $id, [
      'table'          => $table,
      'type'           => $type,
      'act'            => $act,
      'redirect'       => $redirect,
      'convert_webp'   => $config['product'][$type]['convert_webp_brand'],
      'enable_slug'    => $config['product'][$type]['slug_brand'],
      'enable_seo'     => $config['product'][$type]['seo_brand'],
      'enable_gallery' => $config['product'][$type]['gallery_brand'],
      'skip_redirect'  => $isSaveHere && empty($id),
    ]);
    if ($isSaveHere && empty($id) && $newId > 0) {
      $fn->transfer(
        capnhatdulieuthanhcong,
        "index.php?page=product&type=$type&act=form_brand&id=$newId",
        true
      );
    }
  }
  $result = $seo_data = [];
  if (!empty($id)) {
    $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
    if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);

    $seo_data = $db->rawQueryOne(
      "SELECT * FROM tbl_seo WHERE id_parent = ? AND type = ? AND act = ?",
      [$id, $type, $act]
    );
  }
}
function deleteMan()
{
  global $fn, $linkProduct, $type, $config;
  $fn->delete_data([
    'id'             => (int)$_GET['id'],
    'table'          => 'tbl_product',
    'type'           => $type,
    'redirect'       => "$linkProduct&act=man",
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
    'redirect'       => "$linkProduct&act=man_list",
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
    'redirect'       => "$linkProduct&act=man_cat",
    'delete_seo'     => $config['product'][$type]['seo_cat'],
    'delete_gallery' => $config['product'][$type]['gallery_cat']
  ]);
}

function deleteItem()
{
  global $fn, $linkProduct, $type, $config;
  $fn->delete_data([
    'id'             => (int)$_GET['id'],
    'table'          => 'tbl_product_item',
    'type'           => $type,
    'redirect'       => "$linkProduct&act=man_item",
    'delete_seo'     => $config['product'][$type]['seo_item'],
    'delete_gallery' => $config['product'][$type]['gallery_item']
  ]);
}

function deleteBrand()
{
  global $fn, $linkProduct, $type, $config;
  $fn->delete_data([
    'id'             => (int)$_GET['id'],
    'table'          => 'tbl_product_brand',
    'type'           => $type,
    'redirect'       => "$linkProduct&act=man_brand",
    'delete_seo'     => $config['product'][$type]['seo_brand'],
    'delete_gallery' => $config['product'][$type]['gallery_brand']
  ]);
}
function deleteMultipleMan()
{
  global $fn, $linkProduct, $type, $config;
  $fn->deleteMultiple_data([
    'listid'         => $_GET['listid'],
    'table'          => 'tbl_product',
    'type'           => $type,
    'redirect'       => "$linkProduct&act=man",
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
    'redirect'       => "$linkProduct&act=man_list",
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
    'redirect'       => "$linkProduct&act=man_cat",
    'delete_seo'     => $config['product'][$type]['seo_cat'],
    'delete_gallery' => $config['product'][$type]['gallery_cat']
  ]);
}

function deleteMultipleItem()
{
  global $fn, $linkProduct, $type, $config;
  $fn->deleteMultiple_data([
    'listid'         => $_GET['listid'],
    'table'          => 'tbl_product_item',
    'type'           => $type,
    'redirect'       => "$linkProduct&act=man_item",
    'delete_seo'     => $config['product'][$type]['seo_item'],
    'delete_gallery' => $config['product'][$type]['gallery_item']
  ]);
}

function deleteMultipleBrand()
{
  global $fn, $linkProduct, $type, $config;
  $fn->deleteMultiple_data([
    'listid'         => $_GET['listid'],
    'table'          => 'tbl_product_brand',
    'type'           => $type,
    'redirect'       => "$linkProduct&act=man_brand",
    'delete_seo'     => $config['product'][$type]['seo_brand'],
    'delete_gallery' => $config['product'][$type]['gallery_brand']
  ]);
}
