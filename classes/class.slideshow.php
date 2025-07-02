<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class slideshow
{
  private $db;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }
  public function save_slideshow($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $fields_multi = ['name'];
    $fields_common = ['link', 'numb'];
    $table = 'tbl_slideshow';

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
    $thumb_size = $width . 'x' . $height . 'x1';
    $thumb_filename = $this->fn->Upload([
      'file' => $files['file'],
      'custom_name' => $data_escaped['namevi'],
      'thumb' => $thumb_size,
      'old_file_path' => $old_file_path,
      'watermark' => false,
      'convert_webp' => true
    ]);
    $data_escaped['options'] = '';
    if (!empty($thumb_filename)) {
      $options = [
        'w' => $width,
        'h' => $height
      ];
      $data_escaped['options'] = json_encode($options);
    }
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
      $msg = $result ? "Cập nhật slideshow thành công" : "Cập nhật slideshow thất bại";
    } else {
      $field_names = array_map(fn($k) => "`$k`", array_keys($data_escaped));
      $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);
      if (!empty($thumb_filename)) {
        $field_names[] = '`file`';
        $field_values[] = "'" . $thumb_filename . "'";
      }
      $query = "INSERT INTO `$table` (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
      $result = $this->db->insert($query);
      $msg = $result ? "Thêm slideshow thành công" : "Thêm slideshow thất bại";
    }
    $redirectPath = $this->fn->getRedirectPath(['table' => $table]);
    $this->fn->transfer($msg, $redirectPath, $result);
  }
}

?>
