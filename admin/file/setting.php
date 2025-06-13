<?php include('inc/header.php'); ?>
<?php include('inc/sidebar.php'); ?>
<!-- Main content -->
<?php
$get_setting = $setting->get_setting();
if ($get_setting) {
  $result = $get_setting->fetch_assoc();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $update = $setting->update_setting($_POST);
}
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item active">Thông tin công ty</li>
      </ol>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Thông tin chung</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="form-group col-md-4 col-sm-6">
            <label for="email">Email:</label>
            <input type="email" class="form-control text-sm" name="email" id="email" placeholder="Email"
              value="<?= isset($result['email']) ? htmlspecialchars($result['email']) : ''; ?>">
          </div>
          <div class="form-group col-md-4 col-sm-6">
            <label for="hotline">Hotline tư vấn:</label>
            <input type="text" class="form-control text-sm" name="hotline" id="hotline" placeholder="Hotline tư vấn"
              value="<?= isset($result['hotline']) ? htmlspecialchars($result['hotline']) : ''; ?>">
          </div>
          <div class="form-group col-md-4 col-sm-6">
            <label for="web_name">Tên Website:</label>
            <input type="text" class="form-control text-sm" name="web_name" id="web_name" placeholder="Tên Website"
              value="<?= isset($result['web_name']) ? htmlspecialchars($result['web_name']) : ''; ?>">
          </div>
          <div class="form-group col-md-4 col-sm-6">
            <label for="introduction">Lời giới thiệu:</label>
            <input type="text" class="form-control text-sm" name="introduction" id="introduction"
              placeholder="Lời giới thiệu"
              value="<?= isset($result['introduction']) ? htmlspecialchars($result['introduction']) : ''; ?>">
          </div>

          <div class="form-group col-md-4 col-sm-6">
            <label for="fanpage">Fanpage:</label>
            <input type="text" class="form-control text-sm" name="fanpage" id="fanpage" placeholder="Fanpage"
              value="<?= isset($result['fanpage']) ? htmlspecialchars($result['fanpage']) : ''; ?>">
          </div>
          <div class="form-group col-md-4 col-sm-6">
            <label for="copyright">Copyright:</label>
            <input type="text" class="form-control text-sm" name="copyright" id="copyright" placeholder="Copyright"
              value="<?= isset($result['copyright']) ? htmlspecialchars($result['copyright']) : ''; ?>">
          </div>
          <div class="form-group col-md-4 col-sm-6">
            <label for="link_googlemaps">Link google maps:</label>
            <input type="text" class="form-control text-sm" name="link_googlemaps" id="link_googlemaps"
              placeholder="Link google maps"
              value="<?= isset($result['link_googlemaps']) ? htmlspecialchars($result['link_googlemaps']) : ''; ?>">
          </div>
          <div class="form-group col-md-4 col-sm-6">
            <label for="coords">Tọa độ google map:</label>
            <input type="text" class="form-control text-sm" name="coords" id="coords" placeholder="Tọa độ google map"
              value="<?= isset($result['coords']) ? htmlspecialchars($result['coords']) : ''; ?>">
          </div>
          <div class="form-group col-md-4 col-sm-6">
            <label for="worktime">Giờ làm việc:</label>
            <input type="text" class="form-control text-sm" name="worktime" id="worktime" placeholder="Giờ làm việc"
              value="<?= isset($result['worktime']) ? htmlspecialchars($result['worktime']) : ''; ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="descvi">Mô tả:</label>
          <textarea class="form-control text-sm" name="descvi" id="descvi" rows="5"
            placeholder="Mô tả"><?= isset($result['descvi']) ? htmlspecialchars($result['descvi']) : ''; ?></textarea>
        </div>
        <div class="form-group">
          <label for="coords_iframe">
            <span>Tọa độ google map iframe:</span>
            <a class="text-sm font-weight-normal ml-1" href="https://www.google.com/maps" target="_blank"
              title="Lấy mã nhúng google map">(Lấy mã nhúng)</a>
          </label>
          <textarea class="form-control text-sm" name="coords_iframe" id="coords_iframe" rows="5"
            placeholder="Tọa độ google map iframe"><?= isset($result['coords_iframe']) ? htmlspecialchars($result['coords_iframe']) : ''; ?></textarea>
        </div>
        <div class="form-group">
          <label for="analytics">Google analytics:</label>
          <textarea class="form-control text-sm" name="analytics" id="analytics" rows="5"
            placeholder="Google analytics"><?= isset($result['analytics']) ? htmlspecialchars($result['analytics']) : ''; ?></textarea>
        </div>
        <div class="form-group">
          <label for="headjs">Head JS:</label>
          <textarea class="form-control text-sm" name="headjs" id="headjs" rows="5"
            placeholder="Head JS"><?= isset($result['headjs']) ? htmlspecialchars($result['headjs']) : ''; ?></textarea>
        </div>
        <div class="form-group">
          <label for="bodyjs">Body JS:</label>
          <textarea class="form-control text-sm" name="bodyjs" id="bodyjs" rows="5"
            placeholder="Body JS"><?= isset($result['bodyjs']) ? htmlspecialchars($result['bodyjs']) : ''; ?></textarea>
        </div>
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
                  <label for="support">Hỗ trợ 24/7:</label>
                  <textarea class="form-control for-seo text-sm form-control-ckeditor" name="support" id="support"
                    rows="2"><?= isset($result['support']) ? htmlspecialchars($result['support']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                  <label for="client_support">Hỗ trợ khách hàng:</label>
                  <textarea class="form-control for-seo text-sm form-control-ckeditor" name="client_support"
                    id="client_support"
                    rows="2"><?= isset($result['client_support']) ? htmlspecialchars($result['client_support']) : ''; ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-footer text-sm">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
      <input type="hidden" name="id" value="1">
    </div>
  </form>

</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>
<script>
CKEDITOR.replace('support', {
  height: 200
});
CKEDITOR.replace('client_support', {
  height: 200
});
</script>