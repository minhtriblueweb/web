<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class news
{
  private $db;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }

  public function get_news_by_slug($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc() : false;
  }

  public function update_views_by_slug($slug)
  {
    $query = "SELECT * FROM tbl_news WHERE slug = '$slug'";
    $result = $this->db->select($query);
    if ($result && $result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $new_ews = $product['ews'] + 1;
      $update_query = "UPDATE tbl_news SET ews = '$new_ews' WHERE slug = '$slug'";
      $this->db->update($update_query);
      return $product;
    }
    return false;
  }

  public function save_news($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $fields_multi = ['slug', 'name', 'desc', 'content', 'title', 'keywords', 'description'];
    $fields_common = ['type', 'numb'];
    $table = 'tbl_news';
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
      'convert_webp' => true
    ]);
    if (!empty($id)) {
      $update_fields = [];
      foreach ($data_escaped as $field => $value) {
        $update_fields[] = "`$field` = '$value'";
      }
      if (!empty($thumb_filename)) {
        $update_fields[] = "file = '$thumb_filename'";
      }
      $query = "UPDATE $table SET " . implode(", ", $update_fields) . " WHERE id = '" . (int)$id . "'";
      $result = $this->db->update($query);
      $msg = $result ? "Cập nhật dữ liệu thành công" : "Cập nhật dữ liệu thất bại";
    } else {
      $field_names = array_keys($data_escaped);
      $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);
      if (!empty($thumb_filename)) {
        $field_names[] = 'file';
        $field_values[] = "'" . $thumb_filename . "'";
      }
      $query = "INSERT INTO $table (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
      $result = $this->db->insert($query);
      $msg = $result ? "Thêm dữ liệu thành công" : "Thêm dữ liệu thất bại";
    }
    $type_safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $data_escaped['type']);
    $redirectPath = $this->fn->getRedirectPath([
      'table' => $table,
      'type' => $type_safe
    ]);
    $this->fn->transfer($msg, $redirectPath, $result);
  }
}
?>
