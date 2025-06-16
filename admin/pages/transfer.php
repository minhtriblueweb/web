<?php
define('IS_TRANSFER', true);
$transferData = $_SESSION['transfer_data'] ?? [
  'msg' => 'Không có thông báo',
  'page' => 'dashboard',
  'numb' => true
];
unset($_SESSION['transfer_data']);

$showtext = $transferData['msg'];
$page_transfer = $transferData['page'];
$numb = $transferData['numb'];

include __DIR__ . '/../templates/transfer.php';
