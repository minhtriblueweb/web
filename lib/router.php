<?php
$request_uri = trim($_GET['page'] ?? '', '/');

// Xử lý phân trang page-x
$requestParts = explode('/', $request_uri);
$current_page = 1;

if (preg_match('/^page-(\d+)$/', end($requestParts), $matches)) {
  $current_page = (int) $matches[1];
  array_pop($requestParts);
}
$slug = implode('/', $requestParts);
$_GET['page'] = $current_page;

// Danh sách route tĩnh
$routes = [
  ''               => 'home.php',
  'trang-chu'      => 'home.php',
  'gioi-thieu'     => 'gioithieu.php',
  'lien-he'        => 'lienhe.php',
  'san-pham'       => 'product_list.php',
  'mua-hang'       => 'muahang.php',
  'huong-dan-choi' => 'list_news.php',
  'chinh-sach'     => 'list_news.php',
  'tin-tuc'        => 'list_news.php'
];

// Mặc định là 404
$page = '404.php';

// ======== Xử lý định tuyến ========
if (isset($routes[$slug])) {
  $page = $routes[$slug];

  // Gán type cho list_news.php
  if (in_array($slug, ['huong-dan-choi', 'chinh-sach', 'tin-tuc'])) {
    $map_type = [
      'huong-dan-choi' => 'huongdanchoi',
      'chinh-sach'     => 'chinhsach',
      'tin-tuc'        => 'tintuc'
    ];
    $_GET['type'] = $map_type[$slug];
  }
} elseif ($slug === 'tim-kiem') {
  $page = 'search.php';
} elseif (!empty($slug)) {
  // Danh mục cấp 1
  if ($productList = $db->rawQueryOne("SELECT id, name$lang, slugvi, slugen FROM tbl_danhmuc_c1 WHERE slug{$lang} = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", [$slug])) {
    $_GET['slug'] = $productList['slug' . $lang];
    $page = 'product_list_lv1.php';
  }

  // Danh mục cấp 2
  elseif ($productCat = $db->rawQueryOne("SELECT id, name$lang, slugvi, slugen FROM tbl_danhmuc_c2 WHERE slug{$lang} = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", [$slug])) {
    $_GET['slug'] = $productCat['slug' . $lang];
    $page = 'product_list_lv2.php';
  }

  // Bài viết chi tiết
  elseif ($newsData = $db->rawQueryOne("SELECT * FROM tbl_news WHERE slug{$lang} = ? AND FIND_IN_SET('hienthi', status)", [$slug])) {
    $_GET['slug'] = $slug;
    $_GET['type'] = $newsData['type'];
    $page = 'news.php';
  }

  // Sản phẩm chi tiết
  elseif ($productData = $db->rawQueryOne("SELECT * FROM tbl_sanpham WHERE slug{$lang} = ? AND FIND_IN_SET('hienthi', status)", [$slug])) {
    $_GET['slug'] = $slug;
    $page = 'product_details.php';
  }
}
