<?php
require_once 'init.php';

$w  = max(0, (int)($_GET['w'] ?? 0));
$h  = max(0, (int)($_GET['h'] ?? 0));
$zc = (int)($_GET['zc'] ?? 1);
$src = ltrim($_GET['src'] ?? '', '/');
$add_watermark = isset($_GET['wm']) && (int)$_GET['wm'] === 1;
if ($w === 0 || $h === 0 || !$src || str_contains($src, '..')) {
  http_response_code(400);
  exit('Invalid parameters');
}
$base_filename = pathinfo($src, PATHINFO_FILENAME);
$ext = pathinfo($src, PATHINFO_EXTENSION);
$thumb_name = "{$w}x{$h}x{$zc}";
$folder = dirname($src);
$folder = $folder !== '.' ? $folder . '/' : '';
$source_path = __DIR__ . '/' . UPLOADS . $src;
$thumb_base_dir = __DIR__ . '/' . UPLOADS . THUMB;
$thumb_sub_dir = $thumb_name . '/' . $folder;
$thumb_filename = $base_filename . '.' . $ext;
$thumb_path = rtrim($thumb_base_dir . $thumb_sub_dir, '/') . '/' . ($add_watermark ? trim(WATERMARK, '/') . '/' : '') . $thumb_filename;
if (!file_exists($thumb_path)) {
  $thumb_path = $fn->createThumb($source_path, $thumb_name, false, $add_watermark);
}
if (!$thumb_path || !file_exists($thumb_path)) {
  http_response_code(500);
  exit('Failed to load thumbnail');
}
header('Content-Type: ' . mime_content_type($thumb_path));
readfile($thumb_path);
exit;
