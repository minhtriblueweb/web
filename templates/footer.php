<div class="footer">
  <div class="footer-article">
    <div class="wrap-content d-flex flex-wrap justify-content-between">
      <div class="footer-banner">
        <div class="logo-footer">
          <a href="./">
            <?= $fn->getImageCustom([
              'file' => $logo,
              'alt' => $web_name,
              'title' => $web_name,
              'width' => 300,
              'height' => 120,
              'zc' => 1,
              'lazy' => true
            ]) ?>
          </a>
        </div>
        <p class="footer-company"><?= $web_name ?></p>
        <div class="footer-info content-ck">
          <p><span><?= ($trangtinh->get_static('footer')['content' . $lang] ?? '') ?></span></p>
        </div>
        <div class="social">
          <p>KẾT NỐI VỚI CHÚNG TÔI</p>
          <?php if (!empty($show_social)): ?>
            <?php foreach ($show_social as $row): ?>
              <a class="hvr-icon-rotate me-2" href="<?= $row['link'] ?>" target="_blank" rel="noopener noreferrer">
                <?= $fn->getImageCustom([
                  'width' => 40,
                  'height' => 40,
                  'zc' => 1,
                  'file'   => $row['file'],
                  'class'  => 'hvr-icon',
                  'alt' => $row['name' . $lang],
                  'title' => $row['name' . $lang],
                  'lazy' => true
                ]) ?>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="footer-right">
        <div class="box-policy pe-3">
          <div class="footer-policy">
            <p class="footer-title">Chính sách hỗ trợ</p>
            <?php
            $show_chinhsach = $fn->show_data([
              'table'  => 'tbl_news',
              'status' => 'hienthi',
              'type'   => 'chinhsach',
              'select' => "id, slug{$lang}, name{$lang}"
            ]);
            ?>
            <?php if (!empty($show_chinhsach)): ?>
              <ul class="footer-ul">
                <?php foreach ($show_chinhsach as $row): ?>
                  <li>
                    <a class="transition" href="<?= $row['slug' . $lang] ?>" title="<?= $row['name' . $lang] ?>"><?= $row['name' . $lang] ?></a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

          </div>

          <div class="footer-policy">
            <p class="footer-title">PHƯƠNG THỨC THANH TOÁN</p>
            <div class="ibank-wrapper">
              <?php
              $show_phuongthuctt = $fn->show_data([
                'table' => 'tbl_payment',
                'status' => 'hienthi'
              ]);
              ?>
              <?php if (!empty($show_phuongthuctt)): ?>
                <?php foreach ($show_phuongthuctt as $row_pt): ?>
                  <span class="ibank scale-img">
                    <?= $fn->getImageCustom([
                      'file' =>  $row_pt['file'],
                      'width' => 72,
                      'height' => 40,
                      'zc' => 2,
                      'alt' => $row_pt['name' . $lang],
                      'title' => $row_pt['name' . $lang],
                      'lazy' => true
                    ]) ?>
                  </span>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title">HỖ TRỢ KHÁCH HÀNG</p>
            <?= ($trangtinh->get_static('hotrokhachhang')['content' . $lang] ?? '') ?>
          </div>
        </div>
        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title">HỖ TRỢ 24/7</p>
            <div class="footer-info content-ck">
              <?= ($trangtinh->get_static('hotro247')['content' . $lang] ?? '') ?>
            </div>
          </div>
          <div class="footer-newsletter">
            <p class="footer-title">ĐĂNG KÝ NHẬN TIN</p>
            <p class="slogan-newsletter">
              Đăng ký với chúng tôi ngay để không bỏ lỡ những chương trình
              hấp dẫn
            </p>
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
      <?php include 'counter.php'; ?>
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
<div class="floating-support" data-aos="fade-left" data-aos-anchor="#example-anchor" data-aos-offset="500"
  data-aos-duration="500">
  <?php if (!empty($show_social)): ?>
    <?php
    $sameAs = [];
    foreach ($show_social as $row_social): ?>
      <?php $sameAs[] = $row_social['link'] ?>
      <a href="<?= $row_social['link'] ?>" class="floating-support__item" target="_blank">
        <div class="floating-support__item__icon">
          <?= $fn->getImageCustom([
            'file' =>  $row_social['file'],
            'class'  => 'tada',
            'width'  => 50,
            'height'  => 50,
            'zc'  => 1,
            'alt'   => $row_social['name' . $lang],
            'title' => $row_social['name' . $lang],
            'lazy' => true
          ]) ?>
        </div>
        <div class="floating-support__item__content">
          <p><b><?= $row_social['name' . $lang] ?></b></p>
          <span><?= $row_social['desc' . $lang] ?></span>
        </div>
      </a>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
