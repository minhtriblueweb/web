<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Nếu là phiên mới thì ghi log truy cập
if (empty($_SESSION['counted'])) {
  $_SESSION['counted'] = true;

  $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
  $time = time();

  // Lưu vào database
  if (isset($db)) {
    $db->rawQuery("INSERT INTO tbl_counter (tm, ip) VALUES (?, ?)", [$time, $ip]);
  }
}

// Tính mốc thời gian
$now = time();
$todayStart = strtotime(date('Y-m-d 00:00:00'));
$mondayStart = strtotime('monday this week'); // đầu tuần (thứ 2)
$onlineLimit = $now - 300; // trong 5 phút gần nhất

// Lấy số liệu thống kê từ database
$total = $db->rawQueryOne("SELECT COUNT(*) as total FROM tbl_counter")['total'] ?? 0;
$today = $db->rawQueryOne("SELECT COUNT(*) as total FROM tbl_counter WHERE tm >= ?", [$todayStart])['total'] ?? 0;
$week = $db->rawQueryOne("SELECT COUNT(*) as total FROM tbl_counter WHERE tm >= ?", [$mondayStart])['total'] ?? 0;
$online = $db->rawQueryOne("SELECT COUNT(*) as total FROM tbl_counter WHERE tm >= ?", [$onlineLimit])['total'] ?? 0;

// Render HTML
echo '<div class="footer-statistic d-none d-sm-block">';
echo '<span>Đang online: ' . $online . '</span>';
echo '<span>Ngày: ' . $today . '</span>';
echo '<span>Tuần: ' . $week . '</span>';
echo '<span>Tổng truy cập: ' . $total . '</span>';
echo '</div>';
