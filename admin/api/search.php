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
  $rows = $db->rawQuery(
    "SELECT id, name$lang, slug$lang, type
     FROM tbl_product
     WHERE name$lang LIKE ?
     ORDER BY id DESC
     LIMIT 10",
    ["%$keyword%"]
  );

  foreach ($rows as $row) {
    $result[] = [
      'id'     => $row['id'],
      'namevi' => $row["name$lang"],
      'page'    => 'product',
      'type'   => $row['type']
    ];
  }
}

echo json_encode([
  'success' => true,
  'data'    => $result
], JSON_UNESCAPED_UNICODE);

exit;
