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
      'url'         => BASE . ($row["slug{$lang}"] ?? ''),
      'image'       => !empty($row['file']) ? BASE_ADMIN . UPLOADS . $row['file'] : ''
    ]);
    $this->set($data);
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
    $this->set($data);
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

  public function save_seopage($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $table = 'tbl_seopage';
    $fields_multi = ['title', 'keywords', 'description'];
    $fields_common = ['type'];
    $data_prepared = [];
    $has_data = false;
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $val = trim($data[$key] ?? '');
        $data_prepared[$key] = $val;
        if (!$has_data && $val !== '') $has_data = true;
      }
    }
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? '';
    }
    $type_safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $data_prepared['type']);
    $redirectPath = $this->fn->getRedirectPath(['table' => $table, 'type' => $type_safe]);
    if (!$has_data) {
      $this->fn->transfer("Cập nhật dữ liệu thành công", $redirectPath, true);
      return;
    }
    $thumb_filename = '';
    $old_file_path = '';
    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    if ($id) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [$id]);
      if (!empty($old['file'])) $old_file_path = UPLOADS . $old['file'];
    } else {
      $existing = $this->db->rawQueryOne("SELECT id, file FROM $table WHERE type = ?", [$data_prepared['type']]);
      if (!empty($existing)) {
        $id = $existing['id'];
        if (!empty($existing['file'])) $old_file_path = UPLOADS . $existing['file'];
      }
    }
    if (!empty($files['file']['tmp_name'])) {
      $thumb_filename = $this->fn->Upload([
        'file' => $files['file'],
        'custom_name' => 'ogimage_' . $data_prepared['type'],
        'background' => [255, 255, 255, 0],
        'thumb' => '',
        'old_file_path' => $old_file_path,
        'watermark' => false,
        'convert_webp' => true
      ]);
      if ($thumb_filename) {
        $data_prepared['options'] = json_encode(['w' => $width, 'h' => $height]);
      }
    }
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
}

?>
