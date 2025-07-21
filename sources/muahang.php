<?php
$row_mh = $db->rawQueryOne("SELECT * FROM tbl_static WHERE type = ?", ['muahang']);
//SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", ['muahang']);
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

$breadcrumbs->set('mua-hang', 'Mua hàng');
$breadcrumbs = $breadcrumbs->get();
