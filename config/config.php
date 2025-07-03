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
    'debug-css' => false,
    'debug-js' => false
  ],

  // Cấu hình cơ sở dữ liệu (chuẩn dùng cho PDO/PDODb)
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
