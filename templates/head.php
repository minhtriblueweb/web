<!-- Basehref -->
<base href="<?= BASE ?>" />
<!-- UTF-8 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Title, Keywords, Description -->
<title><?= $data_seo['title'] ?? $web_name ?></title>
<meta name="keywords" content="<?= $data_seo['keywords'] ?>" />
<meta name="description" content="<?= $data_seo['description'] ?>" />
<!-- Robots -->
<meta name="robots" content="index,follow,noodp" />
<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="<?= $data_seo['favicon'] ?>" />
<!-- GEO -->
<meta name="geo.region" content="VN" />
<meta name="geo.placename" content="Hồ Chí Minh" />
<meta name="geo.position" content="<?= $data_seo['geo'] ?>" />
<meta name="ICBM" content="<?= $data_seo['geo'] ?>" />
<!-- Author - Copyright -->
<meta name='revisit-after' content='1 days' />
<meta name="author" content="<?= $web_name ?>" />
<meta name="copyright" content="<?= $web_name ?> - [<?= $data_seo['email'] ?>]" />
<link rel="canonical" href="<?= $data_seo['url'] ?>" />
<!-- Facebook -->
<meta property="og:type" content="website" />
<meta property="og:site_name" content="<?= $web_name ?>" />
<meta property="og:title" content="<?= $data_seo['title'] ?>" />
<meta property="og:description" content="<?= $data_seo['description'] ?>" />
<meta property="og:url" content="<?= $data_seo['url'] ?>" />
<meta property="og:image" content="<?= $data_seo['image'] ?>" />
<meta property="og:image:alt" content="<?= $data_seo['title'] ?>" />
<meta property="og:image:type" content="image/png" />
<meta property="og:image:width" content="682" />
<meta property="og:image:height" content="810" />

<!-- Canonical -->
<link rel="canonical" href="<?= BASE ?>" />
<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="<?= $data_seo['email'] ?>" />
<meta name="twitter:creator" content="<?= $web_name ?>" />
<meta property="og:url" content="<?= $data_seo['url'] ?>" />
<meta property="og:title" content="<?= $data_seo['title'] ?>" />
<meta property="og:description" content="<?= $data_seo['description'] ?>" />
<meta property="og:image" content="<?= $data_seo['image'] ?>" />
<!-- Chống đổi màu trên IOS -->
<meta name="format-detection" content="telephone=no" />

<!-- Viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
