<?php
// Bật tất cả lỗi để dễ debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Tạo ảnh test đơn giản
$width = 200;
$height = 100;
$img = imagecreatetruecolor($width, $height);

// Tô nền trắng
$white = imagecolorallocate($img, 255, 255, 255);
imagefill($img, 0, 0, $white);

// Vẽ chữ lên ảnh (nếu có sẵn GD Font)
$text_color = imagecolorallocate($img, 0, 0, 0);
imagestring($img, 5, 10, 40, 'Test WebP', $text_color);

// Tên file
$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
  mkdir($upload_dir, 0777, true);
}

$webp_path = $upload_dir . 'test_webp.webp';
$jpg_path = $upload_dir . 'test_fallback.jpg';

// Kiểm tra hàm imagewebp()
if (!function_exists('imagewebp')) {
  echo "❌ PHP không hỗ trợ WebP (imagewebp() không tồn tại).";
  exit;
}

// Ghi file WebP
ob_start();
$success = imagewebp($img, $webp_path, 100);
ob_end_clean();

// Kết quả
if ($success && file_exists($webp_path)) {
  echo "✅ Tạo ảnh WebP thành công: <a href='$webp_path' target='_blank'>$webp_path</a><br>";
} else {
  echo "❌ Tạo ảnh WebP thất bại.<br>";

  // Ghi ảnh JPG fallback để kiểm tra ảnh có hợp lệ không
  imagejpeg($img, $jpg_path, 90);
  echo "🔍 Ảnh JPG fallback được tạo để kiểm tra: <a href='$jpg_path' target='_blank'>$jpg_path</a><br>";
}

// Dọn bộ nhớ
imagedestroy($img);
