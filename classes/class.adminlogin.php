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
    $username = $this->fm->validation($username);
    $password = $this->fm->validation($password);
    if (empty($username) || empty($password)) {
      return "Không được để trống tài khoản hoặc mật khẩu!";
    }
    $user = $this->db->rawQueryOne("SELECT * FROM admin WHERE username = ? LIMIT 1", [$username]);
    if ($user) {
      $hashedPassword = $user['password'];
      if (password_verify($password, $hashedPassword)) {
        Session::set('adminlogin', true);
        Session::set('adminId', $user['id']);
        Session::set('adminUser', $user['username']);
        return "success";
      } else {
        return "Sai mật khẩu!";
      }
    } else {
      return "Tài khoản không tồn tại!";
    }
  }
}
