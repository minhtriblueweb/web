<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class danhmuc
{
  private $db;
  private $fn;
  private $seo;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
    $this->seo = new seo();
  }
  public function get_danhmuc_c2_with_parent_or_404($slug)
  {
    $row = $this->db->rawQueryOne("
      SELECT
        c2.*,
        c1.namevi AS name_lv1,
        c1.slugvi AS slug_lv1
      FROM tbl_danhmuc_c2 c2
      JOIN tbl_danhmuc_c1 c1 ON c2.id_list = c1.id
      WHERE c2.slugvi = ?
      LIMIT 1
    ", [$slug]);

    if ($row) return $row;

    http_response_code(404);
    include '404.php';
    exit();
  }
  public function get_danhmuc_or_404($slug, $table)
  {
    $row = $this->db->rawQueryOne("SELECT * FROM `$table` WHERE slugvi = ? LIMIT 1", [$slug]);
    if ($row) return $row;
    http_response_code(404);
    include '404.php';
    exit();
  }
  public function slug_exists_lv1($slug)
  {
    $row = $this->db->rawQueryOne("SELECT id FROM tbl_danhmuc_c1 WHERE slugvi = ? LIMIT 1", [$slug]);
    return $row ? true : false;
  }
  public function slug_exists_lv2($slug_lv2, $slug_lv1)
  {
    $row_lv1 = $this->db->rawQueryOne("SELECT id FROM tbl_danhmuc_c1 WHERE slugvi = ? LIMIT 1", [$slug_lv1]);
    if ($row_lv1) {
      $id_list = $row_lv1['id'];
      $row_lv2 = $this->db->rawQueryOne("SELECT id FROM tbl_danhmuc_c2 WHERE slugvi = ? AND id_list = ? LIMIT 1", [$slug_lv2, $id_list]);
      return $row_lv2 ? true : false;
    }
    return false;
  }
  public function find_lv2_with_parent($slug_lv2)
  {
    $row = $this->db->rawQueryOne("
      SELECT
        c2.*,
        c1.slugvi AS slug_lv1,
        c1.namevi AS name_lv1,
        c1.id AS id_list
      FROM tbl_danhmuc_c2 AS c2
      JOIN tbl_danhmuc_c1 AS c1 ON c2.id_list = c1.id
      WHERE c2.slugvi = ?
      LIMIT 1
    ", [$slug_lv2]);

    return $row ?: false;
  }

  public function save_danhmuc($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $fields_multi = ['slug', 'name', 'desc', 'content'];
    $fields_common = ['numb', 'type'];
    $table = 'tbl_danhmuc_c1';
    $data_prepared = [];
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_prepared[$key] = $data[$key] ?? '';
      }
    }
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? '';
    }
    $status_flags = ['hienthi', 'noibat'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) $status_values[] = $flag;
    }
    $data_prepared['status'] = implode(',', $status_values);
    foreach ($langs as $lang) {
      $slug_key = 'slug' . $lang;
      $slug_error = $this->fn->isSlugDuplicated($data_prepared[$slug_key], $table, $id ?? '');
      if ($slug_error) return $slug_error;
    }
    $thumb_filename = '';
    $old_file_path = '';
    if (!empty($id)) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
      if ($old && !empty($old['file'])) {
        $old_file_path = "uploads/" . $old['file'];
      }
    }
    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $zc = (int)($data['thumb_zc'] ?? 0);
    $thumb_size = $width . 'x' . $height . 'x' . $zc;
    $thumb_filename = $this->fn->Upload([
      'file' => $files['file'],
      'custom_name' => $data_prepared['namevi'],
      'thumb' => $thumb_size,
      'old_file_path' => $old_file_path,
      'watermark' => false,
      'convert_webp' => false
    ]);

    if (!empty($id)) {
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
      $sql = "UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ?";
      $result = $this->db->execute($sql, $params);
      if ($result) {
        $this->seo->save_seo($data_prepared['type'], (int)$id, $data, $langs);
      }
      $msg = $result ? "Cập nhật danh mục thành công" : "Cập nhật danh mục thất bại";
    } else {
      $columns = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_prepared);
      if (!empty($thumb_filename)) {
        $columns[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }
      $sql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
      $inserted = $this->db->execute($sql, $params);
      $insert_id = $inserted ? $this->db->getInsertId() : 0;
      if ($insert_id) {
        $this->seo->save_seo($data_prepared['type'], $insert_id, $data, $langs);
      }
      $msg = $inserted ? "Thêm danh mục thành công" : "Thêm danh mục thất bại";
    }
    $this->fn->transfer($msg, $this->fn->getRedirectPath(['table' => $table]), !empty($id) ? $result : $inserted);
  }

  public function save_danhmuc_c2($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);

    $fields_multi = ['slug', 'name', 'desc', 'content'];
    $fields_common = ['id_list', 'numb', 'type'];
    $table = 'tbl_danhmuc_c2';
    $data_escaped = [];
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_prepared[$key] = $data[$key] ?? '';
      }
    }
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? '';
    }
    $status_flags = ['hienthi', 'noibat'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) $status_values[] = $flag;
    }
    $data_prepared['status'] = implode(',', $status_values);
    foreach ($langs as $lang) {
      $slug_key = 'slug' . $lang;
      $slug_error = $this->fn->isSlugDuplicated($data_escaped[$slug_key], $table, $id ?? '');
      if ($slug_error) return $slug_error;
    }
    $thumb_filename = '';
    $old_file_path = '';
    if (!empty($id)) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
      if ($old && !empty($old['file'])) {
        $old_file_path = "uploads/" . $old['file'];
      }
    }
    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $zc = (int)($data['thumb_zc'] ?? 0);
    $thumb_size = $width . 'x' . $height . 'x' . $zc;
    $thumb_filename = $this->fn->Upload([
      'file' => $files['file'],
      'custom_name' => $data_escaped['namevi'],
      'thumb' => $thumb_size,
      'old_file_path' => $old_file_path,
      'watermark' => false,
      'convert_webp' => false
    ]);
    if (!empty($id)) {
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
      $sql = "UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ?";
      $result = $this->db->execute($sql, $params);
      if ($result) {
        $this->seo->save_seo($data_prepared['type'], (int)$id, $data, $langs);
      }
      $msg = $result ? "Cập nhật danh mục thành công" : "Cập nhật danh mục thất bại";
    } else {
      $columns = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_prepared);
      if (!empty($thumb_filename)) {
        $columns[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }
      $sql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
      $inserted = $this->db->execute($sql, $params);
      $insert_id = $inserted ? $this->db->getInsertId() : 0;
      if ($insert_id) {
        $this->seo->save_seo($data_prepared['type'], $insert_id, $data, $langs);
      }
      $msg = $inserted ? "Thêm danh mục thành công" : "Thêm danh mục thất bại";
    }
    $this->fn->transfer($msg, $this->fn->getRedirectPath(['table' => $table]), !empty($id) ? $result : $inserted);
  }
}
?>
