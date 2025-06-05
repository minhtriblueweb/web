<div class="footer">
  <div class="footer-article">
    <div class="wrap-content d-flex flex-wrap justify-content-between">
      <div class="footer-banner">
        <div class="logo-footer">
          <a href="./">
            <img src="<?= isset($result_setting['logo']) ? BASE_ADMIN . UPLOADS . $result_setting['logo'] : ''; ?>"
              alt="logo" title="logo" style="width: 250px;" />
          </a>
        </div>
        <p class="footer-company">
          <?= isset($result_setting['web_name']) ? htmlspecialchars($result_setting['web_name']) : ''; ?>
        </p>
        <div class="footer-info content-ck">
          <p>
            <span
              style="font-size: 14px"><?= isset($result_setting['descvi']) ? htmlspecialchars($result_setting['descvi']) : ''; ?></span>
          </p>
        </div>
        <div class="social">
          <p>KẾT NỐI VỚI CHÚNG TÔI</p>
          <?php $show_social = $social->show_social("hienthi"); ?>
          <?php if ($show_social): ?>
            <?php while ($result_social = $show_social->fetch_assoc()): ?>
              <a class="hvr-icon-rotate" href="<?= $result_social['link'] ?>" target="_blank" class="me-2">
                <img width="50" class="hvr-icon" src="<?= BASE_ADMIN . UPLOADS . $result_social['file'] ?>"
                  alt="<?= $result_social['name'] ?>" title="<?= $result_social['name'] ?>" />
              </a>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="footer-right">
        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title">Chính sách hỗ trợ</p>
            <ul class="footer-ul">
              <?php $show_chinhsach = $news->show_news_by_type('chinhsach', 'hienthi', 'noibat'); ?>
              <?php if ($show_chinhsach): ?>
                <?php while ($resule_chinhsach = $show_chinhsach->fetch_assoc()): ?>
                  <li>
                    <a class="transition" href="chinh-sach/<?= $resule_chinhsach['slugvi'] ?>"
                      title="<?= $resule_chinhsach['namevi'] ?>"><?= $resule_chinhsach['namevi'] ?></a>
                  </li>
                <?php endwhile; ?>
              <?php endif; ?>
            </ul>
          </div>
          <div class="footer-policy">
            <p class="footer-title">PHƯƠNG THỨC THANH TOÁN</p>
            <div class="ibank-wrapper">
              <?php $show_phuongthuctt = $phuongthuctt->show_phuongthuctt("hienthi"); ?>
              <?php if ($show_phuongthuctt): ?>
                <?php while ($result_phuongthuctt = $show_phuongthuctt->fetch_assoc()): ?>
                  <span class="ibank scale-img">
                    <img src="<?= BASE_ADMIN . UPLOADS . $result_phuongthuctt['file'] ?>"
                      alt="<?= $result_phuongthuctt['name'] ?>" title="<?= $result_phuongthuctt['name'] ?>" />
                  </span>
                <?php endwhile; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title">HỖ TRỢ KHÁCH HÀNG</p>
            <?= isset($result_setting['client_support']) ? $result_setting['client_support'] : ''; ?>
          </div>
        </div>
        <div class="box-policy">
          <div class="footer-policy">
            <p class="footer-title">HỖ TRỢ 24/7</p>
            <div class="footer-info content-ck">
              <?= isset($result_setting['support']) ? $result_setting['support'] : ''; ?>
            </div>
          </div>
          <div class="footer-newsletter">
            <p class="footer-title">ĐĂNG KÝ NHẬN TIN</p>
            <p class="slogan-newsletter">
              Đăng ký với chúng tôi ngay để không bỏ lỡ những chương trình
              hấp dẫn
            </p>
            <form class="FormNewsletter validation-newsletter" novalidate method="post" action=""
              enctype="multipart/form-data">
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
        <?php
        function current_year()
        {
          return date('Y');
        }
        ?>
        Copyright © <?= current_year(); ?>
        <span><?= isset($result_setting['copyright']) ? $result_setting['copyright'] : ''; ?></span>.
      </div>
      <?php include 'counter.php'; ?>
    </div>
  </div>
  <div class="toolbar">
    <div class="list-phone">
      <a target="_blank"
        href="tel:<?= isset($result_setting['hotline']) ? htmlspecialchars($result_setting['hotline']) : ''; ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="256" height="256">
          <path
            d="M23.45 20.93a3 3 0 0 0-4.25 0l-1.45 1.45a26.14 26.14 0 0 1-4.51-3.62 26.62 26.62 0 0 1-3.62-4.51l1.45-1.45a3 3 0 0 0 0-4.25L8.24 5.73a2.93 2.93 0 0 0-2.11-.88h0A3 3 0 0 0 4 5.73L2.64 7.08A6.37 6.37 0 0 0 1 12.33c.32 3.75 2.78 8.26 6.57 12.06S15.92 30.64 19.67 31a7.87 7.87 0 0 0 .84 0 6.07 6.07 0 0 0 4.41-1.64L26.27 28a3 3 0 0 0 .88-2.13 2.93 2.93 0 0 0-.88-2.11zm1.41 5.66L23.5 27.94a4.57 4.57 0 0 1-3.66 1c-3.25-.28-7.39-2.58-10.81-6S3.31 15.41 3 12.16a4.53 4.53 0 0 1 1-3.66L5.41 7.14a1 1 0 0 1 .71-.29h0a1 1 0 0 1 .71.29L9.66 10a1 1 0 0 1 0 1.41l-2 2a1 1 0 0 0-.16 1.21 27.49 27.49 0 0 0 4.33 5.58 27.49 27.49 0 0 0 5.58 4.33 1 1 0 0 0 1.21-.16l2-2a1 1 0 0 1 1.41 0l2.83 2.83h0a1 1 0 0 1 .29.71A1 1 0 0 1 24.86 26.59zM26.6 5.39A14.92 14.92 0 0 0 16 1a1 1 0 1 0 0 2A13 13 0 0 1 29 16.11a1 1 0 0 0 1 1h0a1 1 0 0 0 1-1A14.91 14.91 0 0 0 26.6 5.39z"
            class="color000 svgShape"></path>
          <path
            d="M20.91,11.11a6,6,0,0,1,1.77,4.31,1,1,0,0,0,1,1h0a1,1,0,0,0,1-1,8,8,0,0,0-8-8.1,1,1,0,1,0,0,2A6,6,0,0,1,20.91,11.11Z"
            class="color000 svgShape"></path>
        </svg>
        <span><?= isset($result_setting['hotline']) ? htmlspecialchars($result_setting['hotline']) : ''; ?></span>
      </a>
    </div>
  </div>
  <div class="progress-wrap cursor-pointer">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
        style="transition: stroke-dashoffset 10ms linear; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 262.455;">
      </path>
    </svg>
  </div>
  <div class="social-contact toolbar-custom">
    <?php $show_social = $social->show_social("hienthi"); ?>
    <?php if ($show_social): ?>
      <?php while ($result_social = $show_social->fetch_assoc()): ?>
        <a href="<?= $result_social['link'] ?>" target="_blank" class="d-block hvr-icon-rotate"><img
            class="transition hvr-icon" src="<?= BASE_ADMIN . UPLOADS . $result_social['file'] ?>"
            alt="<?= $result_social['name'] ?>">
        </a>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
  <!-- Js Config -->
  <script type="text/javascript">
    var NN_FRAMEWORK = NN_FRAMEWORK || {};
    var CONFIG_BASE = "<?= BASE ?>";
    var ASSET = "<?= BASE ?>";
    var WEBSITE_NAME = "<?= $seo['web_name'] ?>";
    var TIMENOW = "08/09/2024";
    var SHIP_CART = false;
    var RECAPTCHA_ACTIVE = true;
    var RECAPTCHA_SITEKEY = "6Lcy4r0ZAAAAAKCm-yZWmkiZK6GO49G--KW30rNS";
    var GOTOP = ASSET + "assets/images/top.png";
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
    };
    var logo_img = "<?= isset($result_setting['logo']) ? BASE_ADMIN . UPLOADS . $result_setting['logo'] : ''; ?>";
    var IDLIST = "";
    var TOTALPAGE = "";
  </script>

  <!-- Js Files -->
  <script type="text/javascript" src="<?= BASE ?>assets/js/jquery.min.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/js/lazyload.min.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/bootstrap/bootstrap.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/js/wow.min.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/owlcarousel2/owl.carousel.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/holdon/HoldOn.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/confirm/confirm.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/simplenotify/simple-notify.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/easyticker/easy-ticker.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/fotorama/fotorama.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/photobox/photobox.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/fileuploader/jquery.fileuploader.min.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/fancybox5/fancybox.umd.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/slick/slick.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/magiczoomplus/magiczoomplus.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/js/functions.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/menu-mobile/menu-mobile.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/toc/toc.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/aos/aos.js"></script>
  <script type="text/javascript" src="<?= BASE ?>assets/js/apps.js"></script>
  <!-- Js Structdata -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "<?= $seo['web_name'] ?>",
      "url": "<?= BASE ?>",
      "sameAs": [
        <?php
        $sameAs = [];
        $show_social = $social->show_social("hienthi");
        while ($result_social = $show_social->fetch_assoc()):
          $sameAs[] = '"' . $result_social['link'] . '"';
        endwhile;
        echo implode(", ", $sameAs);
        ?>
      ],
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "446/6 Đường Vĩnh Viễn, Phường 8, Quận 10, Thành phố Hồ Chí Minh",
        "addressRegion": "Ho Chi Minh",
        "postalCode": "70000",
        "addressCountry": "vi"
      }
    }
  </script>

  <!-- Js Body -->
  <?= isset($result_setting['bodyjs']) ? $result_setting['bodyjs'] : ''; ?>
</div>
</body>

</html>