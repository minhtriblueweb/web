<?php
$get_watermark = $setting->get_watermark();
$thumb_width = '300';
$thumb_height = '120';
if ($get_watermark) {
  $result = $get_watermark->fetch_assoc();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $update = $setting->update_watermark($_POST, $_FILES);
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

  <!-- FORM -->
  <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary submit-check">
        <i class="far fa-save mr-2"></i>Lưu
      </button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary">
        <i class="fas fa-redo mr-2"></i>Làm lại
      </button>
    </div>

    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Chi tiết watermark</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-xl-4">
            <div class="form-group">
              <div class="upload-file">
                <p>Upload hình ảnh:</p>
                <label class="upload-file-label mb-2" for="file">
                  <div class="upload-file-image rounded mb-3">
                    <div class="d-flex justify-content-center">
                      <div class="d-flex justify-content-center">
                        <div class="border rounded bg-white d-flex align-items-center justify-content-center" style="width:<?= $thumb_width ?>px; height:<?= $thumb_height ?>px;">
                          <?= $fn->getImage([
                            'file' => $result['watermark'],
                            'class' => 'img-fluid',
                            'alt'   => 'Watermark',
                            'title' => 'Watermark',
                            'id'    => 'preview-image',
                            'style' => 'max-height:100%; max-width:100%;'
                          ]) ?>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="custom-file my-custom-file">
                    <input type="file" class="custom-file-input" name="watermark" id="file" lang="vi">
                    <label class="custom-file-label mb-0" data-browse="Chọn" for="file">Chọn file</label>
                  </div>
                </label>
              </div>
            </div>
            <div class="form-group col-12">
              <label>Vị trí đóng dấu:</label>
              <div class="watermark-position rounded">
                <?php
                $selected_position = $result['position'];
                for ($i = 1; $i <= 9; $i++):
                ?>
                  <label class="<?= ($i == $selected_position) ? 'active' : '' ?>">
                    <input type="radio" name="position" value="<?= $i ?>"
                      <?= ($i == $selected_position) ? 'checked' : '' ?>>
                    <img class="rounded" onerror="this.src='<?= NO_IMG ?>';"
                      src="<?= ($i == $selected_position) ? $watermark_src : NO_IMG ?>"
                      alt="Watermark Position <?= $i ?>">
                  </label>
                <?php endfor; ?>
              </div>
            </div>
          </div>
          <div class="col-xl-8 ">
            <div class="form-group col-xl-12 col-sm-4">
              <label>Độ trong suốt :</label>
              <input type="number" class="form-control" id="opacity" name="opacity" placeholder="0 - 100" min="0"
                max="100" value="<?= $result['opacity'] ?>" />
              <p class="text-danger mt-1 small">Độ trong suốt có giá trị từ 0 - 100</p>
            </div>
            <div class="form-group col-xl-12 col-sm-4">
              <label>Tỉ lệ:</label>
              <input type="text" class="form-control text-sm" name="per" placeholder="2" value="<?= $result['per'] ?>">
            </div>
            <div class="form-group col-xl-12 col-sm-4">
              <label>Tỉ lệ &lt; 300px:</label>
              <input type="text" class="form-control text-sm" name="small_per" placeholder="3" value="<?= $result['small_per'] ?>">
            </div>
            <div class="form-group col-xl-12 col-sm-4">
              <label>Kích thước tối đa:</label>
              <input type="text" class="form-control text-sm" name="max" placeholder="600" value="<?= $result['max'] ?>">
            </div>
            <div class="form-group col-xl-12 col-sm-4">
              <label>Kích thước tối thiểu:</label>
              <input type="text" class="form-control text-sm" name="min" placeholder="100" value="<?= $result['min'] ?>">
            </div>
            <div class="mb-3 col-md-12">
              <label for="offset_x" class="form-label">Độ lệch tương đối tùy chọn của hình ảnh mới trên trục x</label>
              <input type="text" class="form-control" id="offset_x" name="offset_x"
                value="<?= $result['offset_x'] ?>" />
            </div>
            <div class="col-md-12">
              <label for="offset_y" class="form-label">Độ lệch tương đối tùy chọn của hình ảnh mới trên trục y</label>
              <input type="text" class="form-control" id="offset_y" name="offset_y"
                value="<?= $result['offset_y'] ?>" />
            </div>
            <div class="col-md-12 mt-2">
              <p class="text-danger mb-0"><strong><i class="ti ti-exclamation-circle ms-1"></i> Lưu ý:</strong> Cần xóa dữ liệu cache khi có sự thay đổi về giá trị của watermark</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="thumb_width" value="<?= $thumb_width ?>">
    <input type="hidden" name="thumb_height" value="<?= $thumb_height ?>">
  </form>
</section>
