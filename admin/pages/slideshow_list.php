<?php
$redirect_url = $_GET['page'];
$records_per_page = 10;
$name_page = 'slideshow';
$table = 'tbl_slideshow';
$current_page = max(1, isset($_GET['p']) ? (int)$_GET['p'] : 1);
$total_pages = ceil($fn->count_data(['table' => $table]) / $records_per_page);
$show_slideshow = $fn->show_data([
  'table' => $table,
  'records_per_page' => $records_per_page,
  'current_page' => $current_page,
  'keyword' => $_GET['keyword'] ?? ''
]);
$linkMulti = "index.php?page=deleteMulti&table=$table&";
$linkDelete = "index.php?page=delete&table=$table&id=";
$linkEdit = "index.php?page=slideshow_form&id=";
$linkAdd = "index.php?page=slideshow_form";
?>
<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => 'index.php'],
  ['label' => $name_page]
];
include 'templates/breadcrumb.php';
?>
<section class="content">
  <?php include 'templates/act_list.php'; ?>
  <div class="card card-primary card-outline text-sm mb-0">
    <div class="card-header">
      <h3 class="card-title">Danh sách <?= $name_page ?></h3>
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
            <th class="align-middle" style="width:30%">Tiêu đề</th>
            <th class="align-middle">Link</th>
            <th class="align-middle text-center">Hiển thị</th>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($show_slideshow):
            while ($row = $show_slideshow->fetch_assoc()):
              $id = $row['id'];
              $name     = $row['namevi'];
              $numb     = $row['numb'];
              $link     = $row['link'];
              $status   = $row['status'] ?? '';
              $imgSrc   = !empty($row['file']) ? BASE_ADMIN . UPLOADS . $row['file'] : NO_IMG;
              $linkEditId  = $linkEdit . $id;
              $linkDeleteId = $linkDelete . $id;
          ?>
              <tr>
                <!-- Checkbox chọn nhiều -->
                <td class="align-middle">
                  <div class="custom-control custom-checkbox my-checkbox">
                    <input type="checkbox" class="custom-control-input select-checkbox" id="select-checkbox-<?= $id ?>" value="<?= $id ?>">
                    <label for="select-checkbox-<?= $id ?>" class="custom-control-label"></label>
                  </div>
                </td>

                <!-- Số thứ tự -->
                <td class="align-middle">
                  <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                    value="<?= $numb ?>" data-id="<?= $id ?>" data-table="<?= $table ?>">
                </td>
                <td class="align-middle text-center">
                  <a href="<?= $linkEditId ?>" title="">
                    <img src="<?= $imgSrc ?>" class="rounded img-preview" alt="<?= $name ?>">
                  </a>
                </td>
                <td class="align-middle">
                  <a class="text-dark text-break" href="<?= $linkEditId ?>" title="<?= $name ?>">
                    <?= $name ?>
                  </a>
                </td>
                <!-- Link -->
                <td class="align-middle">
                  <?= $link ?>
                </td>
                <!-- Hiển thị -->
                <?php foreach (['hienthi'] as $attr): ?>
                  <td class="align-middle text-center">
                    <label class="switch switch-success">
                      <input type="checkbox" class="switch-input custom-control-input show-checkbox " id="show-checkbox-<?= $attr ?>-<?= $id ?>" data-table="<?= $table ?>" data-id="<?= $id ?>" data-attr="<?= $attr ?>" <?= (strpos($row['status'], $attr) !== false) ? 'checked' : '' ?>>
                      <span class="switch-toggle-slider">
                        <span class="switch-on"><i class="fa-solid fa-check"></i></span>
                        <span class="switch-off"><i class="fa-solid fa-xmark"></i></span>
                      </span>
                    </label>
                  </td>
                <?php endforeach; ?>
                <!-- Hành động -->
                <td class="align-middle text-center text-md text-nowrap">
                  <a class="text-primary mr-2" href="<?= $linkEditId ?>" title="Chỉnh sửa">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a class="text-danger" id="delete-item" data-url="<?= $linkDeleteId ?>" title="Xóa">
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
      </table>
    </div>
  </div>
  <div class="card-footer text-sm pb-0 mb-5">
    <?= $fn->renderPagination($current_page, $total_pages); ?>
  </div>
</section>
