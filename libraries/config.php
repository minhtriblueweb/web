<?php
if (!defined('LIBRARIES')) die("Error");

/* Timezone */
date_default_timezone_set('Asia/Ho_Chi_Minh');

/* Cấu hình coder */
define('MT_CONTRACT', 'MSHD');
define('MT_AUTHOR', 'info@vndts.vn');

/* Cấu hình chung */
/* PHP Ver: 8.2.4 | Update: 22-7-2023 */
$config = array(
  'author' => array(
    'name' => 'xxx',
    'email' => 'info@vndts.vn',
    'timefinish' => '2024'
  ),
  'arrayDomainSSL' => array(),
  'database' => array(
    // 'server-name' => $_SERVER["SERVER_NAME"],
    'server-name' => $_SERVER["SERVER_NAME"] ?? 'localhost',
    'url' => '/web/',
    'type' => 'mysql',
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'web',
    'port' => 3306,
    'prefix' => 'table_',
    'charset' => 'utf8mb4'
  ),
  'website' => array(
    'error-reporting' => true,
    'secret' => 'minhtri',
    'salt' => 'thietkeweb',
    'debug-developer' => false,
    'debug-css' => true,
    'debug-js' => true,
    'index' => false,
    'linkredirect' => false,
    'image' => array(
      'hasWebp' => false,
    ),
    'noseo' => array('user', 'order', 'search'), //source
    'video' => array(
      'extension' => array('mp4', 'mkv'),
      'poster' => array(
        'width' => 700,
        'height' => 610,
        'extension' => '.jpg|.png|.jpeg'
      ),
      'allow-size' => '100Mb',
      'max-size' => 100 * 1024 * 1024
    ),
    'upload' => array(
      'max-width' => 1600,
      'max-height' => 1600
    ),
    'adminlang' => array(
      'active' => false,
      'key' => array('vi', 'en'),
      'lang' => array(
        'vi' => 'Tiếng Việt',
        'en' => 'Tiếng Anh'
      )
    ),
    'lang' => array(
      'vi' => 'Tiếng Việt',
      'en' => 'Tiếng Anh'
    ),
    'lang-doc' => 'vi|en',
    'slug' => array(
      'vi' => 'Tiếng Việt',
      'en' => 'Tiếng Anh'
    ),
    'seo' => array(
      'vi' => 'Tiếng Việt',
      'en' => 'Tiếng Anh'
    ),
    'comlang' => array(
      "gioi-thieu" => array("vi" => "gioi-thieu", "en" => "about-us"),
      "san-pham" => array("vi" => "san-pham", "en" => "product"),
      "tin-tuc" => array("vi" => "tin-tuc", "en" => "news"),
      "tuyen-dung" => array("vi" => "tuyen-dung", "en" => "recruitment"),
      "thu-vien-anh" => array("vi" => "thu-vien-anh", "en" => "gallery"),
      "video" => array("vi" => "video", "en" => "video"),
      "lien-he" => array("vi" => "lien-he", "en" => "contact")
    )
  ),
  'order' => array(
    'ship' => true
  ),
  'login' => array(
    'admin' => 'LoginAdmin' . MT_CONTRACT,
    'member' => 'LoginMember' . MT_CONTRACT,
    'attempt' => 5,
    'delay' => 15
  ),
  'googleAPI' => array(
    'recaptcha' => array(
      'active' => true,
      'urlapi' => 'https://www.google.com/recaptcha/api/siteverify',
      'sitekey' => '6LefElMrAAAAAOqPL6EgGolZT6GKLssAO6OwtCgP',
      'secretkey' => '6LefElMrAAAAALX8pxfywPICIVtl5nOgD00UqL2V'
    ),
    'recaptchaV2' => array(
      'active' => true,
      'urlapi' => 'https://www.google.com/recaptcha/api/siteverify',
      'sitekey' => '6LeLORIrAAAAANZjgEG4w_R5OoeTXOFwcvQCyJbe',
      'secretkey' => '6LeLORIrAAAAAGepwiEub23UqXBwXHZuwhFlbvlj'
    )
  ),
  'oneSignal' => array(
    'active' => false,
    'id' => 'af12ae0e-cfb7-41d0-91d8-8997fca889f8',
    'restId' => 'MWFmZGVhMzYtY2U0Zi00MjA0LTg0ODEtZWFkZTZlNmM1MDg4'
  )
);

/* Error reporting */
error_reporting(($config['website']['error-reporting']) ? E_ALL : 0);
/* Cấu hình http */
if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $http = 'https://';
} else {
  $http = 'http://';
}

/* Redirect http/https */
// if (!count($config['arrayDomainSSL']) && $http == 'https://') {
//   $host = $_SERVER['HTTP_HOST'];
//   $request_uri = $_SERVER['REQUEST_URI'];
//   $good_url = "http://" . $host . $request_uri;
//   header("HTTP/1.1 301 Moved Permanently");
//   header("Location: $good_url");
//   exit;
// }

/* CheckSSL */
// if (count($config['arrayDomainSSL'])) {
//   include LIBRARIES . "checkSSL.php";
// }
// if (
//   count($config['arrayDomainSSL']) &&
//   ($_SERVER['HTTP_HOST'] ?? '') !== 'localhost'
// ) {
//   include LIBRARIES . "checkSSL.php";
// }
/* Cấu hnh base */
$configUrl = $config['database']['server-name'] . $config['database']['url'];
$configBase = $http . $configUrl;

/* Token */
define('TOKEN', md5(MT_CONTRACT . $config['database']['url']));

/* Path */
define('ROOT', str_replace(basename(__DIR__), '', __DIR__));
define('ASSET', $http . $configUrl);
define('ADMIN', 'admin');

/* Cấu hình login */
$loginAdmin = $config['login']['admin'];
$loginMember = $config['login']['member'];

/* Cấu hình upload */
require_once LIBRARIES . "constant.php";
