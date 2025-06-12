<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');

$counter_file = __DIR__ . '/counter.json';

// Nếu file chưa tồn tại thì tạo mới
if (!file_exists($counter_file)) {
  $data = [
    'total' => 0,
    'today' => 0,
    'week' => 0,
    'online' => [],
    'last_date' => date('Y-m-d'),
    'last_week' => date('o-W')
  ];
} else {
  $data = json_decode(file_get_contents($counter_file), true);
}

// Xử lý ngày mới
$today = date('Y-m-d');
if ($data['last_date'] != $today) {
  $data['today'] = 0;
  $data['last_date'] = $today;
}

// Xử lý tuần mới
$current_week = date('o-W');
if ($data['last_week'] != $current_week) {
  $data['week'] = 0;
  $data['last_week'] = $current_week;
}

// Đếm người online (theo session + time)
$now = time();
$timeout = 300; // 5 phút
$session_id = session_id();

$data['online'][$session_id] = $now;

// Xoá session hết hạn
foreach ($data['online'] as $sid => $timestamp) {
  if ($now - $timestamp > $timeout) {
    unset($data['online'][$sid]);
  }
}

// Tăng đếm nếu là phiên mới
if (!isset($_SESSION['counted'])) {
  $data['total']++;
  $data['today']++;
  $data['week']++;
  $_SESSION['counted'] = true;
}

// Lưu lại file
file_put_contents($counter_file, json_encode($data));

// Render HTML
echo '<div class="footer-statistic d-none d-sm-block">';
echo '<span>Đang online: ' . count($data['online']) . '</span>';
echo '<span>Ngày: ' . $data['today'] . '</span>';
echo '<span>Tuần: ' . $data['week'] . '</span>';
echo '<span>Tổng truy cập: ' . $data['total'] . '</span>';
echo '</div>';
