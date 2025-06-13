<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<!-- Main content -->
<?php
if (isset($_GET['id']) && $_GET['id'] != NULL) {
  $id = $_GET['id'];
}
$get_id = $sanpham->get_id_sanpham($id);
$get_gallery = $sanpham->get_gallery($id);
if ($get_id) {
  $result = $get_id->fetch_assoc();
}
if (isset($_GET['del'])) {
  $id = $_GET['del'];
  $del = $sanpham->del_gallery($id);
}
if (isset($_GET['listid'])) {
  $listid = $_GET['listid'];
  $xoanhieu = $sanpham->xoanhieu_gallery($listid);
}

?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item active">Hình ảnh sản phẩm</li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="them_gallery.php?id=<?= $result['id'] ?>"
      title="Thêm mới"><i class="fas fa-plus mr-2"></i>Thêm mới</a>
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
      <h3 class="card-title">Danh sách Hình ảnh sản phẩm : <span class="text-info"><?= $result['namevi'] ?></span>
      </h3>
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
            <th class="align-middle text-center" width="8%">Hình</th>
            <th class="align-middle text-center">Hiển thị</th>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($get_gallery): ?>
          <?php while ($resule_gallery = $get_gallery->fetch_assoc()) : ?>
          <tr>
            <td class="align-middle">
              <div class="custom-control custom-checkbox my-checkbox">
                <input type="checkbox" class="custom-control-input select-checkbox"
                  id="select-checkbox-<?= $resule_gallery['id'] ?>" value="<?= $resule_gallery['id'] ?>">
                <label for="select-checkbox-<?= $resule_gallery['id'] ?>" class="custom-control-label"></label>
              </div>
            </td>
            <td class="align-middle">
              <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                value="<?= $resule_gallery['numb'] ?>" data-id="<?= $resule_gallery['id'] ?>" data-table="tbl_gallery">
            </td>
            <td class="align-middle text-center">
              <a href="edit_gallery.php?id=<?= $resule_gallery['id'] ?>">
                <img class="rounded img-preview"
                  src="<?= empty($resule_gallery['photo']) ? $config['baseAdmin'] . "assets/img/noimage.png" : $config['baseAdmin'] . "uploads/" . $resule_gallery['photo'] ?>">
              </a>
            </td>
            <td class="align-middle text-center">
              <div class="custom-control custom-checkbox my-checkbox">
                <input type="checkbox" data-type="hienthi" class="custom-control-input show-checkbox"
                  id="show-checkbox-hienthi-<?= $resule_gallery['id'] ?>" data-table="tbl_gallery"
                  data-id="<?= $resule_gallery['id'] ?>"
                  data-attr="<?= ($resule_gallery['hienthi'] == 'hienthi') ? '' : 'hienthi' ?>"
                  <?= $resule_gallery['hienthi'] == 'hienthi' ? 'checked' : '' ?> />
                <label for="show-checkbox-hienthi-<?= $resule_gallery['id'] ?>" class="custom-control-label"></label>
              </div>
            </td>
            <td class="align-middle text-center text-md text-nowrap">
              <a class="text-primary mr-2" href="edit_gallery.php?id=<?= $resule_gallery['id'] ?>" title="Chỉnh sửa"><i
                  class="fas fa-edit"></i></a>
              <a class="text-danger" id="delete-item" data-url="?del=<?= $resule_gallery['id'] ?>" title="Xóa"><i
                  class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
          <?php endwhile; ?>
          <?php endif ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>