<?php
if (!defined('SOURCES')) die("Error");

@$id = htmlspecialchars($_GET['id']);
@$type = htmlspecialchars($_GET['type']);
@$titleMain = htmlspecialchars($_GET['titleMain']);
if ($id != '') {
  $rowDetail = $fn->show_data([
    'table'  => 'tbl_news',
    'type'   => $type,
    'status' => 'hienthi',
    'id' => $id,
    'select' => "id, file, name{$lang}, slug{$lang},content{$lang},slug{$lang}",
    'limit' => 1
  ]);

  // Tin liên quan
  $relatedNews = $fn->show_data([
    'table'      => 'tbl_news',
    'status'     => 'hienthi',
    'type'       => $type,
    'exclude_id' =>  $rowDetail['id'],
    'select'     => "id, name{$lang}, slug{$lang}, file"
  ]);

  /* SEO */
  $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? LIMIT 0,1", [$id, $type]);
  $seo->set('h1', $rowDetail["name$lang"]);
  $seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : $rowDetail["name$lang"]);
  if (!empty($seo_data["keywords$lang"])) $seo->set('keywords', $seo_data["keywords$lang"]);
  if (!empty($seo_data["description$lang"])) $seo->set('description', $seo_data["description$lang"]);
  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($rowDetail['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $rowDetail['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));
  /* breadCrumbs */
  $breadcr->set($type, $titleMain);
  $breadcr->set($rowDetail["slug$lang"], $rowDetail["name$lang"]);
  $breadcrumbs = $breadcr->get();
  include TEMPLATE . $template . ".php";
} else {

  /* Trang danh sách  */

  // Lấy dữ liệu
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 10;

  $options = [
    'table' => 'tbl_news',
    'status' => 'hienthi',
    'type' => $type,
    'select' => "id, file, slug{$lang}, name{$lang}, desc{$lang}",
    'pagination'  => [$perPage, $curPage]
  ];

  $total = $fn->count_data($options);
  $show_data = $fn->show_data($options);
  $paging = $fn->pagination_tc($total, $perPage, $curPage);
  /* SEO */
  $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE `type` = ? LIMIT 0,1", [$type]);
  $seo->set('h1', $titleMain);
  if (!empty($seo_data["title$lang"])) $seo->set('title', $seo_data["title$lang"]);
  if (!empty($seo_data["keywords$lang"])) $seo->set('keywords', $seo_data["keywords$lang"]);
  if (!empty($seo_data["description$lang"])) $seo->set('description', $seo_data["description$lang"]);
  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($seo_data['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $seo_data['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));


  /* breadCrumbs */
  $breadcr->set($slug, $titleMain);
  $breadcrumbs =  $breadcr->get();
  include TEMPLATE . $template . ".php";
}
