<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit('Forbidden');
}

require_once __DIR__ . '/../init.php'; // Gọi hệ thống (Database, Functions, config...)
require_once LIBRARIES . "config-type.php"; // Load cấu hình các loại (product, news,...)

// Khởi tạo response mặc định
$response = ['success' => true, 'msg' => 'Upload thành công'];

// Lấy dữ liệu POST
$flag = true;
$params = [];
$paramString = $_POST['params'] ?? null;
if ($paramString) parse_str(base64_decode(addslashes($paramString)), $params);

$id = (int)($params['id'] ?? 0);
$com = $params['com'] ?? '';
$type = $params['type'] ?? '';
$hash = addslashes($_POST['hash'] ?? '');
$numb = (int)($_POST['numb'] ?? 0);

// Phân tích act để lấy kind
$act = $params['act'] ?? '';
$actParts = explode('_', $act);
$ex = count($actParts) > 1 ? end($actParts) : '';
$kind = 'man' . ($ex ? "_$ex" : '');

// Lấy file upload
$myFile = $_FILES['files'] ?? null;
if (!$myFile || empty($myFile['name'][0])) {
  echo json_encode(['success' => false, 'msg' => 'Không có file được tải lên']);
  exit;
}

// Chuyển file đầu tiên sang định dạng $_FILES['file']
$_FILES['file'] = [
  'name' => $myFile['name'][0],
  'type' => $myFile['type'][0],
  'tmp_name' => $myFile['tmp_name'][0],
  'error' => $myFile['error'][0],
  'size' => $myFile['size'][0]
];

// Xác định đường dẫn upload theo $com
$uploadDir = $upload_paths[$com] ?? '';

// Tên file chuẩn hoá
$file_name = $func->uploadName($_FILES['file']['name'] ?? 'image');

// Nếu type tồn tại trong config (tức là cho phép upload)
if (!empty($config[$com][$type]['img_type'])) {
  $dataInsert = [
    'id_parent'     => $id,
    'com'           => $com,
    'type'          => $type,
    'kind'          => $kind,
    'val'           => $type,
    'namevi'        => '',
    'status'        => 'hienthi',
    'date_created'  => time()
  ];

  if (!$id) {
    $dataInsert['hash'] = $hash;
  }

  // Tính numb mới
  $max = $d->rawQueryOne("SELECT MAX(numb) as max_numb FROM #_gallery WHERE com = ? AND type = ? AND kind = ? AND val = ? AND id_parent = ?", [$com, $type, $kind, $type, $id]);
  $dataInsert['numb'] = (int)($max['max_numb'] ?? 0) + 1;

  // Insert dữ liệu
  if ($d->insert('gallery', $dataInsert)) {
    $id_insert = $d->getLastInsertId();

    if ($func->hasFile("file")) {
      if ($photo = $func->uploadImage("file", $config[$com][$type]['img_type'], "../" . $uploadDir, $file_name)) {
        $d->where('id', $id_insert);
        $d->update('gallery', ['photo' => $photo]);
      }
    }
  } else {
    $flag = false;
  }
} else {
  $flag = false;
}

if (!$flag) {
  $response = ['success' => false, 'msg' => 'Upload thất bại'];
}

echo json_encode($response);
