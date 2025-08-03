<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= $config['static'][$type]['title_main'] ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary"><i
          class="far fa-save mr-2"></i><?= luu ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i><?= lamlai ?></button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title"><?= noidung ?> <?= $config['static'][$type]['title_main'] ?></h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <?php foreach ($config['static'][$type]['check'] as $check => $label): ?>
            <div class="form-group d-inline-block mb-2 mr-5">
              <label for="<?= $check ?>-checkbox" class="d-inline-block align-middle mb-0 mr-3 form-label"><?= defined($check) ? constant($check) : $check ?>:</label>
              <label class="switch switch-success">
                <input type="checkbox" name="<?= $check ?>" class="switch-input custom-control-input .show-checkbox" id="<?= $check ?>-checkbox" <?= $fn->is_checked($check, $result['status'] ?? '', $result['id'] ?? '') ?>>
              </label>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="card card-primary card-outline card-outline-tabs">
          <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-article-tab-lang" role="tablist">
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
            <div class="tab-content" id="custom-tabs-article-tabContent-lang">
              <?php foreach ($config['website']['lang'] as $k => $v) { ?>
                <div class="tab-pane fade show <?= ($k == $lang) ? 'active' : '' ?>"
                  id="tabs-content-article-<?= $k ?>"
                  role="tabpanel"
                  aria-labelledby="tabs-lang-article-<?= $k ?>">

                  <!-- Tiêu đề -->
                  <?php if (!empty($config['static'][$type]['name'])): ?>
                    <div class="form-group">
                      <label for="name<?= $k ?>"><?= tieude ?> (<?= $k ?>):</label>
                      <input type="text"
                        class="form-control for-seo text-sm"
                        name="name<?= $k ?>" id="name<?= $k ?>"
                        placeholder="<?= tieude ?> (<?= $k ?>)"
                        value="<?= $_POST['name' . $k] ?? $result['name' . $k] ?? '' ?>" />
                    </div>
                  <?php endif; ?>
                  <!-- Mô tả -->
                  <?php if (!empty($config['static'][$type]['desc']) || !empty($config['static'][$type]['desc_cke'])): ?>
                    <div class="form-group">
                      <label for="desc<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                      <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['static'][$type]['desc_cke']) ? 'form-control-ckeditor' : '' ?>"
                        name="desc<?= $k ?>" id="desc<?= $k ?>"
                        placeholder="<?= mota ?> (<?= $k ?>)"><?= $_POST['desc' . $k] ?? ($result['desc' . $k] ?? '') ?></textarea>
                    </div>
                  <?php endif; ?>
                  <!-- Nội dung -->
                  <?php if (!empty($config['static'][$type]['content']) || !empty($config['static'][$type]['content_cke'])): ?>
                    <div class="form-group">
                      <label for="content<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                      <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['static'][$type]['content_cke']) ? 'form-control-ckeditor' : '' ?>"
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
    <input type="hidden" name="type" id="type" value="<?= $type ?>">
  </form>
</section>
