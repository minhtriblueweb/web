<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class tieuchi
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
  public function get_id_tieuchi($id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM tbl_tieuchi WHERE id = '$id' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function show_tieuchi($records_per_page, $current_page, $only_show = false)
  {
    $records_per_page = max(1, (int)$records_per_page);
    $current_page = max(1, (int)$current_page);
    $offset = ($current_page - 1) * $records_per_page;

    $where = [];
    if ($only_show) {
      $where[] = "FIND_IN_SET('hienthi', status)";
    }
    $query = "SELECT * FROM tbl_tieuchi";
    if (!empty($where)) {
      $query .= " WHERE " . implode(" AND ", $where);
    }

    $query .= " ORDER BY numb, id DESC LIMIT $records_per_page OFFSET $offset";
    return $this->db->select($query);
  }
  public function show_tieuchi_index()
  {
    $query = "SELECT * FROM tbl_tieuchi WHERE hienthi = 'hienthi' ORDER BY numb, id DESC";
    return $this->db->select($query);
  }
  public function save_tieuchi($data, $files, $id = null)
  {
    $fields = ['name', 'desc', 'numb'];
    $table = 'tbl_tieuchi';

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
      $old_file = $this->db->select("SELECT file FROM $table WHERE id = '" . (int)$id . "'");
      if ($old_file && $old_file->num_rows > 0) {
        $row = $old_file->fetch_assoc();
        $old_file_path = "uploads/" . $row['file'];
      }
    }

    // Gọi hàm upload chuẩn hóa
    $thumb_filename = $this->fn->Upload($files, '40x40x1', [255, 255, 255, 0], $old_file_path, false, true);

    // Thực thi query
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

    // Chuyển hướng
    $this->fn->transfer($msg, "index.php?page=tieuchi_list", $result);
  }
}

?>
