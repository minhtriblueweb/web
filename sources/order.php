<?php
if (!defined('SOURCES')) die("Error");

/* SEO */
$seo->set('title', $titleMain);

/* breadCrumbs */
if (!empty($titleMain)) $breadcr->set($slug, $titleMain);
$breadcrumbs = $breadcr->get();

/* Tỉnh thành */
// $city = $db->rawQuery("SELECT name, id FROM `tbl_city` ORDER BY numb DESC");

/* Hình thức thanh toán */
$payments_info = $db->rawQuery("SELECT name$lang, desc$lang, id FROM `tbl_news` WHERE type = ? AND FIND_IN_SET(?, status) ORDER BY numb,id DESC", array('hinh-thuc-thanh-toan','hienthi'));

if (!empty($_POST['thanhtoan'])) {

  /* Check cart */
  if (empty($_SESSION['cart'])) {
    $fn->transfer(donhangkhonghoplevuilongthulaisau, BASE, false);
  }

  $dataOrder = $_POST['dataOrder'] ?? [];

  /* ========== VALIDATE INPUT ========== */
  $response = ['messages' => []];

  if (empty($dataOrder['payments'])) $response['messages'][] = chuachonhinhthucthanhtoan;
  if (empty($dataOrder['fullname'])) $response['messages'][] = hotenkhongduoctrong;
  if (empty($dataOrder['phone'])) $response['messages'][] = sodienthoaikhongduoctrong;
  if (!empty($dataOrder['phone']) && !$fn->isPhone($dataOrder['phone'])) $response['messages'][] = sodienthoaikhonghople;
  if (empty($dataOrder['email'])) $response['messages'][] = emailkhongduoctrong;
  if (!empty($dataOrder['email']) && !$fn->isEmail($dataOrder['email'])) $response['messages'][] = emailkhonghople;
  if (empty($dataOrder['city'])) $response['messages'][] = chuachontinhthanhpho;
  if (empty($dataOrder['district'])) $response['messages'][] = chuachonquanhuyen;
  if (empty($dataOrder['ward'])) $response['messages'][] = chuachonphuongxa;
  if (empty($dataOrder['address'])) $response['messages'][] = diachikhongduoctrong;

  if (!empty($response['messages'])) {
    foreach ($dataOrder as $k => $v) {
      $flash->set($k, $v);
    }
    $flash->set('message', base64_encode(json_encode($response)));
    $fn->redirect('gio-hang');
    exit;
  }

  /* ========== BASIC INFO ========== */
  $code        = strtoupper($fn->stringRandom(6));
  $order_date = time();
  $fullname   = htmlspecialchars($dataOrder['fullname']);
  $email      = htmlspecialchars($dataOrder['email']);
  $phone      = htmlspecialchars($dataOrder['phone']);
  $requirements = htmlspecialchars($dataOrder['requirements'] ?? '');

  /* ========== ADDRESS ========== */
  $city     = (int)$dataOrder['city'];
  $district = (int)$dataOrder['district'];
  $ward     = (int)$dataOrder['ward'];

  $city_text     = $fn->getInfoDetail('name', 'tbl_city', $city);
  $district_text = $fn->getInfoDetail('name', 'tbl_district', $district);
  $ward_text     = $fn->getInfoDetail('name', 'tbl_ward', $ward);

  $address = htmlspecialchars($dataOrder['address']) . ', '
    . ($ward_text['name'] ?? '') . ', '
    . ($district_text['name'] ?? '') . ', '
    . ($city_text['name'] ?? '');

  /* ========== PAYMENT ========== */
  $order_payment = (int)$dataOrder['payments'];
  $payment_info = $fn->getInfoDetail("name$lang", 'tbl_news', $order_payment);
  $order_payment_text = $payment_info["name$lang"] ?? '';

  /* ========== SHIPPING ========== */
  $ship_price = 0;
  if (!empty($config['order']['ship'])) {
    $ship_data = $fn->getInfoDetail('ship_price', 'tbl_ward', $ward);
    $ship_price = (int)($ship_data['ship_price'] ?? 0);
  }

  /* ========== PRICE ========== */
  $temp_price  = (int)$cart->getOrderTotal();
  $total_price = $temp_price + $ship_price;

  /* ========== INSERT ORDER ========== */
  $data_donhang = [
    'id_user'       => $_SESSION['member']['id'] ?? 0,
    'code'          => $code,
    'fullname'      => $fullname,
    'phone'         => $phone,
    'address'       => $address,
    'email'         => $email,
    'order_payment' => $order_payment,
    'ship_price'    => $ship_price,
    'temp_price'    => $temp_price,
    'total_price'   => $total_price,
    'requirements'  => $requirements,
    'date_created'  => $order_date,
    'order_status'  => 1,
    'city'          => $city,
    'district'      => $district,
    'ward'          => $ward,
    'numb'          => 1
  ];
  $id_insert = $db->insert('tbl_order', $data_donhang);

  if ($id_insert) {
    foreach ($_SESSION['cart'] as $item) {
      $q = (int)$item['qty'];
      if ($q <= 0) continue;
      $proinfo = $cart->getProductInfo($item['productid']);
      $sale_price    = (int)preg_replace('/[^0-9]/', '', $proinfo['sale_price'] ?? 0);
      $regular_price = (int)preg_replace('/[^0-9]/', '', $proinfo['regular_price'] ?? 0);
      $data_donhangchitiet = [
        'id_product'    => (int)$item['productid'],
        'id_order'      => $id_insert,
        'photo'         => $proinfo['file'] ?? '',
        'name'          => $proinfo['name' . $lang] ?? '',
        'code'          => $item['code'] ?? '',
        'regular_price' => $regular_price,
        'sale_price'    => $sale_price,
        'quantity'      => $q
      ];
      $db->insert('tbl_order_detail', $data_donhangchitiet);
    }
  }
  unset($_SESSION['cart']);
  $fn->transfer_tc(thongtindonhangdaduocguithanhcong, BASE, true);
}
