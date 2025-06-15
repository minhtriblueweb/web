<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/session.php');
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');

class adminlogin
{
  private $db;
  private $fm;

  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
  }

  public function login($username, $password)
  {
    // Validation đầu vào
    $username = $this->fm->validation($username);
    $password = $this->fm->validation($password);

    $username = mysqli_real_escape_string($this->db->link, $username);
    $password = mysqli_real_escape_string($this->db->link, $password);

    if (empty($username) || empty($password)) {
      return "Không được để trống tài khoản hoặc mật khẩu!";
    }

    // Truy vấn DB
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = $this->db->select($query);

    if ($result) {
      $value = $result->fetch_assoc();
      // Lưu session
      Session::set('adminlogin', true);
      Session::set('adminId', $value['id']);
      Session::set('adminUser', $value['username']);

      return "success"; // Thông báo thành công cho login.php xử lý
    } else {
      return "Tài khoản hoặc mật khẩu không đúng!";
    }
  }
}
