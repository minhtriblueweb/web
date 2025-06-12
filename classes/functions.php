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

  function is_selected($name, $result, $id, $value)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return (isset($_POST[$name]) && $_POST[$name] == $value) ? 'selected' : '';
    }
    if (!empty($id) && isset($result[$name])) {
      return ($result[$name] == $value) ? 'selected' : '';
    }
    return '';
  }

  function is_checked($name, $result, $id, $default = true)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return isset($_POST[$name]) && $_POST[$name] === $name ? 'checked' : '';
    }
    if (!empty($id) && isset($result[$name])) {
      return $result[$name] === $name ? 'checked' : '';
    }
    return $default ? 'checked' : '';
  }


  public function deleteMultiple($listid, $table, $imageColumn, $redirectUrl)
  {
    $querySelect = "SELECT `$imageColumn` FROM `$table` WHERE id IN ($listid)";
    $resultSelect = $this->db->select($querySelect);

    if ($resultSelect && $resultSelect->num_rows > 0) {
      while ($row = $resultSelect->fetch_assoc()) {
        $filePath = 'uploads/' . $row[$imageColumn];
        if (!empty($row[$imageColumn]) && file_exists($filePath)) {
          unlink($filePath);
        }
      }
    }
    $queryDelete = "DELETE FROM `$table` WHERE id IN ($listid)";
    $resultDelete = $this->db->delete($queryDelete);

    if ($resultDelete) {
      header("Location: transfer.php?stt=success&url=$redirectUrl");
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function delete($id, $table, $imageColumn, $redirect_url)
  {
    $del_file_name = "SELECT `$imageColumn` FROM $table WHERE id='$id'";
    $delta = $this->db->select($del_file_name);
    $string = "";
    while ($rowData = $delta->fetch_assoc()) {
      $string .= $rowData[$imageColumn];
    }
    $delLink = "uploads/" . $string;
    if (!empty($string) && file_exists($delLink)) {
      unlink($delLink);
    }
    $query = "DELETE FROM $table WHERE id = '$id'";
    $result = $this->db->delete($query);
    if ($result) {
      header("Location: transfer.php?stt=success&url=$redirect_url");
      exit();
    } else {
      header("Location: transfer.php?stt=danger&url=$redirect_url");
      exit();
    }
  }

  public function isSlugviDuplicated($slugvi, $table, $exclude_id)
  {
    // Escape đầu vào
    $slugvi = mysqli_real_escape_string($this->db->link, trim($slugvi));
    $table = mysqli_real_escape_string($this->db->link, trim($table));
    $exclude_id = mysqli_real_escape_string($this->db->link, trim($exclude_id));

    // ✅ Danh sách bảng có thể chứa slugvi
    $tables = ['tbl_danhmuc', 'tbl_danhmuc_c2', 'tbl_sanpham', 'tbl_news'];

    // ❌ Danh sách slugvi KHÔNG ĐƯỢC sử dụng (trang tĩnh)
    $reserved_slugs = [
      'lien-he',
      'tin-tuc',
      'huong-dan-choi',
      'san-pham',
      'gioi-thieu',
      'chinh-sach',
      'mua-hang',
      'dang-nhap',
      'dang-ky'
    ];

    // 🔒 Nếu slug nằm trong danh sách cấm → từ chối ngay
    if (in_array($slugvi, $reserved_slugs)) {
      return 'reserved';
    }

    // 🔁 Kiểm tra trùng trong bảng dữ liệu
    foreach ($tables as $tbl) {
      // Bỏ qua nếu bảng không có cột slugvi
      $check_column_query = "SHOW COLUMNS FROM `$tbl` LIKE 'slugvi'";
      $check_column_result = $this->db->select($check_column_query);
      if (!$check_column_result || $check_column_result->num_rows == 0) continue;

      // Câu truy vấn kiểm tra slug
      $check_slug_query = "SELECT slugvi FROM `$tbl` WHERE slugvi = '$slugvi'";
      if ($table === $tbl && $exclude_id) {
        $check_slug_query .= " AND id != '$exclude_id'";
      }
      $check_slug_query .= " LIMIT 1";

      $result = $this->db->select($check_slug_query);
      if ($result && $result->num_rows > 0) {
        return $tbl; // trùng trong bảng cụ thể
      }
    }
    return false; // hợp lệ
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

  public function createFixedThumbnail($source_path, $thumb_width, $thumb_height, $background = false)
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

    if ($background && ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_WEBP)) {
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

    // Tạo tên & thư mục lưu
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $original_name = pathinfo($source_path, PATHINFO_FILENAME);
    $thumb_filename = $original_name . '_' . $thumb_width . 'x' . $thumb_height . '.webp';
    $thumb_path = $upload_dir . $thumb_filename;

    // Lưu thumbnail
    imagewebp($thumb, $thumb_path, 80);

    // Thêm watermark lên chính ảnh vừa tạo
    $this->addWatermark($thumb_path, $thumb_path, 9, 100, 0, 0);

    // Giải phóng bộ nhớ
    imagedestroy($image);
    imagedestroy($resized);
    imagedestroy($thumb);

    return basename($thumb_path);
  }


  public function add_thumb($source_path, $thumb_width, $thumb_height, $background = false)
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

    if ($background && ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_WEBP)) {
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
