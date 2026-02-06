<?php
$payment = $fn->show_data(['table' => 'tbl_photo', 'type'  => 'payment', 'status' => 'hienthi', 'select' => "id, file,name{$lang}"]);
$footer_static = $db->rawQueryOne("SELECT content$lang FROM tbl_static WHERE type = ? AND FIND_IN_SET(?, status) LIMIT 1", ['footer', 'hienthi']);
$hotrokhachhang = $db->rawQueryOne("SELECT name$lang,content$lang FROM tbl_static WHERE type = ? AND FIND_IN_SET(?, status) LIMIT 1", ['ho-tro-khach-hang', 'hienthi']);
$dangkynhantin = $db->rawQueryOne("SELECT name$lang,content$lang FROM tbl_static WHERE type = ? AND FIND_IN_SET(?, status) LIMIT 1", ['dang-ky-nhan-tin', 'hienthi']);
$hotro247 = $db->rawQueryOne("SELECT name$lang,content$lang FROM tbl_static WHERE type = ? AND FIND_IN_SET(?, status) LIMIT 1", ['ho-tro-247', 'hienthi']);
$background_footer = $db->rawQueryOne("SELECT `file` FROM tbl_photo WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['background_footer'])['file'] ?? '';
?>
<div class="footer" style="background: #f2f2f2 url('<?= BASE_ADMIN . UPLOADS . $background_footer ?>') no-repeat center;">
  <div class="footer-article">
    <div class="wrap-content d-flex flex-wrap justify-content-between">
      <div class="footer-banner">
        <div class="logo-footer">
          <?php if ($logo) : ?>
            <a href="./">
              <?= $fn->getImageCustom(['file' => $logo['file'], 'width'  => 200, 'height'  => 200, 'zc' => 4, 'alt' => $optsetting["name$lang"], 'title' => $optsetting["name$lang"], 'lazy' => false]) ?>
            </a>
          <?php endif ?>
        </div>
        <p class="footer-company"><?= $web_name ?></p>
        <div class="footer-info .content-ck"><?= $footer_static["content$lang"] ?? '' ?></div>
        <div class="social">
          <p>KẾT NỐI VỚI CHÚNG TÔI</p>
          <?php if (!empty($show_social)): ?>
            <?php foreach ($show_social as $row): ?>
              <a class="hvr-icon-rotate me-2" href="<?= $row['link'] ?>" target="_blank" rel="noopener noreferrer">
                <?= $fn->getImageCustom(['width' => 40, 'height' => 40, 'zc' => 1, 'file'   => $row['file'], 'class'  => 'hvr-icon', 'alt' => $row["name$lang"], 'title' => $row["name$lang"], 'lazy' => true]) ?>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="footer-right">
        <div class="box-policy pe-3">
          <div class="footer-policy">
            <p class="footer-title">Chính sách hỗ trợ</p>
            <?php if (!empty($show_chinhsach)): ?>
              <ul class="footer-ul">
                <?php foreach ($show_chinhsach as $row): ?>
                  <li>
                    <a class="transition" href="<?= $row["slug$lang"] ?>" title="<?= $row["name$lang"] ?>"><?= $row["name$lang"] ?></a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

          </div>

          <div class="footer-policy">
            <p class="footer-title">PHƯƠNG THỨC THANH TOÁN</p>
            <div class="ibank-wrapper">
              <?php if (!empty($payment)): ?>
                <?php foreach ($payment as $row): ?>
                  <span class="ibank scale-img">
                    <?= $fn->getImageCustom(['file' =>  $row['file'], 'width' => 72, 'height' => 40, 'zc' => 2, 'alt' => $row["name$lang"], 'title' => $row["name$lang"], 'lazy' => true]) ?>
                  </span>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title"><?= $hotrokhachhang["name$lang"] ?? '' ?></p>
            <?= $hotrokhachhang["content$lang"] ?? '' ?>
          </div>
        </div>
        <div class="box-policy">
          <?php if ($hotro247) : ?>
            <div class="footer-policy">
              <p class="footer-title"> <?= $hotro247["name$lang"] ?? '' ?></p>
              <div class="footer-info">
                <?= $hotro247["content$lang"] ?? '' ?>
              </div>
            </div>
          <?php endif ?>
          <?php if ($dangkynhantin) : ?>
            <div class="footer-newsletter">
              <p class="footer-title"><?= $dangkynhantin["name$lang"] ?? '' ?></p>
              <p class="slogan-newsletter"><?= $dangkynhantin["content$lang"] ?? '' ?></p>
              <form class="FormNewsletter validation-newsletter" novalidate method="post" action=""
                enctype="multipart/form-data" data-aos="fade-left" data-aos-duration="500">
                <div class="newsletter-input">
                  <input type="email" class="form-control text-sm" id="email-newsletter" name="dataNewsletter[email]"
                    placeholder="Nhập vào email của bạn" required />
                </div>
                <div class="newsletter-button">
                  <button type="submit" class="btn-submit" name="submit-newsletter" value="Đăng ký">
                    <i class="fa-solid fa-paper-plane"></i>
                  </button>
                  <input type="hidden" name="newsletter" value="submit" />
                  <input type="hidden" class="btn btn-sm btn-danger w-100" name="recaptcha_response_newsletter"
                    id="recaptchaResponseNewsletter" />
                </div>
              </form>
            </div>
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-powered">
    <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
      <div class="footer-copyright">
        Copyright © <?= current_year(); ?>
        <span><?= $copyright ?></span>.
      </div>
      <?php $counter = $statistic->getOnline(); ?>
      <div class="footer-statistic d-none d-sm-block">
        <span><?= dangonline ?>: <?= $counter['online'] ?></span>
        <span>Ngày: <?= $counter['today'] ?></span>
        <span><?= trongtuan ?>: <?= $counter['week'] ?></span>
        <span><?= tongtruycap ?>: <?= $counter['total'] ?></span>
      </div>
    </div>
  </div>
  <div class="progress-wrap cursor-pointer">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
        style="transition: stroke-dashoffset 10ms linear; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 262.455;">
      </path>
    </svg>
  </div>
</div>

<?php
/*
<div class="floating-support" data-aos="fade-left" data-aos-anchor="#example-anchor" data-aos-offset="500"
  data-aos-duration="500">
  <?php if (!empty($show_social)): ?>
    <?php
    $sameAs = [];
    foreach ($show_social as $v): ?>
      <?php $sameAs[] = $v['link'] ?>
      <a href="<?= $v['link'] ?>" class="floating-support__item" target="_blank">
        <div class="floating-support__item__icon">
          <?= $fn->getImageCustom(['file' =>  $v['file'], 'class'  => 'tada', 'width'  => 50, 'height'  => 50, 'zc'  => 1, 'alt'   => $v["name$lang"], 'title' => $v["name$lang"], 'lazy' => true]) ?>
        </div>
        <div class="floating-support__item__content">
          <p><b><?= $v["name$lang"] ?></b></p>
          <span><?= $v["desc$lang"] ?></span>
        </div>
      </a>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
*/
?>
<div class="support-online">
  <?php if (!empty($show_social)): ?>
    <?php
    $sameAs = [];
    foreach ($show_social as $v): ?>
      <?php $sameAs[] = $v['link'] ?>
      <div class="btn_animation--style">
        <a
          title="<?= $v["name$lang"] ?>"
          href="<?= $v['link'] ?>"
          <?= ($v['link'] !== BASE.'gio-hang') ? 'target="_blank"' : '' ?>>
          <?= $fn->getImageCustom([
            'file'   => $v['file'],
            'width'  => 30,
            'height' => 30,
            'zc'     => 1,
            'alt'    => $v["name$lang"],
            'title'  => $v["name$lang"],
            'lazy'   => true
          ]) ?>
          <span><?= $v["name$lang"] ?></span>
        </a>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<!-- Overlay -->
<div class="search-overlay"></div>

<!-- Search Form -->
<form class="search-bar-container">
  <div class="searchbox">
    <input
      type="text"
      class="searchinput js-search-input"
      placeholder="Nhập từ khóa tìm kiếm"
      autocomplete="off" />
    <button type="button" class="search-submit js-search-btn">
      <i class="fa-solid fa-magnifying-glass"></i>
    </button>
  </div>
</form>

<!-- Modal cart -->
<div class="modal fade" id="popup-cart" tabindex="-1" aria-labelledby="popup-quickviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fs-5" id="popup-quickviewLabel">Giỏ hàng của bạn</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>
