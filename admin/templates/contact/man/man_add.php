<!-- Content Header -->
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item"><a href="<?= $linkMan ?>" title="<?= quanlylienhe ?>"><?= quanlylienhe ?></a></li>
        <li class="breadcrumb-item active"><?= chitietlienhe ?></li>
      </ol>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i class="far fa-save mr-2"></i><?= luu ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i><?= lamlai ?></button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="<?= thoat ?>"><i class="fas fa-sign-out-alt mr-2"></i><?= thoat ?></a>
    </div>

    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title"><?= thongtinlienhe ?></h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="form-group col-12">
            <label class="d-inline-block align-middle mb-1 mr-2"><?= taptin ?>:</label>
            <?php if (isset($result['file_attach']) && ($result['file_attach'] != '')) { ?>
              <a class="btn btn-sm bg-gradient-primary text-white d-inline-block align-middle p-2 rounded mb-1" href="" title="<?= downloadtaptinhientai ?>"><i class="fas fa-download mr-2"></i><?= downloadtaptinhientai ?></a>
            <?php } else { ?>
              <a class="bg-gradient-secondary text-white d-inline-block p-2 rounded mb-1" href="#" title="Tập tin trống"><i class="fas fa-download mr-2"></i><?= taptintrong ?></a>
            <?php } ?>
          </div>
          <div class="form-group col-md-4">
            <label for="fullname"><?= hoten ?>:</label>
            <input type="text" class="form-control text-sm" name="data[fullname]" id="fullname" placeholder="<?= hoten ?>" value="<?= $result['fullname'] ?>" required>
          </div>
          <div class="form-group col-md-4">
            <label for="email">Email:</label>
            <input type="email" class="form-control text-sm" name="data[email]" id="email" placeholder="Email" value="<?= $result['email'] ?>" required>
          </div>
          <div class="form-group col-md-4">
            <label for="phone"><?= dienthoai ?>:</label>
            <input type="text" class="form-control text-sm" name="data[phone]" id="phone" placeholder="<?= dienthoai ?>" value="<?= $result['phone'] ?>" required>
          </div>
          <div class="form-group col-md-4">
            <label for="address"><?= diachi ?>:</label>
            <input type="text" class="form-control text-sm" name="data[address]" id="address" placeholder="<?= diachi ?>" value="<?= $result['address'] ?>" required>
          </div>
          <div class="form-group col-md-4">
            <label for="subject"><?= chude ?>:</label>
            <input type="text" class="form-control text-sm" name="data[subject]" id="subject" placeholder="<?= chude ?>" value="<?= $result['subject'] ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label for="content"><?= noidung ?>:</label>
          <textarea class="form-control text-sm" name="data[content]" id="content" rows="5" placeholder="<?= noidung ?>" required><?= $result['content'] ?></textarea>
        </div>
        <div class="form-group">
          <label for="notes"><?= ghichu ?>:</label>
          <textarea class="form-control text-sm" name="data[notes]" id="notes" rows="5" placeholder="<?= ghichu ?>"><?= $result['notes'] ?></textarea>
        </div>
        <div class="form-group">
          <?php $status_array = (!empty($result['status'])) ? explode(',', $result['status']) : array(); ?>
          <?php foreach ($config['contact']['check'] as $check => $label): ?>
            <div class="form-group d-inline-block mb-2 mr-5">
              <label for="<?= $check ?>-checkbox"
                class="d-inline-block align-middle mb-0 mr-3 form-label">
                <?= $label ?>:
              </label>
              <label class="switch switch-success">
                <input
                  type="checkbox"
                  name="data[status][<?= $check ?>]"
                  id="<?= $check ?>-checkbox"
                  class="switch-input custom-control-input"
                  value="1"
                  <?= $fn->is_checked($check, $result['status'] ?? '', $id ?? '') ?>>
              </label>
            </div>
          <?php endforeach; ?>

        </div>
        <div class="form-group">
          <label for="numb" class="d-inline-block align-middle mb-0 mr-2"><?= sothutu ?>:</label>
          <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0" name="data[numb]" id="numb" placeholder="<?= sothutu ?>" value="<?= isset($result['numb']) ? $result['numb'] : 1 ?>">
        </div>
      </div>
    </div>
    <div class="card-footer text-sm">
      <button type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i class="far fa-save mr-2"></i><?= luu ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i><?= lamlai ?></button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="<?= thoat ?>"><i class="fas fa-sign-out-alt mr-2"></i><?= thoat ?></a>
      <input type="hidden" name="id" value="<?= (isset($result['id']) && $result['id'] > 0) ? $result['id'] : '' ?>">
    </div>
  </form>
</section>
