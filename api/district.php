<?php
include_once '../config/autoload.php';

$id_city = (!empty($_POST['id_city'])) ? htmlspecialchars($_POST['id_city']) : 0;
$district = null;
if ($id_city) $district = $db->rawQuery("SELECT name, id FROM `tbl_district` WHERE id_city = ?
  AND FIND_IN_SET('hienthi', status) ORDER BY numb ASC", array($id_city));

if ($district) { ?>
    <option value=""><?= quanhuyen ?></option>
    <?php foreach ($district as $k => $v) { ?>
        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
    <?php }
} else { ?>
    <option value=""><?= quanhuyen ?></option>
<?php }
?>
