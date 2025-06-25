<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$baseDir = dirname(__DIR__);
require_once $baseDir . '/config/config.php';

// Các thư viện
require_once $baseDir . '/lib/database.php';
require_once $baseDir . '/lib/session.php';
require_once $baseDir . '/helpers/format.php';
require_once $baseDir . '/helpers/global_functions.php';

// Khởi tạo autoload class
spl_autoload_register(function ($class) use ($baseDir) {
  $path = $baseDir . '/classes/' . $class . '.php';
  if (file_exists($path)) {
    require_once $path;
  }
});

// Tạo đối tượng
$db = new Database();
$fn = new Functions();
$fm = new Format();

// Khởi tạo sẵn các class hay dùng
$classes = ['danhmuc', 'sanpham', 'slideshow', 'tieuchi', 'danhgia', 'setting', 'social', 'news', 'trangtinh', 'payment'];
foreach ($classes as $class) {
  if (class_exists($class)) {
    $$class = new $class();
  }
}

// Load constants
require_once $baseDir . '/config/constants.php';

// Load setting + SEO
require_once $baseDir . '/config/init_settings.php';
