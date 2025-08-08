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
  var ORDER_ADVANCED_SEARCH = false;
  var ORDER_MIN_TOTAL = 1;
  var ORDER_MAX_TOTAL = 1;
  var ORDER_PRICE_FROM = 1;
  var ORDER_PRICE_TO = 1;
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
<script src="ckeditor/ckeditor.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof CHARTS !== 'undefined') {
      document.getElementById('month-label').textContent = CHARTS.month;
      document.getElementById('year-label').textContent = CHARTS.year || new Date().getFullYear();
    }
  });
  $(document).ready(function() {
    const selectedMonth = parseInt(CHARTS?.month || (new Date().getMonth() + 1));
    const selectedYear = parseInt(CHARTS?.year || new Date().getFullYear());
    for (let i = 1; i <= 12; i++) {
      const selected = i === selectedMonth ? 'selected' : '';
      $('#month').append(`<option value="${i}" ${selected}>Tháng ${i}</option>`);
    }
    const currentYear = new Date().getFullYear();
    for (let y = 2000; y <= currentYear + 10; y++) {
      const selected = y === selectedYear ? 'selected' : '';
      $('#year').append(`<option value="${y}" ${selected}>Năm ${y}</option>`);
    }
    $('.select2').select2();
  });
</script>
<?php
if (isset($_SESSION['toast'])) {
  $toast = $_SESSION['toast'];
  unset($_SESSION['toast']);
?>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      new Notify({
        status: '<?= $toast['status'] ?>',
        title: '<?= $toast['title'] ?>',
        text: '<?= $toast['msg'] ?>',
        // effect: 'slide',
        ffect: 'fade',
        speed: 1000,
        autoclose: true,
        autotimeout: 5000,
        position: 'bottom right',
        gap: 20,
        distance: 20,
        type: 3
      });
    });
  </script>
<?php } ?>
