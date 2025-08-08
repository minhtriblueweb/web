<?php

/**
 * Session Class
 **/
class Session
{
  // Thời gian timeout (tính bằng giây) - mặc định 30 phút
  private static $timeout = 1800;

  public static function init()
  {
    if (session_status() === PHP_SESSION_NONE) {
      // Chỉ cấu hình ini_set nếu session chưa bắt đầu
      ini_set('session.gc_maxlifetime', self::$timeout);
      ini_set('session.cookie_lifetime', 0); // 0 = cookie hết khi đóng trình duyệt
      session_start();
    }
  }

  public static function set($key, $val)
  {
    $_SESSION[$key] = $val;
  }

  public static function get($key)
  {
    return $_SESSION[$key] ?? false;
  }

  public static function checkSession()
  {
    self::init();
    $last_activity = self::get("last_activity");
    if (self::get("adminlogin") == false || !$last_activity || (time() - $last_activity > self::$timeout)) {
      self::destroy();
      header("Location: index.php?page=user&act=login");
      exit();
    }
    self::set("last_activity", time());
  }

  public static function checkLogin()
  {
    self::init();
    if (self::get("adminlogin") == true) {
      header("Location:index.php");
      exit();
    }
  }

  public static function destroy()
  {
    if (session_id() == '') session_start();
    session_unset();
    session_destroy();
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }
  }
}
