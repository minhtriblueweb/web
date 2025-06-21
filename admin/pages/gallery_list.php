<?php
$name = 'sản phẩm';
$redirect_url = $_GET['page'];
if (isset($_GET['id']) && $_GET['id'] != NULL) {
  $id = $_GET['id'];
}
$get_id_sp = $functions->get_id('tbl_sanpham', $id);
$get_gallery = $functions->show_data(['table' => 'tbl_gallery', 'id_parent' => $id]);
if ($get_id_sp) {
  $result = $get_id_sp->fetch_assoc();
}
$linkMulti = "index.php?page=deleteMulti&table=tbl_gallery&id_parent=$id";
$linkDelete = "index.php?page=delete&table=tbl_gallery&id_parent=$id&id=";
$linkGallery = "index.php?page=gallery_form&id=";
$linkEdit = "index.php?page=gallery_form&id_child=";
?>
<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => '?page=dashboard'],
  ['label' =>  $name],
  ['label' => 'Hình ảnh ' . $name]
];
include 'templates/breadcrumb.php';
?>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="<?= $linkGallery . $result['id'] ?>"
      title="Thêm mới"><i class="fas fa-plus mr-2"></i>Thêm mới</a>
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
      <h3 class="card-title">Danh sách Hình ảnh sản phẩm : <span class="text-info"><?= $result['name'] ?></span>
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
            <?php while ($row = $get_gallery->fetch_assoc()): ?>
              <?php $id = $row['id']; ?>
              <tr>
                <!-- Checkbox chọn dòng -->
                <td class="align-middle">
                  <div class="custom-control custom-checkbox my-checkbox">
                    <input type="checkbox" class="custom-control-input select-checkbox" id="select-checkbox-<?= $id ?>" value="<?= $id ?>">
                    <label for="select-checkbox-<?= $id ?>" class="custom-control-label"></label>
                  </div>
                </td>

                <!-- Số thứ tự -->
                <td class="align-middle">
                  <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                    value="<?= $row['numb'] ?>" data-id="<?= $id ?>" data-table="tbl_gallery">
                </td>

                <!-- Hình ảnh -->
                <td class="align-middle text-center">
                  <a href="<?= $linkEdit . $id ?>">
                    <img class="rounded img-preview"
                      src="<?= empty($row['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row['file'] ?>">
                  </a>
                </td>

                <!-- Các checkbox trạng thái -->
                <?php foreach (['hienthi'] as $attr): ?>
                  <td class="align-middle text-center">
                    <label class="switch switch-success mb-0">
                      <input type="checkbox" class="switch-input custom-control-input show-checkbox"
                        id="show-checkbox-<?= $attr ?>-<?= $id ?>"
                        data-table="tbl_gallery"
                        data-id="<?= $id ?>"
                        data-attr="<?= $attr ?>"
                        <?= (strpos($row['status'], $attr) !== false) ? 'checked' : '' ?>>
                      <span class="switch-toggle-slider">
                        <span class="switch-on"><i class="fa-solid fa-check"></i></span>
                        <span class="switch-off"><i class="fa-solid fa-xmark"></i></span>
                      </span>
                    </label>
                  </td>
                <?php endforeach; ?>

                <!-- Hành động -->
                <td class="align-middle text-center text-md text-nowrap">
                  <a class="text-primary mr-2" href="<?= $linkEdit . $id ?>" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                  <a class="text-danger" id="delete-item" data-url="<?= $linkDelete . $id ?>" title="Xóa"><i class="fas fa-trash-alt"></i></a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="100" class="text-center">Không có dữ liệu</td>
            </tr>
          <?php endif; ?>
        </tbody>

      </table>
    </div>
  </div>
</section>
