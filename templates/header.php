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
  <link href="<?= BASE ?>assets/css/animate.min.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/bootstrap/bootstrap.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/holdon/HoldOn.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/holdon/HoldOn-style.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/confirm/confirm.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/fileuploader/font-fileuploader.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/fileuploader/jquery.fileuploader.min.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/fileuploader/jquery.fileuploader-theme-dragdrop.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/owlcarousel2/owl.carousel.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/owlcarousel2/owl.theme.default.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/photobox/photobox.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/fotorama/fotorama.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/fotorama/fotorama-style.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/simplenotify/simple-notify.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/menu-mobile/menu-mobile.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/fancybox5/fancybox.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/slick/slick.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/magiczoomplus/magiczoomplus.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/aos/aos.css" rel="stylesheet" />
  <link href="<?= BASE ?>assets/css/style.css" rel="stylesheet" />

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
      <li><a class="u-url ul" href="<?= $config['base'] ?>"><?= $config['base'] ?></a></li>
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
              $show_social = $functions->show_data([
                'table' => 'tbl_social',
                'status' => 'hienthi'
              ]); ?>
              <?php if ($show_social): ?>
                <?php while ($row_social = $show_social->fetch_assoc()): ?><a href="<?= $row_social['link'] ?>"
                    class="lazy hvr-icon-rotate" class="me-2">
                    <img width="20" height="20"
                      src="<?= empty($row_social['file']) ? BASE_ADMIN . "assets/img/noimage.png" : BASE_ADMIN . UPLOADS . $row_social['file']; ?>"
                      alt="<?= $row_social['name'] ?>" />
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
                <img width="300" height="auto" src="<?= $logo ?>" alt="<?= $web_name ?>" title="<?= $web_name ?>" />
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
              Hotline tư vấn:
              <p>
                <?= $hotline ?></p>
            </div>
            <div class="hotline">
              Giờ làm việc:
              <p><?= $worktime ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
