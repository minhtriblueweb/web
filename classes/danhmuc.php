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

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }

  public function get_danhmuc_c2_with_parent_or_404($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);

    $query = "
    SELECT
        c2.*,
        c1.namevi AS name_lv1,
        c1.slugvi AS slug_lv1,
        c1.titlevi AS title_lv1,
        c1.keywordsvi AS keywords_lv1,
        c1.descriptionvi AS description_lv1
    FROM tbl_danhmuc_c2 c2
    JOIN tbl_danhmuc_c1 c1 ON c2.id_list = c1.id
    WHERE c2.slugvi = '$slug'
    LIMIT 1
  ";
    $result = $this->db->select($query);
    if ($result && $row = $result->fetch_assoc()) {
      return $row;
    } else {
      http_response_code(404);
      include '404.php';
      exit();
    }
  }

  public function slug_exists_lv1($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT id FROM tbl_danhmuc_c1 WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    return $result ? true : false;
  }

  public function slug_exists_lv2($slug_lv2, $slug_lv1)
  {
    $slug_lv1 = mysqli_real_escape_string($this->db->link, $slug_lv1);
    $slug_lv2 = mysqli_real_escape_string($this->db->link, $slug_lv2);

    // Lấy ID của danh mục cấp 1 từ slug
    $query_lv1 = "SELECT id FROM tbl_danhmuc_c1 WHERE slugvi = '$slug_lv1' LIMIT 1";
    $result_lv1 = $this->db->select($query_lv1);
    if ($result_lv1 && $row_lv1 = $result_lv1->fetch_assoc()) {
      $id_list = $row_lv1['id'];

      // Kiểm tra danh mục cấp 2 trong danh mục cấp 1 đó
      $query_lv2 = "SELECT id FROM tbl_danhmuc_c2 WHERE slugvi = '$slug_lv2' AND id_list = '$id_list' LIMIT 1";
      $result_lv2 = $this->db->select($query_lv2);
      return $result_lv2 ? true : false;
    }

    return false;
  }
  public function find_lv2_with_parent($slug_lv2)
  {
    $slug_lv2 = mysqli_real_escape_string($this->db->link, $slug_lv2);

    $query = "
        SELECT
            c2.*,
            c1.slugvi AS slug_lv1,
            c1.namevi AS name_lv1,
            c1.id AS id_list
        FROM tbl_danhmuc_c2 AS c2
        JOIN tbl_danhmuc_c1 AS c1 ON c2.id_list = c1.id
        WHERE c2.slugvi = '$slug_lv2'
        LIMIT 1
    ";

    $result = $this->db->select($query);

    if ($result && $row = $result->fetch_assoc()) {
      return $row;
    }

    return false;
  }

  public function get_danhmuc($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM tbl_danhmuc_c1 WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_danhmuc_or_404($slug, $table)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM $table WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    if ($result && $row = $result->fetch_assoc()) {
      return $row;
    } else {
      http_response_code(404);
      include '404.php';
      exit();
    }
  }

  public function get_danhmuc_c2($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM tbl_danhmuc_c2 WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function save_danhmuc($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $fields_multi = ['slug', 'name', 'desc', 'content', 'title', 'keywords', 'description'];
    $fields_common = ['numb'];
    $table = 'tbl_danhmuc_c1';
    $data_escaped = [];
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_escaped[$key] = !empty($data[$key]) ? mysqli_real_escape_string($this->db->link, $data[$key]) : "";
      }
    }
    foreach ($fields_common as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }
    $status_flags = ['hienthi', 'noibat'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) {
        $status_values[] = $flag;
      }
    }
    $data_escaped['status'] = mysqli_real_escape_string($this->db->link, implode(',', $status_values));
    foreach ($langs as $lang) {
      $slug_key = 'slug' . $lang;
      $slug_error = $this->fn->isSlugDuplicated($data_escaped[$slug_key], $table, $id ?? '');
      if ($slug_error) return $slug_error;
    }
    $thumb_filename = '';
    $old_file_path = '';
    if (!empty($id)) {
      $old_file = $this->db->select("SELECT file FROM $table WHERE id = '" . (int)$id . "'");
      if ($old_file && $old_file->num_rows > 0) {
        $row = $old_file->fetch_assoc();
        $old_file_path = "uploads/" . $row['file'];
      }
    }
    $width = isset($data['thumb_width']) ? (int)$data['thumb_width'] : '';
    $height = isset($data['thumb_height']) ? (int)$data['thumb_height'] : '';
    $zc = isset($data['thumb_zc']) ? (int)$data['thumb_zc'] : '';
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
      $update_fields = [];
      foreach ($data_escaped as $field => $value) {
        $update_fields[] = "`$field` = '$value'";
      }
      if (!empty($thumb_filename)) {
        $update_fields[] = "file = '$thumb_filename'";
      }
      $update_query = "UPDATE $table SET " . implode(", ", $update_fields) . " WHERE id = '" . (int)$id . "'";
      $result = $this->db->update($update_query);
      $msg = $result ? "Cập nhật danh mục thành công" : "Cập nhật danh mục thất bại";
    } else {
      $field_names = array_keys($data_escaped);
      $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);
      if (!empty($thumb_filename)) {
        $field_names[] = 'file';
        $field_values[] = "'" . $thumb_filename . "'";
      }
      $insert_query = "INSERT INTO $table (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
      $result = $this->db->insert($insert_query);
      $msg = $result ? "Thêm danh mục thành công" : "Thêm danh mục thất bại";
    }
    $this->fn->transfer($msg, $this->fn->getRedirectPath(['table' => $table]), $result);
  }
  public function save_danhmuc_c2($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);

    $fields_multi = ['slug', 'name', 'desc', 'content', 'title', 'keywords', 'description'];
    $fields_common = ['id_list', 'numb'];
    $table = 'tbl_danhmuc_c2';
    $data_escaped = [];
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_escaped[$key] = !empty($data[$key]) ? mysqli_real_escape_string($this->db->link, $data[$key]) : "";
      }
    }
    foreach ($fields_common as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }
    $status_flags = ['hienthi', 'noibat'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) {
        $status_values[] = $flag;
      }
    }
    $data_escaped['status'] = mysqli_real_escape_string($this->db->link, implode(',', $status_values));
    foreach ($langs as $lang) {
      $slug_key = 'slug' . $lang;
      $slug_error = $this->fn->isSlugDuplicated($data_escaped[$slug_key], $table, $id ?? '');
      if ($slug_error) return $slug_error;
    }
    $thumb_filename = '';
    $old_file_path = '';
    if (!empty($id)) {
      $old_file = $this->db->select("SELECT file FROM $table WHERE id = '" . (int)$id . "'");
      if ($old_file && $old_file->num_rows > 0) {
        $row = $old_file->fetch_assoc();
        $old_file_path = "uploads/" . $row['file'];
      }
    }
    $width = isset($data['thumb_width']) ? (int)$data['thumb_width'] : '';
    $height = isset($data['thumb_height']) ? (int)$data['thumb_height'] : '';
    $zc = isset($data['thumb_zc']) ? (int)$data['thumb_zc'] : '';
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
      $update_fields = [];
      foreach ($data_escaped as $field => $value) {
        $update_fields[] = "`$field` = '$value'";
      }
      if (!empty($thumb_filename)) {
        $update_fields[] = "file = '$thumb_filename'";
      }
      $update_query = "UPDATE $table SET " . implode(", ", $update_fields) . " WHERE id = '" . (int)$id . "'";
      $result = $this->db->update($update_query);
      $msg = $result ? "Cập nhật danh mục thành công" : "Cập nhật danh mục thất bại";
    } else {
      $field_names = array_keys($data_escaped);
      $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);
      if (!empty($thumb_filename)) {
        $field_names[] = 'file';
        $field_values[] = "'" . $thumb_filename . "'";
      }
      $insert_query = "INSERT INTO $table (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
      $result = $this->db->insert($insert_query);
      $msg = $result ? "Thêm danh mục thành công" : "Thêm danh mục thất bại";
    }
    $this->fn->transfer($msg, $this->fn->getRedirectPath(['table' => $table]), $result);
  }
}

?>
