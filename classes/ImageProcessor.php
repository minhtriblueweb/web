<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');

class ImageProcessor
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function processImageUpload($source_path, $original_name, $resize_width = 1600, $resize_height = null, $webp_quality = 80)
  {
    $this->resizeImage($source_path, $source_path, $resize_width, $resize_height);

    $row = $this->db->select("SELECT * FROM tbl_watermark LIMIT 1")->fetch_assoc();
    $position = isset($row['position']) ? intval($row['position']) : 5;
    $opacity = isset($row['opacity']) ? intval($row['opacity']) : 50;
    $offset_x = isset($row['offset_x']) ? intval($row['offset_x']) : 0;
    $offset_y = isset($row['offset_y']) ? intval($row['offset_y']) : 0;

    $this->addWatermark($source_path, $source_path, $position, $opacity, $offset_x, $offset_y);

    $webp_file = $this->convert_webp_from_path($source_path, $original_name, $webp_quality);

    if ($webp_file) {
      if (file_exists($source_path)) {
        unlink($source_path);
      }
      return $webp_file;
    } else {
      return basename($source_path);
    }
  }

  public function convert_webp_from_path($source_path, $original_name, $quality = 80)
  {
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $filename_no_ext = pathinfo($original_name, PATHINFO_FILENAME);
    $destination_path = 'uploads/' . $filename_no_ext . '.webp';

    switch ($ext) {
      case 'jpg':
      case 'jpeg':
        $image = imagecreatefromjpeg($source_path);
        break;
      case 'png':
        $image = imagecreatefrompng($source_path);
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
        break;
      case 'gif':
        $image = imagecreatefromgif($source_path);
        break;
      default:
        return false;
    }

    if (imagewebp($image, $destination_path, $quality)) {
      imagedestroy($image);
      return $filename_no_ext . '.webp';
    }

    imagedestroy($image);
    return false;
  }

  public function resizeImage($source_path, $destination_path, $max_width, $max_height = null)
  {
    list($width_orig, $height_orig, $image_type) = getimagesize($source_path);

    if ($width_orig <= $max_width && (!$max_height || $height_orig <= $max_height)) return;

    $ratio_orig = $width_orig / $height_orig;

    if ($max_height) {
      $ratio_target = $max_width / $max_height;

      if ($ratio_target > $ratio_orig) {
        $new_height = $max_height;
        $new_width = $max_height * $ratio_orig;
      } else {
        $new_width = $max_width;
        $new_height = $max_width / $ratio_orig;
      }
    } else {
      $new_width = $max_width;
      $new_height = $max_width / $ratio_orig;
    }

    switch ($image_type) {
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($source_path);
        break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($source_path);
        break;
      default:
        return;
    }

    $new_image = imagecreatetruecolor($new_width, $new_height);
    if ($image_type == IMAGETYPE_PNG) {
      imagealphablending($new_image, false);
      imagesavealpha($new_image, true);
    }

    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

    if ($image_type == IMAGETYPE_JPEG) {
      imagejpeg($new_image, $destination_path, 85);
    } else {
      imagepng($new_image, $destination_path, 8);
    }

    imagedestroy($image);
    imagedestroy($new_image);
  }

  public function addWatermark($source_path, $destination_path, $position, $opacity, $offset_x, $offset_y)
  {
    $result = $this->db->select("SELECT * FROM tbl_watermark LIMIT 1");
    $row = $result ? $result->fetch_assoc() : null;
    if (!$row || empty($row['watermark'])) return false;

    $watermark_path = 'uploads/' . $row['watermark'];
    if (!file_exists($watermark_path)) return false;

    list($img_width, $img_height, $img_type) = getimagesize($source_path);

    switch ($img_type) {
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($source_path);
        break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($source_path);
        break;
      default:
        return false;
    }

    if (!$image) return false;

    $watermark_src = imagecreatefrompng($watermark_path);
    if (!$watermark_src) return false;

    imagesavealpha($watermark_src, true);

    $wm_orig_width = imagesx($watermark_src);
    $wm_orig_height = imagesy($watermark_src);

    $per = isset($row['per']) ? floatval($row['per']) : 2;
    $small_per = isset($row['small_per']) ? floatval($row['small_per']) : 3;
    $max_size = isset($row['max']) ? intval($row['max']) : 120;
    $min_size = isset($row['min']) ? intval($row['min']) : 120;
    $scale_percent = ($img_width < 300) ? $small_per : $per;
    $target_wm_width = $img_width * $scale_percent / 100;
    if ($max_size > 0) {
      $target_wm_width = min($target_wm_width, $max_size);
    }
    $target_wm_width = max($target_wm_width, $min_size);
    $target_wm_height = intval($wm_orig_height * ($target_wm_width / $wm_orig_width));
    $watermark = imagecreatetruecolor($target_wm_width, $target_wm_height);
    imagealphablending($watermark, false);
    imagesavealpha($watermark, true);
    imagecopyresampled($watermark, $watermark_src, 0, 0, 0, 0, $target_wm_width, $target_wm_height, $wm_orig_width, $wm_orig_height);
    imagedestroy($watermark_src);

    $padding = 10;
    switch ($position) {
      case 1:
        $x = $padding;
        $y = $padding;
        break;
      case 2:
        $x = ($img_width - $target_wm_width) / 2;
        $y = $padding;
        break;
      case 3:
        $x = $img_width - $target_wm_width - $padding;
        $y = $padding;
        break;
      case 4:
        $x = $img_width - $target_wm_width - $padding;
        $y = ($img_height - $target_wm_height) / 2;
        break;
      case 5:
        $x = $img_width - $target_wm_width - $padding;
        $y = $img_height - $target_wm_height - $padding;
        break;
      case 6:
        $x = ($img_width - $target_wm_width) / 2;
        $y = $img_height - $target_wm_height - $padding;
        break;
      case 7:
        $x = $padding;
        $y = $img_height - $target_wm_height - $padding;
        break;
      case 8:
        $x = $padding;
        $y = ($img_height - $target_wm_height) / 2;
        break;
      case 9:
      default:
        $x = ($img_width - $target_wm_width) / 2;
        $y = ($img_height - $target_wm_height) / 2;
        break;
    }

    $x += intval($offset_x);
    $y += intval($offset_y);

    imagecopy($image, $watermark, $x, $y, 0, 0, $target_wm_width, $target_wm_height);

    switch ($img_type) {
      case IMAGETYPE_JPEG:
        imagejpeg($image, $destination_path, 85);
        break;
      case IMAGETYPE_PNG:
        imagepng($image, $destination_path, 8);
        break;
    }

    imagedestroy($image);
    imagedestroy($watermark);

    return true;
  }
}
