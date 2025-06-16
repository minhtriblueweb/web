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

  public function show_sanpham_pagination($records_per_page, $current_page, $hienthi = '', $id_list = '', $id_cat = '', $limit = 0)
  {
    // Escape các giá trị truyền vào
    $hienthi = mysqli_real_escape_string($this->db->link, $hienthi);
    $id_list = mysqli_real_escape_string($this->db->link, $id_list);
    $id_cat = mysqli_real_escape_string($this->db->link, $id_cat);

    // Bắt đầu query
    $query = "SELECT * FROM tbl_sanpham WHERE 1";

    // Thêm điều kiện nếu có
    if ($hienthi !== '') {
      $query .= " AND hienthi = '$hienthi'";
    }

    if ($id_list !== '') {
      $query .= " AND id_list = '$id_list'";
    }

    if ($id_cat !== '') {
      $query .= " AND id_cat = '$id_cat'";
    }

    // Thứ tự sắp xếp
    $query .= " ORDER BY numb, id DESC";

    // Xử lý phân trang hoặc limit
    if ($limit > 0) {
      $query .= " LIMIT $limit";
    } else {
      $offset = ((int)$current_page - 1) * (int)$records_per_page;
      $query .= " LIMIT $records_per_page OFFSET $offset";
    }

    $result = $this->db->select($query);
    return $result;
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
    $hienthi = mysqli_real_escape_string($this->db->link, $data['hienthi'] ?? 0);
    $numb = mysqli_real_escape_string($this->db->link, $data['numb'] ?? 0);
    $thumb_filename = '';
    if (!empty($files['file']['name']) && !empty($files['file']['tmp_name'])) {
      $old_file_path = '';
      $old = $this->db->select("SELECT photo FROM tbl_gallery WHERE id='$id'");
      if ($old && $old->num_rows > 0) {
        $row = $old->fetch_assoc();
        $old_file_path = 'uploads/' . $row['photo'];
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
    }
    $update_fields = [
      "hienthi = '$hienthi'",
      "numb = '$numb'"
    ];
    if (!empty($thumb_filename)) {
      $update_fields[] = "photo = '" . mysqli_real_escape_string($this->db->link, $thumb_filename) . "'";
    }
    $query = "UPDATE tbl_gallery SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
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

    for ($i = 0; $i < 6; $i++) {
      $file_key = "file$i";
      if (!empty($files[$file_key]['name']) && $files[$file_key]['error'] == 0) {
        // Gọi hàm Upload
        $thumb_filename = $this->fn->Upload(
          ['file' => $files[$file_key]],
          '500x500x1',
          [0, 0, 0, 127],
          '',
          true
        );
        if (!empty($thumb_filename)) {
          $fields = ['id_parent', 'photo', 'numb', 'hienthi'];
          $values = [
            "'" . $id_parent . "'",
            "'" . mysqli_real_escape_string($this->db->link, $thumb_filename) . "'",
            "'" . mysqli_real_escape_string($this->db->link, $data["numb$i"] ?? 0) . "'",
            "'" . mysqli_real_escape_string($this->db->link, $data["hienthi$i"] ?? 0) . "'"
          ];

          $insert_query = "INSERT INTO tbl_gallery (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ")";
          $this->db->insert($insert_query);
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

  public function get_id_sanpham($id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM tbl_sanpham WHERE id = '$id' LIMIT 1";
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
        tbl_danhmuc.namevi AS dm_c1_name,
        tbl_danhmuc.slugvi AS dm_c1_slug,
        tbl_danhmuc_c2.namevi AS dm_c2_name,
        tbl_danhmuc_c2.slugvi AS dm_c2_slug
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
    $query = "SELECT * FROM tbl_sanpham WHERE slugvi = '$slug' AND hienthi = 'hienthi' LIMIT 1";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc() : false;
  }

  public function xoanhieu_sanpham($listid)
  {
    // Lấy danh sách các file cần xóa trước khi xóa bản ghi
    $del_file_query = "SELECT file FROM tbl_sanpham WHERE id IN ($listid)";
    $old_files = $this->db->select($del_file_query);

    // Xóa các file trên hệ thống
    if ($old_files) {
      while ($rowData = $old_files->fetch_assoc()) {
        $old_file_path = "uploads/" . $rowData['file'];
        if (file_exists($old_file_path)) {
          unlink($old_file_path);  // Xóa file nếu tồn tại
        }
      }
    }
    $query = "DELETE FROM tbl_sanpham WHERE id IN ($listid)";
    $result = $this->db->delete($query);
    if ($result) {
      header('Location: transfer.php?stt=success&url=product_list');
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function del_sanpham($id)
  {
    $del_file_name = "SELECT file FROM tbl_sanpham WHERE id='$id'";
    $delta = $this->db->select($del_file_name);
    $string = "";
    while ($rowData = $delta->fetch_assoc()) {
      $string .= $rowData['file'];
    }
    $delLink = "uploads/" . $string;
    unlink("$delLink");
    $query = "DELETE FROM tbl_sanpham WHERE id = '$id'";
    $result = $this->db->delete($query);
    if ($result) {
      header('Location: transfer.php?stt=success&url=product_list');
    } else {
      return "Lỗi thao tác!";
    }
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

  public function show_sanpham($records_per_page, $current_page, $hienthi = '')
  {
    if (!empty($hienthi)) {
      $query = "SELECT * FROM tbl_sanpham WHERE hienthi = '$hienthi' ORDER BY numb,id ASC";
      $result = $this->db->select($query);
      return $result;
    } else {
      $offset = ((int)$current_page - 1) * (int)$records_per_page;
      $query = "SELECT * FROM tbl_sanpham ORDER BY numb,id DESC LIMIT $records_per_page OFFSET $offset";
      $result = $this->db->select($query);
      return $result;
    }
  }

  public function sanpham_banchay()
  {
    $query = "SELECT * FROM tbl_sanpham WHERE banchay = 'banchay' AND hienthi = 'hienthi' ORDER BY numb ASC";
    $result = $this->db->select($query);
    return $result;
  }

  public function show_sanpham_tc($id_list = '')
  {
    $query = "SELECT * FROM tbl_sanpham WHERE id_list = '$id_list' AND hienthi = 'hienthi' ORDER BY numb,id DESC LIMIT 10";
    $result = $this->db->select($query);
    return $result;
  }

  public function show_sanpham_tc_c2($id_c2)
  {
    $query = "SELECT * FROM tbl_sanpham WHERE id_cat = '$id_c2' AND hienthi = 'hienthi' ORDER BY numb,id DESC LIMIT 10";
    $result = $this->db->select($query);
    return $result;
  }

  public function show_sanpham_cap_1($id_list = '')
  {
    $query = "SELECT * FROM tbl_sanpham WHERE id_list = '$id_list' AND hienthi = 'hienthi' ORDER BY numb,id DESC";
    $result = $this->db->select($query);
    return $result;
  }

  public function count_sanpham($id_list = '', $id_cat = '')
  {
    $id_list = mysqli_real_escape_string($this->db->link, $id_list);
    $id_cat = mysqli_real_escape_string($this->db->link, $id_cat);
    $query = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE hienthi = 'hienthi'";
    if ($id_list !== '') {
      $query .= " AND id_list = '$id_list'";
    }
    if ($id_cat !== '') {
      $query .= " AND id_cat = '$id_cat'";
    }
    $result = $this->db->select($query);
    if ($result) {
      $row = $result->fetch_assoc();
      return $row['total'];
    }
    return 0;
  }


  public function show_sanpham_c2_tc($id_cat = '')
  {
    $query = "SELECT * FROM tbl_sanpham WHERE id_cat = '$id_cat' AND hienthi = 'hienthi' ORDER BY numb,id DESC";
    $result = $this->db->select($query);
    return $result;
  }

  public function save_sanpham($data, $files, $id = null)
  {
    $fields = [
      'slugvi',
      'namevi',
      'id_list',
      'id_cat',
      'regular_price',
      'sale_price',
      'discount',
      'code',
      'descvi',
      'contentvi',
      'titlevi',
      'keywordsvi',
      'descriptionvi',
      'hienthi',
      'banchay',
      'numb'
    ];
    $table = 'tbl_sanpham';
    $data_escaped = [];
    foreach ($fields as $field) {
      $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
    }
    $slug_error = $this->fn->isSlugviDuplicated($data_escaped['slugvi'], $table, $id ?? '');
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
    $thumb_filename = $this->fn->Upload($files, '500x500x1', [0, 0, 0, 127], $old_file_path, true);
    if (!empty($id)) {
      $update_fields = [];
      foreach ($data_escaped as $field => $value) {
        $update_fields[] = "`$field` = '$value'";
      }
      if (!empty($thumb_filename)) {
        $update_fields[] = "file = '$thumb_filename'";
      }
      $query = "UPDATE $table SET " . implode(", ", $update_fields) . " WHERE id = '" . (int)$id . "'";
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
