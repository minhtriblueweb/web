<?php
$request = $_GET['page'] ?? '';
$request = trim($request, '/');

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

if (isset($routes[$request])) {
  $page = $routes[$request];
} elseif ($request !== '') {
  // Kiểm tra slug trong cấp 1
  if ($danhmuc->slug_exists_lv1($request)) {
    $_GET['slug'] = $request;
    $page = 'product_list_lv1.php';
  }
  // Nếu không có trong cấp 1, thử tìm trong cấp 2
  elseif ($info_lv2 = $danhmuc->find_lv2_with_parent($request)) {
    $_GET['slug'] = $info_lv2['slugvi'];         // gán slug cấp 2
    $_GET['slug_lv1'] = $info_lv2['slug_lv1'];   // gán slug cha (cấp 1)
    $page = 'product_list_lv2.php';
  } elseif ($newsData = $news->get_news_by_slug($request)) {
    $_GET['slug'] = $request;
    $_GET['type'] = $newsData['type'];
    $page = 'news.php';
  } elseif ($productData = $sanpham->get_sanpham_by_slug($request)) {
    $_GET['slug'] = $request;
    $page = 'product_details.php';
  } else {
    $page = '404.php';
  }
} else {
  $page = '404.php';
}
