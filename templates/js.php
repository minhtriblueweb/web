<script type="text/javascript">
  var NN_FRAMEWORK = NN_FRAMEWORK || {};
  var CONFIG_BASE = "<?= BASE ?>";
  var ASSET = "<?= BASE ?>";
  var WEBSITE_NAME = "<?= $data_seo['web_name'] ?>";
  var TIMENOW = "<?= date('d/m/Y') ?>";
  var SHIP_CART = false;
  var RECAPTCHA_ACTIVE = true;
  var RECAPTCHA_SITEKEY = "6Lcy4r0ZAAAAAKCm-yZWmkiZK6GO49G--KW30rNS";
  var PATH_JSON = "./assets/jsons/";
  var logo_img = "<?= $logo ?>";
  var IDLIST = "";
  var TOTALPAGE = "";
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
  <?= $fn->decodeHtmlChars($setting['bodyjs']) ?>
<?php } ?>
<!-- Js Structdata -->
<?php include TEMPLATE . "strucdata.php"; ?>
