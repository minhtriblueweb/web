<?php
if (!defined('SOURCES')) die("Error");
$table = 'tbl_news';
if (!isset($config['news'][$type])) {
  $fn->transfer("Trang không tồn tại!", "index.php", false);
}
$linkNews = "index.php?page=news&type=$type";
$linkMan = "$linkNews&act=man";
$linkForm = "$linkNews&act=form";
$linkEdit = "$linkForm&id=";
$linkDelete = "$linkNews&act=delete&id=";
$linkMulti  = "$linkNews&act=delete_multiple";

switch ($act) {
  case 'delete':
    if (is_numeric($_GET['id'] ?? null)) {
      $fn->delete_data([
        'id' => (int)$_GET['id'],
        'table' => $table,
        'type' => $type,
        'redirect_page' => $linkMan,
        'delete_seo'     => $config['news'][$type]['seo'],
        'delete_gallery' => $config['news'][$type]['gallery']
      ]);
    } else {
      $fn->transfer("ID không hợp lệ!", $linkMan, false);
    }
    break;

  case 'delete_multiple':
    if (!empty($_GET['listid'])) {
      $fn->deleteMultiple_data([
        'listid' => $_GET['listid'],
        'table' => $table,
        'type' => $type,
        'redirect_page' => $linkMan,
        'delete_seo'     => $config['news'][$type]['seo'],
        'delete_gallery' => $config['news'][$type]['gallery']
      ]);
    } else {
      $fn->transfer("Danh sách ID không hợp lệ!", $linkMan, false);
    }
    break;

  case 'man':
    $keyword = $_GET['keyword'] ?? '';
    $curPage = max(1, (int)($_GET['p'] ?? 1));
    $perPage = 10;
    $options = [
      'table' => $table,
      'type' => $type,
      'keyword' => $keyword,
      'pagination'  => [$perPage, $curPage]
    ];
    $total = $fn->count_data($options);
    $show_data = $fn->show_data($options);
    $paging = $fn->pagination($total, $perPage, $curPage);
    $template = "news/news_man";
    break;

  case 'form':
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
    $result = $seo_data = [];

    if ($id !== null) {
      $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 1", [$id]);
      if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
      $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ?", [$id, $type]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
      $save_options = [
        'table'          => $table,
        'fields_multi'   => ['slug', 'name', 'desc', 'content'],
        'fields_common'  => ['numb', 'type'],
        'status_flags'   => $config['news'][$type]['check'],
        'convert_webp'   => $config['news'][$type]['convert_webp'],
        'enable_slug'    => $config['news'][$type]['slug'],
        'enable_seo'     => $config['news'][$type]['seo'],
        'enable_gallery' => $config['news'][$type]['gallery'],
        'redirect_page'  => $linkMan,
      ];
      $fn->save_data($_POST, $_FILES, $id, $save_options);
    }
    $template = "news/news_form";
    break;

  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
