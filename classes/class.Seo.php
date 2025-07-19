<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class Seo
{
  private $db;
  private $fn;
  private $data;
  private $prefix;
  private $seo = [];
  private $d;
  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }
  public function get_seo(int $id_parent, string $type = '', string $lang = 'vi', string $act = ''): array
  {
    global $default_seo;

    $sql = "SELECT title{$lang}, keywords{$lang}, description{$lang} FROM tbl_seo WHERE `id_parent` = ?";
    $params = [$id_parent];

    if ($type) {
      $sql .= " AND `type` = ?";
      $params[] = $type;
    }

    if ($act) {
      $sql .= " AND `act` = ?";
      $params[] = $act;
    }
    $row = $this->db->rawQueryOne($sql, $params);
    if (!$row) {
      $this->set($default_seo);
      return $default_seo;
    }
    $data = array_merge($default_seo, [
      'h1'          => $row["title{$lang}"] ?? '',
      'title'       => $row["title{$lang}"] ?? '',
      'keywords'    => $row["keywords{$lang}"] ?? '',
      'description' => $row["description{$lang}"] ?? ''
    ]);
    $this->set($data);
    return array_merge($row, $data);
  }

  public function get_seopage(array $row, string $lang = 'vi'): array
  {
    global $default_seo;
    $title = $row["title{$lang}"] ?? '';
    $data = array_merge($default_seo, [
      'h1'    => $title,
      'title' => $title,
      'keywords' => $row["keywords{$lang}"] ?? '',
      'description' => $row["description{$lang}"] ?? '',
      'url'   => BASE . ($row["slug{$lang}"] ?? ''),
      'image' => !empty($row['file']) ? BASE_ADMIN . UPLOADS . $row['file'] : ''
    ]);
    $this->set($data);
    return $data;
  }
  public function set($key = '', $value = '')
  {
    if (is_array($key)) {
      foreach ($key as $k => $v) {
        $this->set($k, $v);
      }
    } elseif ($key !== '') {
      $this->data[$key] = $value;
    }
  }
  public function get($key)
  {
    return (!empty($this->data[$key])) ? $this->data[$key] : '';
  }
}

?>
