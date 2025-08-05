<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$charts = [
  'month' => str_pad($month, 2, '0', STR_PAD_LEFT),
  'year' => $year,
  'series' => [],
  'labels' => []
];
for ($i = 1; $i <= $daysInMonth; $i++) {
  $begin = strtotime("$year-$month-$i 00:00:00");
  $end   = strtotime("$year-$month-$i 23:59:59") + 1;
  $row = $db->rawQueryOne("SELECT COUNT(*) as total FROM tbl_counter WHERE tm >= ? AND tm < ?", [$begin, $end]);
  $charts['series'][] = (int)$row['total'];
  $charts['labels'][] = 'D' . $i;
}
?>


<?php include TEMPLATE . LAYOUT . 'loader.php'; ?>
<section class="content mb-3">
  <div class="container-fluid">
    <h5 class="pt-3 pb-2">Bảng điều khiển</h5>
    <div class="row mb-2 text-sm">
      <div class="col-12 col-sm-6 col-md-3">
        <a class="my-info-box info-box" href="index.php?page=setting&act=update" title="<?= cauhinhwebsite ?>">
          <span class="my-info-box-icon info-box-icon bg-primary"><i class="fas fa-cogs"></i></span>
          <div class="info-box-content text-dark">
            <span class="info-box-text text-capitalize"><?= cauhinhwebsite ?></span>
            <span class="info-box-number"><?= xemthem ?></span>
          </div>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <a class="my-info-box info-box" href="index.php?page=user&act=info_admin" title="<?= taikhoan ?>">
          <span class="my-info-box-icon info-box-icon bg-danger"><i class="fas fa-user-cog"></i></span>
          <div class="info-box-content text-dark">
            <span class="info-box-text text-capitalize"><?= taikhoan ?></span>
            <span class="info-box-number"><?= xemthem ?></span>
          </div>
        </a>
      </div>
      <div class="clearfix hidden-md-up"></div>
      <div class="col-12 col-sm-6 col-md-3">
        <a class="my-info-box info-box" href="index.php?page=user&act=info_admin&changepass=1" title="<?= doimatkhau ?>">
          <span class="my-info-box-icon info-box-icon bg-success"><i class="fas fa-key"></i></span>
          <div class="info-box-content text-dark">
            <span class="info-box-text text-capitalize"><?= doimatkhau ?></span>
            <span class="info-box-number"><?= xemthem ?></span>
          </div>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <a class="my-info-box info-box" href="index.php?com=contact&act=man" title="<?= thulienhe ?>">
          <span class="my-info-box-icon info-box-icon bg-info"><i class="fas fa-address-book"></i></span>
          <div class="info-box-content text-dark">
            <span class="info-box-text text-capitalize"><?= thulienhe ?></span>
            <span class="info-box-number"><?= xemthem ?></span>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>
<section class="content pb-4">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0"><?= thongketruycapthang ?> <span id="month-label"></span>/<span id="year-label"></span></h5>
      </div>
      <div class="card-body">
        <form class="form-filter-charts row align-items-center mb-3" method="get">
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control select2" name="month" id="month">
                <option value="">Chọn tháng</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control select2" name="year" id="year">
                <option value="">Chọn năm</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group"><button type="submit" class="btn btn-success" fdprocessedid="5x379q"><?= thongke ?></button></div>
          </div>
        </form>
        <div id="apexMixedChart"></div>
      </div>
    </div>
  </div>
</section>
