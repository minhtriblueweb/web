<?php
session_start();
require_once __DIR__ . '/../init.php';
$db = new Database();

$table = $_POST['table'] ?? '';
$id = $_POST['id'] ?? 0;
$attr = $_POST['attr'] ?? '';
$checked = isset($_POST['checked']) ? (int)$_POST['checked'] : 0;
if (!$table || !$id || !$attr) {
  echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
  exit;
}
$row = $db->select("SELECT status FROM `$table` WHERE id = '$id'");
if (!$row || $row->num_rows == 0) {
  echo json_encode(['success' => false, 'message' => 'Không tìm thấy dữ liệu']);
  exit;
}
$old_status = $row->fetch_assoc()['status'];
$status_array = array_filter(array_map('trim', explode(',', $old_status)));
if ($checked && !in_array($attr, $status_array)) {
  $status_array[] = $attr;
} elseif (!$checked && in_array($attr, $status_array)) {
  $status_array = array_diff($status_array, [$attr]);
}
$new_status = implode(',', $status_array);
$update = $db->update("UPDATE `$table` SET `status` = '$new_status' WHERE id = '$id'");
echo json_encode(['success' => $update ? true : false]);
exit;
