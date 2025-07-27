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
    $table = 'tbl_product';
    $linkMan   = "$linkProduct&act=man";
    $linkForm  = "$linkProduct&act=form";
    $linkEdit  = "$linkForm&id=";
    $linkDelete = "$linkProduct&act=delete&id=";
    $linkMulti  = "$linkProduct&act=delete_multiple";
    $linkGalleryMan  = "index.php?page=gallery&act=man&type=$type&id=";
    $linkGalleryForm  = "index.php?page=gallery&act=form&type=$type&id=";
    $join = "LEFT JOIN tbl_product_cat c2 ON p.id_cat = c2.id
      LEFT JOIN tbl_product_list c1 ON p.id_list = c1.id";
    $select = "p.*, c1.name{$lang} AS name_list, c2.name{$lang} AS name_cat";

    $options = [
      'table'       => $table,
      'type'        => $type,
      'alias'       => 'p',
      'join'        => $join,
      'id_list'     => $id_list,
      'id_cat'      => $id_cat,
      'select'      => $select,
      'keyword'     => $keyword,
      'pagination'  => [$perPage, $curPage]
    ];

    $total = $fn->count_data($options);
    $show_data = $fn->show_data($options);
    $paging = $fn->pagination($total, $perPage, $curPage);
    $template = "product/man/product_man";
    break;

  case 'man_list':
    $table = 'tbl_product_list';
    $linkMan = "$linkProduct&act=man_list";
    $linkForm  = "$linkProduct&act=form_list";
    $linkEdit  = "$linkForm&id=";
    $linkDelete = "$linkProduct&act=delete_list&id=";
    $linkMulti  = "$linkProduct&act=delete_multiple_list";
    $options = [
      'table' => $table,
      'keyword' => $keyword,
      'pagination'  => [$perPage, $curPage]
    ];

    $total = $fn->count_data($options);
    $show_data = $fn->show_data($options);
    $paging = $fn->pagination($total, $perPage, $curPage);
    $template = "product/list/product_man_list";
    break;


  case 'man_cat':
    $table = 'tbl_product_cat';
    $linkMan = "$linkProduct&act=man_cat";
    $linkForm  = "$linkProduct&act=form_cat";
    $linkEdit  = "$linkForm&id=";
    $linkDelete = "$linkProduct&act=delete_cat&id=";
    $linkMulti  = "$linkProduct&act=delete_multiple_cat";
    $options = [
      'table' => $table,
      'id_list' => $id_list,
      'keyword' => $keyword,
      'pagination'  => [$perPage, $curPage]
    ];

    $total = $fn->count_data($options);
    $show_data = $fn->show_data($options);
    $paging = $fn->pagination($total, $perPage, $curPage);
    $template = "product/cat/product_man_cat";

    break;

  case 'form':
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
      break;
    }

    $result = $seo_data = [];
    if (!empty($id)) {
      $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id,]);
      if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
      $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ?", [$id, $type, $actBack]);
      $gallery = $db->rawQuery("SELECT * FROM tbl_gallery WHERE id_parent = ? AND type = ? ORDER BY numb, id DESC", [$id, $type]);
    }
    $template = "product/man/product_form";

    break;

  case 'form_list': {
      $table = 'tbl_product_list';
      $actBack = 'man_list';
      $linkMan = "$linkProduct&act=man_list";
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
        break;
      }

      $result = $seo_data = [];
      if (!empty($id)) {
        $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
        if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
        $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ?", [$id, $type, $actBack]);
      }
      $template = "product/list/product_form_list";
      break;
    }
  case 'form_cat': {
      $table = 'tbl_product_cat';
      $actBack = 'man_cat';
      $linkMan = "$linkProduct&act=$actBack";
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
      $status_flags = $config['product'][$type]['check_cat'] ?? [];
      $fields_multi  = ['slug', 'name', 'desc', 'content'];
      $fields_common = ['numb', 'type', 'id_list'];

      if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
        $fn->save_data($_POST, $_FILES, $id, [
          'table'         => $table,
          'type'          => $type,
          'act'           => $actBack,
          'fields_multi'  => $fields_multi,
          'fields_common' => $fields_common,
          'status_flags'  => $status_flags,
          'redirect_page' => $linkMan,
          'convert_webp'   => $config['product'][$type]['convert_webp_cat'],
          'enable_slug'   => $config['product'][$type]['slug_cat'],
          'enable_seo'    => $config['product'][$type]['seo_cat'],
          'enable_gallery' => $config['product'][$type]['gallery_cat']
        ]);
        break;
      }

      $result = $seo_data = [];
      if (!empty($id)) {
        $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
        if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
        $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ?", [$id, $type, $actBack]);
      }
      $template = "product/cat/product_form_cat";
      break;
    }
  case 'delete': {
      $fn->delete_data([
        'id'             => (int)$_GET['id'],
        'table'          => 'tbl_product',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man",
        'delete_seo'     => $config['product'][$type]['seo'],
        'delete_gallery' => $config['product'][$type]['gallery']
      ]);
      break;
    }
  case 'delete_list': {
      $fn->delete_data([
        'id'             => (int)$_GET['id'],
        'table'          => 'tbl_product_list',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man_list",
        'delete_seo'     => $config['product'][$type]['seo_list'],
        'delete_gallery' => $config['product'][$type]['gallery_list']
      ]);
      break;
    }
  case 'delete_cat': {
      $fn->delete_data([
        'id'             => (int)$_GET['id'],
        'table'          => 'tbl_product_cat',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man_cat",
        'delete_seo'     => $config['product'][$type]['seo_cat'],
        'delete_gallery' => $config['product'][$type]['gallery_cat']
      ]);
      break;
    }
  case 'delete_multiple': {
      $fn->deleteMultiple_data([
        'listid'         => $_GET['listid'],
        'table'          => 'tbl_product',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man",
        'delete_seo'     => $config['product'][$type]['seo'],
        'delete_gallery' => $config['product'][$type]['gallery']
      ]);
      break;
    }
  case 'delete_multiple_list': {
      $fn->deleteMultiple_data([
        'listid'         => $_GET['listid'],
        'table'          => 'tbl_product_list',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man_list",
        'delete_seo'     => $config['product'][$type]['seo_list'],
        'delete_gallery' => $config['product'][$type]['gallery_list']
      ]);
      break;
    }
  case 'delete_multiple_cat': {
      $fn->deleteMultiple_data([
        'listid'         => $_GET['listid'],
        'table'          => 'tbl_product_cat',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man_cat",
        'delete_seo'     => $config['product'][$type]['seo_cat'],
        'delete_gallery' => $config['product'][$type]['gallery_cat']
      ]);
      break;
    }
  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
