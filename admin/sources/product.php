<?php
if (!defined('SOURCES')) die("Error");
if (!isset($config['product'][$type])) $fn->transfer(trangkhongtontai, "index.php", false);
$curPage = max(1, (int)($_GET['p'] ?? 1));
$perPage = 10;
$keyword = $_GET['keyword'] ?? '';
$id_list = $_GET['id_list'] ?? '';
$id_cat  = $_GET['id_cat'] ?? '';
$id_item  = $_GET['id_item'] ?? '';
$id_sub  = $_GET['id_sub'] ?? '';
$id_brand  = $_GET['id_brand'] ?? '';
/* Cấu hình đường dẫn trả về */
$strUrl = "";
$arrUrl = array('id_list', 'id_cat', 'id_item', 'id_sub', 'id_vari', 'id_brand');

if (!empty($_POST['data']) && is_array($_POST['data'])) {
  foreach ($arrUrl as $key) {
    if (!empty($_POST['data'][$key]) && (int)$_POST['data'][$key] > 0) {
      $strUrl .= '&' . $key . '=' . (int)$_POST['data'][$key];
    }
  }
} else {
  foreach ($arrUrl as $k => $v) {
    if (isset($_REQUEST[$arrUrl[$k]])) $strUrl .= "&" . $arrUrl[$k] . "=" . htmlspecialchars($_REQUEST[$arrUrl[$k]]);
  }

  if (!empty($_REQUEST['comment_status'])) $strUrl .= "&comment_status=" . htmlspecialchars($_REQUEST['comment_status']);
  if (isset($_REQUEST['keyword'])) $strUrl .= "&keyword=" . htmlspecialchars($_REQUEST['keyword']);
}
switch ($act) {
  case 'man':
    viewProduct('man');
    $template = "product/man/product_man";
    break;

  case 'man_list':
    viewProduct('list');
    $template = "product/list/product_man_list";
    break;

  case 'man_cat':
    viewProduct('cat');
    $template = "product/cat/product_man_cat";
    break;

  case 'man_item':
    viewProduct('item');
    $template = "product/item/product_man_item";
    break;

  case 'man_sub':
    viewProduct('sub');
    $template = "product/sub/product_man_sub";
    break;

  case 'man_brand':
    viewProduct('brand');
    $template = "product/brand/product_man_brand";
    break;

  case 'form':
    saveProduct('man');
    $template = "product/man/product_form";
    break;

  case 'form_list':
    saveProduct('list');
    $template = "product/list/product_form_list";
    break;

  case 'form_cat':
    saveProduct('cat');
    $template = "product/cat/product_form_cat";
    break;

  case 'form_item':
    saveProduct('item');
    $template = "product/item/product_form_item";
    break;

  case 'form_sub':
    saveProduct('sub');
    $template = "product/sub/product_form_sub";
    break;

  case 'form_brand':
    saveProduct('brand');
    $template = "product/brand/product_form_brand";
    break;

  case 'delete':
    deleteProduct('man');
    break;

  case 'delete_list':
    deleteProduct('list');
    break;

  case 'delete_cat':
    deleteProduct('cat');
    break;

  case 'delete_item':
    deleteProduct('item');
    break;

  case 'delete_sub':
    deleteProduct('sub');
    break;

  case 'delete_brand':
    deleteProduct('brand');
    break;

  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
function deleteProduct(string $level)
{
  global $fn, $type, $config;
  $isMan = ($level === 'man');
  $table = $isMan ? 'tbl_product' : "tbl_product_$level";
  $act   = $isMan ? 'man' : "man_$level";
  $redirect = "index.php?page=product&act=$act&type=$type";
  $productConfig = $config['product'][$type] ?? [];
  $delete_seo = $productConfig["seo_$level"] ?? $productConfig['seo'] ?? false;
  $delete_gallery = $productConfig["gallery_$level"] ?? $productConfig['gallery'] ?? false;
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  if ($id) {
    $fn->delete_data([
      'id'             => $id,
      'table'          => $table,
      'type'           => $type,
      'redirect'       => $redirect,
      'delete_seo'     => $delete_seo,
      'delete_gallery' => $delete_gallery
    ]);
    return;
  }
  $listid = $_GET['listid'] ?? '';
  if ($listid) {
    $fn->deleteMultiple_data([
      'listid'         => $listid,
      'table'          => $table,
      'type'           => $type,
      'redirect'       => $redirect,
      'delete_seo'     => $delete_seo,
      'delete_gallery' => $delete_gallery
    ]);
  }
}
function saveProduct(string $level)
{
  global $id, $table, $db, $fn, $type, $config, $strUrl, $gallery, $result, $seo_data;

  $isMan = ($level === 'man');
  $table = $isMan ? 'tbl_product' : "tbl_product_$level";
  $act   = $isMan ? 'man' : "man_$level";

  $linkMan  = "index.php?page=product&act=$act&type=$type";
  $linkForm = "index.php?page=product&act=form" . ($isMan ? '' : "_$level") . "&type=$type";

  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  $productConfig = $config['product'][$type] ?? [];

  /* ================== SAVE ================== */
  if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && (isset($_POST['add']) || isset($_POST['edit']) || isset($_POST['save-here']))
  ) {
    $isSaveHere = isset($_POST['save-here']);

    $redirect = $linkMan . $strUrl;
    if ($isSaveHere && $id) {
      $redirect = $linkForm . $strUrl . '&id=' . $id;
    }

    $newId = $fn->save_data(
      $_POST['data'] ?? [],
      $_FILES,
      $id,
      [
        'table'          => $table,
        'type'           => $type,
        'act'            => $act,
        'redirect'       => $redirect,
        'convert_webp'   => $productConfig["convert_webp_$level"] ?? $productConfig['convert_webp'] ?? false,
        'enable_slug'    => $productConfig["slug_$level"] ?? $productConfig['slug'] ?? false,
        'enable_seo'     => $productConfig["seo_$level"] ?? $productConfig['seo'] ?? false,
        'enable_gallery' => $productConfig["gallery_$level"] ?? $productConfig['gallery'] ?? false,
        'skip_redirect'  => $isSaveHere && !$id,
      ]
    );

    if ($isSaveHere && !$id && $newId > 0) {
      $fn->transfer(capnhatdulieuthanhcong, $linkForm . $strUrl . '&id=' . $newId, true);
    }
  }

  /* ================== EDIT ================== */
  $result = $seo_data = [];
  if ($id) {
    $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
    if (!$result) {
      $fn->transfer(dulieukhongcothuc, $linkMan, false);
    }

    if (!empty($productConfig["seo_$level"] ?? $productConfig['seo'] ?? false)) {
      $seo_data = $db->rawQueryOne(
        "SELECT * FROM `tbl_seo` WHERE id_parent = ? AND type = ? AND act = ?",
        [$id, $type, $act]
      );
    }

    if (!empty($productConfig["gallery_$level"] ?? $productConfig['gallery'] ?? false)) {
      $gallery = $db->rawQuery(
        "SELECT * FROM `tbl_gallery` WHERE id_parent = ? AND type = ? ORDER BY numb, id DESC",
        [$id, $type]
      );
    }
  }
}
function viewProduct(string $level)
{
  global $fn, $table, $curPage, $perPage, $type;
  global $id_list, $id_cat, $id_item, $id_brand, $id_sub, $keyword;
  global $paging, $show_data;
  $isMan = ($level === 'man');
  $table = $isMan ? 'tbl_product' : "tbl_product_$level";
  $options = [
    'table'      => $table,
    'keyword'    => $keyword,
    'pagination' => [$perPage, $curPage]
  ];
  $filters = [
    'type'     => $type,
    'id_list'  => $id_list,
    'id_cat'   => $id_cat,
    'id_item'  => $id_item,
    'id_sub'   => $id_sub,
    'id_brand' => $id_brand,
  ];

  foreach ($filters as $field => $value) {
    if ($value !== '' && $value !== null) {
      $options[$field] = is_numeric($value) ? (int)$value : $value;
    }
  }
  $total     = $fn->count_data($options);
  $show_data = $fn->show_data($options);
  $paging    = $fn->pagination($total, $perPage, $curPage);
}
