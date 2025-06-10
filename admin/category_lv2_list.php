<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<!-- Main content -->
<?php
$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_records = $functions->phantrang_sp('tbl_danhmuc_c2');
$total_pages = ceil($total_records / $records_per_page);
$show_danhmuc_c1 = $danhmuc->show_danhmuc('tbl_danhmuc');
$show_danhmuc_c2 = $danhmuc->show_danhmuc('tbl_danhmuc_c2', $records_per_page, $current_page);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loc'])) {
  $id_list = $_POST['id_list'];
} else {
  $id_list = '';
}
if (!empty($id_list)) {
  $show_danhmuc_c2 = $danhmuc->show_danhmuc_c2('tbl_danhmuc_c2', $id_list);
} else {
  $show_danhmuc_c2 = $danhmuc->show_danhmuc('tbl_danhmuc_c2', $records_per_page, $current_page);
}
if (isset($_GET['del'])) {
  $id = $_GET['del'];
  $del = $danhmuc->del_category($id, 'tbl_danhmuc_c2', 'category_lv2_list');
}

if (isset($_GET['listid'])) {
  $listid = $_GET['listid'];
  $xoanhieu = $danhmuc->deleteMultipleCategories($listid, 'tbl_danhmuc_c2', 'file', 'category_lv2_list');
}
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="Bảng điều khiển">Bảng điều khiển</a></li>
        <li class="breadcrumb-item">Danh mục cấp 2</li>

      </ol>
    </div>
  </div>
</section>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="category_lv2_form.php" title="Thêm mới"><i
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
    <div class="form-group col-xl-2 col-lg-3 col-md-4 col-sm-4 mb-2">
      <form class="validation-form" novalidate method="post" action="">
        <input class="btn btn-sm bg-gradient-info submit-check mb-3" type="submit" id="loc" value="Lọc danh mục"
          name="loc" />
        <select id="id_list" name="id_list" class="form-control filter-category select2">
          <option value="0">Chọn danh mục</option>
          <?php if ($show_danhmuc_c1): ?>
            <?php while ($resule = $show_danhmuc_c1->fetch_assoc()): ?>
              <option value="<?= $resule['id']; ?>" data-select2-id="<?= $resule['id']; ?>"
                <?= ($id_list == $resule['id']) ? 'selected' : ''; ?>><?= $resule['namevi']; ?></option>
            <?php endwhile; ?>
          <?php endif; ?>
        </select>
      </form>
    </div>
  </div>
  <div class="card card-primary card-outline text-sm mb-0">
    <div class="card-header">
      <h3 class="card-title">Danh sách Danh mục cấp 2</h3>
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
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <form class="validation-form" novalidate method="post" action="">
          <tbody>
            <?php
            if ($show_danhmuc_c2) {
              while ($resule_c2 = $show_danhmuc_c2->fetch_assoc()) {
            ?>
                <tr>
                  <td class="align-middle">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input select-checkbox"
                        id="select-checkbox-<?php echo $resule_c2['id']; ?>" value="<?php echo $resule_c2['id']; ?>"
                        name="checkbox_id<?php echo $resule_c2['id']; ?>" />
                      <label for="select-checkbox-<?php echo $resule_c2['id']; ?>" class="custom-control-label"></label>
                    </div>
                  </td>
                  <td class="align-middle">
                    <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                      value="<?php echo $resule_c2['numb']; ?>" data-id="<?php echo $resule_c2['id']; ?>"
                      data-table="tbl_danhmuc_c2" />
                  </td>
                  <td class="align-middle">
                    <a href="category_lv2_form.php?id=<?php echo $resule_c2['id']; ?>"
                      title="<?php echo $resule_c2['namevi']; ?>">
                      <img
                        src="<?= !empty($resule_c2['file']) ? $config['baseAdmin'] . "uploads/" . $resule_c2['file'] : $config['baseAdmin'] . "assets/img/noimage.png"; ?>"
                        class="rounded img-preview" alt="<?php echo $resule_c2['namevi']; ?>" />
                    </a>
                  </td>
                  <td class="align-middle">
                    <a class="text-dark text-break" href="category_lv2_form.php?id=<?php echo $resule_c2['id']; ?>"
                      title="<?php echo $resule_c2['namevi']; ?>"><?= $resule_c2['namevi']; ?></a>
                  </td>
                  <td class="align-middle text-center">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input <?php if ($resule_c2['hienthi'] == 'hienthi') {
                                echo "checked";
                              }; ?> type="checkbox" class="custom-control-input show-checkbox"
                        id="show-checkbox-hienthi-<?php echo $resule_c2['id']; ?>" data-table="tbl_danhmuc_c2"
                        data-id="<?php echo $resule_c2['id']; ?>" data-type="hienthi"
                        data-attr="<?php echo ($resule_c2['hienthi'] == 'hienthi') ? '' : 'hienthi'; ?>" />
                      <label for="show-checkbox-hienthi-<?php echo $resule_c2['id']; ?>"
                        class="custom-control-label"></label>
                    </div>
                  </td>
                  <td class="align-middle text-center text-md text-nowrap">
                    <a class="text-primary mr-2" href="category_lv2_form.php?id=<?php echo $resule_c2['id']; ?>"
                      title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                    <a class="text-danger" id="delete-item" data-url="?del=<?php echo $resule_c2['id']; ?>" title="Xóa"><i
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
      <?= $pagination_html = $functions->renderPagination($current_page, $total_pages); ?>
    </div>
  </div>
</section>
<!-- Main Footer -->
<?php include 'inc/footer.php'; ?>