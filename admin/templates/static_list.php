<?php
$redirect_url = $_GET['page'];
$type = $_GET['type'];
$name_page = $fn->convert_type($type)['vi'];
$result = $trangtinh->get_static($type);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $update = $trangtinh->update_static($_POST, $type, $result['id']);
}
?>
<?php
$breadcrumb = [['label' => $name_page]];
include 'templates/breadcrumb.php';
?>
<section class="content">
  <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary submit-check"><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Nội dung <?= $name_page ?></h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body">
        <div class="card card-primary card-outline card-outline-tabs">
          <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-article-tab-lang" role="tablist">
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
          <div class="card-body card-article">
            <div class="tab-content" id="custom-tabs-article-tabContent-lang">
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
                      value="<?= $_POST['name' . $k] ?? ($result['name' . $k] ?? '') ?>"
                      <?= ($k == 'vi') ? 'required' : '' ?> />
                  </div>
                  <!-- Nội dung -->
                  <div class="form-group">
                    <label for="content<?= $k ?>">Nội dung (<?= $k ?>):</label>
                    <textarea class="form-control for-seo text-sm form-control-ckeditor"
                      name="content<?= $k ?>" id="content<?= $k ?>"
                      placeholder="Nội dung (<?= $k ?>)"><?= $_POST['content' . $k] ?? ($result['content' . $k] ?? '') ?></textarea>
                  </div>
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
