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
            <label class="upload-file-label mb-2" for="watermark-file">
              <div class="upload-file-image rounded mb-3">
                <div class="d-flex justify-content-center">
                  <div class="border rounded bg-white d-flex align-items-center justify-content-center" .style="width:<?= $width ?>px; height:<?= $height ?>px;">
                    <?= $fn->getImage(['file' => $result['file'] ?? '', 'class' => 'img-fluid', 'alt' => $name_page, 'title' => $name_page, 'id' => 'preview-image']) ?>
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
        <div class="form-group">
          <label for="link0">Link:</label>
          <input type="text" class="form-control text-sm" name="link" id="link0" placeholder="Link" value="<?= $_POST['link'] ?? (!empty($id) ? $result['link'] : '') ?>">
        </div>
        <div class="form-group">
          <?php foreach ($status as $check => $label): ?>
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
    <input type="hidden" name="type" value="<?= $type ?>">
  </form>
</section>
