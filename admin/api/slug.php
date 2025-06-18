<?php
session_start();
require_once __DIR__ . '/../init.php';
$db = new Database();
$dataSlug = array();
$dataSlug['slug'] = !empty($_POST['slug']) ? trim(htmlspecialchars($_POST['slug'])) : '';
$dataSlug['table'] = !empty($_POST['table']) ? htmlspecialchars($_POST['table']) : '';
$dataSlug['id'] = !empty($_POST['id']) ? (int)$_POST['id'] : '';
$check = $functions->isSlugviDuplicated($dataSlug['slug'], $dataSlug['table'], $dataSlug['id']);
echo ($check !== false) ? 0 : 1;
exit;
