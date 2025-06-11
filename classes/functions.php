<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class functions
{
  private $db;
  private $fm;

  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
  }

  public function addWatermark($source_path, $destination_path, $position, $opacity, $offset_x, $offset_y)
  {
    $result = $this->db->select("SELECT * FROM tbl_watermark LIMIT 1");
    $row = $result ? $result->fetch_assoc() : null;
    if (!$row || empty($row['watermark'])) return false;
    $watermark_path = 'uploads/' . $row['watermark'];
    if (!file_exists($watermark_path)) return false;
    $img_type = exif_imagetype($source_path);
    switch ($img_type) {
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($source_path);
        break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($source_path);
        break;
      case IMAGETYPE_WEBP:
        $image = imagecreatefromwebp($source_path);
        break;
      default:
        return false;
    }
    if (!$image) return false;
    $img_width = imagesx($image);
    $img_height = imagesy($image);
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
    $target_wm_width = max(min($target_wm_width, $max_size), $min_size);
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
        break; // top-left
      case 2:
        $x = ($img_width - $target_wm_width) / 2;
        $y = $padding;
        break; // top-center
      case 3:
        $x = $img_width - $target_wm_width - $padding;
        $y = $padding;
        break; // top-right
      case 4:
        $x = $img_width - $target_wm_width - $padding;
        $y = ($img_height - $target_wm_height) / 2;
        break; // middle-right
      case 5:
        $x = $img_width - $target_wm_width - $padding;
        $y = $img_height - $target_wm_height - $padding;
        break; // bottom-right
      case 6:
        $x = ($img_width - $target_wm_width) / 2;
        $y = $img_height - $target_wm_height - $padding;
        break; // bottom-center
      case 7:
        $x = $padding;
        $y = $img_height - $target_wm_height - $padding;
        break; // bottom-left
      case 8:
        $x = $padding;
        $y = ($img_height - $target_wm_height) / 2;
        break; // middle-left
      case 9:
      default:
        $x = ($img_width - $target_wm_width) / 2;
        $y = ($img_height - $target_wm_height) / 2;
        break; // center
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
      case IMAGETYPE_WEBP:
        imagewebp($image, $destination_path, 80);
        break;
    }
    imagedestroy($image);
    imagedestroy($watermark);
    return true;
  }

  public function createFixedThumbnail($source_path, $thumb_width = 600, $thumb_height = 600, $background = [0, 0, 0, 127])
  {
    if (!file_exists($source_path)) return false;

    $image_info = getimagesize($source_path);
    if (!$image_info) return false;

    list($width_orig, $height_orig, $image_type) = $image_info;

    switch ($image_type) {
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($source_path);
        break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($source_path);
        break;
      case IMAGETYPE_WEBP:
        $image = imagecreatefromwebp($source_path);
        break;
      default:
        return false;
    }

    $ratio_orig = $width_orig / $height_orig;
    $thumb_ratio = $thumb_width / $thumb_height;

    if ($ratio_orig > $thumb_ratio) {
      $new_width = $thumb_width;
      $new_height = intval($thumb_width / $ratio_orig);
    } else {
      $new_height = $thumb_height;
      $new_width = intval($thumb_height * $ratio_orig);
    }

    $resized = imagecreatetruecolor($new_width, $new_height);
    if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_WEBP) {
      imagealphablending($resized, false);
      imagesavealpha($resized, true);
    }

    imagecopyresampled($resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

    // Đọc màu nền từ mảng RGBA
    $r = isset($background[0]) ? intval($background[0]) : 0;
    $g = isset($background[1]) ? intval($background[1]) : 0;
    $b = isset($background[2]) ? intval($background[2]) : 0;
    $a = isset($background[3]) ? intval($background[3]) : 0;

    if (($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_WEBP) && $a > 0) {
      imagealphablending($thumb, false);
      imagesavealpha($thumb, true);
      $bg_color = imagecolorallocatealpha($thumb, $r, $g, $b, $a);
    } else {
      $bg_color = imagecolorallocate($thumb, $r, $g, $b);
    }

    imagefill($thumb, 0, 0, $bg_color);

    $dst_x = intval(($thumb_width - $new_width) / 2);
    $dst_y = intval(($thumb_height - $new_height) / 2);
    imagecopy($thumb, $resized, $dst_x, $dst_y, 0, 0, $new_width, $new_height);

    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $original_name = pathinfo($source_path, PATHINFO_FILENAME);
    $thumb_filename = $original_name . '_' . $thumb_width . 'x' . $thumb_height . '.webp';
    $thumb_path = $upload_dir . $thumb_filename;
    imagewebp($thumb, $thumb_path, 80);
    imagedestroy($image);
    imagedestroy($resized);
    imagedestroy($thumb);
    $watermarked_path = $upload_dir . $original_name . $thumb_width . 'x' . $thumb_height . '.webp';
    $this->addWatermark($thumb_path, $watermarked_path, 9, 0.5, 0, 0);
    return basename($watermarked_path);
  }


  public function add_thumb($source_path, $thumb_width = 600, $thumb_height = 600, $transparent_background = false)
  {
    if (!file_exists($source_path)) return false;

    $image_info = getimagesize($source_path);
    if (!$image_info) return false;

    list($width_orig, $height_orig, $image_type) = $image_info;

    switch ($image_type) {
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($source_path);
        break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($source_path);
        break;
      case IMAGETYPE_WEBP:
        $image = imagecreatefromwebp($source_path);
        break;
      default:
        return false;
    }

    $ratio_orig = $width_orig / $height_orig;
    $thumb_ratio = $thumb_width / $thumb_height;

    if ($ratio_orig > $thumb_ratio) {
      $new_width = $thumb_width;
      $new_height = intval($thumb_width / $ratio_orig);
    } else {
      $new_height = $thumb_height;
      $new_width = intval($thumb_height * $ratio_orig);
    }

    $resized = imagecreatetruecolor($new_width, $new_height);
    if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_WEBP) {
      imagealphablending($resized, false);
      imagesavealpha($resized, true);
    }

    imagecopyresampled($resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

    if ($transparent_background && ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_WEBP)) {
      imagealphablending($thumb, false);
      imagesavealpha($thumb, true);
      $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127); // transparent black
      imagefill($thumb, 0, 0, $transparent);
    } else {
      $white = imagecolorallocate($thumb, 255, 255, 255);
      imagefill($thumb, 0, 0, $white);
    }

    $dst_x = intval(($thumb_width - $new_width) / 2);
    $dst_y = intval(($thumb_height - $new_height) / 2);
    imagecopy($thumb, $resized, $dst_x, $dst_y, 0, 0, $new_width, $new_height);

    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $original_name = pathinfo($source_path, PATHINFO_FILENAME);
    $thumb_filename = $original_name . '_' . $thumb_width . 'x' . $thumb_height . '.webp';
    $thumb_path = $upload_dir . $thumb_filename;

    imagewebp($thumb, $thumb_path, 80);

    imagedestroy($image);
    imagedestroy($resized);
    imagedestroy($thumb);

    return $thumb_filename;
  }


  public function phantrang_sp($tbl)
  {
    $tbl = mysqli_real_escape_string($this->db->link, $tbl);
    $query = "SELECT COUNT(*) as total FROM `$tbl`";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc()['total'] : 0;
  }

  public function phantrang($tbl, $type)
  {
    $tbl = mysqli_real_escape_string($this->db->link, $tbl);
    $type = mysqli_real_escape_string($this->db->link, $type);
    $query = "SELECT COUNT(*) as total FROM `$tbl` WHERE type = '$type'";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc()['total'] : 0;
  }

  function renderPagination($current_page, $total_pages, $base_url = '?page=')
  {
    if ($total_pages <= 1) {
      return '';
    }
    $pagination_html = '<ul class="pagination flex-wrap justify-content-center mb-0">';
    $pagination_html .= '<li class="page-item">';
    $pagination_html .= '<a class="page-link">Trang ' . $current_page . ' / ' . $total_pages . '</a>';
    $pagination_html .= '</li>';
    if ($current_page > 1) {
      $pagination_html .= '<li class="page-item">';
      $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page - 1) . '">Trước</a>';
      $pagination_html .= '</li>';
    }
    $range = 2;
    for ($i = 1; $i <= $total_pages; $i++) {
      if (
        $i == 1 ||
        $i == 2 ||
        $i == $total_pages ||
        $i == $total_pages - 1 ||
        ($i >= $current_page - $range && $i <= $current_page + $range)
      ) {
        $active_class = ($i == $current_page) ? 'active' : '';
        $pagination_html .= '<li class="page-item ' . $active_class . '">';
        $pagination_html .= '<a class="page-link" href="' . $base_url . $i . '">' . $i . '</a>';
        $pagination_html .= '</li>';
      } elseif (
        ($i == 3 && $current_page - $range > 4) ||
        ($i == $total_pages - 2 && $current_page + $range < $total_pages - 3)
      ) {
        $pagination_html .= '<li class="page-item disabled">';
        $pagination_html .= '<a class="page-link">...</a>';
        $pagination_html .= '</li>';
      }
    }

    if ($current_page < $total_pages) {
      $pagination_html .= '<li class="page-item">';
      $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page + 1) . '">Tiếp</a>';
      $pagination_html .= '</li>';
    }
    if ($current_page < $total_pages) {
      $pagination_html .= '<li class="page-item">';
      $pagination_html .= '<a class="page-link" href="' . $base_url . $total_pages . '">Cuối</a>';
      $pagination_html .= '</li>';
    }
    $pagination_html .= '</ul>';
    return $pagination_html;
  }


  function renderPagination_tc($current_page, $total_pages, $base_url)
  {
    if ($total_pages <= 1) {
      return '';
    }
    $pagination_html = '<ul class="pagination flex-wrap justify-content-center mb-0">';
    if ($current_page > 1) {
      $pagination_html .= '<li class="page-item">';
      $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page - 1) . '"><i class="fas fa-angle-left"></i></a>';
      $pagination_html .= '</li>';
    } else {
      $pagination_html .= '<li class="page-item disabled">';
      $pagination_html .= '<a class="page-link"><i class="fas fa-angle-left"></i></a>';
      $pagination_html .= '</li>';
    }
    $range = 2;
    $show_dots = false;
    for ($i = 1; $i <= $total_pages; $i++) {
      if (
        $i == 1 || $i == $total_pages || // Trang đầu, cuối luôn hiển thị
        ($i >= $current_page - $range && $i <= $current_page + $range) // Các trang gần current page
      ) {
        if ($show_dots) {
          $pagination_html .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
          $show_dots = false;
        }
        $active_class = ($i === $current_page) ? 'active' : '';
        $pagination_html .= '<li class="page-item ' . $active_class . '">';
        $pagination_html .= '<a class="page-link" href="' . $base_url . $i . '">' . $i . '</a>';
        $pagination_html .= '</li>';
      } else {
        $show_dots = true;
      }
    }
    if ($current_page < $total_pages) {
      $pagination_html .= '<li class="page-item">';
      $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page + 1) . '"><i class="fas fa-angle-right"></i></a>';
      $pagination_html .= '</li>';
    } else {
      $pagination_html .= '<li class="page-item disabled">';
      $pagination_html .= '<a class="page-link"><i class="fas fa-angle-right"></i></a>';
      $pagination_html .= '</li>';
    }
    $pagination_html .= '<li class="page-item">';
    $pagination_html .= '<a class="page-link" href="' . $base_url . $total_pages . '"><i class="fa-solid fa-angles-right"></i></a>';
    $pagination_html .= '</li>';
    $pagination_html .= '</ul>';
    return $pagination_html;
  }
}
