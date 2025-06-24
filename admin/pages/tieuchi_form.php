<?php
$message = '';
$name_page = 'tiêu chí';
$table = 'tbl_tieuchi';
$id = $_GET['id'] ?? null;
$thumb_width = '40';
$thumb_height = '40';
$thumb_zc = '1';
$result = [];
if (!empty($id)) {
  $get_id = $fn->get_id($table, $id);
  if ($get_id) {
    $result = $get_id->fetch_assoc();
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
  $message = $tieuchi->save_tieuchi($_POST, $_FILES, $id);
}
?>
<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => 'index.php'],
  ['label' => !empty($id) ? 'Cập nhật ' . $name_page : 'Thêm mới ' . $name_page]
];
include 'templates/breadcrumb.php';
?>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <?php include 'templates/act.php'; ?>
    <div class="row">
      <div class="col-xl-8">
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Nội dung <?= $name_page ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
                  <?php foreach ($config['website']['lang'] as $k => $v) { ?>
                    <li class="nav-item">
                      <a class="nav-link <?= ($k == 'vi') ? 'active' : '' ?>"
                        id="tabs-lang-article-<?= $k ?>"
                        data-toggle="pill"
                        href="#tabs-content-article-<?= $k ?>"
                        role="tab"
                        aria-controls="tabs-content-article-<?= $k ?>"
                        aria-selected="<?= ($k == 'vi') ? 'true' : 'false' ?>">
                        <?= $v ?>
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              </div>
              <div class="card-body card-article">
                <div class="tab-content" id="custom-tabs-three-tabContent-lang">
                  <?php foreach ($config['website']['lang'] as $k => $v) { ?>
                    <div class="tab-pane fade show <?= ($k == 'vi') ? 'active' : '' ?>"
                      id="tabs-content-article-<?= $k ?>"
                      role="tabpanel"
                      aria-labelledby="tabs-lang-article-<?= $k ?>">
                      <!-- Tiêu đề -->
                      <div class="form-group">
                        <label for="name<?= $k ?>">Tiêu đề (<?= $k ?>):</label>
                        <input type="text"
                          class="form-control for-seo text-sm"
                          name="name<?= $k ?>" id="name<?= $k ?>"
                          placeholder="Tiêu đề (<?= $k ?>)"
                          value="<?= $_POST['name' . $k] ?? ($result['name' . $k] ?? '') ?>"
                          <?= ($k == 'vi') ? 'required' : '' ?> />
                      </div>

                      <!-- Mô tả -->
                      <div class="form-group">
                        <label for="desc<?= $k ?>">Mô tả (<?= $k ?>):</label>
                        <textarea class="form-control for-seo text-sm"
                          name="desc<?= $k ?>" id="desc<?= $k ?>"
                          rows="4" placeholder="Mô tả (<?= $k ?>)"><?= $_POST['desc' . $k] ?? ($result['desc' . $k] ?? '') ?></textarea>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Thông tin <?= $name_page ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group">
              <?php
              $checkboxes = [
                'hienthi' => 'Hiển thị',
              ];
              ?>
              <?php foreach ($checkboxes as $check => $label): ?>
                <div class="form-group d-inline-block mb-2 mr-2">
                  <label for="<?= $check ?>-checkbox" class="d-inline-block align-middle mb-0 mr-2"><?= $label ?>:</label>
                  <div class="custom-control custom-checkbox d-inline-block align-middle">
                    <input <?= $fn->is_checked($check, $result ?? null, $id ?? null) ?>
                      type="checkbox"
                      class="custom-control-input <?= $check ?>-checkbox"
                      name="<?= $check ?>"
                      id="<?= $check ?>-checkbox"
                      value="<?= $check ?>" />
                    <label for="<?= $check ?>-checkbox" class="custom-control-label"></label>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="form-group">
              <label for="numb" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
              <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                name="numb" id="numb" placeholder="Số thứ tự" value="<?= $_POST['numb'] ?? (!empty($id) ? $result['numb'] : '1') ?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Hình ảnh <?= $name_page ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="photoUpload-zone">
              <div class="photoUpload-detail" id="photoUpload-preview">
                <img src="<?= empty($result['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $result['file']; ?>"
                  alt='Alt Photo' class='rounded' />
              </div>
              <label class="photoUpload-file" id="photo-zone" for="file-zone">
                <input type="file" name="file" id="file-zone">
                <i class="fas fa-cloud-upload-alt"></i>
                <p class="photoUpload-drop">Kéo và thả hình vào đây</p>
                <p class="photoUpload-or">hoặc</p>
                <p class="photoUpload-choose btn btn-sm bg-gradient-success">Chọn hình</p>
              </label>
              <div class="photoUpload-dimension">
                Width: <?= $thumb_width ?> px - Height: <?= $thumb_height ?> px
                (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="thumb_width" value="<?= $thumb_width ?>">
    <input type="hidden" name="thumb_height" value="<?= $thumb_height ?>">
    <input type="hidden" name="thumb_zc" value="<?= $thumb_zc ?>">
  </form>
</section>
