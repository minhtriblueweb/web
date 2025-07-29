<?php
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
    'debug-css' => true,
    'debug-js' => true,
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
