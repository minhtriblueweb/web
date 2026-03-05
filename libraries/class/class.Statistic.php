<?php
class Statistic
{
  private $d;
  private $cache;

  function __construct($d, $cache)
  {
    $this->d = $d;
    $this->cache = $cache;
  }

  public function getCounter()
  {
    $locktime = 15 * 60;
    $initialvalue = 1;
    $records = 100000;
    $day = date('d');
    $month = date('n');
    $year = date('Y');

    /* Day start */
    $daystart = mktime(0, 0, 0, $month, $day, $year);

    /* Month start */
    $monthstart = mktime(0, 0, 0, $month, 1, $year);

    /* Week start */
    $weekday = date('w');
    $weekday--;
    if ($weekday < 0) $weekday = 7;
    $weekday = $weekday * 24 * 60 * 60;
    $weekstart = $daystart - $weekday;

    /* Yesterday start */
    $yesterdaystart = $daystart - (24 * 60 * 60);
    $now = time();
    $ip = $_SERVER['REMOTE_ADDR'];

    $t = $this->cache->get("select max(id) as total from #_counter", null, 'fetch', 1800);
    $all_visitors = $t['total'];

    if ($all_visitors !== NULL) $all_visitors += $initialvalue;
    else $all_visitors = $initialvalue;

    /* Delete old records */
    $temp = $all_visitors - $records;

    if ($temp > 0) $this->d->rawQuery("delete from #_counter where id < '$temp'");

    $vip = $this->d->rawQueryOne("select count(*) as visitip from #_counter where ip='$ip' and (tm+'$locktime')>'$now' limit 0,1");
    $items = $vip['visitip'];

    if (empty($items)) $this->d->rawQuery("insert into #_counter (tm, ip) values ('$now', '$ip')");

    $n = $all_visitors;
    $div = 100000;
    while ($n > $div) $div *= 10;

    $todayrec = $this->cache->get("select count(*) as todayrecord from #_counter where tm > '$daystart'", null, 'fetch', 1800);
    $yesrec = $this->cache->get("select count(*) as yesterdayrec from #_counter where tm > '$yesterdaystart' and tm < '$daystart'", null, 'fetch', 1800);
    $weekrec = $this->cache->get("select count(*) as weekrec from #_counter where tm >= '$weekstart'", null, 'fetch', 1800);
    $monthrec = $this->cache->get("select count(*) as monthrec from #_counter where tm >= '$monthstart'", null, 'fetch', 1800);
    $totalrec = $this->cache->get("select max(id) as totalrec from #_counter", null, 'fetch', 1800);

    $result['today'] = $todayrec['todayrecord'];
    $result['yesterday'] = $yesrec['yesterdayrec'];
    $result['week'] = $weekrec['weekrec'];
    $result['month'] = $monthrec['monthrec'];
    $result['total'] = $totalrec['totalrec'];

    return $result;
  }

  public function getOnline()
  {
    $now = time();

    /* ===== TIME RANGE ===== */
    $daystart   = strtotime('today');
    $weekstart  = strtotime('monday this week');
    $monthstart = strtotime(date('Y-m-01'));

    /* ===== ONLINE HIỆN TẠI (10 PHÚT) ===== */
    $online_time = $now - 600;

    $online_now = $this->d->rawQueryOne(
      "SELECT COUNT(*) AS total FROM #_user_online WHERE time > ?",
      [$online_time]
    )['total'];

    /* ===== ONLINE TRONG NGÀY (IP DUY NHẤT) ===== */
    $online_day = $this->d->rawQueryOne(
      "SELECT COUNT(DISTINCT ip) AS total FROM #_user_online WHERE time >= ?",
      [$daystart]
    )['total'];

    /* ===== ONLINE TRONG TUẦN ===== */
    $online_week = $this->d->rawQueryOne(
      "SELECT COUNT(DISTINCT ip) AS total FROM #_user_online WHERE time >= ?",
      [$weekstart]
    )['total'];

    /* ===== ONLINE TRONG THÁNG ===== */
    $online_month = $this->d->rawQueryOne(
      "SELECT COUNT(DISTINCT ip) AS total FROM #_user_online WHERE time >= ?",
      [$monthstart]
    )['total'];

    return [
      'now'   => (int)$online_now,
      'day'   => (int)$online_day,
      'week'  => (int)$online_week,
      'month' => (int)$online_month,
    ];
  }
}
