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