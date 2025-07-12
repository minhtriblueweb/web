<?php
$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
  http_response_code(404);
  include '404.php';
  exit();
}

// Lấy thông tin sản phẩm theo ngôn ngữ
$row_sp = $db->rawQueryOne("SELECT * FROM tbl_product WHERE FIND_IN_SET('hienthi', status) AND slug{$lang} = ? LIMIT 1", [$slug]);

if (!$row_sp) {
  http_response_code(404);
  include '404.php';
  exit();
}

$product->update_views($slug);

$id = $row_sp['id'];
$id_list = $row_sp['id_list'];
$id_cat = $row_sp['id_cat'];

// Lấy thông tin danh mục C1, C2 theo JOIN và ngôn ngữ
$dm_data = $fn->show_data_join([
  'table'  => 'tbl_product',
  'alias'  => 'sp',
  'join'   => "
    INNER JOIN tbl_product_list c1 ON sp.id_list = c1.id
    LEFT JOIN tbl_product_cat c2 ON sp.id_cat = c2.id
  ",
  'select' => "
    c1.name{$lang} AS dm_c1_name,
    c1.slug{$lang} AS dm_c1_slug,
    c2.name{$lang} AS dm_c2_name,
    c2.slug{$lang} AS dm_c2_slug
  ",
  'where' => ['sp.id' => $id],
  'limit' => 1
]);

$dm = $dm_data[0] ?? [];
$dm_c1_name = $dm['dm_c1_name'] ?? '';
$dm_c2_name = $dm['dm_c2_name'] ?? '';
$dm_c1_slug = $dm['dm_c1_slug'] ?? '';
$dm_c2_slug = $dm['dm_c2_slug'] ?? '';

// Sản phẩm liên quan
$records_per_page = 8;
$current_page = max(1, (int)($_GET['page'] ?? 1));
$total_records = $fn->count_data([
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'type' => 'product',
  'exclude_id' => $id
]);
$total_pages = max(1, ceil($total_records / $records_per_page));

$sanpham_lienquan = $fn->show_data([
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'exclude_id' => $id,
  'select' => "id,file, name{$lang}, slug{$lang}, regular_price, sale_price, views",
  'records_per_page' => $records_per_page,
  'current_page' => $current_page
]);

// Hình ảnh con
$get_gallery = $fn->show_data([
  'table' => 'tbl_gallery',
  'status' => 'hienthi',
  'id_parent' => $id,
  'select' => "file"
]);

// Lấy tiêu chí
$show_tieuchi = $fn->show_data(['table' => 'tbl_tieuchi', 'status' => 'hienthi', 'select' => "file, name{$lang}"]);

// SEO
$data_seo = $seo->get_seo($id, 'product');

// Breadcrumbs
$breadcrumbs->set('san-pham', 'Sản phẩm');
if (!empty($dm_c1_slug) && !empty($dm_c1_name)) {
  $breadcrumbs->set($dm_c1_slug, $dm_c1_name);
}
if (!empty($dm_c2_slug) && !empty($dm_c2_name)) {
  $breadcrumbs->set($dm_c2_slug, $dm_c2_name);
}
$breadcrumbs->set($row_sp['slug' . $lang], $row_sp['name' . $lang]);
$breadcrumbs = $breadcrumbs->get();
