<?php
$get_setting = $setting->get_setting();
if ($get_setting) {
  $row_st = $get_setting->fetch_assoc();
}
// SEO mặc định
$default_seo = [
  'favicon'     => !empty($row_st['favicon']) ? BASE_ADMIN . UPLOADS . $row_st['favicon'] : '',
  'title'       => get_setting_value('web_name'),
  'keywords'    => '',
  'description' => get_setting_value('descvi'),
  'geo'         => get_setting_value('coords'),
  'web_name'    => get_setting_value('web_name'),
  'email'       => get_setting_value('email'),
  'url'         => BASE,
  'image'       => !empty($row_st['logo']) ? BASE_ADMIN . UPLOADS . $row_st['logo'] : ''
];
// Các biến toàn cục khác (tuỳ dùng)
$hotline         = get_setting_value('hotline');
$web_name        = get_setting_value('web_name');
$introduction    = get_setting_value('introduction');
$worktime        = get_setting_value('worktime');
$descvi          = get_setting_value('descvi');
$address         = get_setting_value('address');
$coords_iframe   = get_setting_value('coords_iframe', false);
$copyright       = get_setting_value('copyright');
$bodyjs          = get_setting_value('bodyjs', false);
$headjs          = get_setting_value('headjs', false);
$analytics       = get_setting_value('analytics', false);
$logo            = get_setting_value('logo', false);
$color           = get_setting_value('color', false);
