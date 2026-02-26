<?php
class adminlogin
{
  private $d;
  private $func;

  public function __construct($d, $func)
  {
    $this->d = $d;
    $this->func = $func;
  }

  public function login($username, $password)
  {
    $username = $this->func->validation($username);
    $password = $this->func->validation($password);

    if (empty($username) || empty($password)) {
      return ['status' => false, 'msg' => vuilongnhaptaikhoan];
    }

    $user = $this->d->rawQueryOne("SELECT * FROM `tbl_user` WHERE username = ? LIMIT 1",[$username]);

    if (!$user) {
      return ['status' => false, 'msg' => 'Tài khoản không tồn tại!'];
    }

    if (!password_verify($password, $user['password'])) {
      return ['status' => false, 'msg' => 'Sai mật khẩu!'];
    }

    Session::set('adminlogin', [
      'active' => true,
      'id'     => $user['id'],
      'name'   => $user['username'],
      'time'   => time()
    ]);

    Session::set('last_activity', time());

    return ['status' => true];
  }

  public function isLogin(): bool
  {
    return Session::get('adminlogin')['active'] ?? false;
  }
}
