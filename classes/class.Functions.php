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
  function isItemActive(array $activeList, string $currentPage, string $currentType): bool
  {
    foreach ($activeList as $activeItem) {
      parse_str(ltrim($activeItem, '?'), $activeParams);
      if (($activeParams['page'] ?? '') === $currentPage && ($activeParams['type'] ?? '') === $currentType) {
        return true;
      }
    }
    return false;
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
  public function getLinkCategory($table = '', $selectedId = 0, $title_select = 'Chọn danh mục')
  {
    $params = [];
    $where = ' WHERE 1';
    $name = $id = '';

    // Xác định cấp danh mục và điều kiện
    if (str_contains($table, '_c1')) {
      $name = $id = 'id_list'; // cấp 1
    } elseif (str_contains($table, '_c2')) {
      $name = $id = 'id_cat';
      $id_list = $_REQUEST['id_list'] ?? 0;
      if ((int)$id_list > 0) {
        $where .= ' AND id_list = ?';
        $params[] = (int)$id_list;
      }
    } elseif (str_contains($table, '_c3')) {
      $name = $id = 'id_item';
      $id_cat = $_REQUEST['id_cat'] ?? 0;
      if ((int)$id_cat > 0) {
        $where .= ' AND id_cat = ?';
        $params[] = (int)$id_cat;
      }
    } else {
      $name = $id = 'id';
    }

    // Truy vấn
    $rows = $this->db->rawQuery("SELECT id, namevi FROM `$table` $where ORDER BY numb, id DESC", $params);

    // Tạo <select>
    $str = '<select id="' . $id . '" name="' . $name . '" onchange="onchangeCategory($(this))" class="form-control filter-category select2">';
    $str .= '<option value="0">' . htmlspecialchars($title_select) . '</option>';

    if (!empty($rows)) {
      foreach ($rows as $row) {
        $selected = ($selectedId == $row['id']) ? 'selected' : '';
        $str .= '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['namevi']) . '</option>';
      }
    } else {
      $str .= '<option disabled>Không có dữ liệu</option>';
    }

    $str .= '</select>';
    return $str;
  }
  public function getAjaxCategory($table = '', $selectedId = 0, $id_list = null, $id_cat = null, $title_select = 'Chọn danh mục')
  {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $params = [];
    $where = ' WHERE 1';
    $class = 'form-control select2 select-category';

    // Cấu hình theo cấp
    $levels = [
      '_c1' => [
        'field' => 'id_list',
        'data_level' => '0',
        'data_table' => 'tbl_danhmuc_c2',
        'data_child' => 'id_cat',
        'filters' => []
      ],
      '_c2' => [
        'field' => 'id_cat',
        'data_level' => '1',
        'data_table' => 'tbl_danhmuc_c3',
        'data_child' => 'id_item',
        'filters' => [
          'id_list' => $id_list
        ]
      ],
      '_c3' => [
        'field' => 'id_item',
        'data_level' => '2',
        'data_table' => '',
        'data_child' => '',
        'filters' => [
          'id_list' => $id_list,
          'id_cat' => $id_cat
        ]
      ]
    ];

    // Xác định cấp theo tên bảng
    $matched = null;
    foreach ($levels as $key => $conf) {
      if (str_contains($table, $key)) {
        $matched = $conf;
        break;
      }
    }

    // Mặc định nếu không khớp cấp
    if (!$matched) {
      $field = $name = 'id';
      $data_level = '';
      $data_table = '';
      $data_child = '';
    } else {
      $field = $name = $matched['field'];
      $data_level = 'data-level="' . $matched['data_level'] . '"';
      $data_table = 'data-table="' . $matched['data_table'] . '"';
      $data_child = 'data-child="' . $matched['data_child'] . '"';

      // Áp dụng điều kiện lọc
      foreach ($matched['filters'] as $filterField => $filterValue) {
        if ($filterValue > 0) {
          $where .= " AND {$filterField} = ?";
          $params[] = $filterValue;
        }
      }
    }

    // Query
    $rows = $this->db->rawQuery("SELECT id, namevi FROM `$table` $where ORDER BY numb, id DESC", $params);

    // Render HTML select
    $str = '<select id="' . $field . '" name="' . $name . '" ' . $data_level . ' ' . $data_table . ' ' . $data_child . ' class="' . $class . '">';
    $str .= '<option value="0">' . htmlspecialchars($title_select) . '</option>';

    if (!empty($rows)) {
      foreach ($rows as $row) {
        $selected = ($selectedId == $row['id']) ? 'selected' : '';
        $str .= '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['namevi']) . '</option>';
      }
    } else {
      $str .= '<option disabled>Không có dữ liệu</option>';
    }

    $str .= '</select>';
    return $str;
  }

  public function getRedirectPath($params = [])
  {
    $map = [];
    $tables = ['news', 'product', 'gallery', 'product_list', 'product_cat', 'tieuchi', 'danhgia', 'slideshow', 'setting', 'payment', 'static', 'social', 'seopage'];
    foreach ($tables as $tbl) {
      $map["tbl_{$tbl}"] = "{$tbl}_man";
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

  private function buildWhere(array $options): array
  {
    $where = [];
    $params = [];
    if (!empty($options['status'])) {
      $statuses = is_array($options['status']) ? $options['status'] : explode(',', $options['status']);
      foreach ($statuses as $status) {
        $where[] = "FIND_IN_SET(?, status)";
        $params[] = trim($status);
      }
    }
    $fields = ['id_list', 'id_cat', 'id_parent', 'exclude_id', 'type'];
    foreach ($fields as $field) {
      if (isset($options[$field]) && $options[$field] !== '') {
        $operator = ($field == 'exclude_id') ? '!=' : '=';
        $column = ($field == 'exclude_id') ? 'id' : $field;
        $where[] = "`$column` $operator ?";
        $params[] = $field === 'type' ? $options[$field] : (int)$options[$field];
      }
    }
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
    if (!$file) return true;

    // Tên file gốc không có thư mục
    $filename = basename($file); // vd: abc.jpg
    $filenameNoExt = pathinfo($filename, PATHINFO_FILENAME); // abc
    $ext = pathinfo($filename, PATHINFO_EXTENSION); // jpg

    // Thư mục uploads/
    $baseDir = rtrim(UPLOADS, '/');

    // ✅ Xoá gốc .jpg / .png / .webp trong uploads/
    $mainFile = $baseDir . '/' . $filename;
    if (file_exists($mainFile)) @unlink($mainFile);

    // Nếu là JPG/PNG, cũng thử xoá .webp tương ứng
    if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
      $webpMain = $baseDir . '/' . $filenameNoExt . '.webp';
      if (file_exists($webpMain)) @unlink($webpMain);
    }

    // ✅ Xoá các thumbnail trong uploads/500x500x1/... và bản .webp nếu có
    $thumbDirs = glob($baseDir . '/*x*x*', GLOB_ONLYDIR);
    foreach ($thumbDirs as $dir) {
      // Xoá bản jpg/png
      $thumbFile = $dir . '/' . $filename;
      if (file_exists($thumbFile)) @unlink($thumbFile);

      // Xoá bản webp
      $thumbWebp = $dir . '/' . $filenameNoExt . '.webp';
      if (file_exists($thumbWebp)) @unlink($thumbWebp);
    }

    return true;
  }
  public function delete(int $id, string $table, string $type = '', $id_parent = null)
  {
    $id = (int)$id;
    $table = mysqli_real_escape_string($this->db->link, $table);

    // Lấy file (nếu có) và id_parent nếu là gallery
    $row = $this->db->rawQueryOne("SELECT file" . ($table === 'tbl_gallery' ? ", id_parent" : "") . " FROM `$table` WHERE id = ?", [$id]);

    // Xoá file vật lý nếu có
    !empty($row['file']) && $this->deleteFile(UPLOADS . $row['file']);
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

    // Chuẩn bị câu truy vấn SELECT
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $resultSelect = $this->db->rawQuery("SELECT id, file" . ($table === 'tbl_gallery' ? ", id_parent" : "") . " FROM `$table` WHERE id IN ($placeholders)", $ids);
    $seo_delete_ids = [];
    $last_id_parent = 0;
    if (!empty($resultSelect) && is_iterable($resultSelect)) {
      foreach ($resultSelect as $row) {
        if (!empty($row['file'])) {
          $this->deleteFile(UPLOADS . $row['file']);
        }
        if ($table === 'tbl_gallery' && isset($row['id_parent'])) {
          $last_id_parent = $row['id_parent'];
        }
        $seo_delete_ids[] = (int)$row['id'];
      }
    }
    $queryDelete = "DELETE FROM `$table` WHERE id IN ($placeholders)";
    $resultDelete = $this->db->execute($queryDelete, $ids);
    foreach ($seo_delete_ids as $seo_id) {
      if (!empty($type)) {
        $this->db->execute("DELETE FROM `tbl_seo` WHERE id_parent = ? AND `type` = ?", [$seo_id, $type]);
      } else {
        $this->db->execute("DELETE FROM `tbl_seo` WHERE id_parent = ?", [$seo_id]);
      }
    }
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
  function is_checked($name, $status = '', $id = null, $default = true)
  {
    // Nếu đang submit lại form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Ưu tiên dạng name="status[hienthi]"
      if (isset($_POST['status'][$name])) {
        return $_POST['status'][$name] ? 'checked' : '';
      }

      // Hoặc name="hienthi"
      if (isset($_POST[$name])) {
        return $_POST[$name] ? 'checked' : '';
      }

      return '';
    }

    // Nếu thêm mới (chưa có ID) → luôn checked theo $default
    if (empty($id)) {
      return $default ? 'checked' : '';
    }

    // Nếu đang sửa → đọc từ status lưu trong DB
    $statuses = array_filter(array_map('trim', explode(',', $status)));
    return in_array($name, $statuses) ? 'checked' : '';
  }



  /* Dump */
  public function dump($value = '', $exit = false)
  {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
    if ($exit) exit();
  }
  public function checkTitle($data = array())
  {
    global $config;

    $result = array();

    foreach ($config['website']['lang'] as $k => $v) {
      if (isset($data['name' . $k])) {
        $title = trim($data['name' . $k]);

        if (empty($title)) {
          $result[] = 'Tiêu đề (' . $v . ') không được trống';
        }
      }
    }

    return $result;
  }
  public function checkSlug(array $data = []): string|false
  {
    $slug = trim($data['slug'] ?? '');
    $table = trim($data['table'] ?? '');
    $exclude_id = isset($data['exclude_id']) ? (int)$data['exclude_id'] : 0;
    $lang = $data['lang'] ?? 'vi';
    if ($slug === '' || $table === '') return false;
    $slugCol = 'slug' . $lang;
    $errorMsg = "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
    $reservedSlugs = [
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
    if (in_array($slug, $reservedSlugs)) {
      return $errorMsg;
    }
    $tablesToCheck = [
      "tbl_product_list",
      "tbl_product_cat",
      "tbl_product_item",
      "tbl_product_sub",
      "tbl_product_brand",
      "tbl_product",
      "tbl_news_list",
      "tbl_news_cat",
      "tbl_news_item",
      "tbl_news_sub",
      "tbl_news",
      "tbl_tags"
    ];
    foreach ($tablesToCheck as $tbl) {
      $tblEsc = mysqli_real_escape_string($this->db->link, $tbl);
      $slugColEsc = mysqli_real_escape_string($this->db->link, $slugCol);
      $checkTable = $this->db->rawQueryOne("SHOW TABLES LIKE '$tblEsc'");
      if (!$checkTable) continue;
      $checkCol = $this->db->rawQueryOne("SHOW COLUMNS FROM `$tblEsc` LIKE '$slugColEsc'");
      if (!$checkCol) continue;
      $sql = "SELECT `$slugColEsc` FROM `$tblEsc` WHERE `$slugColEsc` = ?";
      $params = [$slug];
      if (strtolower($tbl) === strtolower($table) && $exclude_id > 0) {
        $sql .= " AND id != ?";
        $params[] = $exclude_id;
      }
      $sql .= " LIMIT 1";
      $exist = $this->db->rawQueryOne($sql, $params);
      if ($exist) {
        return $errorMsg;
      }
    }
    return false;
  }
  private function cropTransparentOrWhiteBorder($image)
  {
    $w = imagesx($image);
    $h = imagesy($image);

    $min_x = $w;
    $min_y = $h;
    $max_x = 0;
    $max_y = 0;

    for ($y = 0; $y < $h; $y++) {
      for ($x = 0; $x < $w; $x++) {
        $rgba = imagecolorat($image, $x, $y);
        $a = ($rgba & 0x7F000000) >> 24;
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;

        // Nếu không phải trắng hoặc không trong suốt
        if (!($r > 240 && $g > 240 && $b > 240) && $a < 120) {
          if ($x < $min_x) $min_x = $x;
          if ($y < $min_y) $min_y = $y;
          if ($x > $max_x) $max_x = $x;
          if ($y > $max_y) $max_y = $y;
        }
      }
    }

    // Nếu không tìm được vùng nội dung
    if ($max_x <= $min_x || $max_y <= $min_y) {
      return false;
    }

    $crop_width = $max_x - $min_x + 1;
    $crop_height = $max_y - $min_y + 1;

    $new_img = imagecreatetruecolor($crop_width, $crop_height);
    imagealphablending($new_img, false);
    imagesavealpha($new_img, true);

    $transparent = imagecolorallocatealpha($new_img, 0, 0, 0, 127);
    imagefill($new_img, 0, 0, $transparent);

    imagecopy($new_img, $image, 0, 0, $min_x, $min_y, $crop_width, $crop_height);

    return $new_img;
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
  private function applyOpacity($image, $opacity)
  {
    $opacity = max(0, min(100, $opacity));
    $w = imagesx($image);
    $h = imagesy($image);
    $tmp = imagecreatetruecolor($w, $h);
    imagealphablending($tmp, false);
    imagesavealpha($tmp, true);
    for ($y = 0; $y < $h; ++$y) {
      for ($x = 0; $x < $w; ++$x) {
        $rgba = imagecolorsforindex($image, imagecolorat($image, $x, $y));
        $alpha = 127 - ((127 - $rgba['alpha']) * ($opacity / 100));
        $color = imagecolorallocatealpha($tmp, $rgba['red'], $rgba['green'], $rgba['blue'], (int)round($alpha));
        imagesetpixel($tmp, $x, $y, $color);
      }
    }
    return $tmp;
  }

  public function addWatermark($source_path, $destination_path)
  {
    $row = $this->db->rawQueryOne("SELECT * FROM tbl_watermark LIMIT 1");
    if (empty($row['watermark'])) return false;
    $img_type = exif_imagetype($source_path);
    $image = match ($img_type) {
      IMAGETYPE_JPEG => imagecreatefromjpeg($source_path),
      IMAGETYPE_PNG  => imagecreatefrompng($source_path),
      IMAGETYPE_WEBP => imagecreatefromwebp($source_path),
      default        => false,
    };
    if (!$image) return false;
    $img_width = imagesx($image);
    $img_height = imagesy($image);
    $watermark_path = UPLOADS . $row['watermark'];
    if (!file_exists($watermark_path)) return false;
    $wm_src = imagecreatefrompng($watermark_path);
    if (!$wm_src) return false;
    imagesavealpha($wm_src, true);
    $wm_width = imagesx($wm_src);
    $wm_height = imagesy($wm_src);
    $per       = floatval($row['per'] ?? 2);
    $small_per = floatval($row['small_per'] ?? 3);
    $max       = intval($row['max'] ?? 120);
    $min       = intval($row['min'] ?? 120);
    $opacity   = floatval($row['opacity'] ?? 100);
    $position  = intval($row['position'] ?? 9);
    $offset_x  = intval($row['offset_x'] ?? 0);
    $offset_y  = intval($row['offset_y'] ?? 0);
    $scale_percent = ($img_width < 300) ? $small_per : $per;
    $target_wm_width = $img_width * $scale_percent / 100;
    $target_wm_width = max($min, min($max > 0 ? $max : $img_width, $target_wm_width));
    $target_wm_height = intval($wm_height * ($target_wm_width / $wm_width));
    $scaled_wm = imagecreatetruecolor($target_wm_width, $target_wm_height);
    imagealphablending($scaled_wm, false);
    imagesavealpha($scaled_wm, true);
    imagecopyresampled($scaled_wm, $wm_src, 0, 0, 0, 0, $target_wm_width, $target_wm_height, $wm_width, $wm_height);
    imagedestroy($wm_src);
    $watermark = $this->applyOpacity($scaled_wm, $opacity);
    imagedestroy($scaled_wm);
    $padding = 10;
    $positions = [
      1 => [$padding, $padding],
      2 => [($img_width - $target_wm_width) / 2, $padding],
      3 => [$img_width - $target_wm_width - $padding, $padding],
      4 => [$img_width - $target_wm_width - $padding, ($img_height - $target_wm_height) / 2],
      5 => [$img_width - $target_wm_width - $padding, $img_height - $target_wm_height - $padding],
      6 => [($img_width - $target_wm_width) / 2, $img_height - $target_wm_height - $padding],
      7 => [$padding, $img_height - $target_wm_height - $padding],
      8 => [$padding, ($img_height - $target_wm_height) / 2],
      9 => [($img_width - $target_wm_width) / 2, ($img_height - $target_wm_height) / 2],
    ];
    [$x, $y] = $positions[$position] ?? $positions[9];
    $x += $offset_x;
    $y += $offset_y;

    imagecopy($image, $watermark, $x, $y, 0, 0, $target_wm_width, $target_wm_height);

    match ($img_type) {
      IMAGETYPE_JPEG => imagejpeg($image, $destination_path, 85),
      IMAGETYPE_PNG  => imagepng($image, $destination_path, 8),
      IMAGETYPE_WEBP => imagewebp($image, $destination_path, 80),
      default        => null,
    };

    imagedestroy($image);
    imagedestroy($watermark);
    return true;
  }
  public function createFixedThumbnail($source_path, $thumb_name, $background = false, $add_watermark = false, $convert_webp = false)
  {
    if (!file_exists($source_path) || !preg_match('/^(\d+)x(\d+)(x(\d+))?$/', $thumb_name, $m)) return false;

    [$width_orig, $height_orig, $image_type] = getimagesize($source_path);
    $thumb_width = (int)$m[1];
    $thumb_height = (int)$m[2];
    $zoom_crop = isset($m[4]) ? (int)$m[4] : 1;

    $ext_map = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    $create_func = [IMAGETYPE_JPEG => 'imagecreatefromjpeg', IMAGETYPE_PNG => 'imagecreatefrompng', IMAGETYPE_WEBP => 'imagecreatefromwebp'];

    if (!isset($ext_map[$image_type])) return false;
    $ext = $ext_map[$image_type];
    $image = $create_func[$image_type]($source_path);

    if (!$image) return false;
    $is_webp = ($image_type === IMAGETYPE_WEBP);
    if (in_array($image_type, [IMAGETYPE_PNG, IMAGETYPE_WEBP])) {
      imagepalettetotruecolor($image);
      imagealphablending($image, true);
      imagesavealpha($image, true);
    }

    $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    if (is_array($background) && count($background) === 4 && $background[3] == 127 && ($convert_webp || $is_webp)) {
      imagealphablending($thumb, false);
      imagesavealpha($thumb, true);
      imagefill($thumb, 0, 0, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
    } elseif (is_array($background) && count($background) === 4) {
      $bg = imagecolorallocatealpha($thumb, ...$background);
      imagefill($thumb, 0, 0, $bg);
    } else {
      imagefill($thumb, 0, 0, imagecolorallocate($thumb, 255, 255, 255));
    }

    $src_ratio = $width_orig / $height_orig;
    $dst_ratio = $thumb_width / $thumb_height;

    if ($zoom_crop === 3 || $zoom_crop === 2) {
      $dst_w = $thumb_width;
      $dst_h = ($src_ratio > $dst_ratio) ? intval($thumb_width / $src_ratio) : intval($thumb_height * $src_ratio);
      $dst_x = intval(($thumb_width - $dst_w) / 2);
      $dst_y = intval(($thumb_height - $dst_h) / 2);
    } else {
      $src_w = ($src_ratio > $dst_ratio) ? intval($height_orig * $dst_ratio) : $width_orig;
      $src_h = ($src_ratio > $dst_ratio) ? $height_orig : intval($width_orig / $dst_ratio);
      $src_x = intval(($width_orig - $src_w) / 2);
      $src_y = intval(($height_orig - $src_h) / 2);
      imagecopyresampled($thumb, $image, 0, 0, $src_x, $src_y, $thumb_width, $thumb_height, $src_w, $src_h);
    }

    if ($zoom_crop >= 2) {
      imagecopyresampled($thumb, $image, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $width_orig, $height_orig);
      if ($zoom_crop === 3 && ($cropped = $this->cropTransparentOrWhiteBorder($thumb))) {
        imagedestroy($thumb);
        $thumb = $cropped;
      }
    }

    $upload_dir = UPLOADS . "/{$thumb_width}x{$thumb_height}x{$zoom_crop}/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $filename = pathinfo($source_path, PATHINFO_FILENAME);
    $thumb_ext = ($convert_webp || $is_webp) ? 'webp' : $ext;
    $thumb_path = $upload_dir . $filename . '.' . $thumb_ext;

    $success = match ($thumb_ext) {
      'webp' => imagewebp($thumb, $thumb_path, 100),
      'jpg'  => imagejpeg($thumb, $thumb_path, 90),
      'png'  => imagepng($thumb, $thumb_path),
      default => false
    };

    if ($success && $add_watermark && method_exists($this, 'addWatermark')) {
      $this->addWatermark($thumb_path, $thumb_path);
    }

    imagedestroy($image);
    imagedestroy($thumb);
    return $success ? $thumb_path : false;
  }

  public function ImageUpload($file_path, $original_name, $old_file_path, $thumb_base_name, $background, $watermark = false, $convert_webp = false, $save_original = true)
  {
    if (!preg_match('/^(\d+)x(\d+)$/', $thumb_base_name, $m)) return basename($file_path);

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $filename = pathinfo($file_path, PATHINFO_FILENAME);
    $thumb_created = false;

    for ($zc = 1; $zc <= 3; $zc++) {
      $thumb = $this->createFixedThumbnail($file_path, "{$m[1]}x{$m[2]}x{$zc}", $background, $watermark, $convert_webp);
      if ($zc === 1 && $thumb) $thumb_created = basename($thumb);
    }

    if ($convert_webp) {
      $webp_path = UPLOADS . $filename . '.webp';
      if (!file_exists($webp_path)) {
        $info = getimagesize($file_path);
        $img = match ($info[2]) {
          IMAGETYPE_JPEG => imagecreatefromjpeg($file_path),
          IMAGETYPE_PNG => imagecreatefrompng($file_path),
          IMAGETYPE_WEBP => imagecreatefromwebp($file_path),
          default => null
        };
        if ($img) {
          imagepalettetotruecolor($img);
          imagealphablending($img, true);
          imagesavealpha($img, true);
          imagewebp($img, $webp_path, 100);
          imagedestroy($img);
        }
      }
      if (file_exists($file_path)) unlink($file_path);
      $thumb_created = $filename . '.webp';
    } elseif (!$save_original && file_exists($file_path)) {
      unlink($file_path);
    }

    if (!empty($old_file_path) && file_exists($old_file_path)) unlink($old_file_path);
    return $thumb_created ?: basename($file_path);
  }

  public function Upload(array $options)
  {
    $f = $options['file'] ?? [];
    if (empty($f['name']) || empty($f['tmp_name'])) return '';

    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    $upload_dir = UPLOADS;
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $filename = !empty($options['custom_name'])
      ? $this->generateUniqueFilename($upload_dir, $this->to_slug($options['custom_name']) . '_' . substr(md5(uniqid() . microtime(true)), 0, 4), $ext)
      : substr(md5(time() . rand() . uniqid()), 0, 10) . '.' . $ext;

    $target_path = $upload_dir . $filename;

    if (move_uploaded_file($f['tmp_name'], $target_path)) {
      return preg_match('/^(\d+)x(\d+)(x\d+)?$/', $options['thumb'] ?? '')
        ? $this->ImageUpload(
          $target_path,
          $f['name'],
          $options['old_file_path'] ?? '',
          $options['thumb'],
          $options['background'] ?? [255, 255, 255, 0],
          $options['watermark'] ?? false,
          $options['convert_webp'] ?? false,
          true
        )
        : basename($target_path);
    }

    return '';
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
