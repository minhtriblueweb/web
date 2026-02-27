<?php
$image = $this->getImage([
  'file' => $params['photo'],
  'width' => 150,
  'height' => 150,
  'thumb' => false,
  'class' => 'img-fluid rounded mx-auto d-block'
]);
?>

<li class="jFiler-item <?= $params['col'] ?>">
  <div class="jFiler-item-container">
    <div class="jFiler-item-inner">
      <div class="jFiler-item-thumb">
        <div class="jFiler-item-status"></div>
        <div class="jFiler-item-thumb-overlay">
          <div class="jFiler-item-info">
            <div style="display: table-cell; vertical-align: middle;">
              <span class="jFiler-item-title">
                <b title="<?= htmlspecialchars($params['name']) ?>">
                  <?= htmlspecialchars($params['name']) ?>
                </b>
              </span>
            </div>
          </div>
        </div>
        <?= $image ?>
      </div>
      <div class="jFiler-item-assets jFiler-row">
        <ul class="list-inline pull-right d-flex align-items-center justify-content-between w-100">
          <li class="ml-1">
            <a class="icon-jfi-trash jFiler-item-trash-action my-jFiler-item-trash"
              data-id="<?= (int)$params['id'] ?>" data-photo="<?= htmlspecialchars($params['photo']) ?>"></a>
          </li>
          <li class="mr-1">
            <div class="custom-control custom-checkbox d-inline-block align-middle text-md">
              <input type="checkbox"
                class="custom-control-input filer-checkbox"
                id="filer-checkbox-<?= (int)$params['id'] ?>"
                value="<?= (int)$params['id'] ?>">
              <label for="filer-checkbox-<?= (int)$params['id'] ?>"
                class="custom-control-label font-weight-normal"
                data-label="<?= chon ?>"><?= chon ?></label>
            </div>
          </li>
        </ul>
      </div>

      <input type="number" class="form-control form-control-sm mb-1" name="data[numb-filer][]" placeholder="<?= sothutu ?>" value="<?= (int)$params['numb'] ?>">
      <input type="text" class="form-control form-control-sm" name="data[name-filer][]" placeholder="<?= tieude ?>" value="<?= htmlspecialchars($params['name']) ?>">
      <input type="hidden" name="data[id-filer][]" value="<?= (int)$params['id'] ?>">
      <input type="hidden" name="data[photo-filer][]" value="<?= htmlspecialchars($params['photo']) ?>">
    </div>
  </div>
</li>
