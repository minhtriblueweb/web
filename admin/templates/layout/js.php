<script type="text/javascript">
  var PHP_VERSION = 80309;
  var CONFIG_BASE = "<?= BASE ?>";
  var CONFIG_BASE_RTRIM = " <?= rtrim(BASE, '/') ?>";
  var LANGS = "<?= implode(',', array_keys($config['website']['lang'])) ?>";
  var TOKEN = "4cfd9a6ec93f31ec5006af2f97389efa";
  var ADMIN = "admin";
  var ASSET = "<?= BASE ?>";
  var LINK_FILTER = "<?= (!empty($linkMan)) ? $linkMan : '' ?>";
  var ID = <?= (!empty($id)) ? $id : 0 ?>;
  var TYPE = '<?= (!empty($type)) ? $type : '' ?>';
  var HASH = "<?= VERSION ?>";
  var ACTIVE_GALLERY = false;
  var BASE64_QUERY_STRING = "";
  var LOGIN_PAGE = false;
  var MAX_DATE = '<?= date("Y/m/d", time()) ?>';
  var CHARTS = <?= (!empty($charts)) ? json_encode($charts) : '{}' ?>;
  var ADD_OR_EDIT_PERMISSIONS = false;
  var IMPORT_IMAGE_EXCELL = false;
  var ORDER_ADVANCED_SEARCH = <?= (!empty($page) && $page == 'order' && !empty($config['order']['search'])) ? 'true' : 'false' ?>;
  var ORDER_MIN_TOTAL = <?= (!empty($minTotal)) ? $minTotal : 1 ?>;
  var ORDER_MAX_TOTAL = <?= (!empty($maxTotal)) ? $maxTotal : 1 ?>;
  var ORDER_PRICE_FROM = <?= (!empty($price_from)) ? $price_from : 1 ?>;
  var ORDER_PRICE_TO = <?= (!empty($price_to)) ? $price_to : ((!empty($maxTotal)) ? $maxTotal : 1) ?>;
  var LANG = {
    'taithembinhluan': '<?= taithembinhluan ?>',
    'xemthembinhluan': '<?= xemthembinhluan ?>',
    'traloi': '<?= traloi ?>',
    'dangtai': '<?= dangtai ?>',
    'huybo': '<?= huybo ?>',
    'duyet': '<?= duyet ?>',
    'boduyet': '<?= boduyet ?>',
    'timkiem': '<?= timkiem ?>',
    'thongbao': '<?= thongbao ?>',
    'chondanhmuc': '<?= chondanhmuc ?>',
    'dulieukhonghople': '<?= dulieukhonghople ?>',
    'dinhdanghinhanhkhonghople': '<?= dinhdanghinhanhkhonghople ?>',
    'dungluonghinhanhlondungluongchopheplt4mb4096kb': '<?= dungluonghinhanhlondungluongchopheplt4mb4096kb ?>',
    'xoabinhluanthanhcong': '<?= xoabinhluanthanhcong ?>',
    'banmuonxoabinhluannay': '<?= banmuonxoabinhluannay ?>',
    'capnhattrangthaithanhcong': '<?= capnhattrangthaithanhcong ?>',
    'phanhoithanhcong': '<?= phanhoithanhcong ?>',
    'hethongbiloivuilongthulaisau': '<?= hethongbiloivuilongthulaisau ?>',
    'duongdankhonghople': '<?= duongdankhonghople ?>',
    'duongdanhople': '<?= duongdanhople ?>',
    'banhaychonitnhat1mucdegui': '<?= banhaychonitnhat1mucdegui ?>',
    'banhaychonitnhat1mucdexoa': '<?= banhaychonitnhat1mucdexoa ?>',
    'albumhientai': '<?= albumhientai ?>',
    'chontatca': '<?= chontatca ?>',
    'sapxep': '<?= sapxep ?>',
    'dongy': '<?= dongy ?>',
    'xoatatca': '<?= xoatatca ?>',
    'cothechonnhieuhinhdedichuyen': '<?= cothechonnhieuhinhdedichuyen ?>',
    'xulythatbaivuilongthulaisau': '<?= xulythatbaivuilongthulaisau ?>',
    'banmuondaytinnay': '<?= banmuondaytinnay ?>',
    'banmuonguithongtinchocacmucdachon': '<?= banmuonguithongtinchocacmucdachon ?>',
    'bancochacmuonxoamucnay': '<?= bancochacmuonxoamucnay ?>',
    'bancochacmuonxoanhungmucnay': '<?= bancochacmuonxoanhungmucnay ?>',
    'dulieuhinhanhkhonghople': '<?= dulieuhinhanhkhonghople ?>',
    'noidungseodaduocthietlapbanmuontaolainoidungseo': '<?= noidungseodaduocthietlapbanmuontaolainoidungseo ?>',
    'bancochacmuonxoahinhanhnay': '<?= bancochacmuonxoahinhanhnay ?>',
    'bancochacmuonxoacachinhanhdachon': '<?= bancochacmuonxoacachinhanhdachon ?>',
    'keovathahinhvaoday': '<?= keovathahinhvaoday ?>',
    'hoac': '<?= hoac ?>',
    'hinhanh': '<?= hinhanh ?>',
    'tieude': '<?= tieude ?>',
    'chonhinh': '<?= chonhinh ?>',
    'chon': '<?= chon ?>',
    'chiduocuploadmoilan': '<?= chiduocuploadmoilan ?>',
    'themhinh': '<?= themhinh ?>',
    'vuilongchonhinhanh': '<?= vuilongchonhinhanh ?>',
    'nhunghinhdaduocchon': '<?= nhunghinhdaduocchon ?>',
    'keohinhvaodaydeupload': '<?= keohinhvaodaydeupload ?>',
    'banmuonloaibohinhanhnay': '<?= banmuonloaibohinhanhnay ?>',
    'uploadhinhanh': '<?= uploadhinhanh ?>',
    'sothutu': '<?= sothutu ?>',
    'chihotrotaptinlahinhanhcodinhdang': '<?= chihotrotaptinlahinhanhcodinhdang ?>',
    'cokichthuocqualonvuilonguploadhinhanhcokichthuoctoida': '<?= cokichthuocqualonvuilonguploadhinhanhcokichthuoctoida ?>',
    'nhunghinhanhbanchoncokichthuocqualonvuilongchonnhunghinhanhcokichthuoctoida': '<?= nhunghinhanhbanchoncokichthuocqualonvuilongchonnhunghinhanhcokichthuoctoida ?>',
    'thongketruycapthang': '<?= thongketruycapthang ?>',
    'luot': '<?= luot ?>',
  };
</script>
<?php
$jsFiles = [
  "js/jquery.min.js",
  "js/moment.min.js",
  "confirm/confirm.js",
  "select2/select2.full.js",
  "sumoselect/jquery.sumoselect.js",
  "datetimepicker/php-date-formatter.min.js",
  "datetimepicker/jquery.mousewheel.js",
  "datetimepicker/jquery.datetimepicker.js",
  "daterangepicker/daterangepicker.js",
  "rangeSlider/ion.rangeSlider.js",
  "js/jquery.priceformat.min.js",
  "jscolor/jscolor.js",
  "filer/jquery.filer.js",
  "holdon/HoldOn.js",
  "sortable/Sortable.js",
  "js/bootstrap.js",
  "js/adminlte.js",
  "apexcharts/apexcharts.min.js",
  "simplenotify/simple-notify.js",
  "comment/comment.js",
  "fancybox5/fancybox.umd.js",
  "js/apps.js",
];
foreach ($jsFiles as $file) {
  echo '<script src="./assets/' . $file . '?v=' . VERSION . '"></script>' . PHP_EOL;
}
?>
<script src="<?= $page === 'setting' ? 'monaco/min/vs/loader.js' : 'ckeditor/ckeditor.js' ?>"></script>
<?php if (!empty($_SESSION['notify']) && is_array($_SESSION['notify'])): ?>
  <script>
    $(function() {
      <?php foreach ($_SESSION['notify'] as $notify): ?>
        new Notify({
          status: '<?= $notify['status'] ?>', // success, error, warning, info
          title: '<?= $notify['title'] ?>',
          text: '<?= $notify['msg'] ?>',
          effect: 'slide',
          speed: 1000,
          autoclose: true,
          autotimeout: 5000,
          position: 'top right',
          gap: 60,
          distance: 20,
          type: 1
        });
      <?php endforeach; ?>
    });
  </script>
  <?php unset($_SESSION['notify']); ?>
<?php endif; ?>
