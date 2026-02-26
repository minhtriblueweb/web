<?php

class Session
{
  private static $timeout = 1800;

  public static function init()
  {
    if (session_status() === PHP_SESSION_NONE) {
      ini_set('session.gc_maxlifetime', self::$timeout);
      ini_set('session.cookie_lifetime', 0);
      session_start();
    }
  }

  public static function set($key, $val)
  {
    $_SESSION[$key] = $val;
  }

  public static function get($key)
  {
    return $_SESSION[$key] ?? null;
  }

  /* ===== CHECK LOGIN (ADMIN) ===== */
  public static function checkSession()
  {
    self::init();

    $admin = self::get('adminlogin');
    $lastActivity = self::get('last_activity');

    if (
      empty($admin) ||
      empty($admin['active']) ||
      empty($lastActivity) ||
      (time() - $lastActivity > self::$timeout)
    ) {
      self::destroy();
      header("Location: index.php?com=user&act=login");
      exit();
    }

    self::set('last_activity', time());
  }

  public static function checkLogin()
  {
    self::init();
    $admin = self::get('adminlogin');

    if (!empty($admin['active'])) {
      header("Location: index.php");
      exit();
    }
  }

  public static function destroy()
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

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
