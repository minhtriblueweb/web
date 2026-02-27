<?php
$hotline         = $optsetting_json['hotline'];
$introduction    = $optsetting["slogan{$lang}"];
$worktime        = $optsetting_json['opendoor'];
$descvi          = $optsetting_json["desc'{$lang}"] ?? '';
$web_name        = $optsetting_json["name'{$lang}"] ?? '';
$address         = $optsetting_json['address'];
$coords_iframe   = $optsetting_json['coords_iframe'];
$copyright       = $optsetting_json['copyright'];
$bodyjs          = $optsetting['bodyjs'];
$headjs          = $optsetting['headjs'];
$analytics       = $optsetting['analytics'];
$color           = $optsetting_json['color'];

$show_social = $func->show_data([
  'table' => 'tbl_photo',
  'status' => 'hienthi',
  'type'   => 'social',
  'select' => "file, link, name{$lang}, desc{$lang}"
]);

$tieuchi = $func->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'tieu-chi',
  'select' => "file, name{$lang}, desc{$lang}"
]);

/* Lấy brand*/
$brand = $d->rawQuery("select id,slug{$lang},name{$lang} from `tbl_product_brand` where find_in_set('hienthi',status) order by numb, id desc");

/* Lấy tin tức cấp 1 */
$news_list = $d->rawQuery("select id,slug{$lang},name{$lang} from `tbl_news_list` where type = 'tin-tuc' and find_in_set('hienthi',status) order by numb, id desc");

// Chính sách
$show_chinhsach = $func->show_data(['table' => 'tbl_news', 'status' => 'hienthi', 'type'   => 'chinh-sach', 'select' => "id, slug{$lang}, name{$lang}"]);

// Bài viết mới
$news_new = $func->show_data([
  'table'    => 'tbl_news',
  'status'   => 'hienthi',
  'type'     => 'tin-tuc',
  'select'   => "id, file, slug{$lang}, name{$lang}",
  'order_by' => 'id DESC',
  'limit'    => 5
]);

/* Newsletter */
if (!empty($_POST['submit-newsletter'])) {
  $data = $_POST['dataNewsletter'] ?? [];
  $email = trim($data['email'] ?? '');
  if ($email === '') {
    $func->transfer(vuilongnhapdiachiemail, $func->getCurrentPageURL(), false);
  }
  if (!$func->isEmail($email)) {
    $func->transfer(emailkhonghople, $func->getCurrentPageURL(), false);
  }
  $exists = $d->rawQueryOne("SELECT id FROM `tbl_newsletter` WHERE email = ? LIMIT 1",[$email]);
  if ($exists) {
    $func->transfer("Email đã được đăng ký", $func->getCurrentPageURL(), false);
  }
  $insert = $d->execute(
    "INSERT INTO `tbl_newsletter` (email, type, date_created) VALUES (?, ?, NOW())",
    [$email, 'dang-ky-nhan-tin']
  );
  $func->transfer( $insert ? guilienhethanhcong : guilienhethatbai, BASE, $insert);
}
