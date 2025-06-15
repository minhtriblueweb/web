<?php
$redirect_url = 'category_lv1_list';
$records_per_page = 10;
$current_page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1); // tránh 0 hoặc âm
$total_records = $functions->phantrang_sp('tbl_danhmuc');
$total_pages = ceil($total_records / $records_per_page);
$show_danhmuc = $danhmuc->show_danhmuc('tbl_danhmuc', $records_per_page, $current_page);
$linkMulti = "index.php?page=deleteMulti&table=tbl_danhmuc&image=file&redirect=category_lv1_list";
$linkDelete = "index.php?page=delete&table=tbl_danhmuc&image=file&redirect=category_lv1_list&id=";
$linkEdit = "index.php?page=category_lv1_form&id=";

?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item">Danh mục cấp 1</li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="?page=category_lv1_form" title="Thêm mới"><i
        class="fas fa-plus mr-2"></i>Thêm mới</a>
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?= $linkMulti ?>" title="Xóa tất cả"><i
        class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
    <div class="form-inline form-search d-inline-block align-middle ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar text-sm" type="search" id="keyword" placeholder="Tìm kiếm"
          aria-label="Tìm kiếm" value=""
          onkeypress="doEnter(event,'keyword','index.php?com=product&act=man_list&type=san-pham')" />
        <div class="input-group-append bg-primary rounded-right">
          <button class="btn btn-navbar text-white" type="button"
            onclick="onSearch('keyword','index.php?com=product&act=man_list&type=san-pham')">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="card card-primary card-outline text-sm mb-0">
    <div class="card-header">
      <h3 class="card-title">Danh sách Danh mục cấp 1</h3>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover">
        <thead>
          <tr>
            <th class="align-middle" width="5%">
              <div class="custom-control custom-checkbox my-checkbox">
                <input type="checkbox" class="custom-control-input" id="selectall-checkbox" />
                <label for="selectall-checkbox" class="custom-control-label"></label>
              </div>
            </th>
            <th class="align-middle text-center" width="10%">STT</th>
            <th class="align-middle">Hình</th>
            <th class="align-middle" style="width: 30%">Tiêu đề</th>
            <th class="align-middle text-center">Hiển thị</th>
            <th class="align-middle text-center">Nổi bật</th>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <form action="" method="POST">
          <tbody>
            <?php if ($show_danhmuc):
              while ($resule = $show_danhmuc->fetch_assoc()):
                $id = $resule['id'];
                $namevi = $resule['namevi'];
                $file = empty($resule['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $resule['file'];
                $hienthi = $resule['hienthi'] === 'hienthi';
                $noibat = $resule['noibat'] === 'noibat';
            ?>
                <tr>
                  <!-- Checkbox chọn -->
                  <td class="align-middle">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input select-checkbox"
                        id="select-checkbox-<?= $id ?>" value="<?= $id ?>" name="checkbox_id<?= $id ?>" />
                      <label for="select-checkbox-<?= $id ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- Số thứ tự -->
                  <td class="align-middle">
                    <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                      value="<?= $resule['numb'] ?>" data-id="<?= $id ?>" data-table="tbl_danhmuc" />
                  </td>

                  <!-- Ảnh -->
                  <td class="align-middle">
                    <a href="<?= $linkEdit ?><?= $id ?>" title="<?= $namevi ?>">
                      <img src="<?= $file ?>" alt="<?= $namevi ?>" class="rounded img-preview" />
                    </a>
                  </td>

                  <!-- Tên -->
                  <td class="align-middle">
                    <a class="text-dark text-break" href="<?= $linkEdit ?><?= $id ?>" title="<?= $namevi ?>">
                      <?= $namevi ?>
                    </a>
                  </td>

                  <!-- Hiển thị -->
                  <td class="align-middle text-center">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input show-checkbox"
                        id="show-checkbox-hienthi-<?= $id ?>" data-table="tbl_danhmuc"
                        data-id="<?= $id ?>" data-type="hienthi"
                        data-attr="<?= $hienthi ? '' : 'hienthi' ?>" <?= $hienthi ? 'checked' : '' ?> />
                      <label for="show-checkbox-hienthi-<?= $id ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- Nổi bật -->
                  <td class="align-middle text-center">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input show-checkbox"
                        id="show-checkbox-noibat-<?= $id ?>" data-table="tbl_danhmuc"
                        data-id="<?= $id ?>" data-type="noibat"
                        data-attr="<?= $noibat ? '' : 'noibat' ?>" <?= $noibat ? 'checked' : '' ?> />
                      <label for="show-checkbox-noibat-<?= $id ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- Hành động -->
                  <td class="align-middle text-center text-md text-nowrap">
                    <a class="text-primary mr-2" href="<?= $linkEdit ?>" title="Chỉnh sửa">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a class="text-danger" id="delete-item" data-url="<?= $linkDelete ?><?= $id ?>" title="Xóa">
                      <i class="fas fa-trash-alt"></i>
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="100" class="text-center">Không có dữ liệu</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </form>
      </table>
    </div>

  </div>
  <?php if ($total_pages > 2): ?>
    <div class="card-footer text-sm pb-0 mb-5">
      <?= $pagination_html = $functions->renderPagination($current_page, $total_pages); ?>
    </div>
  <?php endif; ?>
</section>
