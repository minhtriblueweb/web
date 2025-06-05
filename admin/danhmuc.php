<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<!-- Main content -->
<?php
$records_per_page = 10; // Số bản ghi trên mỗi trang
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
$total_records = $functions->phantrang_sp('tbl_danhmuc'); // Lấy tổng số bản ghi
$total_pages = ceil($total_records / $records_per_page); // Tính số trang
$show_danhmuc = $danhmuc->show_danhmuc_pt($records_per_page, $current_page);
if (isset($_GET['del'])) {
  $id = $_GET['del'];
  $del = $danhmuc->del_danhmuc($id);
}
if (isset($_GET['listid'])) {
  $listid = $_GET['listid'];
  // print_r($listid);
  $xoanhieu = $danhmuc->xoanhieu_danhmuc($listid);
}
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
    <a class="btn btn-sm bg-gradient-primary text-white" href="themdanhmuc.php" title="Thêm mới"><i
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
            <?php
            if ($show_danhmuc) {
              $i = 0;
              while ($resule = $show_danhmuc->fetch_assoc()) {
                $i++;
            ?>
                <tr>
                  <td class="align-middle">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input select-checkbox"
                        id="select-checkbox-<?php echo $resule['id']; ?>" value="<?php echo $resule['id']; ?>"
                        name="checkbox_id<?php echo $resule['id']; ?>" />
                      <label for="select-checkbox-<?php echo $resule['id']; ?>" class="custom-control-label"></label>
                    </div>
                  </td>
                  <td class="align-middle">
                    <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                      value="<?php echo $resule['numb']; ?>" data-id="<?php echo $resule['id']; ?>"
                      data-table="tbl_danhmuc" />
                  </td>
                  <td class="align-middle">
                    <a href="suadanhmuc.php?id=<?php echo $resule['id']; ?>" title="<?php echo $resule['namevi']; ?>">
                      <img src="<?php if (empty($resule['file_name'])) {
                                  echo $config['baseAdmin'] . "assets/img/noimage.png";
                                } else {
                                  echo $config['baseAdmin'] . "uploads/" . $resule['file_name'];
                                } ?>" alt="<?php echo $resule['namevi']; ?>" class="rounded img-preview" />
                    </a>
                  </td>
                  <td class="align-middle">
                    <a class="text-dark text-break" href="suadanhmuc.php?id=<?php echo $resule['id']; ?>"
                      title="<?php echo $resule['namevi']; ?>"><?php echo $resule['namevi']; ?></a>
                  </td>
                  <td class="align-middle text-center">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input show-checkbox"
                        id="show-checkbox-hienthi-<?php echo $resule['id']; ?>" data-table="tbl_danhmuc"
                        data-id="<?php echo $resule['id']; ?>" data-type="hienthi"
                        data-attr="<?php echo ($resule['hienthi'] == 'hienthi') ? '' : 'hienthi'; ?>" <?php if ($resule['hienthi'] == 'hienthi') {
                                                                                                        echo "checked";
                                                                                                      } ?> />
                      <label for="show-checkbox-hienthi-<?php echo $resule['id']; ?>" class="custom-control-label"></label>
                    </div>
                  </td>
                  <td class="align-middle text-center">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input show-checkbox"
                        id="show-checkbox-noibat-<?php echo $resule['id']; ?>" data-table="tbl_danhmuc"
                        data-id="<?php echo $resule['id']; ?>" data-type="noibat"
                        data-attr="<?php echo ($resule['noibat'] == 'noibat') ? '' : 'noibat'; ?>" <?php if ($resule['noibat'] == 'noibat') {
                                                                                                      echo "checked";
                                                                                                    } ?> />
                      <label for="show-checkbox-noibat-<?php echo $resule['id']; ?>" class="custom-control-label"></label>
                    </div>
                  </td>
                  <td class="align-middle text-center text-md text-nowrap">
                    <a class="text-primary mr-2" href="suadanhmuc.php?id=<?php echo $resule['id']; ?>" title="Chỉnh sửa"><i
                        class="fas fa-edit"></i></a>
                    <a class="text-danger" id="delete-item" data-url="?del=<?php echo $resule['id']; ?>" title="Xóa"><i
                        class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>
            <?php }
            } ?>
          </tbody>
        </form>
      </table>
    </div>
    <div class="card-footer text-sm pb-0 mb-5">
      <?php echo $pagination_html = $functions->renderPagination($current_page, $total_pages); ?>
    </div>
  </div>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>