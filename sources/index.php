<?php
$slides = $fn->show_data([
  'table'  => 'tbl_photo',
  'type'  => 'slideshow',
  'status' => 'hienthi',
  'select' => "file, name$lang, link, options"
]);
$tintuc = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi,noibat',
  'type'   => 'tin-tuc',
  'select' => "file, name$lang, desc$lang, slug$lang,updated_at",
  'limit'  => 8
]);
$feedback = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'danh-gia',
  'select' => "file, name$lang, desc$lang,content$lang"
]);
$banchay = $fn->show_data([
  'table'  => 'tbl_product',
  'status' => 'hienthi,banchay',
  'select' => "id, file, slug$lang, name$lang, sale_price, regular_price, views",
  'limit' => 10
]);
$brand = $fn->show_data([
  'table'  => 'tbl_product_brand',
  'status' => 'hienthi,noibat',
  'select' => "id,slug$lang,name$lang,file"
]);
$productList = $fn->show_data([
  'table'  => 'tbl_product_list',
  'status' => 'hienthi,noibat',
  'select' => "id, slug$lang, name$lang"
]);
$background_bc = $db->rawQueryOne("SELECT `file` FROM tbl_photo WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['background_bc'])['file'] ?? '';
// SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['trang-chu']);
$seo->set('h1', $seo_data["title$lang" ?? $optsetting["name$lang"] ?? '']);
$seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : $optsetting["name$lang"]);
$seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
$seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');
if (!empty($seo_data['options']) && ($imgJson = json_decode($seo_data['options'], true))) {
  $seo->set('photo:width', $imgJson['width'] ?? '');
  $seo->set('photo:height', $imgJson['height'] ?? '');
}
$seo->set('photo', !empty($seo_data['file']) ? $fn->getImageCustom(['file' => $seo_data['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]) : '');
