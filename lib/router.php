<?php
define('UPLOADS', 'uploads/');
define('BASE_ADMIN', $config['baseAdmin']);
define('BASE', $config['base']);
$get_setting = $setting->get_setting();
if ($get_setting) {
  $result_setting = $get_setting->fetch_assoc();
}
$show_danhmuc = $danhmuc->show_danhmuc_index('hienthi');
$seo = array(
  'favicon' => isset($result_setting['favicon']) ? BASE_ADMIN . UPLOADS . $result_setting['favicon'] : '',
  'title' => isset($result_setting['web_name']) ? htmlspecialchars($result_setting['web_name']) : '',
  'keywords' => '',
  'description' => isset($result_setting['descvi']) ? htmlspecialchars($result_setting['descvi']) : '',
  'geo' => isset($result_setting['coords']) ? htmlspecialchars($result_setting['coords']) : '',
  'web_name' => isset($result_setting['web_name']) ? htmlspecialchars($result_setting['web_name']) : '',
  'email' => isset($result_setting['email']) ? htmlspecialchars($result_setting['email']) : '',
  'url' => BASE,
  'image' => isset($result_setting['logo']) ? BASE_ADMIN . UPLOADS . $result_setting['logo'] : '',
);

$hotline = isset($result_setting['hotline']) ? htmlspecialchars($result_setting['hotline']) : '';
$web_name = isset($result_setting['web_name']) ? htmlspecialchars($result_setting['web_name']) : '';
$introduction = isset($result_setting['introduction']) ? htmlspecialchars($result_setting['introduction']) : '';
$logo = isset($result_setting['logo']) ? BASE_ADMIN . UPLOADS . $result_setting['logo'] : '';
$worktime = isset($result_setting['worktime']) ? htmlspecialchars($result_setting['worktime']) : '';
$descvi = isset($result_setting['descvi']) ? htmlspecialchars($result_setting['descvi']) : '';
$client_support = isset($result_setting['client_support']) ? $result_setting['client_support'] : '';
$support = isset($result_setting['support']) ? $result_setting['support'] : '';
$copyright = isset($result_setting['copyright']) ? htmlspecialchars($result_setting['copyright']) : '';
$bodyjs = isset($result_setting['bodyjs']) ? $result_setting['bodyjs'] : '';
$headjs = isset($result_setting['headjs']) ? $result_setting['headjs'] : '';
$analytics = isset($result_setting['analytics']) ? $result_setting['analytics'] : '';
