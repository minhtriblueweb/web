<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) $fn->abort_404();

// Lấy danh mục cấp 2 kèm cấp 1 (JOIN)
$dm_c2_list = $fn->show_data_join([
  'table' => 'tbl_product_cat',
  'alias' => 'c2',
  'join' => 'JOIN tbl_product_list c1 ON c2.id_list = c1.id',
  'select' => "c2.*, c1.name{$lang} AS name_lv1, c1.slug{$lang} AS slug_lv1",
  'where' => ["c2.slug{$lang}" => $slug],
  'limit' => 1
]);

$dm_c2 = $dm_c2_list[0] ?? null;
if (!$dm_c2) $fn->abort_404();
// Gộp dữ liệu danh mục cấp 1 vào biến $dm_c1
$dm_c1 = [
  "name{$lang}" => $dm_c2["name_lv1"] ?? '',
  "slug{$lang}" => $dm_c2["slug_lv1"] ?? ''
];

// Lấy danh sách các danh mục cấp 2 cùng cấp 1
$id_list = (int)$dm_c2['id_list'];
$list_danhmuc_c2 = $fn->show_data([
  'table' => 'tbl_product_cat',
  'status' => 'hienthi',
  'id_list' => $id_list,
  'select' => "id, name{$lang}, slug{$lang}"
]);

// Phân trang sản phẩm thuộc danh mục cấp 2
$per_page = 20;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = $fn->count_data([
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'id_cat' => $dm_c2['id']
]);
$total_pages = max(1, ceil($total / $per_page));

$get_sp = $fn->show_data([
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'id_cat' => $dm_c2['id'],
  'records_per_page' => $per_page,
  'current_page' => $page,
  'select' => "id, name{$lang}, slug{$lang}, file, regular_price, sale_price, views"
]);

// SEO
$data_seo = $seo->get_seo($dm_c2['id'], 'danhmuc_c2');
// breadcrumbs
$breadcrumbs->set('san-pham', 'Sản phẩm');
$breadcrumbs->set($dm_c1['slug' . $lang], $dm_c1['name' . $lang]);
$breadcrumbs->set($dm_c2['slug' . $lang], $dm_c2['name' . $lang]);
$breadcrumbs = $breadcrumbs->get();
