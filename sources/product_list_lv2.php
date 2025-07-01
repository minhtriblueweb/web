<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) $fn->abort_404();

// Lấy danh mục cấp 2 + cấp 1
$dm_c2 = $danhmuc->get_danhmuc_c2_with_parent_or_404($slug);

// Lấy danh mục cấp 1 (từ c2), đổi key name_lv1 => namevi
$dm_c1 = array_combine(
  array_map(fn($k) => $k . 'vi', ['name', 'slug', 'title', 'keywords', 'description']),
  array_map(fn($k) => $dm_c2[$k . '_lv1'] ?? '', ['name', 'slug', 'title', 'keywords', 'description'])
);

// Lấy các danh mục cấp 2 cùng cấp 1
$id_list = (int)$dm_c2['id_list'];
$list_danhmuc_c2 = $fn->show_data([
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
  'id_cat' => $dm_c2['id']
]);
$total_pages = ceil(max(1, $total / $per_page));

$get_sp = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_cat' => $dm_c2['id'],
  'records_per_page' => $per_page,
  'current_page' => $page
]);

// SEO
$lang = array_key_first($config['website']['lang']);
$seo_row = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE type = ? AND id_parent = ?", ['danhmuc_c2', $dm_c2['id']]);
$fn->get_seo($seo_row ?: $dm_c2, $lang);
