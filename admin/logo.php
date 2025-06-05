<?php include('inc/header.php'); ?>
<?php include('inc/sidebar.php'); ?>
<!-- Main content -->
<?php
$get_logo = $setting->get_setting_item("logo");
if ($get_logo) {
  $result_logo = $get_logo->fetch_assoc();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $update = $setting->update_setting_item("logo", $_POST, $_FILES);
}
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item active">Quản lý hình ảnh - video</li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary submit-check"><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Chi tiết Logo</h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <div class="upload-file">
            <p>Upload hình ảnh:</p>
            <label class="upload-file-label mb-2" for="file">
              <div class="upload-file-image rounded mb-3">
                <img
                  src="<?= empty($result_logo['logo']) ? $config['baseAdmin'] . "assets/img/noimage.png" : $config['baseAdmin'] . "uploads/" . htmlspecialchars($result_logo['logo']); ?>"
                  class="rounded img-upload" id="preview-image">
              </div>
              <div class="custom-file my-custom-file">
                <input type="file" class="custom-file-input" name="logo" id="file" lang="vi">
                <label class="custom-file-label mb-0" data-browse="Chọn" for="file">Chọn file</label>
              </div>
            </label>
            <strong class="d-block text-sm">Width: 300 px - Height: 120 px
              (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)</strong>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="hash" value="iGEWLTFzs">
  </form>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>