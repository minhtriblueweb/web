<?php
if (!defined('SOURCES')) die("Error");

$slider = $cache->get("select name$lang, photo, link from #_photo where type = ? and find_in_set('hienthi',status) order by numb,id desc", array('slide'), 'result', 7200);
$slider_about = $cache->get("select name$lang, photo, link from #_photo where type = ? and find_in_set('hienthi',status) order by numb,id desc", array('slide-about'), 'result', 7200);
$about = $cache->get("select name$lang,nametop$lang,namebot$lang,desc$lang,photo,icon from #_static where type = ? and find_in_set('hienthi',status) limit 0,1", array('gioi-thieu'), 'fetch', 7200);
$slideinfo = $cache->get("select nametop$lang,namebot$lang,desc$lang from #_static where type = ? and find_in_set('hienthi',status) limit 0,1", array('slide-info'), 'fetch', 7200);
$criteria = $cache->get("select name$lang, slugvi, slugen, id, photo, desc$lang from #_news where type = ? and find_in_set('hienthi',status) order by numb,id desc", array('tieu-chi'), 'result', 7200);
$service_list_nb = $cache->get("select name$lang, id from #_news_list where type = ? and find_in_set('noibat',status) and find_in_set('hienthi',status) order by numb,id desc", array('dich-vu'), 'result', 7200);
$project = $cache->get("select name$lang, slugvi, slugen, id, photo, desc$lang, type from #_news where type = ? and find_in_set('hienthi',status) and find_in_set('noibat',status) order by numb,id desc", array('du-an'), 'result', 7200);
$news = $cache->get("select name$lang, slugvi, slugen, desc$lang, date_created, id, photo,type from #_news where type = ? and find_in_set('noibat',status) and find_in_set('hienthi',status) order by numb,id desc", array('tin-tuc'), 'result', 7200);
/* SEO */
$seoDB = $seo->getOnDB(0, 'setting', 'update', 'setting');
if (!empty($seoDB['title' . $seolang])) $seo->set('h1', $seoDB['title' . $seolang]);
if (!empty($seoDB['title' . $seolang])) $seo->set('title', $seoDB['title' . $seolang]);
if (!empty($seoDB['keywords' . $seolang])) $seo->set('keywords', $seoDB['keywords' . $seolang]);
if (!empty($seoDB['description' . $seolang])) $seo->set('description', $seoDB['description' . $seolang]);
$seo->set('url', $func->getPageURL());
$imgJson = (!empty($logo['options'])) ? json_decode($logo['options'], true) : null;
if (empty($imgJson) || ($imgJson['p'] != $logo['photo'])) {
    $imgJson = $func->getImgSize($logo['photo'], UPLOAD_PHOTO_L . $logo['photo']);
    $seo->updateSeoDB(json_encode($imgJson), 'photo', $logo['id']);
}
if (!empty($imgJson)) {
    $seo->set('photo', $configBase . THUMBS . '/' . $imgJson['w'] . 'x' . $imgJson['h'] . 'x2/' . UPLOAD_PHOTO_L . $logo['photo']);
    $seo->set('photo:width', $imgJson['w']);
    $seo->set('photo:height', $imgJson['h']);
    $seo->set('photo:type', $imgJson['m']);
}
