<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) $fn->abort_404();

// Lấy danh mục cấp 2 và thông tin cấp 1
$dm_c2 = $db->rawQueryOne("
  SELECT c2.*, c1.name$lang AS name_lv1, c1.slug$lang AS slug_lv1
  FROM tbl_product_cat c2
  JOIN tbl_product_list c1 ON c2.id_list = c1.id
  WHERE c2.slug$lang = ?
  LIMIT 1
", [$slug]);

if (!$dm_c2) $fn->abort_404();

// Tạo thông tin danh mục cấp 1
$dm_c1 = [
  "name$lang" => $dm_c2["name_lv1"] ?? '',
  "slug$lang" => $dm_c2["slug_lv1"] ?? ''
];

// Lấy danh sách các danh mục cấp 2 cùng cấp 1
$id_list = (int)$dm_c2['id_list'];
$list_danhmuc_c2 = $fn->show_data([
  'table' => 'tbl_product_cat',
  'status' => 'hienthi',
  'id_list' => $id_list,
  'select' => "id, name$lang, slug$lang"
]);

// Phân trang
$curPage = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;

$options = [
  'table' => 'tbl_product',
  'status' => 'hienthi',
  'id_cat' => $dm_c2['id'],
  'select' => "id, name$lang, slug$lang, file, regular_price, sale_price, views",
  'pagination' => [$perPage, $curPage]
];

$total = $fn->count_data($options);
$show_sanpham = $fn->show_data($options);
$paging = $fn->pagination_tc($total, $perPage, $curPage);

// SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ? AND `act` = ? LIMIT 1", array($dm_c2['id'], 'sanpham', 'man_cat'));

$seo->set('h1', $dm_c2["name$lang"]);
$seo->set('title', $seo_data["title$lang"] ?? $dm_c2["name$lang"]);
if (!empty($seo_data["keywords$lang"])) $seo->set('keywords', $seo_data["keywords$lang"]);
if (!empty($seo_data["description$lang"])) $seo->set('description', $seo_data["description$lang"]);

$imgJson = (!empty($seo_data['options'])) ? json_decode($seo_data['options'], true) : null;
if (!empty($imgJson)) {
  $seo->set('photo:width', $imgJson['width']);
  $seo->set('photo:height', $imgJson['height']);
  // $seo->set('photo:type', $imgJson['m']);
}
if (!empty($dm_c2['file'])) {
  $seo->set('photo', $fn->getImageCustom(['file' => $dm_c2['file'], 'width' => 600, 'height' => 315, 'zc' => 2, 'src_only' => true]));
}

// Breadcrumbs
$breadcrumbs->set('san-pham', 'Sản phẩm');
$breadcrumbs->set($dm_c1["slug$lang"], $dm_c1["name$lang"]);
$breadcrumbs->set($dm_c2["slug$lang"], $dm_c2["name$lang"]);
$breadcrumbs = $breadcrumbs->get();
