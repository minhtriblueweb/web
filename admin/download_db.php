<?php
require_once 'init.php';

// Lấy thông tin từ config
$dbHost    = $config['database']['host'];
$dbUser    = $config['database']['username'];
$dbPass    = $config['database']['password'];
$dbName    = $config['database']['dbname'];
$dbCharset = $config['database']['charset'] ?? 'utf8mb4';

try {
  // Kết nối PDO
  $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset";
  $pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $dbCharset"
  ]);

  // Lấy danh sách bảng
  $tables = [];
  $stmt = $pdo->query("SHOW TABLES");
  while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    $tables[] = $row[0];
  }

  // Bắt đầu tạo nội dung SQL
  $sql = "-- Backup Database: $dbName\n";
  $sql .= "-- Created: " . date('Y-m-d H:i:s') . "\n\n";
  $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

  foreach ($tables as $table) {
    // CREATE TABLE
    $stmt2 = $pdo->query("SHOW CREATE TABLE `$table`");
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $sql .= "DROP TABLE IF EXISTS `$table`;\n";
    $sql .= $row2['Create Table'] . ";\n\n";

    // Dữ liệu bảng
    $stmt3 = $pdo->query("SELECT * FROM `$table`");
    while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
      $cols = array_map(fn($col) => "`" . str_replace("`", "``", $col) . "`", array_keys($row3));
      $vals = array_map(fn($val) => $val === null ? "NULL" : $pdo->quote($val), array_values($row3));
      $sql .= "INSERT INTO `$table` (" . implode(", ", $cols) . ") VALUES (" . implode(", ", $vals) . ");\n";
    }
    $sql .= "\n";
  }

  $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

  // Xuất file
  $fileName = "backup_" . date('Y-m-d_H-i-s') . ".sql";
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="' . $fileName . '"');
  header('Content-Length: ' . strlen($sql));
  echo $sql;
  exit;
} catch (PDOException $e) {
  die("Lỗi kết nối database: " . $e->getMessage());
}
