<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Ghi truy cập mới vào bảng tbl_counter nếu là phiên chưa đếm
if (empty($_SESSION['counted'])) {
  $_SESSION['counted'] = true;

  $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
  $time = time();

  if (isset($db)) {
    $db->rawQuery("INSERT INTO tbl_counter (tm, ip) VALUES (?, ?)", [$time, $ip]);
  }
}

// Mốc thời gian để thống kê
$now = time();
$todayStart = strtotime(date('Y-m-d 00:00:00'));
$mondayStart = strtotime('monday this week');
$onlineLimit = $now - 300; // 5 phút gần nhất

// Truy vấn dữ liệu từ bảng tbl_counter
$total  = $db->rawQueryOne("SELECT COUNT(*) AS total FROM tbl_counter")['total'] ?? 0;
$today  = $db->rawQueryOne("SELECT COUNT(*) AS total FROM tbl_counter WHERE tm >= ?", [$todayStart])['total'] ?? 0;
$week   = $db->rawQueryOne("SELECT COUNT(*) AS total FROM tbl_counter WHERE tm >= ?", [$mondayStart])['total'] ?? 0;
$online = $db->rawQueryOne("SELECT COUNT(*) AS total FROM tbl_counter WHERE tm >= ?", [$onlineLimit])['total'] ?? 0;

// Hiển thị giao diện footer thống kê
echo '<div class="footer-statistic d-none d-sm-block">';
echo '<span>Đang online: ' . $online . '</span>';
echo '<span>Ngày: ' . $today . '</span>';
echo '<span>Tuần: ' . $week . '</span>';
echo '<span>Tổng truy cập: ' . $total . '</span>';
echo '</div>';
