<?php
// Lấy URI từ tham số page
$request_uri = $_GET['page'] ?? '';
$request_uri = trim($request_uri, '/');

// Tách page-x nếu có ở cuối URI
$requestParts = explode('/', $request_uri);
$current_page = 1;

if (preg_match('/^page-(\d+)$/', end($requestParts), $matches)) {
  $current_page = (int)$matches[1];
  array_pop($requestParts); // Bỏ 'page-x' ra khỏi mảng
}
$slug = implode('/', $requestParts);

// Gán lại vào $_GET để dùng cho phân trang
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

// ===== Xử lý định tuyến =====
$page = '404.php'; // Mặc định là lỗi

if (isset($routes[$slug])) {
  // Route tĩnh
  $page = $routes[$slug];
} elseif ($slug === 'tim-kiem') {
  $page = 'search.php';
}elseif ($slug !== '') {
  // Danh mục cấp 1
  if ($danhmuc->slug_exists_lv1($slug)) {
    $_GET['slug'] = $slug;
    $page = 'product_list_lv1.php';
  }
  // Danh mục cấp 2
  elseif ($info_lv2 = $danhmuc->find_lv2_with_parent($slug)) {
    $_GET['slug'] = $info_lv2['slugvi'];
    $_GET['slug_lv1'] = $info_lv2['slug_lv1'];
    $page = 'product_list_lv2.php';
  }
  // Bài viết
  elseif ($newsData = $fn->get_only_data([
    'table' => 'tbl_news',
    'slugvi' => $slug,
    'status' => 'hienthi'
  ])) {
    $_GET['slug'] = $slug;
    $_GET['type'] = $newsData['type'];
    $page = 'news.php';
  }
  // Chi tiết sản phẩm
  elseif ($productData = $fn->get_only_data([
    'table' => 'tbl_sanpham',
    'slugvi' => $slug,
    'status' => 'hienthi'
  ])) {
    $_GET['slug'] = $slug;
    $page = 'product_details.php';
  }
}
