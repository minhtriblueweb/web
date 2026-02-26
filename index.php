<?php
ob_start();
session_start();
define('ROOT', realpath(dirname(__DIR__)));
define('LIBRARIES', './libraries/');
define('SOURCES', './sources/');
define('LAYOUT', 'layout/');
define('THUMBS', 'thumbs');
define('JSONS', './assets/jsons/');
define('WATERMARK', 'watermark');
define('TEMPLATE', 'templates/');
define('UPLOADS', 'uploads/');
define('THUMB', 'thumb/');

require_once LIBRARIES . 'config.php';
require_once LIBRARIES . 'config-type.php';
require_once LIBRARIES . 'database.php';
require_once LIBRARIES . 'session.php';
require_once LIBRARIES . 'autoload.php';

new AutoLoad();
$d  = new Database();
// $cache = new Cache($d);
$func = new Functions($d);
$flash = new Flash();
$seo = new Seo($d);
$breadcr = new Breadcrumbs();
$statistic = new Statistic($d);
$cart = new Cart($d);

define('VERSION', $func->generateHash());
define('BASE', $config['base'] ?? '/');
define('BASE_ADMIN', $config['baseAdmin'] ?? '/admin/');
// define('NO_IMG', BASE_ADMIN . 'assets/img/noimage.png');
define('NO_IMG', BASE_ADMIN . 'assets/img/noimage.jpeg');

$css = new CssMinify($config['website']['debug-css'], $func);
$js  = new JsMinify($config['website']['debug-js'], $func);

$optsetting = $d->rawQueryOne("SELECT * FROM `tbl_setting` WHERE id = ?", [1]);
$optsetting_json = json_decode($optsetting['options'] ?? '{}', true);
$logo = $d->rawQueryOne("SELECT `file` FROM `tbl_photo` WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['logo']);
$favicon = $d->rawQueryOne("SELECT `file` FROM `tbl_photo` WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['favicon']);
$seo->set('url', $func->getPageURL());
$seo->set('favicon', !empty($favicon['file']) ? $func->getImageCustom(['file' => $favicon['file'], 'width' => 48, 'height' => 48, 'zc' => 1, 'src_only' => true]) : '');
$lang = $optsetting_json['lang_default'] ?? array_key_first($config['website']['lang']);
require_once LIBRARIES . "lang/web/$lang.php";
include_once LIBRARIES . 'router.php';
// SOURCES
require_once SOURCES . "allpage.php";
$sources_file = SOURCES . $sources . ".php";
if (file_exists($sources_file)) {
  include_once $sources_file;
  if (!isset($template)) {
    $template = $sources . "/" . $sources;
  }
} else {
  $func->abort_404();
}
include TEMPLATE . "index.php";
