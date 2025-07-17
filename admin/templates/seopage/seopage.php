<section class="content">
  <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary .submit-check"><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Thông tin SEO page - <?= $config['seopage']['page'][$type] ?></h3>
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
                <?= $fn->getImage(['file' => $file, 'class' => 'img-fluid', 'id' => 'preview-image', 'style' => 'max-height:100%; max-width:100%;', 'alt'   => $config['seopage']['page'][$type], 'title' => $config['seopage']['page'][$type]]) ?>
              </div>
            <?php endif; ?>

            <label class="upload-file-label mb-2 mt-3" for="file">
              <div class="custom-file my-custom-file">
                <input type="file" class="custom-file-input" name="file" id="file" lang="vi">
                <label class="custom-file-label mb-0" data-browse="Chọn" for="file">Chọn file</label>
              </div>
            </label>
            <strong class="d-block text-sm"> Width: <?= $config['seopage']['width'] ?> px - Height: <?= $config['seopage']['height'] ?> px (<?= $config['seopage']['img_type'] ?>)</strong>
          </div>
        </div>
        <?php include TEMPLATE . LAYOUT . 'seo.php'; ?>
        <input type="hidden" name="type" id="type" value="<?= $type ?>">
        <input type="hidden" name="width" value="<?= $config['seopage']['width'] ?>">
        <input type="hidden" name="height" value="<?= $config['seopage']['height'] ?>">
        <input type="hidden" name="zc" value="<?= isset($config['seopage']['thumb']) && ($parts = explode('x', $config['seopage']['thumb'])) && isset($parts[2]) ? (int)$parts[2] : 1 ?>">
      </div>
    </div>
  </form>
</section>
