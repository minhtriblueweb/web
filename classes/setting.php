<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class setting
{
  private $db;
  private $fm;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
    $this->fn = new functions();
  }

  public function update_watermark($data, $files)
  {
    $fields = ['position', 'opacity', 'per', 'small_per', 'max', 'min', 'offset_x', 'offset_y'];
    $data_escaped = [];
    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }
    $file_name = $_FILES["watermark"]["name"];
    $unique_image = "";
    if (!empty($file_name)) {
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
      $uploaded_image = "uploads/" . $unique_image;
      move_uploaded_file($_FILES["watermark"]["tmp_name"], $uploaded_image);
      $del_file_query = "SELECT watermark FROM tbl_watermark WHERE id = 1";
      $old_file = $this->db->select($del_file_query);
      if ($old_file && $old_file->num_rows > 0) {
        $row = $old_file->fetch_assoc();
        $old_file_path = "uploads/" . $row['watermark'];
        if (file_exists($old_file_path) && !empty($row['watermark'])) {
          unlink($old_file_path);
        }
      }
    }
    $update_fields = [];
    foreach ($data_escaped as $field => $value) {
      $update_fields[] = "`$field` = '$value'";
    }
    if (!empty($unique_image)) {
      $update_fields[] = "watermark = '$unique_image'";
    }
    $update_query = "UPDATE tbl_watermark SET " . implode(", ", $update_fields) . " WHERE id = 1";
    $result = $this->db->update($update_query);
    if ($result) {
      $this->fn->transfer("Cập nhật hình ảnh thành công", "index.php?page=watermark", true);
    } else {
      $this->fn->transfer("Cập nhật hình ảnh thất bại", "index.php?page=watermark", false);
    }
  }
  public function update_setting_item($item, $data, $files)
  {
    $unique_image = '';
    if (!empty($files[$item]['name'])) {
      $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];  // Các loại file được phép
      $file_ext = strtolower(pathinfo($files[$item]['name'], PATHINFO_EXTENSION));
      if (in_array($file_ext, $allowed_extensions)) {
        $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        if (move_uploaded_file($files[$item]['tmp_name'], "uploads/" . $unique_image)) {
          $del_file_query = "SELECT `$item` FROM tbl_setting WHERE id=1";
          $old_file = $this->db->select($del_file_query);
          if ($old_file && $old_file->num_rows > 0) {
            $rowData = $old_file->fetch_assoc();
            $old_file_path = "uploads/" . $rowData[$item];
            if (file_exists($old_file_path)) {
              unlink($old_file_path);
            }
          }
        } else {
          return "Lỗi trong quá trình tải file lên!";
        }
      } else {
        return "Loại file không hợp lệ! Chỉ chấp nhận các file JPG, JPEG, PNG và GIF.";
      }
    }
    $query = "UPDATE tbl_setting SET `$item` = '$unique_image' WHERE id = 1";
    $result = $this->db->update($query);
    if ($result) {
      header('Location: transfer.php?stt=success&url=' . $item);
    } else {
      return "Cập nhật cơ sở dữ liệu thất bại!";
    }
  }

  public function get_setting_item($item)
  {
    $query = "SELECT `$item` FROM tbl_setting WHERE id = '1' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_setting()
  {
    $query = "SELECT * FROM tbl_setting WHERE id = '1' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_watermark()
  {
    $query = "SELECT * FROM tbl_watermark WHERE id = '1' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function update_setting($data)
  {
    $fields = [
      'email',
      'hotline',
      'web_name',
      'link_googlemaps',
      'fanpage',
      'copyright',
      'introduction',
      'worktime',
      'descvi',
      'coords_iframe',
      'coords',
      'analytics',
      'headjs',
      'bodyjs',
      'support',
      'client_support',
      'position'
    ];

    $updates = [];
    foreach ($fields as $field) {
      $value = mysqli_real_escape_string($this->db->link, $data[$field]);
      $updates[] = "$field = '$value'";
    }

    $query = "UPDATE tbl_setting SET " . implode(', ', $updates) . " WHERE id =1";

    $result = $this->db->update($query);
    if ($result) {
      header('Location: transfer.php?stt=success&url=setting');
    } else {
      return "Lỗi thao tác!";
    }
  }
}

?>
