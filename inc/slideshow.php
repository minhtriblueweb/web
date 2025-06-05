<div class="wrap-slideshow">
  <div class="wrap-content d-flex flex-wrap justify-content-end align-items-start">
    <div class="slideshow">
      <div class="owl-page owl-carousel owl-theme" data-items="screen:0|items:1" data-rewind="1" data-autoplay="1"
        data-loop="0" data-lazyload="0" data-mousedrag="0" data-touchdrag="0" data-smartspeed="800"
        data-autoplayspeed="800" data-autoplaytimeout="5000" data-dots="0"
        data-animations="animate__fadeInDown, animate__backInUp, animate__rollIn, animate__backInRight, animate__zoomInUp, animate__backInLeft, animate__rotateInDownLeft, animate__backInDown, animate__zoomInDown, animate__fadeInUp, animate__zoomIn"
        data-nav="1" data-navcontainer=".control-slideshow">
        <!-- Lặp slideshow-item  -->
        <?php
        $show_slideshow = $slideshow->show_slideshow();
        if ($show_slideshow) {
          while ($result_slideshow = $show_slideshow->fetch_assoc()) {
        ?>
            <div class="slideshow-item" owl-item-animation>
              <a class="slideshow-image" href="<?php echo $result_slideshow['link'] ?>" target="_blank"
                title="<?php echo $result_slideshow['name'] ?>">
                <picture>
                  <img class="w-100" src="<?= BASE_ADMIN . UPLOADS . $result_slideshow['file'] ?>"
                    alt="<?php echo $result_slideshow['name'] ?>" title="<?php echo $result_slideshow['name'] ?>" />
                </picture>
              </a>
            </div>
        <?php }
        } ?>
      </div>
      <div class="control-slideshow control-owl transition"></div>
    </div>
  </div>
</div>