<?php
$redirect_url = $_GET['page'];
$type = $_GET['type'] ?? null;
$convert_type = $fn->convert_type($type);
$name_page = $convert_type['vi'];
$records_per_page = 10;
$current_page = max(1, isset($_GET['p']) ? (int)$_GET['p'] : 1);
$total_pages = ceil($fn->count_data(['table' => 'tbl_news', 'type' => $type]) / $records_per_page);
$show_news = $fn->show_data([
  'table' => 'tbl_news',
  'type' => $type,
  'records_per_page' => $records_per_page,
  'current_page' => $current_page,
  'keyword' => $_GET['keyword'] ?? ''
]);
$linkMulti = "index.php?page=deleteMulti&table=tbl_news&type=$type";
$linkDelete = "index.php?page=delete&table=tbl_news&type=$type&id=";
$linkEdit = "index.php?page=news_form&type=$type&id=";
$linkAdd = "index.php?page=news_form&type=$type";
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
            <?php if ($show_news): ?>
              <?php while ($row = $show_news->fetch_assoc()):
                $id = $row['id'];
                $name = $row['namevi'];
                $slug = $row['slugvi'];
                $imgSrc = !empty($row['file'])
                  ? BASE_ADMIN . UPLOADS . $row['file']
                  : NO_IMG;
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
                      value="<?= $row['numb'] ?>" data-id="<?= $id ?>" data-table="<?= $table ?>" />
                  </td>

                  <!-- Ảnh -->
                  <td class="align-middle">
                    <a href="<?= $linkEdit . $id ?>" title="<?= $name ?>">
                      <img class="rounded img-preview" src="<?= $imgSrc ?>" alt="<?= $name ?>" />
                    </a>
                  </td>

                  <!-- Tên + tools -->
                  <td class="align-middle">
                    <a class="text-dark text-break" href="<?= $linkEdit . $id ?>" title="<?= $name ?>"><?= $name ?></a>
                    <div class="tool-action mt-2 w-clear">
                      <a class="text-primary mr-3" href="<?= BASE . $slug ?>" target="_blank" title="Xem"><i class="far fa-eye mr-1"></i>View</a>
                      <a class="text-info mr-3" href="<?= $linkEdit . $id ?>" title="Chỉnh sửa"><i class="far fa-edit mr-1"></i>Edit</a>
                      <a class="text-danger" id="delete-item" data-url="<?= $linkDelete . $id ?>" title="Xoá"><i class="far fa-trash-alt mr-1"></i>Delete</a>
                    </div>
                  </td>

                  <!-- Checkbox Hiển thị, Nổi bật -->
                  <?php foreach (['hienthi', 'noibat'] as $attr): ?>
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
                    <a class="text-danger" id="delete-item" data-url="<?= $linkDelete . $id ?>" title="Xoá"><i class="fas fa-trash-alt"></i></a>
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
      <?= $fn->renderPagination($current_page, $total_pages, "index.php?page=$redirect_url&type=$type&p="); ?>
    </div>
  <?php endif; ?>
</section>
