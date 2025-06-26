<?php
$slides = [];
$show_slideshow = $fn->show_data([
  'table' => 'tbl_slideshow',
  'status' => 'hienthi'
]);
if ($show_slideshow && $show_slideshow->num_rows > 0) {
  while ($row = $show_slideshow->fetch_assoc()) {
    $slides[] = $row;
  }
}
?>
<?php if (!empty($slides)): ?>
  <div class="wrap-slideshow">
    <div class="wrap-content d-flex flex-wrap justify-content-end align-items-start">
      <div class="slideshow">
        <div class="slick-slideshow">
          <?php foreach ($slides as $slide): ?>
            <div class="slideshow-item">
              <a class="slideshow-image" href="<?= $slide['link'] ?>" target="_blank" title="<?= $slide['namevi'] ?>">
                <?= $fn->getImage([
                  'file' => $slide['file'],
                  'alt' => $slide['namevi'],
                  'title' => $slide['namevi'],
                  'lazy' => false
                ]) ?>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
