<?php
session_start();
require_once __DIR__ . '/../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit('Forbidden');
}

if (empty($_POST['id']) || empty($_POST['table'])) {
  echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
  exit;
}

$id    = (int)$_POST['id'];
$table = $_POST['table'];

/* Lấy dữ liệu gốc */
$item = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 1", [$id]);
if (!$item) {
  echo json_encode(['success' => false, 'message' => 'Không tìm thấy dữ liệu']);
  exit;
}

/* Cột trong bảng */
function getExistingColumns($table)
{
  global $db;
  $rows = $db->rawQueryArray("SHOW COLUMNS FROM `$table`");
  return array_map(fn($r) => $r['Field'], $rows);
}

/* Sinh slug không trùng */
function makeUniqueSlugChain($baseSlug, $table, $lang = 'vi', $excludeId = 0)
{
  global $db;
  $slugCol = 'slug' . $lang;
  $slug = $baseSlug;
  $i = 1;
  while (true) {
    $exists = $db->rawQueryOne(
      "SELECT id FROM `$table` WHERE `$slugCol` = ? AND id != ? LIMIT 1",
      [$slug, $excludeId]
    );
    if (empty($exists['id'])) break;
    $slug = $baseSlug . '-' . $i;
    $i++;
  }
  return $slug;
}

/* Copy file ảnh */
function duplicateImage($imageName)
{
  if (empty($imageName)) return '';

  // Lấy thư mục upload
  $uploadDir = defined('UPLOADS')
    ? rtrim(UPLOADS, '/') . '/'
    : dirname(__DIR__) . '/uploads/';

  // File gốc
  $src = $uploadDir . $imageName;
  if (!is_file($src)) {
    // Debug dễ hiểu
    error_log("❌ duplicateImage: file không tồn tại tại đường dẫn $src");
    return '';
  }

  // Tạo tên mới
  $ext  = pathinfo($imageName, PATHINFO_EXTENSION);
  $base = pathinfo($imageName, PATHINFO_FILENAME);
  $newName = $base . '-' . uniqid() . '.' . $ext;

  // File đích
  $dest = $uploadDir . $newName;

  // Copy
  if (@copy($src, $dest)) {
    return $newName;
  }

  error_log("❌ duplicateImage: copy thất bại từ $src sang $dest");
  return '';
}


/* Hàm nhân bản */
function createCopy($item, $table)
{
  global $db, $config;

  $langs = array_keys($config['website']['lang'] ?? ['vi']);
  $data = [];

  foreach ($langs as $lang) {
    $oldName = $item['name' . $lang] ?? '';
    $newName = trim($oldName . ' (1)');

    $baseSlug = $oldName ? preg_replace('/\s+/', '-', strtolower($oldName)) : 'item';
    $newSlug  = makeUniqueSlugChain($baseSlug, $table, $lang);

    $data['name' . $lang]    = $newName;
    $data['slug' . $lang]    = $newSlug;
    $data['desc' . $lang]    = $item['desc' . $lang]    ?? '';
    $data['content' . $lang] = $item['content' . $lang] ?? '';
  }

  /* Copy ảnh đại diện (cột "file") */
  if (!empty($item['file'])) {
    $newFile = duplicateImage($item['file']);
    if ($newFile) $data['file'] = $newFile;
  }

  /* Các field khác */
  $data = array_merge($data, [
    'id_list'      => $item['id_list'] ?? 0,
    'id_cat'       => $item['id_cat']  ?? 0,
    'id_item'      => $item['id_item'] ?? 0,
    'status'       => '',
    'type'         => $item['type'] ?? '',
    'date_created' => time()
  ]);

  if ($table === 'tbl_product') {
    $data = array_merge($data, [
      'id_brand'      => $item['id_brand']      ?? 0,
      'code'          => $item['code']          ?? '',
      'regular_price' => $item['regular_price'] ?? 0,
      'discount'      => $item['discount']      ?? 0,
      'sale_price'    => $item['sale_price']    ?? 0,
      'numb'          => 0
    ]);
  }

  /* Chỉ insert những cột có trong bảng */
  $existing = getExistingColumns($table);
  $columns  =$values   = [];
  foreach ($data as $col => $val) {
    if (in_array($col, $existing, true)) {
      $columns[] = $col;
      $values[]  = $val;
    }
  }
  if (empty($columns)) return false;
  $placeholders = implode(',', array_fill(0, count($columns), '?'));
  $columnsStr   = implode(',', $columns);
  $sql = "INSERT INTO `$table` ($columnsStr) VALUES ($placeholders)";
  return $db->execute($sql, $values);
}
/* Thực thi */
$ok = createCopy($item, $table);

echo json_encode([
  'success' => $ok ? true : false,
  'message' => $ok ? 'Sao chép thành công' : 'Copy thất bại'
]);
