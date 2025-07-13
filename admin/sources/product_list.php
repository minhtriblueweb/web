<?php
$act = $_GET['act'] ?? 'man';
$type = 'product_list';
$table = "tbl_$type";
$pageConfig = [
  'name_page' => 'danh mục cấp 1',
  'table' => $table,
  'width' => 50,
  'height' => 50,
  'img_type_list' => '.jpg|.gif|.png|.jpeg|.gif|.webp',
  'linkMan' => "index.php?page=$type&act=man",
  'linkForm' => "index.php?page=$type&act=form",
  'linkEdit' => "index.php?page=$type&act=form&id=",
  'linkDelete' => "index.php?page=$type&act=delete&id=",
  'linkMulti' => "index.php?page=$type&act=delete_multiple",
  'status' => ['hienthi' => 'Hiển thị', 'noibat' => 'Nổi bật']
];
extract($pageConfig);
$validActs = ['man', 'form', 'delete', 'delete_multiple'];
if (!in_array($act, $validActs)) {
  $fn->transfer("Trang không tồn tại", "index.php", false);
}
if ($act === 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {
  $fn->delete_data([
    'id' => (int)$_GET['id'],
    'table' => $table,
    'type' => $type,
    'delete_seo' => true,
    'redirect_page' => $linkMan
  ]);
}
if ($act === 'delete_multiple' && isset($_GET['listid'])) {
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
    $records_per_page = 10;
    $total_records = $fn->count_data(['table' => $table, 'keyword' => $keyword]);
    $total_pages = ceil($total_records / $records_per_page);
    $paging = $fn->renderPagination($current_page, $total_pages);
    $show_danhmuc = $fn->show_data([
      'table' => $table,
      'records_per_page' => $records_per_page,
      'current_page' => $current_page,
      'keyword' => $keyword
    ]);

    $breadcrumb = [['label' => $name_page]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "product_list/product_list_man.php";
    break;
  case 'form':
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
    $result = $seo_data = [];
    if ($id !== null) {
      $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
      $seo_data = $result ? $seo->get_seo($id, $type) : [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
      $fn->save_data($_POST, $_FILES, $id, [
        'table'              => $table,
        'fields_multi'       => ['slug', 'name', 'desc', 'content'],
        'fields_common'      => ['numb', 'type'],
        'status_flags'       => array_keys($status),
        'redirect_page'      => $linkMan,
        'enable_seo'         => true,
        'enable_slug'        => true,
        'convert_webp'       => false,
        'watermark'          => false,
      ]);
    }
    $breadcrumb = [['label' => ($id !== null ? 'Cập nhật ' : 'Thêm mới ') . $name_page]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "product_list/product_list_form.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại", "index.php", false);
    break;
}
