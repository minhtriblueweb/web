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
    <?php if (!empty($message)): ?>
      <div class="card bg-gradient-danger">
        <div class="card-header">
          <h3 class="card-title"><?= thongbao ?></h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <p class="mb-1">- <?= $message ?></p>
        </div>
      </div>
    <?php endif; ?>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title"><?= thongtinadmin ?></h3>
      </div>
      <div class="card-body">
        <div class="row">
          <?php if (!empty($changepass)) { ?>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="old-password"><?= matkhaucu ?>:</label>
              <input type="password" class="form-control text-sm" name="old-password" id="old-password" placeholder="<?= matkhaucu ?>">
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="new-password">
                <span class="d-inline-block align-middle"><?= matkhaumoi ?>:</span>
                <span class="text-danger ml-2" id="show-password"></span>
              </label>
              <div class="row align-items-center">
                <div class="col-6"><input type="password" class="form-control text-sm" name="new-password" id="new-password" placeholder="<?= matkhaumoi ?>"></div>
                <div class="col-6"><a class="btn btn-sm bg-gradient-primary text-sm" href="#" onclick="randomPassword()"><i class="fas fa-random mr-2"></i><?= taomatkhau ?></a></div>
              </div>
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="renew-password"><?= nhaplaimatkhaumoi ?>:</label>
              <input type="password" class="form-control text-sm" name="renew-password" id="renew-password" placeholder="<?= nhaplaimatkhaumoi ?>">
            </div>
          <?php } else { ?>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="username"><?= taikhoan ?>: <span class="text-danger">*</span></label>
              <input type="text" class="form-control text-sm" name="data[username]" id="username" placeholder="<?= taikhoan ?>" value="" required>
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="fullname"><?= hoten ?>: <span class="text-danger">*</span></label>
              <input type="text" class="form-control text-sm" name="data[fullname]" id="fullname" placeholder="<?= hoten ?>" value="" required>
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="email">Email:</label>
              <input type="email" class="form-control text-sm" name="data[email]" id="email" placeholder="Email" value="">
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="phone"><?= dienthoai ?>:</label>
              <input type="text" class="form-control text-sm" name="data[phone]" id="phone" placeholder="<?= dienthoai ?>" value="">
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="gender"><?= gioitinh ?>:</label>
              <select class="custom-select text-sm" name="data[gender]" id="gender" required>
                <option value=""><?= chongioitinh ?></option>
                <option value="1"><?= nam ?></option>
                <option value="2"><?= nu ?></option>
              </select>
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="birthday"><?= ngaysinh ?>:</label>
              <input type="text" class="form-control text-sm max-date" name="data[birthday]" id="birthday" placeholder="<?= ngaysinh ?>" value="" required autocomplete="off">
            </div>
            <div class="form-group col-xl-4 col-lg-6 col-md-6">
              <label for="address"><?= diachi ?>:</label>
              <input type="text" class="form-control text-sm" name="data[address]" id="address" placeholder="<?= diachi ?>" value="" required>
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
