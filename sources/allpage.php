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

$show_social = $fn->show_data([
  'table' => 'tbl_photo',
  'status' => 'hienthi',
  'type'   => 'social',
  'select' => "file, link, name{$lang}, desc{$lang}"
]);

$tieuchi = $fn->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'tieu-chi',
  'select' => "file, name{$lang}, desc{$lang}"
]);

$show_chinhsach = $fn->show_data(['table' => 'tbl_news', 'status' => 'hienthi', 'type'   => 'chinh-sach', 'select' => "id, slug{$lang}, name{$lang}"]);

/* Newsletter */
if (!empty($_POST['submit-newsletter'])) {
  $data = $_POST['dataNewsletter'] ?? [];
  $email = trim($data['email'] ?? '');
  if ($email === '') {
    $fn->transfer_tc(vuilongnhapdiachiemail, $fn->getCurrentPageURL(), false);
  }
  if (!$fn->isEmail($email)) {
    $fn->transfer_tc(emailkhonghople, $fn->getCurrentPageURL(), false);
  }
  $exists = $db->rawQueryOne("SELECT id FROM tbl_newsletter WHERE email = ? LIMIT 1",[$email]);
  if ($exists) {
    $fn->transfer_tc("Email đã được đăng ký", $fn->getCurrentPageURL(), false);
  }
  $insert = $db->execute(
    "INSERT INTO tbl_newsletter (email, type, date_created) VALUES (?, ?, NOW())",
    [$email, 'dang-ky-nhan-tin']
  );
  $fn->transfer_tc( $insert ? guilienhethanhcong : guilienhethatbai, BASE, $insert);
}
