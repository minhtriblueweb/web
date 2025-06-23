<?php
$baseDir = dirname(__DIR__); // trỏ về thư mục gốc "web"

// Định nghĩa đường dẫn
define('ROOT', $baseDir);
define('UPLOADS', 'uploads/');
define('BASE', $config['base'] ?? '/');
define('BASE_ADMIN', $config['baseAdmin'] ?? '/admin/');
define('NO_IMG', BASE_ADMIN . 'assets/img/noimage.png');
