<?php include('inc/header.php'); ?>
<?php include('inc/sidebar.php'); ?>
<!-- Main content -->
<?php
$get_muahang = $trangtinh->get_static('muahang');
if ($get_muahang) {
  $result_muahang = $get_muahang->fetch_assoc();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
  $update = $trangtinh->update_static($_POST, 'muahang', $result_muahang['id']);
}
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item active">mua hàng</li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate="" method="post" id="form-watermark" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="upload" type="submit" class="btn btn-sm bg-gradient-primary submit-check"><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Nội dung mua hàng</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body">
        <div class="card card-primary card-outline card-outline-tabs">
          <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
              <li class="nav-item">
                <a class="nav-link active">Tiếng Việt</a>
              </li>
            </ul>
          </div>
          <div class="card-body card-article">
            <div class="tab-content" id="custom-tabs-three-tabContent-lang">
              <div class="tab-pane fade show active" id="tabs-lang-vi" role="tabpanel" aria-labelledby="tabs-lang">
                <div class="form-group">
                  <label for="namevi">Tiêu đề (vi):</label>
                  <input type="text" class="form-control for-seo text-sm" name="namevi" id="namevi"
                    placeholder="Tiêu đề (vi)" value="<?= $result_muahang['namevi'] ?>" required>
                </div>
                <div class="form-group">
                  <label for="contentvi">Nội dung (vi):</label>
                  <textarea class="form-control for-seo text-sm form-control-ckeditor" name="contentvi" id="contentvi"
                    placeholder="Nội dung (vi)"><?= $result_muahang['contentvi'] ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Nội dung SEO</h3>
        <a class="btn btn-sm bg-gradient-success d-inline-block text-white float-right create-seo" title="Tạo SEO">Tạo
          SEO</a>
      </div>
      <div class="card-body">
        <!-- SEO -->
        <div class="card-seo">
          <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
              <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="tabs-lang" data-toggle="pill" href="#tabs-seolang-vi" role="tab"
                    aria-controls="tabs-seolang-vi" aria-selected="true">SEO (Tiếng Việt)</a>
                </li>
              </ul>
            </div>
 <div class="card-body">
        <!-- SEO -->
        <div class="card-seo">
          <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
              <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="tabs-lang" data-toggle="pill" href="#tabs-seolang-vi" role="tab"
                    aria-controls="tabs-seolang-vi" aria-selected="true">SEO (Tiếng Việt)</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="custom-tabs-three-tabContent-lang">
                <div class="tab-pane fade show active" id="tabs-seolang-vi" role="tabpanel" aria-labelledby="tabs-lang">
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="titlevi">SEO Title (vi):</label>
                      <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                    </div>
                    <input type="text" class="form-control check-seo title-seo text-sm" name="titlevi" id="titlevi"
                      placeholder="SEO Title (vi)" value="<?= $result_muahang['titlevi'] ?>">
                  </div>
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="keywordsvi">SEO Keywords (vi):</label>
                      <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                    </div>
                    <input type="text" class="form-control check-seo keywords-seo text-sm" name="keywordsvi"
                      id="keywordsvi" placeholder="SEO Keywords (vi)" value="<?= $result_muahang['keywordsvi'] ?>">
                  </div>
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="descriptionvi">SEO Description (vi):</label>
                      <strong class="count-seo"><span>0</span>/160 ký tự</strong>
                    </div>
                    <textarea class="form-control check-seo description-seo text-sm" name="descriptionvi"
                      id="descriptionvi" rows="5" placeholder="SEO Description (vi)"><?= $result_muahang['descriptionvi'] ?></textarea>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" id="seo-create" value="vi">
          </div>
        </div>
      </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="type" id="type" value="muahang">
  </form>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>