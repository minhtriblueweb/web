<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<!-- Main content -->
<?php
if (isset($_GET['id']) && $_GET['id'] != NULL) {
  $id = $_GET['id'];
}
$get_id = $sanpham->get_id_sanpham($id);
$get_gallery = $sanpham->get_gallery($id);
if ($get_id) {
  $result = $get_id->fetch_assoc();
  $result_id = $result['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $insert = $sanpham->them_gallery($_POST, $_FILES, $result_id);
}
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item active">Thêm mới Hình ảnh sản phẩm</li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="add" type="submit" class="btn btn-sm bg-gradient-primary submit-check"><i
          class="far fa-save mr-2"></i>Lưu</button>
      <a class="btn btn-sm bg-gradient-danger" href="" title="Thoát"><i class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
    </div>
    <?php for ($i = 0; $i < 5; $i++) { ?>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Hình ảnh sản phẩm: <?= $i + 1; ?></h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <div class="upload-file">
            <p>Upload hình ảnh:</p>
            <div class="photoUpload-zone w-25">
              <div class="photoUpload-detail" id="photoUpload-preview<?= $i; ?>">
                <a data-fancybox href=""><img class="rounded" src="./assets/img/noimage.png" alt="Alt Photo" /></a>
              </div>
              <label class="photoUpload-file" id="photo-zone<?= $i; ?>" for="file-zone<?= $i; ?>">
                <input type="file" name="file<?= $i; ?>" id="file-zone<?= $i; ?>" />
                <i class="fas fa-cloud-upload-alt"></i>
                <p class="photoUpload-drop">Kéo và thả hình vào đây</p>
                <p class="photoUpload-or">hoặc</p>
                <p class="photoUpload-choose btn btn-sm bg-gradient-success">
                  Chọn hình
                </p>
              </label>
            </div>
            <strong class="d-block text-sm">Width: 540 px - Height: 540 px
              (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)</strong>
          </div>
        </div>
        <div class="form-group">
          <div class="form-group d-inline-block mb-2 mr-2">
            <label for="hienthi-checkbox<?= $i; ?>" class="d-inline-block align-middle mb-0 mr-2">Hiển thị:</label>
            <div class="custom-control custom-checkbox d-inline-block align-middle">
              <input type="checkbox" class="custom-control-input" name="hienthi<?= $i; ?>"
                id="hienthi-checkbox<?= $i; ?>" value="hienthi" checked="">
              <label for="hienthi-checkbox<?= $i; ?>" class="custom-control-label"></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="numb<?= $i; ?>" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
          <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
            name="numb<?= $i; ?>" id="numb<?= $i; ?>" placeholder="Số thứ tự" value="<?= $i + 1; ?>">
        </div>
      </div>
    </div>
    <?php
    }
    ?>
  </form>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>