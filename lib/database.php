<?php
$filepath = realpath(dirname(__FILE__));
require_once($filepath . '/../config/config.php');

class Database
{
  private $host;
  private $user;
  private $pass;
  private $dbname;

  public $link;
  public $error;

  public function __construct()
  {
    global $config;
    $this->host = $config['db_host'];
    $this->user = $config['db_user'];
    $this->pass = $config['db_pass'];
    $this->dbname = $config['db_name'];

    $this->connectDB();
  }

  private function connectDB()
  {
    $this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
    mysqli_set_charset($this->link, 'UTF8');
    if (!$this->link) {
      $this->error = "Connection failed: " . $this->link->connect_error;
      return false;
    }
  }

  public function select($query)
  {
    $result = $this->link->query($query) or die($this->link->error . __LINE__);
    return ($result->num_rows > 0) ? $result : false;
  }

  public function insert($query)
  {
    $insert_row = $this->link->query($query) or die($this->link->error . __LINE__);
    return $insert_row ?: false;
  }

  public function update($query)
  {
    $update_row = $this->link->query($query) or die($this->link->error . __LINE__);
    return $update_row ?: false;
  }

  public function delete($query)
  {
    $delete_row = $this->link->query($query) or die($this->link->error . __LINE__);
    return $delete_row ?: false;
  }

  // ==========================
  // Các hàm mở rộng sử dụng prepare
  // ==========================

  // Hàm thực thi query dạng prepare (nhiều dòng)
  public function rawQuery($query, $params = [])
  {
    $stmt = $this->link->prepare($query);
    if (!$stmt) return false;

    if (!empty($params)) {
      $types = str_repeat('s', count($params));
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return ($result && $result->num_rows > 0) ? $result : false;
  }

  // Hàm lấy 1 dòng duy nhất
  public function rawQueryOne($query, $params = [])
  {
    $result = $this->rawQuery($query, $params);
    return ($result) ? $result->fetch_assoc() : null;
  }

  // Hàm lấy toàn bộ mảng (dạng array)
  public function fetchAll($query)
  {
    $res = $this->select($query);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  }

  // Hàm lấy 1 dòng (dạng array)
  public function fetchOne($query)
  {
    $res = $this->select($query);
    return $res ? $res->fetch_assoc() : null;
  }

  // Dành cho insert/update/delete dùng prepare
  public function execute($query, $params = [])
  {
    $stmt = $this->link->prepare($query);
    if (!$stmt) return false;

    if (!empty($params)) {
      $types = str_repeat('s', count($params));
      $stmt->bind_param($types, ...$params);
    }

    return $stmt->execute();
  }
}
