<?php
$id        = $_GET['id'] ?? null;
$id_child  = $_GET['id_child'] ?? null;
$name_page = 'hình ảnh sản phẩm';
$linkMan   = 'index.php?page=gallery_man&id=' . $id;
$thumb_width = 500;
$thumb_height = 500;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add']) && $id) {
  $insert = $product->them_gallery($_POST, $_FILES, $id);
}
if (
  $id_child &&
  ($result = $db->rawQueryOne("SELECT * FROM tbl_gallery WHERE id = ? LIMIT 1", [$id_child])) &&
  ($id_parent = $result['id_parent'] ?? null) &&
  $_SERVER['REQUEST_METHOD'] === 'POST' &&
  isset($_POST['edit'])
) {
  $update = $product->upload_gallery($_POST, $_FILES, $id_child, $id_parent);
}
$breadcrumb = [['label' => (!empty($id) ? 'Thêm mới ' : 'Cập nhật ') . $name_page]];
include TEMPLATE . 'breadcrumb.php';
?>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" name="<?= !empty($id) ? "add" : "edit"; ?>"
        class="btn btn-sm bg-gradient-primary">
        <i class="far fa-save mr-2"></i>Lưu
      </button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
    </div>
    <div class="row">
      <?php if (!empty($id)) : ?>
        <?php for ($i = 0; $i < 6; $i++) { ?>
          <div class="col-sm-4 col-6">
            <div class="card card-primary card-outline text-sm">
              <div class="card-header">
                <h3 class="card-title"><?= $name_page ?> <?= $i + 1; ?>: </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="upload-file">
                    <p>Upload hình ảnh:</p>
                    <div class="photoUpload-zone w-100">
                      <div class="photoUpload-detail" id="photoUpload-preview<?= $i; ?>">
                        <img class="rounded" src="<?= NO_IMG ?>" alt="Alt Photo" />
                      </div>
                      <label class="photoUpload-file" id="photo-zone<?= $i; ?>" for="file-zone<?= $i; ?>">
                        <input type="file" name="file<?= $i; ?>" id="file-zone<?= $i; ?>" />
                        <p class="photoUpload-choose btn btn-sm bg-gradient-success">
                          Chọn hình
                        </p>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-group d-inline-block mb-2 mr-2">
                    <label for="hienthi-checkbox<?= $i; ?>" class="d-inline-block align-middle mb-0 mr-2">Hiển thị:</label>
                    <div class="custom-control custom-checkbox d-inline-block align-middle">
                      <input type="checkbox" class="custom-control-input" name="hienthi<?= $i; ?>"
                        id="hienthi-checkbox<?= $i; ?>" value="hienthi" checked="">
                      <label for="hienthi-checkbox<?= $i; ?>" class="custom-control-label"></label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="numb<?= $i; ?>" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
                  <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                    name="numb<?= $i; ?>" id="numb<?= $i; ?>" placeholder="Số thứ tự" value="<?= $i + 1; ?>">
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      <?php else : ?>
        <div class="col-12">
          <div class="card card-primary card-outline text-sm">
            <div class="card-header">
              <h3 class="card-title"><?= $name_page ?>:</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <div class="upload-file">
                  <p>Upload hình ảnh:</p>
                  <div class="photoUpload-zone w-25">
                    <div class="photoUpload-detail" id="photoUpload-preview">
                      <a data-fancybox href="">
                        <?= $fn->getImage([
                          'file' => $result['file'],
                          'class' => 'rounded',
                          'alt' => 'Alt Photo',
                        ]) ?>
                      </a>
                    </div>
                    <label class="photoUpload-file" id="photo-zone" for="file-zone">
                      <input type="file" name="file" id="file-zone" />
                      <i class="fas fa-cloud-upload-alt"></i>
                      <p class="photoUpload-drop">Kéo và thả hình vào đây</p>
                      <p class="photoUpload-or">hoặc</p>
                      <p class="photoUpload-choose btn btn-sm bg-gradient-success">
                        Chọn hình
                      </p>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <?php
                $checkboxes = [
                  'hienthi' => 'Hiển thị',
                ];
                ?>
                <?php foreach ($checkboxes as $check => $label): ?>
                  <div class="form-group d-inline-block mb-2 mr-5">
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
                <label for="numb0" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
                <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                  name="numb" id="numb0" placeholder="Số thứ tự" value="<?= $_POST['numb'] ?? (!empty($id_child) ? $result['numb'] : '1') ?>">
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <input type="hidden" name="thumb_width" value="<?= $thumb_width ?>">
    <input type="hidden" name="thumb_height" value="<?= $thumb_height ?>">
  </form>
</section>
