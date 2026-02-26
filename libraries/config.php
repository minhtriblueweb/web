<?php
/* Timezone */
date_default_timezone_set('Asia/Ho_Chi_Minh');
$config = [
  // Base URL
  'base' => 'http://localhost/web/',
  'baseAdmin' => 'http://localhost/web/admin/',

  // Cấu hình website
  'website' => [
    'lang' => [
      'vi' => 'Tiếng Việt',
      'en' => 'Tiếng Anh'
    ],
    'lang-doc' => 'vi|en',
    'debug-css' => false,
    'debug-js' => false,
    'point-srcset' => array(380 => 2, 555 => 2, 768 => 3, 1024 => 4)
  ],

  // Cấu hình cơ sở dữ liệu
  'database' => [
    'type' => 'mysql',
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'web',
    'port' => 3306,
    'prefix' => 'tbl_',
    'charset' => 'utf8mb4',
    'server-name' => $_SERVER['SERVER_NAME'],
    'url' => '/web/'
  ]
];

/* Cấu hhnh base */
$configUrl = $config['database']['server-name'] . $config['database']['url'];
// $configBase = $http . $configUrl;

/* Token */
// define('TOKEN', md5(NN_CONTRACT . $config['database']['url']));

/* Path */
// define('ROOT', str_replace(basename(__DIR__), '', __DIR__));
// define('ASSET', $http . $configUrl);
// define('ADMIN', 'workspace');

/* Cấu hình login */
// $loginAdmin = $config['login']['workspace'];
// $loginMember = $config['login']['member'];
