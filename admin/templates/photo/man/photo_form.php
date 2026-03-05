<?php
if ((isset($config['photo']['photo_man'][$type]['images_photo']) && $config['photo']['photo_man'][$type]['images_photo'] == true)) {
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
        <li class="breadcrumb-item active"><?= ($id > 0 ? capnhat : themmoi) . ' ' . ($config['photo']['photo_man'][$type]['title_main_photo'] ?? '') ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" name="<?= !empty($id) ? "edit" : "add"; ?>"
        class="btn btn-sm bg-gradient-primary">
        <i class="far fa-save mr-2"></i><?= luu ?>
      </button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary">
        <i class="fas fa-redo mr-2"></i><?= lamlai ?>
      </button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="<?= thoat ?>"><i class="fas fa-sign-out-alt mr-2"></i><?= thoat ?></a>
    </div>
    <div class="row">
      <div class="<?= $colLeft ?>">
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title"><?= noidung ?> <?= $config['photo']['photo_man'][$type]['title_main_photo'] ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
                  <?php foreach ($config['website']['lang'] as $k => $v) { ?>
                    <li class="nav-item">
                      <a class="nav-link <?= ($k == $lang) ? 'active' : '' ?>" id="tabs-lang-article-<?= $k ?>" data-toggle="pill"
                        href="#tabs-content-article-<?= $k ?>" role="tab" aria-controls="tabs-content-article-<?= $k ?>"
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
                    <div class="tab-pane fade show <?= ($k == $lang) ? 'active' : '' ?>" id="tabs-content-article-<?= $k ?>"
                      role="tabpanel" aria-labelledby="tabs-lang-article-<?= $k ?>">
                      <?php if (!empty($config['photo']['photo_man'][$type]['name_photo'])): ?>
                        <div class="form-group">
                          <label for="name<?= $k ?>"><?= tieude ?> (<?= $k ?>):</label>
                          <input type="text"
                            class="form-control for-seo text-sm"
                            name="data[name<?= $k ?>]" id="name<?= $k ?>"
                            placeholder="<?= tieude ?> (<?= $k ?>)"
                            value="<?= $_POST['name' . $k] ?? ($item['name' . $k] ?? '') ?>" />
                        </div>
                      <?php endif; ?>
                      <?php if (!empty($config['photo']['photo_man'][$type]['desc_photo'])): ?>
                        <div class="form-group">
                          <label for="desc<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <input type="text" class="form-control for-seo text-sm" name="data[desc<?= $k ?>]" id="desc<?= $k ?>"
                            placeholder="<?= mota ?>" value="<?= $_POST['desc' . $k] ?? ($item['desc' . $k] ?? '') ?>" />
                        </div>
                      <?php endif; ?>
                      <?php if (!empty($config['photo']['photo_man'][$type]['content_photo'])): ?>
                        <div class="form-group">
                          <label for="content<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <textarea class="form-control for-seo text-sm"
                            name="data[content<?= $k ?>]" id="content<?= $k ?>"
                            rows="4" placeholder="<?= mota ?> (<?= $k ?>)"><?= $_POST['content' . $k] ?? ($item['content' . $k] ?? '') ?></textarea>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title"><?= thongtin ?> <?= $config['photo']['photo_man'][$type]['title_main_photo'] ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <?php if (!empty($config['photo']['photo_man'][$type]['link_photo'])): ?>
              <div class="form-group">
                <label for="link0">Link:</label>
                <input type="text" class="form-control text-sm" name="data[link]" id="link0" placeholder="Link"
                  value="<?= $_POST['link'] ?? $item['link'] ?? '' ?>">
              </div>
            <?php endif; ?>
            <div class="form-group">
              <?php
              $status_array = !empty($item['status']) ? explode(',', $item['status']) : [];
              if (!empty($config['photo']['photo_man'][$type]['check_photo'])) {
                foreach ($config['photo']['photo_man'][$type]['check_photo'] as $key => $value) {
                  echo $func->is_checked($key, $value, $status_array, $item['id'] ?? null);
                }
              }
              ?>
            </div>
            <div class="form-group">
              <label for="numb" class="d-inline-block align-middle mb-0 mr-2"><?= sothutu ?>:</label>
              <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                name="data[numb]" id="numb" placeholder="<?= sothutu ?>"
                value="<?= $_POST['numb'] ?? (!empty($id) ? $item['numb'] : '1') ?>">
            </div>
          </div>
        </div>
      </div>

      <div class="<?= $colRight ?>">
        <?php if (!empty($config['photo']['photo_man'][$type]['images_photo'])): ?>
          <div class="card card-primary card-outline text-sm">
            <div class="card-header">
              <h3 class="card-title"><?= hinhanh ?></h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <?php
              $photoDetail = array();
              $photoDetail['image'] = $item['file'] ?? '';
              $photoDetail['dimension'] = "Width: " . ($config['photo']['photo_man'][$type]['width_photo'] ?? 0) . " px - Height: " . ($config['photo']['photo_man'][$type]['height_photo'] ?? 0) . " px (" . ($config['photo']['photo_man'][$type]['img_type_photo'] ?? '.jpg|.png') . ")";
              include TEMPLATE . LAYOUT . "image.php"; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </form>
</section>
