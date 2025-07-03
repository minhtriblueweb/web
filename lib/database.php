<?php
$filepath = realpath(dirname(__FILE__));
require_once($filepath . '/../config/config.php');

class Database
{
  private $host;
  private $user;
  private $pass;
  private $dbname;
  private $port;
  private $charset;
  public $prefix;

  public $link;
  public $error;

  public function __construct()
  {
    global $config;
    $dbConfig = $config['database'];

    $this->host    = $dbConfig['host'];
    $this->user    = $dbConfig['username'];
    $this->pass    = $dbConfig['password'];
    $this->dbname  = $dbConfig['dbname'];
    $this->port    = $dbConfig['port'] ?? 3306;
    $this->charset = $dbConfig['charset'] ?? 'utf8mb4';
    $this->prefix  = $dbConfig['prefix'] ?? '';

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
    $insert_row = $this->link->query($query);
    if ($insert_row) {
      return $this->link->insert_id;
    } else {
      error_log("Insert Error: " . $this->link->error . " | Query: $query");
      return false;
    }
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
  public function fetchOne($sql, $params = [])
  {
    $stmt = $this->link->prepare($sql);
    if (!$stmt) {
      throw new Exception("Prepare failed: " . $this->link->error);
    }

    if (!empty($params)) {
      $types = str_repeat('s', count($params)); // Tự động assume là chuỗi
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
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

  public function getInsertId()
  {
    return $this->link->insert_id;
  }
  public function rawQueryArray($query, $params = [])
  {
    $result = $this->rawQuery($query, $params);
    if (!$result) return [];

    $rows = [];
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    return $rows;
  }

  public function rawQueryValue(string $sql, array $params = [])
  {
    $stmt = $this->link->prepare($sql);
    if (!$stmt) return null;

    if (!empty($params)) {
      $types = str_repeat('s', count($params));
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $stmt->store_result();

    $value = null;
    if ($stmt->num_rows > 0) {
      $stmt->bind_result($value);
      $stmt->fetch();
    }

    $stmt->close();
    return $value;
  }
}
