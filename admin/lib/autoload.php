<?php
include '../lib/session.php';
Session::checkSession();
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
  Session::destroy();
}
ob_start();
require_once '../lib/database.php';
require_once '../helpers/format.php';
spl_autoload_register(function ($class) {
  include "../classes/" . $class . ".php";
});
$fm = new Format();
$db = new Database();
$classes = ['functions', 'danhmuc', 'sanpham', 'slideshow', 'tieuchi', 'danhgia', 'setting', 'social', 'news', 'trangtinh', 'phuongthuctt'];
foreach ($classes as $class) {
  $$class = new $class();
}
define('ROOT', dirname(__DIR__));
define('UPLOADS', 'uploads/');
define('BASE_ADMIN', $config['baseAdmin']);
define('BASE', $config['base']);
define('NO_IMG', $config['baseAdmin'] . "assets/img/noimage.png");
