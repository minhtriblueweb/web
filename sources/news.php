<?php
$slug = $_GET['slug'] ?? '';
$type = $_GET['type'] ?? '';
$typeInfo = $fn->convert_type($type);

// Kiểm tra đầu vào
if (!$slug || !$type) {
  http_response_code(404);
  include '404.php';
  exit();
}

// Lấy bài viết theo slug + type
$baiviet = $db->rawQueryOne("SELECT id, file, name{$lang}, slug{$lang},content{$lang},slug{$lang} FROM tbl_news WHERE slug{$lang} = ? AND FIND_IN_SET('hienthi', status) AND type = ?", [$slug, $type]);

if (!$baiviet) {
  http_response_code(404);
  include '404.php';
  exit();
}

// Tin liên quan
$relatedNews = $fn->show_data([
  'table'      => 'tbl_news',
  'status'     => 'hienthi',
  'type'       => $type,
  'exclude_id' => $baiviet['id'],
  'select'     => "id, name{$lang}, slug{$lang}, file"
]);

// SEO
$data_seo = $seo->get_seo($baiviet['id'], $type);
// breadcrumbs
$breadcrumbs->set($typeInfo['slug'], $typeInfo['vi']);
$breadcrumbs->set($baiviet['slugvi'], $baiviet['namevi']);
$breadcrumbs = $breadcrumbs->get();
