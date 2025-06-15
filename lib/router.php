<?php
$request_uri = $_GET['page'] ?? '';
$request_uri = trim($request_uri, '/');

// Tách phần page-x nếu có
$requestParts = explode('/', $request_uri);
$current_page = 1;

if (preg_match('/^page-(\d+)$/', end($requestParts), $matches)) {
  $current_page = (int)$matches[1];
  array_pop($requestParts); // bỏ page-x ra
}
$slugPath = implode('/', $requestParts);

// Gán giá trị trang hiện tại vào $_GET để dùng trong các trang khác
$_GET['page'] = $current_page;

// Các route tĩnh
$routes = [
  '' => 'home.php',
  'trang-chu' => 'home.php',
  'gioi-thieu' => 'gioithieu.php',
  'lien-he' => 'lienhe.php',
  'san-pham' => 'product_list.php',
  'mua-hang' => 'muahang.php',
  'huong-dan-choi' => 'list_huongdan.php',
  'chinh-sach' => 'list_chinhsach.php',
  'tin-tuc' => 'list_tintuc.php'
];

// Xử lý theo route
if (isset($routes[$slugPath])) {
  $page = $routes[$slugPath];
} elseif ($slugPath !== '') {
  // Danh mục cấp 1
  if ($danhmuc->slug_exists_lv1($slugPath)) {
    $_GET['slug'] = $slugPath;
    $page = 'product_list_lv1.php';
  }
  // Danh mục cấp 2 (có slug con và slug cha)
  elseif ($info_lv2 = $danhmuc->find_lv2_with_parent($slugPath)) {
    $_GET['slug'] = $info_lv2['slugvi'];
    $_GET['slug_lv1'] = $info_lv2['slug_lv1'];
    $page = 'product_list_lv2.php';
  }
  // Bài viết
  elseif ($newsData = $news->get_news_by_slug($slugPath)) {
    $_GET['slug'] = $slugPath;
    $_GET['type'] = $newsData['type'];
    $page = 'news.php';
  } elseif ($productData = $sanpham->get_sanpham_by_slug($slugPath)) {
    $_GET['slug'] = $slugPath;
    $page = 'product_details.php';
  } else {
    $page = '404.php';
  }
} else {
  $page = '404.php';
}
