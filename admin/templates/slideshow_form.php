<?php
$message = '';
$name_page = 'slideshow';
$table = 'tbl_slideshow';
$thumb_width = '1366';
$thumb_height = '600';
$id = $_GET['id'] ?? null;
if (!empty($id)) {
  $get_id = $fn->get_id($table, $id);
  if ($get_id) {
    $result = $get_id->fetch_assoc();
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
  $message = $slideshow->save_slideshow($_POST, $_FILES, $id);
}
$linkMan = "index.php?page=slideshow_list";
?>
<?php
$breadcrumb = [
  ['label' => (!empty($id) ? 'Cập nhật ' : 'Thêm mới ') . $name_page]
];
include TEMPLATE . 'breadcrumb.php';
?>
<section class="content">
  <form class="validation-form" novalidate="" method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" name="<?= !empty($id) ? "edit" : "add"; ?>"
        class="btn btn-sm bg-gradient-primary submit-check" disabled>
        <i class="far fa-save mr-2"></i>Lưu
      </button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary">
        <i class="fas fa-redo mr-2"></i>Làm lại
      </button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Nội dung <?= $name_page ?></h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <div class="upload-file">
            <p>Upload hình ảnh:</p>
            <?php include TEMPLATE . "image.php"; ?>
          </div>
        </div>
        <div class="form-group">
          <label for="link0">Link:</label>
          <input type="text" class="form-control text-sm" name="link" id="link0" placeholder="Link" value="">
        </div>
        <div class="form-group">
          <?php
          $checkboxes = [
            'hienthi' => 'Hiển thị',
          ];
          ?>
          <?php foreach ($checkboxes as $check => $label): ?>
            <div class="form-group d-inline-block mb-2 mr-4">
              <label for="<?= $check ?>-checkbox" class="d-inline-block align-middle mb-0 mr-3 form-label"><?= $label ?>:</label>
              <label class="switch switch-success">
                <input
                  type="checkbox"
                  name="<?= $check ?>"
                  class="switch-input custom-control-input .show-checkbox"
                  id="<?= $check ?>-checkbox"
                  <?= $fn->is_checked($check, $result['status'] ?? '', $id ?? '') ?>>
                <span class="switch-toggle-slider">
                  <span class="switch-on"><i class="fa-solid fa-check"></i></span>
                  <span class="switch-off"><i class="fa-solid fa-xmark"></i></span>
                </span>
              </label>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="form-group">
          <label for="numb" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
          <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
            name="numb" id="numb" placeholder="Số thứ tự" value="<?= $_POST['numb'] ?? (!empty($id) ? $result['numb'] : '1') ?>">
        </div>
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
          <div class="card-body">
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
                      value="<?= $_POST['name' . $k] ?? ($result['name' . $k] ?? '') ?>" />
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="thumb_width" value="<?= $thumb_width ?>">
    <input type="hidden" name="thumb_height" value="<?= $thumb_height ?>">
  </form>
</section>
