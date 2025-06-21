<?php
$redirect_url = $_GET['page'];
$records_per_page = 10;
$current_page = max(1, isset($_GET['p']) ? (int)$_GET['p'] : 1);
$total_records = $functions->phantrang('tbl_danhmuc_c2');
$total_pages = ceil($total_records / $records_per_page);
$show_danhmuc_c1 = $functions->show_data('tbl_danhmuc');
$show_danhmuc_c2 = $functions->show_data([
  'table' => 'tbl_danhmuc_c2',
  'records_per_page' => $records_per_page,
  'current_page' => $current_page,
  'keyword' => $_GET['keyword'] ?? ''
]);
$name = 'danh mục cấp 2';
$linkMulti = "index.php?page=deleteMulti&table=tbl_danhmuc_c2";
$linkDelete = "index.php?page=delete&table=tbl_danhmuc_c2&id=";
$linkEdit = "index.php?page=category_lv2_form&id=";
$linkAdd = "index.php?page=category_lv2_form";
?>
<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => '?page=dashboard'],
  ['label' => 'Danh mục'],
  ['label' => 'Danh sách ' . $name]
];
include 'templates/breadcrumb.php';
?>
<section class="content">
  <?php include 'templates/act_list.php'; ?>
  <div class="card-footer form-group-category text-sm bg-light row">
    <div class="form-group col-xl-2 col-lg-3 col-md-4 col-sm-4 mb-2">
      <form class="validation-form" novalidate method="post" action="">
        <input class="btn btn-sm bg-gradient-info submit-check mb-3" type="submit" id="loc" value="Lọc danh mục"
          name="loc" />
        <select id="id_list" name="id_list" class="form-control filter-category select2">
          <option value="0">Chọn danh mục</option>
          <?php if ($show_danhmuc_c1 && $show_danhmuc_c1->num_rows > 0): ?>
            <?php while ($resule = $show_danhmuc_c1->fetch_assoc()): ?>
              <option value="<?= $resule['id']; ?>" data-select2-id="<?= $resule['id']; ?>"
                <?= (isset($id_list) && $id_list == $resule['id']) ? 'selected' : ''; ?>>
                <?= $resule['name']; ?>
              </option>
            <?php endwhile; ?>
          <?php else: ?>
            <option disabled>Không có danh mục</option>
          <?php endif; ?>
        </select>
      </form>
    </div>
  </div>
  <div class="card card-primary card-outline text-sm mb-0">
    <div class="card-header">
      <h3 class="card-title">Danh sách <?= $name ?></h3>
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
        <form class="validation-form" novalidate method="post" action="">
          <tbody>
            <?php if ($show_danhmuc_c2):
              while ($row = $show_danhmuc_c2->fetch_assoc()):
                $id = $row['id'];
                $name = $row['name'];
                $file = empty($row['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row['file'];
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
                      value="<?= $row['numb'] ?>" data-id="<?= $id ?>" data-table="tbl_danhmuc_c2" />
                  </td>

                  <!-- Ảnh -->
                  <td class="align-middle">
                    <a href="<?= $linkEdit ?><?= $id ?>" title="<?= $name ?>">
                      <img src="<?= $file ?>" class="rounded img-preview" alt="<?= $name ?>" />
                    </a>
                  </td>

                  <!-- Tên -->
                  <td class="align-middle">
                    <a class="text-dark text-break" href="<?= $linkEdit ?><?= $id ?>" title="<?= $name ?>">
                      <?= $name ?>
                    </a>
                  </td>

                  <!-- Checkbox Hiển thị, Nổi bật -->
                  <?php foreach (['hienthi', 'noibat'] as $attr): ?>
                    <td class="align-middle text-center">
                      <label class="switch switch-success">
                        <input type="checkbox" class="switch-input custom-control-input show-checkbox " id="show-checkbox-<?= $attr ?>-<?= $id ?>" data-table="tbl_danhmuc_c2" data-id="<?= $id ?>" data-attr="<?= $attr ?>" <?= (strpos($row['status'], $attr) !== false) ? 'checked' : '' ?>>
                        <span class="switch-toggle-slider">
                          <span class="switch-on"><i class="fa-solid fa-check"></i></span>
                          <span class="switch-off"><i class="fa-solid fa-xmark"></i></span>
                        </span>
                      </label>
                    </td>
                  <?php endforeach; ?>


                  <!-- Hành động -->
                  <td class="align-middle text-center text-md text-nowrap">
                    <a class="text-primary mr-2" href="<?= $linkEdit ?><?= $id ?>" title="Chỉnh sửa">
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
  <?php if ($total_pages > 1): ?>
    <div class="card-footer text-sm pb-0 mb-5">
      <?= $functions->renderPagination($current_page, $total_pages, "index.php?page=$redirect_url&p="); ?>
    </div>
  <?php endif; ?>

</section>
