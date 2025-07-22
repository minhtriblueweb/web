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

// Khởi tạo
$sources = '';
$requick = [
  // Static pages
  ['tbl' => '', 'source' => 'index', 'type' => '', 'field' => '', 'file' => 'index.php', 'slug' => 'trang-chu'],
  ['tbl' => '', 'source' => 'static', 'type' => 'gioi-thieu', 'file' => 'static.php', 'slug' => 'gioi-thieu'],
  ['tbl' => '', 'source' => 'static', 'type' => 'lien-he', 'field' => '', 'file' => 'lienhe.php',      'slug' => 'lien-he'],
  ['tbl' => '', 'source' => 'static',   'type' => 'mua-hang', 'field' => '', 'file' => 'muahang.php',     'slug' => 'mua-hang'],
  ['tbl' => '', 'source' => 'search', 'type' => '', 'field' => '', 'file' => 'search.php', 'slug' => 'tim-kiem'],

  // Product routes
  array("tbl" => "", "source" => "product", "type" => "san-pham", "field" => "", "file" => "product.php", "slug" => "san-pham", "titleMain" => "Sản phẩm"),
  array("tbl" => "product", "source" => "product", "type" => "san-pham", "field" => "id", "file" => "product.php"),
  array("tbl" => "product_list", "source" => "product", "type" => "san-pham", "field" => "idl", "file" => "product.php"),
  array("tbl" => "product_cat", "source" => "product", "type" => "san-pham", "field" => "idc", "file" => "product.php"),

  // News routes
  array("tbl" => "news", "source" => "news", "type" => "tin-tuc", "field" => "id", "file" => "news.php", "slug" => "tin-tuc", "titleMain" => "Tin tức"),
  array("tbl" => "news", "source" => "news", "type" => "chinh-sach", "field" => "id", "file" => "news.php", "slug" => "chinh-sach", "titleMain" => "Chính sách"),
  array("tbl" => "news", "source" => "news", "type" => "huong-dan-choi", "field" => "id", "file" => "news.php", "slug" => "huong-dan-choi", "titleMain" => "Hướng dẫn chơi")
];

// Tìm route khớp
$found = false;
foreach ($requick as $r) {
  $titleMain = $_GET['titleMain'] = $r['titleMain'] ?? "";
  // Ưu tiên match theo slug nếu có
  if (!empty($r['slug']) && $slug == $r['slug']) {
    $type = $_GET['type'] = $r['type'];
    $sources = $r['file'];
    $found = true;

    break;
  }

  // Nếu có tbl thì kiểm tra động
  if (!empty($r['tbl'])) {
    $row = $db->rawQueryOne(
      "SELECT id, type FROM tbl_{$r['tbl']} WHERE slug{$lang} = ? AND type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1",
      [$slug, $r['type']]
    );
    if ($row) {
      $_GET[$r['field']] = $row['id'];
      $type = $_GET['type'] = $row['type'];
      $sources = $r['file'];
      $found = true;
      break;
    }
  }
}

// Nếu không tìm thấy route nào phù hợp
if (!$found || !$sources) {
  $sources = '404.php';
}
