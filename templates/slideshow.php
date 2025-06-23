<div class="wrap-slideshow">
  <div class="wrap-content d-flex flex-wrap justify-content-end align-items-start">
    <div class="slideshow">
      <div class="owl-page owl-carousel owl-theme" data-items="screen:0|items:1" data-rewind="1" data-autoplay="1"
        data-loop="0" data-lazyload="0" data-mousedrag="0" data-touchdrag="0" data-smartspeed="800"
        data-autoplayspeed="800" data-autoplaytimeout="5000" data-dots="0"
        data-animations="animate__fadeInDown, animate__backInUp, animate__rollIn, animate__backInRight, animate__zoomInUp, animate__backInLeft, animate__rotateInDownLeft, animate__backInDown, animate__zoomInDown, animate__fadeInUp, animate__zoomIn"
        data-nav="1" data-navcontainer=".control-slideshow">
        <?php
        $show_slideshow = $fn->show_data([
          'table' => 'tbl_slideshow',
          'status' => 'hienthi'
        ]);
        if ($show_slideshow):
          while ($row_slider = $show_slideshow->fetch_assoc()):
        ?>
            <div class="slideshow-item" owl-item-animation>
              <a class="slideshow-image" href="<?= $row_slider['link'] ?>" target="_blank" title="<?= $row_slider['namevi'] ?>">
                <picture>
                  <img class="w-100"
                    src="<?= empty($row_slider['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row_slider['file'] ?>"
                    alt="<?= $row_slider['namevi'] ?>" title="<?= $row_slider['namevi'] ?>" />
                </picture>
              </a>
            </div>
        <?php
          endwhile;
        endif;
        ?>

      </div>
      <div class="control-slideshow control-owl transition"></div>
    </div>
  </div>
</div>
