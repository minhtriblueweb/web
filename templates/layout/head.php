<!-- Basehref -->
<base href="<?= BASE ?>" />

<!-- UTF-8 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Title, Keywords, Description -->
<title><?= $seo->get('title') ?: $optsetting["name$lang"] ?></title>
<meta name="keywords" content="<?= $seo->get('keywords') ?>" />
<meta name="description" content="<?= $seo->get('description') ?>" />

<!-- Robots -->
<meta name="robots" content="index,follow,noodp" />

<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="<?= $seo->get('favicon') ?>" />

<!-- GEO -->
<meta name="geo.region" content="VN" />
<meta name="geo.placename" content="Hồ Chí Minh" />
<meta name="geo.position" content="<?= $optsetting_json['coords'] ?>" />
<meta name="ICBM" content="<?= $optsetting_json['coords'] ?>" />

<!-- Author - Copyright -->
<meta name="revisit-after" content="1 days" />
<meta name="author" content="<?= $optsetting["name$lang"] ?>" />
<meta name="copyright" content="<?= $optsetting["name$lang"] . " - [" . $optsetting_json['email'] . "]" ?>" />

<!-- Facebook Open Graph -->
<meta property="og:type" content="website" />
<meta property="og:site_name" content="<?= $optsetting["name$lang"] ?>" />
<meta property="og:title" content="<?= $seo->get('title') ?>" />
<meta property="og:description" content="<?= $seo->get('description') ?>" />
<meta property="og:url" content="<?= $seo->get('url') ?>" />
<meta property="og:image" content="<?= $seo->get('photo') ?>" />
<meta property="og:image:alt" content="<?= $seo->get('title') ?>" />
<meta property="og:image:type" content="<?= $seo->get('photo:type') ?: 'image/png' ?>" />
<meta property="og:image:width" content="<?= $seo->get('photo:width') ?: '1200' ?>" />
<meta property="og:image:height" content="<?= $seo->get('photo:height') ?: '630' ?>" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="<?= $optsetting_json['email'] ?>" />
<meta name="twitter:creator" content="<?= $optsetting["name$lang"] ?>" />
<meta name="twitter:title" content="<?= $seo->get('title') ?>" />
<meta name="twitter:description" content="<?= $seo->get('description') ?>" />
<meta name="twitter:image" content="<?= $seo->get('photo') ?>" />

<!-- Canonical -->
<link rel="canonical" href="<?= $fn->getCurrentPageURL() ?>" />

<!-- Chống đổi màu trên iOS -->
<meta name="format-detection" content="telephone=no" />

<!-- Viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />

<!-- AddtoAny Share -->
<script async src="https://static.addtoany.com/menu/page.js"></script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "<?= $optsetting["name$lang"] ?>",
  "url": "<?= $seo->get('url') ?>",
  "description": "<?= $seo->get('description') ?>",
  "image": {
    "@type": "ImageObject",
    "url": "<?= $seo->get('photo') ?>",
    "width": <?= $seo->get('photo:width') ?: 1200 ?>,
    "height": <?= $seo->get('photo:height') ?: 630 ?>
  }
}
</script>

