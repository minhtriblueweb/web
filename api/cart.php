<?php
include_once "../config/autoload.php";
require_once "../lib/lang/web/$lang.php";
$cmd = (!empty($_POST['cmd'])) ? htmlspecialchars($_POST['cmd']) : '';
$id = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
$quantity = (!empty($_POST['quantity'])) ? htmlspecialchars($_POST['quantity']) : 1;
$code = (!empty($_POST['code'])) ? htmlspecialchars($_POST['code']) : '';
if ($cmd === 'add-cart' && $id > 0) {
  $quantity = max(1, (int)$quantity);
  $cart->addToCart($quantity, (int)$id);
  $max = (
    isset($_SESSION['cart']) &&
    is_array($_SESSION['cart'])
  ) ? count($_SESSION['cart']) : 0;
  echo json_encode(['max' => $max]);
  exit;
} else if ($cmd == 'update-cart' && $id > 0 && $code != '') {
  $quantity = (int)$quantity;
  if ($quantity < 1) $quantity = 1;
  if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) {
      if (!empty($item['code']) && $item['code'] === $code) {
        $item['qty'] = $quantity;
        break;
      }
    }
    unset($item);
  }
  $proinfo = $cart->getProductInfo($id);
  $regular = (int) preg_replace('/[^0-9]/', '', $proinfo['regular_price'] ?? 0);
  $sale    = (int) preg_replace('/[^0-9]/', '', $proinfo['sale_price'] ?? 0);
  $regular_price = $fn->formatMoney($regular * $quantity);
  $sale_price    = $sale ? $fn->formatMoney($sale * $quantity) : '';
  $temp = $cart->getOrderTotal();
  $tempText = $fn->formatMoney($temp);
  $total = $temp;
  if (!empty($ship)) $total += (int)$ship;
  $totalText = $fn->formatMoney($total);
  echo json_encode([
    'regularPrice' => $regular_price,
    'salePrice'    => $sale_price,
    'tempText'     => $tempText,
    'totalText'    => $totalText
  ]);
  exit;
} else if ($cmd == 'delete-cart' && $code != '') {
  $cart->removeProduct($code);
  $max = (!empty($_SESSION['cart'])) ? count($_SESSION['cart']) : 0;
  $temp = $cart->getOrderTotal();
  $tempText = $fn->formatMoney($temp);
  $total = $cart->getOrderTotal();
  if (!empty($ship)) $total += $ship;
  $totalText = $fn->formatMoney($total);
  $data = array('max' => $max, 'tempText' => $tempText, 'totalText' => $totalText);
  echo json_encode($data);
} else if ($cmd == 'ship-cart') {
  $shipData = array();
  $shipPrice = 0;
  $shipText = '0đ';
  $total = 0;
  $totalText = '';
  if ($id) $shipData = $fn->getInfoDetail('ship_price', "ward", $id);
  $total = $cart->getOrderTotal();
  if (!empty($shipData['ship_price'])) {
    $total += $shipData['ship_price'];
    $shipText = $fn->formatMoney($shipData['ship_price']);
  }
  $totalText = $fn->formatMoney($total);
  $shipPrice = (!empty($shipData['ship_price'])) ? $shipData['ship_price'] : 0;
  $data = array('shipText' => $shipText, 'ship' => $shipPrice, 'totalText' => $totalText);
  echo json_encode($data);
} else if ($cmd == 'popup-cart') { ?>
  <form class="form-cart" method="post" action="" enctype="multipart/form-data">
    <div class="wrap-cart">
      <div class="top-cart border-right-0">
        <div class="list-procart">
          <div class="procart procart-label">
            <div class="row row-10">
              <div class="pic-procart col-3 col-md-2 mg-col-10"><?= hinhanh ?></div>
              <div class="info-procart col-6 col-md-5 mg-col-10"><?= tensanpham ?></div>
              <div class="quantity-procart col-3 col-md-2 mg-col-10">
                <p><?= soluong ?></p>
              </div>
              <div class="text-center col-3 col-md-3 mg-col-10"><?= thanhtien ?></div>
            </div>
          </div>
          <?php
          $max = (!empty($_SESSION['cart']) && is_array($_SESSION['cart']))
            ? count($_SESSION['cart'])
            : 0;
          for ($i = 0; $i < $max; $i++) {
            $pid      = (int) ($_SESSION['cart'][$i]['productid'] ?? 0);
            $quantity = (int) ($_SESSION['cart'][$i]['qty'] ?? 1);
            $code     = $_SESSION['cart'][$i]['code'] ?? '';
            $proinfo = $cart->getProductInfo($pid);
            $pro_price = (int) preg_replace('/[^0-9]/', '', $proinfo['regular_price'] ?? 0);
            $pro_price_new = (int) preg_replace('/[^0-9]/', '', $proinfo['sale_price'] ?? 0);
            $pro_price_qty = $pro_price * $quantity;
            $pro_price_new_qty = $pro_price_new * $quantity;
          ?>
            <div class="procart procart-<?= $code ?>">
              <div class="row row-10">
                <div class="pic-procart col-3 col-md-2 mg-col-10">
                  <a class="text-decoration-none" href="<?= $proinfo["slug$lang"] ?>" target="_blank" title="<?= $proinfo["name$lang"] ?>">
                    <?= $fn->getImageCustom(['file'  => $proinfo['file'], 'alt' => $proinfo["name$lang"], 'title' => $proinfo["name$lang"], 'width' => 85, 'height' => 85, 'zc' => 2]) ?>
                  </a>
                  <a class="del-procart text-decoration-none" data-code="<?= $code ?>">
                    <i class="fa fa-times-circle"></i>
                    <span><?= xoa ?></span>
                  </a>
                </div>
                <div class="info-procart col-6 col-md-5 mg-col-10 d-flex align-items-center">
                  <h3 class="name-procart"><a class="text-decoration-none" href="<?= $proinfo["slug$lang"] ?>" target="_blank" title="<?= $proinfo["name$lang"] ?>"><?= $proinfo["name$lang"] ?></a></h3>
                </div>
                <div class="quantity-procart col-3 col-md-2 mg-col-10 d-flex align-items-center">
                  <div class="price-procart price-procart-rp">
                    <?php if ($proinfo['sale_price']) { ?>
                      <p class="price-new-cart load-price-new-<?= $code ?>">
                        <?= $fn->formatMoney($pro_price_new_qty) ?>
                      </p>
                      <p class="price-old-cart load-price-<?= $code ?>">
                        <?= $fn->formatMoney($pro_price_qty) ?>
                      </p>
                    <?php } else { ?>
                      <p class="price-new-cart load-price-<?= $code ?>">
                        <?= $fn->formatMoney($pro_price_qty) ?>
                      </p>
                    <?php } ?>
                  </div>
                  <div class="quantity-counter-procart quantity-counter-procart-<?= $code ?>">
                    <span class="counter-procart-minus counter-procart">-</span>
                    <input type="number" class="quantity-procart" min="1" value="<?= $quantity ?>" data-pid="<?= $pid ?>" data-code="<?= $code ?>" />
                    <span class="counter-procart-plus counter-procart">+</span>
                  </div>
                </div>
                <div class="text-center d-flex align-items-center col-3 col-md-3 mg-col-10">
                  <div class="text-center align-items-center w-100">
                    <?php if ($proinfo['sale_price']) { ?>
                      <p class=" price-new-cart load-price-new-<?= $code ?>">
                        <?= $fn->formatMoney($pro_price_new_qty) ?>
                      </p>
                      <p class="price-old-cart load-price-<?= $code ?>">
                        <?= $fn->formatMoney($pro_price_qty) ?>
                      </p>
                    <?php } else { ?>
                      <p class="price-new-cart load-price-<?= $code ?>">
                        <?= $fn->formatMoney($pro_price_qty) ?>
                      </p>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
        <div class="money-procart">
          <div class="total-procart">
            <p><?= tamtinh ?>:</p>
            <p class="total-price load-price-temp">
              <?= $fn->formatMoney($cart->getOrderTotal()) ?>
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <a href="san-pham" class="buymore-cart text-decoration-none" title="<?= tieptucmuahang ?>">
            <i class="fa fa-angle-double-left"></i>
            <span><?= tieptucmuahang ?></span>
          </a>
          <a class="btn btn-primary btn-cart" href="gio-hang" title="<?= thanhtoan ?>"><?= thanhtoan ?></a>
        </div>
      </div>
    </div>
  </form>
<?php } ?>
