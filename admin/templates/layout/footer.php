</div>
<footer class="main-footer text-center text-sm">
  <p class="mb-1">Minh Trí Web</p>
  <p class="mb-1">
    Địa chỉ: Lầu 2, Số 27 Trường Chinh, Phường Tân Thới Nhất, Quận 12, Thành phố Hồ Chí Minh, Việt Nam
  </p>
  <p class="mb-1">Tel: 0328 732 834</p>
  <p class="mb-0">Email: minhtri.blueweb@gmail.com</p>
</footer>
</div>
<!-- Js all -->
<!-- Js Config -->
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
    taithembinhluan: "Tải thêm bình luận",
    xemthembinhluan: "Xem thêm bình luận",
    traloi: "Trả lời",
    dangtai: "Đang tải",
    huybo: "Hủy bỏ",
    duyet: "Duyệt",
    boduyet: "Bỏ duyệt",
    timkiem: "Tìm kiếm",
    thongbao: "Thông báo",
    chondanhmuc: "Chọn danh mục",
    dulieukhonghople: "Dữ liệu không hợp lệ",
    dinhdanghinhanhkhonghople: "Định dạng hình ảnh không hợp lệ",
    dungluonghinhanhlondungluongchopheplt4mb4096kb: "Dung lượng hình ảnh lớn. Dung lượng cho phép &lt;= 4MB ~ 4096KB",
    xoabinhluanthanhcong: "Xóa bình luận thành công",
    banmuonxoabinhluannay: "Bạn muốn xóa bình luận này ?",
    capnhattrangthaithanhcong: "Cập nhật trạng thái thành công",
    phanhoithanhcong: "Phản hồi thành công",
    hethongbiloivuilongthulaisau: "Hệ thống bị lỗi. Vui lòng thử lại sau.",
    duongdankhonghople: "Đường dẫn không hợp lệ",
    banhaychonitnhat1mucdegui: "Bạn hãy chọn ít nhất 1 mục để gửi",
    albumhientai: "Album hiện tại",
    chontatca: "Chọn tất cả",
    sapxep: "Sắp xếp",
    dongy: "Đồng ý",
    xoatatca: "Xóa tất cả",
    cothechonnhieuhinhdedichuyen: "Có thể chọn nhiều hình để di chuyển",
    xulythatbaivuilongthulaisau: "Xử lý thất bại. Vui lòng thử lại sau.",
    banmuondaytinnay: "Bạn muốn đẩy tin này ?",
    banmuonguithongtinchocacmucdachon: "Bạn muốn gửi thông tin cho các mục đã chọn ?",
    bancochacmuonxoamucnay: "Bạn có chắc muốn xóa mục này ?",
    bancochacmuonxoanhungmucnay: "Bạn có chắc muốn xóa những mục này ?",
    dulieuhinhanhkhonghople: "Dữ liệu hình ảnh không hợp lệ",
    noidungseodaduocthietlapbanmuontaolainoidungseo: "Nội dung SEO đã được thiết lập. Bạn muốn tạo lại nội dung SEO ?",
    bancochacmuonxoahinhanhnay: "Bạn có chắc muốn xóa hình ảnh này ?",
    bancochacmuonxoacachinhanhdachon: "Bạn có chắc muốn xóa các hình ảnh đã chọn ?",
    keovathahinhvaoday: "Kéo và thả hình vào đây",
    hoac: "hoặc",
    hinhanh: "Hình ảnh",
    chonhinh: "Chọn hình",
    chiduocuploadmoilan: "Chỉ được upload mỗi lần",
    themhinh: "Thêm hình",
    vuilongchonhinhanh: "Vui lòng chọn hình ảnh",
    nhunghinhdaduocchon: "Những hình đã được chọn",
    keohinhvaodaydeupload: "Kéo hình vào đây để upload",
    banmuonloaibohinhanhnay: "Bạn muốn loại bỏ hình ảnh này ?",
    uploadhinhanh: "Upload hình ảnh",
    sothutu: "Số thứ tự",
    chihotrotaptinlahinhanhcodinhdang: "Chỉ hỗ trợ tập tin là hình ảnh có định dạng",
    cokichthuocqualonvuilonguploadhinhanhcokichthuoctoida: "có kích thước quá lớn. Vui lòng upload hình ảnh có kích thước tối đa",
    nhunghinhanhbanchoncokichthuocqualonvuilongchonnhunghinhanhcokichthuoctoida: "Những hình ảnh bạn chọn có kích thước quá lớn. Vui lòng chọn những hình ảnh có kích thước tối đa",
  };
</script>
<!-- Js Files -->
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
</body>

</html>
