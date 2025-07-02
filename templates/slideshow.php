<?php
$slides = $fn->show_data([
  'table'  => 'tbl_slideshow',
  'status' => 'hienthi',
  'select' => "file, name{$lang}, link"
]);
?>
<?php if (!empty($slides)): ?>
  <div class="wrap-slideshow">
    <div class="wrap-content d-flex flex-wrap justify-content-end align-items-start">
      <div class="slideshow">
        <div class="slick-slideshow">
          <?php foreach ($slides as $slide): ?>
            <?php
            $name = $slide['name' . $lang];
            $link = !empty($slide['link']) ? $slide['link'] : 'javascript:void(0)';
            ?>
            <div class="slideshow-item">
              <a class="slideshow-image" href="<?= $link ?>" target="_blank" title="<?= $name ?>">
                <?= $fn->getImage([
                  'file'   => $slide['file'],
                  'alt'    => $name,
                  'title'  => $name,
                  'lazy'   => false
                ]) ?>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
