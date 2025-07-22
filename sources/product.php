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
  $rowDetail = $fn->show_data(['table' => 'tbl_product', 'status' => 'hienthi', 'type' => $type, 'id' => $id, 'limit' => 1]);

  /* Cập nhật lượt xem */
  $fn->update_views('tbl_product', $rowDetail["slug$lang"], $lang);

  /* Lấy cấp 1 */
  $productList = $fn->show_data(['table' => 'tbl_product_list', 'status' => 'hienthi', 'type' => $type, 'id' => $rowDetail['id_list'], 'limit' => 1, 'select' => "id, name{$lang}, slug{$lang}"]);
  /* Lấy cấp 2 */
  $productCat = $fn->show_data(['table' => 'tbl_product_cat', 'status' => 'hienthi', 'type' => $type, 'id' => $rowDetail['id_cat'], 'limit' => 1, 'select' => "id, name{$lang}, slug{$lang}"]);

  /* Lấy sản phẩm cùng loại */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 20;
  $options = ['table' => 'tbl_product', 'status' => 'hienthi', 'type' => $type, 'exclude_id' => $id, 'select' => "id,file, name{$lang}, slug{$lang}, regular_price, sale_price, views", 'pagination' => [$perPage, $curPage]];
  $product = $fn->show_data($options);
  $total_product = $fn->count_data($options);
  $paging = $fn->pagination_tc($total_product, $perPage, $curPage);


  // Hình ảnh con
  $rowDetailPhoto = $fn->show_data(['table' => 'tbl_gallery', 'status' => 'hienthi', 'id_parent' => $id, 'select' => "file"]);

  //SEO
  $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$id, 'san-pham', 'man']);
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
  $breadcr->set('san-pham', sanpham);
  if (!empty($productList["slug$lang"]) && !empty($productList["name$lang"])) {
    $breadcr->set($productList["slug$lang"], $productList["name$lang"]);
  }
  if (!empty($productCat["slug$lang"]) && !empty($productCat["name$lang"])) {
    $breadcr->set($productCat["slug$lang"], $productCat["name$lang"]);
  }
  $breadcr->set($rowDetail["slug$lang"], $rowDetail["name$lang"]);
  $breadcrumbs =  $breadcr->get();
  include TEMPLATE . "product/product_details.php";
} else if ($idl != '') {
  /* Lấy cấp 1 detail */
  $productList = $fn->show_data(['table' => 'tbl_product_list', 'status' => 'hienthi', 'type' => $type, 'id' => $idl, 'limit' => 1, 'select' => "id, name{$lang}, slug{$lang}, content{$lang}"]);
  /* Lấy cấp 2 detail */
  $productCat = $fn->show_data(['table' => 'tbl_product_cat', 'status' => 'hienthi', 'type' => $type, 'id_list' => $idl, 'select' => "id, name{$lang}, slug{$lang}"]);
  /* Lấy sản phẩm */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 10;
  $options = ['table' => 'tbl_product', 'status' => 'hienthi', 'type' => $type, 'id_list' => $idl, 'select' => "id, name{$lang}, slug{$lang}, file, regular_price, sale_price, views", 'pagination'  => [$perPage, $curPage]];
  $total = $fn->count_data($options);
  $show_sanpham = $fn->show_data($options);
  $paging = $fn->pagination_tc($total, $perPage, $curPage);

  /* SEO */
  $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$id, 'san-pham', 'man_list']);
  $seo->set('h1', $productList["name$lang"]);
  if (!empty($seo_data["title$lang"])) $seo->set('title', $seo_data["title$lang"]);
  if (!empty($seo_data["keywords$lang"])) $seo->set('keywords', $seo_data["keywords$lang"]);
  if (!empty($seo_data["description$lang"])) $seo->set('description', $seo_data["description$lang"]);
  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($productList['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $productList['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  /* breadCrumbs */
  $breadcr->set('san-pham', sanpham);
  $breadcr->set($productList["slug$lang"], $productList["name$lang"]);
  $breadcrumbs =  $breadcr->get();
  include TEMPLATE . "product/product_list.php";
} else if ($idc != '') {
  /* Lấy cấp 2 */
  $productCat = $fn->show_data(['table' => 'tbl_product_cat', 'status' => 'hienthi', 'type' => $type, 'id' => $idc, 'limit' => 1, 'select' => "id, id_list, name{$lang}, slug{$lang}, content{$lang}"]);
  /* Lấy cấp 1 từ id_list */
  $productList = $fn->show_data(['table' => 'tbl_product_list', 'status' => 'hienthi', 'type' => $type, 'id' => $productCat['id_list'], 'limit' => 1, 'select' => "id, name{$lang}, slug{$lang}"]);
  /* Lấy tất cả cấp 2 thuộc cấp 1 */
  $productCat_All = $fn->show_data(['table' => 'tbl_product_cat', 'status' => 'hienthi', 'type' => $type, 'id_list' => $productCat['id_list'], 'select' => "id, name{$lang}, slug{$lang}"]);

  /* Lấy sản phẩm */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 10;
  $options = ['table' => 'tbl_product', 'status' => 'hienthi', 'type' => $type, 'id_cat' => $idc, 'select' => "id, name{$lang}, slug{$lang}, file, regular_price, sale_price, views", 'pagination'  => [$perPage, $curPage]];
  $total = $fn->count_data($options);
  $product = $fn->show_data($options);
  $paging = $fn->pagination_tc($total, $perPage, $curPage);

  //SEO
  $seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 0,1", [$id, 'san-pham', 'man_list']);
  $seo->set('h1', $productList["name$lang"]);
  if (!empty($seo_data["title$lang"])) $seo->set('title', $seo_data["title$lang"]);
  if (!empty($seo_data["keywords$lang"])) $seo->set('keywords', $seo_data["keywords$lang"]);
  if (!empty($seo_data["description$lang"])) $seo->set('description', $seo_data["description$lang"]);
  $imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
  if (!empty($imgJson)) {
    $seo->set('photo:width', $imgJson['width']);
    $seo->set('photo:height', $imgJson['height']);
  }
  if (!empty($productList['file'])) $seo->set('photo',  $fn->getImageCustom(['file' => $productList['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));

  /* breadCrumbs */
  $breadcr->set('san-pham', sanpham);
  $breadcr->set($productList["slug$lang"], $productList["name$lang"]);
  $breadcr->set($productCat["slug$lang"], $productCat["name$lang"]);
  $breadcrumbs =  $breadcr->get();
  include TEMPLATE . "product/product_cat.php";
} else {
  /* Lấy sản phẩm */
  $curPage =  max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
  $perPage = 20;
  $options = ['table' => 'tbl_product', 'status' => 'hienthi', 'select' => "id, name{$lang}, slug{$lang}, file, regular_price, sale_price, views", 'pagination'  => [$perPage, $curPage]];
  $options_list = ['table' => 'tbl_product_list', 'status' => 'hienthi', 'select' => " name{$lang}, slug{$lang}"];
  $total = $fn->count_data($options);
  $product = $fn->show_data($options);
  $product_list = $fn->show_data($options_list);
  $paging = $fn->pagination_tc($total, $perPage, $curPage);

  //SEO
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
