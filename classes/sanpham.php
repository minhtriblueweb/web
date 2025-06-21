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
    $this->fn = new functions();
  }

  public function total_pages_sanpham_lienquan($id, $id_cat, $limit)
  {
    $id_cat = mysqli_real_escape_string($this->db->link, $id_cat);
    $query = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE id_cat = '$id_cat' AND id != '$id'";
    $result = $this->db->select($query);
    if ($result) {
      $row = $result->fetch_assoc();
      $total_products = $row['total'];
      $total_pages = ceil($total_products / $limit);
      return $total_pages;
    } else {
      return 0;
    }
  }

  public function sanpham_lienquan($id, $id_cat, $records_per_page, $current_page)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $id_cat = mysqli_real_escape_string($this->db->link, $id_cat);
    $offset = ((int)$current_page - 1) * (int)$records_per_page;
    $query = "SELECT * FROM tbl_sanpham WHERE id_cat = '$id_cat' AND id != '$id' AND hienthi='hienthi' ORDER BY numb ASC LIMIT $records_per_page OFFSET $offset";
    $result = $this->db->select($query);
    return $result;
  }

  public function upload_gallery($data, $files, $id, $id_parent)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $id_parent = mysqli_real_escape_string($this->db->link, $id_parent);
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
    $thumb_filename = '';
    if (!empty($files['file']['name']) && !empty($files['file']['tmp_name'])) {
      $old_file_path = '';
      $old = $this->db->select("SELECT file FROM $table WHERE id='$id'");
      if ($old && $old->num_rows > 0) {
        $row = $old->fetch_assoc();
        $old_file_path = 'uploads/' . $row['file'];
      }
      $thumb_filename = $this->fn->Upload(
        ['file' => $files['file']],
        '500x500x1',
        [0, 0, 0, 127],
        $old_file_path,
        true
      );
      if (empty($thumb_filename)) {
        return "Lỗi upload file!";
      }
      $data_escaped['file'] = mysqli_real_escape_string($this->db->link, $thumb_filename);
    }
    $update_fields = [];
    foreach ($data_escaped as $field => $value) {
      $update_fields[] = "`$field` = '$value'";
    }
    $query = "UPDATE `$table` SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
    $result = $this->db->update($query);
    if ($result) {
      $this->fn->transfer("Cập nhật hình ảnh thành công", "index.php?page=gallery_list&id=$id_parent");
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function them_gallery($data, $files, $id_parent)
  {
    $id_parent = mysqli_real_escape_string($this->db->link, $id_parent);
    $table = 'tbl_gallery';
    for ($i = 0; $i < 6; $i++) {
      $file_key = "file$i";
      if (!empty($files[$file_key]['name']) && $files[$file_key]['error'] == 0) {
        // Upload ảnh
        $thumb_filename = $this->fn->Upload(
          ['file' => $files[$file_key]],
          '500x500x1',
          [0, 0, 0, 127],
          '',
          true
        );
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
          $this->db->insert($query);
        }
      }
    }

    $this->fn->transfer("Thêm hình ảnh thành công", "index.php?page=gallery_list&id=$id_parent");
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

  public function update_ews_by_slug($slug)
  {
    $query = "SELECT * FROM tbl_sanpham WHERE slug = '$slug'";
    $result = $this->db->select($query);
    if ($result && $result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $new_ews = $product['ews'] + 1;
      $update_query = "UPDATE tbl_sanpham SET ews = '$new_ews' WHERE slug = '$slug'";
      $this->db->update($update_query);
      return $product;
    }
    return false;
  }


  public function get_danhmuc_by_sanpham($id)
  {
    $query = "SELECT tbl_sanpham.*,
        tbl_danhmuc.name AS dm_c1_name,
        tbl_danhmuc.slug AS dm_c1_slug,
        tbl_danhmuc_c2.name AS dm_c2_name,
        tbl_danhmuc_c2.slug AS dm_c2_slug
        FROM tbl_sanpham
        INNER JOIN tbl_danhmuc ON tbl_sanpham.id_list = tbl_danhmuc.id
        LEFT JOIN tbl_danhmuc_c2 ON tbl_sanpham.id_cat = tbl_danhmuc_c2.id
        WHERE tbl_sanpham.id = '$id'";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_sanpham_by_slug($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM tbl_sanpham WHERE slug = '$slug' AND hienthi = 'hienthi' LIMIT 1";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc() : false;
  }

  public function get_name_danhmuc($id, $table)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $table = mysqli_real_escape_string($this->db->link, $table);
    $query = "SELECT name FROM `$table` WHERE id = '$id' LIMIT 1";
    $result = $this->db->select($query);
    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['name'] ?? '';
    }
    return '';
  }

  public function save_sanpham($data, $files, $id = null)
  {
    $fields = [
      'slug',
      'name',
      'id_list',
      'id_cat',
      'regular_price',
      'sale_price',
      'discount',
      'code',
      'desc',
      'content',
      'title',
      'keywords',
      'description',
      'numb'
    ];
    $table = 'tbl_sanpham';
    $data_escaped = [];
    foreach ($fields as $field) {
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
    $slug_error = $this->fn->isSlugDuplicated($data_escaped['slug'], $table, $id ?? '');
    if ($slug_error) return $slug_error;
    $thumb_filename = '';
    $old_file_path = '';
    if (!empty($id)) {
      $old_file = $this->db->select("SELECT file FROM $table WHERE id = '" . (int)$id . "'");
      if ($old_file && $old_file->num_rows > 0) {
        $row = $old_file->fetch_assoc();
        $old_file_path = "uploads/" . $row['file'];
      }
    }
    $thumb_filename = $this->fn->Upload($files, '500x500x1', [0, 0, 0, 127], $old_file_path, $watermark = true, $convert_webp = true);
    if (!empty($id)) {
      $update_fields = [];
      foreach ($data_escaped as $field => $value) {
        $update_fields[] = "`$field` = '$value'";
      }
      if (!empty($thumb_filename)) {
        $update_fields[] = "file = '$thumb_filename'";
      }
      $query = "UPDATE $table SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
      $result = $this->db->update($query);
      $msg = $result ? "Cập nhật sản phẩm thành công" : "Cập nhật sản phẩm thất bại";
    } else {
      $field_names = array_keys($data_escaped);
      $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);
      if (!empty($thumb_filename)) {
        $field_names[] = 'file';
        $field_values[] = "'" . $thumb_filename . "'";
      }
      $query = "INSERT INTO $table (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
      $result = $this->db->insert($query);
      $msg = $result ? "Thêm sản phẩm thành công" : "Thêm sản phẩm thất bại";
    }
    $this->fn->transfer($msg, "index.php?page=product_list", $result);
  }
}
?>
