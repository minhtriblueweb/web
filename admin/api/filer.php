<?php
session_start();
require_once __DIR__ . '/../init.php';
$db = new Database();

// Ngăn chặn truy cập trực tiếp
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit('Forbidden');
}

// Nhận dữ liệu POST
$id_parent = $_POST['id_parent'] ?? null;

// Kiểm tra dữ liệu bắt buộc
if (!$id_parent || !$com || !$type) {
  http_response_code(400);
  exit('Missing required parameters.');
}

// Truy vấn danh sách tệp tin từ bảng files
$id_parent = (int)$id_parent;

$query = "SELECT * FROM tbl_gallery WHERE id_parent = '$id_parent'";
$result = $db->select($query);

if ($result) {
  $html = '';
  while ($row = $result->fetch_assoc()) {
    $html .= '
      <li class="jFiler-item">
        <div class="jFiler-item-container">
          <div class="jFiler-item-inner">
            <div class="jFiler-item-thumb">
              <img src="' . htmlspecialchars($row['filepath']) . '" alt="' . htmlspecialchars($row['filename']) . '" />
            </div>
            <div class="jFiler-item-info">
              <span class="jFiler-item-title">' . htmlspecialchars($row['filename']) . '</span>
            </div>
          </div>
        </div>
      </li>';
  }
  echo $html;
} else {
  echo "Query failed.";
}





// $id = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
// $id_parent = (!empty($_POST['id_parent'])) ? htmlspecialchars($_POST['id_parent']) : 0;
// $folder = (!empty($_POST['folder'])) ? htmlspecialchars($_POST['folder']) : '';
// $info = (!empty($_POST['info'])) ? htmlspecialchars($_POST['info']) : '';
// $value = (!empty($_POST['value'])) ? htmlspecialchars($_POST['value']) : '';
// $listid = (!empty($_POST['listid'])) ? $func->sanitize($_POST['listid']) : '';
// $com = (!empty($_POST['com'])) ? htmlspecialchars($_POST['com']) : '';
// $kind = (!empty($_POST['kind'])) ? htmlspecialchars($_POST['kind']) : '';
// $type = (!empty($_POST['type'])) ? htmlspecialchars($_POST['type']) : '';
// $colfiler = (!empty($_POST['colfiler'])) ? htmlspecialchars($_POST['colfiler']) : '';
// $cmd = (!empty($_POST['cmd'])) ? htmlspecialchars($_POST['cmd']) : '';
// $hash = (!empty($_POST['hash'])) ? htmlspecialchars($_POST['hash']) : '';
// $gallery = null;

// if ($cmd == 'refresh' && ($id_parent > 0 || $hash != '')) {
//     if ($id_parent) {
//         $gallery = $d->rawQuery("select numb, id, photo, namevi from #_gallery where id_parent = ? and com = ? and type = ? and kind = ? and val = ? order by numb,id desc", array($id_parent, $com, $type, $kind, $type));
//     } else if ($hash != '') {
//         $gallery = $d->rawQuery("select numb, id, photo, namevi from #_gallery where hash = ? and com = ? and type = ? and kind = ? and val = ? order by numb,id desc", array($hash, $com, $type, $kind, $type));
//     }

//     if ($gallery) {
//         for ($i = 0; $i < count($gallery); $i++) {
//             echo $func->galleryFiler($gallery[$i]['numb'], $gallery[$i]['id'], $gallery[$i]['photo'], $gallery[$i]['namevi'], $com, $colfiler);
//         }

//         $cache->delete();
//     }
// } else if ($cmd == 'info' && $id > 0 && ($id_parent > 0 || $hash != '')) {
//     if ($info == 'numb') {
//         $data['numb'] = $value;
//     }

//     if ($info == 'name') {
//         $data['namevi'] = $value;
//     }

//     $d->where('id', $id);
//     if ($d->update('gallery', $data)) {
//         if ($id_parent) {
//             $gallery = $d->rawQuery("select numb, id, photo, namevi from #_gallery where id_parent = ? and com = ? and type = ? and kind = ? and val = ? order by numb,id desc", array($id_parent, $com, $type, $kind, $type));
//         } else if ($hash != '') {
//             $gallery = $d->rawQuery("select numb, id, photo, namevi from #_gallery where hash = ? and com = ? and type = ? and kind = ? and val = ? order by numb,id desc", array($hash, $com, $type, $kind, $type));
//         }

//         if ($gallery) {
//             for ($i = 0; $i < count($gallery); $i++) {
//                 echo $func->galleryFiler($gallery[$i]['numb'], $gallery[$i]['id'], $gallery[$i]['photo'], $gallery[$i]['namevi'], $com, $colfiler);
//             }

//             $cache->delete();
//         }
//     }
// } else if (($cmd == 'updateNumb' && $id_parent > 0 && $listid) || ($hash != '')) {
//     if ($id_parent) {
//         $row = $d->rawQuery("select id, numb from #_gallery where id_parent = ? and com = ? and type = ? and kind = ? and val = ? order by numb,id desc", array($id_parent, $com, $type, $kind, $type));
//     } else if ($hash != '') {
//         $row = $d->rawQuery("select id, numb from #_gallery where hash = ? and com = ? and type = ? and kind = ? and val = ? order by numb,id desc", array($hash, $com, $type, $kind, $type));
//     }

//     if ($listid) {
//         for ($i = 0; $i < count($listid); $i++) {
//             $arrId[] = $listid[$i];
//             $arrNumb[] = $row[$i]['numb'];

//             $data['numb'] = $row[$i]['numb'];

//             $d->where('id', $listid[$i]);
//             $d->update('gallery', $data);
//         }

//         $cache->delete();
//         $data = array('id' => $arrId, 'numb' => $arrNumb);
//         echo json_encode($data);
//     }
// } else if ($cmd == 'delete' && $id > 0) {
//     $row = $d->rawQueryOne("select photo from #_gallery where id = ? limit 0,1", array($id));

//     $path = "../../upload/" . $folder . "/" . $row['photo'];

//     $func->deleteFile($path);

//     $d->rawQuery("delete from #_gallery where id = ?", array($id));

//     $cache->delete();
// } else if ($cmd == 'delete-all' && $listid != '') {
//     $listid = explode(",", $listid);
//     $cols = ["id", "photo"];
//     $d->where('id', $listid, 'IN');
//     $row = $d->get("gallery", null, $cols);

//     for ($i = 0; $i < count($row); $i++) {
//         $path = "../../upload/" . $folder . "/" . $row[$i]['photo'];

//         $func->deleteFile($path);

//         $id = $row[$i]['id'];
//         $d->rawQuery("delete from #_gallery where id = ?", array($id));
//     }

//     $cache->delete();
// }
