<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class Functions
{
  private $db;
  private $d;
  private $fm;

  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
  }
  /* Alert */
  public function alert($notify = '')
  {
    echo '<script language="javascript">alert("' . $notify . '")</script>';
  }
  /* Decode html characters */
  public function decodeHtmlChars($htmlChars)
  {
    return htmlspecialchars_decode($htmlChars ?: '');
  }

  public function abort_404()
  {
    http_response_code(404);
    include '404.php';
    exit();
  }
  function renderSelectOptions($result, string $valueKey = 'id', string $labelKey = 'namevi', int|string $selectedId = 0): void
  {
    if (is_array($result) && !empty($result)) {
      foreach ($result as $row) {
        $selected = ($row[$valueKey] == $selectedId) ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($row[$valueKey]) . '" ' . $selected . '>' . htmlspecialchars($row[$labelKey]) . '</option>';
      }
    } elseif ($result instanceof mysqli_result && $result->num_rows > 0) {
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
    $tables = ['news', 'sanpham', 'gallery', 'danhmuc_c1', 'danhmuc_c2', 'tieuchi', 'danhgia', 'slideshow', 'setting', 'payment', 'static', 'social', 'seopage'];
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
  private function buildWhere(array $options): array
  {
    $where = [];
    $params = [];

    // Trạng thái (multi-value)
    if (!empty($options['status'])) {
      $statuses = is_array($options['status']) ? $options['status'] : explode(',', $options['status']);
      foreach ($statuses as $status) {
        $where[] = "FIND_IN_SET(?, status)";
        $params[] = trim($status);
      }
    }

    // Các điều kiện đơn giản + loại trừ ID
    $fields = ['id_list', 'id_cat', 'id_parent', 'exclude_id', 'type'];
    foreach ($fields as $field) {
      if (isset($options[$field]) && $options[$field] !== '') {
        $operator = ($field == 'exclude_id') ? '!=' : '=';
        $column = ($field == 'exclude_id') ? 'id' : $field;
        $where[] = "`$column` $operator ?";
        $params[] = $field === 'type' ? $options[$field] : (int)$options[$field];
      }
    }

    // Từ khóa (search)
    if (!empty($options['keyword'])) {
      $where[] = "`namevi` LIKE ?";
      $params[] = '%' . $options['keyword'] . '%';
    }

    $sql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
    return ['sql' => $sql, 'params' => $params];
  }

  public function show_data(array $options = [])
  {
    if (empty($options['table'])) return [];

    $table = $options['table'];
    $select = $options['select'] ?? '*';
    $order = $options['order'] ?? ' ORDER BY numb, id DESC';

    $whereData = $this->buildWhere($options);
    $sql = "SELECT $select FROM `$table`" . $whereData['sql'] . $order;

    // Phân trang
    if (!empty($options['records_per_page']) && !empty($options['current_page'])) {
      $limit = (int)$options['records_per_page'];
      $offset = ((int)$options['current_page'] - 1) * $limit;
      $sql .= " LIMIT $limit OFFSET $offset";
    } elseif (!empty($options['limit'])) {
      $limit = (int)$options['limit'];
      $offset = isset($options['offset']) ? (int)$options['offset'] : 0;
      $sql .= " LIMIT $limit OFFSET $offset";
    }
    $result = $this->db->rawQuery($sql, $whereData['params']);
    $data = [];
    if ($result instanceof mysqli_result) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    return $data;
  }

  public function show_data_join(array $options = [])
  {
    $select = $options['select'] ?? '*';
    $table = $options['table'] ?? '';
    $alias = $options['alias'] ?? '';
    $join = $options['join'] ?? '';
    $where = [];
    $bindings = [];

    if (!empty($options['where'])) {
      foreach ($options['where'] as $key => $val) {
        $where[] = "$key = ?";
        $bindings[] = $val;
      }
    }

    $sql = "SELECT $select FROM `$table`";
    if (!empty($alias)) $sql .= " $alias";
    if (!empty($join)) $sql .= " $join";
    if (!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);
    if (!empty($options['order_by'])) $sql .= " ORDER BY " . $options['order_by'];
    if (!empty($options['limit'])) $sql .= " LIMIT " . (int)$options['limit'];

    $result = $this->db->rawQuery($sql, $bindings);

    $data = [];
    if ($result instanceof mysqli_result) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    return $data;
  }

  public function count_data(array $options = []): int
  {
    if (empty($options['table'])) return 0;

    $table = $options['table'];
    $whereData = $this->buildWhere($options);
    $sql = "SELECT COUNT(*) FROM `$table`" . $whereData['sql'];

    $count = $this->db->rawQueryValue($sql, $whereData['params']);
    return is_numeric($count) ? (int)$count : 0;
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
  /* Delete file */
  public function deleteFile($file = '')
  {
    if ($file && file_exists($file)) {
      @unlink($file);
      $webp = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file);
      if (file_exists($webp)) @unlink($webp);
    }

    return true;
  }

  public function delete(int $id, string $table, string $type = '', $id_parent = null)
  {
    $id = (int)$id;
    $table = mysqli_real_escape_string($this->db->link, $table);

    // Lấy file (nếu có) và id_parent nếu là gallery
    $querySelect = "SELECT file" . ($table === 'tbl_gallery' ? ", id_parent" : "") . " FROM `$table` WHERE id = ?";
    $row = $this->db->rawQueryOne($querySelect, [$id]);

    // Xoá file vật lý nếu có
    if (!empty($row['file'])) {
      $this->deleteFile(UPLOADS . $row['file']);
    }
    // Xoá dữ liệu chính
    $delete_result = $this->db->execute("DELETE FROM `$table` WHERE id = ?", [$id]);

    // Xoá SEO
    if (!empty($type)) {
      $this->db->execute("DELETE FROM `tbl_seo` WHERE id_parent = ? AND `type` = ?", [$id, $type]);
    } else {
      $this->db->execute("DELETE FROM `tbl_seo` WHERE id_parent = ?", [$id]);
    }

    // Redirect
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
  public function deleteMultiple(string $listid, string $table, string $type = '', $id_parent = null)
  {
    $ids = array_filter(array_map('intval', explode(',', $listid)));
    if (empty($ids)) {
      $this->transfer("Danh sách ID không hợp lệ!", $this->getRedirectPath([
        'table' => $table,
        'type' => $type,
        'id_parent' => $id_parent
      ]), false);
    }

    // Lấy dữ liệu file và id_parent nếu có
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $querySelect = "SELECT id, file" . ($table === 'tbl_gallery' ? ", id_parent" : "") . " FROM `$table` WHERE id IN ($placeholders)";
    $resultSelect = $this->db->rawQuery($querySelect, $ids);

    $last_id_parent = 0;
    $seo_delete_ids = [];

    if ($resultSelect) {
      while ($row = $resultSelect->fetch_assoc()) {
        // Xoá file vật lý nếu có
        if (!empty($row['file'])) {
          $this->deleteFile(UPLOADS . $row['file']);
        }

        // Nếu là gallery, lưu lại id_parent
        if ($table === 'tbl_gallery') {
          $last_id_parent = $row['id_parent'];
        }

        // Ghi nhận id cần xoá SEO
        $seo_delete_ids[] = (int)$row['id'];
      }
    }

    // Xoá dữ liệu chính
    $queryDelete = "DELETE FROM `$table` WHERE id IN ($placeholders)";
    $resultDelete = $this->db->execute($queryDelete, $ids);

    // Xoá SEO liên quan
    foreach ($seo_delete_ids as $seo_id) {
      if (!empty($type)) {
        $this->db->execute("DELETE FROM `tbl_seo` WHERE id_parent = ? AND `type` = ?", [$seo_id, $type]);
      } else {
        $this->db->execute("DELETE FROM `tbl_seo` WHERE id_parent = ?", [$seo_id]);
      }
    }

    // Redirect
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
  public function convert_type(string $type)
  {
    $type = trim($type);
    if ($type === '') return '';

    $row = $this->db->rawQueryOne(
      "SELECT langvi FROM tbl_type WHERE lang_define = ? LIMIT 1",
      [$type]
    );

    if ($row && !empty($row['langvi'])) {
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
  /* Dump */
  public function dump($value = '', $exit = false)
  {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
    if ($exit) exit();
  }
  public function checkSlug(array $data = []): string|false
  {
    $slug = trim($data['slug'] ?? '');
    $table = $data['table'] ?? '';
    $exclude_id = isset($data['exclude_id']) ? (int)$data['exclude_id'] : 0;
    $lang = $data['lang'] ?? 'vi';

    // Nếu slug rỗng
    if ($slug === '') return false;

    $slug_col = 'slug' . $lang;

    // Slug cố định không được dùng
    $reserved = [
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

    if (in_array($slug, $reserved)) {
      return "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
    }

    // Danh sách các bảng có thể chứa slug
    $tables = ['tbl_danhmuc_c1', 'tbl_danhmuc_c2', 'tbl_sanpham', 'tbl_news'];

    foreach ($tables as $tbl) {
      // Escape tên cột slug
      $slug_col_safe = mysqli_real_escape_string($this->db->link, $slug_col);

      // Kiểm tra xem bảng có cột slug đó không
      $hasColumn = $this->db->rawQueryOne(
        "SHOW COLUMNS FROM `$tbl` LIKE '$slug_col_safe'"
      );
      if (!$hasColumn) continue;

      // Tạo câu SQL kiểm tra trùng
      $params = [$slug];
      $sql = "SELECT `$slug_col` FROM `$tbl` WHERE `$slug_col` = ?";

      if ($tbl === $table && $exclude_id > 0) {
        $sql .= " AND id != ?";
        $params[] = $exclude_id;
      }

      $sql .= " LIMIT 1";
      $exists = $this->db->rawQueryOne($sql, $params);

      if ($exists) {
        return "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
      }
    }

    return false; // không trùng
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

  public function getImage(array $data = []): string
  {
    $file    = $data['file'] ?? '';
    $class   = $data['class'] ?? '';
    $alt     = htmlspecialchars($data['alt'] ?? pathinfo($file, PATHINFO_FILENAME));
    $title   = htmlspecialchars($data['title'] ?? $alt);
    $id      = !empty($data['id']) ? ' id="' . htmlspecialchars($data['id']) . '"' : '';
    $style   = !empty($data['style']) ? ' style="' . htmlspecialchars($data['style']) . '"' : '';
    $width   = isset($data['width']) ? ' width="' . (int)$data['width'] . '"' : '';
    $height  = isset($data['height']) ? ' height="' . (int)$data['height'] . '"' : '';
    $lazy    = array_key_exists('lazy', $data) ? (bool)$data['lazy'] : true;
    $loading = $lazy ? ' loading="lazy"' : '';

    $errorImg = BASE_ADMIN . 'assets/img/noimage.png';
    $src = empty($file)
      ? NO_IMG
      : rtrim(BASE_ADMIN . UPLOADS, '/') . '/' . ltrim(htmlspecialchars($file), '/');

    return '<img src="' . $src . '"'
      . (!empty($class) ? ' class="' . htmlspecialchars($class) . '"' : '')
      . $id
      . $style
      . $width
      . $height
      . ' alt="' . $alt . '"'
      . ' title="' . $title . '"'
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
  function darkenColor($hex, $percent = 10)
  {
    $hex = ltrim($hex, '#');
    if (strlen($hex) == 3) {
      $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $r = max(0, min(255, $r - ($r * $percent / 100)));
    $g = max(0, min(255, $g - ($g * $percent / 100)));
    $b = max(0, min(255, $b - ($b * $percent / 100)));
    return sprintf("%02x%02x%02x", $r, $g, $b);
  }
}
