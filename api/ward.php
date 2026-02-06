<?php
include_once '../config/autoload.php';

$id_district = (!empty($_POST['id_district'])) ? htmlspecialchars($_POST['id_district']) : 0;
$ward = null;
if ($id_district) $ward = $db->rawQuery("SELECT name, id FROM `tbl_ward` WHERE id_district = ? and find_in_set('hienthi',status) ORDER BY numb ASC", array($id_district));
if ($ward) { ?>
    <option value=""><?= phuongxa ?></option>
    <?php foreach ($ward as $k => $v) { ?>
        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
    <?php }
} else { ?>
    <option value=""><?= phuongxa ?></option>
<?php }
?>
