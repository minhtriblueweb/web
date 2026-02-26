<?php
if (!defined('SOURCES')) die("Error");

/* Kiểm tra active đơn hàng */
if (!isset($config['order']['active']) || $config['order']['active'] == false) $func->transfer(trangkhongtontai, "index.php", false);

/* Cấu hình đường dẫn trả về */
$strUrl = "";
$strUrl .= (isset($_REQUEST['order_status'])) ? "&order_status=" . htmlspecialchars($_REQUEST['order_status']) : "";
$strUrl .= (isset($_REQUEST['order_payment'])) ? "&order_payment=" . htmlspecialchars($_REQUEST['order_payment']) : "";
$strUrl .= (isset($_REQUEST['order_date'])) ? "&order_date=" . htmlspecialchars($_REQUEST['order_date']) : "";
$strUrl .= (isset($_REQUEST['range_price'])) ? "&range_price=" . htmlspecialchars($_REQUEST['range_price']) : "";
$strUrl .= (isset($_REQUEST['city'])) ? "&city=" . htmlspecialchars($_REQUEST['city']) : "";
$strUrl .= (isset($_REQUEST['district'])) ? "&district=" . htmlspecialchars($_REQUEST['district']) : "";
$strUrl .= (isset($_REQUEST['ward'])) ? "&ward=" . htmlspecialchars($_REQUEST['ward']) : "";
$strUrl .= (isset($_REQUEST['keyword'])) ? "&keyword=" . htmlspecialchars($_REQUEST['keyword']) : "";

switch ($act) {
  case "man":
    viewMans();
    $template = "order/man/mans";
    break;

  case "edit":
    editMan();
    $template = "order/man/man_add";
    break;

  case "save":
    saveMan();
    break;

  case "delete":
    deleteMan();
    break;

  default:
    $func->transfer(trangkhongtontai, "index.php", false);
    break;
}

/* View order */
function viewMans()
{
  global $d, $func, $strUrl, $curPage, $items, $paging, $minTotal, $maxTotal, $price_from, $price_to, $allNewOrder, $totalNewOrder, $allConfirmOrder, $totalConfirmOrder, $allDeliveriedOrder, $totalDeliveriedOrder, $allCanceledOrder, $totalCanceledOrder;

  $where = "";

  $order_status  = !empty($_REQUEST['order_status'])  ? (int)$_REQUEST['order_status']  : 0;
  $order_payment = !empty($_REQUEST['order_payment']) ? (int)$_REQUEST['order_payment'] : 0;
  $order_date    = !empty($_REQUEST['order_date'])    ? $_REQUEST['order_date']        : '';
  $range_price   = !empty($_REQUEST['range_price'])   ? $_REQUEST['range_price']       : '';
  $city          = !empty($_REQUEST['id_city'])       ? (int)$_REQUEST['id_city']       : 0;
  $district      = !empty($_REQUEST['id_district'])   ? (int)$_REQUEST['id_district']   : 0;
  $ward          = !empty($_REQUEST['id_ward'])       ? (int)$_REQUEST['id_ward']       : 0;

  if ($order_status)  $where .= " AND order_status = $order_status";
  if ($order_payment) $where .= " AND order_payment = $order_payment";

  if ($order_date) {
    $order_date = explode("-", $order_date);
    $date_from = strtotime(str_replace("/", "-", trim($order_date[0] . " 00:00:00")));
    $date_to   = strtotime(str_replace("/", "-", trim($order_date[1] . " 23:59:59")));
    $where .= " AND date_created >= $date_from AND date_created <= $date_to";
  }

  if ($range_price) {
    $range_price = explode(";", $range_price);
    $price_from = (int)trim($range_price[0]);
    $price_to   = (int)trim($range_price[1]);
    $where .= " AND total_price >= $price_from AND total_price <= $price_to";
  }

  if ($city)     $where .= " AND city = $city";
  if ($district) $where .= " AND district = $district";
  if ($ward)     $where .= " AND ward = $ward";

  if (!empty($_REQUEST['keyword'])) {
    $keyword = htmlspecialchars($_REQUEST['keyword'], ENT_QUOTES);
    $where .= " AND (
      fullname LIKE '%$keyword%' OR
      email    LIKE '%$keyword%' OR
      phone    LIKE '%$keyword%' OR
      code     LIKE '%$keyword%'
    )";
  }

  /* ===== PHÂN TRANG ===== */
  $perPage = 10;
  $curPage = max(1, (int)($_GET['p'] ?? 1));
  $startpoint = ($curPage - 1) * $perPage;
  $limit = " LIMIT $startpoint, $perPage";

  /* ===== DANH SÁCH ĐƠN HÀNG ===== */
  $sql = "SELECT * FROM `tbl_order` WHERE id <> 0 $where ORDER BY numb ASC, date_created DESC $limit";
  $items = $d->rawQuery($sql);

  /* ===== ĐẾM TỔNG ===== */
  $sqlNum = "SELECT COUNT(*) AS num FROM `tbl_order` WHERE id <> 0 $where";
  $count = $d->rawQueryOne($sqlNum);
  $total = !empty($count) ? (int)$count['num'] : 0;
  $url    = $strUrl;
  $paging = $func->pagination($total, $perPage, $curPage, $url);

  /* ===== GIÁ MIN ===== */
  $minTotal = $d->rawQueryOne("SELECT MIN(total_price) AS min_price FROM tbl_order");
  $minTotal = !empty($minTotal['min_price']) ? $minTotal['min_price'] : 0;

  /* ===== GIÁ MAX ===== */
  $maxTotal = $d->rawQueryOne("SELECT MAX(total_price) AS max_price FROM tbl_order");
  $maxTotal = !empty($maxTotal['max_price']) ? $maxTotal['max_price'] : 0;

  /* ===== THỐNG KÊ ĐƠN HÀNG ===== */

  // Mới đặt
  $order_count = $d->rawQueryOne("SELECT COUNT(id) AS total, SUM(total_price) AS amount FROM tbl_order WHERE order_status = 1");
  $allNewOrder   = $order_count['total'];
  $totalNewOrder = $order_count['amount'];

  // Đã xác nhận
  $order_count = $d->rawQueryOne("SELECT COUNT(id) AS total, SUM(total_price) AS amount FROM tbl_order WHERE order_status = 2");
  $allConfirmOrder   = $order_count['total'];
  $totalConfirmOrder = $order_count['amount'];

  // Đã giao
  $order_count = $d->rawQueryOne("SELECT COUNT(id) AS total, SUM(total_price) AS amount FROM tbl_order WHERE order_status = 4");
  $allDeliveriedOrder   = $order_count['total'];
  $totalDeliveriedOrder = $order_count['amount'];

  // Đã hủy
  $order_count = $d->rawQueryOne("SELECT COUNT(id) AS total, SUM(total_price) AS amount FROM tbl_order WHERE order_status = 5");
  $allCanceledOrder   = $order_count['total'];
  $totalCanceledOrder = $order_count['amount'];
}

/* Edit order */
function editMan()
{
  global $d, $func, $item, $order_detail;
  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
  if (empty($id)) {
    $func->transfer(khongnhanduocdulieu, "index.php?com=order&act=man", false);
  } else {
    $item = $d->rawQueryOne("SELECT * FROM `tbl_order` WHERE id = ? LIMIT 0,1", array($id));
    if (empty($item)) {
      $func->transfer(dulieukhongcothuc, "index.php?com=order&act=man", false);
    } else {
      $order_detail = $d->rawQuery("SELECT * FROM `tbl_order_detail` WHERE id_order = ? ORDER BY id DESC", array($id));
    }
  }
}

/* Save order */
function saveMan()
{
  global $d, $func;
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
  }
  $id   = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
  $data = (!empty($_POST['data'])) ? $_POST['data'] : null;
  if ($data) {
    foreach ($data as $column => $value) {
      $data[$column] = htmlspecialchars($func->sanitize($value));
    }
  }
  if ($id) {
    $d->where('id', $id);
    if ($d->update('tbl_order', $data)) {
      if (isset($_POST['save-here'])) {
        $func->Notify(capnhatdulieuthanhcong, "index.php?com=order&act=edit&id=$id", 'success');
      } else {
        $func->transfer(capnhatdulieuthanhcong, "index.php?com=order&act=man");
      }
    } else {
      $func->transfer(capnhatdulieubiloi, "index.php?com=order&act=man", false);
    }
  } else {
    $func->transfer(dulieurong, "index.php?com=order&act=man", false);
  }
}

/* Delete order */
function deleteMan()
{
  global $d, $func;
  $id = (!empty($_GET['id'])) ? htmlspecialchars($_GET['id']) : 0;
  if ($id) {
    $row = $d->rawQueryOne("SELECT id FROM tbl_order WHERE id = ? LIMIT 1", array($id));
    if (!empty($row)) {
      $d->rawQuery("DELETE FROM tbl_order_detail WHERE id_order = ?", array($id));
      $d->rawQuery("DELETE FROM tbl_order WHERE id = ?", array($id));
      $func->transfer(xoadulieuthanhcong, "index.php?com=order&act=man");
    } else {
      $func->transfer(xoadulieubiloi, "index.php?com=order&act=man", false);
    }
  } elseif (isset($_GET['listid'])) {
    $listid = explode(",", $_GET['listid']);
    for ($i = 0; $i < count($listid); $i++) {
      $id = htmlspecialchars($listid[$i]);
      $row = $d->rawQueryOne("SELECT id FROM tbl_order WHERE id = ? LIMIT 1", array($id));
      if (!empty($row)) {
        $d->rawQuery("DELETE FROM tbl_order_detail WHERE id_order = ?", array($id));
        $d->rawQuery("DELETE FROM tbl_order WHERE id = ?", array($id));
      }
    }
    $func->transfer(xoadulieuthanhcong, "index.php?com=order&act=man");
  } else {
    $func->transfer(khongnhanduocdulieu, "index.php?com=order&act=man", false);
  }
}
