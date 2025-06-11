<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class img
{
  private $db;
  private $fm;

  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
  }

  // public function convert_webp_from_path($source_path, $original_name, $quality = 80)
  // {
  //   $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
  //   $random_name = uniqid('img_') . '.webp';
  //   $destination_path = 'uploads/' . $random_name;

  //   if ($ext === 'webp') {
  //     // Chỉ đổi tên và di chuyển file
  //     if (!copy($source_path, $destination_path)) {
  //       error_log("Failed to move existing WebP to uploads/: $source_path");
  //       return false;
  //     }
  //     return $random_name;
  //   }

  //   // Convert các định dạng khác sang WebP
  //   switch ($ext) {
  //     case 'jpg':
  //     case 'jpeg':
  //       $image = imagecreatefromjpeg($source_path);
  //       break;
  //     case 'png':
  //       $image = imagecreatefrompng($source_path);
  //       imagepalettetotruecolor($image);
  //       imagealphablending($image, true);
  //       imagesavealpha($image, true);
  //       break;
  //     case 'gif':
  //       $image = imagecreatefromgif($source_path);
  //       break;
  //     default:
  //       error_log("Unsupported file format: $ext");
  //       return false;
  //   }

  //   if (!$image) {
  //     error_log("Failed to load image from $source_path");
  //     return false;
  //   }

  //   if (!imagewebp($image, $destination_path, $quality)) {
  //     error_log("Failed to convert to WebP at $destination_path");
  //     imagedestroy($image);
  //     return false;
  //   }

  //   imagedestroy($image);
  //   return $random_name;
  // }
  // public function resizeImage($source_path, $destination_path, $max_width, $max_height)
  // {
  //   list($width_orig, $height_orig, $image_type) = getimagesize($source_path);

  //   if ($width_orig <= $max_width && (!$max_height || $height_orig <= $max_height)) return;

  //   $ratio_orig = $width_orig / $height_orig;

  //   if ($max_height) {
  //     $ratio_target = $max_width / $max_height;

  //     if ($ratio_target > $ratio_orig) {
  //       $new_height = $max_height;
  //       $new_width = $max_height * $ratio_orig;
  //     } else {
  //       $new_width = $max_width;
  //       $new_height = $max_width / $ratio_orig;
  //     }
  //   } else {
  //     $new_width = $max_width;
  //     $new_height = $max_width / $ratio_orig;
  //   }

  //   switch ($image_type) {
  //     case IMAGETYPE_JPEG:
  //       $image = imagecreatefromjpeg($source_path);
  //       break;
  //     case IMAGETYPE_PNG:
  //       $image = imagecreatefrompng($source_path);
  //       break;
  //     default:
  //       return;
  //   }

  //   $new_image = imagecreatetruecolor($new_width, $new_height);
  //   if ($image_type == IMAGETYPE_PNG) {
  //     imagealphablending($new_image, false);
  //     imagesavealpha($new_image, true);
  //   }

  //   imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

  //   if ($image_type == IMAGETYPE_JPEG) {
  //     imagejpeg($new_image, $destination_path, 85);
  //   } else {
  //     imagepng($new_image, $destination_path, 8);
  //   }

  //   imagedestroy($image);
  //   imagedestroy($new_image);
  // }
  // public function processImageUpload($source_path, $original_name, $resize_width = 1600, $resize_height = null, $webp_quality = 80)
  // {
  //   // ✅ 1. Resize trước
  //   $this->resizeImage($source_path, $source_path, $resize_width, $resize_height);

  //   // ✅ 2. Sau đó thêm watermark (tỷ lệ per dựa trên ảnh đã resize)
  //   $row = $this->db->select("SELECT * FROM tbl_watermark LIMIT 1")->fetch_assoc();
  //   $position = isset($row['position']) ? intval($row['position']) : 5;
  //   $opacity = isset($row['opacity']) ? intval($row['opacity']) : 50;
  //   $offset_x = isset($row['offset_x']) ? intval($row['offset_x']) : 0;
  //   $offset_y = isset($row['offset_y']) ? intval($row['offset_y']) : 0;

  //   $this->addWatermark($source_path, $source_path, $position, $opacity, $offset_x, $offset_y);

  //   // ✅ 3. Chuyển sang webp + random tên
  //   $webp_file = $this->convert_webp_from_path($source_path, $original_name, $webp_quality);

  //   if ($webp_file) {
  //     if (file_exists($source_path)) {
  //       // unlink($source_path);
  //     }
  //     return $webp_file;
  //   } else {
  //     return basename($source_path);
  //   }
  // }
}
