<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/web/lib/database.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!empty($_POST["id"])) {
    $level = (!empty($_POST["level"])) ? htmlspecialchars($_POST["level"]) : 0;
    $table = (!empty($_POST["table"])) ? htmlspecialchars($_POST["table"]) : '';
    $id = (!empty($_POST["id"])) ? htmlspecialchars($_POST["id"]) : 0;
    $type = (!empty($_POST["type"])) ? htmlspecialchars($_POST["type"]) : '';
    $row = null;

    switch ($level) {
        case '0':
            $id_temp = "id_list";
            break;

        case '1':
            $id_temp = "id_cat";
            break;

        case '2':
            $id_temp = "id_item";
            break;

        default:
            echo 'error ajax';
            exit();
            break;
    }

    if ($id) {
        $result = mysqli_query($conn, "SELECT namevi, id FROM `$table` WHERE `$id_temp` = '$id' ORDER BY numb, id DESC");
        if ($result) {
            $str = '<option value="0">Chọn danh mục</option>';
            while ($kg = $result->fetch_assoc()) {
                $str .= '<option value="' . $kg["id"] . '">' . $kg["namevi"] . '</option>';
            }
        } else {
            $str = '<option value="0">Chọn danh mục</option>';
        }
    } else {
        $str = '<option value="0">Chọn danh mục</option>';
    }
} else {
    $str = '<option value="0">Chọn danh mục</option>';
}

echo $str;
