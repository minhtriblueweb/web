<?php
require_once 'init.php';

$w = isset($_GET['w']) ? (int)$_GET['w'] : 0;
$h = isset($_GET['h']) ? (int)$_GET['h'] : 0;
$zc = isset($_GET['zc']) ? (int)$_GET['zc'] : 1;
$src = $_GET['src'] ?? '';

if ($w <= 0 || $h <= 0 || empty($src)) {
  http_response_code(400);
  echo 'Invalid parameters';
  exit;
}

// Đường dẫn gốc ảnh
$source_path = __DIR__ . '/uploads/' . ltrim($src, '/');

// Đường dẫn file thumb
$thumb_path = __DIR__ . "/uploads/thumb/{$w}x{$h}x{$zc}/" . basename($src);

if (!file_exists($thumb_path)) {
  $fn->createFixedThumbnail($source_path, "{$w}x{$h}x{$zc}");
}

// Xuất ảnh nếu đã tạo
if (file_exists($thumb_path)) {
  header('Content-Type: ' . mime_content_type($thumb_path));
  readfile($thumb_path);
  exit;
} else {
  http_response_code(404);
  echo 'Image not found';
}
