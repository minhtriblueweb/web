<?php
$message = '';
$name = 'sản phẩm';
$redirectUrl = 'product_list';
$table = 'tbl_sanpham';
$show_danhmuc = $functions->show_data(['table' => 'tbl_danhmuc']);
$id = $_GET['id'] ?? null;
if (!empty($id)) {
  $get_id = $functions->get_id($table, $id);
  if ($get_id) {
    $result = $get_id->fetch_assoc();
    $get_id_cap1 = $result['id_list'];
    $show_danhmuc_c2 = $functions->show_data(['table' => 'tbl_danhmuc_c2']);
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
<!-- Main content -->
<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => 'index.php'],
  ['label' => $name, 'link' => $redirectUrl],
  ['label' => !empty($id) ? 'Cập nhật ' . $name : 'Thêm mới ' . $name]
];
include 'templates/breadcrumb.php';
?>

<!-- Main content -->
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <?php include 'templates/act.php'; ?>
    <div class="row">
      <div class="col-xl-8">
        <?php include 'templates/slug.php'; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Nội dung <?= $name ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="tabs-lang" data-toggle="pill" href="#tabs-lang-vi" role="tab"
                      aria-controls="tabs-lang-vi" aria-selected="true">Tiếng Việt</a>
                  </li>
                </ul>
              </div>
              <div class="card-body card-article">
                <div class="tab-content" id="custom-tabs-three-tabContent-lang">
                  <div class="tab-pane fade show active" id="tabs-lang-vi" role="tabpanel" aria-labelledby="tabs-lang">
                    <div class="form-group">
                      <label for="name">Tiêu đề (vi):</label>
                      <input type="text" class="form-control for-seo text-sm" name="name" id="name"
                        placeholder="Tiêu đề (vi)" value="<?= $_POST['name'] ?? ($result['name'] ?? "") ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="desc">Mô tả (vi):</label>
                      <textarea class="form-control for-seo text-sm form-control-ckeditor" name="desc" id="desc"
                        rows="5" placeholder="Mô tả (vi)"><?= $_POST['desc'] ?? ($result['desc'] ?? "") ?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="content">Nội dung (vi):</label>
                      <textarea class="form-control for-seo text-sm form-control-ckeditor" name="content"
                        id="content" placeholder="Nội dung (vi)"><?= $_POST['content'] ?? ($result['content'] ?? "") ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Danh mục <?= $name ?></h3>
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
                  <?php if ($show_danhmuc): ?>
                    <?php while ($row = $show_danhmuc->fetch_assoc()): ?>
                      <option value="<?= $row['id'] ?>"
                        <?= (($_POST['id_list'] ?? ($result['id_list'] ?? '')) == $row['id']) ? 'selected' : '' ?>>
                        <?= $row['name'] ?>
                      </option>
                    <?php endwhile; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="form-group col-xl-6 col-sm-4">
                <label class="d-block" for="id_cat">Danh mục cấp 2:</label>
                <select id="id_cat" name="id_cat" data-level="1" data-type="san-pham" data-table="" data-child="id_item"
                  class="form-control select2 select-category">
                  <option value="0">Chọn danh mục</option>
                  <?php if (!empty($id) && $show_danhmuc_c2): ?>
                    <?php while ($get_c2 = $show_danhmuc_c2->fetch_assoc()): ?>
                      <option value="<?= $get_c2['id'] ?>" <?= ($get_c2['id'] == $result['id_cat']) ? "selected" : ''; ?>>
                        <?= $get_c2['name'] ?>
                      </option>
                    <?php endwhile; ?>
                  <?php endif; ?>
                </select>
              </div>

            </div>

          </div>
        </div>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Thông tin <?= $name ?></h3>
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
                    <input <?= $functions->is_checked($check, $result ?? null, $id ?? null) ?>
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
            <h3 class="card-title">Hình ảnh Sản phẩm</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="photoUpload-zone">
              <div class="photoUpload-detail" id="photoUpload-preview">
                <img src="<?= empty($result['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $result['file']; ?>"
                  alt='Alt Photo' class='rounded' />
              </div>
              <label class="photoUpload-file" id="photo-zone" for="file-zone">
                <input type="file" name="file" id="file-zone">
                <i class="fas fa-cloud-upload-alt"></i>
                <p class="photoUpload-drop">Kéo và thả hình vào đây</p>
                <p class="photoUpload-or">hoặc</p>
                <p class="photoUpload-choose btn btn-sm bg-gradient-success">Chọn hình</p>
              </label>
              <div class="photoUpload-dimension">Width: 540 px - Height: 540 px (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="card card-primary card-outline text-sm">
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
            data-hash="<?= $functions->generateHash() ?>"
            class="form-control-file" />
          <input type="hidden" class="col-filer" value="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
          <input type="hidden" class="act-filer" value="man">
          <input type="hidden" class="folder-filer" value="product">
        </div>
      </div>
    </div> -->

    <?php include 'templates/seo.php'; ?>
  </form>
</section>
