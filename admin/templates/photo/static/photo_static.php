<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= $config['photo']['photo_static'][$type]['title_main'] ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary">
        <i class="far fa-save mr-2"></i><?= luu ?>
      </button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary">
        <i class="fas fa-redo mr-2"></i><?= lamlai ?>
      </button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title"><?= cauhinh ?> <?= $config['photo']['photo_static'][$type]['title_main'] ?></h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <?php
          $status_array = !empty($item['status']) ? explode(',', $item['status']) : [];
          if (!empty($config['photo']['photo_static'][$type]['status'])) {
            foreach ($config['photo']['photo_static'][$type]['status'] as $key => $value) {
              echo $func->is_checked($key, $value, $status_array, $item['id'] ?? null);
            }
          }
          ?>
        </div>
        <div class="row">
          <div class="<?= ($type === 'watermark') ? 'col-xl-4' : 'col-xl-12' ?>">
            <div class="form-group">
              <div class="upload-file">
                <p><?= uploadhinhanh ?>:</p>
                <label class="upload-file-label mb-2" for="<?= $type ?>-file">
                  <div class="upload-file-image rounded mb-3">
                    <div class="d-flex justify-content-center">
                      <div class="border rounded bg-white d-flex align-items-center justify-content-center">
                        <?= $func->getImage(['file' => $item['file'] ?? '', 'class' => 'img-fluid', 'alt' => $config['photo']['photo_static'][$type]['title_main'], 'title' => $config['photo']['photo_static'][$type]['title_main'], 'id' => 'preview-image', 'style' => 'max-height:100%; max-width:100%;']) ?>
                      </div>
                    </div>
                  </div>
                  <div class="custom-file my-custom-file">
                    <input type="file" class="custom-file-input" name="file" id="file" lang="vi">
                    <label class="custom-file-label mb-0" data-browse="<?= chon ?>" for="file"><?= chonfile ?></label>
                  </div>
                  <strong class="d-block text-sm">
                    Width: <?= $config['photo']['photo_static'][$type]['width'] ?> px - Height: <?= $config['photo']['photo_static'][$type]['height'] ?> px (<?= $config['photo']['photo_static'][$type]['img_type'] ?>)
                  </strong>
                </label>
              </div>
            </div>
            <?php if ($type === 'watermark'):
              $position   = (int)($options['position'] ?? 1);
              $per        = (int)$options['per'] ?? 40;
              $small_per  = (int)$options['small_per'] ?? 50;
              $max        = (int)$options['max'] ?? 500;
              $min        = (int)$options['min'] ?? 100;
              $opacity    = (int)$options['opacity'] ?? 100;
              $offset_x   = (int)$options['offset_x'] ?? 0;
              $offset_y   = (int)$options['offset_y'] ?? 0;
            ?>
              <div class="form-group">
                <label><?= vitridongdau ?>:</label>
                <div class="watermark-position rounded">
                  <?php for ($i = 1; $i <= 9; $i++): ?>
                    <label class="<?= ($i === $position) ? 'active' : '' ?>">
                      <input type="radio" name="data[options][position]" value="<?= $i ?>" <?= ($i === $position) ? 'checked' : '' ?>>
                      <?= $func->getImage([
                        'file' => ($i === $position) ? $item['file'] : '',
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
                <input type="number" class="form-control" id="opacity" name="data[options][opacity]" min="0" max="100" value="<?= $opacity ?>">
                <p class="text-danger mt-1 small">Giá trị từ 0 - 100</p>
              </div>

              <div class="form-group">
                <label for="per">Tỉ lệ:</label>
                <input min="0" max="100" type="number" class="form-control text-sm" id="per" name="data[options][per]" value="<?= $per ?>" placeholder="2">
              </div>

              <div class="form-group">
                <label for="small_per">Tỉ lệ &lt; 300px:</label>
                <input min="0" max="800" type="number" class="form-control text-sm" id="small_per" name="data[options][small_per]" value="<?= $small_per ?>" placeholder="3">
              </div>

              <div class="form-group">
                <label for="max">Kích thước tối đa:</label>
                <input min="0" max="800" type="number" class="form-control text-sm" id="max" name="data[options][max]" value="<?= $max ?>" placeholder="600">
              </div>

              <div class="form-group">
                <label for="min">Kích thước tối thiểu:</label>
                <input min="0" max="800" type="number" class="form-control text-sm" id="min" name="data[options][min]" value="<?= $min ?>" placeholder="100">
              </div>

              <div class="form-group">
                <label for="offset_x">Độ lệch X:</label>
                <input min="0" max="800" type="number" class="form-control" id="offset_x" name="data[options][offset_x]" value="<?= $offset_x ?>">
              </div>

              <div class="form-group">
                <label for="offset_y">Độ lệch Y:</label>
                <input min="0" max="800" type="number" class="form-control" id="offset_y" name="data[options][offset_y]" value="<?= $offset_y ?>">
              </div>

              <div class="mt-2">
                <p class="text-danger mb-0">
                  <strong><i class="ti ti-exclamation-circle ms-1"></i> Lưu ý: </strong>Cần xóa cache nếu có thay đổi watermark
                </p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </form>
</section>
