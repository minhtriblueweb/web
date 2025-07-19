<!-- Basehref -->
<base href="<?= BASE ?>" />
<!-- UTF-8 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Title, Keywords, Description -->
<title><?= $seo_data['title'] ?? $web_name ?></title>
<meta name="keywords" content="<?= $seo_data['keywords'] ?>" />
<meta name="description" content="<?= $seo_data['description'] ?>" />
<!-- Robots -->
<meta name="robots" content="index,follow,noodp" />
<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="<?= $seo_data['favicon'] ?>" />
<!-- GEO -->
<meta name="geo.region" content="VN" />
<meta name="geo.placename" content="Hồ Chí Minh" />
<meta name="geo.position" content="<?= $seo_data['geo'] ?>" />
<meta name="ICBM" content="<?= $seo_data['geo'] ?>" />
<!-- Author - Copyright -->
<meta name='revisit-after' content='1 days' />
<meta name="author" content="<?= $web_name ?>" />
<meta name="copyright" content="<?= $web_name ?> - [<?= $seo_data['email'] ?>]" />
<link rel="canonical" href="<?= $seo_data['url'] ?>" />
<!-- Facebook -->
<meta property="og:type" content="website" />
<meta property="og:site_name" content="<?= $web_name ?>" />
<meta property="og:title" content="<?= $seo_data['title'] ?>" />
<meta property="og:description" content="<?= $seo_data['description'] ?>" />
<meta property="og:url" content="<?= $seo_data['url'] ?>" />
<meta property="og:image" content="<?= $seo_data['image'] ?>" />
<meta property="og:image:alt" content="<?= $seo_data['title'] ?>" />
<meta property="og:image:type" content="image/png" />
<meta property="og:image:width" content="682" />
<meta property="og:image:height" content="810" />

<!-- Canonical -->
<link rel="canonical" href="<?= BASE ?>" />
<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="<?= $seo_data['email'] ?>" />
<meta name="twitter:creator" content="<?= $web_name ?>" />
<meta property="og:url" content="<?= $seo_data['url'] ?>" />
<meta property="og:title" content="<?= $seo_data['title'] ?>" />
<meta property="og:description" content="<?= $seo_data['description'] ?>" />
<meta property="og:image" content="<?= $seo_data['image'] ?>" />
<!-- Chống đổi màu trên IOS -->
<meta name="format-detection" content="telephone=no" />

<!-- Viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
