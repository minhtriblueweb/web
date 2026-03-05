<?php
$linkMan = "index.php?com=news&act=man_list&type=" . $type;
$linkSave = "index.php?com=news&act=save_list&type=" . $type;
/* Check cols */
if (isset($config['news'][$type]['gallery_list']) && count($config['news'][$type]['gallery_list']) > 0) {
  foreach ($config['news'][$type]['gallery_list'] as $key => $value) {
    if ($key == $type) {
      $keyGallery = $key;
      $flagGallery = true;
      break;
    }
  }
}

if ((!empty($config['news'][$type]['images_list']))) {
  $colLeft = "col-xl-8";
  $colRight = "col-xl-4";
} else {
  $colLeft = "col-12";
  $colRight = "d-none";
}
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= ($id > 0 ? capnhat : themmoi) . ' ' . ($config['news'][$type]['title_main_list'] ?? '') ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" name="<?= !empty($id) ? 'edit' : 'add'; ?>"
        class="btn btn-sm bg-gradient-primary <?= !empty($config['news'][$type]['slug_list']) ? 'submit-check' : '' ?>" <?= !empty($config['news'][$type]['slug_list']) ? 'disabled' : '' ?>><i class="far fa-save mr-2"></i><?= luu ?>
      </button>
      <button type="submit" class="btn btn-sm bg-gradient-success <?= !empty($config['news'][$type]['slug_list']) ? 'submit-check' : '' ?>" name="save-here"><i class="far fa-save mr-2"></i><?= luutaitrang ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary">
        <i class="fas fa-redo mr-2"></i><?= lamlai ?>
      </button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="<?= thoat ?>"><i class="fas fa-sign-out-alt mr-2"></i><?= thoat ?></a>
    </div>
    <div class="row">
      <div class="<?= $colLeft ?>">
        <?php if (!empty($config['news'][$type]['slug_list'])): ?>
          <?php include TEMPLATE . LAYOUT . 'slug.php'; ?>
        <?php endif; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title"><?= noidung ?> <?= $config['news'][$type]['title_main_list'] ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
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
                      <div class="form-group">
                        <label for="name<?= $k ?>"><?= tieude ?> (<?= $k ?>):</label>
                        <input type="text"
                          class="form-control for-seo text-sm"
                          name="data[name<?= $k ?>]" id="name<?= $k ?>"
                          placeholder="<?= tieude ?> (<?= $k ?>)"
                          value="<?= $_POST['name' . $k] ?? ($item['name' . $k] ?? '') ?>" <?= ($k == $lang) ? 'required' : '' ?> />
                      </div>

                      <!-- Mô tả -->
                      <?php if (!empty($config['news'][$type]['desc_cke_list']) || !empty($config['news'][$type]['desc_list'])): ?>
                        <div class="form-group">
                          <label for="desc<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['news'][$type]['desc_cke_list']) ? 'form-control-ckeditor' : '' ?>"
                            name="data[desc<?= $k ?>]" id="desc<?= $k ?>"
                            placeholder="<?= mota ?> (<?= $k ?>)"><?= $_POST['desc' . $k] ?? ($item['desc' . $k] ?? '') ?></textarea>
                        </div>
                      <?php endif; ?>

                      <!-- Nội dung -->
                      <?php if (!empty($config['news'][$type]['content_cke_list']) || !empty($config['news'][$type]['content_list'])): ?>
                        <div class="form-group">
                          <label for="content<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['news'][$type]['content_cke_list']) ? 'form-control-ckeditor' : '' ?>"
                            name="data[content<?= $k ?>]" id="content<?= $k ?>"
                            placeholder="<?= mota ?> (<?= $k ?>)"><?= $_POST['content' . $k] ?? ($item['content' . $k] ?? '') ?></textarea>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="card card-primary card-outline text-sm">
              <div class="card-header">
                <h3 class="card-title"><?= thongtin ?></h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <?php
                  $status_array = !empty($item['status']) ? explode(',', $item['status']) : [];
                  if (!empty($config['news'][$type]['check_list'])) {
                    foreach ($config['news'][$type]['check_list'] as $key => $value) {
                      echo $func->is_checked($key, $value, $status_array, $item['id'] ?? null);
                    }
                  }
                  ?>
                </div>
                <div class="form-group">
                  <label for="numb" class="d-inline-block align-middle mb-0 mr-2"><?= sothutu ?>:</label>
                  <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                    name="data[numb]" id="numb" placeholder="<?= sothutu ?>" value="<?= $_POST['numb'] ?? (!empty($id) ? $item['numb'] : '1') ?>" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="<?= $colRight ?>">
        <?php if (!empty($config['news'][$type]['images_list'])): ?>
          <div class="card card-primary card-outline text-sm">
            <div class="card-header">
              <h3 class="card-title"><?= hinhanh ?> <?= $config['news'][$type]['title_main_list'] ?></h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <?php
              $photoDetail = array();
              $photoDetail['image'] = $item['file'] ?? '';
              $photoDetail['dimension'] = "Width: " . $config['news'][$type]['width_list'] . " px - Height: " . $config['news'][$type]['height_list'] . " px (" . $config['news'][$type]['img_type_list'] . ")";
              include TEMPLATE . LAYOUT . "image.php"; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php if (!empty($config['news'][$type]['seo_list'])): ?>
      <?php include TEMPLATE . LAYOUT . 'seo.php'; ?>
    <?php endif; ?>
    <input type="hidden" name="data[type]" value="<?= $type ?>">
  </form>
</section>
