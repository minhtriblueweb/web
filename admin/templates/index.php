<?php
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year  = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
$currentMonth = (int)date('m');
$currentYear  = (int)date('Y');
if ($year > $currentYear) {
  $fn->Notify("Năm không hợp lệ",  "index.php", "error");
  exit;
}
if ($year == $currentYear && $month > $currentMonth) {
  $fn->Notify("Tháng không hợp lệ",  "index.php", "error");
  exit;
}
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
$counter = $statistic->getOnline();
?>
<?php include TEMPLATE . LAYOUT . 'loader.php'; ?>
<section class="content mb-3">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Bảng điều khiển</h5>
      </div>
      <div class="card-body">
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
                <h5 class="mb-0"><?= $counter['online'] ?></h5>
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
                <h5 class="mb-0"><?= $counter['today'] ?></h5>
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
        <h5 class="mb-0">
          <?= thongketruycapthang ?> <?= str_pad($month, 2, '0', STR_PAD_LEFT) ?>/<?= $year ?>
        </h5>
      </div>
      <div class="card-body">
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
          $row = $db->rawQueryOne(
            "SELECT COUNT(*) as total FROM tbl_counter WHERE tm >= ? AND tm < ?",
            [$begin, $end]
          );
          $charts['series'][] = (int)$row['total'];
          $charts['labels'][] = 'D' . $i;
        }
        ?>

        <form class="form-filter-charts row align-items-center mb-3" method="get">
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control select2" name="month" id="month">
                <option value=""><?= thang ?></option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                  <option value="<?= $i ?>" <?= $i == $month ? 'selected' : '' ?>>
                    Tháng <?= $i ?>
                  </option>
                <?php endfor; ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control select2" name="year" id="year">
                <option value=""><?= nam ?></option>
                <?php
                $currentYear = date('Y');
                for ($y = 2000; $y <= $currentYear + 10; $y++):
                ?>
                  <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>>
                    Năm <?= $y ?>
                  </option>
                <?php endfor; ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <button type="submit" class="btn btn-success"><?= thongke ?></button>
            </div>
          </div>
        </form>
        <div id="apexMixedChart"></div>
      </div>
    </div>
  </div>
</section>
