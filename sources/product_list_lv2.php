<?php
$slug = $_GET['slug'] ?? '';

if (empty($slug)) $fn->abort_404();

// Lấy thông tin danh mục cấp 2 và cấp 1 (JOIN)
$dm_c2 = $danhmuc->get_danhmuc_c2_with_parent_or_404($slug);
// Cấp 1 (cha)
$dm_c1 = [
  'namevi' => $dm_c2['name_lv1'] ?? '',
  'slugvi' => $dm_c2['slug_lv1'] ?? '',
  'titlevi' => $dm_c2['title_lv1'] ?? '',
  'keywordsvi' => $dm_c2['keywords_lv1'] ?? '',
  'descriptionvi' => $dm_c2['description_lv1'] ?? ''
];
// Sidebar: danh sách danh mục cấp 2 thuộc cùng cấp 1
$id_list = (int)$dm_c2['id_list'];
$list_danhmuc_c2 = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi',
  'id_list' => $id_list
]);

// Phân trang sản phẩm
$records_per_page = 20;
$current_page = max(1, (int)($_GET['page'] ?? 1));

$total_records = $fn->count_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_cat' => $dm_c2['id']
]);

$total_pages = max(1, ceil($total_records / $records_per_page));

$get_sp = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_cat' => $dm_c2['id'],
  'records_per_page' => $records_per_page,
  'current_page' => $current_page
]);
// SEO
$seo['title'] = !empty($dm_c2['titlevi']) ? $dm_c2['titlevi'] : $dm_c2['namevi'];
$seo['keywords'] = $dm_c2['keywordsvi'];
$seo['description'] = $dm_c2['descriptionvi'];
$seo['url'] = BASE . $dm_c2['slugvi'];
$seo['image'] = BASE_ADMIN . UPLOADS . $dm_c2['file'];
