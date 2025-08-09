<?php
if (!empty($_GET['changepass']) && ($_GET['changepass'] == 1)) {
  $changepass = "&changepass=1";
} else {
  $changepass = '';
}
$linkSave = "index.php?page=user&act=info_admin" . $changepass;
?>
<!-- Content Header -->
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= thongtintaikhoan ?></li>
      </ol>
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
  <form class="validation-form" novalidate method="post" action="<?= $linkSave ?>" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i class="far fa-save mr-2"></i><?= luu ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i><?= lamlai ?></button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title"><?= thongtinadmin ?></h3>
      </div>
      <div class="card-body">
        <div class="row">
          <?php if (!empty($changepass)) { ?>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="old-password"><?= matkhaucu ?>:</label>
              <div class="input-group">
                <input type="password" class="form-control text-sm" name="old-password" id="old-password" placeholder="<?= matkhaucu ?>" value="<?= $_POST['old-password'] ?? ($_POST['old-password'] ?? '') ?>">
                <div class="input-group-append">
                  <div class="input-group-text show-password">
                    <span class="fas fa-eye"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="new-password">
                <span class="d-inline-block align-middle"><?= matkhaumoi ?>:</span>
                <span class="text-danger ml-2" id="show-password"></span>
              </label>
              <div class="row align-items-center">
                <div class="col-6">
                  <div class="input-group">
                    <input type="password" class="form-control text-sm" name="new-password" id="new-password" placeholder="<?= matkhaumoi ?>">

                    <div class="input-group-append">
                      <div class="input-group-text show-password">
                        <span class="fas fa-eye"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <a class="btn btn-sm bg-gradient-primary text-sm" href="#" onclick="randomPassword()">
                    <i class="fas fa-random mr-2"></i><?= taomatkhau ?>
                  </a>
                </div>
              </div>
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="renew-password"><?= nhaplaimatkhaumoi ?>:</label>
              <div class="input-group">
                <input type="password" class="form-control text-sm" name="renew-password" id="renew-password" placeholder="<?= nhaplaimatkhaumoi ?>">
                <div class="input-group-append">
                  <div class="input-group-text show-password">
                    <span class="fas fa-eye"></span>
                  </div>
                </div>
              </div>
            </div>
          <?php } else { ?>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="username"><?= taikhoan ?>: <span class="text-danger">*</span></label>
              <input type="text" class="form-control text-sm" id="username" value="<?= $result['username'] ?>" disabled>
              <input type="hidden" name="data[username]" value="<?= $result['username'] ?>">
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="fullname"><?= hoten ?>: <span class="text-danger">*</span></label>
              <input type="text" class="form-control text-sm" name="data[fullname]" id="fullname" placeholder="<?= hoten ?>" value="<?= $result['fullname'] ?>" required>
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="email">Email:</label>
              <input type="email" class="form-control text-sm" name="data[email]" id="email" placeholder="Email" value="<?= $result['email'] ?>">
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="phone"><?= dienthoai ?>:</label>
              <input type="text" class="form-control text-sm" name="data[phone]" id="phone" placeholder="<?= dienthoai ?>" value="<?= $result['phone'] ?>">
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="gender"><?= gioitinh ?>:</label>
              <select class="custom-select text-sm" name="data[gender]" id="gender">
                <option value=""><?= chongioitinh ?></option>
                <option value="1" <?= (isset($result['gender']) && $result['gender'] == 1) ? 'selected' : '' ?>><?= nam ?></option>
                <option value="2" <?= (isset($result['gender']) && $result['gender'] == 2) ? 'selected' : '' ?>><?= nu ?></option>
              </select>
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="birthday"><?= ngaysinh ?>:</label>
              <input type="text" class="form-control text-sm max-date" name="data[birthday]" id="birthday" placeholder="<?= ngaysinh ?>" value="<?= (!empty($result['birthday']) ? date('d/m/Y', $result['birthday']) : '') ?>" autocomplete="off">

            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="address"><?= diachi ?>:</label>
              <input type="text" class="form-control text-sm" name="data[address]" id="address" placeholder="<?= diachi ?>" value="<?= $result['address'] ?>" required>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="card-footer text-sm">
      <button type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i class="far fa-save mr-2"></i><?= luu ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i><?= lamlai ?></button>
      <input type="hidden" name="id" value="<?= (isset($item['id']) && $item['id'] > 0) ? $item['id'] : '' ?>">
    </div>
  </form>
</section>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const showPasswordToggles = document.querySelectorAll(".show-password");
    showPasswordToggles.forEach(toggle => {
      toggle.addEventListener("click", function() {
        const input = this.closest(".input-group").querySelector("input");
        const icon = this.querySelector("span");

        if (input.type === "password") {
          input.type = "text";
          icon.classList.remove("fa-eye");
          icon.classList.add("fa-eye-slash");
        } else {
          input.type = "password";
          icon.classList.remove("fa-eye-slash");
          icon.classList.add("fa-eye");
        }
      });
    });
  });
</script>
