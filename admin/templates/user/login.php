<?php
// echo password_hash('fX*%DaY$p', PASSWORD_DEFAULT);
?>
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
  window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
      document.querySelectorAll('.title_bluewf').forEach(function(el) {
        el.classList.add('active');
      });
    }, 1000);
  });
</script>
