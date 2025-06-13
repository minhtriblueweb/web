<?php include('inc/header.php'); ?>
<?php include('inc/sidebar.php'); ?>
<!-- Main content -->
<?php
$show_danhgia = $danhgia->show_danhgia();
if (isset($_GET['del'])) {
  $id = $_GET['del'];
  $del = $danhgia->del_danhgia($id);
}
if (isset($_GET['listid'])) {
  $listid = $_GET['listid'];
  $xoanhieu = $danhgia->xoanhieu_danhgia($listid);
}
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item active">Đánh giá khách hàng</li>
      </ol>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="themdanhgia.php" title="Thêm mới"><i
        class="fas fa-plus mr-2"></i>Thêm mới</a>
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="?xoa=1" title="Xóa tất cả"><i
        class="far fa-trash-alt mr-2"></i>Xóa
      tất cả</a>
    <div class="form-inline form-search d-inline-block align-middle ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar text-sm" type="search" id="keyword" placeholder="Tìm kiếm"
          aria-label="Tìm kiếm" value=""
          onkeypress="doEnter(event,'keyword','index.php?com=news&act=man&type=tieu-chi')">
        <div class="input-group-append bg-primary rounded-right">
          <button class="btn btn-navbar text-white" type="button"
            onclick="onSearch('keyword','index.php?com=news&act=man&type=tieu-chi')">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="card card-primary card-outline text-sm mb-0">
    <div class="card-header">
      <h3 class="card-title">Danh sách Đánh giá khách hàng</h3>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover">
        <thead>
          <tr>
            <th class="align-middle" width="5%">
              <div class="custom-control custom-checkbox my-checkbox">
                <input type="checkbox" class="custom-control-input" id="selectall-checkbox">
                <label for="selectall-checkbox" class="custom-control-label"></label>
              </div>
            </th>
            <th class="align-middle text-center" width="10%">STT</th>
            <th class="align-middle">Hình</th>
            <th class="align-middle" style="width:30%">Tiêu đề</th>
            <th class="align-middle text-center">Hiển thị</th>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($show_danhgia): ?>
          <?php while ($resule = $show_danhgia->fetch_assoc()): ?>
          <tr>
            <td class="align-middle">
              <div class="custom-control custom-checkbox my-checkbox">
                <input type="checkbox" class="custom-control-input select-checkbox"
                  id="select-checkbox-<?= $resule['id']; ?>" value="<?= $resule['id']; ?>">
                <label for="select-checkbox-<?= $resule['id']; ?>" class="custom-control-label"></label>
              </div>
            </td>
            <td class="align-middle">
              <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                value="<?= $resule['numb']; ?>" data-id="<?= $resule['id']; ?>" data-table="tbl_danhgia">
            </td>
            <td class="align-middle">
              <a href="themdanhgia.php?id=<?= $resule['id']; ?>" title="<?= $resule['name']; ?>">
                <img class='rounded img-preview'
                  src='<?php echo $config['baseAdmin'] . (empty($resule['file']) ? "assets/img/noimage.png" : "uploads/" . $resule['file']); ?>'
                  alt='<?= $resule['name']; ?>' alt='<?= $resule['name']; ?>' /> </a>
            </td>
            <td class="align-middle">
              <a class="text-dark text-break" href="themdanhgia.php?id=<?= $resule['id']; ?>"
                title="<?= $resule['name']; ?>"><?= $resule['name']; ?></a>
              <div class="tool-action mt-2 w-clear">
                <a class="text-info mr-3" href="themdanhgia.php?id=<?= $resule['id']; ?>"
                  title="<?= $resule['name']; ?>"><i class="far fa-edit mr-1"></i>Edit</a>
                <a class="text-danger" id="delete-item" data-url="?del=<?= $resule['id']; ?>"
                  title="<?= $resule['name']; ?>"><i class="far fa-trash-alt mr-1"></i>Delete</a>
              </div>
            </td>
            <td class="align-middle text-center">
              <div class="custom-control custom-checkbox my-checkbox">
                <input type="checkbox" class="custom-control-input show-checkbox"
                  id="show-checkbox-hienthi-<?= $resule['id']; ?>" data-table="tbl_danhgia" data-type="hienthi"
                  data-id="<?= $resule['id']; ?>"
                  data-attr="<?php echo ($resule['hienthi'] == 'hienthi') ? '' : 'hienthi'; ?>"
                  <?php echo $resule['hienthi'] == 'hienthi' ? 'checked' : ''; ?>>
                <label for="show-checkbox-hienthi-<?= $resule['id']; ?>" class="custom-control-label"></label>
              </div>
            </td>
            <td class="align-middle text-center text-md text-nowrap">
              <a class="text-primary mr-2" href="themdanhgia.php?id=<?= $resule['id']; ?>" title="Chỉnh sửa"><i
                  class="fas fa-edit"></i></a>
              <a class="text-danger" id="delete-item" data-url="?del=<?= $resule['id']; ?>" title="Xóa"><i
                  class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
          <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>