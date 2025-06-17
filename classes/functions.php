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

  function transfer($msg, $page = 'dashboard', $numb = true)
  {
    $_SESSION['transfer_data'] = [
      'msg' => $msg,
      'page' => $page,
      'numb' => $numb
    ];

    define('IS_TRANSFER', true);
    header("Location: index.php?page=transfer");
    exit();
  }


  public function convert_type($type)
  {
    $type = trim($type);
    if ($type === '') return '';

    $escapedType = mysqli_real_escape_string($this->db->link, $type);
    $query = "SELECT langvi FROM tbl_type WHERE lang_define = '$escapedType' LIMIT 1";
    $result = $this->db->select($query);

    if ($result && $row = $result->fetch_assoc()) {
      $langvi = $row['langvi'];
      return [
        'vi' => $langvi,
        'slug' => $this->to_slug($langvi)
      ];
    }
    return $type;
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

  public function deleteMultiple($listid, $table, $imageColumn, $redirectUrl, $hasIdParent = false)
  {
    $ids = array_filter(array_map('intval', explode(',', $listid)));
    if (empty($ids)) {
      $this->transfer("Danh sách ID không hợp lệ!", "index.php?page=$redirectUrl", false);
    }
    $idList = implode(',', $ids);
    $querySelect = "SELECT id, `$imageColumn`" . ($hasIdParent ? ", id_parent" : "") . " FROM `$table` WHERE id IN ($idList)";
    $resultSelect = $this->db->select($querySelect);
    $last_id_parent = 0;
    if ($resultSelect && $resultSelect->num_rows > 0) {
      while ($row = $resultSelect->fetch_assoc()) {
        if (!empty($row[$imageColumn])) {
          $filePath = 'uploads/' . $row[$imageColumn];
          if (file_exists($filePath)) {
            unlink($filePath);
          }
        }
        if ($hasIdParent) {
          $last_id_parent = $row['id_parent'];
        }
      }
    }
    $queryDelete = "DELETE FROM `$table` WHERE id IN ($idList)";
    $resultDelete = $this->db->delete($queryDelete);
    if ($resultDelete) {
      if ($hasIdParent && $table == 'tbl_gallery') {
        $this->transfer("Xóa dữ liệu thành công!", "index.php?page=gallery_list&id=$last_id_parent", true);
      } else {
        $this->transfer("Xóa dữ liệu thành công!", "index.php?page=$redirectUrl", true);
      }
    } else {
      $this->transfer("Xóa dữ liệu thất bại!", "index.php?page=$redirectUrl", false);
    }
  }

  public function delete($id, $table, $imageColumn, $redirect_url, $id_parent = null)
  {
    $id = intval($id);
    $table = mysqli_real_escape_string($this->db->link, $table);
    $imageColumn = mysqli_real_escape_string($this->db->link, $imageColumn);

    // Lấy dữ liệu ảnh & id_parent nếu có
    $check_query = "SELECT `$imageColumn`" . ($table === 'tbl_gallery' ? ", id_parent" : "") . " FROM `$table` WHERE id = '$id'";
    $result = $this->db->select($check_query);
    $row = ($result) ? $result->fetch_assoc() : null;

    if ($row && !empty($row[$imageColumn])) {
      $filePath = "uploads/" . $row[$imageColumn];
      if (file_exists($filePath)) {
        unlink($filePath);
      }
    }

    $query = "DELETE FROM `$table` WHERE id = '$id'";
    $delete_result = $this->db->delete($query);

    // Chuyển hướng
    if ($delete_result) {
      if ($table === 'tbl_gallery') {
        // Ưu tiên id_parent nếu truyền vào, nếu không lấy từ bản ghi
        $parentId = $id_parent ?? ($row['id_parent'] ?? 0);
        $this->transfer("Xóa hình ảnh thành công", "index.php?page=gallery_list&id=" . intval($parentId));
      } else {
        $this->transfer("Xóa dữ liệu thành công", "index.php?page=" . $redirect_url, true);
      }
    } else {
      if ($table === 'tbl_gallery') {
        $this->transfer("Xóa hình ảnh thất bại", "index.php?page=gallery_list&id=" . intval($id_parent ?? 0));
      } else {
        $this->transfer("Xóa dữ liệu thất bại", "index.php?page=" . $redirect_url, false);
      }
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
      return 'Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.';
    }

    // 🔁 Kiểm tra trùng trong bảng dữ liệu
    foreach ($tables as $tbl) {
      // Bỏ qua nếu bảng không có cột slugvi
      $check_column_query = "SHOW COLUMNS FROM `$tbl` LIKE 'slugvi'";
      $check_column_result = $this->db->select($check_column_query);
      if (!$check_column_result || $check_column_result->num_rows == 0) continue;

      // Câu truy vấn kiểm tra slug
      $check_slug_query = "SELECT slugvi FROM `$tbl` WHERE slugvi = '$slugvi'";
      if ($table === $tbl && is_numeric($exclude_id) && (int)$exclude_id > 0) {
        $check_slug_query .= " AND id != '$exclude_id'";
      }
      $check_slug_query .= " LIMIT 1";

      $result = $this->db->select($check_slug_query);
      if ($result && $result->num_rows > 0) {
        return "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
      }
    }
    return false; // hợp lệ
  }

  private function applyOpacity($image, $opacity)
  {
    $opacity = max(0, min(100, $opacity));
    $w = imagesx($image);
    $h = imagesy($image);

    $tmp = imagecreatetruecolor($w, $h);
    imagealphablending($tmp, false);
    imagesavealpha($tmp, true);

    for ($x = 0; $x < $w; ++$x) {
      for ($y = 0; $y < $h; ++$y) {
        $rgba = imagecolorat($image, $x, $y);
        $alpha = ($rgba & 0x7F000000) >> 24;
        $alpha = 127 - ((127 - $alpha) * ($opacity / 100));
        $color = imagecolorsforindex($image, $rgba);
        $newColor = imagecolorallocatealpha($tmp, $color['red'], $color['green'], $color['blue'], $alpha);
        imagesetpixel($tmp, $x, $y, $newColor);
      }
    }

    return $tmp;
  }

  public function addWatermark($source_path, $destination_path)
  {
    $result = $this->db->select("SELECT * FROM tbl_watermark LIMIT 1");
    $row = $result ? $result->fetch_assoc() : null;
    if (!$row || empty($row['watermark'])) return false;

    $position = isset($row['position']) ? intval($row['position']) : 9;
    $opacity = isset($row['opacity']) ? floatval($row['opacity']) : 100;
    $offset_x = isset($row['offset_x']) ? intval($row['offset_x']) : 0;
    $offset_y = isset($row['offset_y']) ? intval($row['offset_y']) : 0;

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

    // Load watermark gốc
    $watermark_src = imagecreatefrompng($watermark_path);
    if (!$watermark_src) return false;
    imagesavealpha($watermark_src, true);

    $wm_orig_width = imagesx($watermark_src);
    $wm_orig_height = imagesy($watermark_src);

    // Scale watermark
    $per = isset($row['per']) ? floatval($row['per']) : 2;
    $small_per = isset($row['small_per']) ? floatval($row['small_per']) : 3;
    $max_size = isset($row['max']) ? intval($row['max']) : 120;
    $min_size = isset($row['min']) ? intval($row['min']) : 120;
    $scale_percent = ($img_width < 300) ? $small_per : $per;

    $target_wm_width = $img_width * $scale_percent / 100;
    $target_wm_width = max(20, min($target_wm_width, $img_width * 0.5));
    $target_wm_height = intval($wm_orig_height * ($target_wm_width / $wm_orig_width));

    // Resize watermark
    $scaled_wm = imagecreatetruecolor($target_wm_width, $target_wm_height);
    imagealphablending($scaled_wm, false);
    imagesavealpha($scaled_wm, true);
    imagecopyresampled($scaled_wm, $watermark_src, 0, 0, 0, 0, $target_wm_width, $target_wm_height, $wm_orig_width, $wm_orig_height);
    imagedestroy($watermark_src);

    // Áp dụng opacity
    $watermark = $this->applyOpacity($scaled_wm, $opacity);
    imagedestroy($scaled_wm);

    // Tính vị trí
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

    // Áp dụng offset từ config
    $x += $offset_x;
    $y += $offset_y;

    // Dán watermark vào ảnh
    imagecopy($image, $watermark, $x, $y, 0, 0, $target_wm_width, $target_wm_height);

    // Lưu ảnh đầu ra
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

  public function createFixedThumbnail($source_path, $thumb_name, $background = false, $add_watermark = false, $convert_webp = false)
  {
    if (!file_exists($source_path)) return false;

    if (!preg_match('/^(\d+)x(\d+)(x(\d+))?$/', $thumb_name, $matches)) return false;

    $thumb_width = (int)$matches[1];
    $thumb_height = (int)$matches[2];
    $zoom_crop = isset($matches[4]) ? (int)$matches[4] : 1;

    $image_info = getimagesize($source_path);
    if (!$image_info) return false;

    list($width_orig, $height_orig, $image_type) = $image_info;

    switch ($image_type) {
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($source_path);
        $ext = 'jpg';
        break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($source_path);
        $ext = 'png';
        break;
      case IMAGETYPE_WEBP:
        $image = imagecreatefromwebp($source_path);
        $ext = 'webp';
        break;
      default:
        return false;
    }

    // Hỗ trợ alpha cho ảnh gốc (PNG, WebP)
    if (in_array($image_type, [IMAGETYPE_PNG, IMAGETYPE_WEBP])) {
      imagepalettetotruecolor($image);
      imagealphablending($image, true);
      imagesavealpha($image, true);
    }

    // Tính toán crop
    $src_x = $src_y = 0;
    $src_w = $width_orig;
    $src_h = $height_orig;
    $dst_w = $thumb_width;
    $dst_h = $thumb_height;
    $src_ratio = $width_orig / $height_orig;
    $dst_ratio = $thumb_width / $thumb_height;

    if ($zoom_crop == 2) {
      if ($src_ratio > $dst_ratio) $dst_h = intval($thumb_width / $src_ratio);
      else $dst_w = intval($thumb_height * $src_ratio);
    } elseif ($zoom_crop == 1) {
      if ($src_ratio > $dst_ratio) {
        $src_w = intval($height_orig * $dst_ratio);
        $src_x = intval(($width_orig - $src_w) / 2);
      } else {
        $src_h = intval($width_orig / $dst_ratio);
        $src_y = intval(($height_orig - $src_h) / 2);
      }
    } else {
      if ($src_ratio > $dst_ratio) $dst_h = intval($thumb_width / $src_ratio);
      else $dst_w = intval($thumb_height * $src_ratio);
    }

    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

    // Fill nền
    if (is_array($background) && count($background) === 4 && $background[3] == 127 && $convert_webp) {
      imagealphablending($thumb, false);
      imagesavealpha($thumb, true);
      $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
      imagefill($thumb, 0, 0, $transparent);
    } elseif (is_array($background) && count($background) === 4) {
      imagealphablending($thumb, true);
      imagesavealpha($thumb, true);
      $bg_color = imagecolorallocatealpha($thumb, $background[0], $background[1], $background[2], $background[3]);
      imagefill($thumb, 0, 0, $bg_color);
    } else {
      $bg_color = imagecolorallocate($thumb, 255, 255, 255);
      imagefill($thumb, 0, 0, $bg_color);
    }

    $dst_x = intval(($thumb_width - $dst_w) / 2);
    $dst_y = intval(($thumb_height - $dst_h) / 2);
    imagecopyresampled($thumb, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

    // Tên file
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $original_name = pathinfo($source_path, PATHINFO_FILENAME);
    $thumb_ext = $convert_webp ? 'webp' : $ext;
    $thumb_filename = $original_name . '_' . $thumb_name . '.' . $thumb_ext;
    $thumb_path = $upload_dir . $thumb_filename;

    // Ghi file
    if ($convert_webp) {
      $success = imagewebp($thumb, $thumb_path, 100);
      if (!$success) {
        unlink($thumb_path); // xóa file lỗi nếu có
        return false;
      }
    } elseif ($ext === 'jpg') {
      $success = imagejpeg($thumb, $thumb_path, 90);
    } elseif ($ext === 'png') {
      $success = imagepng($thumb, $thumb_path);
    } else {
      $success = false;
    }

    if (!$success) return false;

    if ($add_watermark && method_exists($this, 'addWatermark')) {
      $this->addWatermark($thumb_path, $thumb_path);
    }

    imagedestroy($image);
    imagedestroy($thumb);

    return basename($thumb_path);
  }

  public function ImageUpload(
    $file_source_path,
    $original_name,
    $old_file_path,
    $thumb_name,
    $background,
    $watermark = false,
    $convert_webp = false
  ) {
    // Gọi tạo thumbnail, truyền watermark và convert_webp trực tiếp
    $thumb_filename = $this->createFixedThumbnail($file_source_path, $thumb_name, $background, $watermark, $convert_webp);

    if (!$thumb_filename) {
      $thumb_filename = basename($file_source_path);
    } else {
      if (file_exists($file_source_path)) unlink($file_source_path);
    }

    if (!empty($old_file_path) && file_exists($old_file_path)) {
      unlink($old_file_path);
    }

    return $thumb_filename;
  }


  public function Upload(
    $files,
    $thumb_name,
    array $background,
    string $old_file_path = '',
    $watermark = false,
    $convert_webp = false
  ) {
    $file_name = $files["file"]["name"] ?? '';
    $file_tmp = $files["file"]["tmp_name"] ?? '';

    if (!empty($file_name) && !empty($file_tmp)) {
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $unique_image = substr(md5(time() . rand() . uniqid()), 0, 10) . '.' . $file_ext;
      $uploaded_image = "uploads/" . $unique_image;

      if (move_uploaded_file($file_tmp, $uploaded_image)) {
        return $this->ImageUpload($uploaded_image, $file_name, $old_file_path, $thumb_name, $background, $watermark, $convert_webp);
      }
    }
    return '';
  }

  public function phantrang($tbl, $type = null)
  {
    $tbl = mysqli_real_escape_string($this->db->link, $tbl);
    $query = "SELECT COUNT(*) as total FROM `$tbl`";
    if (!empty($type)) {

      $type = mysqli_real_escape_string($this->db->link, $type);
      $query .= " WHERE type = '$type'";
    }
    $result = $this->db->select($query);
    return $result ? (int)$result->fetch_assoc()['total'] : 0;
  }

  function renderPagination($current_page, $total_pages, $base_url = '?p=')
  {
    if ($total_pages <= 1) return '';

    $html = '<ul class="pagination flex-wrap justify-content-center mb-0">';
    $html .= '<li class="page-item"><a class="page-link">Trang ' . $current_page . ' / ' . $total_pages . '</a></li>';

    if ($current_page > 1) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . ($current_page - 1) . '">Trước</a></li>';
    }

    $range = 2;
    for ($i = 1; $i <= $total_pages; $i++) {
      if (
        $i == 1 || $i == 2 || $i == $total_pages || $i == $total_pages - 1 ||
        ($i >= $current_page - $range && $i <= $current_page + $range)
      ) {
        $active_class = ($i == $current_page) ? 'active' : '';
        $html .= '<li class="page-item ' . $active_class . '">';
        $html .= '<a class="page-link" href="' . $base_url . $i . '">' . $i . '</a>';
        $html .= '</li>';
      } elseif (
        ($i == 3 && $current_page - $range > 4) ||
        ($i == $total_pages - 2 && $current_page + $range < $total_pages - 3)
      ) {
        $html .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
      }
    }

    if ($current_page < $total_pages) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . ($current_page + 1) . '">Tiếp</a></li>';
      $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . $total_pages . '">Cuối</a></li>';
    }

    $html .= '</ul>';
    return $html;
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

  public function to_slug($string)
  {
    $search = array(
      '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
      '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
      '#(ì|í|ị|ỉ|ĩ)#',
      '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
      '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
      '#(ỳ|ý|ỵ|ỷ|ỹ)#',
      '#(đ)#',
      '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
      '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
      '#(Ì|Í|Ị|Ỉ|Ĩ)#',
      '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
      '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
      '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
      '#(Đ)#',
      "/[^a-zA-Z0-9\-\_]/",
    );
    $replace = array(
      'a',
      'e',
      'i',
      'o',
      'u',
      'y',
      'd',
      'A',
      'E',
      'I',
      'O',
      'U',
      'Y',
      'D',
      '-',
    );
    $string = preg_replace($search, $replace, $string);
    $string = preg_replace('/(-)+/', '-', $string);
    $string = strtolower($string);
    return $string;
  }

  public function generateHash()
  {
    if (!isset($_SESSION['upload_hash'])) {
      $_SESSION['upload_hash'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['upload_hash'];
  }
}
