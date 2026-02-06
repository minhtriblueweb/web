<?php
class Cart
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function getProductInfo($pid = 0)
  {
    $row = null;
    if ($pid) {
      $row = $this->db->rawQueryOne("SELECT * FROM tbl_product WHERE id = ? LIMIT 0,1", array($pid));
    }
    return $row;
  }

  public function removeProduct($code_order = '')
  {
    if (!empty($_SESSION['cart']) && $code_order != '') {
      $max = count($_SESSION['cart']);

      for ($i = 0; $i < $max; $i++) {
        if ($code_order == $_SESSION['cart'][$i]['code']) {
          unset($_SESSION['cart'][$i]);
          break;
        }
      }

      $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
  }

  public function getOrderTotal()
  {
    $sum = 0;
    if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
      foreach ($_SESSION['cart'] as $item) {
        $pid = (int) ($item['productid'] ?? 0);
        $q   = (int) ($item['qty'] ?? 1);
        if ($pid <= 0 || $q <= 0) continue;
        $proinfo = $this->getProductInfo($pid);
        $priceRaw = ($proinfo['sale_price'])
          ? $proinfo['sale_price']
          : $proinfo['regular_price'];
        $price = (int) preg_replace('/[^0-9]/', '', $priceRaw);
        $sum += $price * $q;
      }
    }
    return $sum;
  }


  public function addToCart($q = 1, $pid = 0, $color = 0, $size = 0)
  {
    if ($pid < 1 or $q < 1) return;
    $code_order = md5($pid . $color . $size);
    if (!empty($_SESSION['cart'])) {
      if (!$this->productExists($code_order, $q)) {
        $max = count($_SESSION['cart']);
        $_SESSION['cart'][$max]['productid'] = $pid;
        $_SESSION['cart'][$max]['qty'] = $q;
        $_SESSION['cart'][$max]['color'] = $color;
        $_SESSION['cart'][$max]['size'] = $size;
        $_SESSION['cart'][$max]['code'] = $code_order;
      }
    } else {
      $_SESSION['cart'] = array();
      $_SESSION['cart'][0]['productid'] = $pid;
      $_SESSION['cart'][0]['qty'] = $q;
      $_SESSION['cart'][0]['color'] = $color;
      $_SESSION['cart'][0]['size'] = $size;
      $_SESSION['cart'][0]['code'] = $code_order;
    }
  }

  private function productExists($code_order = '', $q = 1)
  {
    $flag = 0;
    if (!empty($_SESSION['cart']) && $code_order != '') {
      $q = ($q > 1) ? $q : 1;
      $max = count($_SESSION['cart']);
      for ($i = 0; $i < $max; $i++) {
        if ($code_order == $_SESSION['cart'][$i]['code']) {
          $_SESSION['cart'][$i]['qty'] += $q;
          $flag = 1;
        }
      }
    }
    return $flag;
  }
}
