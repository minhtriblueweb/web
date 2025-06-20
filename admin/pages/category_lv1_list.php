<?php
$redirect_url = $_GET['page'];
$records_per_page = 10;
$current_page = max(1, isset($_GET['p']) ? (int)$_GET['p'] : 1);
$total_records = $functions->phantrang('tbl_danhmuc');
$total_pages = ceil($total_records / $records_per_page);
$show_danhmuc = $functions->show_data([
  'table' => 'tbl_danhmuc',
  'records_per_page' => $records_per_page,
  'current_page' => $current_page,
  'keyword' => $_GET['keyword'] ?? ''
]);
$name = 'danh mục cấp 1';
$linkMulti = "index.php?page=deleteMulti&table=tbl_danhmuc";
$linkDelete = "index.php?page=delete&table=tbl_danhmuc&id=";
$linkEdit = "index.php?page=category_lv1_form&id=";
$linkAdd = "index.php?page=category_lv1_form";
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
        <form action="" method="POST">
          <tbody>
            <?php if ($show_danhmuc && $show_danhmuc->num_rows > 0): ?>
              <?php while ($row = $show_danhmuc->fetch_assoc()):
                $id = $row['id'];
                $namevi = $row['namevi'];
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
                    <input type="number" class="form-control form-control-mini m-auto update-numb"
                      min="0" value="<?= $row['numb'] ?>"
                      data-id="<?= $id ?>" data-table="tbl_danhmuc" />
                  </td>

                  <!-- Ảnh -->
                  <td class="align-middle">
                    <a href="<?= $linkEdit . $id ?>" title="<?= $namevi ?>">
                      <img src="<?= $file ?>" alt="<?= $namevi ?>" class="rounded img-preview" />
                    </a>
                  </td>

                  <!-- Tên -->
                  <td class="align-middle">
                    <a class="text-dark text-break" href="<?= $linkEdit . $id ?>" title="<?= $namevi ?>">
                      <?= $namevi ?>
                    </a>
                  </td>

                  <!-- Checkbox trạng thái (hiển thị, nổi bật) -->
                  <?php foreach (['hienthi', 'noibat'] as $attr): ?>
                    <td class="align-middle text-center">
                      <label class="switch switch-success">
                        <input type="checkbox" class="switch-input custom-control-input show-checkbox" id="show-checkbox-<?= $attr ?>-<?= $id ?>" data-table="tbl_danhmuc" data-id="<?= $id ?>" data-attr="<?= $attr ?>" <?= (strpos($row['status'], $attr) !== false) ? 'checked' : '' ?>>
                        <span class="switch-toggle-slider">
                          <span class="switch-on"><i class="fa-solid fa-check"></i></span>
                          <span class="switch-off"><i class="fa-solid fa-xmark"></i></span>
                        </span>
                      </label>
                    </td>
                  <?php endforeach; ?>

                  <!-- Hành động -->
                  <td class="align-middle text-center text-md text-nowrap">
                    <a class="text-primary mr-2" href="<?= $linkEdit . $id ?>" title="Chỉnh sửa">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a class="text-danger" id="delete-item" data-url="<?= $linkDelete . $id ?>" title="Xóa">
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
