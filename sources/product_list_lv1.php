<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) $fn->abort_404();

// Lấy danh mục cấp 1 theo slug
$dm = $fn->show_data([
  'table' => 'tbl_danhmuc_c1',
  "slug{$lang}" => $slug,
  'limit' => 1
])[0] ?? null;

if (!$dm) $fn->abort_404();

$id_list = (int)$dm['id'];

// Lấy danh mục cấp 2 thuộc danh mục cấp 1 này
$dm_c2_all = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi',
  'id_list' => $id_list,
  'select' => "id, name{$lang}, slug{$lang}"
]);

// Phân trang sản phẩm
$per_page = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = $fn->count_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_list' => $id_list
]);
$total_pages = max(1, ceil($total / $per_page));

$sp_all = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_list' => $id_list,
  'records_per_page' => $per_page,
  'current_page' => $page,
  'select' => "id, name{$lang}, slug{$lang}, file, regular_price, sale_price, views"
]);

// SEO
$data_seo = $seo->get_seo($id_list, 'danhmuc_c1');
// breadcrumbs
$breadcrumbs->set('san-pham', 'Sản phẩm');
$breadcrumbs->set($dm['slug' . $lang], $dm['name' . $lang]);
$breadcrumbs = $breadcrumbs->get();
