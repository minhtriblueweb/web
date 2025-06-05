<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . '/web/lib/database.php';
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if(isset($_POST)){
        $table = (!empty($_POST['table'])) ? htmlspecialchars($_POST['table']) : '';
        $id = (!empty($_POST['id'])) ? (int)$_POST['id'] : '';
        $value = (!empty($_POST['value'])) ? htmlspecialchars($_POST['value']) : '';
        $query = "UPDATE `$table` SET numb = '$value' WHERE id = '$id'";
        $result = mysqli_query($conn, $query);
        return $result;
    }