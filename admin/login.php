<?php
include_once '../lib/session.php';
Session::init();

if (Session::get("adminlogin")) {
  header("Location: index.php");
  exit();
}

include_once '../classes/class.adminlogin.php';
include_once '../lib/validation.php';

$login = new adminlogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["btn_login"])) {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $login_check = $login->login($username, $password);
  if ($login_check === "success") {
    Session::set("adminlogin", true);
    Session::set("last_activity", time());
    header("Location: index.php");
    exit();
  } else {
    $error = $login_check;
  }
}
// $password = "%Q@Q#Tc@V";
// $hashed = password_hash($password, PASSWORD_DEFAULT);
// echo "Mật khẩu đã hash: " . $hashed;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="assets/img/logo2-3962.png" rel="shortcut icon" type="image/x-icon" />
  <title>Đăng Nhập</title>
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
  <link href="assets/css/adminlte.css" rel="stylesheet" />
  <link href="assets/css/adminlte-style.css" rel="stylesheet" />
</head>

<body class="sidebar-mini hold-transition text-sm login-page">
  <!-- Loader -->
  <?php
  if (session_id() === '') session_start();
  if (isset($_SESSION['loader'])) {
    $_SESSION['loader'] += 1;
  } else {
    $_SESSION['loader'] = 1;
  }
  $luotvao = $_SESSION['loader'];
  ?>

  <?php if ($luotvao == 1): ?>
    <div class="loader-wrapper" id="global-loader">
      <div class="loader">
        <div class="preloader">
          <div class="spinner-layer">
            <div class="circle-clipper float-left">
              <div class="circle"></div>
            </div>
            <div class="circle-clipper float-right">
              <div class="circle"></div>
            </div>
          </div>
        </div>
        <p>Please wait...</p>
      </div>
    </div>
  <?php endif; ?>

  <!-- Wrapper -->
  <div class="login_blueweb blue-wrap-main">
    <div class="login_blueweb_content">
      <div class="login_blueweb_left">
        <img src="./assets/img/bg_admin2.jpg">
      </div>
      <div class="login_blueweb_right">
        <div class="login-trongdong">
          <img src="./assets/img/trongdong.png">
        </div>

        <div class="login_blueweb_right_content">
          <div class="gr_toploginf ">
            <div class="title_bluewf"><span>MINH TRÍ WEB</span></div>
            <a href="../" target="_blank" title="Xem website">
              Về trang chủ
              <img src="./assets/img/icontrangchu.png" alt="" class="icontrangchu">
            </a>
          </div>

          <div class="gr_titlehethong">
            <img src="./assets/img/security.png" alt="" class="security">
            <div class="title_loginhethong">Đăng Nhập Hệ Thống</div>
            <div class="mien"><?= $config['base'] ?></div>
          </div>

          <form action="" method="post" class="formLoginBlueweb" enctype="multipart/form-data">
            <div class="input-group mb-3">
              <div class="input-group-append login-input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
              <input type="text" class="form-control text-sm" name="username" id="username"
                placeholder="Tài khoản *" autocomplete="off" value="<?= $username ?? '' ?>">
            </div>
            <div class="input-group mb-3">
              <div class="input-group-append login-input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
              <input type="password" class="form-control text-sm" name="password" id="password"
                placeholder="Mật khẩu *" autocomplete="off">
              <div class="input-group-append">
                <div class="input-group-text show-password">
                  <span class="fas fa-eye"></span>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-sm bg-gradient-danger btn-block btn-login text-sm p-2" name="btn_login" id="btn_login">
              <div class="sk-chase d-none">
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
              </div>
              <span>ĐĂNG NHẬP</span>
            </button>

            <div class="login_l_footer">
              Designed by Minh Trí Website - 0328 732 834
            </div>
            <?php if (isset($login_check)): ?>
              <div class="alert my-alert alert-login text-center text-sm p-2 mb-0 mt-2 alert-danger" role="alert">
                <?= $login_check; ?>
              </div>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
      const passwordInput = document.getElementById("password");
      const showPasswordBtn = document.querySelector(".show-password");
      const showPasswordIcon = showPasswordBtn.querySelector("span");
      showPasswordBtn.addEventListener("click", function() {
        const isVisible = passwordInput.type === "text";
        passwordInput.type = isVisible ? "password" : "text";
        showPasswordIcon.classList.toggle("fa-eye");
        showPasswordIcon.classList.toggle("fa-eye-slash");
        showPasswordBtn.classList.toggle("active");
      });
    });
    window.addEventListener("load", function() {
      const loader = document.getElementById("global-loader");
      if (loader) {
        loader.style.transition = "opacity 0.5s";
        loader.style.opacity = 0;
        setTimeout(() => loader.remove(), 500);
      }
    });
    window.addEventListener('DOMContentLoaded', function() {
      setTimeout(function() {
        document.querySelectorAll('.title_bluewf').forEach(function(el) {
          el.classList.add('active');
        });
      }, 1000);
    });
  </script>
</body>

</html>
