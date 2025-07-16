<?php
$lang = $_SESSION['lang'] ?? array_key_first($config['website']['lang']);
$optsetting = $db->rawQueryOne("SELECT * FROM tbl_setting WHERE id = ?", [1]);
$logo = $db->rawQueryOne("SELECT `file` FROM tbl_photo WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['logo']);
$favicon = $db->rawQueryOne("SELECT `file` FROM tbl_photo WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['favicon']);
// SEO mặc định
$default_seo = [
  'title'       => $optsetting['web_name'],
  'keywords'    => $optsetting['web_name'],
  'description' => $optsetting['desc' . $lang] ?? '',
  'geo'         => $optsetting['coords'],
  'web_name'    => $optsetting['web_name'],
  'email'       => $optsetting['email'],
  'url'         => BASE,
  'favicon'     => !empty($favicon['file']) ? $fn->getImageCustom(['file' => $favicon['file'], 'width' => 48, 'height' => 48, 'zc' => 1, 'src_only' => true]) : '',
  'image'       => !empty($logo['file']) ? $fn->getImageCustom(['file' => $logo['file'], 'width' => 300, 'src_only' => true]) : '',
];
