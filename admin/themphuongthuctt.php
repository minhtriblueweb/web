<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php
if (isset($_GET['id']) && $_GET['id'] != NULL) {
  $id = $_GET['id'];
  $get_id = $phuongthuctt->get_id_phuongthuctt($id);
  if ($get_id) {
    $result = $get_id->fetch_assoc();
  }
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $update = $phuongthuctt->update_phuongthuctt($_POST, $_FILES, $id);
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $data = array($_POST);
  // echo "<pre>";
  // print_r($data);
  // echo "</pre>";
  $insert = $phuongthuctt->insert_phuongthuctt($_POST, $_FILES);
}
?>
<!-- Main content -->
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item active">
          <?php echo !empty($id) ? "Chỉnh sửa" : "Thêm mới"; ?> Phương thức thanh toán
        </li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="<?php echo !empty($id) ? "edit" : "add"; ?>" type="submit"
        class="btn btn-sm bg-gradient-primary submit-check" disabled><i class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
      <a class="btn btn-sm bg-gradient-danger" href="phuongthuctt.php" title="Thoát"><i
          class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
    </div>
    <div class="row">
      <div class="col-xl-8">
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Nội dung</h3>
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
                      <label for="name">Tiêu đề:</label>
                      <input type="text" class="form-control for-seo text-sm" name="name" id="name"
                        placeholder="Tiêu đề" value="<?php echo $result['name'] ?? ""; ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="contentvi">Nội dung (vi):</label>
                      <textarea class="form-control for-seo text-sm form-control-ckeditor" name="content"
                        id="content" placeholder="Nội dung (vi)"><?php echo $result['content'] ?? ""; ?></textarea>
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
            <h3 class="card-title">Hình ảnh</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="photoUpload-zone">
              <div class="photoUpload-detail" id="photoUpload-preview">
                <img src='<?php
                          echo empty($result['file']) ? $config['baseAdmin'] . "assets/img/noimage.png" : $config['baseAdmin'] . "uploads/" . $result['file'];
                          ?>' class='rounded' alt='Alt Photo' />
              </div>
              <label class="photoUpload-file" id="photo-zone" for="file-zone">
                <input type="file" name="file" id="file-zone">
                <i class="fas fa-cloud-upload-alt"></i>
                <p class="photoUpload-drop">Kéo và thả hình vào đây</p>
                <p class="photoUpload-or">hoặc</p>
                <p class="photoUpload-choose btn btn-sm bg-gradient-success">Chọn hình</p>
              </label>
              <div class="photoUpload-dimension">Width: 30 px - Height: 30 px (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)
              </div>
            </div>
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
          <div class="form-group d-inline-block mb-2 mr-2">
            <label for="hienthi-checkbox" class="d-inline-block align-middle mb-0 mr-2">Hiển thị:</label>
            <div class="custom-control custom-checkbox d-inline-block align-middle">
              <input type="checkbox" class="custom-control-input hienthi-checkbox" name="hienthi" id="hienthi-checkbox"
                checked value="hienthi" <?php echo $result['numb'] ?? "checked"; ?>>
              <label for="hienthi-checkbox" class="custom-control-label"></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="numb" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
          <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
            name="numb" id="numb" placeholder="Số thứ tự" value="<?php echo $result['numb'] ?? "1"; ?>">
        </div>
      </div>
    </div>
  </form>
</section>
<?php include 'inc/footer.php'; ?>