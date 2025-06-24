<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class tieuchi
{
  private $db;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }
  public function save_tieuchi($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $fields_multi = ['name', 'desc'];
    $fields_common = ['numb'];
    $table = 'tbl_tieuchi';
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
    $status_flags = ['hienthi'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) {
        $status_values[] = $flag;
      }
    }
    $data_escaped['status'] = mysqli_real_escape_string($this->db->link, implode(',', $status_values));
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
        $update_fields[] = "`file` = '$thumb_filename'";
      }
      $query = "UPDATE `$table` SET " . implode(", ", $update_fields) . " WHERE id = '" . (int)$id . "'";
      $result = $this->db->update($query);
      $msg = $result ? "Cập nhật dữ liệu thành công" : "Cập nhật dữ liệu thất bại";
    } else {
      $field_names = array_map(fn($k) => "`$k`", array_keys($data_escaped));
      $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);
      if (!empty($thumb_filename)) {
        $field_names[] = '`file`';
        $field_values[] = "'" . $thumb_filename . "'";
      }
      $query = "INSERT INTO `$table` (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
      $result = $this->db->insert($query);
      $msg = $result ? "Thêm dữ liệu thành công" : "Thêm dữ liệu thất bại";
    }
    $this->fn->transfer($msg, "index.php?page=tieuchi_list", $result);
  }
}

?>
