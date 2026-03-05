<?php
session_start();
define('LIBRARIES', '../libraries/');
define('SOURCES', './sources/');
define('TEMPLATE', './templates/');
define('LAYOUT', 'layout/');
define('THUMBS', 'thumbs');
define('JSONS', '../assets/jsons/');
define('WATERMARK', '../watermark');

if (empty($_SESSION['xsrf_token'])) {
  $_SESSION['xsrf_token'] = bin2hex(random_bytes(32));
}

require_once LIBRARIES . 'config.php';
require_once LIBRARIES . 'database.php';
require_once LIBRARIES . 'autoload.php';

new AutoLoad();
$d  = new Database();
// $d = new PDODb($config['database']);
$cache = new Cache($d);
$func = new Functions($d, $cache);
$seo = new Seo($d);
// $login = new adminlogin($d, $func);
$statistic = new Statistic($d, $cache);
$flash = new Flash();

/* Config type */
require_once LIBRARIES . "config-type.php";

/* Check HTTP */
$func->checkHTTP($http, $config['arrayDomainSSL'], $configBase, $configUrl);

/* Setting */
$logo = $d->rawQueryOne("select id, photo, options from table_photo where type = ? and act = ? limit 0,1", array('logo', 'photo_static'));
$setting = $d->rawQueryOne("select * from #_setting limit 0,1");
$optsetting = (isset($setting['options']) && $setting['options'] != '') ? json_decode($setting['options'], true) : null;
$lang = $optsetting['lang_default'] ?? array_key_first($config['website']['lang']);
require_once LIBRARIES . "lang/admin/$lang.php";

/* Requick */
require_once LIBRARIES . "requick.php";
$wtm = $d->rawQueryOne("select status, photo, options from #_photo where type = ? and act = ? and find_in_set('hienthi',status) limit 0,1", array('watermark', 'photo_static'));
if (!empty($wtm['photo'])) {
  $_SESSION['waterM']['actived'] = 'on';
  $_SESSION['waterM']['vitrix'] = 'right';
  $_SESSION['waterM']['vitriy'] = 'bottom';
  $_SESSION['waterM']['imglogowatermark'] = 'photo/' . $wtm['photo'];
}
?>
<!DOCTYPE html>
<html lang="<?= $config['website']['lang-doc'] ?>">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="./assets/img/logo2-3962.png" rel="shortcut icon" type="image/x-icon" />
  <title>Admin - <?= $setting["name$lang"] ?? '' ?></title>
  <?php include TEMPLATE . LAYOUT . "css.php"; ?>
</head>

<body class="sidebar-mini hold-transition text-sm <?= (!isset($_SESSION[$loginAdmin]['active']) || $_SESSION[$loginAdmin]['active'] == false) ? 'login-page' : '' ?>">
  <!-- Loader -->
  <?php if ($template == 'index' || $template == 'user/login') include TEMPLATE . LAYOUT . "loader.php"; ?>

  <!-- Wrapper -->
  <?php if (isset($_SESSION[$loginAdmin]['active']) && ($_SESSION[$loginAdmin]['active'] == true)) { ?>
    <div class="wrapper">
      <?php
      include TEMPLATE . LAYOUT . "header.php";
      include TEMPLATE . LAYOUT . "sidebar.php";
      ?>
      <div class="content-wrapper">
        <?php if ($alertlogin) { ?>
          <section class="content">
            <div class="container-fluid">
              <div class="alert my-alert alert-warning alert-dismissible text-sm bg-gradient-warning mt-3 mb-0">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fas fa-exclamation-triangle"></i> <?= $alertlogin ?>
              </div>
            </div>
          </section>
        <?php } ?>
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
