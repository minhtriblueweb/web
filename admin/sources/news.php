<?php
if (!defined('SOURCES')) die("Error");
if (!isset($config['news'][$type])) $fn->transfer("Trang không tồn tại!", "index.php", false);

$table = 'tbl_news';
$linkNews = "index.php?page=news&type=$type";
$linkMan = "$linkNews&act=man";

switch ($act) {
  case 'delete':
    delete();
    break;

  case 'delete_multiple':
    deleteMultiple();
    break;

  case 'man':
    view();
    $template = "news/news_man";
    break;

  case 'form':
    save();
    $template = "news/news_form";
    break;

  default:
    $fn->transfer(trangkhongtontai, "index.php", false);
    break;
}
function view()
{
  global $fn, $table, $curPage, $perPage, $type, $keyword, $paging, $show_data;
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
}
function save()
{
  global $db, $fn, $table, $linkMan, $type, $config, $id, $result, $seo_data;
  $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
  $result = $seo_data = [];
  if ($id !== null) {
    $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 1", [$id]);
    if (!$result) $fn->transfer(dulieukhongcothuc, $linkMan, false);
    $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ?", [$id, $type]);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
    $options = [
      'table'          => $table,
      'convert_webp'   => $config['news'][$type]['convert_webp'],
      'enable_slug'    => $config['news'][$type]['slug'],
      'enable_seo'     => $config['news'][$type]['seo'],
      'enable_gallery' => $config['news'][$type]['gallery'],
      'redirect'  => $linkMan
    ];
    $fn->save_data($_POST['data'] ?? [], $_FILES, $id, $options);
  }
}
function delete()
{
  global $fn, $table, $type, $config, $linkMan;
  if (is_numeric($_GET['id'] ?? null)) {
    $fn->delete_data([
      'id' => (int)$_GET['id'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan,
      'delete_seo'     => $config['news'][$type]['seo'],
      'delete_gallery' => $config['news'][$type]['gallery']
    ]);
  } else {
    $fn->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
function deleteMultiple()
{
  global $fn, $table, $type, $config, $linkMan;
  if (!empty($_GET['listid'])) {
    $fn->deleteMultiple_data([
      'listid' => $_GET['listid'],
      'table' => $table,
      'type' => $type,
      'redirect' => $linkMan,
      'delete_seo'     => $config['news'][$type]['seo'],
      'delete_gallery' => $config['news'][$type]['gallery']
    ]);
  } else {
    $fn->transfer(khongnhanduocdulieu, $linkMan, false);
  }
}
