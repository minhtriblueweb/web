<?php
require_once '../init.php';
Session::init();
Session::destroy();
header('Location: dang-nhap');
exit();
