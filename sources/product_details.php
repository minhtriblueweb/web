<?php
$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
  http_response_code(404);
  include '404.php';
  exit();
}

// Lấy thông tin sản phẩm theo ngôn ngữ
$row_sp = $db->fetchOne("SELECT * FROM tbl_sanpham WHERE FIND_IN_SET('hienthi', status) AND slug{$lang} = ?", [$slug]);

if (!$row_sp) {
  http_response_code(404);
  include '404.php';
  exit();
}

$sanpham->update_views_by_slug($slug);

$id = $row_sp['id'];
$id_list = $row_sp['id_list'];
$id_cat = $row_sp['id_cat'];

// Lấy thông tin danh mục C1, C2 theo JOIN và ngôn ngữ
$dm_data = $fn->show_data_join([
  'table'  => 'tbl_sanpham',
  'alias'  => 'sp',
  'join'   => "
    INNER JOIN tbl_danhmuc_c1 c1 ON sp.id_list = c1.id
    LEFT JOIN tbl_danhmuc_c2 c2 ON sp.id_cat = c2.id
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
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'exclude_id' => $id
]);
$total_pages = max(1, ceil($total_records / $records_per_page));

$sanpham_lienquan = $fn->show_data([
  'table' => 'tbl_sanpham',
  'status' => 'hienthi',
  'exclude_id' => $id,
  'select' => "id, file, name{$lang}, slug{$lang}, regular_price, sale_price, views",
  'records_per_page' => $records_per_page,
  'current_page' => $current_page
]);

// Hình ảnh con
$get_gallery = $fn->show_data([
  'table' => 'tbl_gallery',
  'status' => 'hienthi',
  'id_parent' => $id,
  'select' => "id, file"
]);

// SEO
$fn->get_seo($db->rawQueryOne("SELECT * FROM tbl_seo WHERE type = ? AND id_parent = ?", ['sanpham', $id]) ?: $row_sp, $lang);

// Ảnh chính sản phẩm
$img_main = !empty($row_sp['file']) ? BASE_ADMIN . UPLOADS . $row_sp['file'] : NO_IMG;
$img_alt = $row_sp['name' . $lang];

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
