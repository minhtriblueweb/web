<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  echo json_encode(['success' => false]);
  exit;
}

require_once __DIR__ . '/../init.php';

$db = new Database();
$result = [];
$keyword = trim($_POST['keyword'] ?? '');
$lang = $lang ?? 'vi';

if ($keyword !== '') {

  $products = $db->rawQuery(
    "SELECT id, id_list, id_cat, id_item, id_sub, id_brand, name$lang, type FROM tbl_product WHERE name$lang LIKE ? ORDER BY id DESC LIMIT 10",
    ["%$keyword%"]
  ) ?: [];

  foreach ($products as $row) {
    $result[] = [
      'id' => $row['id'],
      'name' => $row["name$lang"],
      'page' => 'product',
      'type' => $row['type'],
      'ids' => [
        'id_list'  => $row['id_list'],
        'id_cat'   => $row['id_cat'],
        'id_item'  => $row['id_item'],
        'id_sub'   => $row['id_sub'],
        'id_brand' => $row['id_brand']
      ]
    ];
  }

  $news = $db->rawQuery(
    "SELECT id, id_list, id_cat, id_item, id_sub, name$lang, type FROM tbl_news WHERE name$lang LIKE ? ORDER BY id DESC LIMIT 10",
    ["%$keyword%"]
  ) ?: [];

  foreach ($news as $row) {
    $result[] = [
      'id' => $row['id'],
      'name' => $row["name$lang"],
      'page' => 'news',
      'type' => $row['type'],
      'ids' => [
        'id_list'  => $row['id_list'],
        'id_cat'   => $row['id_cat'],
        'id_item'  => $row['id_item'],
        'id_sub'   => $row['id_sub']
      ]
    ];
  }
}

echo json_encode(['success' => true, 'data' => $result], JSON_UNESCAPED_UNICODE);
exit;
