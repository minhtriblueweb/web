<?php
$records_per_page = 20;
$current_page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);

// Lấy tổng số sản phẩm
$total_records = $fn->count_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
]);

$total_pages = ceil($total_records / $records_per_page);

// Lấy sản phẩm theo trang
$show_sanpham = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'records_per_page' => $records_per_page,
  'current_page' => $current_page,
  'select' => "id, name{$lang}, slug{$lang}, file, sale_price, regular_price, views"
]);

// Lấy dữ liệu SEO
$data_seo = $seo->get_seopage(
  $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['sanpham']) ?: [],
  $lang
);
// Lấy danh mục cấp 1
$dm = $fn->show_data([
  'table' => 'tbl_danhmuc_c1',
  'status' => 'hienthi,noibat',
  'select' => "id, name{$lang}, slug{$lang}"
]);
// breadcrumbs
$breadcrumbs->set('san-pham', 'Sản phẩm');
$breadcrumbs = $breadcrumbs->get();
