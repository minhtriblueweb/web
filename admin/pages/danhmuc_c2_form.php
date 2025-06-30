<?php
$setting_page = [
  'message' => '',
  'name_page' => 'danh mục cấp 2',
  'table' => 'tbl_danhmuc_c2',
  'thumb_width' => 100,
  'thumb_height' => 100,
  'thumb_zc' => 1,
  'type' => 'danhmuc_c2'
];
extract($setting_page);
$result = $seo_data = [];
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
if ($id !== null) {
  $get_id = $fn->get_id($table, $id);
  if ($get_id && $get_id->num_rows > 0) {
    $result = $get_id->fetch_assoc();
    $seo_data = $seo->get_seo($id);
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
  $message = $danhmuc->save_danhmuc_c2($_POST, $_FILES, $id);
}
$show_danhmuc = $fn->show_data(['table' => 'tbl_danhmuc_c1']);
?>
<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => 'index.php'],
  ['label' => !empty($id) ? 'Cập nhật ' . $name_page : 'Thêm mới ' . $name_page]
];
include 'templates/breadcrumb.php';
?>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <?php include 'templates/act.php'; ?>
    <div class="row">
      <div class="col-xl-8">
        <?php include 'templates/slug.php'; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Nội dung <?= $name_page ?></h3>
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
                <div class="tab-content" id="custom-tabs-three-tabContent-lang">
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

                      <!-- Mô tả -->
                      <div class="form-group">
                        <label for="desc<?= $k ?>">Mô tả (<?= $k ?>):</label>
                        <textarea class="form-control for-seo text-sm"
                          name="desc<?= $k ?>" id="desc<?= $k ?>"
                          rows="4" placeholder="Mô tả (<?= $k ?>)"><?= $_POST['desc' . $k] ?? ($result['desc' . $k] ?? '') ?></textarea>
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
      </div>
      <div class="col-xl-4">
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Chọn danh mục cấp 1</h3>
          </div>
          <div class="card-body">
            <div class="form-group-category">
              <select id="id_list" name="id_list" data-level="0" data-type="san-pham" data-table="#_product_cat"
                data-child="id_cat" class="form-control select2 select-category">
                <option value="0">Chọn danh mục</option>
                <?php if ($show_danhmuc): ?>
                  <?php while ($row = $show_danhmuc->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"
                      <?= (($_POST['id_list'] ?? ($result['id_list'] ?? '')) == $row['id']) ? 'selected' : '' ?>>
                      <?= $row['namevi'] ?>
                    </option>
                  <?php endwhile; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Hình ảnh <?= $name_page ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="photoUpload-zone">
              <div class="photoUpload-detail" id="photoUpload-preview">
                <?= $fn->getImage([
                  'file' => $result['file'] ?? '',
                  'class' => 'rounded',
                  'alt' => 'Alt Photo',
                ]) ?>
              </div>
              <label class="photoUpload-file" id="photo-zone" for="file-zone">
                <input type="file" name="file" id="file-zone">
                <i class="fas fa-cloud-upload-alt"></i>
                <p class="photoUpload-drop">Kéo và thả hình vào đây</p>
                <p class="photoUpload-or">hoặc</p>
                <p class="photoUpload-choose btn btn-sm bg-gradient-success">Chọn hình</p>
              </label>
              <div class="photoUpload-dimension">
                Width: <?= $thumb_width ?> px - Height: <?= $thumb_height ?> px
                (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)
              </div>
            </div>
          </div>
        </div>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Thông tin</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group">
              <?php
              $checkboxes = [
                'hienthi' => 'Hiển thị',
                'noibat' => 'Nổi bật'
              ];
              ?>
              <?php foreach ($checkboxes as $check => $label): ?>
                <div class="form-group d-inline-block mb-2 mr-2">
                  <label for="<?= $check ?>-checkbox" class="d-inline-block align-middle mb-0 mr-2"><?= $label ?>:</label>
                  <div class="custom-control custom-checkbox d-inline-block align-middle">
                    <input <?= $fn->is_checked($check, $result ?? null, $id ?? null) ?>
                      type="checkbox"
                      class="custom-control-input <?= $check ?>-checkbox"
                      name="<?= $check ?>"
                      id="<?= $check ?>-checkbox"
                      value="<?= $check ?>" />
                    <label for="<?= $check ?>-checkbox" class="custom-control-label"></label>
                  </div>
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
    <?php include 'templates/seo.php'; ?>
    <input type="hidden" name="type" value="<?= $type ?>">
    <input type="hidden" name="thumb_width" value="<?= $thumb_width ?>">
    <input type="hidden" name="thumb_height" value="<?= $thumb_height ?>">
    <input type="hidden" name="thumb_zc" value="<?= $thumb_zc ?>">
  </form>
</section>
