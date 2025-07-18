<?php
if (!defined('SOURCES')) die("Error");

if (!isset($config['product'][$type])) $fn->transfer("Trang không tồn tại!", "index.php", false);

$linkProduct = "index.php?page=product&type=$type";
$current_page = max(1, (int)($_GET['p'] ?? 1));
$rp = 10;
$keyword = $_GET['keyword'] ?? '';
$id_list = $_GET['id_list'] ?? '';
$id_cat  = $_GET['id_cat'] ?? '';
switch ($act) {
  case 'man': {
      $table = 'tbl_product';
      $linkMan   = "$linkProduct&act=man";
      $linkForm  = "$linkProduct&act=form";
      $linkEdit  = "$linkForm&id=";
      $linkDelete = "$linkProduct&act=delete&id=";
      $linkMulti  = "$linkProduct&act=delete_multiple";
      $linkGalleryMan  = "index.php?page=gallery&act=man&id=";
      $linkGalleryForm  = "index.php?page=gallery&act=form&id=";
      $join = "
      LEFT JOIN tbl_product_cat c2 ON p.id_cat = c2.id
      LEFT JOIN tbl_product_list c1 ON p.id_list = c1.id
    ";
      $select = "p.*, c1.namevi AS name_list, c2.namevi AS name_cat";
      $where = array_filter([
        'id_list' => $id_list,
        'id_cat'  => $id_cat
      ]);

      $options = [
        'table'   => $table,
        'type'    => $type,
        'alias'   => 'p',
        'join'    => $join,
        'id_list' => $id_list,
        'id_cat'  => $id_cat,
        'select' => $select,
        'keyword' => $keyword,
        'records_per_page' => $rp
      ];

      $total_records = $fn->count_data($options);
      $show_data     = $fn->show_data($options);
      $total_pages   = ceil($total_records / $rp);
      $paging        = $fn->renderPagination($current_page, $total_pages);
      $breadcrumb = [['label' => $config['product'][$type]['title_main']]];
      include TEMPLATE . LAYOUT . 'breadcrumb.php';
      include TEMPLATE . "product/man/product_man.php";
      break;
    }

  case 'man_list': {
      $table = 'tbl_product_list';
      $linkMan = "$linkProduct&act=man_list";
      $linkForm  = "$linkProduct&act=form_list";
      $linkEdit  = "$linkForm&id=";
      $linkDelete = "$linkProduct&act=delete_list&id=";
      $linkMulti  = "$linkProduct&act=delete_multiple_list";
      $options = [
        'table' => $table,
        'keyword' => $keyword,
        'records_per_page' => $rp,
        'current_page' => $current_page
      ];

      $total_records = $fn->count_data($options);
      $show_data     = $fn->show_data($options);
      $total_pages   = ceil($total_records / $rp);
      $paging        = $fn->renderPagination($current_page, $total_pages);
      $breadcrumb = [['label' => $config['product'][$type]['title_main_list']]];

      include TEMPLATE . LAYOUT . 'breadcrumb.php';
      include TEMPLATE . "product/list/product_man_list.php";
      break;
    }

  case 'man_cat': {
      $level = 'cat';
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
        'records_per_page' => $rp
      ];

      $total_records = $fn->count_data($options);
      $show_data     = $fn->show_data($options);
      $total_pages   = ceil($total_records / $rp);
      $paging        = $fn->renderPagination($current_page, $total_pages);

      $title = $config['product'][$type]['title_main_cat'];
      $breadcrumb = [['label' => $title]];

      include TEMPLATE . LAYOUT . 'breadcrumb.php';
      include TEMPLATE . "product/cat/product_man_cat.php";
      break;
    }
  case 'form': {
      $level = 'product';
      $table = 'tbl_product';
      $actBack = 'man';
      $linkMan = "$linkProduct&act=$actBack";
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
      $status_flags = array_keys($config['product'][$type]['check'] ?? []);
      $convert_webp = true;

      $fields_multi  = ['slug', 'name', 'desc', 'content'];
      $fields_common = ['numb', 'type', 'id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code'];

      if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
        $fn->save_data($_POST, $_FILES, $id, [
          'table'          => $table,
          'type'           => $type,
          'act'            => $actBack,
          'fields_multi'   => $fields_multi,
          'fields_common'  => $fields_common,
          'status_flags'   => $status_flags,
          'redirect_page'  => $linkMan,
          'convert_webp'   => $convert_webp,
          'enable_slug'    => true,
          'enable_seo'     => true,
          'enable_gallery' => true
        ]);
        break;
      }

      $result = [];
      if (!empty($id)) {
        $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
        if (!$result) $fn->transfer("Dữ liệu không tồn tại", $linkMan, false);
        $seo_data = $seo->get_seo($id, $type);
        $gallery = $db->rawQuery("SELECT * FROM tbl_gallery WHERE id_parent = ? AND type = ? ORDER BY numb, id DESC", [$id, $type]);
      }
      $breadcrumb = [['label' => ($id > 0 ? 'Cập nhật ' : 'Thêm mới ') . ($config['product'][$type]['title_main'] ?? '')]];
      include TEMPLATE . LAYOUT . 'breadcrumb.php';
      include TEMPLATE . "product/man/product_form.php";
      break;
    }
  case 'form_list': {
      $table = 'tbl_product_list';
      $actBack = 'man_list';
      $linkMan = "$linkProduct&act=man_list";
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
      $status_flags = array_keys($config['product'][$type]['check_list'] ?? []);
      $convert_webp = false;

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
          'convert_webp'  => $convert_webp,
          'enable_slug'   => true,
          'enable_seo'    => true
        ]);
        break;
      }

      $result = [];
      if (!empty($id)) {
        $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
        if (!$result) $fn->transfer("Dữ liệu không tồn tại", $linkMan, false);
        $seo_data = $seo->get_seo($id, $type);
      }
      $breadcrumb = [['label' => ($id > 0 ? 'Cập nhật ' : 'Thêm mới ') . ($config['product'][$type]['title_main_list'] ?? '')]];
      include TEMPLATE . LAYOUT . 'breadcrumb.php';
      include TEMPLATE . "product/list/product_form_list.php";
      break;
    }
  case 'form_cat': {
      $table = 'tbl_product_cat';
      $actBack = 'man_cat';
      $linkMan = "$linkProduct&act=$actBack";
      $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
      $status_flags = array_keys($config['product'][$type]['check_cat'] ?? []);
      $convert_webp = true;

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
          'convert_webp'  => $convert_webp,
          'enable_slug'   => true,
          'enable_seo'    => true
        ]);
        break;
      }

      $result = [];
      if (!empty($id)) {
        $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
        if (!$result) $fn->transfer("Dữ liệu không tồn tại", $linkMan, false);
        $seo_data = $seo->get_seo($id, $type);
      }
      $breadcrumb = [['label' => ($id > 0 ? 'Cập nhật ' : 'Thêm mới ') . ($config['product'][$type]['title_main_cat'] ?? '')]];
      include TEMPLATE . LAYOUT . 'breadcrumb.php';
      include TEMPLATE . "product/cat/product_form_cat.php";
      break;
    }
  case 'delete': {
      $fn->delete_data([
        'id'             => (int)$_GET['id'],
        'table'          => 'tbl_product',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man",
        'delete_seo'     => true,
        'delete_gallery' => true
      ]);
      break;
    }
  case 'delete_list': {
      $fn->delete_data([
        'id'             => (int)$_GET['id'],
        'table'          => 'tbl_product_list',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man_list",
        'delete_seo'     => true
      ]);
      break;
    }
  case 'delete_cat': {
      $fn->delete_data([
        'id'             => (int)$_GET['id'],
        'table'          => 'tbl_product_cat',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man_cat",
        'delete_seo'     => true
      ]);
      break;
    }
  case 'delete_multiple': {
      $fn->deleteMultiple_data([
        'listid'         => $_GET['listid'],
        'table'          => 'tbl_product',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man",
        'delete_seo'     => true,
        'delete_gallery' => true
      ]);
      break;
    }
  case 'delete_multiple_list': {
      $fn->deleteMultiple_data([
        'listid'         => $_GET['listid'],
        'table'          => 'tbl_product_list',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man_list",
        'delete_seo'     => true
      ]);
      break;
    }
  case 'delete_multiple_cat': {
      $fn->deleteMultiple_data([
        'listid'         => $_GET['listid'],
        'table'          => 'tbl_product_cat',
        'type'           => $type,
        'redirect_page'  => "$linkProduct&act=man_cat",
        'delete_seo'     => true
      ]);
      break;
    }


  default:
    $fn->transfer("Trang không hợp lệ", "index.php", false);
    break;
}
