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
  private $seo;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
    $this->seo = new seo();
  }

  public function get_news_by_slug($slug)
  {
    $slug = mysqli_real_escape_string($this->db->link, $slug);
    $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug' LIMIT 1";
    $result = $this->db->select($query);
    return $result ? $result->fetch_assoc() : false;
  }

  public function update_views_by_slug($slug)
  {
    $query = "SELECT * FROM tbl_news WHERE slug = '$slug'";
    $result = $this->db->select($query);
    if ($result && $result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $new_ews = $product['ews'] + 1;
      $update_query = "UPDATE tbl_news SET ews = '$new_ews' WHERE slug = '$slug'";
      $this->db->update($update_query);
      return $product;
    }
    return false;
  }
  public function save_news($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $fields_multi = ['slug', 'name', 'desc', 'content'];
    $fields_common = ['type', 'numb'];
    $table = 'tbl_news';
    $data_prepared = [];

    // Xử lý các trường đa ngôn ngữ
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_prepared[$key] = $data[$key] ?? "";
      }
    }

    // Xử lý các trường chung
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? "";
    }

    // Trạng thái
    $status_flags = ['hienthi', 'noibat'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) $status_values[] = $flag;
    }
    $data_prepared['status'] = implode(',', $status_values);

    // Kiểm tra slug
    foreach ($langs as $lang) {
      $slug_key = 'slug' . $lang;
      $slug_error = $this->fn->isSlugDuplicated($data_prepared[$slug_key], $table, $id ?? '');
      if ($slug_error) return $slug_error;
    }

    // Xử lý ảnh
    $thumb_filename = '';
    $old_file_path = '';
    if (!empty($id)) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
      if ($old && !empty($old['file'])) {
        $old_file_path = "uploads/" . $old['file'];
      }
    }

    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $zc = (int)($data['thumb_zc'] ?? 0);
    $thumb_size = $width . 'x' . $height . 'x' . $zc;

    $thumb_filename = $this->fn->Upload([
      'file' => $files['file'],
      'custom_name' => $data_prepared['namevi'],
      'thumb' => $thumb_size,
      'old_file_path' => $old_file_path,
      'watermark' => false,
      'convert_webp' => true
    ]);

    if (!empty($id)) {
      // UPDATE
      $fields = [];
      $params = [];
      foreach ($data_prepared as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }
      if (!empty($thumb_filename)) {
        $fields[] = "`file` = ?";
        $params[] = $thumb_filename;
      }
      $params[] = (int)$id;
      $sql = "UPDATE $table SET " . implode(", ", $fields) . " WHERE id = ?";
      $result = $this->db->execute($sql, $params);
      if ($result) {
        $this->seo->save_seo($data_prepared['type'], (int)$id, $data, $langs);
      }
      $msg = $result ? "Cập nhật dữ liệu thành công" : "Cập nhật dữ liệu thất bại";
    } else {
      // INSERT
      $columns = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_prepared);
      if (!empty($thumb_filename)) {
        $columns[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }
      $sql = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";
      $inserted = $this->db->execute($sql, $params);
      $insert_id = $inserted ? $this->db->getInsertId() : 0;
      if ($insert_id) {
        $this->seo->save_seo($data_prepared['type'], $insert_id, $data, $langs);
      }
      $msg = $inserted ? "Thêm dữ liệu thành công" : "Thêm dữ liệu thất bại";
    }

    // Chuyển hướng
    $type_safe = preg_replace('/[^a-zA-Z0-9_-]/', '', $data_prepared['type']);
    $redirectPath = $this->fn->getRedirectPath([
      'table' => $table,
      'type' => $type_safe
    ]);
    $this->fn->transfer($msg, $redirectPath, !empty($id) ? $result : $inserted);
  }
}
?>
