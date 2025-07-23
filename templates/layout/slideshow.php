<?php if (!empty($slides)): ?>
  <div class="wrap-slideshow">
    <div class="wrap-content d-flex flex-wrap justify-content-end align-items-start">
      <div class="slideshow">
        <div class="slick-slideshow">
          <?php foreach ($slides as $slide):
            $opts = json_decode($slide['options'] ?? '', true);
            $width  = isset($opts['width']) ? (int)$opts['width'] : '';
            $height = isset($opts['height']) ? (int)$opts['height'] : '';
            $zc     = isset($opts['zc']) ? (int)$opts['zc'] : 1;
          ?>

            <div class="slideshow-item">
              <a class="slideshow-image" href="<?= !empty($slide['link']) ? $slide['link'] : 'javascript:void(0)' ?>" target="_blank" title="<?= $slide["name$lang"] ?>">
                <?= $fn->getImageCustom([
                  'file'   => $slide['file'],
                  'width' => $width,
                  'height' => $height,
                  'zc' => $zc,
                  'title'  => $slide["name$lang"],
                  'alt'    => $slide["name$lang"],
                  'lazy'   => true
                ]) ?>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
