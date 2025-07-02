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

    if (empty($username) || empty($password)) {
      return "Không được để trống tài khoản hoặc mật khẩu!";
    }

    // Truy vấn DB: chỉ kiểm tra username
    $query = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
    $result = $this->db->select($query);

    if ($result && $result->num_rows > 0) {
      $value = $result->fetch_assoc();
      $hashedPassword = $value['password'];

      // So sánh với password người dùng nhập
      if (password_verify($password, $hashedPassword)) {
        Session::set('adminlogin', true);
        Session::set('adminId', $value['id']);
        Session::set('adminUser', $value['username']);
        return "success";
      } else {
        return "Sai mật khẩu!";
      }
    } else {
      return "Tài khoản không tồn tại!";
    }
  }
}
