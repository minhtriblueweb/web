<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/web/lib/database.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$dataSlug = array();
$dataSlug['slug'] = (!empty($_POST['slug'])) ? trim(htmlspecialchars($_POST['slug'])) : '';
$dataSlug['id'] = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
$dataSlug['copy'] = (!empty($_POST['copy'])) ? htmlspecialchars($_POST['copy']) : 0;

echo ($func->checkSlug($dataSlug) == 'exist') ? 0 : 1;
