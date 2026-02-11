<?php
require_once 'init.php';
Session::init();
$login = new adminlogin();
$error = null;

/* ===== LẤY PAGE/ACT ===== */
$page = $_GET['page'] ?? 'index';
$act  = $_GET['act'] ?? '';
$type  = $_GET['type'] ?? '';
$kind  = $_GET['kind'] ?? '';

/* ===== SET TEMPLATE */
if ($page === 'user' && $act === 'login') {
  $template = 'user/login';
} else {
  $template = $page;
}

/* ===== XỬ LÝ ĐĂNG NHẬP ===== */
if ($template === 'user/login') {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["btn_login"])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $login_check = $login->login($username, $password);
    if ($login_check === "success") {
      Session::set("adminlogin", ['active' => true, 'name' => $username]);
      Session::set("last_activity", time());
      header("Location: index.php");
      exit();
    } else {
      $error = $login_check;
    }
  }
} else {
  Session::checkSession();
}

/* ===== TRẠNG THÁI LOGIN ===== */
$is_logged_in = Session::get("adminlogin")['active'] ?? false;

/* ===== TRANG TRANSFER ===== */
if ($page === 'transfer') {
  define('IS_TRANSFER', true);
  $transferData  = $_SESSION['transfer_data'] ?? [
    'msg'  => 'Không có thông báo',
    'page' => 'index.php',
    'numb' => false
  ];
  unset($_SESSION['transfer_data']);
  $showtext      = $transferData['msg'];
  $page_transfer = $transferData['page'];
  $numb          = $transferData['numb'];
  include TEMPLATE . LAYOUT . "transfer.php";
  exit();
}

/* ===== FILE SOURCES ===== */
$source_file = SOURCES . $page . ".php";
if (file_exists($source_file)) {
  include_once $source_file;
}
$page_file = TEMPLATE . $template . ".php";
if (!file_exists($page_file)) {
  define('IS_404', true);
}

/* Kiểm tra phân quyền */
if (!empty($config['permission']['active']) && !empty($_SESSION[$loginAdmin]['active'])) {
  /* Lấy quyền */
  $_SESSION[$loginAdmin]['permissions'] = array();
  if (!empty($_SESSION[$loginAdmin]['id'])) {
    $id_permission = $d->rawQueryOne("select id_permission from #_user where id = ? and find_in_set('hienthi',status) limit 0,1", array($_SESSION[$loginAdmin]['id']));
    if (!empty($id_permission['id_permission'])) {
      $permission = $d->rawQueryOne("select id from #_permission_group where id = ? and find_in_set('hienthi',status) limit 0,1", array($id_permission['id_permission']));
      if (!empty($permission['id'])) {
        $user_permission = $d->rawQuery("select permission from #_permission where id_permission_group = ?", array($permission['id']));
        if (!empty($user_permission)) {
          foreach ($user_permission as $value) {
            $_SESSION[$loginAdmin]['permissions'][] = $value['permission'];
          }
        }
      }
    }
  }

  /* Kiểm tra quyền */
  if ($func->checkRole()) {
    $is_permission = true;

    if (!empty($com) && !in_array($com, ['user', 'index']) && !empty($act) && !in_array($act, ['save', 'save_list', 'save_cat', 'save_item', 'save_sub', 'save_brand', 'save_color', 'save_size', 'saveImages', 'uploadExcel', 'save_static', 'save_photo'])) {
      $sum_permission = '';
      $sum_permission .= $com . '_' . $act;
      $sum_permission .= (!empty($variant)) ? '_' . $variant : '';
      $sum_permission .= (!empty($type)) ? '_' . $type : '';

      if (isset($_SESSION[$loginAdmin]['permissions'])) {
        if (!in_array($sum_permission, $_SESSION[$loginAdmin]['permissions'])) {
          $func->transfer("Bạn không có quyền truy cập vào khu vực này", "index.php", false);
        }
      }
    }
  }
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

<body class="sidebar-mini hold-transition text-sm <?= $is_logged_in ? '' : 'login-page' ?>">
  <!-- Loader -->
  <?php if ($template === 'index' || $template === 'user/login') include TEMPLATE . LAYOUT . "loader.php"; ?>

  <?php if ($is_logged_in) { ?>
    <div class="wrapper">
      <?php
      include TEMPLATE . LAYOUT . "header.php";
      include TEMPLATE . LAYOUT . "sidebar.php";
      ?>
      <div class="content-wrapper">
        <?php
        if (!defined('IS_404') && file_exists($page_file)) include $page_file;
        else $fn->abort_404();
        ?>
      </div>
      <?php include TEMPLATE . LAYOUT . "footer.php"; ?>
    </div>
  <?php } else {
    include TEMPLATE . "user/login.php";
  } ?>

  <?php include TEMPLATE . LAYOUT . "js.php"; ?>
</body>

</html>
