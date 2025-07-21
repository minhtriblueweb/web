<?php
$slides = $fn->show_data([
  'table'  => 'tbl_photo',
  'type'  => 'slideshow',
  'status' => 'hienthi',
  'select' => "file, name{$lang}, link, options"
]);
$tintuc = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi,noibat',
  'type'   => 'tintuc',
  'select' => "file, name{$lang}, desc{$lang}, slug{$lang}",
  'limit'  => 8
]);
$tieuchi = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'tieuchi',
  'select' => "file, name{$lang}, desc{$lang}"
]);
$feedback = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'danhgia',
  'select' => "file, name{$lang}, desc{$lang},content{$lang}"
]);
$sanpham_banchay = $fn->show_data([
  'table'  => 'tbl_product',
  'status' => 'hienthi,banchay',
  'select' => "id, file, slug{$lang}, name{$lang}, sale_price, regular_price, views"
]);
$sp_all = $fn->show_data([
  'table'  => 'tbl_product',
  'status' => 'hienthi',
  'select' => "id, file, slug{$lang}, name{$lang}, sale_price, regular_price, views, id_list, id_cat"
]);
$sp_group = [];
foreach ($sp_all ?? [] as $row) {
  $id_list = $row['id_list'];
  $id_cat = $row['id_cat'];
  $sp_group[$id_list]['all'][] = $row;
  if ($id_cat) {
    $sp_group[$id_list]['cat'][$id_cat][] = $row;
  }
}

// SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['trangchu']);
$seo->set('h1', $seo_data['title' . $lang]);
if (!empty($seo_data['title' . $lang])) $seo->set('title', $seo_data['title' . $lang]);
if (!empty($seo_data['keywords' . $lang])) $seo->set('keywords', $seo_data['keywords' . $lang]);
if (!empty($seo_data['description' . $lang])) $seo->set('description', $seo_data['description' . $lang]);
$imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
if (!empty($imgJson)) {
  $seo->set('photo:width', $imgJson['width']);
  $seo->set('photo:height', $imgJson['height']);
}
if (!empty($seo_data['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $seo_data['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));
