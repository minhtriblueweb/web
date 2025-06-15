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
}
