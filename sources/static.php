<?php
if (!defined('SOURCES')) die("Error");

/* Lấy bài viết tĩnh */
$static = $db->rawQueryOne("SELECT id, type, name$lang as name, content$lang as content, file FROM tbl_static WHERE type = ? LIMIT 1", [$type]);

//SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", array($type));
$seo->set('h1', $titleMain);
if (!empty($seo_data['title' . $lang])) $seo->set('title', $seo_data['title' . $lang]);
if (!empty($seo_data['keywords' . $lang])) $seo->set('keywords', $seo_data['keywords' . $lang]);
if (!empty($seo_data['description' . $lang])) $seo->set('description', $seo_data['description' . $lang]);
$imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
if (!empty($imgJson)) {
  $seo->set('photo:width', $imgJson['width']);
  $seo->set('photo:height', $imgJson['height']);
}
if (!empty($seo_data['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $seo_data['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

/* breadCrumbs */
if (!empty($titleMain)) $breadcr->set($slug, $titleMain);
$breadcrumbs = $breadcr->get();
