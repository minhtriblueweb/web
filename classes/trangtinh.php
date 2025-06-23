<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class trangtinh
{
  private $db;
  private $fn;
  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }
  public function get_static($type)
  {
    $type = mysqli_real_escape_string($this->db->link, $type);
    $query = "SELECT * FROM tbl_static WHERE type='$type' LIMIT 1";
    $result = $this->db->select($query);
    return $result;
  }
  public function update_static($data, $type, $id)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $table = 'tbl_static';

    $id = (int)$id;
    $type = mysqli_real_escape_string($this->db->link, $type);
    $slug = mysqli_real_escape_string($this->db->link, $data['slug'] ?? '');

    $update_fields = [];

    if (!empty($slug)) {
      $update_fields[] = "`slug` = '$slug'";
    }

    foreach ($langs as $lang) {
      foreach (['name', 'desc', 'content', 'title', 'keywords', 'description'] as $field) {
        $key = $field . $lang;
        if (isset($data[$key])) {
          $value = mysqli_real_escape_string($this->db->link, $data[$key]);
          $update_fields[] = "`$key` = '$value'";
        }
      }
    }
    $update_fields[] = "`update_at` = NOW()";
    if (!empty($update_fields)) {
      $query = "UPDATE `$table` SET " . implode(', ', $update_fields) . " WHERE `id` = '$id' AND `type` = '$type'";
      $result = $this->db->update($query);
    } else {
      $result = false;
    }
    $msg = $result ? "Cập nhật dữ liệu thành công" : "Không có dữ liệu để cập nhật hoặc lỗi xảy ra";
    $redirectPath = $this->fn->getRedirectPath([
      'table' => $table,
      'type' => $type
    ]);
    $this->fn->transfer($msg, $redirectPath, $result);
  }
}

?>
