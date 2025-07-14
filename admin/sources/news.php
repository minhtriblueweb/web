<?php
$act = $_GET['act'] ?? 'man';
$type = $_GET['type'] ?? null;
$table = 'tbl_news';
switch ($type) {
  case 'tintuc':
  case 'chinhsach':
  case 'huongdanchoi':
    $pageConfig = [
      'name_page' => $fn->convert_type($type)['vi'],
      'width' => 540,
      'height' => 360,
      'img_type_list' => '.jpg|.gif|.png|.jpeg|.gif|.webp',
      'status' => ['hienthi' => 'Hiển thị', 'noibat' => 'Nổi bật']
    ];
    break;
  case 'tieuchi':
    $pageConfig = [
      'name_page' => $fn->convert_type($type)['vi'],
      'width' => 40,
      'height' => 40,
      'img_type_list' => '.jpg|.gif|.png|.jpeg|.gif|.webp',
      'status' => ['hienthi' => 'Hiển thị']
    ];
    break;
  case 'danhgia':
    $pageConfig = [
      'name_page' => $fn->convert_type($type)['vi'],
      'width' => 100,
      'height' => 100,
      'img_type_list' => '.jpg|.gif|.png|.jpeg|.gif|.webp',
      'status' => ['hienthi' => 'Hiển thị']
    ];
    break;
  default:
    $fn->transfer("Không xác định loại dữ liệu!", "index.php", false);
    break;
}
$pageConfig['type'] = $type;
$pageConfig += [
  'linkMan'    => "index.php?page=news&act=man&type=$type",
  'linkForm'   => "index.php?page=news&act=form&type=$type",
  'linkEdit'   => "index.php?page=news&act=form&type=$type&id=",
  'linkDelete' => "index.php?page=news&type=$type&act=delete&id=",
  'linkMulti'  => "index.php?page=news&type=$type&act=delete_multiple",
];
extract($pageConfig);
if ($act === 'delete' && is_numeric($_GET['id'] ?? null)) {
  $fn->delete_data([
    'id' => (int)$_GET['id'],
    'table' => $table,
    'type' => $type,
    'delete_seo' => true,
    'redirect_page' => $linkMan
  ]);
} elseif ($act === 'delete_multiple' && !empty($_GET['listid'])) {
  $fn->deleteMultiple_data([
    'listid' => $_GET['listid'],
    'table' => $table,
    'type' => $type,
    'delete_seo' => true,
    'redirect_page' => $linkMan
  ]);
}

switch ($act) {
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
    $breadcrumb = [['label' => $name_page]];
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
      $save_options = [];
      $save_options['table'] = $table;
      $save_options['fields_multi'] = ['slug', 'name', 'desc', 'content'];
      $save_options['fields_common'] = ['numb', 'type'];
      $save_options['status_flags'] = array_keys($status);
      $save_options['redirect_page'] = $linkMan;
      $save_options['convert_webp'] = true;

      switch ($type) {
        case 'tintuc':
        case 'chinhsach':
        case 'huongdanchoi':
          $save_options['enable_slug'] = true;
          $save_options['enable_seo'] = true;
        case 'tieuchi':
        case 'danhgia':
          $save_options['enable_slug'] = false;
          $save_options['enable_seo'] = false;
          break;
      }

      $fn->save_data($_POST, $_FILES, $id, $save_options);
    }

    $breadcrumb = [['label' => ($id !== null ? 'Cập nhật ' : 'Thêm mới ') . $name_page]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    switch ($type) {
      case 'tieuchi':
        include TEMPLATE . "news/tieuchi_form.php";
        break;
      case 'danhgia':
        include TEMPLATE . "news/danhgia_form.php";
        break;
      default:
        include TEMPLATE . "news/news_form.php";
        break;
    }
    break;
  default:
    $fn->transfer("Trang không tồn tại", "index.php", false);
    break;
}
