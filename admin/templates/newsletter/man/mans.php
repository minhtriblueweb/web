<?php
$linkMan = "index.php?page=newsletter&act=man&type=" . $type;
$linkAdd = $linkEdit = "index.php?page=newsletter&act=edit&type=" . $type;
$linkDelete = "index.php?page=newsletter&act=delete&type=" . $type;
$linkMulti  = "index.php?page=newsletter&act=delete_multiple&type=" . $type;
?>
<!-- Content Header -->
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item active"><?= quanlynhantin ?></li>
      </ol>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="card-footer text-sm sticky-top">
    <?php if (isset($config['newsletter'][$type]['is_send']) && $config['newsletter'][$type]['is_send'] == true) { ?>
      <a class="btn btn-sm bg-gradient-success text-white" id="send-email" title="Gửi email"><i class="fas fa-paper-plane mr-2"></i><?= guiemail ?></a>
    <?php } ?>
    <a class="btn btn-sm bg-gradient-primary text-white" href="<?= $linkAdd ?>" title="Thêm mới"><i class="fas fa-plus mr-2"></i><?= themmoi ?></a>
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?= $linkMulti ?>" title="<?= xoatatca ?>"><i class="far fa-trash-alt mr-2"></i><?= xoatatca ?></a>
    <div class="form-inline form-search d-inline-block align-middle ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar text-sm" type="search" id="keyword" placeholder="<?= timkiem ?>" aria-label="<?= timkiem ?>" value="<?= (isset($_GET['keyword'])) ? $_GET['keyword'] : '' ?>" onkeypress="doEnter(event,'keyword','<?= $linkMan ?>')">
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
      <h3 class="card-title"><?= danhsach ?> <?= $config['newsletter'][$type]['title_main'] ?></h3>
      <?php if (isset($config['newsletter'][$type]['is_send']) && $config['newsletter'][$type]['is_send'] == true) { ?>
        <p class="d-block text-secondary w-100 float-left mb-0 mt-1"><?= chonemailsaudokeoxuongduoicungdanhsachnaydecothethietlapnoidungemailmuonguidi ?>.</p>
      <?php } ?>
    </div>
    <div class="card-body table-responsive p-0">
      <?php $cfg = $config['newsletter'][$type] ?? []; ?>
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
            <?php
            $heads = [
              'show_name'      => hoten,
              'email'          => 'Email',
              'show_phone'     => dienthoai,
              'file'           => 'Download',
              'show_date'      => ngaytao,
              'confirm_status' => tinhtrang
            ];
            foreach ($heads as $key => $label) {
              if (!empty($cfg[$key])) {
                echo '<th class="align-middle">' . $label . '</th>';
              }
            }
            ?>
            <th class="align-middle text-center"><?= thaotac ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($items)) { ?>
            <tr>
              <td colspan="100" class="text-center"><?= khongcodulieu ?></td>
            </tr>
            <?php } else {
            foreach ($items as $item) { ?>
              <tr>
                <td class="align-middle">
                  <div class="custom-control custom-checkbox my-checkbox">
                    <input type="checkbox" class="custom-control-input select-checkbox"
                      id="select-checkbox-<?= $item['id'] ?>" value="<?= $item['id'] ?>">
                    <label for="select-checkbox-<?= $item['id'] ?>" class="custom-control-label"></label>
                  </div>
                </td>

                <td class="align-middle">
                  <input type="number"
                    class="form-control form-control-mini m-auto update-numb"
                    min="0"
                    value="<?= $item['numb'] ?>"
                    data-id="<?= $item['id'] ?>"
                    data-table="newsletter">
                </td>

                <?php if (!empty($cfg['show_name'])) { ?>
                  <td class="align-middle">
                    <a class="text-dark text-break"
                      href="<?= $linkEdit ?>&id=<?= $item['id'] ?>">
                      <?= $item['fullname'] ?>
                    </a>
                  </td>
                <?php } ?>

                <?php if (!empty($cfg['email'])) { ?>
                  <td class="align-middle">
                    <a class="text-dark text-break"
                      href="<?= $linkEdit ?>&id=<?= $item['id'] ?>">
                      <?= $item['email'] ?>
                    </a>
                  </td>
                <?php } ?>

                <?php if (!empty($cfg['show_phone'])) { ?>
                  <td class="align-middle"><?= $item['phone'] ?></td>
                <?php } ?>

                <?php if (!empty($cfg['file'])) { ?>
                  <td class="align-middle">
                    <?php if (!empty($item['file_attach'])) { ?>
                      <a class="btn btn-sm bg-gradient-primary text-white p-1 rounded" href="">
                        <i class="fas fa-download mr-2"></i>Download
                      </a>
                    <?php } else { ?>
                      <span class="bg-gradient-secondary text-white p-1 rounded">
                        <i class="fas fa-download mr-2"></i>Trống
                      </span>
                    <?php } ?>
                  </td>
                <?php } ?>

                <?php if (!empty($cfg['show_date'])) { ?>
                  <td class="align-middle">
                    <?= date('H:i:s - d/m/Y', strtotime($item['date_created'])) ?>
                  </td>
                <?php } ?>

                <?php if (!empty($cfg['confirm_status'])) { ?>
                  <td class="align-middle">
                    <?= $fn->getStatusNewsletter($item['confirm_status'], $type) ?>
                  </td>
                <?php } ?>

                <td class="align-middle text-center text-nowrap">
                  <a class="text-primary mr-2"
                    href="<?= $linkEdit ?>&id=<?= $item['id'] ?>">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a class="text-danger"
                    id="delete-item"
                    data-url="<?= $linkDelete ?>&id=<?= $item['id'] ?>">
                    <i class="fas fa-trash-alt"></i>
                  </a>
                </td>
              </tr>
          <?php }
          } ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($paging): ?><div class="card-footer text-sm p-3"><?= $paging ?></div><?php endif; ?>
  <?php if (isset($config['newsletter'][$type]['is_send']) && $config['newsletter'][$type]['is_send'] == true) { ?>
    <div class="card card-primary card-outline text-sm mb-0 <?= (!$paging) ? 'mt-3' : ''; ?>">
      <form name="frmsendemail" method="post" action="<?= $linkMan ?>" enctype="multipart/form-data">
        <div class="card-header">
          <h3 class="card-title">Gửi email đến danh sách được chọn</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="subject">Tiêu đề:</label>
            <input type="text" class="form-control text-sm" name="subject" id="subject" placeholder="Tiêu đề">
          </div>
          <div class="form-group">
            <label class="d-inline-block align-middle mb-1 mr-2"><?= uploadtaptin ?>:</label>
            <strong class="d-block mt-2 mb-2 text-sm"><?php echo $config['newsletter'][$type]['file_type'] ?></strong>
            <div class="custom-file my-custom-file">
              <input type="file" class="custom-file-input" name="file" id="file">
              <label class="custom-file-label" for="file"><?= chonfile ?></label>
            </div>
          </div>
          <div class="form-group">
            <label for="content"><?= noidung ?> thông tin:</label>
            <textarea class="form-control form-control-ckeditor" name="content" id="content" rows="5" placeholder="Nội dung thông tin"></textarea>
          </div>
          <input type="hidden" name="listemail" id="listemail">
        </div>
      </form>
    </div>
  <?php } ?>
  <div class="card-footer text-sm">
    <?php if (isset($config['newsletter'][$type]['is_send']) && $config['newsletter'][$type]['is_send'] == true) { ?>
      <a class="btn btn-sm bg-gradient-success text-white" id="send-email" title="Gửi email"><i class="fas fa-paper-plane mr-2"></i>Gửi email</a>
    <?php } ?>
    <a class="btn btn-sm bg-gradient-primary text-white" href="<?= $linkAdd ?>" title="Thêm mới"><i class="fas fa-plus mr-2"></i><?= themmoi ?></a>
    <a class="btn btn-sm bg-gradient-danger text-white" id="delete-all" data-url="<?= $linkMulti ?>" title="<?= xoatatca ?>"><i class="far fa-trash-alt mr-2"></i><?= xoatatca ?></a>
  </div>
</section>
