<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class seo
{
  private $db;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }
  public function get_seo(string $type, int $id_parent): array
  {
    $table = 'tbl_seo';
    $row = $this->db->fetchOne("SELECT * FROM $table WHERE id_parent = '$id_parent' AND type = '$type'");
    return $row ?: [];
  }
  public function save_seo(string $type, int $id_parent, array $data, array $langs): void
  {
    $table = 'tbl_seo';
    $fields_multi = ['title', 'keywords', 'description', 'schema'];

    $data_sql = [
      'id_parent' => $id_parent,
      'type' => $type
    ];

    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_sql[$key] = !empty($data[$key]) ? mysqli_real_escape_string($this->db->link, $data[$key]) : '';
      }
    }

    $check = $this->db->select("SELECT id FROM $table WHERE id_parent = '$id_parent' AND type = '$type'");
    if ($check && $check->num_rows > 0) {
      $row = $check->fetch_assoc();
      $updates = [];
      foreach ($data_sql as $k => $v) {
        $updates[] = "`$k` = '$v'";
      }
      $sql = "UPDATE $table SET " . implode(', ', $updates) . " WHERE id = '" . $row['id'] . "'";
      $this->db->update($sql);
    } else {
      $fields = implode(',', array_keys($data_sql));
      $values = implode(',', array_map(fn($v) => "'$v'", $data_sql));
      $sql = "INSERT INTO $table ($fields) VALUES ($values)";
      $this->db->insert($sql);
    }
  }
}

?>
