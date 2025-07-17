<?php
// Hiển thị lỗi để debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Định nghĩa thư mục gốc dự án
$baseDir = realpath(dirname(__DIR__));
define('ROOT', $baseDir);

// 2. Load cấu hình hệ thống
require_once $baseDir . '/config/config.php';

// 3. Load các file hỗ trợ (dùng trước autoload)
require_once $baseDir . '/lib/database.php';
require_once $baseDir . '/lib/session.php';
require_once $baseDir . '/helpers/format.php';
require_once $baseDir . '/helpers/global_functions.php';

// 4. Autoload class dạng class.<ten>.php trong thư mục /classes/
spl_autoload_register(function ($class) use ($baseDir) {
  $path = $baseDir . '/classes/class.' . strtolower($class) . '.php';
  if (file_exists($path)) {
    require_once $path;
  }
});

// 5. Khởi tạo các class cơ bản
$db  = new Database();
$cache = new Cache($db);
$fn  = new Functions();
$fm  = new Format();
$seo = new Seo();

// 6. Định nghĩa các hằng số dùng chung
define('TEMPLATE', 'templates/');
define('UPLOADS', 'uploads/');
define('SOURCES', 'sources/');
define('LAYOUT', 'layout/');
define('WATERMARK', 'watermark/');
define('THUMB', 'thumb/');
define('VERSION', $fn->generateHash());
define('BASE', $config['base'] ?? '/');
define('BASE_ADMIN', $config['baseAdmin'] ?? '/admin/');
define('NO_IMG', BASE_ADMIN . 'assets/img/noimage.png');

// 7. Khởi tạo các class tiện ích mở rộng
$classes = ['breadcrumbs', 'product', 'setting', 'news',];

foreach ($classes as $class) {
  if (class_exists($class)) {
    $$class = new $class();
  }
}

// 8. Load các cài đặt từ DB (SEO, setting, ...)
require_once $baseDir . '/config/init_settings.php';

// 9. Khởi tạo minify CSS/JS
$css = new CssMinify($config['website']['debug-css'], $fn);
$js  = new JsMinify($config['website']['debug-js'], $fn);
