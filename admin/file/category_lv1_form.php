<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php
$message = '';
$name = 'danh mục cấp 1';
$redirectUrl = 'category_lv1_list'; // nút thoát
if (!empty($_GET['id'])) {
  $id = $_GET['id'];
  if ($get_id = $danhmuc->get_id_danhmuc($id)) {
    $result = $get_id->fetch_assoc();
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $update = $danhmuc->update_danhmuc($_POST, $_FILES, $id);
    if (!empty($update)) $message = $update;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $insert = $danhmuc->insert_danhmuc($_POST, $_FILES);
  if (!empty($insert)) $message = $insert;
}
?>

<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => 'index.php'],
  ['label' => 'Danh mục', 'link' => $redirectUrl],
  ['label' => !empty($id) ? 'Cập nhật ' . $name : 'Thêm mới ' . $name]
];
include 'inc/breadcrumb.php';
?>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <?php include 'inc/act.php'; ?>
    <div class="row">
      <div class="col-xl-8">
        <?php include 'inc/slug.php'; ?>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Nội dung <?= $name ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
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
                      <label for="namevi">Tiêu đề (vi):</label>
                      <input type="text" class="form-control for-seo text-sm" name="namevi" id="namevi"
                        placeholder="Tiêu đề (vi)" value="<?= $_POST['namevi'] ?? ($result['namevi'] ?? "") ?>" required />
                    </div>
                    <div class="form-group">
                      <label for="descvi">Mô tả (vi):</label>
                      <textarea class="form-control for-seo text-sm" name="descvi" id="descvi" rows="4"
                        placeholder="Mô tả (vi)"><?= $_POST['descvi'] ?? ($result['descvi'] ?? "") ?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="contentvi">Nội dung (vi):</label>
                      <textarea class="form-control for-seo text-sm form-control-ckeditor" name="contentvi"
                        id="contentvi" placeholder="Nội dung (vi)"><?= $_POST['contentvi'] ?? ($result['contentvi'] ?? "") ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="form-group d-inline-block mb-2 mr-2">
                <label for="hienthi-checkbox" class="d-inline-block align-middle mb-0 mr-2">Hiển thị:</label>
                <div class="custom-control custom-checkbox d-inline-block align-middle">
                  <input <?= $functions->is_checked('hienthi', $result ?? null, $id ?? null) ?> type="checkbox" class="custom-control-input hienthi-checkbox" name="hienthi"
                    id="hienthi-checkbox"
                    value="hienthi" />
                  <label for="hienthi-checkbox" class="custom-control-label"></label>
                </div>
              </div>
              <div class="form-group d-inline-block mb-2 mr-2">
                <label for="noibat-checkbox" class="d-inline-block align-middle mb-0 mr-2">Nổi bật:</label>
                <div class="custom-control custom-checkbox d-inline-block align-middle">
                  <input <?= $functions->is_checked('noibat', $result ?? null, $id ?? null) ?> type="checkbox" class="custom-control-input noibat-checkbox" name="noibat" id="noibat-checkbox" value="noibat">
                  <label for="noibat-checkbox" class="custom-control-label"></label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="numb" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
              <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                name="numb" id="numb" placeholder="Số thứ tự" value="<?= $_POST['numb'] ?? (!empty($id) ? $result['numb'] : '1') ?>" />
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Hình ảnh <?= $name ?></h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="photoUpload-zone">
              <div class="photoUpload-detail" id="photoUpload-preview">
                <a data-fancybox href="">
                  <img src="<?= empty($result['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $result['file']; ?>"
                    class="rounded" alt="Alt Photo" /></a>
              </div>
              <label class="photoUpload-file" id="photo-zone" for="file-zone">
                <input type="file" name="file" id="file-zone" />
                <i class="fas fa-cloud-upload-alt"></i>
                <p class="photoUpload-drop">Kéo và thả hình vào đây</p>
                <p class="photoUpload-or">hoặc</p>
                <p class="photoUpload-choose btn btn-sm bg-gradient-success">
                  Chọn hình
                </p>
              </label>
              <div class="photoUpload-dimension">
                (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include 'inc/seo.php'; ?>
  </form>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>
