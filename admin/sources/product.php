<?php
$act = $_GET['act'] ?? 'man';
$type = 'product';
$pageConfig = [
  'name_page' => 'Sản phẩm',
  'table' => "tbl_$type",
  'width' => 500,
  'height' => 500,
  'img_type_list' => '.jpg|.gif|.png|.jpeg|.gif|.webp',
  'linkMan' => "index.php?page=$type&act=man",
  'linkForm' => "index.php?page=$type&act=form",
  'linkEdit' => "index.php?page=$type&act=form&id=",
  'linkDelete' => "index.php?page=$type&act=delete&id=",
  'linkMulti' => "index.php?page=$type&act=delete_multiple",
  'linkGalleryMan' => "index.php?page=gallery&act=man&id=",
  'linkGalleryForm' => "index.php?page=gallery&act=form&id=",
  'status' => ['hienthi' => 'Hiển thị', 'noibat' => 'Nổi bật', 'banchay' => 'Bán chạy']
];
extract($pageConfig);
if ($act === 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {
  $fn->delete_data([
    'id' => (int)$_GET['id'],
    'table' => $table,
    'type' => $type,
    'delete_seo' => true,
    'delete_gallery' => true,
    'redirect_page' => $linkMan
  ]);
}
if ($act === 'delete_multiple' && isset($_GET['listid'])) {
  $fn->deleteMultiple_data([
    'listid' => $_GET['listid'],
    'table' => $table,
    'type' => $type,
    'delete_seo' => true,
    'delete_gallery' => true,
    'redirect_page' => $linkMan
  ]);
}
switch ($act) {
  case 'man':
    $id_list = $_GET['id_list'] ?? '';
    $id_cat = $_GET['id_cat'] ?? '';
    $keyword = $_GET['keyword'] ?? '';
    $current_page = max(1, (int)($_GET['p'] ?? 1));
    $records_per_page = 10;

    $join = "
      LEFT JOIN tbl_product_cat c2 ON p.id_cat = c2.id
      LEFT JOIN tbl_product_list c1 ON p.id_list = c1.id
    ";
    $where = array_filter([
      'c1.id' => $id_list,
      'c2.id' => $id_cat
    ]);

    $total_records = $fn->count_data_join([
      'table' => $table,
      'alias' => 'p',
      'join' => $join,
      'where' => $where,
      'keyword' => $keyword
    ]);

    $total_pages = ceil($total_records / $records_per_page);
    $paging = $fn->renderPagination($current_page, $total_pages);

    $show_sanpham = $fn->show_data_join([
      'table' => $table,
      'alias' => 'p',
      'join' => $join,
      'select' => "
        p.*,
        c1.name{$lang} AS name_list,
        c2.name{$lang} AS name_cat
      ",
      'where' => $where,
      'keyword' => $keyword,
      'records_per_page' => $records_per_page,
      'current_page' => $current_page
    ]);

    $breadcrumb = [['label' => $name_page]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "product/product_man.php";
    break;

  case 'form':
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
    $result = $seo_data = [];

    if ($id !== null) {
      $result = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ?", [$id]);
      $seo_data = $result ? $seo->get_seo($id, $type) : [];
      $gallery = $db->rawQuery("SELECT * FROM tbl_gallery WHERE id_parent = ? AND type = ? ORDER BY numb ASC, id ASC", [$id, $type]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
      $fn->save_data($_POST, $_FILES, $id, [
        'table' => $table,
        'fields_multi' => ['slug', 'name', 'desc', 'content'],
        'fields_common' => ['id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code', 'numb', 'type'],
        'status_flags' => array_keys($status),
        'redirect_page' => $linkMan,
        'convert_webp' => true,
        'watermark' => true,
        'enable_slug' => true,
        'enable_seo' => true,
        'enable_gallery' => true
      ]);
    }

    $breadcrumb = [['label' => ($id !== null ? 'Cập nhật ' : 'Thêm mới ') . $name_page]];
    include TEMPLATE . LAYOUT . "breadcrumb.php";
    include TEMPLATE . "product/product_form.php";
    break;

  default:
    $fn->transfer("Trang không tồn tại", "index.php", false);
    break;
}
