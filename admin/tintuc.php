<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<!-- Main content -->
<?php
if (isset($_GET['del'])) {
  $id = $_GET['del'];
  $del = $news->del_news($id, 'tintuc');
}
if (isset($_GET['listid'])) {
  $listid = $_GET['listid'];
  $xoanhieu = $news->delete_multiple_news($listid, 'tintuc');
}
$records_per_page = 10; // Số bản ghi trên mỗi trang
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
$total_records = $functions->phantrang('tbl_news', 'tintuc'); // Lấy tổng số bản ghi
$total_pages = ceil($total_records / $records_per_page); // Tính số trang
// Lấy dữ liệu cho trang hiện tại
$show_tintuc = $news->show_news($records_per_page, $current_page, '', 'tintuc');
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item">tin tức</li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="themtintuc.php" title="Thêm mới"><i
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
      <h3 class="card-title">Danh sách tin tức</h3>
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
            <th class="align-middle" style="width: 40%">Tiêu đề</th>
            <th class="align-middle text-center">Hiển thị</th>
            <th class="align-middle text-center">Nổi bật</th>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <form action="" method="POST">
          <tbody>
            <?php if ($show_tintuc): ?>
              <?php while ($resule = $show_tintuc->fetch_assoc()): ?>
                <tr>
                  <td class="align-middle">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input select-checkbox"
                        id="select-checkbox-<?= $resule['id'] ?>" value="<?= $resule['id'] ?>"
                        name="checkbox_id<?= $resule['id'] ?>" />
                      <label for="select-checkbox-<?= $resule['id'] ?>" class="custom-control-label"></label>
                    </div>
                  </td>
                  <td class="align-middle">
                    <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                      value="<?= $resule['numb'] ?>" data-id="<?= $resule['id'] ?>" data-table="tbl_news" />
                  </td>
                  <td class="align-middle">
                    <a href="suatintuc.php?id=<?= $resule['id'] ?>" title="<?= $resule['namevi'] ?>">
                      <img class="rounded img-preview"
                        src="<?= empty($resule['file']) ? $config['baseAdmin'] . "assets/img/noimage.png" : $config['baseAdmin'] . "uploads/" . $resule['file'] ?>"
                        alt="<?= $resule['namevi'] ?>" />
                    </a>

                  </td>
                  <td class="align-middle">
                    <a class="text-dark text-break" href="suatintuc.php?id=<?= $resule['id'] ?>"
                      title="<?= $resule['namevi'] ?>"><?= $resule['namevi'] ?></a>
                    <div class="tool-action mt-2 w-clear">
                      <a class="text-primary mr-3" href="<?= "{$config['base']}{$resule['slugvi']}" ?>" target="_blank"
                        title="<?= $resule['namevi'] ?>"><i class="far fa-eye mr-1"></i>View</a>
                      <a class="text-info mr-3" href="suatintuc.php?id=<?= $resule['id'] ?>"
                        title="<?= $resule['namevi'] ?>"><i class="far fa-edit mr-1"></i>Edit</a>
                      <a class="text-danger" id="delete-item" data-url="?del=<?= $resule['id'] ?>"
                        title="<?= $resule['namevi'] ?>"><i class="far fa-trash-alt mr-1"></i>Delete</a>
                    </div>
                  </td>

                  <td class="align-middle text-center">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" data-type="hienthi" class="custom-control-input show-checkbox"
                        id="show-checkbox-hienthi-<?= $resule['id'] ?>" data-table="tbl_news" data-id="<?= $resule['id'] ?>"
                        data-attr="<?= ($resule['hienthi'] == 'hienthi') ? '' : 'hienthi' ?>"
                        <?= $resule['hienthi'] == 'hienthi' ? 'checked' : '' ?> />
                      <label for="show-checkbox-hienthi-<?= $resule['id'] ?>" class="custom-control-label"></label>
                    </div>
                  </td>
                  <td class="align-middle text-center">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" data-type="noibat" class="custom-control-input show-checkbox"
                        id="show-checkbox-noibat-<?= $resule['id'] ?>" data-table="tbl_news" data-id="<?= $resule['id'] ?>"
                        data-attr="<?= ($resule['noibat'] == 'noibat') ? '' : 'noibat' ?>"
                        <?= $resule['noibat'] == 'noibat' ? 'checked' : '' ?> />
                      <label for="show-checkbox-noibat-<?= $resule['id'] ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <td class="align-middle text-center text-md text-nowrap">
                    <a class="text-primary mr-2" href="suatintuc.php?id=<?= $resule['id'] ?>" title="Chỉnh sửa"><i
                        class="fas fa-edit"></i></a>
                    <a class="text-danger" id="delete-item" data-url="?del=<?= $resule['id'] ?>" title="Xóa"><i
                        class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php endif; ?>
          </tbody>
        </form>
      </table>
    </div>
    <div class="card-footer text-sm pb-0 mb-5">
      <?php
      echo  $pagination_html = $functions->renderPagination($current_page, $total_pages);
      ?>
    </div>
  </div>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>