<div class="footer">
  <div class="footer-article">
    <div class="wrap-content d-flex flex-wrap justify-content-between">
      <div class="footer-banner">
        <div class="logo-footer">
          <a href="./">
            <?= $fn->getImage([
              'file' => $logo,
              'alt' => $web_name,
              'title' => $web_name,
              'lazy' => false
            ]) ?>
          </a>
        </div>
        <p class="footer-company"><?= $web_name ?></p>
        <div class="footer-info content-ck">
          <p><span><?= ($trangtinh->get_static('footer')['contentvi'] ?? '') ?></span></p>
        </div>
        <div class="social">
          <p>KẾT NỐI VỚI CHÚNG TÔI</p>
          <?php
          $show_social = $fn->show_data([
            'table'  => 'tbl_social',
            'status' => 'hienthi',
            'select' => "file, link, name{$lang}"
          ]);
          ?>
          <?php if (!empty($show_social)): ?>
            <?php foreach ($show_social as $row): ?>
              <?php
              $name = $row['name' . $lang];
              $link = $row['link'];
              ?>
              <a class="hvr-icon-rotate me-2" href="<?= $link ?>" target="_blank" rel="noopener noreferrer">
                <?= $fn->getImage([
                  'file'   => $row['file'],
                  'class'  => 'hvr-icon',
                  'width'  => 50,
                  'alt'    => $name,
                  'title'  => $name,
                  'lazy'   => true
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
                  <?php
                  $name = $row['name' . $lang];
                  $slug = $row['slug' . $lang];
                  ?>
                  <li>
                    <a class="transition" href="<?= $slug ?>" title="<?= $name ?>"><?= $name ?></a>
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
                  <?php $name = $row_pt['name' . $lang]; ?>
                  <span class="ibank scale-img">
                    <img src="<?= empty($row_pt['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row_pt['file'] ?>"
                      alt="<?= $name ?>" title="<?= $name ?>" />
                  </span>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title">HỖ TRỢ KHÁCH HÀNG</p>
            <?= ($trangtinh->get_static('hotrokhachhang')['contentvi'] ?? '') ?>
          </div>
        </div>
        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title">HỖ TRỢ 24/7</p>
            <div class="footer-info content-ck">
              <?= ($trangtinh->get_static('hotro247')['contentvi'] ?? '') ?>
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
  <?php include ROOT . '/templates/social.php'; ?>
  <!-- Js Config -->
  <script type="text/javascript">
    var NN_FRAMEWORK = NN_FRAMEWORK || {};
    var CONFIG_BASE = "<?= BASE ?>";
    var ASSET = "<?= BASE ?>";
    var WEBSITE_NAME = "<?= $seo['web_name'] ?>";
    var TIMENOW = "<?= date('d/m/Y') ?>";
    var SHIP_CART = false;
    var RECAPTCHA_ACTIVE = true;
    var RECAPTCHA_SITEKEY = "6Lcy4r0ZAAAAAKCm-yZWmkiZK6GO49G--KW30rNS";
    var PATH_JSON = "./assets/jsons/";
    var LANG = {
      no_keywords: "Chưa nhập từ khóa tìm kiếm",
      delete_product_from_cart: "Bạn muốn xóa sản phẩm này khỏi giỏ hàng ? ",
      no_products_in_cart: "Không tồn tại sản phẩm nào trong giỏ hàng !",
      ward: "Phường/xã",
      back_to_home: "Về trang chủ",
      thongbao: "Thông báo",
      dongy: "Đồng ý",
      dungluonghinhanhlon: "Dung lượng hình ảnh lớn. Dung lượng cho phép &lt;= 4MB ~ 4096KB",
      dulieukhonghople: "Dữ liệu không hợp lệ",
      banchiduocchon1hinhanhdeuplen: "Bạn chỉ được chọn 1 hình ảnh để upload",
      dinhdanghinhanhkhonghople: "Định dạng hình ảnh không hợp lệ",
      huy: "Hủy",
      chinhsach: "Chính Sách",
    };
    var logo_img = "<?= $logo ?>";
    var IDLIST = "";
    var TOTALPAGE = "";
  </script>

  <!-- Js Files -->
  <?php
  $jsFiles = [
    "js/jquery.min.js",
    "js/lazyload.min.js",
    "bootstrap/bootstrap.js",
    "js/wow.min.js",
    "holdon/HoldOn.js",
    "confirm/confirm.js",
    "simplenotify/simple-notify.js",
    "easyticker/easy-ticker.js",
    "fotorama/fotorama.js",
    "photobox/photobox.js",
    "fileuploader/jquery.fileuploader.min.js",
    "fancybox5/fancybox.umd.js",
    "slick/slick.min.js",
    "magiczoomplus/magiczoomplus.js",
    "js/functions.js",
    "menu-mobile/menu-mobile.js",
    "toc/toc.js",
    "aos/aos.js",
    "js/apps.js"
  ];
  foreach ($jsFiles as $file) {
    $js->set("{$file}?v=" . VERSION);
  }
  echo $js->get();

  ?>

  <!-- Js Structdata -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "<?= $seo['title'] ?? $web_name ?>",
      "url": "<?= BASE ?>",
      "sameAs": [<?= implode(", ", array_map(fn($link) => '"' . $link . '"', $sameAs)) ?>]
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?= $address ?>",
        "addressRegion": "Ho Chi Minh",
        "postalCode": "70000",
        "addressCountry": "vi"
      }
    }
  </script>

  <!-- Js Body -->
  <?= $bodyjs ?>
</div>
</body>

</html>
