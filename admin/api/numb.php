<?php
session_start();
require_once __DIR__ . '/../init.php';
$db = new Database();
if (isset($_POST)) {
  $table = (!empty($_POST['table'])) ? htmlspecialchars($_POST['table']) : '';
  $id = (!empty($_POST['id'])) ? (int)$_POST['id'] : '';
  $value = (!empty($_POST['value'])) ? htmlspecialchars($_POST['value']) : '';
  $query = "UPDATE `$table` SET numb = '$value' WHERE id = '$id'";
  $result = $db->update($query);
  return $result;
}
