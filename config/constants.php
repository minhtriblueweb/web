<?php
define('ROOT', dirname(__DIR__));
define('VERSION', $fn->generateHash());
define('UPLOADS', 'uploads/');
define('BASE', $config['base'] ?? '/');
define('BASE_ADMIN', $config['baseAdmin'] ?? '/admin/');
define('NO_IMG', BASE_ADMIN . 'assets/img/noimage.png');
