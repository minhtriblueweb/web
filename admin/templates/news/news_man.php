<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= $config['news'][$type]['title_main'] ?></li>
      </ol>
    </div>
  </div>
</section>
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <a class="btn btn-sm bg-gradient-primary text-white" href="<?= $linkForm ?>" title="<?= themmoi ?>"><i class="fas fa-plus mr-2"></i><?= themmoi ?></a>
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?= $linkMulti ?>" title="<?= xoatatca ?>"><i class="far fa-trash-alt mr-2"></i><?= xoatatca ?></a>
    <div class="form-inline form-search d-inline-block align-middle ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar text-sm" type="search" id="keyword" placeholder="<?= timkiem ?>" aria-label="<?= timkiem ?>" value="<?= $keyword ?>" onkeypress="doEnter(event,'keyword','<?= $linkMan ?>')">
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
      <h3 class="card-title"><?= danhsach ?> <?= $config['news'][$type]['title_main'] ?></h3>
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
            <th class="align-middle"><?= hinh ?></th>
            <th class="align-middle" style="width: 30%"><?= tieude ?></th>
            <?php foreach ($config['news'][$type]['check'] as $attr => $label): ?>
              <th class="align-middle text-center"><?= defined($attr) ? constant($attr) : $attr ?></th>
            <?php endforeach; ?>
            <th class="align-middle text-center"><?= thaotac ?></th>
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
                        id="select-checkbox-<?= $row['id'] ?>" value="<?= $row['id'] ?>" name="checkbox_id<?= $row['id'] ?>" />
                      <label for="select-checkbox-<?= $row['id'] ?>" class="custom-control-label"></label>
                    </div>
                  </td>

                  <!-- STT -->
                  <td class="align-middle">
                    <input type="number" class="form-control form-control-mini m-auto update-numb" min="0"
                      value="<?= $row['numb'] ?>" data-id="<?= $row['id'] ?>" data-table="<?= $table ?>" />
                  </td>

                  <!-- Ảnh -->
                  <td class="align-middle">
                    <a href="<?= $linkEdit . $row['id'] ?>" title="<?= $row["name$lang"] ?>">
                      <?= $fn->getImage([
                        'file' => $row['file'],
                        'class' => 'rounded img-preview',
                        'alt' => $row["name$lang"],
                      ]) ?>
                    </a>
                  </td>

                  <!-- Tên + tools -->
                  <td class="align-middle">
                    <a class="text-dark text-break" href="<?= $linkEdit . $row['id'] ?>" title="<?= $row["name$lang"] ?>"><?= $row["name$lang"] ?></a>
                    <div class="tool-action mt-2 w-clear">
                      <?php if (!empty($config['news'][$type]['view'])): ?>
                        <a class="text-primary mr-3" href="<?= BASE . $row["slug$lang"] ?>" target="_blank" title="Xem"><i class="far fa-eye mr-1"></i>View</a>
                      <?php endif; ?>
                      <a class="text-info mr-3" href="<?= $linkEdit . $row['id'] ?>" title="Chỉnh sửa"><i class="far fa-edit mr-1"></i>Edit</a>
                      <a class="text-danger" id="delete-item" data-url="<?= $linkDelete . $row['id'] ?>" title="Xoá"><i class="far fa-trash-alt mr-1"></i>Delete</a>
                    </div>
                  </td>

                  <!-- Checkbox Hiển thị, Nổi bật -->
                  <?php foreach ($config['news'][$type]['check'] as $attr => $label): ?>
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
                    <a class="text-primary mr-2" href="<?= $linkEdit . $row['id'] ?>" title="<?= chinhsua ?>"><i class="fas fa-edit"></i></a>
                    <a class="text-danger" id="delete-item" data-url="<?= $linkDelete . $row['id'] ?>" title="<?= xoa ?>"><i class="fas fa-trash-alt"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="100" class="text-center"><?= khongcodulieu ?></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </form>
      </table>
    </div>
  </div>
  <?php if ($paging): ?><div class="card-footer text-sm p-3"><?= $paging ?></div><?php endif; ?>
</section>
