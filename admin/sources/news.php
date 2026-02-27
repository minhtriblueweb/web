<?php
if (!defined('SOURCES')) die("Error");
if (!isset($config['news'][$type])) $func->transfer(trangkhongtontai, "index.php", false);
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
    viewNews('man');
    $template = "news/man/news_man";
    break;

  case 'man_list':
    viewNews('list');
    $template = "news/list/man_list";
    break;

  case 'man_cat':
    viewNews('cat');
    $template = "news/cat/news_man_cat";
    break;

  case 'man_item':
    viewNews('item');
    $template = "news/item/news_man_item";
    break;

  case 'man_sub':
    viewNews('sub');
    $template = "news/sub/news_man_sub";
    break;

  case 'man_brand':
    viewNews('brand');
    $template = "news/brand/news_man_brand";
    break;

  case 'form':
    saveNews('man');
    $template = "news/man/news_form";
    break;

  case 'form_list':
    saveNews('list');
    $template = "news/list/form_list";
    break;

  case 'form_cat':
    saveNews('cat');
    $template = "news/cat/news_form_cat";
    break;

  case 'form_item':
    saveNews('item');
    $template = "news/item/news_form_item";
    break;

  case 'form_sub':
    saveNews('sub');
    $template = "news/sub/news_form_sub";
    break;

  case 'form_brand':
    saveNews('brand');
    $template = "news/brand/news_form_brand";
    break;

  case 'delete':
    deleteNews('man');
    break;

  case 'delete_list':
    deleteNews('list');
    break;

  case 'delete_cat':
    deleteNews('cat');
    break;

  case 'delete_item':
    deleteNews('item');
    break;

  case 'delete_sub':
    deleteNews('sub');
    break;

  case 'delete_brand':
    deleteNews('brand');
    break;

  case "copy":
    if ($act === 'copy' && !($config['news'][$type]['copy'] ?? false)) {
      $func->transfer(trangkhongtontai, "index.php", false);
      return false;
    }
    saveNews('man');
    $template = "news/man/news_form";
    break;

  default:
    $func->transfer(trangkhongtontai, "index.php", false);
    break;
}
function deleteNews(string $level)
{
  global $func, $type, $config;
  $isMan = ($level === 'man');
  $table = $isMan ? 'tbl_news' : "tbl_news_$level";
  $act   = $isMan ? 'man' : "man_$level";
  $redirect = "index.php?com=news&act=$act&type=$type";
  $newsConfig = $config['news'][$type] ?? [];
  $delete_seo = $newsConfig["seo_$level"] ?? $newsConfig['seo'] ?? false;
  $delete_gallery = $newsConfig["gallery_$level"] ?? $newsConfig['gallery'] ?? false;
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  if ($id) {
    $func->delete_data([
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
    $func->deleteMultiple_data([
      'listid'         => $listid,
      'table'          => $table,
      'type'           => $type,
      'redirect'       => $redirect,
      'delete_seo'     => $delete_seo,
      'delete_gallery' => $delete_gallery
    ]);
  }
}
function saveNews(string $level)
{
  global $id, $id_copy, $table, $d, $func, $type, $config, $strUrl, $gallery, $result, $seo_data;

  $isMan = ($level === 'man');
  $table = $isMan ? 'tbl_news' : "tbl_news_$level";
  $act   = $isMan ? 'man' : "man_$level";

  $linkMan  = "index.php?com=news&act=$act&type=$type";
  $linkForm = "index.php?com=news&act=form" . ($isMan ? '' : "_$level") . "&type=$type";

  $id_copy = filter_input(INPUT_GET, 'id_copy', FILTER_VALIDATE_INT);
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  $newsConfig = $config['news'][$type] ?? [];

  /* ================== SAVE ================== */
  if ((isset($_POST['add']) || isset($_POST['edit']) || isset($_POST['save-here']))) {
    $isSaveHere = isset($_POST['save-here']);

    $redirect = $linkMan . $strUrl;
    if ($isSaveHere && $id) {
      $redirect = $linkForm . $strUrl . '&id=' . $id;
    }

    $newId = $func->save_data(
      $_POST['data'] ?? [],
      $_FILES,
      $id,
      [
        'table'          => $table,
        'type'           => $type,
        'act'            => $act,
        'redirect'       => $redirect,
        'convert_webp'   => $newsConfig["convert_webp_$level"] ?? $newsConfig['convert_webp'] ?? false,
        'enable_slug'    => $newsConfig["slug_$level"] ?? $newsConfig['slug'] ?? false,
        'enable_seo'     => $newsConfig["seo_$level"] ?? $newsConfig['seo'] ?? false,
        'enable_gallery' => $newsConfig["gallery_$level"] ?? $newsConfig['gallery'] ?? false,
        'skip_redirect'  => $isSaveHere && !$id,
      ]
    );

    if ($isSaveHere && !$id && $newId > 0) {
      $func->transfer(capnhatdulieuthanhcong, $linkForm . $strUrl . '&id=' . $newId, true);
    }
  }

  /* ================== EDIT ================== */
  $result = $seo_data = [];
  $isId = $id_copy ?: $id;
  if ($isId) {
    $result = $d->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$isId]);
    if (!$result) {
      $func->transfer(dulieukhongcothuc, $linkMan, false);
    }

    if (!empty($newsConfig["seo_$level"] ?? $newsConfig['seo'] ?? false)) {
      $seo_data = $d->rawQueryOne("SELECT * FROM `tbl_seo` WHERE id_parent = ? AND type = ? AND act = ?",[$isId, $type, $act]);
    }

    if (!empty($newsConfig["gallery_$level"] ?? $newsConfig['gallery'] ?? false)) {
      $gallery = $d->rawQuery("SELECT * FROM `tbl_gallery` WHERE id_parent = ? AND type = ? ORDER BY numb, id DESC",[$isId, $type]);
    }
  }
}
function viewNews(string $level)
{
  global $func, $table, $curPage, $perPage, $type;
  global $id_list, $id_cat, $id_item, $id_brand, $id_sub, $keyword;
  global $paging, $show_data;
  $isMan = ($level === 'man');
  $table = $isMan ? 'tbl_news' : "tbl_news_$level";
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
  $total     = $func->count_data($options);
  $show_data = $func->show_data($options);
  $paging    = $func->pagination($total, $perPage, $curPage);
}
