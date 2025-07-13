<?php
require_once 'init.php'; // Chứa autoload, DB, define('UPLOADS', 'uploads/'), class $fn

$src      = $_GET['src'] ?? '';
$position = isset($_GET['position']) ? (int)filter_var($_GET['position'], FILTER_SANITIZE_NUMBER_INT) : 9;

$src = ltrim($src, '/');
$source_path = __DIR__ . '/' . UPLOADS . $src;

// Kiểm tra hợp lệ
if (empty($src) || !file_exists($source_path) || strpos($src, '..') !== false) {
  http_response_code(404);
  exit('File not found');
}
$cached_path = __DIR__ . '/' . UPLOADS . 'watermark/pos' . $position . '/' . basename($src);
// Nếu chưa có thì tạo watermark
if (!file_exists($cached_path)) {
  // Tạo thư mục nếu chưa có
  $cache_dir = dirname($cached_path);
  if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0755, true);
  }

  // Gọi hàm addWatermark
  $fn->addWatermark($source_path, $cached_path, ['position' => $position]);
}

// Xuất ảnh
header('Content-Type: ' . mime_content_type($cached_path));
readfile($cached_path);
exit;
