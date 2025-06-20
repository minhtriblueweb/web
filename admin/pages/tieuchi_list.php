<?php
$redirect_url = $_GET['page'];
$records_per_page = 10;
$current_page = max(1, isset($_GET['p']) ? (int)$_GET['p'] : 1);
$total_pages = ceil($functions->phantrang('tbl_tieuchi') / $records_per_page);
$show_tieuchi = $functions->show_data('tbl_tieuchi', [
  'records_per_page' => $records_per_page,
  'current_page' => $current_page,
  'keyword' => $_GET['keyword'] ?? ''
]);
$linkMulti = "index.php?page=deleteMulti&table=tbl_tieuchi&image=file&";
$linkDelete = "index.php?page=delete&table=tbl_tieuchi&image=file&&id=";
$linkEdit = "index.php?page=tieuchi_form&id=";
$linkAdd = "index.php?page=tieuchi_form";
?>
<?php
$name = 'tiêu chí';
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => '?page=dashboard'],
  ['label' => $name],
  ['label' => 'Danh sách ' . $name]
];
include 'templates/breadcrumb.php';
?>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="<?= $linkAdd ?>" title="Thêm mới"><i
        class="fas fa-plus mr-2"></i>Thêm mới</a>
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
            <th class="align-middle" style="width:30%">Tiêu đề</th>
            <th class="align-middle text-center">Hiển thị</th>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <form action="" method="POST">
          <tbody>
            <?php if ($show_tieuchi): ?>
              <?php while ($row = $show_tieuchi->fetch_assoc()):
                $id       = $row['id'];
                $name     = $row['name'];
                $numb     = $row['numb'];
                $status   = $row['status'] ?? '';
                $imgSrc   = !empty($row['file']) ? BASE_ADMIN . UPLOADS . $row['file'] : NO_IMG;
                $linkEditId  = $linkEdit . $id;
                $linkDeleteId = $linkDelete . $id;
              ?>
                <tr>
                  <!-- Checkbox chọn -->
                  <td class="align-middle">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input select-checkbox" id="select-checkbox-<?= $id ?>" value="<?= $id ?>" name="checkbox_id<?= $id ?>" />
                      <label for="select-checkbox-<?= $id ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- STT -->
                  <td class="align-middle">
                    <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                      value="<?= $numb ?>" data-id="<?= $id ?>" data-table="tbl_tieuchi" />
                  </td>

                  <!-- Ảnh -->
                  <td class="align-middle">
                    <a href="<?= $linkEditId ?>" title="<?= $name ?>">
                      <img class="rounded img-preview" src="<?= $imgSrc ?>" alt="<?= $name ?>" />
                    </a>
                  </td>

                  <!-- Tên + tools -->
                  <td class="align-middle">
                    <a class="text-dark text-break" href="<?= $linkEditId ?>" title="<?= $name ?>"><?= $name ?></a>
                    <div class="tool-action mt-2 w-clear">
                      <a class="text-primary mr-3" href="<?= BASE . $slug ?>" target="_blank" title="Xem">
                        <i class="far fa-eye mr-1"></i>View
                      </a>
                      <a class="text-info mr-3" href="<?= $linkEditId ?>" title="Chỉnh sửa">
                        <i class="far fa-edit mr-1"></i>Edit
                      </a>
                      <a class="text-danger" id="delete-item" data-url="?del=<?= $id ?>" title="Xoá">
                        <i class="far fa-trash-alt mr-1"></i>Delete
                      </a>
                    </div>
                  </td>

                  <?php foreach (['hienthi'] as $attr): ?>
                    <td class="align-middle text-center">
                      <div class="custom-control custom-checkbox my-checkbox">
                        <input type="checkbox"
                          class="custom-control-input show-checkbox"
                          id="show-checkbox-<?= $attr ?>-<?= $id ?>"
                          data-id="<?= $id ?>"
                          data-table="tbl_tieuchi"
                          data-attr="<?= $attr ?>"
                          <?= (strpos($status, $attr) !== false) ? 'checked' : '' ?> />
                        <label for="show-checkbox-<?= $attr ?>-<?= $id ?>" class="custom-control-label"></label>
                      </div>
                    </td>
                  <?php endforeach; ?>

                  <!-- Hành động -->
                  <td class="align-middle text-center text-md text-nowrap">
                    <a class="text-primary mr-2" href="<?= $linkEditId ?>" title="Chỉnh sửa">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a class="text-danger" id="delete-item" data-url="<?= $linkDeleteId ?>" title="Xoá">
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
