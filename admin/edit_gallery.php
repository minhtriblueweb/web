<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<!-- Main content -->
<?php
if (isset($_GET['id']) && $_GET['id'] != NULL) {
  $id = $_GET['id'];
}
$get_img = $sanpham->get_img_gallery($id);
if ($get_img) {
  $result = $get_img->fetch_assoc();
  $result_id_parent = $result['id_parent'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
  $update = $sanpham->upload_gallery($_POST, $_FILES, $id, $result_id_parent);
}
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item active">Chỉnh sửa hình ảnh sản phẩm</li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="edit" type="submit" class="btn btn-sm bg-gradient-primary submit-check"><i
          class="far fa-save mr-2"></i>Lưu</button>
      <a class="btn btn-sm bg-gradient-danger" href="" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Hình ảnh sản phẩm:</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <div class="upload-file">
            <p>Upload hình ảnh:</p>
            <div class="photoUpload-zone w-25">
              <div class="photoUpload-detail" id="photoUpload-preview">
                <a data-fancybox href="">
                  <img src="<?php if (empty($result['photo'])) {
                              echo $config['baseAdmin'] . "assets/img/noimage.png";
                            } else {
                              echo $config['baseAdmin'] . "uploads/" . $result['photo'];
                            } ?>" alt="Alt Photo" class="rounded" />
                </a>
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
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-group d-inline-block mb-2 mr-2">
            <label for="hienthi-checkbox0" class="d-inline-block align-middle mb-0 mr-2">Hiển thị:</label>
            <div class="custom-control custom-checkbox d-inline-block align-middle">
              <input type="checkbox" class="custom-control-input hienthi-checkbox0" name="hienthi"
                id="hienthi-checkbox0" value="hienthi" <?php if ($result['hienthi'] == 'hienthi') {
                                                          echo "checked";
                                                        }
                                                        ?>>
              <label for="hienthi-checkbox0" class="custom-control-label"></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="numb0" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
          <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
            name="numb" id="numb0" placeholder="Số thứ tự" value="<?php echo $result['numb']; ?>">
        </div>
      </div>
    </div>
  </form>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>