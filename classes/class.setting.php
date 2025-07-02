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
    $this->fn = new Functions();
  }

  public function update_watermark($data, $files)
  {
    $fields = ['position', 'opacity', 'per', 'small_per', 'max', 'min', 'offset_x', 'offset_y'];
    $data_escaped = [];
    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }
    $width = isset($data['thumb_width']) ? (int)$data['thumb_width'] : 300;
    $height = isset($data['thumb_height']) ? (int)$data['thumb_height'] : 120;
    $thumb_size = "{$width}x{$height}x1";
    $thumb_filename = '';
    $old_file_path = '';
    if (!empty($files['watermark']['name'])) {
      $ext = strtolower(pathinfo($files['watermark']['name'], PATHINFO_EXTENSION));
      if ($ext !== 'png') {
        $this->fn->transfer("Vui lòng chọn file PNG để giữ nền trong suốt", "index.php?page=watermark", false);
      }
      $query = "SELECT `watermark` FROM tbl_watermark WHERE id = 1";
      $old_file = $this->db->select($query);
      if ($old_file && $old_file->num_rows > 0) {
        $row = $old_file->fetch_assoc();
        if (!empty($row['watermark'])) {
          $old_file_path = "uploads/" . $row['watermark'];
        }
      }
      $thumb_filename = $this->fn->Upload([
        'file' => $files['watermark'],
        'custom_name' => 'watermark',
        'thumb' => $thumb_size,
        'old_file_path' => '',
        'background' => [0, 0, 0, 127],
        'watermark' => false,
        'convert_webp' => false
      ]);
      if (!empty($thumb_filename) && !empty($old_file_path) && file_exists($old_file_path)) {
        unlink($old_file_path);
      }
    }

    $update_fields = [];
    foreach ($data_escaped as $field => $value) {
      $update_fields[] = "`$field` = '$value'";
    }
    if (!empty($thumb_filename)) {
      $update_fields[] = "`watermark` = '$thumb_filename'";
    }

    $update_query = "UPDATE tbl_watermark SET " . implode(", ", $update_fields) . " WHERE id = 1";
    $result = $this->db->update($update_query);
    $msg = $result ? "Cập nhật watermark thành công" : "Cập nhật watermark thất bại";

    $this->fn->transfer($msg, "index.php?page=watermark", $result);
  }
  public function update_setting_item($item, $data, $files)
  {
    $width = isset($data['thumb_width']) ? (int)$data['thumb_width'] : 300;
    $height = isset($data['thumb_height']) ? (int)$data['thumb_height'] : 120;
    $thumb_size = "{$width}x{$height}x1";
    $thumb_filename = '';
    $old_file_path = '';

    // Nếu có file mới upload
    if (!empty($files[$item]['name'])) {
      $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
      $ext = strtolower(pathinfo($files[$item]['name'], PATHINFO_EXTENSION));

      if (!in_array($ext, $allowed_extensions)) {
        $this->fn->transfer("File không hợp lệ! Chỉ chấp nhận JPG, JPEG, PNG, GIF", "index.php?page=setting_file&type={$item}", false);
      }

      // Lấy ảnh cũ
      $old = $this->db->rawQueryOne("SELECT `$item` FROM tbl_setting WHERE id = 1");
      if (!empty($old[$item])) {
        $old_file_path = "uploads/" . $old[$item];
      }

      // Upload ảnh mới
      $thumb_filename = $this->fn->Upload([
        'file' => $files[$item],
        'custom_name' => $item,
        'thumb' => $thumb_size,
        'background' => [0, 0, 0, 127],
        'old_file_path' => $old_file_path,
        'watermark' => false,
        'convert_webp' => false
      ]);
      // Xoá ảnh cũ nếu có
      if (!empty($thumb_filename) && !empty($old_file_path) && file_exists($old_file_path)) {
        unlink($old_file_path);
      }

      // Cập nhật CSDL
      $updated = $this->db->execute("UPDATE tbl_setting SET `$item` = ? WHERE id = 1", [$thumb_filename]);
      $msg = $updated ? "Cập nhật ảnh thành công" : "Cập nhật ảnh thất bại";
      $this->fn->transfer($msg, "index.php?page=setting_list&type={$item}", $updated);
    } else {
      $this->fn->transfer("Không có ảnh mới, không cần cập nhật", "index.php?page=setting_list&type={$item}", true);
    }
  }

  public function get_setting_item($item)
  {
    $query = "SELECT `$item` FROM tbl_setting WHERE id = '1' LIMIT 1";
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
      'desc',
      'coords_iframe',
      'coords',
      'analytics',
      'headjs',
      'bodyjs',
      'address',
      'color'
    ];
    $updates = [];
    foreach ($fields as $field) {
      $value = mysqli_real_escape_string($this->db->link, $data[$field]);
      $updates[] = "`$field` = '$value'";
    }
    $query = "UPDATE tbl_setting SET " . implode(', ', $updates) . " WHERE id =1";
    $result = $this->db->update($query);
    $msg = $result ? "Cập nhật dữ liệu thành công" : "Không có dữ liệu để cập nhật hoặc lỗi xảy ra";
    $redirectPath = $this->fn->getRedirectPath(['table' => 'tbl_setting']);
    $this->fn->transfer($msg, $redirectPath, $result);
  }
}

?>
