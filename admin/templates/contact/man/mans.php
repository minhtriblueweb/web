<?php
$linkMan = "index.php?page=contact&act=man&type=lien-he";
$linkEdit = "index.php?page=contact&act=edit";
$linkDelete = "index.php?page=contact&act=delete&id=";
$linkMulti  = "index.php?page=contact&act=delete_multiple";
?>
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= quanlylienhe ?></li>
      </ol>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="card-footer text-sm sticky-top">
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
      <h3 class="card-title"><?= danhsachlienhe ?></h3>
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
            <th class="align-middle"><?= hoten ?></th>
            <th class="align-middle"><?= dienthoai ?></th>
            <th class="align-middle">Email</th>
            <th class="align-middle"><?= ngaytao ?></th>
            <?php foreach ($config['contact']['check'] ?? [] as $value): ?>
              <th class="align-middle text-center"><?= $value ?></th>
            <?php endforeach; ?>
            <th class="align-middle text-center"><?= thaotac ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($show_data)): ?>
            <tr>
              <td colspan="100" class="text-center"><?= khongcodulieu ?></td>
            </tr>
          <?php else: ?>
            <?php foreach ($show_data as $row): ?>
              <?php $status_array = !empty($row['status']) ? explode(',', $row['status']) : []; ?>
              <tr>
                <td class="align-middle">
                  <div class="custom-control custom-checkbox my-checkbox">
                    <input type="checkbox"
                      class="custom-control-input select-checkbox"
                      id="select-checkbox-<?= $row['id'] ?>"
                      value="<?= $row['id'] ?>">
                    <label for="select-checkbox-<?= $row['id'] ?>" class="custom-control-label"></label>
                  </div>
                </td>

                <td class="align-middle">
                  <input type="number"
                    class="form-control form-control-mini m-auto update-numb"
                    min="0"
                    value="<?= $row['numb'] ?>"
                    data-id="<?= $row['id'] ?>"
                    data-table="<?= $table ?>">
                </td>

                <td class="align-middle">
                  <a class="text-dark text-break"
                    href="<?= $linkEdit ?>&id=<?= $row['id'] ?>"
                    title="<?= $row['fullname'] ?>">
                    <?= $row['fullname'] ?>
                  </a>
                </td>
                <td class="align-middle">
                  <a class="text-dark text-break"
                    href="<?= $linkEdit ?>&id=<?= $row['id'] ?>"
                    title="<?= $row['phone'] ?>">
                    <?= $row['phone'] ?>
                  </a>
                </td>

                <td class="align-middle">
                  <a class="text-dark text-break"
                    href="<?= $linkEdit ?>&id=<?= $row['id'] ?>"
                    title="<?= $row['email'] ?>">
                    <?= $row['email'] ?>
                  </a>
                </td>
                <td class="align-middle">
                  <?= date('H:i - d/m/Y', strtotime($row['date_created'])) ?>
                </td>
                <?php foreach ($config['contact']['check'] as $attr => $label): ?>
                  <td class="align-middle text-center">
                    <label class="switch switch-success">
                      <input type="checkbox" class="switch-input custom-control-input show-checkbox" id="show-checkbox-<?= $attr ?>-<?= $row['id'] ?>"
                        data-table="<?= $table ?>" data-id="<?= $row['id'] ?>" data-attr="<?= $attr ?>" <?= (strpos($row['status'] ?? '', $attr) !== false) ? 'checked' : '' ?>>
                    </label>
                  </td>
                <?php endforeach; ?>
                <td class="align-middle text-center text-md text-nowrap">
                  <a class="text-primary mr-2"
                    href="<?= $linkEdit ?>&id=<?= $row['id'] ?>"
                    title="<?= chinhsua ?>">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a class="text-danger"
                    id="delete-item"
                    data-url="<?= $linkDelete ?>&id=<?= $row['id'] ?>"
                    title="Xóa">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($paging): ?><div class="card-footer text-sm p-3"><?= $paging ?></div><?php endif; ?>
  <div class="card-footer text-sm">
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?= $linkMulti ?>" title="<?= xoatatca ?>"><i class="far fa-trash-alt mr-2"></i><?= xoatatca ?></a>
  </div>
</section>
