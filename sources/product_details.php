<?php
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
  http_response_code(404);
  include '404.php';
  exit();
}

// Lấy thông tin sản phẩm
$row_sp = $fn->get_only_data([
  'table' => 'tbl_sanpham',
  'slugvi' => $slug,
  'status' => 'hienthi'
]);
if (!$row_sp) {
  http_response_code(404);
  include '404.php';
  exit();
}
$sanpham->update_views_by_slug($slug);
$dm_c1_name = $dm_c2_name = $dm_c1_slug = $dm_c2_slug = '';
$id = $row_sp['id'];
$id_list = $row_sp['id_list'];
$id_cat = $row_sp['id_cat'];
$get_danhmuc = $sanpham->get_danhmuc_by_sanpham($id);
if ($get_danhmuc && $dm = $get_danhmuc->fetch_assoc()) {
  $dm_c1_name = $dm['dm_c1_name'] ?? '';
  $dm_c2_name = $dm['dm_c2_name'] ?? '';
  $dm_c1_slug = $dm['dm_c1_slug'] ?? '';
  $dm_c2_slug = $dm['dm_c2_slug'] ?? '';
}
// Sản phẩm liên quan
$records_per_page = 1;
$current_page = max(1, (int)($_GET['page'] ?? 1));
$total_records = $fn->count_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'exclude_id' => $id,
  'records_per_page' => $records_per_page,
  'current_page' => $current_page
]);
$total_pages = max(1, ceil($total_records / $records_per_page));

$sanpham_lienquan = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'exclude_id' => $id,
  'records_per_page' => $records_per_page,
  'current_page' => $current_page
]);
// Hình ảnh con
$get_gallery = $fn->show_data([
  'table' => 'tbl_gallery',
  'status' => 'hienthi',
  'id_parent' => $id,
]);

// SEO
$seo['title'] = !empty($row_sp['titlevi']) ? $row_sp['titlevi'] : $row_sp['namevi'];
$seo['keywords'] = $row_sp['keywordsvi'];
$seo['description'] = $row_sp['descriptionvi'];
$seo['url'] = BASE . $row_sp['slugvi'];
$seo['image'] = BASE_ADMIN . UPLOADS . $row_sp['file'];

$img_main = !empty($row_sp['file']) ? BASE_ADMIN . UPLOADS . $row_sp['file'] : NO_IMG;
$img_alt = $row_sp['namevi'];

// breadcrumbs
$breadcrumbs = [
  ['label' => 'Trang chủ', 'url' => './'],
  ['label' => 'Sản phẩm', 'url' => 'san-pham'],
];
if (!empty($dm_c1_name) && !empty($dm_c1_slug)) {
  $breadcrumbs[] = ['label' => $dm_c1_name, 'url' => BASE . $dm_c1_slug];
}
if (!empty($dm_c2_name) && !empty($dm_c2_slug)) {
  $breadcrumbs[] = ['label' => $dm_c2_name, 'url' => BASE . $dm_c2_slug];
}
// Sản phẩm hiện tại (active, không cần URL)
$breadcrumbs[] = ['label' => $row_sp['namevi'], 'url' => BASE . $row_sp['slugvi'], 'active' => true];
