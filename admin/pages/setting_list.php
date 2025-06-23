<?php
$redirect_url = $_GET['page'];
$type = $_GET['type'];
$name_page = $fn->convert_type($type)['vi'];
$dimensions = [
  'logo' => [300, 120],
  'favicon' => [48, 48]
];
list($thumb_width, $thumb_height) = $dimensions[$type] ?? [];
$get_data = $setting->get_setting_item($type);
if ($get_data) {
  $result = $get_data->fetch_assoc();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $update = $setting->update_setting_item($type, $_POST, $_FILES);
}
?>
<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => 'index.php'],
  ['label' => $name_page]
];
include 'templates/breadcrumb.php';
?>
<section class="content">
  <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary submit-check"><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Chi tiết <?= $name_page ?></h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <div class="upload-file">
            <p>Upload hình ảnh:</p>
            <label class="upload-file-label mb-2" for="file">
              <div class="upload-file-image rounded mb-3">
                <div class="d-flex justify-content-center">
                  <div class="d-flex justify-content-center">
                    <div class="border rounded bg-white d-flex align-items-center justify-content-center" style="width:<?= $thumb_width ?>px; height:<?= $thumb_height ?>px;">
                      <img src="<?= empty($result[$type]) ? NO_IMG : BASE_ADMIN . UPLOADS . htmlspecialchars($result[$type]); ?>" class="img-fluid" id="preview-image" style="max-height:100%; max-width:100%;" alt="<?= $type ?>">
                    </div>
                  </div>

                </div>
              </div>
              <div class="custom-file my-custom-file">
                <input type="file" class="custom-file-input" name="<?= $type ?>" id="file" lang="vi">
                <label class="custom-file-label mb-0" data-browse="Chọn" for="file">Chọn file</label>
              </div>
            </label>
            <strong class="d-block text-sm">
              Width: <?= $thumb_width ?> px - Height: <?= $thumb_height ?> px (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)
            </strong>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="thumb_width" value="<?= $thumb_width ?>">
    <input type="hidden" name="thumb_height" value="<?= $thumb_height ?>">
  </form>
</section>
