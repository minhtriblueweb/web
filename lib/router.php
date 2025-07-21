<?php
// Lấy slug từ URL: ?page=trang-abc/page-2
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

// Route tĩnh
$routes = [
  ''               => 'index.php',
  'trang-chu'      => 'index.php',
  'gioi-thieu'     => 'gioithieu.php',
  'lien-he'        => 'lienhe.php',
  'san-pham'       => 'product.php',
  'mua-hang'       => 'muahang.php',
  'huong-dan-choi' => 'news.php',
  'chinh-sach'     => 'news.php',
  'tin-tuc'        => 'news.php',
  'tim-kiem'       => 'search.php'
];

// Mặc định là 404
$page = '404.php';
if (isset($routes[$slug])) {
  $page = $routes[$slug];
  $map_type = [
    'huong-dan-choi' => 'huongdanchoi',
    'chinh-sach'     => 'chinhsach',
    'tin-tuc'        => 'tintuc'
  ];
  if (isset($map_type[$slug])) {
    $_GET['type'] = $map_type[$slug];
  }
} elseif (!empty($slug)) {
  $requick = [
    ['tbl' => 'product',      'type' => 'san-pham', 'field' => 'id'],
    ['tbl' => 'product_list', 'type' => 'san-pham', 'field' => 'idl'],
    ['tbl' => 'product_cat',  'type' => 'san-pham', 'field' => 'idc'],
    ['tbl' => 'news',         'type' => '',         'field' => 'id']
  ];
  foreach ($requick as $rq) {
    $tbl     = $rq['tbl'];
    $type    = $rq['type'];
    $field   = $rq['field'];
    $row = $db->rawQueryOne("SELECT id, type FROM `tbl_$tbl` WHERE `slug$lang` = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", [$slug]);
    if ($row) {
      $_GET[$field] = $row['id'];
      $_GET['slug'] = $slug;
      if (!empty($row['type'])) $_GET['type'] = $row['type'];
      switch ($type) {
        case 'san-pham':
          $page = 'product.php';
          break;
        case 'news':
          $page = 'news_details.php';
          break;
      }
      break;
    }
  }
}
