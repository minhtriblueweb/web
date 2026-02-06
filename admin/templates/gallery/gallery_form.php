<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= ($id_child > 0 ? capnhat : themmoi) . ' ' . ($config['product'][$type]['gallery'][$type]['title_main_photo'] ?? '') ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" name="<?= !empty($id_child) ? "edit" : "add"; ?>"
        class="btn btn-sm bg-gradient-primary btn-submit-HoldOn">
        <i class="far fa-save mr-2"></i>Lưu
      </button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan . $result['id_parent'] ?>" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
    </div>
    <div class="row">
      <?php if (empty($id_child)) : ?>
        <div class="col-12">
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
                <label for="filer-gallery" class="alabel-filer-gallery mb-3">
                  Album: (<?= $config['product'][$type]['gallery'][$type]['img_type_photo'] ?>)
                </label>
                <input type="file" name="files[]" id="filer-gallery" multiple="multiple">
                <input type="hidden" name="id_parent" value="<?= $id ?>">
                <input type="hidden" class="col-filer" value="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
              </div>

              <div class="form-group d-inline-block mb-2 mr-5">
                <label for="hienthi_all-checkbox" class="d-inline-block align-middle mb-0 mr-3 form-label"><?= hienthitatca ?></label>
                <label class="switch switch-success">
                  <input
                    type="checkbox"
                    name="hienthi_all"
                    class="switch-input custom-control-input"
                    id="hienthi_all-checkbox"
                    value="hienthi" checked>
                </label>
              </div>
              <?php
              /*
      <?php if (isset($get_gallery) && count($get_gallery) > 0) { ?>
                <div class="form-group form-group-gallery">
                  <label class="label-filer">Album hiện tại:</label>
                  <div class="action-filer mb-3">
                    <a class="btn btn-sm bg-gradient-primary text-white check-all-filer mr-1"><i
                        class="far fa-square mr-2"></i>Chọn tất cả</a>
                    <button type="button" class="btn btn-sm bg-gradient-success text-white sort-filer mr-1"><i
                        class="fas fa-random mr-2"></i>Sắp xếp</button>
                    <a class="btn btn-sm bg-gradient-danger text-white delete-all-filer"><i
                        class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
                  </div>
                  <div class="alert my-alert alert-sort-filer alert-info text-sm text-white bg-gradient-info"><i
                      class="fas fa-info-circle mr-2"></i>Có thể chọn nhiều hình để di chuyển</div>
                  <div class="jFiler-items my-jFiler-items jFiler-row">
                    <ul class="jFiler-items-list jFiler-items-grid row scroll-bar" id="jFilerSortable">
                      <?php foreach ($gallery as $v) echo $fn->galleryFiler($v['numb'], $v['id'], $v['photo'], $v['namevi'], 'product', 'col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6'); ?>
                    </ul>
                  </div>
                </div>
              <?php } ?>
*/
              ?>
            </div>
          </div>
        </div>

      <?php else : ?>
        <div class="col-12">
          <div class="card card-primary card-outline text-sm">
            <div class="card-header">
              <h3 class="card-title"><?= $config['product'][$type]['gallery'][$type]['title_main_photo'] ?>:</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <?php
              $photoDetail = array();
              $photoDetail['image'] = $result['file'] ?? '';
              $photoDetail['dimension'] = "Width: " . $config['product'][$type]['width'] . " px - Height: " . $config['product'][$type]['height'] . " px (" . $config['product'][$type]['img_type'] . ")";
              include TEMPLATE . LAYOUT . "image.php"; ?>
            </div>
            <div class="card-body">
              <div class="form-group">
                <?php foreach ($config['product'][$type]['gallery'][$type]['check_photo'] as $check => $label): ?>
                  <div class="form-group d-inline-block mb-2 mr-5">
                    <label for="<?= $check ?>-checkbox" class="d-inline-block align-middle mb-0 mr-3 form-label"><?= $label ?>:</label>
                    <label class="switch switch-success">
                      <input type="checkbox" name="<?= $check ?>"
                        class="switch-input custom-control-input .show-checkbox"
                        id="<?= $check ?>-checkbox" <?= $fn->is_checked($check, $result['status'] ?? '', $id ?? '') ?>>
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
  </form>
</section>
