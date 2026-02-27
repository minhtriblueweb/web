<?php
ob_start();
session_start();
define('LIBRARIES', '../libraries/');
define('SOURCES', './sources/');
define('TEMPLATE', './templates/');
define('UPLOADS', 'uploads/');
define('THUMB', 'thumb/');
define('LAYOUT', 'layout/');
define('THUMBS', 'thumbs');
define('JSONS', '../assets/jsons/');
define('WATERMARK', '../watermark');

require_once LIBRARIES . 'config.php';
require_once LIBRARIES . 'config-type.php';
require_once LIBRARIES . 'database.php';
require_once LIBRARIES . 'session.php';
require_once LIBRARIES . 'autoload.php';

new AutoLoad();
$d  = new Database();
$func = new Functions($d);
$login = new adminlogin($d, $func);
$statistic = new Statistic($d);
$flash = new Flash();

define('VERSION', $func->generateHash());
define('BASE', $config['base'] ?? '/');
define('BASE_ADMIN', $config['baseAdmin'] ?? '/admin/');
define('NO_IMG', BASE_ADMIN . 'assets/img/noimage.jpeg');

$optsetting = $d->rawQueryOne("SELECT * FROM `tbl_setting` WHERE id = ?", [1]);
$optsetting_json = json_decode($optsetting['options'] ?? '{}', true);
$lang = $optsetting_json['lang_default'] ?? array_key_first($config['website']['lang']);
$logo = $d->rawQueryOne("SELECT `file` FROM `tbl_photo` WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['logo']);
$favicon = $d->rawQueryOne("SELECT `file` FROM `tbl_photo` WHERE type = ? AND FIND_IN_SET('hienthi', status) LIMIT 1", ['favicon']);

require_once LIBRARIES . "lang/admin/$lang.php";
require_once LIBRARIES . "requick.php";

?>
<!DOCTYPE html>
<html lang="<?= $config['website']['lang-doc'] ?>">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="./assets/img/logo2-3962.png" rel="shortcut icon" type="image/x-icon" />
  <title>Admin - <?= $optsetting["name$lang"] ?? '' ?></title>
  <?php include TEMPLATE . LAYOUT . "css.php"; ?>
</head>

<body class="sidebar-mini hold-transition text-sm <?= $is_logged_in ? '' : 'login-com' ?>">
  <!-- Loader -->
  <?php if ($template === 'index' || $template === 'user/login') include TEMPLATE . LAYOUT . "loader.php"; ?>
  <!-- Wrapper -->
  <?php if ($is_logged_in) { ?>
    <div class="wrapper">
      <?php
      include TEMPLATE . LAYOUT . "header.php";
      include TEMPLATE . LAYOUT . "sidebar.php";
      ?>
      <div class="content-wrapper">
        <?php include TEMPLATE . $template . ".php" ?>
      </div>
      <?php include TEMPLATE . LAYOUT . "footer.php"; ?>
    </div>
  <?php } else {
    include TEMPLATE . "user/login.php";
  } ?>
  <!-- Js all -->
  <?php include TEMPLATE . LAYOUT . "js.php"; ?>
</body>

</html>
