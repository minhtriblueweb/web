<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit('Forbidden');
}
require_once __DIR__ . '/../init.php';
$db = new Database();
$table = $_POST['table'] ?? '';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$attr = $_POST['attr'] ?? '';
$checked = isset($_POST['checked']) ? (int)$_POST['checked'] : 0;
if (!$table || !$id || !$attr) {
  echo json_encode(['success' => false]);
  exit;
}
$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
$row = $db->rawQueryOne("SELECT status FROM `$table` WHERE id = ?", [$id]);
if (!$row) {
  echo json_encode(['success' => false]);
  exit;
}
$status_array = array_filter(array_map('trim', explode(',', $row['status'] ?? '')));
if ($checked && !in_array($attr, $status_array)) {
  $status_array[] = $attr;
} elseif (!$checked && in_array($attr, $status_array)) {
  $status_array = array_diff($status_array, [$attr]);
}
$new_status = implode(',', $status_array);
$stmt = $db->execute("UPDATE `$table` SET `status` = ? WHERE id = ?", [$new_status, $id]);
echo json_encode(['success' => $stmt !== false]);
exit;
