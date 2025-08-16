<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= ($id > 0 ? capnhat : themmoi) . ' ' . ($config['product'][$type]['title_main_item'] ?? '') ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" name="<?= !empty($id) ? 'edit' : 'add'; ?>"
        class="btn btn-sm bg-gradient-primary <?= !empty($config['product'][$type]['slug_item']) ? 'submit-check' : '' ?>"
        <?= !empty($config['product'][$type]['slug_item']) ? 'disabled' : '' ?>>
        <i class="far fa-save mr-2"></i><?= luu ?>
      </button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary">
        <i class="fas fa-redo mr-2"></i><?= lamlai ?>
      </button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="<?= thoat ?>"><i class="fas fa-sign-out-alt mr-2"></i><?= thoat ?></a>
    </div>
    <div class="row">
      <div class="col-xl-8">
        <?php if (!empty($config['product'][$type]['slug_item'])): ?>
          <?php include TEMPLATE . LAYOUT . 'slug.php'; ?>
        <?php endif; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title"><?= noidung ?> <?= $config['product'][$type]['title_main_item'] ?></h3>
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
                        <label for="name<?= $k ?>"><?= tieude ?> (<?= $k ?>):</label>
                        <input type="text"
                          class="form-control for-seo text-sm"
                          name="data[name<?= $k ?>]" id="name<?= $k ?>"
                          placeholder="<?= tieude ?> (<?= $k ?>)"
                          value="<?= $_POST['name' . $k] ?? ($result['name' . $k] ?? '') ?>"
                          <?= ($k == $lang) ? 'required' : '' ?> />
                      </div>

                      <!-- Mô tả -->
                      <?php if (!empty($config['product'][$type]['desc_cke_item']) || !empty($config['product'][$type]['desc_item'])): ?>
                        <div class="form-group">
                          <label for="desc<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['product'][$type]['desc_cke_item']) ? 'form-control-ckeditor' : '' ?>"
                            name="data[desc<?= $k ?>]" id="desc<?= $k ?>"
                            placeholder="<?= mota ?> (<?= $k ?>)"><?= $_POST['desc' . $k] ?? ($result['desc' . $k] ?? '') ?></textarea>
                        </div>
                      <?php endif; ?>

                      <!-- Nội dung -->
                      <?php if (!empty($config['product'][$type]['content_cke_item']) || !empty($config['product'][$type]['content_item'])): ?>
                        <div class="form-group">
                          <label for="content<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['product'][$type]['content_cke_item']) ? 'form-control-ckeditor' : '' ?>"
                            name="data[content<?= $k ?>]" id="content<?= $k ?>"
                            placeholder="<?= mota ?> (<?= $k ?>)"><?= $_POST['content' . $k] ?? ($result['content' . $k] ?? '') ?></textarea>
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
            <h3 class="card-title"><?= chondanhmuc ?></h3>
          </div>
          <div class="card-body">
            <div class="form-group-category row">
              <?php if (!empty($config['product'][$type]['list'])) : ?>
                <div class="form-group col-xl-6 col-sm-4">
                  <label class="d-block" for="id_list"><?= danhmuccap1 ?>:</label>
                  <?= $fn->getAjaxCategory('tbl_product_list', $_POST['id_list'] ?? $result['id_list'] ?? '') ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($config['product'][$type]['cat'])) : ?>
                <div class="form-group col-xl-6 col-sm-4">
                  <label class="d-block" for="id_cat"><?= danhmuccap2 ?>:</label>
                  <?= $fn->getAjaxCategory(
                    'tbl_product_cat',
                    $_POST['id_cat'] ?? $result['id_cat'] ?? '',
                    $_POST['id_list'] ?? $result['id_list'] ?? ''
                  ) ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php if (!empty($config['product'][$type]['images_item'])): ?>
          <div class="card card-primary card-outline text-sm">
            <div class="card-header">
              <h3 class="card-title"><?= hinhanh ?> <?= $config['product'][$type]['title_main_item'] ?></h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                    class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <?php
              $photoDetail = array();
              $photoDetail['image'] = $result['file'] ?? '';
              $photoDetail['dimension'] = "Width: " . $config['product'][$type]['width_item'] . " px - Height: " . $config['product'][$type]['height_item'] . " px (" . $config['product'][$type]['img_type_item'] . ")";
              include TEMPLATE . LAYOUT . "image.php"; ?>
            </div>
          </div>
        <?php endif; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title"><?= thongtin ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group">
              <?php foreach ($config['product'][$type]['check_item'] as $check => $label): ?>
                <div class="form-group d-inline-block mb-2 mr-5">
                  <label for="<?= $check ?>-checkbox" class="d-inline-block align-middle mb-0 mr-3 form-label"><?= defined($check) ? constant($check) : $check ?>:</label>
                  <label class="switch switch-success">
                    <input
                      type="checkbox"
                      name="data[status][<?= $check ?>]"
                      class="switch-input custom-control-input .show-checkbox"
                      id="<?= $check ?>-checkbox"
                      <?= $fn->is_checked($check, $result['status'] ?? '', $id ?? '') ?>>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="form-group">
              <label for="numb" class="d-inline-block align-middle mb-0 mr-2"><?= sothutu ?>:</label>
              <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                name="data[numb]" id="numb" placeholder="Số thứ tự" value="<?= $_POST['numb'] ?? (!empty($id) ? $result['numb'] : '1') ?>" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if (!empty($config['product'][$type]['seo_list'])): ?>
      <?php include TEMPLATE . LAYOUT . 'seo.php'; ?>
    <?php endif; ?>
    <input type="hidden" name="data[type]" value="<?= $type ?>">
  </form>
</section>
