<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) $fn->abort_404();

// Lấy thông tin sản phẩm
$row_sp = $db->rawQueryOne("SELECT * FROM tbl_product WHERE FIND_IN_SET('hienthi', status) AND slug{$lang} = ? LIMIT 1", [$slug]);
if (!$row_sp) $fn->abort_404();

$product->update_views($slug);

$id = $row_sp['id'];
$id_list = $row_sp['id_list'];
$id_cat = $row_sp['id_cat'];

// Lấy thông tin danh mục C1, C2
$dm_data = $fn->show_data([
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

// sản phẩm liên quan
$curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$perPage = 10;
$options = [
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'type' => 'product',
  'exclude_id' => $id,
  'select' => "id,file, name{$lang}, slug{$lang}, regular_price, sale_price, views",
  'pagination'  => [$perPage, $curPage]
];
$sanpham_lienquan = $fn->show_data($options);
$total_lienquan = $fn->count_data($options);
$paging = $fn->pagination_tc($total_lienquan, $perPage, $curPage);


// Hình ảnh con
$get_gallery = $fn->show_data([
  'table' => 'tbl_gallery',
  'status' => 'hienthi',
  'id_parent' => $id,
  'select' => "file"
]);

//SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 1", [$id, 'san-pham', 'man']);
$seo->set('h1', $seo_data["title$lang"]);
if (!empty($seo_data["title$lang"])) $seo->set('title', $seo_data["title$lang"]);
if (!empty($seo_data["keywords$lang"])) $seo->set('keywords', $seo_data["keywords$lang"]);
if (!empty($seo_data["description$lang"])) $seo->set('description', $seo_data["description$lang"]);
$imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
if (!empty($imgJson)) {
  $seo->set('photo:width', $imgJson['width']);
  $seo->set('photo:height', $imgJson['height']);
}
if (!empty($row_sp['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $row_sp['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

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
