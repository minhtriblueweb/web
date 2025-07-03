<?php
$redirect_url = $_GET['page'];
$name_page = 'sản phẩm';
$table = 'tbl_sanpham';
// cấp 1
$show_danhmuc_c1 = $fn->show_data([
  'table' => 'tbl_danhmuc_c1'
]);
// cấp 2
$show_danhmuc_c2 = $fn->show_data([
  'table' => 'tbl_danhmuc_c2',
  'id_list' => $_GET['id_list'] ?? '',
]);
// phân trang
$records_per_page = 10;
$current_page = max(1, isset($_GET['p']) ? (int)$_GET['p'] : 1);
$total_records = $fn->count_data([
  'table' => $table,
  'id_list' => $_GET['id_list'] ?? '',
  'id_cat' => $_GET['id_cat'] ?? '',
  'keyword' => $_GET['keyword'] ?? ''
]);
$total_pages = ceil($total_records / $records_per_page);
$show_sanpham = $fn->show_data([
  'table' => $table,
  'id_list' => $_GET['id_list'] ?? '',
  'id_cat' => $_GET['id_cat'] ?? '',
  'records_per_page' => $records_per_page,
  'current_page' => $current_page,
  'keyword' => $_GET['keyword'] ?? ''
]);
$linkMulti = "index.php?page=deleteMulti&table=$table";
$linkDelete = "index.php?page=delete&table=$table&id=";
$linkEdit = "index.php?page=sanpham_form&id=";
$linkAdd = "index.php?page=sanpham_form";
$linkGalleryList = "index.php?page=gallery_list&id=";
$linkGallery = "index.php?page=gallery_form&id=";
?>
<?php
$breadcrumb = [
  ['label' => 'Bảng điều khiển', 'link' => 'index.php'],
  ['label' => $name_page]
];
include 'templates/breadcrumb.php';
?>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="<?= $linkAdd ?>" title="Thêm mới"><i class="fas fa-plus mr-2"></i>Thêm mới</a>
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?= $linkDelete ?>" title="Xóa tất cả"><i class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
    <div class="form-inline form-search d-inline-block align-middle ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar text-sm" type="search" id="keyword" placeholder="Tìm kiếm" aria-label="Tìm kiếm" value="<?= (isset($_GET['keyword'])) ? $_GET['keyword'] : '' ?>" onkeypress="doEnter(event,'keyword','<?= $linkMan ?>')">
        <div class="input-group-append bg-primary rounded-right">
          <button class="btn btn-navbar text-white" type="button" onclick="onSearch('keyword','<?= $linkMan ?>')">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer form-group-category text-sm bg-light row">
    <div class="form-group col-xl-2 col-lg-3 col-md-4 col-sm-4 mb-2">
      <select id="id_list" name="id_list" class="form-control filter-category select2" onchange="onchangeCategory($(this))">
        <option value="0">Chọn danh mục</option>
        <?php
        $id_list_selected = (int)($_GET['id_list'] ?? 0);
        $fn->renderSelectOptions($show_danhmuc_c1, 'id', 'namevi', $id_list_selected);
        ?>
      </select>
    </div>
    <div class="form-group col-xl-2 col-lg-3 col-md-4 col-sm-4 mb-2">
      <select id="id_cat" name="id_cat" class="form-control filter-category select2" onchange="onchangeCategory($(this))">
        <option value="0">Chọn danh mục</option>
        <?php
        $id_cat_selected = (int)($_GET['id_cat'] ?? 0);
        $fn->renderSelectOptions($show_danhmuc_c2, 'id', 'namevi', $id_cat_selected);
        ?>
      </select>
    </div>
  </div>
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
                <input type="checkbox" class="custom-control-input" id="selectall-checkbox" />
                <label for="selectall-checkbox" class="custom-control-label"></label>
              </div>
            </th>
            <th class="align-middle text-center" style="width: 10%">STT</th>
            <th class="align-middle">Hình</th>
            <th class="align-middle" style="width: 20%">Tiêu đề</th>
            <th class="align-middle">Gallery</th>
            <th class="align-middle">Danh mục</th>
            <th class="align-middle">Danh mục cấp 2</th>
            <th class="align-middle text-center">Hiển thị</th>
            <th class="align-middle text-center">Nổi bật</th>
            <th class="align-middle text-center">Bán chạy</th>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <form action="" method="POST">
          <tbody>
            <?php if ($show_sanpham): ?>
              <?php while ($row = $show_sanpham->fetch_assoc()):
                $id = $row['id'];
                $name = $row['namevi'];
                $slug = $row['slugvi'];
                $file =  $row['file'];
              ?>
                <tr>
                  <!-- Checkbox chọn -->
                  <td class="align-middle">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input select-checkbox" id="select-checkbox-<?= $id ?>" value="<?= $id ?>" name="checkbox_id<?= $id ?>" />
                      <label for="select-checkbox-<?= $id ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- Số thứ tự -->
                  <td class="align-middle">
                    <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                      value="<?= $row['numb'] ?>" data-id="<?= $id ?>" data-table="<?= $table ?>" />
                  </td>

                  <!-- Ảnh sản phẩm -->
                  <td class="align-middle">
                    <a href="<?= $linkEdit . $id ?>" title="<?= $name ?>">
                      <?= $fn->getImage([
                        'file' => $file,
                        'class' => 'rounded img-preview',
                        'alt' => $name,
                      ]) ?>
                    </a>
                  </td>

                  <!-- Tên sản phẩm -->
                  <td class="align-middle">
                    <a class="text-dark text-break" href="<?= $linkEdit . $id ?>" title="<?= $name ?>"><?= $name ?></a>
                    <div class="tool-action mt-2 w-clear">
                      <a class="text-primary mr-3" href="<?= BASE . $slug ?>" target="_blank" title="Xem"><i class="far fa-eye mr-1"></i>View</a>
                      <a class="text-info mr-3" href="<?= $linkEdit . $id ?>" title="Chỉnh sửa"><i class="far fa-edit mr-1"></i>Edit</a>
                      <a class="text-danger" id="delete-item" data-url="<?= $linkDelete . $id ?>" title="Xóa"><i class="far fa-trash-alt mr-1"></i>Delete</a>
                    </div>
                  </td>

                  <!-- Thêm -->
                  <td class="align-middle">
                    <div class="dropdown">
                      <button type="button" class="btn btn-sm bg-gradient-info dropdown-toggle" data-toggle="dropdown">Thêm</button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item text-dark" href="<?= $linkGallery . $id ?>" title="Gallery">
                          <i class="far fa-caret-square-right text-secondary mr-2"></i>Thêm Gallery
                        </a>
                        <a class="dropdown-item text-dark" href="<?= $linkGalleryList . $id ?>" title="Gallery">
                          <i class="far fa-caret-square-right text-secondary mr-2"></i>Danh sách Gallery
                        </a>
                      </div>
                    </div>
                  </td>

                  <!-- Danh mục cấp 1 -->
                  <td class="align-middle">
                    <?= $sanpham->get_name_danhmuc($row['id_list'], 'tbl_danhmuc_c1') ?>
                  </td>

                  <!-- Danh mục cấp 2 -->
                  <td class="align-middle">
                    <?= $sanpham->get_name_danhmuc($row['id_cat'], 'tbl_danhmuc_c2') ?>
                  </td>
                  <!-- Checkbox Hiển thị, bán chạy -->
                  <?php foreach (['hienthi', 'noibat', 'banchay'] as $attr): ?>
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
        </form>
      </table>
    </div>
  </div>
  <div class="card-footer text-sm pb-0 mb-5">
    <?= $fn->renderPagination($current_page, $total_pages); ?>
  </div>
</section>
