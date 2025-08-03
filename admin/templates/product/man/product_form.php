<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= ($id > 0 ? capnhat : themmoi) . ' ' . ($config['product'][$type]['title_main'] ?? '') ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" name="<?= !empty($id) ? 'edit' : 'add'; ?>"
        class="btn btn-sm bg-gradient-primary <?= !empty($config['product'][$type]['slug']) ? 'submit-check' : '' ?>"
        <?= !empty($config['product'][$type]['slug']) ? 'disabled' : '' ?>>
        <i class="far fa-save mr-2"></i><?= luu ?>
      </button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary">
        <i class="fas fa-redo mr-2"></i><?= lamlai ?>
      </button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="<?= thoat ?>"><i class="fas fa-sign-out-alt mr-2"></i><?= thoat ?></a>
    </div>
    <div class="card bg-gradient-danger" style="display: none;">
      <div class="card-header">
        <h3 class="card-title"><?= thongbao ?></h3>
        <div class="card-tools"><button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button><button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button></div>
      </div>
      <div class="card-body">
        <p class="mb-1">- <?= duongdandatontaiduongdantruycapmucnaycothebitrunglap ?></p>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-8">
        <?php if (!empty($config['product'][$type]['slug'])): ?>
          <?php include TEMPLATE . LAYOUT . 'slug.php'; ?>
        <?php endif; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title"><?= noidung ?> <?= $config['product'][$type]['title_main'] ?></h3>
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
                        data-toggle="pill"
                        href="#tabs-content-article-<?= $k ?>"
                        role="tab"
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
                          name="name<?= $k ?>" id="name<?= $k ?>"
                          placeholder="<?= tieude ?> (<?= $k ?>)"
                          value="<?= $_POST['name' . $k] ?? ($result['name' . $k] ?? '') ?>"
                          <?= ($k == $lang) ? 'required' : '' ?> />
                      </div>

                      <!-- Mô tả -->
                      <?php if (!empty($config['product'][$type]['desc_cke']) || !empty($config['product'][$type]['desc'])): ?>
                        <div class="form-group">
                          <label for="desc<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['product'][$type]['desc_cke']) ? 'form-control-ckeditor' : '' ?>"
                            name="desc<?= $k ?>" id="desc<?= $k ?>"
                            placeholder="<?= mota ?> (<?= $k ?>)"><?= $_POST['desc' . $k] ?? ($result['desc' . $k] ?? '') ?></textarea>
                        </div>
                      <?php endif; ?>

                      <!-- Nội dung -->
                      <?php if (!empty($config['product'][$type]['content_cke']) || !empty($config['product'][$type]['content'])): ?>
                        <div class="form-group">
                          <label for="content<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['product'][$type]['content_cke']) ? 'form-control-ckeditor' : '' ?>"
                            name="content<?= $k ?>" id="content<?= $k ?>"
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
            <h3 class="card-title"><?= chondanhmuc ?> <?= $config['product'][$type]['title_main'] ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group-category row">
              <div class="form-group col-xl-6 col-sm-4">
                <label class="d-block" for="id_list"><?= danhmuccap1 ?>:</label>
                <?= $fn->getAjaxCategory('tbl_product_list', $_POST['id_list'] ?? $result['id_list'] ?? '') ?>
              </div>
              <div class="form-group col-xl-6 col-sm-4">
                <label class="d-block" for="id_cat"><?= danhmuccap2 ?>:</label>
                <?= $fn->getAjaxCategory(
                  'tbl_product_cat',
                  $_POST['id_cat'] ?? $result['id_cat'] ?? '',
                  $_POST['id_list'] ?? $result['id_list'] ?? 0,
                ) ?>
              </div>
            </div>
          </div>
        </div>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title"><?= thongtin ?> <?= $config['product'][$type]['title_main'] ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <?php if (!empty($config['product'][$type]['regular_price'])): ?>
                <div class="form-group col-md-6">
                  <label class="d-block" for="regular_price"><?= gia ?>:</label>
                  <div class="input-group">
                    <input type="text" class="form-control format-price regular_price text-sm" name="regular_price"
                      id="regular_price" placeholder="<?= gia ?>" value="<?= $_POST['regular_price'] ?? $result['regular_price'] ?? '' ?>">
                    <div class="input-group-append">
                      <div class="input-group-text"><strong>VNĐ</strong></div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <?php if (!empty($config['product'][$type]['sale_price'])): ?>
                <div class="form-group col-md-6">
                  <label class="d-block" for="sale_price"><?= giamoi ?>:</label>
                  <div class="input-group">
                    <input type="text" class="form-control format-price sale_price text-sm" name="sale_price"
                      id="sale_price" placeholder="<?= giamoi ?>" value="<?= $_POST['sale_price'] ?? $result['sale_price'] ?? '' ?>">
                    <div class="input-group-append">
                      <div class="input-group-text"><strong>VNĐ</strong></div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <?php if (!empty($config['product'][$type]['discount'])): ?>
                <div class="form-group col-md-4">
                  <label class="d-block" for="discount">Chiếc khấu:</label>
                  <div class="input-group">
                    <input type="text" class="form-control discount text-sm" name="discount" id="discount"
                      placeholder="Chiếc khấu" value="<?= $_POST['discount'] ?? $result['discount'] ?? '' ?>" maxlength="3" readonly>
                    <div class="input-group-append">
                      <div class="input-group-text"><strong>%</strong></div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <?php if (!empty($config['product'][$type]['code'])): ?>
                <div class="form-group col-md-12">
                  <label class="d-block" for="code"><?= masp ?>:</label>
                  <input type="text" class="form-control text-sm" name="code" id="code" placeholder="<?= masp ?>"
                    value="<?= $_POST['code'] ?? $result['code'] ?? '' ?>">
                </div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <?php foreach ($config['product'][$type]['check'] as $check => $label): ?>
                <div class="form-group d-inline-block mb-2 mr-4">
                  <label for="<?= $check ?>-checkbox" class="d-inline-block align-middle mb-0 mr-3 form-label"><?= defined($check) ? constant($check) : $check ?>:</label>
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
              <label for="numb" class="d-inline-block align-middle mb-0 mr-2"><?= sothutu ?>:</label>
              <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                name="numb" id="numb" placeholder="Số thứ tự" value="<?= $_POST['numb'] ?? (!empty($id) ? $result['numb'] : '1') ?>" />
            </div>
          </div>
        </div>
        <?php if (!empty($config['product'][$type]['images'])): ?>
          <div class="card card-primary card-outline text-sm">
            <div class="card-header">
              <h3 class="card-title"><?= hinhanh ?> <?= $config['product'][$type]['title_main'] ?></h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                    class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <?php
              $photoDetail = array();
              $photoDetail['image'] = $result['file'] ?? '';
              $photoDetail['dimension'] = "Width: " . $config['product'][$type]['width'] . " px - Height: " . $config['product'][$type]['height'] . " px (" . $config['product'][$type]['img_type'] . ")";
              include TEMPLATE . LAYOUT . "image.php"; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php if (!empty($config['product'][$type]['gallery'])): ?>
      <div class="card card-primary card-outline text-sm">
        <div class="card-header">
          <h3 class="card-title"><?= bosuutap ?></h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="filer-gallery" class="label-filer-gallery mb-3">
              Album: (<?= $config['product'][$type]['img_type'] ?>)
            </label>
            <input type="file" name="files[]" id="filer-gallery" multiple="multiple">
            <input type="hidden" name="id_parent" value="<?= $id ?>">
            <input type="hidden" class="col-filer" value="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
            <input type="hidden" name="deleted_images" class="deleted-images" value="">
          </div>
          <?php if (!empty($gallery)) { ?>
            <div class="form-group form-group-gallery">
              <label class="label-filer"><?= albumhientai ?>:</label>
              <div class="action-filer mb-3">
                <a class="btn btn-sm bg-gradient-primary text-white check-all-filer mr-1"><i class="far fa-square mr-2"></i><?= chontatca ?></a>
                <a class="btn btn-sm bg-gradient-danger text-white delete-all-filer"><i class="far fa-trash-alt mr-2"></i><?= xoatatca ?></a>
              </div>
              <div class="jFiler-items my-jFiler-items jFiler-row">
                <ul class="jFiler-items-list jFiler-items-grid row scroll-bar" id="jFilerSortable">
                  <?php foreach ($gallery as $g): ?>
                    <?= $fn->galleryFiler($g['numb'] ?? 1, $g['id'] ?? 0, $g['file'] ?? '', $g['name'] ?? '', 'col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6') ?>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php } ?>
          <div class="form-group d-inline-block mb-2 mr-5">
            <label for="hienthi_all-checkbox" class="d-inline-block align-middle mb-0 mr-3 form-label"><?= hienthitatca ?>:</label>
            <label class="switch switch-success">
              <input type="checkbox" name="hienthi_all" class="switch-input custom-control-input" id="hienthi_all-checkbox" value="hienthi" checked>
            </label>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <?php if (!empty($config['product'][$type]['seo'])): ?>
      <?php include TEMPLATE . LAYOUT . 'seo.php'; ?>
    <?php endif; ?>
    <input type="hidden" name="type" value="<?= $type ?>">
  </form>
</section>
