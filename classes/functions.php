<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class Functions
{
  private $db;
  private $fm;

  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
  }
  function renderSelectOptions($result, string $valueKey = 'id', string $labelKey = 'namevi', int|string $selectedId = 0): void
  {
    if ($result instanceof mysqli_result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $selected = ($row[$valueKey] == $selectedId) ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($row[$valueKey]) . '" ' . $selected . '>' . htmlspecialchars($row[$labelKey]) . '</option>';
      }
    } else {
      echo '<option disabled>Không có danh mục</option>';
    }
  }
  public function getRedirectPath($params = [])
  {
    $map = [];
    $tables = ['news', 'sanpham', 'gallery', 'danhmuc_c1', 'danhmuc_c2', 'tieuchi', 'danhgia', 'slideshow', 'setting', 'payment', 'static', 'social'];
    foreach ($tables as $tbl) {
      $map["tbl_{$tbl}"] = "{$tbl}_list";
    }
    $tableKey = $params['table'] ?? '';
    $page = $map[$tableKey] ?? 'dashboard';
    $query = "index.php?page={$page}";
    if (!empty($params['type'])) {
      $query .= "&type=" . urlencode($params['type']);
    }
    if (!empty($params['id_parent'])) {
      $query .= "&id=" . (int)$params['id_parent'];
    }
    return $query;
  }

  public function get_id($table, $id)
  {
    $table = mysqli_real_escape_string($this->db->link, $table);
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM `$table` WHERE id = '$id' LIMIT 1";
    return $this->db->select($query);
  }
  public function get_only_data(array $options)
  {
    $table = mysqli_real_escape_string($this->db->link, $options['table'] ?? '');
    if (empty($table)) return false;
    $where = [];
    foreach ($options as $field => $value) {
      if ($field == 'table') continue;
      if ($field === 'status') {
        $statuses = is_array($value) ? $value : explode(',', $value);
        $status_conditions = array_map(function ($s) {
          return "FIND_IN_SET('" . mysqli_real_escape_string($this->db->link, $s) . "', status)";
        }, $statuses);
        $where[] = '(' . implode(' AND ', $status_conditions) . ')';
      } else {
        $escaped_value = mysqli_real_escape_string($this->db->link, $value);
        $where[] = "`$field` = '$escaped_value'";
      }
    }
    $where_sql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    $query = "SELECT * FROM `$table` $where_sql LIMIT 1";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc() : false;
  }
  private function build_where_conditions($options)
  {
    $where = [];

    // Trạng thái
    if (!empty($options['status'])) {
      if (is_array($options['status'])) {
        $status_conditions = array_map(function ($s) {
          return "FIND_IN_SET('" . mysqli_real_escape_string($this->db->link, $s) . "', status)";
        }, $options['status']);
        $where[] = "(" . implode(" AND ", $status_conditions) . ")";
      } else {
        $statuses = explode(',', $options['status']);
        $status_conditions = array_map(function ($s) {
          return "FIND_IN_SET('" . mysqli_real_escape_string($this->db->link, $s) . "', status)";
        }, $statuses);
        $where[] = "(" . implode(" AND ", $status_conditions) . ")";
      }
    }

    // Theo danh mục
    if (!empty($options['id_list'])) {
      $where[] = "`id_list` = " . (int)$options['id_list'];
    }

    if (!empty($options['id_cat'])) {
      $where[] = "`id_cat` = " . (int)$options['id_cat'];
    }

    // Lọc hình ảnh con theo id_parent
    if (!empty($options['id_parent'])) {
      $where[] = "`id_parent` = " . (int)$options['id_parent'];
    }

    // Loại trừ sản phẩm theo id (ví dụ: show sản phẩm liên quan)
    if (!empty($options['exclude_id'])) {
      $where[] = "`id` != " . (int)$options['exclude_id'];
    }

    // Loại theo type
    if (!empty($options['type'])) {
      $type = mysqli_real_escape_string($this->db->link, $options['type']);
      $where[] = "`type` = '$type'";
    }

    // Tìm theo keyword
    if (!empty($options['keyword'])) {
      $keyword = mysqli_real_escape_string($this->db->link, $options['keyword']);
      $where[] = "`namevi` LIKE '%$keyword%'";
    }

    return $where;
  }

  public function show_data($options = [])
  {
    if (empty($options['table'])) return false;
    $tbl = mysqli_real_escape_string($this->db->link, $options['table']);
    $where = $this->build_where_conditions($options);
    $query = "SELECT * FROM `$tbl`";
    if (!empty($where)) {
      $query .= " WHERE " . implode(" AND ", $where);
    }
    $query .= " ORDER BY numb, id DESC";
    if (!empty($options['records_per_page']) && !empty($options['current_page'])) {
      $limit = (int)$options['records_per_page'];
      $offset = ((int)$options['current_page'] - 1) * $limit;
      $query .= " LIMIT $limit OFFSET $offset";
    }
    return $this->db->select($query);
  }
  public function count_data($options = [])
  {
    if (empty($options['table'])) return 0;
    $tbl = mysqli_real_escape_string($this->db->link, $options['table']);
    $where = $this->build_where_conditions($options);
    $query = "SELECT COUNT(*) AS total FROM `$tbl`";
    if (!empty($where)) {
      $query .= " WHERE " . implode(" AND ", $where);
    }
    $result = $this->db->select($query);
    if ($result && $row = $result->fetch_assoc()) {
      return (int)$row['total'];
    }
    return 0;
  }
  public function deleteMultiple($listid, $table, $type = '', $id_parent = null)
  {
    $ids = array_filter(array_map('intval', explode(',', $listid)));
    if (empty($ids)) {
      $this->transfer("Danh sách ID không hợp lệ!", $this->getRedirectPath($table, ['type' => $type, 'id_parent' => $id_parent]), false);
    }

    $idList = implode(',', $ids);
    $querySelect = "SELECT id, file" . ($table === 'tbl_gallery' ? ", id_parent" : "") . " FROM `$table` WHERE id IN ($idList)";
    $resultSelect = $this->db->select($querySelect);

    $last_id_parent = 0;
    if ($resultSelect && $resultSelect->num_rows > 0) {
      while ($row = $resultSelect->fetch_assoc()) {
        if (!empty($row['file'])) {
          $filePath = 'uploads/' . $row['file'];
          if (file_exists($filePath)) {
            unlink($filePath);
          }
        }
        if ($table === 'tbl_gallery') {
          $last_id_parent = $row['id_parent'];
        }
      }
    }
    $queryDelete = "DELETE FROM `$table` WHERE id IN ($idList)";
    $resultDelete = $this->db->delete($queryDelete);
    $redirectPath = $this->getRedirectPath([
      'table' => $table,
      'type' => $type,
      'id_parent' => $table === 'tbl_gallery' ? $last_id_parent : $id_parent
    ]);
    $this->transfer(
      $resultDelete ? "Xóa dữ liệu thành công!" : "Xóa dữ liệu thất bại!",
      $redirectPath,
      $resultDelete
    );
  }
  public function delete($id, $table, $type = '', $id_parent = null)
  {
    $id = intval($id);
    $table = mysqli_real_escape_string($this->db->link, $table);
    $querySelect = "SELECT file" . ($table === 'tbl_gallery' ? ", id_parent" : "") . " FROM `$table` WHERE id = $id";
    $result = $this->db->select($querySelect);
    $row = ($result) ? $result->fetch_assoc() : null;
    if ($row && !empty($row['file'])) {
      $filePath = "uploads/" . $row['file'];
      if (file_exists($filePath)) {
        unlink($filePath);
      }
    }
    $delete_result = $this->db->delete("DELETE FROM `$table` WHERE id = $id");
    $redirectPath = $this->getRedirectPath([
      'table' => $table,
      'type' => $type,
      'id_parent' => $table === 'tbl_gallery' ? ($id_parent ?? $row['id_parent'] ?? 0) : $id_parent
    ]);
    $this->transfer(
      $delete_result ? "Xóa dữ liệu thành công!" : "Xóa dữ liệu thất bại!",
      $redirectPath,
      $delete_result
    );
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
      $lang = $row['langvi'];
      return [
        'vi' => $lang,
        'slug' => $this->to_slug($lang)
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

    if (!empty($id) && isset($result['status'])) {
      $statuses = explode(',', $result['status']);
      return in_array($name, $statuses) ? 'checked' : '';
    }

    return $default ? 'checked' : '';
  }

  public function isSlugDuplicated($slug, $table, $exclude_id = '', $lang = 'vi')
  {
    $slug = mysqli_real_escape_string($this->db->link, trim($slug));
    $table = mysqli_real_escape_string($this->db->link, trim($table));
    $exclude_id = mysqli_real_escape_string($this->db->link, trim($exclude_id));
    $lang = mysqli_real_escape_string($this->db->link, trim($lang));
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
    if (in_array($slug, $reserved_slugs)) {
      return "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
    }
    // global $reserved_slugs;
    // if (in_array($slug, $reserved_slugs)) {
    //   return "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
    // }
    $tables = ['tbl_danhmuc_c1', 'tbl_danhmuc_c2', 'tbl_sanpham', 'tbl_news'];
    foreach ($tables as $tbl) {
      $slug_column = 'slug' . $lang;
      $check_column_query = "SHOW COLUMNS FROM `$tbl` LIKE '$slug_column'";
      $check_column_result = $this->db->select($check_column_query);
      if (!$check_column_result || $check_column_result->num_rows == 0) continue;
      $check_slug_query = "SELECT `$slug_column` FROM `$tbl` WHERE `$slug_column` = '$slug'";
      if ($table === $tbl && is_numeric($exclude_id) && (int)$exclude_id > 0) {
        $check_slug_query .= " AND id != '$exclude_id'";
      }
      $check_slug_query .= " LIMIT 1";
      // file_put_contents('log_sql_query.txt', $check_slug_query . PHP_EOL, FILE_APPEND);
      $result = $this->db->select($check_slug_query);
      if ($result && $result->num_rows > 0) {
        return "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
      }
    }

    return false;
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
        $newColor = imagecolorallocatealpha(
          $tmp,
          (int)round($color['red']),
          (int)round($color['green']),
          (int)round($color['blue']),
          (int)round($alpha)
        );
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
      $target_wm_width = min($max_size, max($min_size, $target_wm_width));
    } else {
      $target_wm_width = max($min_size, $target_wm_width);
    }
    $target_wm_width = min($target_wm_width, $img_width);
    $target_wm_width = intval($target_wm_width);
    if ($target_wm_width <= 0) {
      return false;
    }
    $target_wm_height = intval($wm_orig_height * ($target_wm_width / $wm_orig_width));
    $scaled_wm = imagecreatetruecolor($target_wm_width, $target_wm_height);
    imagealphablending($scaled_wm, false);
    imagesavealpha($scaled_wm, true);
    imagecopyresampled($scaled_wm, $watermark_src, 0, 0, 0, 0, $target_wm_width, $target_wm_height, $wm_orig_width, $wm_orig_height);
    imagedestroy($watermark_src);
    $watermark = $this->applyOpacity($scaled_wm, $opacity);
    imagedestroy($scaled_wm);
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
    $x += $offset_x;
    $y += $offset_y;
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
    $is_source_webp = ($image_type === IMAGETYPE_WEBP);
    if (in_array($image_type, [IMAGETYPE_PNG, IMAGETYPE_WEBP])) {
      imagepalettetotruecolor($image);
      imagealphablending($image, true);
      imagesavealpha($image, true);
    }
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
    if (is_array($background) && count($background) === 4 && $background[3] == 127 && ($convert_webp || $is_source_webp)) {
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
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $original_name = pathinfo($source_path, PATHINFO_FILENAME);
    $thumb_ext = ($convert_webp || $is_source_webp) ? 'webp' : $ext;
    $thumb_filename = $original_name . '_' . $thumb_name . '.' . $thumb_ext;
    $thumb_path = $upload_dir . $thumb_filename;
    if ($convert_webp || $is_source_webp) {
      $success = imagewebp($thumb, $thumb_path, 100);
      if (!$success) {
        unlink($thumb_path);
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
  public function Upload(array $options)
  {
    $files = $options['file'] ?? [];
    $custom_name = $options['custom_name'] ?? '';
    $thumb_name = $options['thumb'] ?? '';
    $background = $options['background'] ?? [255, 255, 255, 0];
    $old_file_path = $options['old_file_path'] ?? '';
    $watermark = $options['watermark'] ?? false;
    $convert_webp = $options['convert_webp'] ?? false;
    $file_name = $files["name"] ?? '';
    $file_tmp = $files["tmp_name"] ?? '';
    if (!empty($file_name) && !empty($file_tmp)) {
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $upload_dir = 'uploads/';
      if (!empty($custom_name)) {
        $slug_name = $this->to_slug($custom_name);
        $slug_name .= '-' . substr(md5(uniqid() . microtime(true)), 0, 4);
        $filename = $this->generateUniqueFilename($upload_dir, $slug_name, $file_ext);
      } else {
        $filename = substr(md5(time() . rand() . uniqid()), 0, 10) . '.' . $file_ext;
      }
      $uploaded_image = $upload_dir . $filename;
      if (move_uploaded_file($file_tmp, $uploaded_image)) {
        if (preg_match('/^(\d+)x(\d+)(x\d+)?$/', $thumb_name)) {
          return $this->ImageUpload($uploaded_image, $file_name, $old_file_path, $thumb_name, $background, $watermark, $convert_webp);
        } else {
          if (!empty($old_file_path) && file_exists($old_file_path)) {
            unlink($old_file_path);
          }
          return basename($uploaded_image);
        }
      }
    }
    return '';
  }
  private function generateUniqueFilename($upload_dir, $slug_name, $ext)
  {
    $i = 0;
    do {
      $suffix = $i > 0 ? '-' . $i : '';
      $filename = $slug_name . $suffix . '.' . $ext;
      $file_path = rtrim($upload_dir, '/') . '/' . $filename;
      $i++;
    } while (file_exists($file_path));

    return $filename;
  }
  function renderPagination($current_page, $total_pages, $base_url = 'index.php')
  {
    if ($total_pages <= 1) return '';
    $queryParams = $_GET;
    unset($queryParams['p']);
    $queryString = http_build_query($queryParams);
    $queryString = $queryString ? $queryString . '&' : '';

    $html = '<ul class="pagination flex-wrap justify-content-center mb-0">';
    $html .= '<li class="page-item"><a class="page-link">Trang ' . $current_page . ' / ' . $total_pages . '</a></li>';

    if ($current_page > 1) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?' . $queryString . 'p=' . ($current_page - 1) . '">Trước</a></li>';
    }

    $range = 2;
    for ($i = 1; $i <= $total_pages; $i++) {
      if (
        $i == 1 || $i == 2 || $i == $total_pages || $i == $total_pages - 1 ||
        ($i >= $current_page - $range && $i <= $current_page + $range)
      ) {
        $active_class = ($i == $current_page) ? 'active' : '';
        $html .= '<li class="page-item ' . $active_class . '">';
        $html .= '<a class="page-link" href="' . $base_url . '?' . $queryString . 'p=' . $i . '">' . $i . '</a>';
        $html .= '</li>';
      } elseif (
        ($i == 3 && $current_page - $range > 4) ||
        ($i == $total_pages - 2 && $current_page + $range < $total_pages - 3)
      ) {
        $html .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
      }
    }

    if ($current_page < $total_pages) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?' . $queryString . 'p=' . ($current_page + 1) . '">Tiếp</a></li>';
      $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?' . $queryString . 'p=' . $total_pages . '">Cuối</a></li>';
    }

    $html .= '</ul>';
    return $html;
  }
  function renderPagination_tc($current_page, $total_pages, $base_url)
  {
    if ($total_pages <= 1) return '';
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
        $i == 1 || $i == $total_pages || ($i >= $current_page - $range && $i <= $current_page + $range)
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

  public function getImage($data = [])
  {
    $file     = $data['file'] ?? '';
    $class    = $data['class'] ?? '';
    $alt      = htmlspecialchars($data['alt'] ?? '');
    $title    = htmlspecialchars($data['title'] ?? $alt);
    $id       = !empty($data['id']) ? ' id="' . htmlspecialchars($data['id']) . '"' : '';
    $style    = !empty($data['style']) ? ' style="' . htmlspecialchars($data['style']) . '"' : '';
    $width    = isset($data['width']) ? ' width="' . (int)$data['width'] . '"' : '';
    $height   = isset($data['height']) ? ' height="' . (int)$data['height'] . '"' : '';
    $lazy     = array_key_exists('lazy', $data) ? (bool)$data['lazy'] : true;
    $loading  = $lazy ? ' loading="lazy"' : '';

    $errorImg = BASE_ADMIN . 'assets/img/noimage.png';
    $src      = empty($file) ? NO_IMG : BASE_ADMIN . UPLOADS . htmlspecialchars($file);

    return '<img src="' . $src . '"'
      . (!empty($class) ? ' class="' . htmlspecialchars($class) . '"' : '')
      . $id
      . $style
      . $width
      . $height
      . (!empty($alt) ? ' alt="' . $alt . '"' : '')
      . (!empty($title) ? ' title="' . $title . '"' : '')
      . $loading
      . ' onerror="this.src=\'' . $errorImg . '\'"'
      . '>';
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
  public function isGoogleSpeed()
  {
    if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') === false) {
      return false;
    }
    return true;
  }
  public function stringRandom($sokytu = 10)
  {
    $str = '';
    $chuoi = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $max = strlen($chuoi) - 1;
    for ($i = 0; $i < $sokytu; $i++) {
      $str .= $chuoi[mt_rand(0, $max)];
    }
    return $str;
  }
  public function generateHash($length = 10)
  {
    return $this->stringRandom($length);
  }
}
