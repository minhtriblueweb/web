<section class="content">
  <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary">
        <i class="far fa-save mr-2"></i>Lưu
      </button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary">
        <i class="fas fa-redo mr-2"></i>Làm lại
      </button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Cấu hình <?= $name_page ?></h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <?php foreach ($status as $check => $label): ?>
            <div class="form-group d-inline-block mb-2 mr-5">
              <label for="<?= $check ?>-checkbox" class="d-inline-block align-middle mb-0 mr-3 form-label"><?= $label ?>:</label>
              <label class="switch switch-success">
                <input type="checkbox" name="<?= $check ?>" class="switch-input custom-control-input .show-checkbox" id="<?= $check ?>-checkbox" <?= $fn->is_checked($check, $result['status'] ?? '', $result['id'] ?? '') ?>>
              </label>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="row">
          <div class="col-xl-4">
            <div class="form-group">
              <div class="upload-file">
                <p>Upload hình ảnh:</p>
                <label class="upload-file-label mb-2" for="watermark-file">
                  <div class="upload-file-image rounded mb-3">
                    <div class="d-flex justify-content-center">
                      <div class="border rounded bg-white d-flex align-items-center justify-content-center" style="width:<?= $thumb_width ?>px; height:<?= $thumb_height ?>px;">
                        <?= $fn->getImage(['file' => $result['file'], 'class' => 'img-fluid', 'alt' => $name_page, 'title' => $name_page, 'id' => 'preview-image', 'style' => 'max-height:100%; max-width:100%;']) ?>
                      </div>
                    </div>
                  </div>
                  <div class="custom-file my-custom-file">
                    <input type="file" class="custom-file-input" name="file" id="file" lang="vi">
                    <label class="custom-file-label mb-0" data-browse="Chọn" for="file">Chọn file</label>
                  </div>
                  <strong class="d-block text-sm">
                    Width: <?= $width ?> px - Height: <?= $height ?> px (<?= $img_type_list ?>)
                  </strong>
                </label>
              </div>
            </div>
            <?php if ($type === 'watermark'):
              $position   = (int)($options['position'] ?? 1);
              $per        = (int)$options['per'] ?? '';
              $small_per  = (int)$options['small_per'] ?? '';
              $max        = (int)$options['max'] ?? '';
              $min        = (int)$options['min'] ?? '';
              $opacity    = (int)$options['opacity'] ?? '';
              $offset_x   = (int)$options['offset_x'] ?? '';
              $offset_y   = (int)$options['offset_y'] ?? '';
            ?>
              <div class="form-group">
                <label>Vị trí đóng dấu:</label>
                <div class="watermark-position rounded">
                  <?php for ($i = 1; $i <= 9; $i++): ?>
                    <label class="<?= ($i === $position) ? 'active' : '' ?>">
                      <input type="radio" name="position" value="<?= $i ?>" <?= ($i === $position) ? 'checked' : '' ?>>
                      <?= $fn->getImage([
                        'file' => ($i === $position) ? $result['file'] : '',
                        'class' => 'rounded',
                        'alt'   => 'Watermark Position ' . $i,
                        'title' => 'Watermark Position ' . $i,
                      ]) ?>
                    </label>
                  <?php endfor; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
          <?php if ($type === 'watermark'): ?>
            <div class="col-xl-8">
              <div class="form-group">
                <label for="opacity">Độ trong suốt:</label>
                <input type="number" class="form-control" id="opacity" name="opacity" min="0" max="100" value="<?= $opacity ?>">
                <p class="text-danger mt-1 small">Giá trị từ 0 - 100</p>
              </div>

              <div class="form-group">
                <label for="per">Tỉ lệ:</label>
                <input type="text" class="form-control text-sm" id="per" name="per" value="<?= $per ?>" placeholder="2">
              </div>

              <div class="form-group">
                <label for="small_per">Tỉ lệ &lt; 300px:</label>
                <input type="text" class="form-control text-sm" id="small_per" name="small_per" value="<?= $small_per ?>" placeholder="3">
              </div>

              <div class="form-group">
                <label for="max">Kích thước tối đa:</label>
                <input type="text" class="form-control text-sm" id="max" name="max" value="<?= $max ?>" placeholder="600">
              </div>

              <div class="form-group">
                <label for="min">Kích thước tối thiểu:</label>
                <input type="text" class="form-control text-sm" id="min" name="min" value="<?= $min ?>" placeholder="100">
              </div>

              <div class="form-group">
                <label for="offset_x">Độ lệch X:</label>
                <input type="text" class="form-control" id="offset_x" name="offset_x" value="<?= $offset_x ?>">
              </div>

              <div class="form-group">
                <label for="offset_y">Độ lệch Y:</label>
                <input type="text" class="form-control" id="offset_y" name="offset_y" value="<?= $offset_y ?>">
              </div>

              <div class="mt-2">
                <p class="text-danger mb-0">
                  <strong><i class="ti ti-exclamation-circle ms-1"></i> Lưu ý:</strong> Cần xóa cache nếu có thay đổi watermark
                </p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <input type="hidden" name="width" value="<?= $width ?>">
    <input type="hidden" name="height" value="<?= $height ?>">
    <input type="hidden" name="zc" value="<?= $zc ?>">
  </form>
</section>
