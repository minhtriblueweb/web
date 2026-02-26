<?php
session_start();
define('LIBRARIES', '../libraries/');
define('THUMB', 'thumb/');
define('UPLOADS', 'uploads/');
define('WATERMARK', 'watermark');

require_once LIBRARIES . "config.php";
require_once LIBRARIES . "database.php";
require_once LIBRARIES . 'autoload.php';

new AutoLoad();
$d  = new Database();
$func = new Functions($d);
$cart = new Cart($d);

$optsetting = $d->rawQueryOne("SELECT * FROM `tbl_setting` WHERE id = ?", [1]);
$optsetting_json = json_decode($optsetting['options'] ?? '{}', true);
$lang = $optsetting_json['lang_default'] ?? array_key_first($config['website']['lang']);
require_once LIBRARIES . "lang/web/$lang.php";
define('BASE', $config['base'] ?? '/');
/* Slug lang */
$sluglang = 'slugvi';
