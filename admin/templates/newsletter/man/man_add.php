<?php
$linkMan = "index.php?page=newsletter&act=man&type=" . $type;
?>
<!-- Content Header -->
<section class="content-header text-sm">
  <div class="container-fluid">
    <div class="row">
      <ol class="breadcrumb float-sm-left">
        <li class="breadcrumb-item"><a href="index.php" title="<?= dashboard ?>"><?= dashboard ?></a></li>
        <li class="breadcrumb-item"><a href="<?= $linkMan ?>" title="Quản lý <?= $config['newsletter'][$type]['title_main'] ?>">Quản lý <?= $config['newsletter'][$type]['title_main'] ?></a></li>
        <li class="breadcrumb-item active"><?= ($act == "edit") ? capnhat : themmoi; ?> <?= $config['newsletter'][$type]['title_main'] ?></li>
      </ol>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <form class="validation-form" novalidate method="post" action="" enctype="multipart/form-data">
    <div class="card-footer text-sm sticky-top">
      <button type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i class="far fa-save mr-2"></i><?= luu ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i><?= lamlai ?></button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="<?= thoat ?>"><i class="fas fa-sign-out-alt mr-2"></i><?= thoat ?></a>
    </div>

    <div class="card card-primary card-outline text-sm">
      <div class="card-header">
        <h3 class="card-title"><?= chitiet ?> <?= $config['newsletter'][$type]['title_main'] ?></h3>
      </div>
      <div class="card-body">
        <?php if (isset($config['newsletter'][$type]['file']) && $config['newsletter'][$type]['file'] == true) { ?>
          <div class="form-group">
            <div class="upload-file mb-2">
              <p><?= uploadtaptin ?>:</p>
              <label class="upload-file-label mb-2" for="file_attach">
                <div class="custom-file my-custom-file">
                  <input type="file" class="custom-file-input" name="file_attach" id="file_attach" lang="vi">
                  <label class="custom-file-label mb-0" data-browse="Chọn" for="file_attach"><?= chonfile ?></label>
                </div>
              </label>
              <?php if (isset($item['file_attach']) && $item['file_attach'] != '') { ?>
                <div class="file-uploaded mb-2">
                  <a class="btn btn-sm bg-gradient-primary text-white d-inline-block align-middle rounded p-2" href="" title="<?= downloadtaptinhientai ?>"><i class="fas fa-download mr-2"></i><?= downloadtaptinhientai ?></a>
                </div>
              <?php } ?>
              <strong class="d-block text-sm"><?= $config['newsletter'][$type]['file_type'] ?></strong>
            </div>
          </div>
        <?php } ?>
        <?php
        $cfg  = $config['newsletter'][$type] ?? [];
        $data = $_POST['data'] ?? $item ?? [];
        $fields = [
          'fullname' => ['label' => hoten, 'type' => 'text',  'col' => 4],
          'email'    => ['label' => 'Email', 'type' => 'email', 'col' => 4],
          'phone'    => ['label' => dienthoai, 'type' => 'text', 'col' => 4],
          'address'  => ['label' => diachi ?? 'Địa chỉ', 'type' => 'text', 'col' => 4],
          'subject'  => ['label' => chude ?? 'Chủ đề',   'type' => 'text', 'col' => 4],
        ];
        ?>

        <div class="form-group-category row">
          <?php foreach ($fields as $name => $opt): ?>
            <?php if (!empty($cfg[$name])): ?>
              <div class="form-group col-md-<?= $opt['col'] ?>">
                <label for="<?= $name ?>"><?= $opt['label'] ?>:</label>
                <input
                  type="<?= $opt['type'] ?>"
                  class="form-control text-sm"
                  name="data[<?= $name ?>]"
                  id="<?= $name ?>"
                  value="<?= htmlspecialchars($data[$name] ?? '') ?>">
              </div>
            <?php endif; ?>
          <?php endforeach; ?>

          <?php if (!empty($cfg['confirm_status'])): ?>
            <div class="form-group col-md-4">
              <label><?= tinhtrang ?>:</label>
              <select name="data[confirm_status]" class="form-control select2">
                <option value="0"><?= capnhattinhtrang ?></option>
                <?php foreach ($cfg['confirm_status'] as $k => $v): ?>
                  <option value="<?= $k ?>" <?= (($data['confirm_status'] ?? 0) == $k) ? 'selected' : '' ?>>
                    <?= $v ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>
        </div>

        <?php if (!empty($cfg['content'])): ?>
          <div class="form-group">
            <label><?= noidung ?>:</label>
            <textarea class="form-control text-sm" name="data[content]" rows="5"><?= htmlspecialchars($data['content'] ?? '') ?></textarea>
          </div>
        <?php endif; ?>

        <?php if (!empty($cfg['notes'])): ?>
          <div class="form-group">
            <label><?= ghichu ?>:</label>
            <textarea class="form-control text-sm" name="data[notes]" rows="5"><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
          </div>
        <?php endif; ?>

        <div class="form-group">
          <label for="numb" class="d-inline-block align-middle mb-0 mr-2"><?= sothutu ?>:</label>
          <input type="number" class="form-control form-control-mini d-inline-block align-middle" min="0" name="data[numb]" id="numb" placeholder="<?= sothutu ?>" value="<?= isset($item['numb']) ? $item['numb'] : 1 ?>">
        </div>
      </div>
    </div>
    <div class="card-footer text-sm">
      <button type="submit" class="btn btn-sm bg-gradient-primary submit-check" disabled><i class="far fa-save mr-2"></i><?= luu ?></button>
      <button type="reset" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-redo mr-2"></i><?= lamlai ?></button>
      <a class="btn btn-sm bg-gradient-danger" href="<?= $linkMan ?>" title="<?= thoat ?>"><i class="fas fa-sign-out-alt mr-2"></i><?= thoat ?></a>
      <input type="hidden" name="id" value="<?= (isset($item['id']) && $item['id'] > 0) ? $item['id'] : '' ?>">
      <input type="hidden" name="data[type]" value="<?= $type ?>">
    </div>
  </form>
</section>
