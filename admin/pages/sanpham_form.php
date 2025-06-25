<?php
$message = '';
$name_page = 'sản phẩm';
$table = 'tbl_sanpham';
$thumb_width = '500';
$thumb_height = '500';
$thumb_zc = '1';
$show_danhmuc_c1 = $fn->show_data(['table' => 'tbl_danhmuc_c1']);
$id = $_GET['id'] ?? null;
$result = [];
if (!empty($id)) {
  $get_id = $fn->get_id($table, $id);
  if ($get_id) {
    $result = $get_id->fetch_assoc();
    $get_id_cap1 = $result['id_list'];
    $show_danhmuc_c2 = $fn->show_data(['table' => 'tbl_danhmuc_c2']);
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add']) || isset($_POST['edit']))) {
  // echo '<pre>';
  // print_r($_POST);
  // print_r($_FILES);
  // echo '</pre>';
  // exit;
  $message = $sanpham->save_sanpham($_POST, $_FILES, $id);
}
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
                        <textarea class="form-control for-seo text-sm form-control-ckeditor"
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
            <h3 class="card-title">Chon danh mục <?= $name_page ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group-category row">
              <div class="form-group col-xl-6 col-sm-4">
                <label class="d-block" for="id_list">Danh mục cấp 1:</label>
                <select id="id_list" name="id_list" data-level="0" data-type="san-pham" data-table="tbl_danhmuc_c2"
                  data-child="id_cat" class="form-control select2 select-category">
                  <option value="0">Chọn danh mục</option>
                  <?php $fn->renderSelectOptions($show_danhmuc_c1); ?>
                </select>
              </div>
              <div class="form-group col-xl-6 col-sm-4">
                <label class="d-block" for="id_cat">Danh mục cấp 2:</label>
                <select id="id_cat" name="id_cat" data-level="1" data-type="san-pham" data-table="" data-child=""
                  class="form-control select2 select-category">
                  <option value="0">Chọn danh mục</option>
                  <?php $fn->renderSelectOptions($show_danhmuc_c2); ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Thông tin <?= $name_page ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-md-6">
                <label class="d-block" for="regular_price">Giá:</label>
                <div class="input-group">
                  <input type="text" class="form-control format-price regular_price text-sm" name="regular_price"
                    id="regular_price" placeholder="Giá" value="<?= $_POST['regular_price'] ?? ($result['regular_price'] ?? "") ?>">
                  <div class="input-group-append">
                    <div class="input-group-text"><strong>VNĐ</strong></div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="d-block" for="sale_price">Giá mới:</label>
                <div class="input-group">
                  <input type="text" class="form-control format-price sale_price text-sm" name="sale_price"
                    id="sale_price" placeholder="Giá mới" value="<?= $_POST['sale_price'] ?? ($result['sale_price'] ?? "") ?>">
                  <div class="input-group-append">
                    <div class="input-group-text"><strong>VNĐ</strong></div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4">
                <label class="d-block" for="discount">Chiếc khấu:</label>
                <div class="input-group">
                  <input type="text" class="form-control discount text-sm" name="discount" id="discount"
                    placeholder="Chiếc khấu" value="<?= $_POST['discount'] ?? ($result['discount'] ?? "") ?>" maxlength="3" readonly>
                  <div class="input-group-append">
                    <div class="input-group-text"><strong>%</strong></div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-8">
                <label class="d-block" for="code">Mã sản phẩm:</label>
                <input type="text" class="form-control text-sm" name="code" id="code" placeholder="Mã sản phẩm"
                  value="<?= $_POST['code'] ?? ($result['code'] ?? "") ?>">
              </div>
            </div>
            <div class="form-group">
              <?php
              $checkboxes = [
                'hienthi' => 'Hiển thị',
                'noibat' => 'Nổi bật',
                'banchay' => 'Bán chạy'
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
              <input type="number"
                class="form-control form-control-mini w-25 text-left d-inline-block align-middle text-sm" min="0"
                name="numb" id="numb" placeholder="Số thứ tự" value="<?= $_POST['numb'] ?? (!empty($id) ? $result['numb'] : '1') ?>">
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
      </div>
    </div>
    <?php
    /*
<div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Bộ sưu tập Sản phẩm</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label for="filer-gallery" class="label-filer-gallery mb-3">
            Album: (.jpg|.gif|.png|.jpeg|.webp)
          </label>
          <input
            type="file"
            name="files[]"
            id="filer-gallery"
            multiple="multiple"
            data-id_parent="<?= $id ?? 0 ?>"
            data-hash="<?= $fn->generateHash() ?>"
            class="form-control-file" />
          <input type="hidden" class="col-filer" value="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
          <input type="hidden" class="act-filer" value="man">
          <input type="hidden" class="folder-filer" value="product">
        </div>
      </div>
    </div>
    */
    ?>
    <?php include 'templates/seo.php'; ?>
    <input type="hidden" name="type" value="sanpham">
    <input type="hidden" name="thumb_width" value="<?= $thumb_width ?>">
    <input type="hidden" name="thumb_height" value="<?= $thumb_height ?>">
    <input type="hidden" name="thumb_zc" value="<?= $thumb_zc ?>">
  </form>
</section>
