<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= $config['photo']['photo_man'][$type]['title_main_photo'] ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="<?= $linkForm ?>" title="Thêm mới"><i class="fas fa-plus mr-2"></i>Thêm mới</a>
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?= $linkMulti ?>" title="Xóa tất cả"><i class="far fa-trash-alt mr-2"></i>Xóa tất cả</a>
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
  <div class="card card-primary card-outline text-sm mb-0">
    <div class="card-header">
      <h3 class="card-title">Danh sách <?= $config['photo']['photo_man'][$type]['title_main_photo'] ?></h3>
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
            <th class="align-middle" width="20%">Tiêu đề</th>
            <?php if (!empty($config['photo']['photo_man'][$type]['link_photo'])): ?>
              <th class="align-middle">Link</th>
            <?php endif; ?>
            <?php foreach ($config['photo']['photo_man'][$type]['status_photo'] as $attr => $label): ?>
              <th class="align-middle text-center"><?= $label ?></th>
            <?php endforeach; ?>
            <th class="align-middle text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($show_data)): ?>
            <?php foreach ($show_data as $row):
              $id = $row['id'];
              $name = $row['namevi'];
              $numb = $row['numb'];
              $link = $row['link'];
              $status = $row['status'] ?? '';
              $file = $row['file'];
              $linkEditId = $linkEdit . $id;
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

                <!-- Hình ảnh -->
                <td class="align-middle text-center">
                  <a href="<?= $linkEditId ?>" title="<?= $name ?>">
                    <?= $fn->getImage([
                      'file' => $file,
                      'class' => 'rounded img-preview',
                      'alt' => $name,
                    ]) ?>
                  </a>
                </td>

                <!-- Tên -->
                <td class="align-middle">
                  <a class="text-dark text-break" href="<?= $linkEditId ?>" title="<?= $name ?>">
                    <?= $name ?>
                  </a>
                </td>

                <!-- Link -->
                <?php if (!empty($config['photo']['photo_man'][$type]['link_photo'])): ?>
                  <td class="align-middle">
                    <?= $link ?>
                  </td>
                <?php endif; ?>

                <?php foreach ($config['photo']['photo_man'][$type]['status_photo'] as $attr => $label): ?>
                  <td class="align-middle text-center">
                    <label class="switch switch-success">
                      <input type="checkbox" class="switch-input custom-control-input show-checkbox"
                        id="show-checkbox-<?= $attr ?>-<?= $row['id'] ?>"
                        data-table="<?= $table ?>"
                        data-id="<?= $row['id'] ?>"
                        data-attr="<?= $attr ?>"
                        <?= (strpos($row['status'], $attr) !== false) ? 'checked' : '' ?>>
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
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="100" class="text-center">Không có dữ liệu</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($paging): ?><div class="card-footer text-sm p-3"><?= $paging ?></div><?php endif; ?>
</section>
