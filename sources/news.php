<?php
$type_map = [
  'huong-dan-choi' => 'huongdanchoi',
  'chinh-sach' => 'chinhsach',
  'tin-tuc' => 'tintuc'
];

$current_route = $_GET['type'] ?? '';
$type = $type_map[$current_route] ?? $current_route;
if (!$type) $fn->abort_404();
$typeInfo = $fn->convert_type($type);
// Lấy dữ liệu
$news = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type' => $type,
  'select' => "id, file, slug{$lang}, name{$lang}, desc{$lang}"
]);
$data_seo = $seo->get_seopage(
  $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE `type` = ?", [$type]) ?? [],
  $lang
);
$breadcrumbs->set($current_route, $typeInfo['vi']);
$breadcrumbs = $breadcrumbs->get();
