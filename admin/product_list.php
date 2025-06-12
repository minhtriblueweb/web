<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<!-- Main content -->
<?php
$redirect_url = pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME);
$records_per_page = 20; // Số bản ghi trên mỗi trang
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_records = $functions->phantrang_sp('tbl_sanpham');
$total_pages = ceil($total_records / $records_per_page);
$show_sanpham = $sanpham->show_sanpham($records_per_page, $current_page);
$show_danhmuc = $danhmuc->show_danhmuc('tbl_danhmuc');
if ($id = ($_GET['del'] ?? null)) {
  $del = $functions->delete($id, 'tbl_sanpham', 'file', $redirect_url);
}

if ($listid = ($_GET['listid'] ?? null)) {
  $xoanhieu = $functions->deleteMultiple($listid, 'tbl_sanpham', 'file', $redirect_url);
}
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item">Sản phẩm</li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="product_form.php" title="Thêm mới"><i
        class="fas fa-plus mr-2"></i>Thêm mới</a>
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="?xoa=1" title="Xóa tất cả"><i
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
  <div class="card-footer form-group-category text-sm bg-light row">
    <div class="form-group col-xl-2 col-lg-3 col-md-4 col-sm-4 mb-2"><select id="id_list" name="id_list"
        .onchange="onchangeCategory($(this))" class="form-control filter-category select2">
        <option value="0">Chọn danh mục</option>
        <?php if ($show_danhmuc) : ?>
          <?php while ($resule_danhmuc = $show_danhmuc->fetch_assoc()) : ?>
            <option value="<?= $resule_danhmuc['id'] ?>"><?= $resule_danhmuc['namevi'] ?></option>
          <?php endwhile; ?>
        <?php endif; ?>
      </select></div>
    <div class="form-group col-xl-2 col-lg-3 col-md-4 col-sm-4 mb-2"><select id="id_cat" name="id_cat"
        .onchange="onchangeCategory($(this))" class="form-control filter-category select2">
        <option value="0">Chọn danh mục cấp 2</option>
      </select></div>
  </div>
  <div class="card card-primary card-outline text-sm mb-0">
    <div class="card-header">
      <h3 class="card-title">Danh sách sản phẩm</h3>
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
            <th class="align-middle text-center" style="width: 10%">STT</th>
            <th class="align-middle">Hình</th>
            <th class="align-middle" style="width: 30%">Tiêu đề</th>
            <th class="align-middle">Gallery</th>
            <th class="align-middle">Danh mục</th>
            <th class="align-middle">Danh mục cấp 2</th>
            <th class="align-middle text-center">Hiển thị</th>
            <th class="align-middle text-center">Bán chạy</th>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <form action="" method="POST">
          <tbody>
            <?php if ($show_sanpham): ?>
              <?php while ($row = $show_sanpham->fetch_assoc()):
                $id = $row['id'];
                $namevi = $row['namevi'];
                $slugvi = $row['slugvi'];
                $img = !empty($row['file'])
                  ? BASE_ADMIN . UPLOADS . $row['file']
                  : NO_IMG;
                $link = "product_form.php?id=$id";
              ?>
                <tr>
                  <!-- Checkbox chọn -->
                  <td class="align-middle">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input select-checkbox" id="select-checkbox-<?= $id ?>" value="<?= $id ?>" name="checkbox_id<?= $id ?>" />
                      <label for="select-checkbox-<?= $id ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- Số thứ tự -->
                  <td class="align-middle">
                    <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                      value="<?= $row['numb'] ?>" data-id="<?= $id ?>" data-table="tbl_sanpham" />
                  </td>

                  <!-- Ảnh sản phẩm -->
                  <td class="align-middle">
                    <a href="<?= $link ?>" title="<?= $namevi ?>">
                      <img class="rounded img-preview" src="<?= $img ?>" alt="<?= $namevi ?>" />
                    </a>
                  </td>

                  <!-- Tên sản phẩm -->
                  <td class="align-middle">
                    <a class="text-dark text-break" href="<?= $link ?>" title="<?= $namevi ?>"><?= $namevi ?></a>
                    <div class="tool-action mt-2 w-clear">
                      <a class="text-primary mr-3" href="<?= "{$config['base']}$slugvi" ?>" target="_blank" title="Xem"><i class="far fa-eye mr-1"></i>View</a>
                      <a class="text-info mr-3" href="<?= $link ?>" title="Chỉnh sửa"><i class="far fa-edit mr-1"></i>Edit</a>
                      <a class="text-danger" id="delete-item" data-url="?del=<?= $id ?>" title="Xóa"><i class="far fa-trash-alt mr-1"></i>Delete</a>
                    </div>
                  </td>

                  <!-- Thêm -->
                  <td class="align-middle">
                    <div class="dropdown">
                      <button type="button" class="btn btn-sm bg-gradient-success dropdown-toggle" data-toggle="dropdown">Thêm</button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-dark" href="gallery.php?id=<?= $id ?>" title="Hình ảnh">
                          <i class="far fa-caret-square-right text-secondary mr-2"></i>Hình ảnh
                        </a>
                      </div>
                    </div>
                  </td>

                  <!-- Danh mục cấp 1 -->
                  <td class="align-middle">
                    <?= $sanpham->get_name_danhmuc($row['id_list'], 'tbl_danhmuc') ?>
                  </td>

                  <!-- Danh mục cấp 2 -->
                  <td class="align-middle">
                    <?= $sanpham->get_name_danhmuc($row['id_cat'], 'tbl_danhmuc_c2') ?>
                  </td>

                  <!-- Hiển thị -->
                  <td class="align-middle text-center">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input show-checkbox" data-type="hienthi"
                        id="show-checkbox-hienthi-<?= $id ?>" data-table="tbl_sanpham" data-id="<?= $id ?>"
                        data-attr="<?= $row['hienthi'] == 'hienthi' ? '' : 'hienthi' ?>"
                        <?= $row['hienthi'] == 'hienthi' ? 'checked' : '' ?> />
                      <label for="show-checkbox-hienthi-<?= $id ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- Bán chạy -->
                  <td class="align-middle text-center">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input show-checkbox" data-type="banchay"
                        id="show-checkbox-banchay-<?= $id ?>" data-table="tbl_sanpham" data-id="<?= $id ?>"
                        data-attr="<?= $row['banchay'] == 'banchay' ? '' : 'banchay' ?>"
                        <?= $row['banchay'] == 'banchay' ? 'checked' : '' ?> />
                      <label for="show-checkbox-banchay-<?= $id ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- Hành động -->
                  <td class="align-middle text-center text-md text-nowrap">
                    <a class="text-primary mr-2" href="<?= $link ?>" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                    <a class="text-danger" id="delete-item" data-url="?del=<?= $id ?>" title="Xóa"><i class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php endif; ?>
          </tbody>
        </form>
      </table>
    </div>
    <div class="card-footer text-sm pb-0 mb-5">
      <?= $pagination_html = $functions->renderPagination($current_page, $total_pages);
      ?>
    </div>
  </div>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>
