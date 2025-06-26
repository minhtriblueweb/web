<!DOCTYPE html>
<html lang="vi">

<head>
  <!-- Basehref -->
  <base href="<?= BASE ?>" />
  <!-- UTF-8 -->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- Title, Keywords, Description -->
  <title><?= $seo['title'] ?? $web_name ?></title>
  <meta name="keywords" content="<?= $seo['keywords'] ?>" />
  <meta name="description" content="<?= $seo['description'] ?>" />
  <!-- Robots -->
  <meta name="robots" content="index,follow,noodp" />
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="<?= $seo['favicon'] ?>" />
  <!-- GEO -->
  <meta name="geo.region" content="VN" />
  <meta name="geo.placename" content="Hồ Chí Minh" />
  <meta name="geo.position" content="<?= $seo['geo'] ?>" />
  <meta name="ICBM" content="<?= $seo['geo'] ?>" />
  <!-- Author - Copyright -->
  <meta name='revisit-after' content='1 days' />
  <meta name="author" content="<?= $web_name ?>" />
  <meta name="copyright" content="<?= $web_name ?> - [<?= $seo['email'] ?>]" />
  <link rel="canonical" href="<?= $seo['url'] ?>" />
  <!-- Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="<?= $web_name ?>" />
  <meta property="og:title" content="<?= $seo['title'] ?>" />
  <meta property="og:description" content="<?= $seo['description'] ?>" />
  <meta property="og:url" content="<?= $seo['url'] ?>" />
  <meta property="og:image" content="<?= $seo['image'] ?>" />
  <meta property="og:image:alt" content="<?= $seo['title'] ?>" />
  <meta property="og:image:type" content="image/png" />
  <meta property="og:image:width" content="682" />
  <meta property="og:image:height" content="810" />

  <!-- Canonical -->
  <link rel="canonical" href="<?= BASE ?>" />
  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="<?= $seo['email'] ?>" />
  <meta name="twitter:creator" content="<?= $web_name ?>" />
  <meta property="og:url" content="<?= $seo['url'] ?>" />
  <meta property="og:title" content="<?= $seo['title'] ?>" />
  <meta property="og:description" content="<?= $seo['description'] ?>" />
  <meta property="og:image" content="<?= $seo['image'] ?>" />
  <!-- Chống đổi màu trên IOS -->
  <meta name="format-detection" content="telephone=no" />

  <!-- Viewport -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <!-- Css Files -->
  <?php
  $css->set("css/animate.min.css");
  $css->set("bootstrap/bootstrap.css");
  $css->set("fontawesome611/css/all.min.css");
  // $css->set("holdon/HoldOn.css");
  // $css->set("holdon/HoldOn-style.css");
  $css->set("confirm/confirm.css");
  $css->set("fileuploader/font-fileuploader.css");
  $css->set("fileuploader/jquery.fileuploader.min.css");
  $css->set("fileuploader/jquery.fileuploader-theme-dragdrop.css");
  // $css->set("owlcarousel2/owl.carousel.css");
  // $css->set("owlcarousel2/owl.theme.default.css");
  $css->set("photobox/photobox.css");
  $css->set("fotorama/fotorama.css");
  $css->set("fotorama/fotorama-style.css");
  $css->set("simplenotify/simple-notify.css");
  $css->set("menu-mobile/menu-mobile.css");
  $css->set("fancybox5/fancybox.css");
  $css->set("slick/slick.css");
  $css->set("magiczoomplus/magiczoomplus.css");
  $css->set("aos/aos.css");
  $css->set("css/style.css");
  echo $css->get();
  ?>

  <!-- Js Google Analytic -->
  <?= $analytics ?>
  <!-- Js Head -->
  <?= $headjs ?>
</head>

<body>
  <div class="wrap-container">
    <ul class="h-card hidden">
      <li class="h-fn fn"><?= $web_name ?></li>
      <li class="h-org org"><?= $web_name ?></li>
      <li class="h-tel tel"><?= $hotline ?></li>
      <li><a class="u-url ul" href="<?= BASE ?>"><?= BASE ?></a></li>
    </ul>
    <h1 class="hidden-seoh"><?= $web_name ?></h1>
    <div class="header">
      <div class="header-slogan">
        <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
          <marquee><?= $introduction ?></marquee>
          <div class="box-social">
            <a class="pay" href="">Phương thức thanh toán</a>
            <div class="social-header">
              <p>Kết nối :</p>
              <?php
              $show_social = $fn->show_data([
                'table' => 'tbl_social',
                'status' => 'hienthi'
              ]); ?>
              <?php if ($show_social): ?>
                <?php while ($row_social = $show_social->fetch_assoc()): ?><a href="<?= $row_social['link'] ?>"
                    class="lazy hvr-icon-rotate" class="me-2">
                    <?= $fn->getImage([
                      'file' => $row_social['file'],
                      'width' => 20,
                      'height' => 20,
                      'class' => 'me-2',
                      'alt' => $row_social['namevi'],
                      'title' => $row_social['namevi'],
                      'lazy' => false
                    ]) ?>
                  </a>
                <?php endwhile; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="header-banner">
        <div class="wrap-content d-flex flex-wrap justify-content-between align-items-center">
          <div class="banner-header">
            <div class="logo">
              <a href="./">
                <?= $fn->getImage([
                  'file' => $logo,
                  'width' => 300,
                  'height' => 'auto',
                  'alt' => $web_name,
                  'title' => $web_name,
                  'lazy' => false
                ]) ?>
              </a>
            </div>
          </div>

          <div class="search">
            <input type="text" id="keyword" placeholder="Nhập từ khóa tìm kiếm...."
              onkeypress="doEnter(event,'keyword');" />
            <p onclick="onSearch('keyword');">
              <i class="fa-solid fa-magnifying-glass"></i>
            </p>
          </div>
          <div class="box-hotline">
            <div class="hotline">
              Hotline tư vấn:<p><?= $hotline ?></p>
            </div>
            <div class="hotline">
              Giờ làm việc:<p><?= $worktime ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
