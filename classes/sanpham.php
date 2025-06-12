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

  public function upload_gallery($data, $files, $id, $result_id_parent)
  {
    $hienthi = mysqli_real_escape_string($this->db->link, $data['hienthi']);
    $numb = mysqli_real_escape_string($this->db->link, $data['numb']);
    $result_id = mysqli_real_escape_string($this->db->link, $data['result_id']);
    $unique_image = '';
    $upload_success = false;
    if (!empty($files['file']['name'])) {
      $file_ext = strtolower(pathinfo($files['file']['name'], PATHINFO_EXTENSION));
      $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
      $upload_path = "uploads/" . $unique_image;
      if (move_uploaded_file($files['file']['tmp_name'], $upload_path)) {
        $upload_success = true;
      } else {
        return "Lỗi upload file!";
      }
    }
    if ($upload_success) {
      $del_file_query = "SELECT photo FROM tbl_gallery WHERE id='$id'";
      $old_file = $this->db->select($del_file_query);
      if ($old_file && $old_file->num_rows > 0) {
        $rowData = $old_file->fetch_assoc();
        $old_file_path = "uploads/" . $rowData['photo'];
        if (file_exists($old_file_path) && !empty($rowData['photo'])) {
          unlink($old_file_path);
        }
      }
    }
    $query = "UPDATE tbl_gallery SET
                hienthi = '$hienthi',
                numb = '$numb'";
    if (!empty($unique_image)) {
      $query .= ", photo = '$unique_image'";
    }
    $query .= " WHERE id = '$id'";
    $result = $this->db->update($query);
    if ($result) {
      header('Location: transfer.php?stt=success&url=gallery&id=' . $result_id_parent);
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }



  public function xoanhieu_gallery($listid)
  {
    $id_array = explode(',', $listid);
    foreach ($id_array as $id) {
      $id = mysqli_real_escape_string($this->db->link, trim($id));
      $del_file_name = "SELECT * FROM tbl_gallery WHERE id='$id'";
      $delta = $this->db->select($del_file_name);
      if ($delta) {
        $get_id_parent = $delta->fetch_assoc();
        $photo = $get_id_parent['photo'];
        $file_path = "uploads/" . $photo;
        if (file_exists($file_path)) {
          unlink($file_path);
        }
        $query = "DELETE FROM tbl_gallery WHERE id = '$id'";
        $result = $this->db->delete($query);
        if (!$result) {
          return "Lỗi thao tác khi xóa ID: $id";
        }
      } else {
        return "Không tìm thấy ảnh để xoá cho ID: $id";
      }
    }
    header('Location: transfer.php?stt=success&url=gallery&id=' . $get_id_parent['id_parent']);
  }


  public function them_gallery($data, $files, $id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $uploaded_images = [];

    for ($i = 0; $i < 5; $i++) {
      if (!empty($files["file$i"]['name']) && $files["file$i"]['error'] == 0) {
        $file_name = $files["file$i"]['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $unique_image = substr(md5(time() . $i), 0, 10) . '.' . $file_ext;
        move_uploaded_file($files["file$i"]['tmp_name'], "uploads/" . $unique_image);
        $uploaded_images[] = $unique_image;

        $hienthi = mysqli_real_escape_string($this->db->link, $data["hienthi$i"]);
        $numb = mysqli_real_escape_string($this->db->link, $data["numb$i"]);

        $gallery_query = "INSERT INTO tbl_gallery (id_parent, photo, numb, hienthi) VALUES ('$id', '$unique_image', '$numb', '$hienthi')";
        $this->db->insert($gallery_query);
      }
    }
    header('Location: transfer.php?stt=success&url=gallery&id=' . $id);
  }

  public function del_gallery($id)
  {
    $del_file_name = "SELECT * FROM tbl_gallery WHERE id='$id'";
    $delta = $this->db->select($del_file_name);
    if ($delta) {
      $get_id_parent = $delta->fetch_assoc();
      $photo = $get_id_parent['photo'];
      $file_path = "uploads/" . $photo;
      if (file_exists($file_path)) {
        unlink($file_path);
      }
      $query = "DELETE FROM tbl_gallery WHERE id = '$id'";
      $result = $this->db->delete($query);
      if ($result) {
        header('Location: transfer.php?stt=success&url=gallery&id=' . $get_id_parent['id_parent']);
      } else {
        return "Lỗi thao tác!";
      }
    } else {
      return "Không tìm thấy ảnh để xoá!";
    }
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
        tbl_danhmuc.namevi AS danhmuc,
        tbl_danhmuc.slugvi AS danhmuc_slugvi,
        tbl_danhmuc_c2.namevi AS danhmuc_c2,
        tbl_danhmuc_c2.slugvi AS danhmuc_c2_slugvi
        FROM tbl_sanpham
        INNER JOIN tbl_danhmuc ON tbl_sanpham.id_list = tbl_danhmuc.id
        LEFT JOIN tbl_danhmuc_c2 ON tbl_sanpham.id_cat = tbl_danhmuc_c2.id
        WHERE tbl_sanpham.id = '$id'";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_sanpham($slug)
  {
    $query = "SELECT * FROM tbl_sanpham WHERE slugvi = '$slug' AND hienthi = 'hienthi' LIMIT 1";
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

  private function handleImageUpload($file_source_path, $original_name, $old_file_path = '')
  {
    $thumb_filename = $this->fn->createFixedThumbnail($file_source_path, 500, 500, [0, 0, 0, 127]);
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

  public function update_sanpham($data, $files, $id)
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

    $sanpham_data = [];
    foreach ($fields as $field) {
      $sanpham_data[$field] = mysqli_real_escape_string($this->db->link, $data[$field]);
    }

    $get_old_file = $this->db->select("SELECT file FROM tbl_sanpham WHERE id='$id'");
    $old_file_name = $get_old_file ? $get_old_file->fetch_assoc()['file'] : '';
    $old_file_path = "uploads/" . $old_file_name;

    $unique_image = '';
    if (!empty($files["file"]["name"])) {
      $file_ext = strtolower(pathinfo($files["file"]["name"], PATHINFO_EXTENSION));
      $raw_name = substr(md5(time()), 0, 10);
      $original_name = $raw_name . '.' . $file_ext;
      $destination = "uploads/" . $original_name;

      if (move_uploaded_file($files["file"]["tmp_name"], $destination)) {
        $unique_image = $this->handleImageUpload($destination, $original_name, $old_file_path);
      }
    } else if (!empty($old_file_name) && file_exists($old_file_path)) {
      $file_ext = strtolower(pathinfo($old_file_name, PATHINFO_EXTENSION));
      $raw_name = substr(md5(time()), 0, 10);
      $original_name = $raw_name . '.' . $file_ext;
      $destination = "uploads/" . $original_name;
      copy($old_file_path, $destination);
      $unique_image = $this->handleImageUpload($destination, $original_name, $old_file_path);
    }

    // Kiểm tra slug bị trùng
    if ($this->fn->isSlugviDuplicated($sanpham_data['slugvi'], 'tbl_sanpham', $id)) {
      return "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
    }

    $update_fields = [];
    foreach ($sanpham_data as $key => $value) {
      $update_fields[] = "$key = '$value'";
    }

    $update_fields[] = "file = '$unique_image'";
    $update_query = "UPDATE tbl_sanpham SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
    $result = $this->db->update($update_query);

    if ($result) {
      header('Location: transfer.php?stt=success&url=product_list');
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function them_sanpham($data, $files)
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

    $sanpham_data = [];
    foreach ($fields as $field) {
      $sanpham_data[$field] = mysqli_real_escape_string($this->db->link, $data[$field]);
    }

    $unique_image = '';
    if (!empty($files["file"]["name"])) {
      $file_ext = strtolower(pathinfo($files["file"]["name"], PATHINFO_EXTENSION));
      $raw_name = substr(md5(time()), 0, 10);
      $original_name = $raw_name . '.' . $file_ext;
      $destination = "uploads/" . $original_name;

      if (move_uploaded_file($files["file"]["tmp_name"], $destination)) {
        $unique_image = $this->handleImageUpload($destination, $original_name);
      }
    }
    // Kiểm tra slug trùng
    if ($this->fn->isSlugviDuplicated($sanpham_data['slugvi'], 'tbl_sanpham', '')) {
      return "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
    }

    $sanpham_data['file'] = $unique_image;
    $field_names = array_keys($sanpham_data);
    $field_values = array_map(function ($value) {
      return "'" . $value . "'";
    }, $sanpham_data);

    $query = "INSERT INTO tbl_sanpham (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
    $result = $this->db->insert($query);

    if ($result) {
      header('Location: transfer.php?stt=success&url=product_list');
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }
}
?>
