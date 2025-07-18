<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) $fn->abort_404();
$dm = $db->rawQueryOne(
  "SELECT id, slug{$lang}, name{$lang}, content{$lang} FROM tbl_product_list WHERE slug{$lang} = ? AND FIND_IN_SET('hienthi', status) ORDER BY numb, id DESC LIMIT 1",
  [$slug]
);
if (!$dm) $fn->abort_404();
$id_list = (int)$dm['id'];
// Lấy danh mục cấp 2 thuộc danh mục cấp 1 này
$dm_c2_all =  $db->rawQuery(
  "SELECT id, name{$lang}, slug{$lang} FROM tbl_product_cat WHERE id_list = ? AND FIND_IN_SET('hienthi', status) ORDER BY numb, id DESC",
  [$id_list]
);

// Phân trang sản phẩm
$per_page = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = $fn->count_data([
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'id_list' => $id_list
]);
$total_pages = max(1, ceil($total / $per_page));

$sp_all = $fn->show_data([
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'id_list' => $id_list,
  'records_per_page' => $per_page,
  'current_page' => $page,
  'select' => "id, name{$lang}, slug{$lang}, file, regular_price, sale_price, views",
]);

// SEO
$data_seo = $seo->get_seo($id_list, 'product_list');
// breadcrumbs
$breadcrumbs->set('san-pham', 'Sản phẩm');
$breadcrumbs->set($dm['slug' . $lang], $dm['name' . $lang]);
$breadcrumbs = $breadcrumbs->get();
