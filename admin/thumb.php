<?php
require_once 'init.php';

$w = isset($_GET['w']) ? (int)$_GET['w'] : 0;
$h = isset($_GET['h']) ? (int)$_GET['h'] : 0;
$zc = isset($_GET['zc']) ? (int)$_GET['zc'] : 1;
$src = $_GET['src'] ?? '';
$src = ltrim($src, '/');

// Validate input
if ($w <= 0 || $h <= 0 || empty($src) || strpos($src, '..') !== false) {
  http_response_code(400);
  echo 'Invalid parameters';
  exit;
}
$source_path = __DIR__ . '/' . UPLOADS . ltrim($src, '/');
$thumb_path  = __DIR__ . '/' . UPLOADS . "thumb/{$w}x{$h}x{$zc}/" . basename($src);
// Tạo ảnh nếu chưa có
if (!file_exists($thumb_path)) {
  $result = $fn->createFixedThumbnail($source_path, "{$w}x{$h}x{$zc}");
  if (!$result || !file_exists($thumb_path)) {
    http_response_code(500);
    echo 'Failed to generate thumbnail';
    exit;
  }
}

// Xuất ảnh
header('Content-Type: ' . mime_content_type($thumb_path));
readfile($thumb_path);
exit;
