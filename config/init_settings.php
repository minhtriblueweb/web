<?php
$optsetting = $db->rawQueryOne("SELECT * FROM tbl_setting WHERE id = ?", [1]);
$optsetting_json = json_decode($optsetting['options'] ?? '{}', true);
$lang = $optsetting_json['lang_default'] ?? array_key_first($config['website']['lang']);
$logo = $db->rawQueryOne("SELECT `file` FROM tbl_photo WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['logo']);
$favicon = $db->rawQueryOne("SELECT `file` FROM tbl_photo WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['favicon']);
// SEO mặc định
$default_seo = [
  'title'       => $optsetting['name' . $lang] ?? '',
  'keywords'    => $optsetting['name' . $lang] ?? '',
  'description' => $optsetting['desc' . $lang] ?? '',
  'geo'         => $optsetting_json['coords'],
  'email'       => $optsetting_json['email'],
  'address'     => $optsetting_json['address'],
  'url'         => $fn->getPageURL(),
  'favicon'     => !empty($favicon['file']) ? $fn->getImageCustom(['file' => $favicon['file'], 'width' => 48, 'height' => 48, 'zc' => 1, 'src_only' => true]) : '',
  'image'       => !empty($logo['file']) ? $fn->getImageCustom(['file' => $logo['file'], 'width' => 300, 'src_only' => true]) : '',
];
