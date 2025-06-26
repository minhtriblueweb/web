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
          <p><span><?= ($get = $trangtinh->get_static('footer')) && ($row = $get->fetch_assoc()) ? $row['contentvi'] : ''; ?></span></p>
        </div>
        <div class="social">
          <p>KẾT NỐI VỚI CHÚNG TÔI</p>
          <?php
          $show_social = $fn->show_data([
            'table' => 'tbl_social',
            'status' => 'hienthi'
          ]); ?>
          <?php if ($show_social): ?>
            <?php while ($row_social = $show_social->fetch_assoc()): ?>
              <a class="hvr-icon-rotate" href="<?= $row_social['link'] ?>" target="_blank" class="me-2">
                <img width="50" class="hvr-icon"
                  src="<?= empty($row_social['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row_social['file']; ?>"
                  alt="<?= $row_social['namevi'] ?>" title="<?= $row_social['namevi'] ?>" />
              </a>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="footer-right">
        <div class="box-policy pe-3">
          <div class="footer-policy">
            <p class="footer-title">Chính sách hỗ trợ</p>
            <ul class="footer-ul">
              <?php $show_chinhsach = $fn->show_data([
                'table' => 'tbl_news',
                'status' => 'hienthi',
                'type' => 'chinhsach'
              ]);
              ?>
              <?php if ($show_chinhsach): ?>
                <?php while ($row_cs = $show_chinhsach->fetch_assoc()): ?>
                  <li>
                    <a class="transition" href="<?= $row_cs['slugvi'] ?>"
                      title="<?= $row_cs['namevi'] ?>"><?= $row_cs['namevi'] ?></a>
                  </li>
                <?php endwhile; ?>
              <?php endif; ?>
            </ul>
          </div>
          <div class="footer-policy">
            <p class="footer-title">PHƯƠNG THỨC THANH TOÁN</p>
            <div class="ibank-wrapper">
              <?php $show_phuongthuctt = $fn->show_data([
                'table' => 'tbl_payment',
                'status' => 'hienthi',
              ]); ?>
              <?php if ($show_phuongthuctt): ?>
                <?php while ($row_pt = $show_phuongthuctt->fetch_assoc()): ?>
                  <span class="ibank scale-img">
                    <img
                      src="<?= empty($row_pt['file']) ? NO_IMG : BASE_ADMIN . UPLOADS . $row_pt['file']; ?>"
                      alt="<?= $row_pt['namevi'] ?>" title="<?= $row_pt['namevi'] ?>" />
                  </span>
                <?php endwhile; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title">HỖ TRỢ KHÁCH HÀNG</p>
            <?= ($get = $trangtinh->get_static('hotrokhachhang')) && ($row = $get->fetch_assoc()) ? $row['contentvi'] : '';
            ?>
          </div>
        </div>
        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title">HỖ TRỢ 24/7</p>
            <div class="footer-info content-ck">
              <?= ($get = $trangtinh->get_static('hotro247')) && ($row = $get->fetch_assoc()) ? $row['contentvi'] : '';
              ?>
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
      "sameAs": [
        <?= implode(", ", array_map(fn($link) => '"' . $link . '"', $sameAs)) ?>
      ]
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "",
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
