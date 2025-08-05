<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Khởi tạo biến
$now = time();
$sessionId = session_id();
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

// ==== Ghi thống kê truy cập tổng quát vào bảng tbl_counter ====
if (empty($_SESSION['counted'])) {
  $_SESSION['counted'] = true;

  if (isset($db)) {
    $db->rawQuery("INSERT INTO tbl_counter (tm, ip) VALUES (?, ?)", [$now, $ip]);
  }
}

// ==== Cập nhật hoạt động online vào bảng tbl_user_online ====
if (isset($db)) {
  // Ghi hoặc cập nhật session đang hoạt động
  $db->rawQuery("REPLACE INTO tbl_user_online (session, last_activity, ip) VALUES (?, ?, ?)", [$sessionId, $now, $ip]);

  // Xoá các session đã hết hạn (quá 5 phút không hoạt động)
  $db->rawQuery("DELETE FROM tbl_user_online WHERE last_activity < ?", [$now - 300]);
}

// ==== Tính toán thống kê truy cập ====
$todayStart  = strtotime(date('Y-m-d 00:00:00'));
$mondayStart = strtotime('monday this week');

$total  = $db->rawQueryOne("SELECT COUNT(*) AS total FROM tbl_counter")['total'] ?? 0;
$today  = $db->rawQueryOne("SELECT COUNT(*) AS total FROM tbl_counter WHERE tm >= ?", [$todayStart])['total'] ?? 0;
$week   = $db->rawQueryOne("SELECT COUNT(*) AS total FROM tbl_counter WHERE tm >= ?", [$mondayStart])['total'] ?? 0;
$online = $db->rawQueryOne("SELECT COUNT(*) AS total FROM tbl_user_online")['total'] ?? 0;

// ==== Hiển thị giao diện thống kê ====
echo '<div class="footer-statistic d-none d-sm-block">';
echo '<span>Đang online: ' . $online . '</span>';
echo '<span>Ngày: ' . $today . '</span>';
echo '<span>Tuần: ' . $week . '</span>';
echo '<span>Tổng truy cập: ' . $total . '</span>';
echo '</div>';
