<div class="photoUpload-zone">
  <div class="photoUpload-detail" id="photoUpload-preview">
    <?= $fn->getImage([
      'width' => $thumb_width,
      'height' => $thumb_height,
      'file' => $result['file'] ?? '',
      'class' => 'rounded',
      'alt' => 'Alt Photo',
    ]) ?>
  </div>
  <label class="photoUpload-file" id="photo-zone" for="file-zone">
    <input type="file" name="file" id="file-zone" />
    <i class="fas fa-cloud-upload-alt"></i>
    <p class="photoUpload-drop">Kéo và thả hình vào đây</p>
    <p class="photoUpload-or">hoặc</p>
    <p class="photoUpload-choose btn btn-sm bg-gradient-success">
      Chọn hình
    </p>
  </label>
  <div class="photoUpload-dimension">
    Width: <?= $thumb_width ?> px - Height: <?= $thumb_height ?> px
    (.jpg|.gif|.png|.jpeg|.gif|.webp|.WEBP)
  </div>
</div>
