<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class danhgia
{
  private $db;
  private $fm;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new functions();
    $this->fm = new Format();
  }

  public function save_danhgia($data, $files, $id = null)
  {
    $fields = ['name', 'address', 'desc', 'numb'];
    $table = 'tbl_danhgia';

    // Escape dữ liệu đầu vào
    $data_escaped = [];
    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }

    // Xử lý status (checkbox: hienthi)
    $status_flags = ['hienthi'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) {
        $status_values[] = $flag;
      }
    }
    $data_escaped['status'] = mysqli_real_escape_string($this->db->link, implode(',', $status_values));

    // Xử lý ảnh (nếu có)
    $thumb_filename = '';
    $old_file_path = '';

    if (!empty($id)) {
      $old_file = $this->db->select("SELECT file FROM `$table` WHERE id = '" . (int)$id . "'");
      if ($old_file && $old_file->num_rows > 0) {
        $row = $old_file->fetch_assoc();
        $old_file_path = "uploads/" . $row['file'];
      }
    }
    $thumb_filename = $this->fn->Upload($files, '100x100x1', [255, 255, 255, 0], $old_file_path, false, true);
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
    $this->fn->transfer($msg, "index.php?page=danhgia_list", $result);
  }
}
?>
