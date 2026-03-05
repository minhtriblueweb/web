<?php
// echo $func->encryptPassword($config['website']['secret'], 'dQ#iyuhLz', $config['website']['salt']);
?>
<div class="login_blueweb blue-wrap-main">
  <div class="login_blueweb_content">
    <div class="login_blueweb_right">
      <div class="trongdong-slide">
        <div class="login-trongdong"></div>
      </div>
      <div class="login-trongdong_2"></div>
      <div class="login_blueweb_item_content">
        <div class="gr_toploginf">
          <div class="plR5qb">
            <div class="mTurwe">
              <div class="iydNQb"></div>
            </div>
            <div class="xL0qi">
              <div class="iydNQb"></div>
            </div>
            <div class="bvUkz"></div>
            <div class="newsfeed">MINH TRÍ WEB</div>
          </div>
          <a href="../" target="_blank" title="Xem website">Về trang chủ</a>
        </div>
        <div class="gr_titlehethong">
          <img src="./assets/img/security.png" alt="" class="security">
          <div class="title_loginhethong">Hệ Thống Quản Trị</div>
          <div class="mien"><?= $configBase ?></div>
        </div>
        <form class="formLoginBlueweb" enctype="multipart/form-data">
          <input type="hidden" name="xsrf_token" id="xsrf_token"
            value="<?= $_SESSION['xsrf_token']; ?>">
          <div class="input-group mb-3 login-input">
            <div class="input-group-prepend login-input-group-append">
              <span class="input-group-text login-icon">
                <i class="fas fa-user"></i>
              </span>
            </div>
            <input
              type="text"
              class="form-control form-control-lg login-input-control"
              name="username"
              id="username"
              placeholder="Tài khoản *"
              autocomplete="off"
              value="<?= $username ?? '' ?>">
          </div>
          <div class="input-group mb-3 login-input">
            <div class="input-group-prepend login-input-group-append">
              <span class="input-group-text login-icon">
                <i class="fas fa-lock"></i>
              </span>
            </div>
            <input
              type="password"
              class="form-control form-control-lg login-input-control"
              name="password"
              id="password"
              placeholder="Mật khẩu *"
              autocomplete="off">

            <div class="input-group-append">
              <span class="input-group-text login-icon login-toggle-password">
                <i class="fas fa-eye"></i>
              </span>
            </div>
          </div>

          <button type="button" class="login_blueweb_btn btn-login">
            <span class="login-btn-text">ĐĂNG NHẬP</span>
          </button>

          <div class="login_l_footer">
            Designed by Minh Trí Website - 0328 732 834
          </div>

          <div class="alert my-alert alert-login d-none text-center text-sm p-2 mb-0 mt-2" role="alert"></div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  document.querySelector('.login-toggle-password')
    ?.addEventListener('click', function() {
      const input = document.getElementById('password');
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  document.querySelector('form')?.addEventListener('submit', () => {
    document.querySelector('.login_blueweb_btn')?.classList.add('is-loading');
  });
</script>
