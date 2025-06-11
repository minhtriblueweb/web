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
    $this->fn = new functions();
  }

  private function ImageUpload($file_source_path, $original_name, $thumb_width, $thumb_height, $old_file_path = '', $background = [0, 0, 0, 127])
  {
    $thumb_filename = $this->fn->add_thumb($file_source_path, $thumb_width, $thumb_height, $background);

    if (!$thumb_filename) {
      $thumb_filename = basename($file_source_path);
    } else {
      if (file_exists($file_source_path)) {
        unlink($file_source_path);
      }
    }

    if (!empty($old_file_path) && file_exists($old_file_path)) {
      unlink($old_file_path);
    }

    return $thumb_filename;
  }

  public function get_name_danhmuc($id_list)
  {
    $id_list = mysqli_real_escape_string($this->db->link, $id_list);
    $query = "SELECT * FROM tbl_danhmuc WHERE id = '$id_list' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_danhmuc($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM tbl_danhmuc WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_danhmuc_c2($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM tbl_danhmuc_c2 WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_id_danhmuc($id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM tbl_danhmuc WHERE id = '$id' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_id_danhmuc_c2($id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM tbl_danhmuc_c2 WHERE id = '$id' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function deleteMultipleCategories($listid, $table, $imageColumn, $redirectUrl)
  {
    $querySelect = "SELECT `$imageColumn` FROM `$table` WHERE id IN ($listid)";
    $resultSelect = $this->db->select($querySelect);

    if ($resultSelect && $resultSelect->num_rows > 0) {
      while ($row = $resultSelect->fetch_assoc()) {
        $filePath = 'uploads/' . $row[$imageColumn];
        if (!empty($row[$imageColumn]) && file_exists($filePath)) {
          unlink($filePath);
        }
      }
    }
    $queryDelete = "DELETE FROM `$table` WHERE id IN ($listid)";
    $resultDelete = $this->db->delete($queryDelete);

    if ($resultDelete) {
      header("Location: transfer.php?stt=success&url=$redirectUrl");
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function del_category($id, $table, $redirect_url)
  {
    $del_file_name = "SELECT file FROM $table WHERE id='$id'";
    $delta = $this->db->select($del_file_name);
    $string = "";
    while ($rowData = $delta->fetch_assoc()) {
      $string .= $rowData['file'];
    }
    $delLink = "uploads/" . $string;
    if (!empty($string) && file_exists($delLink)) {
      unlink($delLink);
    }
    $query = "DELETE FROM $table WHERE id = '$id'";
    $result = $this->db->delete($query);
    if ($result) {
      header("Location: transfer.php?stt=success&url=$redirect_url");
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }


  public function show_danhmuc_index($hienthi = '')
  {
    $query = "SELECT * FROM tbl_danhmuc";
    if (!empty($hienthi)) {
      $query .= " WHERE hienthi = '$hienthi'";
    }
    $query .= " ORDER BY numb ASC";
    return $this->db->select($query);
  }

  public function show_danhmuc_noibat($hienthi = '', $noibat = '')
  {
    $query = "SELECT * FROM tbl_danhmuc";
    if (!empty($hienthi)) {
      $query .= " WHERE hienthi = '$hienthi' AND noibat = '$noibat'";
    }
    $query .= " ORDER BY numb ASC";
    return $this->db->select($query);
  }

  public function show_danhmuc($tbl, $records_per_page = null, $current_page = null)
  {
    $query = "SELECT * FROM $tbl ORDER BY numb,id DESC ";
    if ($records_per_page !== null && $current_page !== null) {
      $offset = ((int)$current_page - 1) * (int)$records_per_page;
      $query .= "LIMIT $records_per_page OFFSET $offset";
    }
    $result = $this->db->select($query);
    return $result;
  }

  public function update_danhmuc_c2($data, $files, $id)
  {
    // Danh sách các trường cần xử lý
    $fields = ['slugvi', 'namevi', 'id_list', 'titlevi', 'keywordsvi', 'descriptionvi', 'numb', 'hienthi'];
    $data_escaped = [];

    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }

    // Kiểm tra slug bị trùng
    $slugvi = $data_escaped['slugvi'];
    $check_slug = "SELECT slugvi FROM tbl_danhmuc_c2 WHERE slugvi = '$slugvi' AND id != '$id'";
    $result_check_slug = $this->db->select($check_slug);
    if ($result_check_slug && $result_check_slug->num_rows > 0) {
      return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
    }

    // Chuẩn bị trường cập nhật
    $update_fields = [];
    foreach ($fields as $field) {
      $update_fields[] = "`$field` = '{$data_escaped[$field]}'";
    }

    // Xử lý ảnh nếu có
    $file_name = $_FILES["file"]["name"] ?? '';
    $file_tmp_name = $_FILES["file"]["tmp_name"] ?? '';
    if (!empty($file_name) && !empty($file_tmp_name)) {
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
      $uploaded_image = "uploads/" . $unique_image;

      if (move_uploaded_file($file_tmp_name, $uploaded_image)) {
        // Lấy ảnh cũ để xóa sau khi tạo thumb
        $del_file_query = "SELECT file FROM tbl_danhmuc_c2 WHERE id = '$id'";
        $del_file = $this->db->select($del_file_query);
        $old_file_path = '';
        if ($del_file && $del_file->num_rows > 0) {
          $old_file_name = $del_file->fetch_assoc()['file'];
          $old_file_path = "uploads/" . $old_file_name;
        }
        $thumb_filename = $this->ImageUpload($uploaded_image, $file_name, 100, 100, $old_file_path, [0, 0, 0, 127]);
        if (!empty($thumb_filename)) {
          $update_fields[] = "`file` = '$thumb_filename'";
        }
      }
    }

    // Thực hiện cập nhật
    $update_query = "UPDATE tbl_danhmuc_c2 SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
    $result = $this->db->update($update_query);

    if ($result) {
      header('Location: transfer.php?stt=success&url=category_lv2_list');
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }


  public function update_danhmuc($data, $files, $id)
  {
    $fields = ['slugvi', 'namevi', 'descvi', 'contentvi', 'titlevi', 'keywordsvi', 'descriptionvi', 'numb', 'hienthi', 'noibat'];
    $data_escaped = [];

    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }

    // Kiểm tra slug trùng
    $duplicated_in = $this->fn->isSlugviDuplicated($data_escaped['slugvi'], 'tbl_danhmuc', '');
    if ($duplicated_in) {
      return "Đường dẫn đã tồn tại trong bảng <b>$duplicated_in</b>. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
    }

    $file_name = $_FILES["file"]["name"] ?? '';
    $file_tmp = $_FILES["file"]["tmp_name"] ?? '';
    $thumb_filename = '';

    if (!empty($file_name) && !empty($file_tmp)) {
      // Tạo tên tạm cho file upload
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
      $uploaded_image = "uploads/" . $unique_image;

      if (move_uploaded_file($file_tmp, $uploaded_image)) {
        // Lấy ảnh cũ để xóa
        $old_file_query = "SELECT file FROM tbl_danhmuc WHERE id = '$id'";
        $old_file = $this->db->select($old_file_query);
        $old_file_path = '';
        if ($old_file && $old_file->num_rows > 0) {
          $row = $old_file->fetch_assoc();
          $old_file_path = "uploads/" . $row['file'];
        }
        $thumb_filename = $this->ImageUpload($uploaded_image, $file_name, 50, 50, $old_file_path, [0, 0, 0, 127]);
      }
    }
    $update_fields = [];
    foreach ($data_escaped as $field => $value) {
      $update_fields[] = "`$field` = '$value'";
    }

    if (!empty($thumb_filename)) {
      $update_fields[] = "file = '$thumb_filename'";
    }
    $update_query = "UPDATE tbl_danhmuc SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
    $result = $this->db->update($update_query);

    if ($result) {
      header('Location: transfer.php?stt=success&url=category_lv1_list');
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }


  public function insert_danhmuc($data, $files)
  {
    $fields = ['slugvi', 'namevi', 'descvi', 'contentvi', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'noibat', 'numb'];
    $field_names = [];
    $field_values = [];

    foreach ($fields as $field) {
      $field_names[] = $field;
      $value = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
      $field_values[] = "'" . $value . "'";
    }

    $file_name = $_FILES["file"]["name"] ?? '';
    $file_tmp_name = $_FILES["file"]["tmp_name"] ?? '';
    $unique_image = '';

    if (!empty($file_name)) {
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
      $uploaded_image_path = "uploads/" . $unique_image;
      if (move_uploaded_file($file_tmp_name, $uploaded_image_path)) {
        $thumb_filename = $this->ImageUpload($uploaded_image_path, $file_name, 50, 50, '', [0, 0, 0, 127]);
        $field_names[] = 'file';
        $field_values[] = "'" . $thumb_filename . "'";
      }
    }

    // Kiểm tra slug trùng
    $duplicated_in = $this->fn->isSlugviDuplicated($data['slugvi'], 'tbl_danhmuc', '');
    if ($duplicated_in) {
      return "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
    }

    $query = "INSERT INTO tbl_danhmuc (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
    $result = $this->db->insert($query);

    if ($result) {
      header('Location: transfer.php?stt=success&url=category_lv1_list');
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function insert_danhmuc_c2($data, $files)
  {
    $fields = ['slugvi', 'namevi', 'descvi', 'contentvi', 'id_list', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'numb'];
    $data_escaped = [];

    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }
    $slug = $data_escaped['slugvi'];
    $check_slug_query = "SELECT slugvi FROM tbl_danhmuc_c2 WHERE slugvi = '$slug' LIMIT 1";
    $result_check_slug = $this->db->select($check_slug_query);
    if ($result_check_slug && $result_check_slug->num_rows > 0) {
      return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
    }
    $field_names = array_keys($data_escaped);
    $field_values = array_map(function ($value) {
      return "'$value'";
    }, array_values($data_escaped));

    $file_name = $_FILES["file"]["name"] ?? '';
    $file_tmp_name = $_FILES["file"]["tmp_name"] ?? '';

    if (!empty($file_name) && !empty($file_tmp_name)) {
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
      $uploaded_image_path = "uploads/" . $unique_image;

      if (move_uploaded_file($file_tmp_name, $uploaded_image_path)) {
        $thumb_filename = $this->ImageUpload($uploaded_image_path, $file_name, 100, 100, '', [0, 0, 0, 127]);
        if (!empty($thumb_filename)) {
          $field_names[] = 'file';
          $field_values[] = "'" . $thumb_filename . "'";
        }
      }
    }
    $insert_query = "INSERT INTO tbl_danhmuc_c2 (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
    $result = $this->db->insert($insert_query);
    if ($result) {
      header('Location: transfer.php?stt=success&url=category_lv2_list');
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function show_danhmuc_c2($tbl, $id_list = null)
  {
    $query = "SELECT * FROM $tbl";
    if ($id_list !== null) {
      $query .= " WHERE id_list = '$id_list'";
    }
    $query .= " ORDER BY numb, id DESC";
    $result = $this->db->select($query);
    return $result;
  }


  public function show_danhmuc_c2_index($list_id)
  {
    $query = "SELECT * FROM tbl_danhmuc_c2 WHERE id_list = '$list_id' AND hienthi = 'hienthi' ORDER BY numb ASC";
    $result = $this->db->select($query);
    return $result;
  }
}

?>
