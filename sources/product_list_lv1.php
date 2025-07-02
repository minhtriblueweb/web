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
$seo = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE type = ? AND id_parent = ?", ['danhmuc_c1', $id_list]);
$fn->get_seo($seo ?: $dm, $lang);
// breadcrumbs
$breadcrumbs->set('san-pham', 'Sản phẩm');
$breadcrumbs->set($dm['slug' . $lang], $dm['name' . $lang]);
$breadcrumbs = $breadcrumbs->get();
