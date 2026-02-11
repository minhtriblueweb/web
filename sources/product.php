<?php
if (!defined('SOURCES')) die("Error");

@$id = htmlspecialchars($_GET['id']);
@$idl = htmlspecialchars($_GET['idl']);
@$idc = htmlspecialchars($_GET['idc']);
@$idi = htmlspecialchars($_GET['idi']);
@$ids = htmlspecialchars($_GET['ids']);
@$idb = htmlspecialchars($_GET['idb']);

if ($id != '') {
  /* Lấy sản phẩm detail */
  $rowDetail = $db->rawQueryOne("select type, id, name$lang, slug$lang, desc$lang, content$lang, code, views, id_brand, id_list, id_cat, id_item, id_sub, file, discount, sale_price, regular_price from tbl_product where id = ? and type = ? and find_in_set('hienthi',status) limit 0,1", array($id, $type));

  /* Cập nhật lượt xem */
  $fn->update_views('tbl_product', $rowDetail["slug$lang"], $lang);

  /* Lấy cấp 1 */
  $productList = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_product_list where id = ? and type = ? and find_in_set('hienthi',status) limit 0,1", array($rowDetail['id_list'], $type));

  /* Lấy cấp 2 */
  $productCat = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_product_cat where id = ? and type = ? and find_in_set('hienthi',status) limit 0,1", array($rowDetail['id_cat'], $type));

  /* Lấy cấp 3 */
  $productItem = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_product_item where id = ? and type = ? and find_in_set('hienthi',status) limit 0,1", array($rowDetail['id_item'], $type));

  /* Lấy cấp 4 */
  $productSub = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_product_sub where id = ? and type = ? and find_in_set('hienthi',status) limit 0,1", array($rowDetail['id_sub'], $type));

  /* Lấy thương hiệu */
  $productBrand = $db->rawQueryOne("select id, name$lang, slug$lang, file from tbl_product_brand where id = ? and type = ? and find_in_set('hienthi',status)", array($rowDetail['id_brand'], $type));

  /* Lấy sản phẩm cùng loại */
  $where = "";
  $where = "id <> ? and id_list = ? and type = ? and find_in_set('hienthi',status)";
  $params = array($id, $rowDetail['id_list'], $type);
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 8;
  $startpoint = ($curPage * $perPage) - $perPage;
  // $limit = " limit " . $startpoint . "," . $perPage;
  $limit = " limit 10";
  $sql = "select id, file, name$lang, slug$lang, sale_price, regular_price, discount from tbl_product where $where order by numb,id desc $limit";
  $product = $db->rawQuery($sql, $params);
  $sqlNum = "select count(*) as 'num' from tbl_product where $where order by numb,id desc";
  $count = $db->rawQueryOne($sqlNum, $params);
  $total = (!empty($count)) ? $count['num'] : 0;
  $paging = $fn->pagination_tc($total, $perPage, $curPage);

  // Hình ảnh con
  $rowDetailPhoto = $db->rawQuery("select file,name from tbl_gallery where id_parent = ? and type = ? and find_in_set('hienthi',status) order by numb,id desc", array($rowDetail['id'], $type));

  // Thông tin tĩnh
  $khuyenmai = $db->rawQueryOne("SELECT name$lang,content$lang FROM `tbl_static` WHERE type = ? AND FIND_IN_SET(?, status) LIMIT 1", ['khuyen-mai', 'hienthi']) ?? [];
  $camket = $db->rawQueryOne("SELECT name$lang,content$lang FROM `tbl_static` WHERE type = ? AND FIND_IN_SET(?, status) LIMIT 1", ['cam-ket', 'hienthi']) ?? [];
  $muahang = $db->rawQueryOne("SELECT name$lang,content$lang FROM `tbl_static` WHERE type = ? AND FIND_IN_SET(?, status) LIMIT 1", ['mua-hang', 'hienthi']) ?? [];
  $thanhtoan = $db->rawQueryOne("SELECT name$lang,content$lang FROM `tbl_static` WHERE type = ? AND FIND_IN_SET(?, status) LIMIT 1", ['thanh-toan', 'hienthi']) ?? [];

  //SEO
  $seo_data = $db->rawQueryOne("SELECT * FROM `tbl_seo` WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$id, $type, 'man']);
  $seo->set('h1', $rowDetail["name$lang"]);
  $seo->set('title', $seo_data["title$lang"] ?? $rowDetail["name$lang"]);
  $seo->set('keywords', $seo_data["keywords$lang"] ?? '');
  $seo->set('description', $seo_data["description$lang"] ?? '');

  if (!empty($seo_data['options']) && ($imgJson = json_decode($seo_data['options'], true))) {
    $seo->set('photo:width', $imgJson['width'] ?? '');
    $seo->set('photo:height', $imgJson['height'] ?? '');
  }
  if (!empty($rowDetail['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $rowDetail['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  $seo->set('photo', !empty($rowDetail['file']) ? $fn->getImageCustom(['file' => $rowDetail['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]) : '');

  /* breadCrumbs */
  if (!empty($titleMain)) $breadcr->set($type, $titleMain);
  if (!empty($productList)) $breadcr->set($productList["slug$lang"], $productList["name$lang"]);
  if (!empty($productCat)) $breadcr->set($productCat["slug$lang"], $productCat["name$lang"]);
  if (!empty($productItem)) $breadcr->set($productItem["slug$lang"], $productItem["name$lang"]);
  if (!empty($productSub)) $breadcr->set($productSub["slug$lang"], $productSub["name$lang"]);
  $breadcr->set($rowDetail["slug$lang"], $rowDetail["name$lang"]);
  $breadcrumbs = $breadcr->get();
} else if ($idl != '') {
  /* Lấy cấp 1 detail */
  $productList = $db->rawQueryOne("select id, name{$lang}, slug{$lang}, content{$lang} from tbl_product_list where id = ? and type = ? and find_in_set('hienthi',status) limit 0,1", array($idl, $type));

  /* Lấy cấp 2 detail */
  $productCat = $db->rawQuery("select id, name{$lang}, slug{$lang} from tbl_product_cat where id_list = ? and type = ? and find_in_set('hienthi',status) order by numb,id desc", array($idl, $type));

  /* Lấy sản phẩm */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 10;
  $where = "";
  $where = "id_list = ? and type = ? and find_in_set('hienthi',status)";
  $params = array($idl, $type);
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select id, name$lang, slug$lang, file, regular_price, sale_price, views,discount from tbl_product where $where order by numb,id desc $limit";
  $category = $db->rawQuery("select name$lang, slug$lang ,file, id from tbl_product_cat where type = ? and find_in_set('hienthi',status) order by numb,id desc", array('san-pham'));
  $product = $db->rawQuery($sql, $params);
  $sqlNum = "select count(*) as 'num' from tbl_product where $where order by numb,id desc";
  $count = $db->rawQueryOne($sqlNum, $params);
  $total = (!empty($count)) ? $count['num'] : 0;
  $paging = $fn->pagination_tc($total, $perPage, $curPage);

  /* SEO */
  $titleCate = $productList["name$lang"] ?? [];
  $contentCate = $productList["content$lang"] ?? [];
  $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$idl, $type, 'man_list']);
  $seo->set('h1', $productList["name$lang"]);
  $seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : ($productList["name$lang"] ?? ''));
  $seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
  $seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');

  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($productList['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $productList['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  /* breadCrumbs */
  if (!empty($titleMain)) $breadcr->set($type, $titleMain);
  if (!empty($productList)) $breadcr->set($productList["slug$lang"], $productList["name$lang"]);
  $breadcrumbs = $breadcr->get();
} else if ($idc != '') {

  // 1. lấy cat detail
  $productCat = $db->rawQueryOne("select id,id_list,name{$lang},content{$lang},slug{$lang},type,file from tbl_product_cat where id = ? and type = ? and find_in_set('hienthi',status) limit 1", [$idc, $type]);

  // 2. lấy list từ id_list
  $productList = $db->rawQueryOne("select id,name{$lang},slug{$lang} from tbl_product_list where id = ? and type = ? and find_in_set('hienthi',status) limit 1", [$productCat['id_list'], $type]);

  // 3. lấy toàn bộ cat cùng list
  $productCat_All = $db->rawQuery("select id,name{$lang},slug{$lang} from tbl_product_cat where type = ? and id_list = ? and find_in_set('hienthi',status) order by numb,id desc", [$type, $productCat['id_list']]);

  /* Lấy sản phẩm */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 10;
  $where = "";
  $where = "id_cat = ? and type = ? and find_in_set('hienthi',status)";
  $params = array($idc, $type);
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select id, name$lang, slug$lang, file, regular_price, sale_price, views,discount from tbl_product where $where order by numb,id desc $limit";
  $category = $db->rawQuery("select name$lang, slug$lang ,file, id from tbl_product_item where type = ? and find_in_set('hienthi',status) order by numb,id desc", array('san-pham'));
  $product = $db->rawQuery($sql, $params);
  $sqlNum = "select count(*) as 'num' from tbl_product where $where order by numb,id desc";
  $count = $db->rawQueryOne($sqlNum, $params);
  $total = (!empty($count)) ? $count['num'] : 0;
  $paging = $fn->pagination_tc($total, $perPage, $curPage);

  /* SEO */
  $titleCate = $productCat["name$lang"];
  $contentCate = $productCat["content$lang"];
  $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$idc, $type, 'man_cat']);
  $seo->set('h1', $productCat["name$lang"]);
  $seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : ($productCat["name$lang"] ?? ''));
  $seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
  $seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');
  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($productCat['file'])) $seo->set('photo', $fn->getImageCustom(['file' => $productCat['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  /* breadCrumbs */
  if (!empty($titleMain)) $breadcr->set($type, $titleMain);
  if (!empty($productList)) $breadcr->set($productList["slug$lang"], $productList["name$lang"]);
  if (!empty($productCat)) $breadcr->set($productCat["slug$lang"], $productCat["name$lang"]);
  $breadcrumbs = $breadcr->get();
} else if ($idi != '') {

  /* Lấy cấp 3 detail */
  $productItem = $db->rawQueryOne("select id, id_list, id_cat, name$lang,content$lang, slug$lang, type, file from tbl_product_item where id = ? and type = ? limit 0,1", array($idi, $type));

  /* Lấy cấp 1 */
  $productList = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_product_list where id = ? and type = ? limit 0,1", array($productItem['id_list'], $type));

  /* Lấy cấp 2 */
  $productCat = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_product_cat where id_list = ? and id = ? and type = ? limit 0,1", array($productItem['id_list'], $productItem['id_cat'], $type));

  /* Lấy sản phẩm */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 10;
  $where = "";
  $where = "id_item = ? and type = ? and find_in_set('hienthi',status)";
  $params = array($idi, $type);
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select id, name$lang, slug$lang, file, regular_price, sale_price, views,discount from tbl_product where $where order by numb,id desc $limit";
  $category = $db->rawQuery("select name$lang, slug$lang, file, id from tbl_product_sub where type = ? and find_in_set('hienthi',status) order by numb,id desc", array('san-pham'));
  $product = $db->rawQuery($sql, $params);
  $sqlNum = "select count(*) as 'num' from tbl_product where $where order by numb,id desc";
  $count = $db->rawQueryOne($sqlNum, $params);
  $total = (!empty($count)) ? $count['num'] : 0;
  $paging = $fn->pagination_tc($total, $perPage, $curPage);

  /* SEO */
  $titleCate = $productItem["name$lang"] ?? [];
  $contentCate = $productItem["content$lang"] ?? [];
  $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$idi, $type, 'man_item']);
  $seo->set('h1', $productItem["name$lang"]);
  $seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : ($productItem["name$lang"] ?? ''));
  $seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
  $seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');
  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($productItem['file'])) $seo->set('photo', $fn->getImageCustom(['file' => $productItem['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  /* breadCrumbs */
  if (!empty($titleMain)) $breadcr->set($type, $titleMain);
  if (!empty($productList)) $breadcr->set($productList["slug$lang"], $productList["name$lang"]);
  if (!empty($productCat)) $breadcr->set($productCat["slug$lang"], $productCat["name$lang"]);
  if (!empty($productItem)) $breadcr->set($productItem["slug$lang"], $productItem["name$lang"]);
  $breadcrumbs = $breadcr->get();
  $breadcrumbs =  $breadcr->get();
} else if ($ids != '') {

  /* Lấy cấp 4 */
  $productSub = $db->rawQueryOne("select id, id_list, id_cat, id_item, name$lang,content$lang, slug$lang, type, file from tbl_product_sub where id = ? and type = ? limit 0,1", array($ids, $type));

  /* Lấy cấp 1 */
  $productList = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_product_list where id = ? and type = ? limit 0,1", array($productSub['id_list'], $type));

  /* Lấy cấp 2 */
  $productCat = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_product_cat where id_list = ? and id = ? and type = ? limit 0,1", array($productSub['id_list'], $productSub['id_cat'], $type));

  /* Lấy cấp 3 */
  $productItem = $db->rawQueryOne("select id, name$lang, slug$lang from tbl_product_item where id_list = ? and id_cat = ? and id = ? and type = ? limit 0,1", array($productSub['id_list'], $productSub['id_cat'], $productSub['id_item'], $type));

  /* Lấy sản phẩm */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 10;
  $where = "";
  $where = "id_sub = ? and type = ? and find_in_set('hienthi',status)";
  $params = array($ids, $type);
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select id, name$lang, slug$lang, file, regular_price, sale_price, views,discount from tbl_product where $where order by numb,id desc $limit";
  $product = $db->rawQuery($sql, $params);
  $sqlNum = "select count(*) as 'num' from tbl_product where $where order by numb,id desc";
  $count = $db->rawQueryOne($sqlNum, $params);
  $total = (!empty($count)) ? $count['num'] : 0;
  $paging = $fn->pagination($total, $perPage, $curPage);

  /* SEO */
  $titleCate = $productSub["name$lang"] ?? [];
  $contentCate = $productSub["content$lang"] ?? [];
  $seo_data = $db->rawQueryOne("SELECT * FROM `tbl_seo` WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$ids, $type, 'man_sub']);
  $seo->set('h1', $productSub["name$lang"]);
  $seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : ($productSub["name$lang"] ?? ''));
  $seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
  $seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');
  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($productSub['file'])) $seo->set('photo', $fn->getImageCustom(['file' => $productSub['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  /* breadCrumbs */
  if (!empty($titleMain)) $breadcr->set($type, $titleMain);
  if (!empty($productList)) $breadcr->set($productList["slug$lang"], $productList["name$lang"]);
  if (!empty($productCat)) $breadcr->set($productCat["slug$lang"], $productCat["name$lang"]);
  if (!empty($productItem)) $breadcr->set($productItem["slug$lang"], $productItem["name$lang"]);
  if (!empty($productSub)) $breadcr->set($productSub["slug$lang"], $productSub["name$lang"]);
  $breadcrumbs = $breadcr->get();
} else if ($idb != '') {

  /* Lấy brand detail */
  $productBrand = $db->rawQueryOne("select id,icon, name$lang, slug$lang,content$lang from tbl_product_brand where id = ? and type = ? limit 0,1", array($idb, $type));

  $brandAll = $db->rawQuery("select id, name{$lang}, slug{$lang},content{$lang},file from tbl_product_brand where find_in_set('hienthi',status) order by numb,id desc");

  /* Lấy sản phẩm */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 10;
  $where = "";
  $where = "id_brand = ? and type = ? and find_in_set('hienthi',status)";
  $params = array($productBrand['id'], $type);

  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select id, name$lang, slug$lang, file, regular_price, sale_price, views,discount from tbl_product where $where order by numb,id desc $limit";
  $product = $db->rawQuery($sql, $params);
  $sqlNum = "select count(*) as 'num' from tbl_product where $where order by numb,id desc";
  $count = $db->rawQueryOne($sqlNum, $params);
  $total = (!empty($count)) ? $count['num'] : 0;
  $paging = $fn->pagination($total, $perPage, $curPage);

  /* SEO */
  $titleCate = $productBrand["name$lang"] ?? [];
  $contentCate = $productBrand["content$lang"] ?? [];
  $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$idb, $type, 'man_brand']);
  $seo->set('h1', $productBrand["name$lang"]);
  $seo->set('title', !empty($seo_data["title$lang"]) ? $seo_data["title$lang"] : ($productBrand["name$lang"] ?? ''));
  $seo->set('keywords', !empty($seo_data["keywords$lang"]) ? $seo_data["keywords$lang"] : '');
  $seo->set('description', !empty($seo_data["description$lang"]) ? $seo_data["description$lang"] : '');
  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($productBrand['file'])) $seo->set('photo', $fn->getImageCustom(['file' => $productBrand['icon'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  /* breadCrumbs */
  $breadcr->set($productBrand["slug$lang"], $titleCate);
  $breadcrumbs =  $breadcr->get();
} else {

  /* Lấy cấp 1 */
  $productList = $db->rawQuery("select id, name$lang, slug$lang from tbl_product_list order by numb,id desc");

  /* Lấy tất cả sản phẩm */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 8;
  $where = "";
  $where = "type = ? and find_in_set('hienthi',status)";
  $params = array($type);
  $startpoint = ($curPage * $perPage) - $perPage;
  $limit = " limit " . $startpoint . "," . $perPage;
  $sql = "select id, name$lang, slug$lang, file, regular_price, sale_price, views, discount from tbl_product where $where order by numb,id desc $limit";
  $product = $db->rawQuery($sql, $params);
  $sqlNum = "select count(*) as 'num' from tbl_product where $where order by numb,id desc";
  $count = $db->rawQueryOne($sqlNum, $params);
  $total = (!empty($count)) ? $count['num'] : 0;
  $paging = $fn->pagination_tc($total, $perPage, $curPage);

  /* SEO */
  $seo_data = $db->rawQueryOne("SELECT * FROM `tbl_seopage` WHERE `type` = ? LIMIT 0,1", [$type]);
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
  $breadcrumbs = $breadcr->get();
}
