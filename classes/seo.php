<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class seo
{
  private $db;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }
  public function get_seopage(string $type = ''): array
  {
    $row = [];
    if (!empty($type)) {
      $row = $this->db->rawQueryOne("SELECT * FROM tbl_seopage WHERE type = ?", [$type]);
    }
    return $row ?: [];
  }


  public function get_seo(int $id_parent, string $type = ''): array
  {
    $sql = "SELECT * FROM tbl_seo WHERE `id_parent` = ?" . ($type ? " AND `type` = ?" : "");
    $params = [$id_parent];
    if ($type) $params[] = $type;
    $row = $this->db->rawQueryOne($sql, $params);
    return $row ?: [];
  }
  public function save_seo(string $type, int $id_parent, array $data, array $langs): void
  {
    $seo_table = 'tbl_seo';
    $fields_multi = ['title', 'keywords', 'description', 'schema'];

    $data_sql = [
      'id_parent' => $id_parent,
      'type' => $type
    ];

    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_sql[$key] = $data[$key] ?? '';
      }
    }

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
    $fields_multi = ['title', 'keywords', 'description'];
    $fields_common = ['type'];
    $table = 'tbl_seopage';

    $data_prepared = [];

    // Dữ liệu đa ngôn ngữ
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_prepared[$key] = $data[$key] ?? "";
      }
    }

    // Dữ liệu chung
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? "";
    }

    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $zc = (int)($data['thumb_zc'] ?? 0);
    $thumb_size = "{$width}x{$height}x{$zc}";

    $thumb_filename = '';
    $old_file_path = '';

    // Nếu có ID => lấy file cũ
    if (!empty($id)) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
      if ($old && !empty($old['file'])) {
        $old_file_path = "uploads/" . $old['file'];
      }
    } else {
      // Nếu không có ID, kiểm tra theo type xem đã có trong DB chưa
      $existing = $this->db->rawQueryOne("SELECT id, file FROM $table WHERE type = ?", [$data_prepared['type']]);
      if (!empty($existing)) {
        $id = $existing['id'];
        if (!empty($existing['file'])) {
          $old_file_path = "uploads/" . $existing['file'];
        }
      }
    }

    // Upload ảnh mới
    $thumb_filename = $this->fn->Upload([
      'file' => $files['file'],
      'custom_name' => 'ogimage_' . $data_prepared['type'],
      'background' => [255, 255, 255, 0],
      'thumb' => $thumb_size,
      'old_file_path' => $old_file_path,
      'watermark' => false,
      'convert_webp' => true
    ]);

    if (!empty($thumb_filename)) {
      $data_prepared['options'] = json_encode(['w' => $width, 'h' => $height]);
    }

    if (!empty($id)) {
      // UPDATE
      $fields = [];
      $params = [];
      foreach ($data_prepared as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }
      if (!empty($thumb_filename)) {
        $fields[] = "`file` = ?";
        $params[] = $thumb_filename;
      }
      $params[] = (int)$id;
      $sql = "UPDATE $table SET " . implode(", ", $fields) . " WHERE id = ?";
      $result = $this->db->execute($sql, $params);
      $msg = $result ? "Cập nhật dữ liệu thành công" : "Cập nhật dữ liệu thất bại";
    } else {
      // INSERT
      $columns = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_prepared);
      if (!empty($thumb_filename)) {
        $columns[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }
      $sql = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";
      $inserted = $this->db->execute($sql, $params);
      $msg = $inserted ? "Thêm dữ liệu thành công" : "Thêm dữ liệu thất bại";
    }

    $type_safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $data_prepared['type']);
    $redirectPath = $this->fn->getRedirectPath(['table' => $table, 'type' => $type_safe]);
    $this->fn->transfer($msg, $redirectPath, !empty($id) ? $result : $inserted);
  }
}

?>
