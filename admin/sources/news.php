<?php
$act = $_GET['act'] ?? '';
$type = $_GET['type'] ?? '';
$table = 'tbl_news';
if (!isset($config['news'][$type])) {
  $fn->transfer("Trang không tồn tại!", "index.php", false);
}
$linkMan = "index.php?page=news&act=man&type=$type";
$linkForm = str_replace('man', 'form', $linkMan);
$linkEdit = "$linkForm&id=";
$linkDelete = "index.php?page=news&type=$type&act=delete&id=";
$linkMulti  = "index.php?page=news&type=$type&act=delete_multiple";

switch ($act) {
  case 'delete':
    if (is_numeric($_GET['id'] ?? null)) {
      $fn->delete_data([
        'id' => (int)$_GET['id'],
        'table' => $table,
        'type' => $type,
        'delete_seo' => true,
        'redirect_page' => $linkMan
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
        'delete_seo' => true,
        'redirect_page' => $linkMan
      ]);
    } else {
      $fn->transfer("Danh sách ID không hợp lệ!", $linkMan, false);
    }
    break;

  case 'man':
    $keyword = $_GET['keyword'] ?? '';
    $current_page = max(1, (int)($_GET['p'] ?? 1));
    $rp = 10;
    $total_records = $fn->count_data(['table' => $table, 'type' => $type, 'keyword' => $keyword]);
    $total_pages = ceil($total_records / $rp);
    $paging = $fn->renderPagination($current_page, $total_pages);
    $show_news = $fn->show_data([
      'table' => $table,
      'type' => $type,
      'records_per_page' => $rp,
      'current_page' => $current_page,
      'keyword' => $keyword
    ]);
    $breadcrumb = [['label' => $config['news'][$type]['title_main']]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "news/news_man.php";
    break;

  case 'form':
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
    $result = $seo_data = [];

    if ($id !== null) {
      $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 1", [$id]);
      $seo_data = $result ? $seo->get_seo($id, $type) : [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
      $save_options = [
        'table'         => $table,
        'fields_multi'  => ['slug', 'name', 'desc', 'content'],
        'fields_common' => ['numb', 'type'],
        'status_flags'  => array_keys($config['news'][$type]['check']),
        'redirect_page' => $linkMan,
        'convert_webp'  => true
      ];
      if (!empty($config['news'][$type]['slug'])) $save_options['enable_slug'] = true;
      if (!empty($config['news'][$type]['seo']))  $save_options['enable_seo']  = true;
      $fn->save_data($_POST, $_FILES, $id, $save_options);
    }


    $breadcrumb = [['label' => ($id !== null ? 'Cập nhật ' : 'Thêm mới ') . $config['news'][$type]['title_main']]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "news/news_form.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại", "index.php", false);
    break;
}
