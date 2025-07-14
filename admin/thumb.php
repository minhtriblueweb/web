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
$source_path = __DIR__ . '/' . UPLOADS . $src;
$thumb_folder = "{$w}x{$h}x{$zc}/";
$thumb_file   = basename($src);
$base_dir  = __DIR__ . '/' . UPLOADS . THUMB;
$thumb_dir = $base_dir . $thumb_folder;
if ($add_watermark) {
  $thumb_dir .= WATERMARK;
}
$path = $fn->createFixedThumbnail($source_path, "{$w}x{$h}x{$zc}", false, $add_watermark);

if (!$path || !file_exists($path)) {
  http_response_code(500);
  exit('Failed to generate thumbnail');
}

header('Content-Type: ' . mime_content_type($path));
readfile($path);
exit;
