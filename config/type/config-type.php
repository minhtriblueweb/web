<?php
/* Config type - Newsletter */
require_once 'config-type-newsletter.php';

/* Config type - News */
require_once 'config-type-news.php';

/* Config type - Static */
require_once 'config-type-static.php';

/* Config type - Photo */
require_once 'config-type-photo.php';

require_once 'config-type-product.php';

/* Seo page */
$config['seopage']['page'] = array(
  "trangchu" => "Trang chủ",
  "gioithieu" => "Giới thiệu",
  "sanpham" => "Sản phẩm",
  "muahang" => "Mua hàng",
  "huongdanchoi" => "Hướng dẫn chơi ",
  "tintuc" => "Tin tức",
  "lienhe" => "Liên hệ"
);
$config['seopage']['width'] = 600;
$config['seopage']['height'] = 315;
$config['seopage']['thumb'] = '600x315x1';
$config['seopage']['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Setting */
$config['setting']['address'] = true;
$config['setting']['phone'] = true;
$config['setting']['hotline'] = true;
$config['setting']['zalo'] = true;
$config['setting']['oaidzalo'] = true;
$config['setting']['email'] = true;
$config['setting']['website'] = true;
$config['setting']['fanpage'] = true;
$config['setting']['coords'] = true;
$config['setting']['coords_iframe'] = true;
$config['setting']['coords_link'] = true;
$config['setting']['slogan'] = true;
$config['setting']['opendoor'] = true;
$config['setting']['copyright'] = true;
$config['setting']['color'] = true;
$config['setting']['video'] = false;
/* Quản lý import */
$config['import']['images'] = true;
$config['import']['thumb'] = '100x100x1';
$config['import']['img_type'] = ".jpg|.gif|.png|.jpeg|.gif";

/* Quản lý export */
$config['export']['category'] = true;

/* Quản lý tài khoản */
$config['user']['active'] = true;
$config['user']['admin'] = false;
$config['user']['check_admin'] = array("hienthi" => "Kích hoạt");
$config['user']['member'] = false;
$config['user']['check_member'] = array("hienthi" => "Kích hoạt");

/* Quản lý phân quyền */
$config['permission']['active'] = false;
$config['permission']['check'] = array("hienthi" => "Kích hoạt");

/* Quản lý liên lệ */
$config['contact']['check'] = array("hienthi" => "Xác nhận");

/* Quản lý địa điểm */
$config['places']['active'] = false;
$config['places']['check_city'] = array("hienthi" => "Hiển thị");
$config['places']['check_district'] = array("hienthi" => "Hiển thị");
$config['places']['check_ward'] = array("hienthi" => "Hiển thị");
$config['places']['placesship'] = true;

/* Quản lý giỏ hàng */
$config['order']['active'] = false;
$config['order']['search'] = true;
$config['order']['excel'] = true;
$config['order']['word'] = true;
$config['order']['excelall'] = true;
$config['order']['wordall'] = true;
$config['order']['thumb'] = '100x100x1';

/* Quản lý thông báo đẩy */
$config['onesignal'] = false;
