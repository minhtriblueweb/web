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
  public function get_static(string $type)
  {
    return $this->db->rawQueryOne("SELECT * FROM tbl_static WHERE type = ? LIMIT 1", [$type]);
  }
  public function update_static(array $data, string $type, int $id)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $table = 'tbl_static';

    $id = (int)$id;
    $type = trim($type);
    $slug = trim($data['slug'] ?? '');

    // Danh sách field cần update
    $fields = [];
    $binds = [];

    if (!empty($slug)) {
      $fields[] = 'slug = ?';
      $binds[] = $slug;
    }

    foreach ($langs as $lang) {
      foreach (['name', 'desc', 'content'] as $f) {
        $key = $f . $lang;
        if (isset($data[$key])) {
          $fields[] = "`$key` = ?";
          $binds[] = trim($data[$key]);
        }
      }
    }

    $fields[] = "update_at = NOW()";

    if (!empty($fields)) {
      $sql = "UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ? AND type = ?";
      $binds[] = $id;
      $binds[] = $type;
      $result = $this->db->execute($sql, $binds);
    } else {
      $result = false;
    }

    $msg = $result ? "Cập nhật dữ liệu thành công" : "Không có dữ liệu để cập nhật hoặc lỗi xảy ra";
    $redirect = $this->fn->getRedirectPath([
      'table' => $table,
      'type' => $type
    ]);

    $this->fn->transfer($msg, $redirect, $result);
  }
}

?>
