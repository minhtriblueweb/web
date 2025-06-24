
<?php
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
  http_response_code(404);
  include '404.php';
  exit();
}

// Lấy thông tin danh mục cấp 1
$kg_danhmuc = $danhmuc->get_danhmuc_or_404($slug, 'tbl_danhmuc_c1');
$id_list = $kg_danhmuc['id'];

// Lấy danh mục cấp 2
$get_danhmuc_c2 = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'status' => 'hienthi',
  'id_list' => $id_list
]);

// Phân trang sản phẩm
$records_per_page = 20;
$current_page = max(1, (int)($_GET['page'] ?? 1));

// Đếm tổng sản phẩm theo danh mục cấp 1
$total_records = $fn->count_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_list' => $id_list
]);

$total_pages = max(1, ceil($total_records / $records_per_page));

// Lấy sản phẩm theo phân trang
$get_sp = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'id_list' => $id_list,
  'records_per_page' => $records_per_page,
  'current_page' => $current_page
]);

$seo['title'] = !empty($kg_danhmuc['titlevi']) ? $kg_danhmuc['titlevi'] : $kg_danhmuc['namevi'];
$seo['keywords'] = $kg_danhmuc['keywordsvi'];
$seo['description'] = $kg_danhmuc['descriptionvi'];
$seo['url'] = BASE . $kg_danhmuc['slugvi'];
$seo['image'] = BASE_ADMIN . UPLOADS . $kg_danhmuc['file'];
