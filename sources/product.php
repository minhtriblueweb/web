<?php

$curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$perPage = 10;

$options = [
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'select' => "id, name{$lang}, slug{$lang}, file, sale_price, regular_price, views",
  'pagination'  => [$perPage, $curPage]
];

$total = $fn->count_data($options);
$show_sanpham = $fn->show_data($options);
$paging = $fn->pagination_tc($total, $perPage, $curPage);

// SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['sanpham']);
$seo->set('h1', $seo_data['title' . $lang]);
if (!empty($seo_data['title' . $lang])) $seo->set('title', $seo_data['title' . $lang]);
if (!empty($seo_data['keywords' . $lang])) $seo->set('keywords', $seo_data['keywords' . $lang]);
if (!empty($seo_data['description' . $lang])) $seo->set('description', $seo_data['description' . $lang]);
$imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
if (!empty($imgJson)) {
  $seo->set('photo:width', $imgJson['width']);
  $seo->set('photo:height', $imgJson['height']);
  //   $seo->set('photo:type', $imgJson['m']);
}
if (!empty($seo_data['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $seo_data['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));
// Lấy danh mục cấp 1
$dm = $fn->show_data([
  'table' => 'tbl_product_list',
  'status' => 'hienthi',
  'select' => "id, name{$lang}, slug{$lang}"
]);
// breadcrumbs
$breadcrumbs->set('san-pham', 'Sản phẩm');
$breadcrumbs = $breadcrumbs->get();
