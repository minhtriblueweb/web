<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="<?= $linkForm ?>" title="Thêm mới"><i class="fas fa-plus mr-2"></i>Thêm mới</a>
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?= $linkMulti ?>" title="Xóa tất cả"><i class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
    <div class="form-inline form-search d-inline-block align-middle ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar text-sm" type="search" id="keyword" placeholder="Tìm kiếm" aria-label="Tìm kiếm" value="<?= $keyword ?>" onkeypress="doEnter(event,'keyword','<?= $linkMan ?>')">
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
      <?= $fn->getLinkCategory('tbl_product_list',  $_GET['id_list'] ?? '') ?>
    </div>
    <div class="form-group col-xl-2 col-lg-3 col-md-4 col-sm-4 mb-2">
      <?= $fn->getLinkCategory('tbl_product_cat',  $_GET['id_cat'] ?? '') ?>
    </div>
  </div>
  <div class="card card-primary card-outline text-sm mb-0">
    <div class="card-header">
      <h3 class="card-title">Danh sách <?= $config['product'][$type]['title_main'] ?></h3>
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
            <?php if (!empty($config['product'][$type]['gallery'])): ?>
              <th class="align-middle">Gallery</th>
            <?php endif; ?>
            <th class="align-middle">Cấp 1</th>
            <th class="align-middle">Cấp 2</th>
            <?php foreach ($config['product'][$type]['check'] as $attr => $label): ?>
              <th class="align-middle text-center"><?= $label ?></th>
            <?php endforeach; ?>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <form action="" method="POST">
          <tbody>
            <?php if (!empty($show_data)): ?>
              <?php foreach ($show_data as $row): ?>
                <tr>
                  <!-- Checkbox chọn -->
                  <td class="align-middle">
                    <div class="custom-control custom-checkbox my-checkbox">
                      <input type="checkbox" class="custom-control-input select-checkbox"
                        id="select-checkbox-<?= $row['id'] ?>" value="<?= $row['id'] ?>"
                        name="checkbox_id<?= $row['id'] ?>" />
                      <label for="select-checkbox-<?= $row['id'] ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- Số thứ tự -->
                  <td class="align-middle">
                    <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                      value="<?= $row['numb'] ?>" data-id="<?= $row['id'] ?>" data-table="<?= $table ?>" />
                  </td>

                  <!-- Ảnh sản phẩm -->
                  <td class="align-middle">
                    <a href="<?= $linkEdit . $row['id'] ?>" title="<?= $row['name' . $lang] ?>">
                      <?= $fn->getImage(['file' => $row['file'], 'class' => 'rounded img-preview', 'alt' => $row['name' . $lang]]) ?>
                    </a>
                  </td>

                  <!-- Tên sản phẩm -->
                  <td class="align-middle">
                    <a class="text-dark text-break" href="<?= $linkEdit . $row['id'] ?>" title="<?= $row["name{$lang}"] ?>">
                      <?= $row["name{$lang}"] ?>
                    </a>
                    <div class="tool-action mt-2 w-clear">
                      <a class="text-primary mr-3" href="<?= BASE . $row['slug' . $lang] ?>" target="_blank" title="Xem">
                        <i class="far fa-eye mr-1"></i>View
                      </a>
                      <a class="text-info mr-3" href="<?= $linkEdit . $row['id'] ?>" title="Chỉnh sửa">
                        <i class="far fa-edit mr-1"></i>Edit
                      </a>
                      <a class="text-danger" id="delete-item" data-url="<?= $linkDelete . $row['id'] ?>" title="Xóa">
                        <i class="far fa-trash-alt mr-1"></i>Delete
                      </a>
                    </div>
                  </td>

                  <?php if (!empty($config['product'][$type]['gallery'])): ?>
                    <td class="align-middle">
                      <div class="dropdown">
                        <button type="button" class="btn btn-sm bg-gradient-info dropdown-toggle" data-toggle="dropdown">Thêm</button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item text-dark" href="<?= $linkGalleryForm . $row['id'] ?>" title="Gallery">
                            <i class="far fa-caret-square-right text-secondary mr-2"></i>Thêm Gallery
                          </a>
                          <a class="dropdown-item text-dark" href="<?= $linkGalleryMan . $row['id'] ?>" title="Gallery">
                            <i class="far fa-caret-square-right text-secondary mr-2"></i>Danh sách Gallery
                          </a>
                        </div>
                      </div>
                    </td>
                  <?php endif; ?>

                  <!-- Danh mục cấp 1 -->
                  <td class="align-middle"><?= $row['name_list'] ?? '' ?></td>

                  <!-- Danh mục cấp 2 -->
                  <td class="align-middle"><?= $row['name_cat'] ?? '' ?></td>

                  <?php foreach ($config['product'][$type]['check'] as $attr => $label): ?>
                    <td class="align-middle text-center">
                      <label class="switch switch-success">
                        <input type="checkbox" class="switch-input custom-control-input show-checkbox"
                          id="show-checkbox-<?= $attr ?>-<?= $row['id'] ?>"
                          data-table="<?= $table ?>" data-id="<?= $row['id'] ?>" data-attr="<?= $attr ?>"
                          <?= (strpos($row['status'], $attr) !== false) ? 'checked' : '' ?>>
                      </label>
                    </td>
                  <?php endforeach; ?>
                  <!-- Hành động -->
                  <td class="align-middle text-center text-md text-nowrap">
                    <a class="text-primary mr-2" href="<?= $linkEdit . $row['id'] ?>" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                    <a class="text-danger" id="delete-item" data-url="<?= $linkDelete . $row['id'] ?>" title="Xóa"><i class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
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
  <?php if ($paging): ?><div class="card-footer text-sm p-3"><?= $paging ?></div><?php endif; ?>
</section>
