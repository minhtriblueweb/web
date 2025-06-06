<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php
$show_danhmuc = $danhmuc->show_danhmuc();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $data = array($_POST);
  // echo "<pre>";
  // print_r($data);
  // echo "</pre>";
  $insert = $sanpham->them_sanpham($_POST, $_FILES);
}
?>
<!-- Main content -->
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item active">Thêm mới Sản phẩm</li>
      </ol>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="add" type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="submit" class="btn btn-sm bg-gradient-success submit-check" name="save-here" disabled><i
          class="far fa-save mr-2"></i>Lưu tại trang</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
      <a class="btn btn-sm bg-gradient-danger" href="sanpham.php" title="Thoát"><i
          class="fas fa-sign-out-alt mr-2"></i>Thoát</a>
    </div>


    <div class="row">
      <div class="col-xl-8">
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Đường dẫn</h3>
            <span class="pl-2 text-danger">(Vui lòng không nhập trùng tiêu đề)</span>
          </div>
          <div class="card-body card-slug">
            <input type="hidden" class="slug-id" value="">
            <input type="hidden" class="slug-copy" value="0">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab-lang" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="tabs-lang" data-toggle="pill" href="#tabs-sluglang-vi" role="tab"
                      aria-controls="tabs-sluglang-vi" aria-selected="true">Tiếng Việt</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent-lang">
                  <div class="tab-pane fade show active" id="tabs-sluglang-vi" role="tabpanel"
                    aria-labelledby="tabs-lang">
                    <div class="form-gourp mb-0">
                      <label class="d-block">Đường dẫn mẫu (vi):<span class="pl-2 font-weight-normal"
                          id="slugurlpreviewvi"><?= $config['base'] ?><strong class="text-info"></strong></span></label>
                      <input type="text" class="form-control slug-input no-validate text-sm" name="slugvi" id="slugvi"
                        placeholder="Đường dẫn (vi)" value="<?php if (!empty($slugvi)) {
                                                              echo $slugvi;
                                                            } ?>" required>
                      <input type="hidden" id="slug-defaultvi" value="">
                      <?php
                      if (isset($insert)) {
                      ?>
                      <p class="alert-slugvi text-danger mt-2 mb-0" id="alert-slug-dangervi">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span><?php echo $insert; ?></span>
                      </p>
                      <?php } ?>
                      <p class="alert-slugvi text-success d-none mt-2 mb-0" id="alert-slug-successvi">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span>Đường dẫn hợp lệ.</span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Nội dung Sản phẩm</h3>
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
                      <label for="namevi">Tiêu đề (vi):</label>
                      <input type="text" class="form-control for-seo text-sm" name="namevi" id="namevi"
                        placeholder="Tiêu đề (vi)" value="" required>
                    </div>
                    <div class="form-group">
                      <label for="descvi">Mô tả (vi):</label>
                      <textarea class="form-control for-seo text-sm form-control-ckeditor" name="descvi" id="descvi"
                        rows="5" placeholder="Mô tả (vi)"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="contentvi">Nội dung (vi):</label>
                      <textarea class="form-control for-seo text-sm form-control-ckeditor" name="contentvi"
                        id="contentvi" placeholder="Nội dung (vi)"></textarea>
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
            <h3 class="card-title">Danh mục Sản phẩm</h3>
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
                  <?php while ($resule_danhmuc = $show_danhmuc->fetch_assoc()): ?>
                  <option value="<?= $resule_danhmuc['id'] ?>">
                    <?= $resule_danhmuc['namevi'] ?>
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
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Thông tin Sản phẩm</h3>
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
                    id="regular_price" placeholder="Giá" value="">
                  <div class="input-group-append">
                    <div class="input-group-text"><strong>VNĐ</strong></div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="d-block" for="sale_price">Giá mới:</label>
                <div class="input-group">
                  <input type="text" class="form-control format-price sale_price text-sm" name="sale_price"
                    id="sale_price" placeholder="Giá mới" value="">
                  <div class="input-group-append">
                    <div class="input-group-text"><strong>VNĐ</strong></div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-4">
                <label class="d-block" for="discount">Chiếc khấu:</label>
                <div class="input-group">
                  <input type="text" class="form-control discount text-sm" name="discount" id="discount"
                    placeholder="Chiếc khấu" value="" maxlength="3" readonly>
                  <div class="input-group-append">
                    <div class="input-group-text"><strong>%</strong></div>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-8">
                <label class="d-block" for="code">Mã sản phẩm:</label>
                <input type="text" class="form-control text-sm" name="code" id="code" placeholder="Mã sản phẩm"
                  value="">
              </div>
            </div>
            <div class="form-group">
              <div class="form-group d-inline-block mb-2 mr-2">
                <label for="hienthi-checkbox" class="d-inline-block align-middle mb-0 mr-2">Hiển thị:</label>
                <div class="custom-control custom-checkbox d-inline-block align-middle">
                  <input type="checkbox" class="custom-control-input hienthi-checkbox" name="hienthi"
                    id="hienthi-checkbox" checked value="hienthi">
                  <label for="hienthi-checkbox" class="custom-control-label"></label>
                </div>
              </div>
              <div class="form-group d-inline-block mb-2 mr-2">
                <label for="banchay-checkbox" class="d-inline-block align-middle mb-0 mr-2">Bán chạy:</label>
                <div class="custom-control custom-checkbox d-inline-block align-middle">
                  <input type="checkbox" class="custom-control-input banchay-checkbox" name="banchay"
                    id="banchay-checkbox" checked value="banchay">
                  <label for="banchay-checkbox" class="custom-control-label"></label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="numb" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
              <input type="number"
                class="form-control form-control-mini w-25 text-left d-inline-block align-middle text-sm" min="0"
                name="numb" id="numb" placeholder="Số thứ tự" value="1">
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
                <img class='rounded' src='./assets/img/noimage.png' alt='Alt Photo' />
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
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label for="filer-gallery" class="label-filer-gallery mb-3">Album:
            (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)</label>
          <input type="file" name="files[]" id="filer-gallery" multiple="multiple">
          <input type="hidden" class="col-filer" value="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
          <input type="hidden" class="act-filer" value="man">
          <input type="hidden" class="folder-filer" value="product">
        </div>
      </div>
    </div> -->

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
              <div class="tab-content" id="custom-tabs-three-tabContent-lang">
                <div class="tab-pane fade show active" id="tabs-seolang-vi" role="tabpanel" aria-labelledby="tabs-lang">
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="titlevi">SEO Title (vi):</label>
                      <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                    </div>
                    <input type="text" class="form-control check-seo title-seo text-sm" name="titlevi" id="titlevi"
                      placeholder="SEO Title (vi)" value="">
                  </div>
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="keywordsvi">SEO Keywords (vi):</label>
                      <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                    </div>
                    <input type="text" class="form-control check-seo keywords-seo text-sm" name="keywordsvi"
                      id="keywordsvi" placeholder="SEO Keywords (vi)" value="">
                  </div>
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="descriptionvi">SEO Description (vi):</label>
                      <strong class="count-seo"><span>0</span>/160 ký tự</strong>
                    </div>
                    <textarea class="form-control check-seo description-seo text-sm" name="descriptionvi"
                      id="descriptionvi" rows="5" placeholder="SEO Description (vi)"></textarea>
                  </div>

                </div>
              </div>
            </div>
            <input type="hidden" id="seo-create" value="vi">
          </div>
        </div>
      </div>
    </div>
    <!-- 
    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title">Schema JSON <a
            href="https://developers.google.com/search/docs/advanced/structured-data/search-gallery"
            target="_blank">(Tài liệu tham khảo)</a></h3>
        <button type="submit" class="btn btn-sm bg-gradient-success float-right submit-check" name="build-schema"><i
            class="far fa-save mr-2"></i>Lưu và tạo tự động Schema</button>
      </div>
      <div class="card-body">
        <div class="card-seo">
          <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab-lang" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="tabs-lang-schema" data-toggle="pill" href="#tabs-schemalang-vi"
                    role="tab" aria-controls="tabs-schemalang-vi" aria-selected="true">Schema JSON (Tiếng Việt)</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="custom-tabs-one-tabContent-lang">
                <div class="tab-pane fade show active" id="tabs-schemalang-vi" role="tabpanel"
                  aria-labelledby="tabs-lang">
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="schemavi">Schema JSON(vi):</label>
                    </div>
                    <textarea class="form-control schema-seo" name="schemavi" id="schemavi" rows="15"
                      placeholder="Nếu quý khách không biết cách sử dụng Data Structure vui lòng không nhập nội dung vào khung này để tránh phát sinh lỗi..."></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> <input type="hidden" id="schema-type" value="product">
      </div>
    </div> -->
  </form>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>