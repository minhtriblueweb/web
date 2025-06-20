<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class trangtinh
{
  private $db;
  private $fm;

  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
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
    $fields = ['slug', 'name', 'desc', 'content', 'title', 'keywords', 'description'];
    foreach ($fields as $field) {
      $$field = mysqli_real_escape_string($this->db->link, $data[$field] ?? '');
    }
    $id = mysqli_real_escape_string($this->db->link, $id);
    $type = mysqli_real_escape_string($this->db->link, $type);
    $query = "UPDATE tbl_static SET
                slug = '$slug',
                name = '$name',
                desc = '$desc',
                content = '$content',
                title = '$title',
                keywords = '$keywords',
                description = '$description'
                WHERE type= '$type' AND id = '$id'";
    $result = $this->db->update($query);
    if ($result) {
      header("Location: transfer.php?stt=success&url=$type");
      exit();
    } else {
      return "Lỗi thao tác!";
    }
  }
}

?>
