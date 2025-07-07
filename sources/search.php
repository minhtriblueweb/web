<?php
$keyword = $_GET['keyword'] ?? '';
$records_per_page = 20;
$current_page = max(1, (int)($_GET['page'] ?? 1));

$total_records = $fn->count_data([
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'keyword' => $keyword
]);

$total_pages = ceil($total_records / $records_per_page);

$show_product = $fn->show_data([
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'keyword' => $keyword,
  'records_per_page' => $records_per_page,
  'current_page' => $current_page,
  'select' => "id, file, name{$lang}, slug{$lang}, sale_price, regular_price, views"
]);
// breadcrumbs
$breadcrumbs->set('tim-kiem', 'Tìm kiếm');
$breadcrumbs = $breadcrumbs->get();
