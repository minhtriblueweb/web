<?php
session_start();
require_once __DIR__ . '/../init.php';
$db = new Database();
if (isset($_POST)) {
  $table = (!empty($_POST['table'])) ? htmlspecialchars($_POST['table']) : '';
  $id = (!empty($_POST['id'])) ? (int)$_POST['id'] : '';
  $attr = (!empty($_POST['attr'])) ? htmlspecialchars($_POST['attr']) : '';
  $type = (!empty($_POST['type'])) ? htmlspecialchars($_POST['type']) : '';
  $query = "UPDATE `$table` SET `$type` = '$attr' WHERE id = '$id'";
  $result = $db->update($query);
  return $result;
}
