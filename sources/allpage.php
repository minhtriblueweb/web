<?php
$favicon = $d->rawQueryOne("select photo from tbl_photo where type = ? and act = ? and find_in_set('hienthi',status) limit 0,1", array('favicon', 'photo_static'));
$logo = $d->rawQueryOne("select id, photo, options from tbl_photo where type = ? and act = ? limit 0,1", array('logo', 'photo_static'));

$hotline         = $optsetting['hotline'];
$introduction    = $setting["slogan$lang"];
$worktime        = $optsetting['opendoor'];
$descvi          = $optsetting["desc$lang"] ?? '';
$web_name        = $optsetting["name$lang"] ?? '';
$address         = $optsetting['address'];
$coords_iframe   = $optsetting['coords_iframe'];
$copyright       = $optsetting['copyright'];
$bodyjs          = $setting['bodyjs'];
$headjs          = $setting['headjs'];
$analytics       = $setting['analytics'];
$color           = $optsetting['color'];

$show_social = $func->show_data([
  'table' => 'tbl_photo',
  'status' => 'hienthi',
  'type'   => 'social',
  'select' => "photo, link, name$lang, desc$lang"
]);

$tieuchi = $func->show_data([
  'table' => 'tbl_news',
  'status' => 'hienthi',
  'type'   => 'tieu-chi',
  'select' => "file, name$lang, desc$lang"
]);

/* Lấy brand*/
$brand = $d->rawQuery("select id,slug$lang,name$lang from `tbl_product_brand` where find_in_set('hienthi',status) order by numb, id desc");

/* Lấy tin tức cấp 1 */
$news_list = $d->rawQuery("select id,slug$lang,name$lang from `tbl_news_list` where type = 'tin-tuc' and find_in_set('hienthi',status) order by numb, id desc");

// Chính sách
$show_chinhsach = $func->show_data(['table' => 'tbl_news', 'status' => 'hienthi', 'type'   => 'chinh-sach', 'select' => "id, slug$lang, name$lang"]);

// Bài viết mới
$news_new = $func->show_data([
  'table'    => 'tbl_news',
  'status'   => 'hienthi',
  'type'     => 'tin-tuc',
  'select'   => "id, file, slug$lang, name$lang",
  'order_by' => 'id DESC',
  'limit'    => 5
]);

/* Newsletter */
if (!empty($_POST['submit-newsletter'])) {
  if (empty($_POST['dataNewsletter']) || !is_array($_POST['dataNewsletter'])) {
    $func->transfer("Dữ liệu không hợp lệ", $func->getCurrentPageURL(), false);
  }
  $dataNewsletter = $_POST['dataNewsletter'];
  foreach ($dataNewsletter as $column => $value) {
    $dataNewsletter[$column] = trim($value);
  }
  $email = strtolower($dataNewsletter['email'] ?? '');
  if ($email === '') {
    $func->transfer(vuilongnhapdiachiemail, $func->getCurrentPageURL(), false);
  }
  if (!$func->isEmail($email)) {
    $func->transfer(emailkhonghople, $func->getCurrentPageURL(), false);
  }
  $exists = $d->rawQueryOne("SELECT id FROM tbl_newsletter WHERE email = ? LIMIT 1",[$email]);
  if (!empty($exists)) {
    $func->transfer("Email đã được đăng ký", $func->getCurrentPageURL(), false);
  }
  $dataNewsletter['date_created'] = time();
  $dataNewsletter['numb'] = 1;
  $dataNewsletter['type'] = 'dang-ky-nhan-tin';
  if ($d->insert('tbl_newsletter', $dataNewsletter)) {
    $func->transfer(dangkynhantinthanhcong, BASE);
  } else {
    $func->transfer(dangkynhantinthatbai, BASE, false);
  }
}
