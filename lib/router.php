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


$sources = $type = '';
$requick = array(
  // Static pages
  array("tbl" => "", "source" => "index", "type" => "index", "slug" => "trang-chu"),
  array("tbl" => "", "source" => "static", "type" => "gioi-thieu", "slug" => "gioi-thieu"),
  array("tbl" => "", "source" => "static", "type" => "mua-hang", "slug" => "mua-hang"),
  array("tbl" => "", "source" => "static", "type" => "thanh-toan", "slug" => "thanh-toan"),
  array("tbl" => "", "source" => "search", "type" => "tim-kiem", "slug" => "tim-kiem"),
  array("tbl" => "", "source" => "contact", "type" => "lien-he", "slug" => "lien-he"),

  // Product routes
  array("tbl" => "", "source" => "product", "type" => "san-pham", "slug" => "san-pham"),
  array("tbl" => "product", "source" => "product", "type" => "san-pham", "field" => "id"),
  array("tbl" => "product_list", "source" => "product", "type" => "san-pham", "field" => "idl"),
  array("tbl" => "product_cat", "source" => "product", "type" => "san-pham", "field" => "idc"),
  array("tbl" => "product_item", "source" => "product", "type" => "san-pham", "field" => "idi"),
  array("tbl" => "product_sub", "source" => "product", "type" => "san-pham", "field" => "ids"),
  array("tbl" => "product_brand", "source" => "product", "type" => "san-pham", "slug" => "thuong-hieu", "field" => "idb"),

  // News routes
  array("tbl" => "news", "source" => "news", "type" => "tin-tuc", "field" => "id", "slug" => "blog", "titleMain" => "BLOG"),
  array("tbl" => "news_list", "source" => "news", "type" => "tin-tuc", "field" => "idl"),
  array("tbl" => "news", "source" => "news", "type" => "chinh-sach", "field" => "id", "slug" => "chinh-sach", "titleMain" => "Chính Sách"),
  // array("tbl" => "news", "source" => "news", "type" => "huong-dan-choi", "field" => "id", "slug" => "huong-dan-choi", "titleMain" => "Hướng Dẫn Chơi"),

  /* Order */
  array("tbl" => "", "source" => "order", "type" => "gio-hang", "slug" => "gio-hang", "titleMain" => "Giỏ hàng"),
);

foreach ($requick as $r) {
  if ($slug == '') {
    $type = $_GET['type'] = 'index';
    $_GET['titleMain'] = $titleMain = 'Trang chủ';
    $sources = "index";
    $seo->set('type', 'website');
    break;
  }

  // Match theo slug tĩnh
  if (!empty($r['slug']) && $slug == $r['slug']) {
    $type = $_GET['type'] = $r['type'] ?? '';
    $_GET['titleMain'] = $titleMain = $r['titleMain'] ?? '';
    $sources = $r['source'];
    break;
  }

  // Match route động
  if (!empty($r['tbl'])) {
    $row = $db->rawQueryOne("SELECT id, type FROM {$db->prefix}{$r['tbl']} WHERE slug{$lang} = ? AND type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", [$slug, $r['type']]);
    if ($row) {
      $_GET[$r['field']] = $row['id'];
      $type = $_GET['type'] = $row['type'];
      $_GET['titleMain'] = $titleMain = $r['titleMain'] ?? '';
      $sources = $r['source'];
      break;
    }
  }
}

// Xử lý chung theo $type
switch ($type) {
  case 'index':
    $template = "index/index";
    break;

  case 'lien-he':
    $template = "contact/contact";
    $titleMain = lienhe;
    break;

  case 'san-pham':
    $template = isset($_GET['id']) ? "product/product_detail" : "product/product";
    $titleMain = $titleMain ?: sanpham;
    break;

  case 'gioi-thieu':
    $template = "static/static";
    $titleMain = $titleMain ?: gioithieu;
    break;

  case 'mua-hang':
    $template = "static/static";
    $titleMain = $titleMain ?: "Hướng dẫn mua hàng";
    break;

  case 'thanh-toan':
    $template = "static/static";
    $titleMain = $titleMain ?: "Phương thức thanh toán";
    break;

  case 'chinh-sach':
    $template = isset($_GET['id']) ? "news/news_detail" : "news/news";
    $seo->set('type', 'article');
    $titleMain = "Chính Sách";
    break;

  case 'tin-tuc':
    if (isset($_GET['id'])) {
      $template = "news/news_detail";
    } elseif (isset($_GET['idl'])) {
      $template = "news/news_list";
    } else {
      $template = "news/news";
    }
    $seo->set('type', 'article');
    $titleMain = $titleMain ?: tintuc;
    break;

  case 'tim-kiem':
    $template = "search/search";
    $titleMain = "Tìm kiếm";
    break;

  case 'gio-hang':
    $source = "order";
    $template = 'order/order';
    $titleMain = giohang;
    $seo->set('type', 'object');
    break;

  default:
    $template = $sources . "/" . $sources;
    $fn->abort_404();
    break;
}


// Nếu không tìm thấy route nào phù hợp
if (!$sources) {
  $sources = '404';
}
