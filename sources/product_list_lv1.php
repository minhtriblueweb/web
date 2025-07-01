<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) $fn->abort_404();

// Lấy danh mục cấp 1
$dm = $danhmuc->get_danhmuc_or_404($slug, 'tbl_danhmuc_c1');
$id_list = (int)$dm['id'];

// Lấy danh sách danh mục cấp 2 trong cấp 1 này
$dm_c2_all = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi',
  'id_list' => $id_list
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
  'current_page' => $page
]);

// SEO
$lang = array_key_first($config['website']['lang']);
$seo_page = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE type = ? AND id_parent = ?", ['danhmuc_c1', $id_list]);
$fn->get_seo($seo_page ?: $dm, $lang);
