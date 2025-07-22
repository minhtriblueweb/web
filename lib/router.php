<?php
require_once "lib/lang/web/$lang.php";
$request_uri = trim($_GET['page'] ?? '', '/');
$requestParts = explode('/', $request_uri);
$current_page = 1;
// Phân trang dạng page-x
if (preg_match('/^page-(\d+)$/', end($requestParts), $matches)) {
  $current_page = (int)$matches[1];
  array_pop($requestParts);
}
$slug = implode('/', $requestParts);
$_GET['page'] = $current_page;

// Khởi tạo
$sources = '';
$requick = [
  // Static pages
  ['tbl' => '', 'source' => 'index', 'type' => 'index', 'slug' => 'trang-chu'],
  ['tbl' => '', 'source' => 'static', 'type' => 'gioi-thieu', 'slug' => 'gioi-thieu'],
  ['tbl' => '', 'source' => 'static', 'type' => 'lien-he', 'slug' => 'lien-he'],
  ['tbl' => '', 'source' => 'static', 'type' => 'mua-hang', 'slug' => 'mua-hang'],
  ['tbl' => '', 'source' => 'search', 'type' => '', 'slug' => 'tim-kiem'],

  // Product routes
  ['tbl' => '', 'source' => 'product', 'type' => 'san-pham', 'slug' => 'san-pham'],
  ['tbl' => 'product', 'source' => 'product', 'type' => 'san-pham', 'field' => 'id'],
  ['tbl' => 'product_list', 'source' => 'product', 'type' => 'san-pham', 'field' => 'idl'],
  ['tbl' => 'product_cat', 'source' => 'product', 'type' => 'san-pham', 'field' => 'idc'],

  // News routes
  array("tbl" => "news", "source" => "news", "type" => "tin-tuc", "field" => "id", "slug" => "tin-tuc", "titleMain" => "Tin tức"),
  array("tbl" => "news", "source" => "news", "type" => "chinh-sach", "field" => "id", "slug" => "chinh-sach", "titleMain" => "Chính sách"),
  array("tbl" => "news", "source" => "news", "type" => "huong-dan-choi", "field" => "id", "slug" => "huong-dan-choi", "titleMain" => "Hướng dẫn chơi")
];

// Tìm route khớp
foreach ($requick as $r) {
  // Ưu tiên match theo slug nếu có
  if ($slug == '') {
    $type = $_GET['type'] = 'index';
    $_GET['titleMain'] = $titleMain = 'Trang chủ';
    $sources = "index";
    $template = "index/index";
    $seo->set('type', 'website');
  }
  if (!empty($r['slug']) && $slug == $r['slug']) {
    $type = $_GET['type'] = $r['type'] ?? '';
    $_GET['titleMain'] = $r['titleMain'] ?? '';
    switch ($type) {
      case 'lien-he':
        $sources = "contact";
        $template = "contact/contact";
        $seo->set('type', 'object');
        $titleMain = lienhe;
        break;

      case 'san-pham':
        $sources = "product";
        $template = isset($_GET['id']) ? "product/product_detail" : "product/product";
        $seo->set('type', isset($_GET['id']) ? "article" : "object");
        $titleMain = sanpham;
        break;

      case 'gioi-thieu':
      case 'mua-hang':
        $sources = "static";
        $template = "static/static";
        $seo->set('type', 'article');
        $titleMain = ($type == 'gioi-thieu') ? gioithieu : "Hướng dẫn mua hàng";
        break;

      case 'tin-tuc':
      case 'chinh-sach':
      case 'huong-dan-choi':
        $sources = "news";
        $template = isset($_GET['id']) ? "news/news_detail" : "news/news";
        $seo->set('type', isset($_GET['id']) ? "article" : "object");
        $titleMain = $_GET['titleMain'];
        break;
    }
    break;
  }

  // Kiểm tra theo bảng (route động)
  if (!empty($r['tbl'])) {
    $row = $db->rawQueryOne("SELECT id, type FROM tbl_{$r['tbl']} WHERE slug{$lang} = ? AND type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", [$slug, $r['type']]);
    if ($row) {
      $_GET[$r['field']] = $row['id'];
      $type = $_GET['type'] = $row['type'];
      $_GET['titleMain'] = $titleMain = $r['titleMain'] ?? '';
      $sources = $r['source'];

      // Gán template tương ứng
      switch ($type) {
        case 'san-pham':
          $template = isset($_GET['id']) ? "product/product_detail" : "product/product";
          $seo->set('type', isset($_GET['id']) ? "article" : "object");
          break;

        case 'tin-tuc':
        case 'chinh-sach':
        case 'huong-dan-choi':
          $template = isset($_GET['id']) ? "news/news_detail" : "news/news";
          $seo->set('type', isset($_GET['id']) ? "article" : "object");
          break;

        default:
          $template = $sources . "/" . $sources;
          $seo->set('type', 'object');
          break;
      }
      break;
    }
  }
}


// Nếu không tìm thấy route nào phù hợp
if (!$sources) {
  $sources = '404.php';
}
