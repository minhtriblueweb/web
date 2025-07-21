<?php
$type_map = [
  'huong-dan-choi' => 'huongdanchoi',
  'chinh-sach' => 'chinhsach',
  'tin-tuc' => 'tintuc'
];

echo $current_route = $_GET['type'] ?? '';
$type = $type_map[$current_route] ?? $current_route;
if (!$type) $fn->abort_404();
if (!isset($config['news'][$type])) {
  echo "kkkkk";
}
$typeInfo = $fn->convert_type($type);
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
$show_sanpham = $fn->show_data($options);
$paging = $fn->pagination_tc($total, $perPage, $curPage);
//SEO
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seo WHERE `id_parent` = ? AND `type` = ?  LIMIT 1", [$news['id'], $type]);
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

$breadcrumbs->set($current_route, $typeInfo['vi']);
$breadcrumbs = $breadcrumbs->get();
