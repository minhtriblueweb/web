<div class="card card-primary card-outline text-sm">
  <div class="card-header">
    <h3 class="card-title">Đường dẫn</h3>
    <span class="pl-2 text-danger">(Vui lòng không nhập trùng tiêu đề)</span>
  </div>
  <div class="card-body card-slug">
    <?php if (!empty($id)) : ?>
      <label for="slugchange" class="d-inline-block align-middle text-info mb-0 mr-2">Thay đổi đường dẫn theo tiêu đề mới:</label>
      <div class="custom-control custom-checkbox d-inline-block align-middle">
        <input type="checkbox" class="custom-control-input" name="slugchange" id="slugchange">
        <label for="slugchange" class="custom-control-label"></label>
      </div>
    <?php endif; ?>
    <!-- Tabs -->
    <div class="card card-primary card-outline card-outline-tabs">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
          <?php foreach ($config['website']['lang'] as $k => $v) { ?>
            <li class="nav-item">
              <a class="nav-link <?= ($k == 'vi') ? 'active' : '' ?>" id="tabs-lang-<?= $k ?>" data-toggle="pill"
                href="#tabs-sluglang-<?= $k ?>" role="tab" aria-controls="tabs-sluglang-<?= $k ?>"
                aria-selected="<?= ($k == 'vi') ? 'true' : 'false' ?>">
                <?= $v ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>

      <!-- Tab Content -->
      <div class="card-body">
        <div class="tab-content" id="custom-tabs-three-tabContent-lang">
          <?php foreach ($config['website']['lang'] as $k => $v) { ?>
            <div class="tab-pane fade show <?= ($k == 'vi') ? 'active' : '' ?>" id="tabs-sluglang-<?= $k ?>"
              role="tabpanel" aria-labelledby="tabs-lang-<?= $k ?>">
              <div class="form-group mb-0">
                <label class="d-block">Đường dẫn mẫu (vi):<span class="pl-2 font-weight-normal"
                    id="slugurlpreviewvi"><?= BASE ?><?php if (!empty($id)): ?><strong class="text-info"><?= $result['slug' . $k]; ?></strong><?php endif; ?></span></label>

                <!-- Input Slug -->
                <input type="text"
                  class="form-control slug-input no-validate text-sm for-seo"
                  name="slug<?= $k ?>" id="slug<?= $k ?>"
                  placeholder="Đường dẫn (<?= $v ?>)"
                  value="<?= $_POST['slug' . $k] ?? ($result['slug' . $k] ?? "") ?>"
                  <?= ($k == 'vi') ? 'required' : '' ?> />
                <!-- Hidden Default -->
                <input type="hidden" id="slug-default<?= $k ?>" value="<?= $_POST['slug' . $k] ?? ($result['slug' . $k] ?? "") ?>">
                <input type="hidden" class="slug-id" value="<?= !empty($id) ? $id : '' ?>" />
                <input type="hidden" class="slug-table" value="<?= $table ?>" />
                <input type="hidden" class="slug-copy" value="" />
                <!-- Alert -->
                <div id="slug-alert-wrapper" class="mt-2" style="min-height: 20px;">
                  <p id="alert-slug-danger<?= $k ?>" class="alert-slug text-danger mb-0 d-none">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <span>Đường dẫn đã tồn tại</span>
                  </p>
                  <p id="alert-slug-success<?= $k ?>" class="alert-slug text-success mb-0 d-none">
                    <i class="fa-solid fa-circle-check mr-1"></i>
                    <span>Đường dẫn hợp lệ.</span>
                  </p>
                </div>
                <?php if (!empty($message)): ?>
                  <p class="alert-slug text-danger mt-2 mb-0" id="alert-slug-dangervi">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <span><?= $message ?></span>
                  </p>
                <?php endif; ?>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
