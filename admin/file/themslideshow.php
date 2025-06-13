<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<?php	
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $data = array($_POST);
    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
    $insert = $slideshow -> insert_slideshow($_POST,$_FILES);
    }
?>
<!-- Main content -->
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item"><a href="slideshow.php" title="Slideshow">Slideshow</a></li>
        <li class="breadcrumb-item active">Thêm mới Slideshow</li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate="" method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" class="btn btn-sm bg-gradient-primary submit-check " name="add"><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
      <a class="btn btn-sm bg-gradient-danger" href="slideshow.php" title="Thoát"><i
          class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Slideshow:</h3>
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
                <a data-fancybox href=""><img class="rounded" src="./assets/img/noimage.png" alt="Alt Photo" /></a>
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
                Width: 510 px - Height: 350 px
                (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="link0">Link:</label>
          <input type="text" class="form-control text-sm" name="link" id="link0" placeholder="Link" value="">
        </div>
        <div class="form-group">
          <div class="form-group d-inline-block mb-2 mr-2">
            <label for="hienthi-checkbox0" class="d-inline-block align-middle mb-0 mr-2">Hiển thị:</label>
            <div class="custom-control custom-checkbox d-inline-block align-middle">
              <input type="checkbox" class="custom-control-input hienthi-checkbox0" name="hienthi"
                id="hienthi-checkbox0" value="hienthi" checked="">
              <label for="hienthi-checkbox0" class="custom-control-label"></label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="numb0" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
          <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
            name="numb" id="numb0" placeholder="Số thứ tự" value="1">
        </div>
        <div class="card card-primary card-outline card-outline-tabs">
          <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="tabs-lang" data-toggle="pill" href="#tabs-lang-vi-0" role="tab"
                  aria-controls="tabs-lang-vi-0" aria-selected="true">Tiếng Việt</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="custom-tabs-three-tabContent-lang">
              <div class="tab-pane fade show active" id="tabs-lang-vi-0" role="tabpanel" aria-labelledby="tabs-lang">
                <div class="form-group">
                  <label for="namevi0">Tiêu đề (vi):</label>
                  <input type="text" class="form-control text-sm" name="name" id="namevi0" placeholder="Tiêu đề (vi)"
                    value="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="hash" value="YZNCQ27qVh">
  </form>
</section>
<?php include 'inc/footer.php';?>