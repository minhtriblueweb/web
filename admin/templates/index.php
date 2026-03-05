<?php
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year  = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
$currentMonth = (int)date('m');
$currentYear  = (int)date('Y');
if ($year > $currentYear) {
  $func->Notify("Năm không hợp lệ",  "index.php", "error");
  exit;
}
if ($year == $currentYear && $month > $currentMonth) {
  $func->Notify("Tháng không hợp lệ",  "index.php", "error");
  exit;
}
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
/* Make data for js chart */
$charts = array();
$charts['month'] = $month;
for ($i = 1; $i <= $daysInMonth; $i++) {
  $k = $i + 1;
  $begin = strtotime($year . '-' . $month . '-' . $i);
  $end = strtotime($year . '-' . $month . '-' . $k);
  $todayrc = $d->rawQueryOne("select count(*) as todayrecord from #_counter where tm >= ? and tm < ?", array($begin, $end));
  $today_visitors = $todayrc['todayrecord'];
  $charts['series'][] = $today_visitors;
  $charts['labels'][] = 'D' . $i;
}
$counter = $statistic->getOnline();
?>
<section class="content mb-3">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Bảng điều khiển</h5>
      </div>
      <div class="card-body">
        <div class="row mb-2 text-sm">
          <div class="col-12 col-sm-6 col-md-3">
            <a class="my-info-box info-box" href="index.php?com=setting&act=update" title="<?= cauhinhwebsite ?>">
              <span class="my-info-box-icon info-box-icon bg-primary"><i class="fas fa-cogs"></i></span>
              <div class="info-box-content text-dark">
                <span class="info-box-text text-capitalize"><?= cauhinhwebsite ?></span>
                <span class="info-box-number"><?= xemthem ?></span>
              </div>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <a class="my-info-box info-box" href="index.php?com=user&act=info_admin" title="<?= taikhoan ?>">
              <span class="my-info-box-icon info-box-icon bg-danger"><i class="fas fa-user-cog"></i></span>
              <div class="info-box-content text-dark">
                <span class="info-box-text text-capitalize"><?= taikhoan ?></span>
                <span class="info-box-number"><?= xemthem ?></span>
              </div>
            </a>
          </div>
          <div class="clearfix hidden-md-up"></div>
          <div class="col-12 col-sm-6 col-md-3">
            <a class="my-info-box info-box" href="index.php?com=user&act=info_admin&changepass=1" title="<?= doimatkhau ?>">
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
    </div>
  </div>
</section>
<section class="content mb-3">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Thống kê truy cập</h5>
      </div>
      <div class="card-body">
        <div class="row gy-3 chart-online">
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center">
              <div class="badge rounded-pill bg-label-primary me-3 p-2">
                <i class="fa-solid fa-chart-line"></i>
              </div>
              <div class="card-info">
                <h5 class="mb-0"><?= $counter['now'] ?></h5>
                <small>Đang online</small>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center">
              <div class="badge rounded-pill bg-label-info me-3 p-2">
                <i class="fa-solid fa-chart-line"></i>
              </div>
              <div class="card-info">
                <h5 class="mb-0"><?= $counter['day'] ?></h5>
                <small>Trong ngày</small>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center">
              <div class="badge rounded-pill bg-label-danger me-3 p-2">
                <i class="fa-solid fa-chart-line"></i>
              </div>
              <div class="card-info">
                <h5 class="mb-0"><?= $counter['week'] ?></h5>
                <small>Trong tuần</small>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="d-flex align-items-center">
              <div class="badge rounded-pill bg-label-success me-3 p-2">
                <i class="fa-solid fa-chart-line"></i>
              </div>
              <div class="card-info">
                <h5 class="mb-0"><?= $counter['month'] ?></h5>
                <small>Trong tháng</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content pb-4">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0"><?= thongketruycapthang ?> <?= $month ?>/<?= $year ?></h5>
      </div>
      <div class="card-body">
        <form class="form-filter-charts row align-items-center mb-1" action="index.php" method="get" name="form-thongke" accept-charset="utf-8">
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control select2" name="month" id="month">
                <option value="">Chọn tháng</option>
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                  <?php
                  if (isset($_GET['year'])) $selected = ($i == $_GET['month']) ? 'selected' : '';
                  else $selected = ($i == date('m')) ? 'selected' : '';
                  ?>
                  <option value="<?= $i ?>" <?= $selected ?>><?= thang ?> <?= $i ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control select2" name="year" id="year">
                <option value="">Chọn năm</option>
                <?php for ($i = 2000; $i <= date('Y') + 20; $i++) { ?>
                  <?php
                  if (isset($_GET['year'])) $selected = ($i == $_GET['year']) ? 'selected' : '';
                  else $selected = ($i == date('Y')) ? 'selected' : '';
                  ?>
                  <option value="<?= $i ?>" <?= $selected ?>><?= year ?> <?= $i ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group"><button type="submit" class="btn btn-success"><?= thongke ?></button></div>
          </div>
        </form>
        <div id="apexMixedChart"></div>
      </div>
    </div>
  </div>
</section>
