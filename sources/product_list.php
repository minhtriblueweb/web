<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) $fn->abort_404();
$dm = $db->rawQueryOne("SELECT id, slug{$lang}, name{$lang}, content{$lang},file FROM tbl_product_list WHERE slug{$lang} = ? AND FIND_IN_SET('hienthi', status) ORDER BY numb, id DESC LIMIT 1", [$slug]);
if (!$dm) $fn->abort_404();
$id_list = (int)$dm['id'];
// Lấy danh mục cấp 2 thuộc danh mục cấp 1 này
$dm_c2_all =  $db->rawQuery(
  "SELECT id, name{$lang}, slug{$lang} FROM tbl_product_cat WHERE id_list = ? AND FIND_IN_SET('hienthi', status) ORDER BY numb, id DESC",
  [$id_list]
);

// Phân trang sản phẩm
$curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$perPage = 10;

$options = [
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'id_list' => $id_list,
  'select' => "id, name{$lang}, slug{$lang}, file, regular_price, sale_price, views",
  'pagination'  => [$perPage, $curPage]
];

$total = $fn->count_data($options);
$show_sanpham = $fn->show_data($options);
$paging = $fn->pagination_tc($total, $perPage, $curPage);

// SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 1", [$id_list, 'sanpham', 'man_list']);
$seo->set('h1', $dm['name' . $lang]);
if (!empty($seo_data['title' . $lang])) $seo->set('title', $seo_data['title' . $lang]);
else $seo->set('title', $dm['name' . $lang]);
if (!empty($seo_data['keywords' . $lang])) $seo->set('keywords', $seo_data['keywords' . $lang]);
if (!empty($seo_data['description' . $lang])) $seo->set('description', $seo_data['description' . $lang]);
$imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
if (!empty($imgJson)) {
  $seo->set('photo:width', $imgJson['width']);
  $seo->set('photo:height', $imgJson['height']);
  $seo->set('photo:type', $imgJson['m']);
}
if (!empty($dm['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $dm['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

// breadcrumbs
$breadcrumbs->set('san-pham', 'Sản phẩm');
if (!empty($dm['slug' . $lang]) && !empty($dm['name' . $lang])) {
  $breadcrumbs->set($dm['slug' . $lang], $dm['name' . $lang]);
}
$breadcrumbs = $breadcrumbs->get();
