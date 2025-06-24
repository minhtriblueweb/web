<?php
$slug = $_GET['slug'] ?? '';
$type = $_GET['type'] ?? '';
$typeInfo = $fn->convert_type($type);
if (empty($slug) || empty($type)) {
  http_response_code(404);
  include '404.php';
  exit();
}
$baiviet = $fn->get_only_data([
  'table' => 'tbl_news',
  'slugvi' => $slug,
  'type' => $type,
  'status' => 'hienthi'
]);
if (!$baiviet) {
  http_response_code(404);
  include '404.php';
  exit();
}
$relatedNews = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'exclude_id' => $baiviet['id'],
  'type' => $type
]);


$seo['title'] = !empty($baiviet['titlevi']) ? $baiviet['titlevi'] : $baiviet['namevi'];
$seo['keywords'] = $baiviet['keywordsvi'];
$seo['description'] = $baiviet['descriptionvi'];
$seo['url'] = BASE . $baiviet['slugvi'];
$seo['image'] = BASE_ADMIN . UPLOADS . $baiviet['file'] ?? '';
