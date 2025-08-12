<?php
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Giờ VN

require_once 'init.php';

// ====== Cấu hình ======
$backupDir = __DIR__ . '/backups';
if (!is_dir($backupDir)) {
  mkdir($backupDir, 0777, true);
}

$dbName    = $config['database']['dbname'] ?? 'database';
$dateTime  = date('Y-m-d_H-i-s');
$fileName  = "db_{$dbName}_{$dateTime}.sql"; // Giờ VN chính xác
$filePath  = $backupDir . '/' . $fileName;

// ====== Lấy danh sách bảng ======
$tables = [];
$result = $db->select("SHOW TABLES");
while ($row = $result->fetch_array()) {
  $tables[] = $row[0];
}

// ====== Bắt đầu xuất dữ liệu ======
$sqlDump  = "-- Database: {$dbName}\n";
$sqlDump .= "-- Created: " . date('Y-m-d H:i:s') . "\n\n";
$sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

foreach ($tables as $table) {
  $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
  $resCreate = $db->select("SHOW CREATE TABLE `$table`");
  $rowCreate = $resCreate->fetch_assoc();
  $sqlDump .= $rowCreate['Create Table'] . ";\n\n";

  $resData = $db->select("SELECT * FROM `$table`");
  if ($resData && $resData->num_rows > 0) {
    $columns = array_keys($resData->fetch_assoc());
    $insertPrefix = "INSERT INTO `$table` (`" . implode("`, `", $columns) . "`) VALUES\n";
    $resData->data_seek(0); // Quay lại đầu

    $batchSize = 200; // số dòng / batch
    $batchRows = [];
    $count = 0;

    while ($row = $resData->fetch_assoc()) {
      $vals = array_map(function ($v) use ($db) {
        return isset($v) ? "'" . $db->link->real_escape_string((string)$v) . "'" : "NULL";
      }, array_values($row));

      $batchRows[] = "(" . implode(", ", $vals) . ")";
      $count++;

      if ($count % $batchSize === 0) {
        $sqlDump .= $insertPrefix . implode(",\n", $batchRows) . ";\n";
        $batchRows = [];
      }
    }

    // Ghi nốt batch cuối
    if (!empty($batchRows)) {
      $sqlDump .= $insertPrefix . implode(",\n", $batchRows) . ";\n";
    }

    $sqlDump .= "\n";
  }
}

$sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

// Lưu file
file_put_contents($filePath, $sqlDump);

// ====== Header chống cache ======
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// ====== Xuất file tải xuống ======
header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;
