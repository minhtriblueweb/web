<?php
ob_start();
require_once __DIR__ . "/../config/autoload.php";
require_once __DIR__ . "/../config/type/config-type.php";
require_once __DIR__ . "/../lib/lang/admin/" . $lang . ".php";
require_once __DIR__ . "/../lib/session.php";
require_once __DIR__ . "/../classes/class.adminlogin.php";
require_once __DIR__ . "/../lib/validation.php";
