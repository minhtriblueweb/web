<?php include 'inc/header.php';?>
<?php include 'inc/sidebar.php';?>
<!-- Main content -->
<?php
  $show_danhmuc = $danhmuc -> show_danhmuc();
  if(isset($_GET['id']) && $_GET['id']!=NULL){
    $id = $_GET['id'];
  }
  $get_id = $danhmuc -> get_id_danhmuc_c2($id);
  if($get_id){
    $result = $get_id -> fetch_assoc();
  }
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $update = $danhmuc -> update_danhmuc_c2($_POST,$_FILES,$id);
  }
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item">Cập nhật Danh mục cấp 2</li>

      </ol>
    </div>
  </div>
</section>
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button name="edit" type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i
          class="far fa-save mr-2"></i>Lưu</button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i>Làm lại</button>
      <a class="btn btn-sm bg-gradient-danger" href="danhmuccap2.php" title="Thoát"><i
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
                          id="slugurlpreviewvi">
                          <?php echo $config['base'] ?>
                          <strong class="text-info">
                            <?php echo $result['slugvi'] ; ?>
                          </strong></span></label>
                      <!-- Slug -->
                      <input type="text" class="form-control slug-input no-validate text-sm" name="slugvi" id="slugvi"
                        placeholder="Đường dẫn (vi)" required value="<?php echo $result['slugvi'] ; ?>" />
                      <input type="hidden" id="slug-defaultvi" value="" />
                      <?php 
                        if(isset($update)){
                      ?>
                      <p class="alert-slugvi text-danger mt-2 mb-0" id="alert-slug-dangervi">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span><?php echo $update ; ?></span>
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
            <h3 class="card-title">Nội dung Danh mục cấp 2</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="form-group">

              <div class="form-group d-inline-block mb-2 mr-2">
                <label for="hienthi-checkbox" class="d-inline-block align-middle mb-0 mr-2">Hiển thị:</label>
                <div class="custom-control custom-checkbox d-inline-block align-middle">
                  <input type="checkbox" class="custom-control-input hienthi-checkbox" name="hienthi"
                    id="hienthi-checkbox" <?php if($result['hienthi'] == 'hienthi'){ echo "checked" ; }
                    ?> value="hienthi">
                  <label for="hienthi-checkbox" class="custom-control-label"></label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="numb" class="d-inline-block align-middle mb-0 mr-2">Số thứ tự:</label>
              <input type="number" class="form-control form-control-mini d-inline-block align-middle text-sm" min="0"
                name="numb" id="numb" placeholder="Số thứ tự" value="<?php echo $result['numb'] ; ?>">
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
                      <label for="namevi">Tiêu đề (vi):</label>
                      <input type="text" class="form-control for-seo text-sm" name="namevi" id="namevi"
                        placeholder="Tiêu đề (vi)" value="<?php echo $result['namevi'] ; ?>" required>
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
            <h3 class="card-title">Danh mục cấp 1</h3>
          </div>
          <div class="card-body">
            <div class="form-group-category">
              <select id="id_list" name="id_list" data-level="0" data-type="san-pham" data-table="#_product_cat"
                data-child="id_cat" class="form-control select2 select-category">
                <option value="0">Chọn danh mục</option>
                <?php
                  if($show_danhmuc) {
                    while($resule_c1 = $show_danhmuc -> fetch_assoc()){
                ?>
                <option value="<?php echo $resule_c1['id'] ; ?>"
                  <?php if($resule_c1['id'] == $result['id_list']){ echo "selected" ; } ?>>
                  <?php echo $resule_c1['namevi'] ; ?>
                </option>
                <?php } } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="card card-primary card-outline text-sm">
          <div class="card-header">
            <h3 class="card-title">Hình ảnh Danh mục cấp 2</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="card-body">
            <div class="photoUpload-zone">
              <div class="photoUpload-detail" id="photoUpload-preview">
                <img class='rounded' src='<?php if(empty($result['file'])){
                    echo $config['baseAdmin']."assets/img/noimage.png";
                  }else{
                    echo $config['baseAdmin']."uploads/".$result['file'];
                  } ?>' alt='Alt Photo' />
              </div>
              <label class="photoUpload-file" id="photo-zone" for="file-zone">
                <input type="file" name="file" id="file-zone">
                <i class="fas fa-cloud-upload-alt"></i>
                <p class="photoUpload-drop">Kéo và thả hình vào đây</p>
                <p class="photoUpload-or">hoặc</p>
                <p class="photoUpload-choose btn btn-sm bg-gradient-success">Chọn hình</p>
              </label>
              <div class="photoUpload-dimension">Width: 300 px - Height: 200 px (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)
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
              <div class="tab-content" id="custom-tabs-three-tabContent-lang">
                <div class="tab-pane fade show active" id="tabs-seolang-vi" role="tabpanel" aria-labelledby="tabs-lang">
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="titlevi">SEO Title (vi):</label>
                      <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                    </div>
                    <input type="text" class="form-control check-seo title-seo text-sm" name="titlevi" id="titlevi"
                      placeholder="SEO Title (vi)" value="<?php echo $result['titlevi'] ; ?>">
                  </div>
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="keywordsvi">SEO Keywords (vi):</label>
                      <strong class="count-seo"><span>0</span>/70 ký tự</strong>
                    </div>
                    <input type="text" class="form-control check-seo keywords-seo text-sm" name="keywordsvi"
                      id="keywordsvi" placeholder="SEO Keywords (vi)" value="<?php echo $result['keywordsvi'] ; ?>">
                  </div>
                  <div class="form-group">
                    <div class="label-seo">
                      <label for="descriptionvi">SEO Description (vi):</label>
                      <strong class="count-seo"><span>0</span>/160 ký tự</strong>
                    </div>
                    <textarea class="form-control check-seo description-seo text-sm" name="descriptionvi"
                      id="descriptionvi" rows="5"
                      placeholder="SEO Description (vi)"><?php echo $result['descriptionvi'] ; ?></textarea>
                  </div>

                </div>
              </div>
            </div>
            <input type="hidden" id="seo-create" value="vi">
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php';?>