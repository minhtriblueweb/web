<?php
session_start();
define('LIBRARIES', '../../libraries/');
define('SOURCES', '../sources/');
define('THUMBS', 'thumbs');
define('UPLOADS', '../uploads/');

require_once LIBRARIES . "config.php";
require_once LIBRARIES . "database.php";
require_once LIBRARIES . 'autoload.php';
new AutoLoad();
$d  = new Database();
$func = new Functions($d);
$lang = (json_decode($d->rawQueryOne("SELECT options FROM tbl_setting WHERE id = ?", [1])['options'] ?? '{}',true)['lang_default']) ?? array_key_first($config['website']['lang']);
require_once LIBRARIES . "lang/admin/$lang.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit('Forbidden');
}
