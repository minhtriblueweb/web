<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();

function current_year()
{
  return date('Y');
}

$baseDir = dirname(__DIR__); // trỏ về thư mục gốc "web"

// Config chung
require_once $baseDir . '/config/config.php';

// Các thư viện bắt buộc
require_once $baseDir . '/lib/database.php';
require_once $baseDir . '/lib/session.php';
require_once $baseDir . '/helpers/format.php';

// Autoload tất cả class trong /classes
spl_autoload_register(function ($class) use ($baseDir) {
  $path = $baseDir . '/classes/' . $class . '.php';
  if (file_exists($path)) {
    require_once $path;
  }
});

// Khởi tạo sẵn các class hay dùng
$db = new Database();
$classes = ['functions', 'danhmuc', 'sanpham', 'slideshow', 'tieuchi', 'danhgia', 'setting', 'social', 'news', 'trangtinh', 'phuongthuctt'];
foreach ($classes as $class) {
  if (class_exists($class)) {
    $$class = new $class();
  }
}

// Định nghĩa đường dẫn
define('ROOT', $baseDir);
define('UPLOADS', 'uploads/');
define('BASE', $config['base']);
define('BASE_ADMIN', $config['baseAdmin']);
define('NO_IMG', BASE_ADMIN . 'assets/img/noimage.png');

$get_setting = $setting->get_setting();
if ($get_setting) {
  $row_st = $get_setting->fetch_assoc();
}

function get_setting_value($key, $htmlspecialchars = true, $default = '')
{
  global $row_st;
  if (isset($row_st[$key])) {
    return $htmlspecialchars ? htmlspecialchars($row_st[$key]) : $row_st[$key];
  }
  return $default;
}

$default_seo = [
  'favicon'     => !empty($row_st['favicon']) ? BASE_ADMIN . UPLOADS . $row_st['favicon'] : '',
  'title'       => get_setting_value('web_name'),
  'keywords'    => '',
  'description' => get_setting_value('descvi'),
  'geo'         => get_setting_value('coords'),
  'web_name'    => get_setting_value('web_name'),
  'email'       => get_setting_value('email'),
  'url'         => BASE,
  'image'       => !empty($row_st['logo']) ? BASE_ADMIN . UPLOADS . $row_st['logo'] : ''
];

// Các biến khác
$hotline         = get_setting_value('hotline');
$web_name        = get_setting_value('web_name');
$introduction    = get_setting_value('introduction');
$logo            = !empty($row_st['logo']) ? BASE_ADMIN . UPLOADS . $row_st['logo'] : '';
$worktime        = get_setting_value('worktime');
$descvi          = get_setting_value('descvi');
$client_support  = get_setting_value('client_support', false);
$support         = get_setting_value('support', false);
$copyright       = get_setting_value('copyright');
$bodyjs          = get_setting_value('bodyjs', false);
$headjs          = get_setting_value('headjs', false);
$analytics       = get_setting_value('analytics', false);
