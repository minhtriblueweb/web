<?php
include '../classes/adminlogin.php';
include '../lib/validation.php';
?>
<?php
$login = new adminlogin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["btn_login"])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $login_check = $login->login($username, $password);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="./assets/img/logo2-3962.png" rel="shortcut icon" type="image/x-icon" />
  <title>Đăng Nhập</title>

  <!-- Css all -->
  <!-- Css Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />

  <!-- Css Files -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
  <link href="./assets/css/adminlte.css" rel="stylesheet" />
  <link href="./assets/css/adminlte-style.css" rel="stylesheet" />
</head>

<body class="sidebar-mini hold-transition text-sm login-page">
  <!-- Loader -->
  <?php
  if (session_id() === '')
    session_start();
  if (isset($_SESSION['loader'])) {
    $_SESSION['loader'] += 1;
  } else {
    $_SESSION['loader'] = 1;
  }
  $luotvao = $_SESSION['loader'];
  if ($luotvao == '1') {
  ?>
    <div class="loader-wrapper">
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
  <?php } ?>
  <!-- Wrapper -->
  <div class="login-view-website text-sm"><a href="../" target="_blank" title="Xem website"><i
        class="fas fa-reply mr-2"></i>Xem website</a></div>
  <div class="login-box">
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Đăng nhập hệ thống</p>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="input-group mb-3">
            <div class="input-group-append login-input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
            <input type="text" class="form-control text-sm" name="username" id="username" placeholder="Tài khoản *"
              value="<?php if (!empty($username)) {
                        echo $username;
                      } ?>" autocomplete="username">
          </div>
          <div class="input-group mb-3">
            <div class="input-group-append login-input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            <input type="password" class="form-control text-sm" name="password" id="password" placeholder="Mật khẩu *"
              autocomplete="password">
            <div class="input-group-append">
              <div class="input-group-text show-password">
                <span class="fas fa-eye"></span>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-sm bg-gradient-danger btn-block btn-login text-sm p-2" name="btn_login"
            id="btn_login">
            <div class="sk-chase d-none">
              <div class="sk-chase-dot"></div>
              <div class="sk-chase-dot"></div>
              <div class="sk-chase-dot"></div>
              <div class="sk-chase-dot"></div>
              <div class="sk-chase-dot"></div>
              <div class="sk-chase-dot"></div>
            </div>
            <span>Đăng nhập</span>
          </button>
          <?php if (isset($login_check)): ?>
            <div class="alert my-alert alert-login text-center text-sm p-2 mb-0 mt-2 alert-danger" role="alert">
              <?= $login_check; ?>
            </div>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
  <div class="login-copyright text-sm">Powered by <a href="" target="_blank" title="">Designed by Minh Trí Web</a></div>
  <script src="./assets/js/jquery.min.js"></script>
  <script>
    $(".show-password").click(function() {
      if ($("#password").val()) {
        const passwordField = $("#password");
        const isActive = $(this).hasClass("active");
        if (isActive) {
          $(this).removeClass("active");
          passwordField.attr("type", "password");
          $(this).attr("aria-pressed", "false");
          $(this).find("span").removeClass("fas fa-eye-slash").addClass("fas fa-eye");
        } else {
          $(this).addClass("active");
          passwordField.attr("type", "text");
          $(this).attr("aria-pressed", "true");
          $(this).find("span").removeClass("fas fa-eye").addClass("fas fa-eye-slash");
        }
      }
    });
  </script>
</body>

</html>