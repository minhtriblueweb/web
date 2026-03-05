<?php
ob_start();
session_start();
define('LIBRARIES', './libraries/');
define('SOURCES', './sources/');
define('LAYOUT', 'layout/');
define('THUMBS', 'thumbs');
define('JSONS', './assets/jsons/');
define('WATERMARK', 'watermark');
define('TEMPLATE', 'templates/');
define('THUMB', 'thumb/');
define('UPLOADS', 'uploads/');
define("UPLOAD", "../upload/");
define("UPLOAD_L", "upload/");

require_once LIBRARIES . 'config.php';
require_once LIBRARIES . 'config-type.php';
require_once LIBRARIES . 'database.php';
require_once LIBRARIES . 'autoload.php';

new AutoLoad();
$injection = new AntiSQLInjection();
$d  = new Database();
$router = new AltoRouter();
$cache = new Cache($d);
$func = new Functions($d);
$flash = new Flash();
$seo = new Seo($d);
$breadcr = new Breadcrumbs();
$statistic = new Statistic($d, $cache);
$cart = new Cart($d);
$addons = new AddonsOnline();
$css = new CssMinify($config['website']['debug-css'], $func);
$js  = new JsMinify($config['website']['debug-js'], $func);
define('VERSION', $func->generateHash());

include_once LIBRARIES . 'router.php';
include TEMPLATE . "index.php";
