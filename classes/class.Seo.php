<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class Seo
{
  private $db;
  private $fn;
  private $data;
  private $prefix;
  private $seo = [];
  private $d;
  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }
  public function get_seo(int $id_parent, string $type = '', string $lang = 'vi'): array
  {
    global $default_seo;

    $sql = "SELECT * FROM tbl_seo WHERE `id_parent` = ?" . ($type ? " AND `type` = ?" : "");
    $params = [$id_parent];
    if ($type) $params[] = $type;

    $row = $this->db->rawQueryOne($sql, $params);
    if (!$row) {
      $this->set($default_seo);
      return $default_seo;
    }

    $title = $row["title{$lang}"] ?? '';
    $data = array_merge($default_seo, [
      'h1'          => $title,
      'title'       => $title,
      'keywords'    => $row["keywords{$lang}"] ?? '',
      'description' => $row["description{$lang}"] ?? '',
      'url'         => BASE . ($row["slug{$lang}"] ?? $row['slug'] ?? ''),
      'image'       => !empty($row['file']) ? BASE_ADMIN . UPLOADS . $row['file'] : ''
    ]);

    $this->set($data); // Gán vào $this->data để dùng $seo->get('...')

    return $row + $data;
  }
  public function get_seopage(array $row, string $lang = 'vi'): array
  {
    global $default_seo;

    $title = $row["title{$lang}"] ?? '';
    $data = array_merge($default_seo, [
      'h1'    => $title,
      'title' => $title,
      'keywords' => $row["keywords{$lang}"] ?? '',
      'description' => $row["description{$lang}"] ?? '',
      'url'   => BASE . ($row["slug{$lang}"] ?? ''),
      'image' => !empty($row['file']) ? BASE_ADMIN . UPLOADS . $row['file'] : ''
    ]);

    $this->set($data); // GÁN vào bên trong class Seo

    return $data;
  }
  public function set($key = '', $value = '')
  {
    if (is_array($key)) {
      foreach ($key as $k => $v) {
        $this->set($k, $v);
      }
    } elseif ($key !== '') {
      $this->data[$key] = $value;
    }
  }
  public function get($key)
  {
    return (!empty($this->data[$key])) ? $this->data[$key] : '';
  }
  public function save_seo(string $type, int $id_parent, array $data, array $langs): void
  {
    $seo_table = 'tbl_seo';
    $fields_multi = ['title', 'keywords', 'description', 'schema'];

    $data_sql = [
      'id_parent' => $id_parent,
      'type' => $type
    ];

    $has_data = false; // <- Dùng để kiểm tra nếu có dữ liệu

    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $value = $data[$key] ?? '';
        $data_sql[$key] = $value;

        // Nếu ít nhất 1 trường có nội dung
        if (!$has_data && trim($value) !== '') {
          $has_data = true;
        }
      }
    }

    // Nếu không có dữ liệu SEO, không lưu gì cả
    if (!$has_data) return;

    // Kiểm tra bản ghi SEO đã tồn tại chưa
    $existing = $this->db->rawQueryOne(
      "SELECT id FROM `$seo_table` WHERE id_parent = ? AND `type` = ?",
      [$id_parent, $type]
    );

    if ($existing) {
      // UPDATE
      $fields = [];
      $params = [];

      foreach ($data_sql as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }

      $params[] = $existing['id'];
      $sql = "UPDATE `$seo_table` SET " . implode(', ', $fields) . " WHERE id = ?";
      $this->db->execute($sql, $params);
    } else {
      // INSERT
      $columns = array_map(fn($col) => "`$col`", array_keys($data_sql));
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_sql);

      $sql = "INSERT INTO `$seo_table` (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
      $this->db->execute($sql, $params);
    }
  }

  public function save_seopage($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $table = 'tbl_seopage';
    $fields_multi = ['title', 'keywords', 'description'];
    $fields_common = ['type'];
    $data_prepared = [];
    $has_data = false;

    // Ghép dữ liệu đa ngôn ngữ và kiểm tra nội dung
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $val = trim($data[$key] ?? '');
        $data_prepared[$key] = $val;
        if (!$has_data && $val !== '') $has_data = true;
      }
    }

    // Dữ liệu chung
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? '';
    }

    // Tạo đường dẫn chuyển hướng
    $type_safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $data_prepared['type']);
    $redirectPath = $this->fn->getRedirectPath(['table' => $table, 'type' => $type_safe]);

    // Nếu không có nội dung -> bỏ qua
    if (!$has_data) {
      $this->fn->transfer("Cập nhật dữ liệu thành công", $redirectPath, true);
      return;
    }

    // Xử lý ảnh
    $thumb_filename = '';
    $old_file_path = '';
    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $zc = (int)($data['thumb_zc'] ?? 0);
    $thumb_size = "{$width}x{$height}x{$zc}";

    // Nếu có ID, lấy ảnh cũ
    if ($id) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [$id]);
      if (!empty($old['file'])) $old_file_path = "uploads/" . $old['file'];
    } else {
      // Nếu chưa có ID, tìm theo type
      $existing = $this->db->rawQueryOne("SELECT id, file FROM $table WHERE type = ?", [$data_prepared['type']]);
      if (!empty($existing)) {
        $id = $existing['id'];
        if (!empty($existing['file'])) $old_file_path = "uploads/" . $existing['file'];
      }
    }

    // Upload nếu có file mới
    if (!empty($files['file']['tmp_name'])) {
      $thumb_filename = $this->fn->Upload([
        'file' => $files['file'],
        'custom_name' => 'ogimage_' . $data_prepared['type'],
        'background' => [255, 255, 255, 0],
        'thumb' => $thumb_size,
        'old_file_path' => $old_file_path,
        'watermark' => false,
        'convert_webp' => true
      ]);
      if ($thumb_filename) {
        $data_prepared['options'] = json_encode(['w' => $width, 'h' => $height]);
      }
    }

    // Xử lý lưu DB
    $params = array_values($data_prepared);
    if ($thumb_filename) {
      $data_prepared['file'] = $thumb_filename;
      $params[] = $thumb_filename;
    }

    if ($id) {
      $fields = array_map(fn($k) => "`$k` = ?", array_keys($data_prepared));
      $params[] = $id;
      $sql = "UPDATE `$table` SET " . implode(", ", $fields) . " WHERE id = ?";
      $ok = $this->db->execute($sql, $params);
      $msg = $ok ? "Cập nhật dữ liệu thành công" : "Cập nhật dữ liệu thất bại";
    } else {
      $columns = array_map(fn($k) => "`$k`", array_keys($data_prepared));
      $placeholders = array_fill(0, count($columns), '?');
      $sql = "INSERT INTO `$table` (" . implode(",", $columns) . ") VALUES (" . implode(",", $placeholders) . ")";
      $ok = $this->db->execute($sql, array_values($data_prepared));
      $msg = $ok ? "Thêm dữ liệu thành công" : "Thêm dữ liệu thất bại";
    }

    $this->fn->transfer($msg, $redirectPath, $ok);
  }
  public function save_seopagePDODb($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $table = $this->prefix . 'seopage';
    $fields_multi = ['title', 'keywords', 'description'];
    $fields_common = ['type'];
    $data_prepared = [];
    $has_data = false;

    // Ghép dữ liệu đa ngôn ngữ và kiểm tra nội dung
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $val = trim($data[$key] ?? '');
        $data_prepared[$key] = $val;
        if (!$has_data && $val !== '') $has_data = true;
      }
    }

    // Dữ liệu chung
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? '';
    }

    // Tạo đường dẫn chuyển hướng
    $type_safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $data_prepared['type']);
    $redirectPath = $this->fn->getRedirectPath(['table' => $table, 'type' => $type_safe]);

    // Nếu không có nội dung -> bỏ qua
    if (!$has_data) {
      $this->fn->transfer("Cập nhật dữ liệu thành công", $redirectPath, true);
      return;
    }

    // Xử lý ảnh
    $thumb_filename = '';
    $old_file_path = '';
    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $zc = (int)($data['thumb_zc'] ?? 0);
    $thumb_size = "{$width}x{$height}x{$zc}";

    // Nếu có ID, lấy ảnh cũ
    if ($id) {
      $old = $this->d->rawQueryOne("SELECT file FROM $table WHERE id = ?", [$id]);
      if (!empty($old['file'])) $old_file_path = "uploads/" . $old['file'];
    } else {
      // Nếu chưa có ID, tìm theo type
      $existing = $this->d->rawQueryOne("SELECT id, file FROM $table WHERE type = ?", [$data_prepared['type']]);
      if (!empty($existing)) {
        $id = $existing['id'];
        if (!empty($existing['file'])) $old_file_path = "uploads/" . $existing['file'];
      }
    }

    // Upload nếu có file mới
    if (!empty($files['file']['tmp_name'])) {
      $thumb_filename = $this->fn->Upload([
        'file' => $files['file'],
        'custom_name' => 'ogimage_' . $data_prepared['type'],
        'background' => [255, 255, 255, 0],
        'thumb' => $thumb_size,
        'old_file_path' => $old_file_path,
        'watermark' => false,
        'convert_webp' => true
      ]);
      if ($thumb_filename) {
        $data_prepared['file'] = $thumb_filename;
        $data_prepared['options'] = json_encode(['w' => $width, 'h' => $height]);
      }
    }

    // Xử lý lưu DB
    if ($id) {
      // UPDATE
      $fields = array_map(fn($k) => "`$k` = ?", array_keys($data_prepared));
      $params = array_values($data_prepared);
      $params[] = $id;
      $sql = "UPDATE `$table` SET " . implode(", ", $fields) . " WHERE id = ?";
      $ok = $this->d->execute($sql, $params);
      $msg = $ok ? "Cập nhật dữ liệu thành công" : "Cập nhật dữ liệu thất bại";
    } else {
      // INSERT
      $columns = array_map(fn($k) => "`$k`", array_keys($data_prepared));
      $placeholders = array_fill(0, count($columns), '?');
      $sql = "INSERT INTO `$table` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";
      $ok = $this->d->execute($sql, array_values($data_prepared));
      $msg = $ok ? "Thêm dữ liệu thành công" : "Thêm dữ liệu thất bại";
    }

    $this->fn->transfer($msg, $redirectPath, $ok);
  }
}

?>
