<?php
require_once 'init.php';
require_once '../lib/session.php';
require_once '../classes/class.adminlogin.php';
require_once '../lib/validation.php';

Session::init();
$login = new adminlogin();
$error = null;

// Lấy biến page & act từ URL
$page = $_GET['page'] ?? '';
$act = $_GET['act'] ?? '';

// ==== XỬ LÝ ĐĂNG NHẬP ====
if ($page === 'user' && $act === 'login') {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["btn_login"])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $login_check = $login->login($username, $password);

    if ($login_check === "success") {
      Session::set("adminlogin", [
        'active' => true,
        'name' => $username
      ]);
      Session::set("last_activity", time());
      header("Location: index.php");
      exit();
    } else {
      $error = $login_check;
    }
  }

  // Hiển thị giao diện login
  include TEMPLATE . "user/login.php";
  exit();
}

// ==== KIỂM TRA PHIÊN ====
Session::checkSession();

// ==== LẤY & LỌC TRANG ====
$page = $_GET['page'] ?? 'index';
$template = $page = preg_replace('/[^a-zA-Z0-9_-]/', '', basename($page));

// ==== TRANG THÔNG BÁO ====
if ($page === 'transfer') {
  define('IS_TRANSFER', true);
  $transferData = $_SESSION['transfer_data'] ?? ['msg' => 'Không có thông báo', 'page' => 'index.php', 'numb' => false];
  unset($_SESSION['transfer_data']);
  $showtext = $transferData['msg'];
  $page_transfer = $transferData['page'];
  $numb = $transferData['numb'];
  include TEMPLATE . LAYOUT . "transfer.php";
  exit();
}

// ==== FILE SOURCES ====
$source_file = SOURCES . $page . ".php";
$page_file = TEMPLATE . $page . ".php";
if (!file_exists($source_file) && !file_exists($page_file)) {
  define('IS_404', true);
}
?>
<!DOCTYPE html>
<html lang="<?= $config['website']['lang-doc'] ?>">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="./assets/img/logo2-3962.png" rel="shortcut icon" type="image/x-icon" />
  <title>Admin - <?= $optsetting["name$lang"] ?? '' ?></title>
  <?php include TEMPLATE . LAYOUT . "css.php"; ?>
</head>

<body class="sidebar-mini hold-transition text-sm">

  <!-- Loader -->
  <?php if ($template == 'index' || $template == 'user/login') include TEMPLATE . LAYOUT . "loader.php"; ?>

  <div class="wrapper">
    <?php
    include TEMPLATE . LAYOUT . "header.php";
    include TEMPLATE . LAYOUT . "sidebar.php";
    ?>
    <div class="content-wrapper">
      <?php
      if (!defined('IS_404') && file_exists($source_file)) {
        include_once $source_file;
        include TEMPLATE . $template . ".php";
      } elseif (!defined('IS_404') && file_exists($page_file)) {
        include $page_file;
      } else {
        $fn->abort_404();
      }
      ?>
    </div>
    <?php include TEMPLATE . LAYOUT . "footer.php"; ?>
  </div>
  <?php include TEMPLATE . LAYOUT . "js.php"; ?>
</body>

</html>
