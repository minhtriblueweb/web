<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
class Statistic
{
  private $db;
  public function __construct()
  {
    $this->db = new Database();
  }
  public function getOnline()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $now = time();
    $sessionId = session_id();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    if (empty($_SESSION['counted'])) {
      $_SESSION['counted'] = true;
      $this->db->rawQuery("INSERT INTO `tbl_counter` (tm, ip) VALUES (?, ?)",[$now, $ip]);
    }
    $this->db->rawQuery("REPLACE INTO tbl_user_online (session, last_activity, ip) VALUES (?, ?, ?)",[$sessionId, $now, $ip]);
    $this->db->rawQuery("DELETE FROM tbl_user_online WHERE last_activity < ?",[$now - 300]);
    $todayStart  = strtotime(date('Y-m-d 00:00:00'));
    $mondayStart = strtotime('monday this week');
    $monthStart = strtotime(date('Y-m-01 00:00:00'));
    return [
      'online' => $this->db->rawQueryOne("SELECT COUNT(*) AS total FROM `tbl_user_online`")['total'] ?? 0,
      'today'  => $this->db->rawQueryOne("SELECT COUNT(*) AS total FROM `tbl_counter` WHERE tm >= ?", [$todayStart])['total'] ?? 0,
      'week'   => $this->db->rawQueryOne("SELECT COUNT(*) AS total FROM `tbl_counter` WHERE tm >= ?", [$mondayStart])['total'] ?? 0,
      'total'  => $this->db->rawQueryOne("SELECT COUNT(*) AS total FROM `tbl_counter`")['total'] ?? 0,
      'month'  => $this->db->rawQueryOne("SELECT COUNT(*) AS total FROM `tbl_counter` WHERE tm >= ?", [$monthStart])['total'] ?? 0,
    ];
  }
}
