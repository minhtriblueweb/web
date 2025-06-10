<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class sanpham
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

  public function update_sanpham($data, $files, $id)
  {
    $fields = ['slugvi', 'namevi', 'id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'banchay', 'numb'];
    foreach ($fields as $field) {
      $$field = mysqli_real_escape_string($this->db->link, $data[$field]);
    }
    $descvi = $data['descvi'];
    $contentvi = $data['contentvi'];
    $file_name = $_FILES["file"]["name"];
    $unique_image = '';
    if (!empty($file_name)) {
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
      move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $unique_image);
      $del_file_query = "SELECT file FROM tbl_sanpham WHERE id='$id'";
      $old_file = $this->db->select($del_file_query);
      if ($old_file && $old_file->num_rows > 0) {
        $rowData = $old_file->fetch_assoc();
        $old_file_path = "uploads/" . $rowData['file'];
        if (file_exists($old_file_path)) {
          unlink($old_file_path);
        }
      }
    }
    $check_slug = "SELECT slugvi FROM tbl_sanpham WHERE slugvi = '$slugvi' AND id != '$id'";
    $result_check_slug = $this->db->select($check_slug);
    if ($result_check_slug && $result_check_slug->num_rows > 0) {
      return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
    }
    $query = "UPDATE tbl_sanpham SET 
                slugvi = '$slugvi',
                namevi = '$namevi',
                descvi = '$descvi',
                contentvi = '$contentvi',
                file = '" . ($unique_image ?: $this->db->select("SELECT file FROM tbl_sanpham WHERE id='$id'")->fetch_assoc()['file']) . "',
                id_list = '$id_list',
                id_cat = '$id_cat',
                regular_price = '$regular_price',
                sale_price = '$sale_price',
                discount = '$discount',
                code = '$code',
                titlevi = '$titlevi',
                keywordsvi = '$keywordsvi',
                descriptionvi = '$descriptionvi',
                hienthi = '$hienthi',
                banchay = '$banchay',
                numb = '$numb' 
                WHERE id = '$id'";
    $result = $this->db->update($query);
    if (!empty($files['files']['name'][0])) {
      foreach ($files['files']['name'] as $key => $file_name) {
        if ($files['files']['error'][$key] == 0) { // Kiểm tra không có lỗi
          $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
          $unique_image = substr(md5(time() . $key), 0, 10) . '.' . $file_ext;
          move_uploaded_file($files['files']['tmp_name'][$key], "uploads/" . $unique_image);
          $gallery_query = "INSERT INTO tbl_gallery (id_parent, photo) VALUES ('$id', '$unique_image')";
          $this->db->insert($gallery_query);
        }
      }
    }
    if ($result) {
      header('Location: transfer.php?stt=success&url=sanpham');
    } else {
      return "Lỗi thao tác!";
    }
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
      header('Location: transfer.php?stt=success&url=sanpham');
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

    $query = "DELETE FROM tbl_sanpham WHERE id = '$id'";
    $result = $this->db->delete($query);
    if ($result) {
      header('Location: transfer.php?stt=success&url=sanpham');
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function get_name_danhmuc($id)
  {
    $id_1 = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT namevi FROM tbl_danhmuc WHERE id = '$id_1' LIMIT 1";
    $result = $this->db->select($query);
    if ($result) {
      $resulted = $result->fetch_assoc();
      $get_name = $resulted['namevi'];
    }
    return $get_name;
  }

  public function get_name_danhmuc_2($id)
  {
    $id_2 = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT namevi FROM tbl_danhmuc_c2 WHERE id = '$id_2' LIMIT 1";
    $result = $this->db->select($query);
    if ($result) {
      $resulted = $result->fetch_assoc();
      $get_name = $resulted['namevi'];
    }
    return $get_name;
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

  public function count_sanpham_cap_1($id_list = '')
  {
    $query = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE id_list = '$id_list' AND hienthi = 'hienthi'";
    $result = $this->db->select($query);

    if ($result) {
      $row = $result->fetch_assoc();
      return $row['total'];
    }
    return 0;
  }

  public function count_all_sanpham()
  {
    $query = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE hienthi = 'hienthi'";
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

  public function count_sanpham_cap_2($id_cat = '')
  {
    $query = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE id_cat = '$id_cat' AND hienthi = 'hienthi'";
    $result = $this->db->select($query);

    if ($result) {
      $row = $result->fetch_assoc();
      return $row['total'];
    }
    return 0;
  }

  public function them_sanpham($data, $files)
  {
    // 1️⃣ Danh sách các field
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

    // 2️⃣ Lấy giá trị các field (escape)
    $sanpham_data = [];
    foreach ($fields as $field) {
      $sanpham_data[$field] = mysqli_real_escape_string($this->db->link, $data[$field]);
    }

    // 3️⃣ Xử lý hình ảnh
    $unique_image = '';
    if (!empty($files["file"]["name"])) {
      $file_ext = strtolower(pathinfo($files["file"]["name"], PATHINFO_EXTENSION));
      $raw_name = substr(md5(time()), 0, 10);
      $original_name = $raw_name . '.' . $file_ext;
      $destination = "uploads/" . $original_name;

      if (move_uploaded_file($files["file"]["tmp_name"], $destination)) {
        $this->fn->resizeImage($destination, $destination, 1600);
        $query = "SELECT * FROM tbl_setting WHERE id = '1' LIMIT 1";
        $result = $this->db->select($query);
        if ($result) {
          $setting = $result->fetch_assoc();
          $position = isset($setting['position']) ? intval($setting['position']) : 5;
          $opacity = isset($setting['opacity']) ? intval($setting['opacity']) : 50;
          $offset_x = isset($setting['offset_x']) ? intval($setting['offset_x']) : 0;
          $offset_y = isset($setting['offset_y']) ? intval($setting['offset_y']) : 0;
        } else {
          $position = 5;
          $opacity = 50;
          $offset_x = 0;
          $offset_y = 0;
        }

        $this->fn->addWatermark($destination, $destination, $position, $opacity, $offset_x, $offset_y);

        // Convert WebP
        $webp_file = $this->fn->convert_webp_from_path($destination, $original_name);
        if ($webp_file) {
          $unique_image = $webp_file;
          unlink($destination); // Xóa file gốc JPG/PNG
        } else {
          $unique_image = $original_name;
        }
      }
    }

    // 4️⃣ Kiểm tra slug trùng
    $slug = $sanpham_data['slugvi'];
    $check_slug = "SELECT COUNT(*) as count FROM tbl_sanpham WHERE slugvi = '$slug' LIMIT 1";
    $result_check_slug = $this->db->select($check_slug);
    if ($result_check_slug && $result_check_slug->fetch_assoc()['count'] > 0) {
      return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
    }

    // 5️⃣ Build câu INSERT
    $sanpham_data['file'] = $unique_image; // Thêm field 'file' vào dữ liệu

    $field_names = array_keys($sanpham_data);
    $field_values = array_map(function ($value) {
      return "'" . $value . "'";
    }, $sanpham_data);

    $query = "INSERT INTO tbl_sanpham (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";

    // 6️⃣ Thực thi
    $result = $this->db->insert($query);
    if ($result) {
      header('Location: transfer.php?stt=success&url=sanpham');
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }
}
?>