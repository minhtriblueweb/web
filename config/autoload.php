<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Định nghĩa thư mục gốc dự án
$baseDir = realpath(dirname(__DIR__));

// 2. Load cấu hình cơ bản (định nghĩa BASE, cấu hình DB, ...)
require_once $baseDir . '/config/config.php';

// 3. Load các file hỗ trợ cần dùng trước khi autoload class
require_once $baseDir . '/lib/database.php';
require_once $baseDir . '/lib/session.php';
require_once $baseDir . '/helpers/format.php';
require_once $baseDir . '/helpers/global_functions.php';

// 4. Autoload class trong thư mục /classes/
spl_autoload_register(function ($class) use ($baseDir) {
  $path = $baseDir . '/classes/' . $class . '.php';
  if (file_exists($path)) {
    require_once $path;
  }
});

// 5. Tạo đối tượng các class cơ bản
$db = new Database();
$fn = new Functions();
$fm = new Format();

// 6. Load constant trước khi gọi setting/SEO
require_once $baseDir . '/config/constants.php';

// 7. Khởi tạo các class thường dùng (sau constants.php để dùng BASE,...)
$classes = ['seo', 'danhmuc', 'sanpham', 'slideshow', 'tieuchi', 'danhgia', 'setting', 'social', 'news', 'trangtinh', 'payment'];
foreach ($classes as $class) {
  if (class_exists($class)) {
    $$class = new $class();
  }
}

// 8. Load các cài đặt SEO & Setting (đọc từ DB)
require_once $baseDir . '/config/init_settings.php';

// 9. Khởi tạo Css, Js

$css = new CssAssets([
  'server' => $baseDir . '/assets/',
  'asset' => BASE . 'assets/'
]);

$js = new JsAssets([
  'server' => $baseDir . '/assets/',
  'asset' => BASE . 'assets/'
]);
