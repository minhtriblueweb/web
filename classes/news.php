<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class news
{
  private $db;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new functions();
  }

  public function get_news_by_slug($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc() : false;
  }

  public function get_baiviet_by_slug_and_type($slug, $type)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $type = mysqli_real_escape_string($this->db->link, $type);
    $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug' AND type = '$type' LIMIT 1";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc() : false;
  }

  public function show_news_by_type($type, $hienthi = '', $noibat = '')
  {
    $conditions = ["type = '$type'"];

    if ($hienthi !== '') {
      $conditions[] = "hienthi = '$hienthi'";
    }

    if ($noibat !== '') {
      $conditions[] = "noibat = '$noibat'";
    }

    $query = "SELECT * FROM tbl_news";
    if (!empty($conditions)) {
      $query .= " WHERE " . implode(" AND ", $conditions);
    }
    $query .= " ORDER BY numb,id DESC";

    return $this->db->select($query);
  }


  public function show_chinhsach_noibat($hienthi = '', $noibat = '')
  {
    $conditions = ["type = 'chinh-sach'"];

    if ($hienthi !== '') {
      $conditions[] = "hienthi = '$hienthi'";
    }

    if ($noibat !== '') {
      $conditions[] = "noibat = '$noibat'";
    }

    $query = "SELECT * FROM tbl_news";

    if (!empty($conditions)) {
      $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY numb ASC";
    return $this->db->select($query);
  }


  public function total_pages_tintuc_lienquan($id, $id_cat, $limit)
  {
    $id_cat = mysqli_real_escape_string($this->db->link, $id_cat);
    $query = "SELECT COUNT(*) as total FROM tbl_news WHERE id_cat = '$id_cat' AND id != '$id'";
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


  public function relatedNews($id, $type)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $type = mysqli_real_escape_string($this->db->link, $type);
    $query = "SELECT * FROM tbl_news WHERE type = '$type' AND id != '$id' AND hienthi='hienthi' ORDER BY numb ASC";
    $result = $this->db->select($query);
    return $result;
  }


  public function update_views_by_slug($slug)
  {
    $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug'";
    $result = $this->db->select($query);
    if ($result && $result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $new_views = $product['views'] + 1;
      $update_query = "UPDATE tbl_news SET views = '$new_views' WHERE slugvi = '$slug'";
      $this->db->update($update_query);
      return $product;
    }
    return false;
  }


  public function get_danhmuc_by_tintuc($id)
  {
    $query = "SELECT tbl_news.*,
        tbl_danhmuc.namevi AS danhmuc,
        tbl_danhmuc.slugvi AS danhmuc_slugvi,
        tbl_danhmuc_c2.namevi AS danhmuc_c2,
        tbl_danhmuc_c2.slugvi AS danhmuc_c2_slugvi
        FROM tbl_news
        INNER JOIN tbl_danhmuc ON tbl_news.id_list = tbl_danhmuc.id
        LEFT JOIN tbl_danhmuc_c2 ON tbl_news.id_cat = tbl_danhmuc_c2.id
        WHERE tbl_news.id = '$id'";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_news($slug)
  {
    $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug' AND hienthi = 'hienthi' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_chinhsach($slug)
  {
    $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug' AND hienthi = 'hienthi' AND type = 'chinh-sach' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function get_id_news($id)
  {
    $id = mysqli_real_escape_string($this->db->link, $id);
    $query = "SELECT * FROM tbl_news WHERE id = '$id' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }

  public function delete_multiple_news($listid, $type)
  {
    if (empty($listid)) {
      return "Không có bản ghi nào được chọn.";
    }
    $type = mysqli_real_escape_string($this->db->link, $type);
    $del_file_query = "SELECT file FROM tbl_news WHERE id IN ($listid)";
    $old_files = $this->db->select($del_file_query);
    if ($old_files) {
      while ($rowData = $old_files->fetch_assoc()) {
        $old_file_path = "uploads/" . $rowData['file'];
        if (file_exists($old_file_path)) {
          unlink($old_file_path);
        }
      }
    }
    $query = "DELETE FROM tbl_news WHERE id IN ($listid)";
    $result = $this->db->delete($query);
    if ($result) {
      header("Location: transfer.php?stt=success&url=$type");
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function del_news($id, $type)
  {
    $id = (int)$id;
    $type = mysqli_real_escape_string($this->db->link, $type);
    $del_file_name = "SELECT file FROM tbl_news WHERE id='$id' AND type='$type'";
    $delta = $this->db->select($del_file_name);
    if ($delta && $delta->num_rows > 0) {
      $rowData = $delta->fetch_assoc();
      $file_name = $rowData['file'];
      if (!empty($file_name)) {
        $file_path = "uploads/$file_name";
        if (file_exists($file_path)) {
          unlink($file_path);
        }
      }
    }
    $query = "DELETE FROM tbl_news WHERE id = '$id' AND type='$type'";
    $result = $this->db->delete($query);
    if ($result) {
      header("Location: transfer.php?stt=success&url=$type");
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }

  public function show_news($records_per_page, $current_page, $hienthi = '', $type = '')
  {
    $type = mysqli_real_escape_string($this->db->link, $type);
    $hienthi = mysqli_real_escape_string($this->db->link, $hienthi);
    $records_per_page = (int)$records_per_page;
    $current_page = (int)$current_page;
    $offset = ($current_page - 1) * $records_per_page;

    if (!empty($hienthi)) {
      $query = "SELECT * FROM tbl_news WHERE hienthi = '$hienthi' AND type = '$type' ORDER BY numb ASC";
    } else {
      $query = "SELECT * FROM tbl_news WHERE type = '$type' ORDER BY numb, id DESC LIMIT $records_per_page OFFSET $offset";
    }

    $result = $this->db->select($query);
    return $result;
  }

  public function save_news($data, $files, $id = null)
  {
    $fields = [
      'slugvi',
      'namevi',
      'descvi',
      'contentvi',
      'titlevi',
      'keywordsvi',
      'descriptionvi',
      'hienthi',
      'noibat',
      'numb',
      'type'
    ];
    $table = 'tbl_news';
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
    $thumb_filename = $this->fn->Upload($files, '391x215x1', [255, 255, 255, 0], $old_file_path, $watermark = false, $convert_webp = true);
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
      $msg = $result ? "Cập nhật dữ liệu thành công" : "Cập nhật dữ liệu thất bại";
    } else {
      $field_names = array_keys($data_escaped);
      $field_values = array_map(fn($v) => "'" . $v . "'", $data_escaped);
      if (!empty($thumb_filename)) {
        $field_names[] = 'file';
        $field_values[] = "'" . $thumb_filename . "'";
      }
      $query = "INSERT INTO $table (" . implode(", ", $field_names) . ") VALUES (" . implode(", ", $field_values) . ")";
      $result = $this->db->insert($query);
      $msg = $result ? "Thêm dữ liệu thành công" : "Thêm dữ liệu thất bại";
    }
    $type_safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $data_escaped['type']);
    $this->fn->transfer($msg, "index.php?page={$type_safe}_list", $result);
  }
}
?>
