<?php
if (!defined('SOURCES')) die("Error");

@$id = htmlspecialchars($_GET['id']);
@$idl = htmlspecialchars($_GET['idl']);
@$idc = htmlspecialchars($_GET['idc']);
@$idi = htmlspecialchars($_GET['idi']);
@$ids = htmlspecialchars($_GET['ids']);
@$idb = htmlspecialchars($_GET['idb']);
@$type = htmlspecialchars($_GET['type']);

if ($id != '') {

  // Trang bài viết detail

  /* Lấy bài viết detail */
  $rowDetail = $db->rawQueryOne("select id, views, created_at, id_list, id_cat, id_item, id_sub, type, name$lang, slug$lang, desc$lang, content$lang, file from tbl_news where id = ? and type = ? and find_in_set('hienthi',status) limit 0,1", array($id, $type));

  /* Lấy cấp 1 */
  $newsList = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_news_list where id = ? and type = ? and find_in_set('hienthi',status) limit 0,1", array($rowDetail['id_list'], $type));

  /* Cập nhật lượt xem */
  $fn->update_views('tbl_news', $rowDetail["slug$lang"], $lang);

  // Tin liên quan
  $where = "";
  $where = "id <> ? and type = ? and find_in_set('hienthi',status)";
  $limit = " limit 10";
  $sql = "select id, name{$lang}, slug{$lang}, file from tbl_news where $where order by numb,id desc $limit";
  $params = array($id, $type);
  $relatedNews = $db->rawQuery($sql, $params);

  $footer_news = $db->rawQueryOne("SELECT content$lang FROM `tbl_static` WHERE type = ? AND FIND_IN_SET(?, status) LIMIT 1", ['footer_news', 'hienthi']);

  $showFooterNews = !empty($db->rawQueryOne("SELECT id FROM tbl_news WHERE id = ? AND FIND_IN_SET('Footernews', status)",[$id]));

  /* SEO */
  $seo_data = $db->rawQueryOne("SELECT * FROM `tbl_seo` WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$id, $type, 'man']);
  $seo->set('h1', $rowDetail["name$lang"]);
  $seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : $rowDetail["name$lang"]);
  $seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
  $seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');

  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($rowDetail['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $rowDetail['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  /* breadCrumbs */
  // $breadcr->set($type, $titleMain);
  // if (!empty($newsList["slug$lang"]) && !empty($newsList["name$lang"])) {
  //   $breadcr->set($newsList["slug$lang"], $newsList["name$lang"]);
  // }
  // $breadcr->set($rowDetail["slug$lang"], $rowDetail["name$lang"]);
  // $breadcrumbs = $breadcr->get();

  /* breadCrumbs */
  if (!empty($titleMain)) $breadcr->set($type, $titleMain);
  if (!empty($newsList)) $breadcr->set($newsList['slug' . $lang], $newsList['name' . $lang]);
  if (!empty($newsCat)) $breadcr->set($newsCat['slug' . $lang], $newsCat['name' . $lang]);
  if (!empty($newsItem)) $breadcr->set($newsItem['slug' . $lang], $newsItem['name' . $lang]);
  if (!empty($newsSub)) $breadcr->set($newsSub['slug' . $lang], $newsSub['name' . $lang]);
  $breadcr->set($rowDetail['slug' . $lang], $rowDetail['name' . $lang]);
  $breadcrumbs = $breadcr->get();
} else if ($idl != '') {
  $where = "";
  $where = "type = ? and id_list = ? and find_in_set('hienthi',status)";
  $params = array($type,$idl);
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 6;
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select id,id_list,file, name{$lang}, slug{$lang}, desc{$lang}, updated_at from tbl_news where $where order by numb,id desc $limit";
  $news = $db->rawQuery($sql, $params);
  $sqlNum = "select count(*) as 'num' from tbl_news where $where order by numb,id desc";
  $count = $db->rawQueryOne($sqlNum, $params);
  $total = (!empty($count)) ? $count['num'] : 0;
  $paging = $fn->pagination_tc($total, $perPage, $curPage);

  /* Lấy cấp 1 */
  $newsList = $db->rawQueryOne("select id,slug{$lang},name{$lang} from tbl_news_list where type = ? and id = ? and find_in_set('hienthi',status) order by numb,id desc limit 0,1", $params);

  /* SEO */
  $titleCate = $newsList["name$lang"] ?? [];
  $contentCate = $newsList["content$lang"] ?? [];
  $seo_data = $db->rawQueryOne("SELECT * FROM `tbl_seo` WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$idl, $type, 'man_list']);
  $seo->set('h1', $newsList["name$lang"]);
  $seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : ($newsList["name$lang"] ?? ''));
  $seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
  $seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');

  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($newsList['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $newsList['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  /* breadCrumbs */
  if (!empty($titleMain)) $breadcr->set($type, $titleMain);
  if (!empty($newsList)) $breadcr->set($newsList["slug$lang"], $newsList["name$lang"]);
  $breadcrumbs = $breadcr->get();
} else {

  /* Trang danh sách  */
  $where  = "type = ? AND FIND_IN_SET('hienthi', status)";
  $params = [$type];
  $sql = "select id,id_list,file,slug{$lang},name{$lang},desc{$lang},updated_at,views from `tbl_news` where $where order by numb, id desc";
  $news = $db->rawQuery($sql, $params);

  // $news_list = $db->rawQuery("SELECT id,slug$lang, name$lang FROM tbl_news_list WHERE type = 'tin-tuc' AND FIND_IN_SET('hienthi', status) ORDER BY numb, id DESC");
  // $paging = $fn->pagination_tc($total, $perPage, $curPage);

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
  if (!empty($titleMain)) $breadcr->set($type, $titleMain);
  $breadcrumbs =  $breadcr->get();
}

