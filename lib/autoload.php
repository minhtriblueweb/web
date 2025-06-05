<?php
include 'lib/session.php';
Session::init();
ob_start();
?>
<?php
include_once 'lib/database.php';
include_once 'lib/validation.php';
include_once 'helpers/format.php';
spl_autoload_register(function ($class) {
  include_once "./classes/" . $class . ".php";
});
$db = new Database();
$fm = new Format();
$classes = ['functions', 'danhmuc', 'sanpham', 'slideshow', 'tieuchi', 'danhgia', 'setting', 'social', 'news', 'trangtinh', 'phuongthuctt'];
foreach ($classes as $class) {
  $$class = new $class();
}
