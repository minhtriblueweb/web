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

  public function get_danhmuc_c2_with_parent($slug)
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
        JOIN tbl_danhmuc c1 ON c2.id_list = c1.id
        WHERE c2.slugvi = '$slug'
        LIMIT 1
    ";

    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc() : false;
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
        JOIN tbl_danhmuc AS c1 ON c2.id_list = c1.id
        WHERE c2.slugvi = '$slug_lv2'
        LIMIT 1
    ";

    $result = $this->db->select($query);

    if ($result && $row = $result->fetch_assoc()) {
      return $row;
    }

    return false;
  }

  public function slug_exists_lv1($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT id FROM tbl_danhmuc WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    return $result ? true : false;
  }

  public function slug_exists_lv2($slug_lv2, $slug_lv1)
  {
    $slug_lv1 = mysqli_real_escape_string($this->db->link, $slug_lv1);
    $slug_lv2 = mysqli_real_escape_string($this->db->link, $slug_lv2);

    // Lấy ID của danh mục cấp 1 từ slug
    $query_lv1 = "SELECT id FROM tbl_danhmuc WHERE slugvi = '$slug_lv1' LIMIT 1";
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
    $fields = ['slugvi', 'namevi', 'id_list', 'titlevi', 'keywordsvi', 'descriptionvi', 'numb', 'hienthi', 'noibat'];
    $data_escaped = [];
    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }
    // Kiểm tra slug bị trùng
    $slug_error = $this->fn->isSlugviDuplicated($data_escaped['slugvi'], 'tbl_danhmuc_c2', $id);
    if ($slug_error) {
      return $slug_error;
    }
    $file_name = $files["file"]["name"] ?? '';
    $old_file_path = '';
    $old_file = $this->db->select("SELECT file FROM tbl_danhmuc_c2 WHERE id = '$id'");
    if ($old_file && $old_file->num_rows > 0) {
      $row = $old_file->fetch_assoc();
      $old_file_path = "uploads/" . $row['file'];
    }
    $thumb_filename = $this->fn->Upload($files, '100x100x1', [0, 0, 0, 127], $old_file_path, false);
    $update_fields = [];
    foreach ($data_escaped as $field => $value) {
      $update_fields[] = "`$field` = '$value'";
    }
    if (!empty($thumb_filename)) {
      $update_fields[] = "file = '$thumb_filename'";
    }
    $id = (int)$id;
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
    $slug_error = $this->fn->isSlugviDuplicated($data_escaped['slugvi'], 'tbl_danhmuc', $id);
    if ($slug_error) {
      return $slug_error;
    }
    $file_name = $files["file"]["name"] ?? '';
    $old_file_path = '';
    $old_file = $this->db->select("SELECT file FROM tbl_danhmuc WHERE id = '$id'");
    if ($old_file && $old_file->num_rows > 0) {
      $row = $old_file->fetch_assoc();
      $old_file_path = "uploads/" . $row['file'];
    }
    $thumb_filename = $this->fn->Upload($files, '50x50x1', [0, 0, 0, 127], $old_file_path, false);
    $update_fields = [];
    foreach ($data_escaped as $field => $value) {
      $update_fields[] = "`$field` = '$value'";
    }

    if (!empty($thumb_filename)) {
      $update_fields[] = "file = '$thumb_filename'";
    }
    $id = (int)$id;
    $update_query = "UPDATE tbl_danhmuc SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
    $result = $this->db->update($update_query);

    if ($result) {
      $this->fn->transfer("Cập nhật danh mục thành công", "index.php?page=category_lv1_list", true);
    } else {
      $this->fn->transfer("Cập nhật danh mục thất bại", "index.php?page=category_lv1_list", false);
    }
  }


  public function insert_danhmuc($data, $files)
  {
    $fields = ['slugvi', 'namevi', 'descvi', 'contentvi', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'noibat', 'numb'];
    $data_escaped = [];
    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }
    // Kiểm tra slug trùng
    $slug_error = $this->fn->isSlugviDuplicated($data_escaped['slugvi'], 'tbl_danhmuc', '');
    if ($slug_error) {
      return $slug_error;
    }
    $field_names = array_keys($data_escaped);
    $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);

    $thumb_filename = $this->fn->Upload($files, '50x50x1', [0, 0, 0, 127], '',  false);
    if (!empty($thumb_filename)) {
      $field_names[] = 'file';
      $field_values[] = "'" . $thumb_filename . "'";
    }
    $insert_query = "INSERT INTO tbl_danhmuc (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
    $result = $this->db->insert($insert_query);

    if ($result) {
      $this->fn->transfer("Thêm danh mục thành công", "index.php?page=category_lv1_list", true);
    } else {
      $this->fn->transfer("Thêm danh mục thất bại", "index.php?page=category_lv1_list", false);
    }
  }

  public function insert_danhmuc_c2($data, $files)
  {
    $fields = ['slugvi', 'namevi', 'descvi', 'contentvi', 'id_list', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'noibat', 'numb'];
    $data_escaped = [];

    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }
    // Kiểm tra slug trùng
    $slug_error = $this->fn->isSlugviDuplicated($data_escaped['slugvi'], 'tbl_danhmuc', '');
    if ($slug_error) {
      return $slug_error;
    }
    $field_names = array_keys($data_escaped);
    $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);

    $thumb_filename = $this->fn->Upload($files, '100x100x1', [0, 0, 0, 127], '', false);
    if (!empty($thumb_filename)) {
      $field_names[] = 'file';
      $field_values[] = "'" . $thumb_filename . "'";
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
