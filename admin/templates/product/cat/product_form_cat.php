<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= ($id > 0 ? 'Cập nhật ' : 'Thêm mới ') . ($config['product'][$type]['title_main_cat'] ?? '') ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
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
    <div class="row">
      <div class="col-xl-8">
        <?php if (!empty($config['product'][$type]['slug_cat'])): ?>
          <?php include TEMPLATE . LAYOUT . 'slug.php'; ?>
        <?php endif; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Nội dung <?= $config['product'][$type]['title_main_cat'] ?></h3>
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
                      <a class="nav-link <?= ($k == $lang) ? 'active' : '' ?>"
                        id="tabs-lang-article-<?= $k ?>"
                        data-toggle="pill" role="tab"
                        href="#tabs-content-article-<?= $k ?>"
                        aria-controls="tabs-content-article-<?= $k ?>"
                        aria-selected="<?= ($k == $lang) ? 'true' : 'false' ?>">
                        <?= $v ?>
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              </div>
              <div class="card-body card-article">
                <div class="tab-content" id="custom-tabs-three-tabContent-lang">
                  <?php foreach ($config['website']['lang'] as $k => $v) { ?>
                    <div class="tab-pane fade show <?= ($k == $lang) ? 'active' : '' ?>"
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
                          value="<?= $_POST['name' . $k] ?? ($result['name' . $k] ?? '') ?>" <?= ($k == $lang) ? 'required' : '' ?> />
                      </div>

                      <!-- Mô tả -->
                      <?php if (!empty($config['product'][$type]['desc_cke_cat']) || !empty($config['product'][$type]['desc_cat'])): ?>
                        <div class="form-group">
                          <label for="desc<?= $k ?>">Mô tả (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['product'][$type]['desc_cke_cat']) ? 'form-control-ckeditor' : '' ?>"
                            name="desc<?= $k ?>" id="desc<?= $k ?>"
                            placeholder="Mô tả (<?= $k ?>)"><?= $_POST['desc' . $k] ?? ($result['desc' . $k] ?? '') ?></textarea>
                        </div>
                      <?php endif; ?>

                      <!-- Nội dung -->
                      <?php if (!empty($config['product'][$type]['content_cke_cat']) || !empty($config['product'][$type]['content_cat'])): ?>
                        <div class="form-group">
                          <label for="content<?= $k ?>">Mô tả (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['product'][$type]['content_cke_cat']) ? 'form-control-ckeditor' : '' ?>"
                            name="content<?= $k ?>" id="content<?= $k ?>"
                            placeholder="Mô tả (<?= $k ?>)"><?= $_POST['content' . $k] ?? ($result['content' . $k] ?? '') ?></textarea>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Chọn danh mục cấp 1</h3>
          </div>
          <div class="card-body">
            <div class="form-group-category">
              <?= $fn->getAjaxCategory('tbl_product_list', $_POST['id_list'] ?? $result['id_list'] ?? '') ?>
            </div>
          </div>
        </div>
        <?php if (!empty($config['product'][$type]['images_cat'])): ?>
          <div class="card card-primary card-outline text-sm">
            <div class="card-header">
              <h3 class="card-title">Hình ảnh <?= $config['product'][$type]['title_main_cat'] ?></h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                    class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <?php
              $photoDetail = array();
              $photoDetail['image'] = $result['file'] ?? '';
              $photoDetail['dimension'] = "Width: " . $config['product'][$type]['width_cat'] . " px - Height: " . $config['product'][$type]['height_cat'] . " px (" . $config['product'][$type]['img_type_cat'] . ")";
              include TEMPLATE . LAYOUT . "image.php"; ?>
            </div>
          </div>
        <?php endif; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Thông tin</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group">
              <?php foreach ($config['product'][$type]['check_cat'] as $check => $label): ?>
                <div class="form-group d-inline-block mb-2 mr-5">
                  <label for="<?= $check ?>-checkbox" class="d-inline-block align-middle mb-0 mr-3 form-label"><?= $label ?>:</label>
                  <label class="switch switch-success">
                    <input
                      type="checkbox"
                      name="<?= $check ?>"
                      class="switch-input custom-control-input .show-checkbox"
                      id="<?= $check ?>-checkbox"
                      <?= $fn->is_checked($check, $result['status'] ?? '', $id ?? '') ?>>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="form-group">
              <label for="numb" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
              <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                name="numb" id="numb" placeholder="Số thứ tự" value="<?= $_POST['numb'] ?? (!empty($id) ? $result['numb'] : '1') ?>" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if (!empty($config['product'][$type]['seo_list'])): ?>
      <?php include TEMPLATE . LAYOUT . 'seo.php'; ?>
    <?php endif; ?>
    <input type="hidden" name="type" value="<?= $type ?>">
  </form>
</section>
