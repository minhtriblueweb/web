<script type="text/javascript">
  var NN_FRAMEWORK = NN_FRAMEWORK || {};
  var CONFIG_BASE = "<?= BASE ?>";
  var ASSET = "<?= BASE ?>";
  var WEBSITE_NAME = "<?= (!empty($setting['name' . $lang])) ? addslashes($setting['name' . $lang]) : '' ?>";
  var TIMENOW = "<?= date('d/m/Y') ?>";
  var SHIP_CART = false;
  var RECAPTCHA_ACTIVE = true;
  var RECAPTCHA_SITEKEY = "6Lcy4r0ZAAAAAKCm-yZWmkiZK6GO49G--KW30rNS";
  var PATH_JSON = "";
  var logo_img = "<?= !empty($logo['file']) ? $logo['file'] : '' ?>";
  var IDLIST = "";
  var TOTALPAGE = "";
  var LANG = {
    'no_keywords': '<?= chuanhaptukhoatimkiem ?>',
    'delete_product_from_cart': '<?= banmuonxoasanphamnay ?>',
    'no_products_in_cart': '<?= khongtontaisanphamtronggiohang ?>',
    'ward': '<?= phuongxa ?>',
    'back_to_home': '<?= vetrangchu ?>',
    'thongbao': '<?= thongbao ?>',
    'dongy': '<?= dongy ?>',
    'dungluonghinhanhlon': '<?= dungluonghinhanhlon ?>',
    'dulieukhonghople': '<?= dulieukhonghople ?>',
    'banchiduocchon1hinhanhdeuplen': '<?= banchiduocchon1hinhanhdeuplen ?>',
    'dinhdanghinhanhkhonghople': '<?= dinhdanghinhanhkhonghople ?>',
    'huy': '<?= huy ?>'
  };
</script>
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
  $js->set($file);
}
echo $js->get();
?>
<?php if (!$fn->isGoogleSpeed()) { ?>
  <?php if (!empty($config['googleAPI']['recaptcha']['active'])) { ?>
    <!-- Js Google Recaptcha V3 -->
    <script src="https://www.google.com/recaptcha/api.js?render=<?= $config['googleAPI']['recaptcha']['sitekey'] ?>"></script>
    <script type="text/javascript">
      if (isExist($('#frm-contact'))) generateCaptcha('frm-contact', 'contact', 'recaptchaResponseContact');
    </script>
  <?php } ?>
  <!-- Js Body -->
  <?= $fn->decodeHtmlChars($optsetting['bodyjs']) ?>
<?php } ?>
<!-- Js Structdata -->
<?php include TEMPLATE . LAYOUT . "strucdata.php"; ?>
