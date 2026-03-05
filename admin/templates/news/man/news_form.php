<?php
if (isset($id_copy) && $id_copy > 0) {
  $breadcrumb = 'Sao chép';
} elseif ($id > 0) {
  $breadcrumb = capnhat;
} else {
  $breadcrumb = themmoi;
}

if ((isset($config['news'][$type]['tags']) && $config['news'][$type]['tags'] == true) ||
  (isset($config['news'][$type]['images']) && $config['news'][$type]['images'] == true)
) {
  $colLeft = "col-xl-8";
  $colRight = "col-xl-4";
} else {
  $colLeft = "col-12";
  $colRight = "d-none";
}
$linkMan = "index.php?com=news&act=man&type=" . $type;
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active">
          <?= $breadcrumb . ' ' . ($config['news'][$type]['title_main'] ?? '') ?>
        </li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" name="<?= !empty($id) ? 'edit' : 'add'; ?>"
        class="btn btn-sm bg-gradient-primary <?= !empty($config['news'][$type]['slug']) ? 'submit-check' : '' ?>"
        <?= !empty($config['news'][$type]['slug']) ? 'disabled' : '' ?>>
        <i class="far fa-save mr-2"></i><?= luu ?>
      </button>
      <button type="submit" class="btn btn-sm bg-gradient-success <?= !empty($config['news'][$type]['slug']) ? 'submit-check' : '' ?>" name="save-here"><i class="far fa-save mr-2"></i><?= luutaitrang ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary">
        <i class="fas fa-redo mr-2"></i><?= lamlai ?>
      </button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="<?= thoat ?>"><i class="fas fa-sign-out-alt mr-2"></i><?= thoat ?></a>
    </div>
    <div class="row">
      <div class="<?= $colLeft ?>">
        <?php if (!empty($config['news'][$type]['slug'])): ?>
          <?php include TEMPLATE . LAYOUT . 'slug.php'; ?>
        <?php endif; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title"><?= noidung ?> <?= $config['news'][$type]['title_main'] ?></h3>
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
                          name="data[name<?= $k ?>]" id="name<?= $k ?>"
                          placeholder="<?= tieude ?> (<?= $k ?>)"
                          value="<?= $_POST['name' . $k] ?? ($item['name' . $k] ?? '') ?>"
                          <?= ($k == $lang) ? 'required' : '' ?> />
                      </div>

                      <!-- Mô tả -->
                      <?php if (!empty($config['news'][$type]['desc_cke']) || !empty($config['news'][$type]['desc'])): ?>
                        <div class="form-group">
                          <label for="desc<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['news'][$type]['desc_cke']) ? 'form-control-ckeditor' : '' ?>" name="data[desc<?= $k ?>]" id="desc<?= $k ?>" placeholder="<?= mota ?> (<?= $k ?>)"><?= $_POST['desc' . $k] ?? ($item['desc' . $k] ?? '') ?></textarea>
                        </div>
                      <?php endif; ?>

                      <!-- Nội dung -->
                      <?php if (!empty($config['news'][$type]['content_cke']) || !empty($config['news'][$type]['content'])): ?>
                        <div class="form-group">
                          <label for="content<?= $k ?>"><?= mota ?> (<?= $k ?>):</label>
                          <textarea rows="4" class="form-control for-seo text-sm <?= !empty($config['news'][$type]['content_cke']) ? 'form-control-ckeditor' : '' ?>" name="data[content<?= $k ?>]" id="content<?= $k ?>" placeholder="<?= mota ?> (<?= $k ?>)"><?= $_POST['content' . $k] ?? ($item['content' . $k] ?? '') ?></textarea>
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
      <div class="<?= $colRight ?>">
        <?php if (!empty($config['news'][$type]['dropdown'])) : ?>
          <div class="card card-primary card-outline text-sm">
            <div class="card-header">
              <h3 class="card-title"><?= chondanhmuc ?> <?= $config['news'][$type]['title_main'] ?></h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                    class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group-category row">
                <?php if (!empty($config['news'][$type]['list'])) : ?>
                  <div class="form-group col-xl-6 col-sm-4">
                    <label class="d-block" for="id_list"><?= danhmuccap1 ?>:</label>
                    <?= $func->getAjaxCategory('news', 'list', $type) ?>
                  </div>
                <?php endif; ?>
                <?php if (!empty($config['news'][$type]['cat'])) : ?>
                  <div class="form-group col-xl-6 col-sm-4">
                    <label class="d-block" for="id_cat"><?= danhmuccap2 ?>:</label>
                    <?= $func->getAjaxCategory('news', 'cat', $type) ?>
                  </div>
                <?php endif; ?>
                <?php if (!empty($config['news'][$type]['item'])) : ?>
                  <div class="form-group col-xl-6 col-sm-4">
                    <label class="d-block" for="id_item"><?= danhmuccap3 ?>:</label>
                    <?= $func->getAjaxCategory('news', 'item', $type) ?>
                  </div>
                <?php endif; ?>
                <?php if (!empty($config['news'][$type]['sub'])) : ?>
                  <div class="form-group col-xl-6 col-sm-4">
                    <label class="d-block" for="id_sub"><?= danhmuccap4 ?>:</label>
                    <?= $func->getAjaxCategory('news', 'sub', $type) ?>
                  </div>
                <?php endif; ?>
                <?php if (!empty($config['news'][$type]['brand'])) : ?>
                  <div class="form-group col-xl-6 col-sm-4">
                    <label class="d-block" for="id_brand"><?= danhmuchang ?>:</label>
                    <?= $func->getAjaxCategory('news', 'brand', $type) ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <?php if (!empty($config['news'][$type]['images'])): ?>
          <div class="card card-primary card-outline text-sm">
            <div class="card-header">
              <h3 class="card-title"><?= hinhanh ?> <?= $config['news'][$type]['title_main'] ?></h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                    class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <?php
              $photoDetail = array();
              $photoDetail['image'] = (!empty($item['file']) && $act != 'copy') ? $item['file'] : '';
              $photoDetail['dimension'] = "Width: " . $config['news'][$type]['width'] . " px - Height: " . $config['news'][$type]['height'] . " px (" . $config['news'][$type]['img_type'] . ")";
              include TEMPLATE . LAYOUT . "image.php"; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title"><?= thongtin ?> <?= $config['news'][$type]['title_main'] ?></h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
              class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <?php
            $status_array = !empty($item['status']) ? explode(',', $item['status']) : [];
            if (!empty($config['news'][$type]['check'])) {
              foreach ($config['news'][$type]['check'] as $key => $value) {
                echo $func->is_checked($key, $value, $status_array, $item['id'] ?? null);
              }
            }
            ?>
            </div>
            <div class="form-group">
              <label for="numb" class="d-inline-block align-middle mb-0 mr-2"><?= sothutu ?>:</label>
              <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                name="data[numb]" id="numb" placeholder="Số thứ tự" value="<?= $_POST['numb'] ?? !empty($id) ? $item['numb'] : '1' ?>" />
            </div>
        </div>
      </div>
      <?php if (!empty($config['news'][$type]['seo'])): ?>
        <?php include TEMPLATE . LAYOUT . 'seo.php'; ?>
      <?php endif; ?>
  </form>
</section>
