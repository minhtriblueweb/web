<?php
$act = $_GET['act'] ?? 'man';
$type = 'product_cat';
$table = "tbl_$type";
$pageConfig = [
  'name_page' => 'danh mục cấp 2',
  'table' => $table,
  'width' => 100,
  'height' => 100,
  'img_type_list' => '.jpg|.gif|.png|.jpeg|.gif|.webp',
  'linkMan' => "index.php?page=$type&act=man",
  'linkForm' => "index.php?page=$type&act=form",
  'linkEdit' => "index.php?page=$type&act=form&id=",
  'linkDelete' => "index.php?page=$type&act=delete&id=",
  'linkMulti' => "index.php?page=$type&act=delete_multiple",
  'status' => ['hienthi' => 'Hiển thị', 'noibat' => 'Nổi bật']
];
extract($pageConfig);
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
    $total_records = $fn->count_data([
      'table' => $table,
      'id_list' => $_GET['id_list'] ?? '',
      'keyword' => $_GET['keyword'] ?? ''
    ]);
    $total_pages = ceil($total_records / $records_per_page);
    $paging = $fn->renderPagination($current_page, $total_pages);

    $show_product_cat = $fn->show_data([
      'table' => $table,
      'id_list' => $_GET['id_list'] ?? '',
      'records_per_page' => $records_per_page,
      'current_page' => $current_page,
      'keyword' => $_GET['keyword'] ?? ''
    ]);
    $show_product_list = $fn->show_data(['table' => 'tbl_product_list']);

    $breadcrumb = [['label' => $name_page]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "product_cat/product_cat_man.php";
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
        'table'               => $table,
        'fields_multi'        => ['slug', 'name', 'desc', 'content'],
        'fields_common'       => ['id_list', 'numb', 'type'],
        'status_flags'        => array_keys($status),
        'redirect_page'       => $linkMan,
        'enable_seo'          => true,
        'enable_slug'         => true,
        'convert_webp'        => true,
        'watermark'           => false,
      ]);
    }
    $breadcrumb = [['label' => ($id !== null ? 'Cập nhật ' : 'Thêm mới ') . $name_page]];
    include TEMPLATE . LAYOUT . 'breadcrumb.php';
    include TEMPLATE . "product_cat/product_cat_form.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại", "index.php", false);
    break;
}
