<?php
$redirect_url = $_GET['page'] ?? '';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$name_page = $fn->convert_type($type)['vi'] ?? '';
$setting_page = [
  'message' => '',
  'name_page' => $name_page,
  'table' => 'tbl_seo',
  'thumb_width' => 600,
  'thumb_height' => 315,
  'thumb_zc' => 1,
  'type' => $type
];
extract($setting_page);
$seo_data = $db->rawQueryOne("SELECT * FROM tbl_seopage WHERE `type` = ?", [$type]);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $update = $seo->save_seopage($_POST, $_FILES);
}
?>

<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => 'index.php'],
  ['label' => $name_page]
];
include 'templates/breadcrumb.php';
?>
<?php if (!empty($type) && !empty($fn->convert_type($type))) { ?>
  <section class="content">
    <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
      <div class="card-footer text-sm sticky-top">
        <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary submit-check"><i
            class="far fa-save mr-2"></i>Lưu</button>
        <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
      </div>
      <div class="card card-primary card-outline text-sm">
        <div class="card-header">
          <h3 class="card-title">Thông tin SEO page - <?= $name_page ?></h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <div class="upload-file">
              <p>Upload hình ảnh:</p>
              <?php
              $file = $seo_data['file'] ?? ($_POST['file'] ?? '');
              ?>
              <?php if (!empty($file)): ?>
                <div class="d-flex align-items-center justify-content-center" style="width:300px; height:200px;">
                  <?= $fn->getImage([
                    'file'  => $file,
                    'class' => 'img-fluid',
                    'id'    => 'preview-image',
                    'style' => 'max-height:100%; max-width:100%;',
                    'alt'   => $name_page,
                    'title' => $name_page
                  ]) ?>
                </div>
              <?php endif; ?>

              <label class="upload-file-label mb-2 mt-3" for="file">
                <div class="custom-file my-custom-file">
                  <input type="file" class="custom-file-input" name="file" id="file" lang="vi">
                  <label class="custom-file-label mb-0" data-browse="Chọn" for="file">Chọn file</label>
                </div>
              </label>
              <strong class="d-block text-sm"> Width: <?= $thumb_width ?> px - Height: <?= $thumb_height ?> px <br>
                (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)</strong>
            </div>
          </div>
          <?php include 'templates/seo.php'; ?>
          <input type="hidden" name="type" id="type" value="<?= $type ?>">
          <input type="hidden" name="thumb_width" value="<?= $thumb_width ?>">
          <input type="hidden" name="thumb_height" value="<?= $thumb_height ?>">
          <input type="hidden" name="thumb_zc" value="<?= $thumb_zc ?>">
        </div>
      </div>
    </form>
  </section>
<?php } ?>
