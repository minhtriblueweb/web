<?php
$redirect_url = $_GET['page'] ?? '';
$type = $_GET['type'] ?? '';
if (!empty($type) && !empty($fn->convert_type($type))) {
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
} else {
  $name_page = "setting";
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
                        <?= $fn->getImage([
                          'file' => !empty($result[$type]) ? $result[$type] : '',
                          'class' => 'img-fluid',
                          'id'    => 'preview-image',
                          'style' => 'max-height:100%; max-width:100%;',
                          'alt'   => $type,
                          'title' => $type
                        ]) ?>
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
<?php } else { ?>
  <?php
  $get_setting = $setting->get_setting();
  if ($get_setting) {
    $result = $get_setting->fetch_assoc();
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $update = $setting->update_setting($_POST);
  }
  ?>
  <!-- Main content -->
  <section class="content">
    <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
      <div class="card-footer text-sm sticky-top">
        <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i
            class="far fa-save mr-2"></i>Lưu</button>
        <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
      </div>
      <div class="card card-primary card-outline text-sm">
        <div class="card-header">
          <h3 class="card-title">Thông tin chung</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="form-group col-md-4 col-sm-6">
              <label for="email">Email:</label>
              <input type="email" class="form-control text-sm" name="email" id="email" placeholder="Email"
                value="<?= isset($result['email']) ? htmlspecialchars($result['email']) : ''; ?>">
            </div>
            <div class="form-group col-md-4 col-sm-6">
              <label for="hotline">Hotline tư vấn:</label>
              <input type="text" class="form-control text-sm" name="hotline" id="hotline" placeholder="Hotline tư vấn"
                value="<?= isset($result['hotline']) ? htmlspecialchars($result['hotline']) : ''; ?>">
            </div>
            <div class="form-group col-md-4 col-sm-6">
              <label for="web_name">Tên Website:</label>
              <input type="text" class="form-control text-sm" name="web_name" id="web_name" placeholder="Tên Website"
                value="<?= isset($result['web_name']) ? htmlspecialchars($result['web_name']) : ''; ?>">
            </div>
            <div class="form-group col-md-4 col-sm-6">
              <label for="introduction">Lời giới thiệu:</label>
              <input type="text" class="form-control text-sm" name="introduction" id="introduction"
                placeholder="Lời giới thiệu"
                value="<?= isset($result['introduction']) ? htmlspecialchars($result['introduction']) : ''; ?>">
            </div>

            <div class="form-group col-md-4 col-sm-6">
              <label for="fanpage">Fanpage:</label>
              <input type="text" class="form-control text-sm" name="fanpage" id="fanpage" placeholder="Fanpage"
                value="<?= isset($result['fanpage']) ? htmlspecialchars($result['fanpage']) : ''; ?>">
            </div>
            <div class="form-group col-md-4 col-sm-6">
              <label for="copyright">Copyright:</label>
              <input type="text" class="form-control text-sm" name="copyright" id="copyright" placeholder="Copyright"
                value="<?= isset($result['copyright']) ? htmlspecialchars($result['copyright']) : ''; ?>">
            </div>
            <div class="form-group col-md-4 col-sm-6">
              <label for="link_googlemaps">Link google maps:</label>
              <input type="text" class="form-control text-sm" name="link_googlemaps" id="link_googlemaps"
                placeholder="Link google maps"
                value="<?= isset($result['link_googlemaps']) ? htmlspecialchars($result['link_googlemaps']) : ''; ?>">
            </div>
            <div class="form-group col-md-4 col-sm-6">
              <label for="coords">Tọa độ google map:</label>
              <input type="text" class="form-control text-sm" name="coords" id="coords" placeholder="Tọa độ google map"
                value="<?= isset($result['coords']) ? htmlspecialchars($result['coords']) : ''; ?>">
            </div>
            <div class="form-group col-md-4 col-sm-6">
              <label for="worktime">Giờ làm việc:</label>
              <input type="text" class="form-control text-sm" name="worktime" id="worktime" placeholder="Giờ làm việc"
                value="<?= isset($result['worktime']) ? htmlspecialchars($result['worktime']) : ''; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="desc">Mô tả:</label>
            <textarea class="form-control text-sm" name="desc" id="desc" rows="5"
              placeholder="Mô tả"><?= isset($result['desc']) ? htmlspecialchars($result['desc']) : ''; ?></textarea>
          </div>
          <div class="form-group">
            <label for="coords_iframe">
              <span>Tọa độ google map iframe:</span>
              <a class="text-sm font-weight-normal ml-1" href="https://www.google.com/maps" target="_blank"
                title="Lấy mã nhúng google map">(Lấy mã nhúng)</a>
            </label>
            <textarea class="form-control text-sm" name="coords_iframe" id="coords_iframe" rows="5"
              placeholder="Tọa độ google map iframe"><?= isset($result['coords_iframe']) ? htmlspecialchars($result['coords_iframe']) : ''; ?></textarea>
          </div>
          <div class="form-group">
            <label for="analytics">Google analytics:</label>
            <textarea class="form-control text-sm" name="analytics" id="analytics" rows="5"
              placeholder="Google analytics"><?= isset($result['analytics']) ? htmlspecialchars($result['analytics']) : ''; ?></textarea>
          </div>
          <div class="form-group">
            <label for="headjs">Head JS:</label>
            <textarea class="form-control text-sm" name="headjs" id="headjs" rows="5"
              placeholder="Head JS"><?= isset($result['headjs']) ? htmlspecialchars($result['headjs']) : ''; ?></textarea>
          </div>
          <div class="form-group">
            <label for="bodyjs">Body JS:</label>
            <textarea class="form-control text-sm" name="bodyjs" id="bodyjs" rows="5"
              placeholder="Body JS"><?= isset($result['bodyjs']) ? htmlspecialchars($result['bodyjs']) : ''; ?></textarea>
          </div>
        </div>
      </div>
    </form>
  </section>
<?php } ?>
