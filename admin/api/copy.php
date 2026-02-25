<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(403);
  exit('Forbidden');
}
require_once __DIR__ . '/../init.php';

if (!empty($_POST['id'])) {
  $id = (!empty($_POST['id'])) ? htmlspecialchars($_POST['id']) : 0;
  $table = (!empty($_POST['table'])) ? htmlspecialchars($_POST['table']) : '';
  $copyimg = (!empty($_POST['copyimg'])) ? htmlspecialchars($_POST['copyimg']) : false;

  if ($id) {
    $item = $db->rawQueryOne("select * from `$table` where id = ? limit 0,1", array($id));
  }

  function createCopy($titleCopy = '', $titleSlug = '', $table = '')
  {
    global $db, $fn, $config, $item, $copyimg;

    $check = $db->rawQueryOne("select id from `$table` where slugvi = ? or slugen = ? limit 0,1", array($titleSlug, $titleSlug));

    if (!empty($check['id'])) {
      $titleCopy .= " (1)";
      $titleSlug = $fn->changeTitle($titleCopy);
      createCopy($titleCopy, $titleSlug, $table);
    } else {
      foreach ($config['website']['lang'] as $key => $value) {
        $dataCopy['desc' . $key] = $item['desc' . $key];
        $dataCopy['content' . $key] = $item['content' . $key];
      }
      if ($copyimg) {
        $dataCopy['file'] = $fn->copyImg($item['file']);
      }
      $comTable = str_replace('tbl_', '', $table);
      $dataCopy['namevi'] = $titleCopy;
      $dataCopy['slugvi'] = !empty($config[$comTable][$item['type']]['slug']) ? $fn->changeTitle($dataCopy['namevi']) : '';
      $dataCopy['id_list'] = $item['id_list'];
      $dataCopy['id_cat'] = $item['id_cat'];
      $dataCopy['id_item'] = $item['id_item'];
      $dataCopy['id_sub'] = $item['id_sub'];

      if ($comTable == 'product') {
        $dataCopy['id_brand'] = $item['id_brand'];
        $dataCopy['code'] = $item['code'];
        $dataCopy['regular_price'] = $item['regular_price'];
        $dataCopy['discount'] = $item['discount'];
        $dataCopy['sale_price'] = $item['sale_price'];
      }

      $dataCopy['numb'] = 0;
      $dataCopy['status'] = '';
      $dataCopy['type'] = $item['type'];
      $dataCopy['date_created'] = date('Y-m-d H:i:s');
      $db->insert($table, $dataCopy);
      // if ($d->insert($table, $dataCopy)) {
      //   $gallery = $d->rawQuery("select * from #_gallery where id_parent = ? and com = ? and type = ? and kind = ? and val = ? order by numb,id desc", array($item['id'], $com, $item['type'], 'man', $item['type']));
      // }
      // $cache->delete();
    }
  }

  if (!empty($item['id'])) {
    $title = $item['namevi'];
    $titleSlug = $item['slugvi'];
    createCopy($title, $titleSlug, $table);
  }
}

// $id    = (int)$_POST['id'];
// $table = $_POST['table'];

// $item = $db->rawQueryOne("SELECT * FROM `$table` WHERE id = ? LIMIT 1", [$id]);
// if (!$item) {
//   echo json_encode(['success' => false, 'message' => 'Không tìm thấy dữ liệu']);
//   exit;
// }

// function getExistingColumns($table)
// {
//   global $db;
//   $result = $db->rawQuery("SHOW COLUMNS FROM `$table`");
//   if (!$result) return [];
//   $columns = [];
//   if ($result instanceof mysqli_result) {
//     while ($row = $result->fetch_assoc()) {
//       $columns[] = $row['Field'];
//     }
//     return $columns;
//   }
//   if (is_array($result)) {
//     return array_column($result, 'Field');
//   }

//   return [];
// }
// function makeUniqueSlugChain($baseSlug, $table, $lang = 'vi', $excludeId = 0)
// {
//   global $db;
//   $slugCol = 'slug' . $lang;
//   $slug = $baseSlug;
//   $i = 1;

//   while (true) {
//     $exists = $db->rawQueryOne(
//       "SELECT id FROM `$table` WHERE `$slugCol` = ? AND id != ? LIMIT 1",
//       [$slug, $excludeId]
//     );
//     if (empty($exists)) break;
//     $slug = $baseSlug . '-' . $i++;
//   }
//   return $slug;
// }

// function createCopy($item, $table)
// {
//   global $db, $config;

//   $langs = array_keys($config['website']['lang'] ?? ['vi']);
//   $data = [];

//   foreach ($langs as $lang) {
//     $oldName = $item['name' . $lang] ?? '';
//     $oldSlug = $item['slug' . $lang] ?? '';

//     $data['name' . $lang] = trim($oldName . ' (1)');
//     $data['slug' . $lang] = makeUniqueSlugChain(
//       $oldSlug ?: preg_replace('/\s+/', '-', strtolower($oldName ?: 'item')),
//       $table,
//       $lang
//     );

//     $data['desc' . $lang]    = $item['desc' . $lang] ?? '';
//     $data['content' . $lang] = $item['content' . $lang] ?? '';
//   }

//   $data += [
//     'id_list' => $item['id_list'] ?? 0,
//     'id_cat'  => $item['id_cat'] ?? 0,
//     'id_item'  => $item['id_item'] ?? 0,
//     'id_sub'  => $item['id_sub'] ?? 0,
//     'status'  => '',
//     'type'    => $item['type'] ?? '',
//     'date_created' => date('Y-m-d H:i:s')
//   ];

//   if ($table === 'tbl_product') {
//     $data += [
//       'id_brand' => $item['id_brand'] ?? 0,
//       'code' => $item['code'] ?? '',
//       'regular_price' => $item['regular_price'] ?? 0,
//       'discount' => $item['discount'] ?? 0,
//       'sale_price' => $item['sale_price'] ?? 0,
//       'numb' => 0
//     ];
//   }

//   $existing = getExistingColumns($table);
//   $insert = array_intersect_key($data, array_flip($existing));

//   return !empty($insert) && $db->insert($table, $insert);
// }

// $ok = createCopy($item, $table);

// echo json_encode([
//   'success' => (bool)$ok,
//   'message' => $ok ? 'Sao chép thành công' : 'Sao chép thất bại'
// ]);
