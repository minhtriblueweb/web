<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class sanpham
{
  private $db;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }

  public function upload_gallery($data, $files, $id, $id_parent)
  {
    $id = (int)$id;
    $id_parent = (int)$id_parent;
    $parent_name = '';
    $parent_query = $this->db->select("SELECT namevi FROM tbl_sanpham WHERE id = '$id_parent' LIMIT 1");
    if ($parent_query && $parent_query->num_rows > 0) {
      $parent_row = $parent_query->fetch_assoc();
      $parent_name = $parent_row['namevi'] ?? '';
    }
    $table = 'tbl_gallery';
    $data_escaped = [];
    $data_escaped['numb'] = mysqli_real_escape_string($this->db->link, $data['numb'] ?? 0);
    $status_flags = ['hienthi'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) {
        $status_values[] = $flag;
      }
    }
    $data_escaped['status'] = mysqli_real_escape_string($this->db->link, implode(',', $status_values));
    if (!empty($files['file']['name']) && !empty($files['file']['tmp_name'])) {
      $old_file_path = '';
      $old = $this->db->select("SELECT file FROM `$table` WHERE id = '$id'");
      if ($old && $old->num_rows > 0) {
        $row = $old->fetch_assoc();
        $old_file_path = 'uploads/' . $row['file'];
      }
      $thumb_filename = $this->fn->Upload([
        'file' => $files['file'],
        'custom_name' => $parent_name,
        'thumb' => '500x500x1',
        'old_file_path' => $old_file_path,
        'watermark' => true,
        'convert_webp' => true
      ]);
      if (empty($thumb_filename)) {
        return "Lỗi upload file!";
      }
      $data_escaped['file'] = mysqli_real_escape_string($this->db->link, $thumb_filename);
    }
    $update_fields = [];
    foreach ($data_escaped as $field => $value) {
      $update_fields[] = "`$field` = '$value'";
    }
    if (!empty($update_fields)) {
      $query = "UPDATE `$table` SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
      $result = $this->db->update($query);
    }
    $redirectPath = $this->fn->getRedirectPath([
      'table' => $table,
      'id_parent' => $id_parent
    ]);
    $this->fn->transfer(
      $result ? "Cập nhật hình ảnh thành công" : "Cập nhật hình ảnh thất bại!",
      $redirectPath,
      $result
    );
  }

  public function them_gallery($data, $files, $id_parent)
  {
    $id_parent = mysqli_real_escape_string($this->db->link, $id_parent);
    $parent_name = '';
    $parent_query = $this->db->select("SELECT namevi FROM tbl_sanpham WHERE id = '$id_parent' LIMIT 1");
    if ($parent_query && $parent_query->num_rows > 0) {
      $parent_row = $parent_query->fetch_assoc();
      $parent_name = $parent_row['namevi'] ?? '';
    }
    $table = 'tbl_gallery';
    for ($i = 0; $i < 6; $i++) {
      $file_key = "file$i";
      if (!empty($files[$file_key]['name']) && $files[$file_key]['error'] == 0) {
        // Upload ảnh
        $thumb_filename = $this->fn->Upload([
          'file' => $files[$file_key],
          'custom_name' => $parent_name,
          'thumb' => '500x500x1',
          'background' => [255, 255, 255, 0],
          'old_file_path' => '',
          'watermark' => true,
          'convert_webp' => true
        ]);
        if (!empty($thumb_filename)) {
          $fields = ['id_parent', 'file', 'numb', 'status'];
          $data_escaped = [];
          $data_escaped['id_parent'] = "'" . $id_parent . "'";
          $data_escaped['file'] = "'" . mysqli_real_escape_string($this->db->link, $thumb_filename) . "'";
          $data_escaped['numb'] = "'" . mysqli_real_escape_string($this->db->link, $data["numb$i"] ?? 0) . "'";
          $status_flags = ['hienthi'];
          $status_values = [];
          foreach ($status_flags as $flag) {
            $flag_key = $flag . $i;
            if (!empty($data[$flag_key])) {
              $status_values[] = $flag;
            }
          }
          $data_escaped['status'] = "'" . mysqli_real_escape_string($this->db->link, implode(',', $status_values)) . "'";
          $field_names = array_map(fn($k) => "`$k`", array_keys($data_escaped));
          $field_values = array_values($data_escaped);

          $query = "INSERT INTO `$table` (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
          $result = $this->db->insert($query);
        }
      }
    }
    $redirectPath = $this->fn->getRedirectPath([
      'table' => $table,
      'id_parent' => $id_parent
    ]);
    $this->fn->transfer(
      $query ? "Cập nhật hình ảnh thành công" : "Cập nhật hình ảnh thất bại!",
      $redirectPath,
      $result
    );
  }

  public function get_img_gallery($id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM tbl_gallery WHERE id = '$id' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_gallery($id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM tbl_gallery WHERE id_parent = '$id' ORDER BY numb ASC";
    $result = $this->db->select($query);
    return $result;
  }

  public function update_views_by_slug($slug)
  {
    $query = "SELECT * FROM tbl_sanpham WHERE slugvi = '$slug'";
    $result = $this->db->select($query);
    if ($result && $result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $new_views = $product['views'] + 1;
      $update_query = "UPDATE tbl_sanpham SET views = '$new_views' WHERE slugvi = '$slug'";
      $this->db->update($update_query);
      return $product;
    }
    return false;
  }

  public function get_danhmuc_by_sanpham($id)
  {
    $query = "SELECT tbl_sanpham.*,
        tbl_danhmuc_c1.namevi AS dm_c1_name,
        tbl_danhmuc_c1.slugvi AS dm_c1_slug,
        tbl_danhmuc_c2.namevi AS dm_c2_name,
        tbl_danhmuc_c2.slugvi AS dm_c2_slug
        FROM tbl_sanpham
        INNER JOIN tbl_danhmuc_c1 ON tbl_sanpham.id_list = tbl_danhmuc_c1.id
        LEFT JOIN tbl_danhmuc_c2 ON tbl_sanpham.id_cat = tbl_danhmuc_c2.id
        WHERE tbl_sanpham.id = '$id'";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_sanpham_by_slug($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM tbl_sanpham WHERE slugvi = '$slug' AND FIND_IN_SET('hienthi', status) LIMIT 1";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc() : false;
  }

  public function get_name_danhmuc($id, $table)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $table = mysqli_real_escape_string($this->db->link, $table);
    $query = "SELECT namevi FROM `$table` WHERE id = '$id' LIMIT 1";
    $result = $this->db->select($query);
    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['namevi'] ?? '';
    }
    return '';
  }

  public function save_sanpham($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $fields_multi = ['slug', 'name', 'desc', 'content', 'title', 'keywords', 'description'];
    $fields_common = ['id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code', 'type', 'numb'];
    $table = 'tbl_sanpham';
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
    $status_flags = ['hienthi', 'noibat', 'banchay'];
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
      'watermark' => true,
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
      $update_query = "UPDATE $table SET " . implode(", ", $update_fields) . " WHERE id = '" . (int)$id . "'";
      $result = $this->db->update($update_query);
      $msg = $result ? "Cập nhật sản phẩm thành công" : "Cập nhật sản phẩm thất bại";
    } else {
      $field_names = array_keys($data_escaped);
      $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);
      if (!empty($thumb_filename)) {
        $field_names[] = 'file';
        $field_values[] = "'" . $thumb_filename . "'";
      }
      $insert_query = "INSERT INTO $table (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
      $result = $this->db->insert($insert_query);
      $msg = $result ? "Thêm sản phẩm thành công" : "Thêm sản phẩm thất bại";
    }
    $this->fn->transfer($msg, $this->fn->getRedirectPath(['table' => $table]), $result);
  }
}
?>
