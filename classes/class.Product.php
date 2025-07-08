<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class product
{
  private $db;
  private $fn;
  private $seo;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
    $this->seo = new seo();
  }
  public function delete_gallery(int $id): void
  {
    $table = 'tbl_gallery';
    $id = (int)$id;

    // Lấy dữ liệu hình ảnh
    $row = $this->db->rawQueryOne("SELECT file, id_parent FROM `$table` WHERE id = ?", [$id]);
    if (!$row) {
      $this->fn->transfer("Hình ảnh không tồn tại!", $this->fn->getRedirectPath(['table' => $table]), false);
    }

    // Xoá file vật lý nếu tồn tại
    if (!empty($row['file'])) {
      $this->fn->deleteFile(UPLOADS . $row['file']);
    }

    // Xoá bản ghi
    $deleted = $this->db->execute("DELETE FROM `$table` WHERE id = ?", [$id]);

    // Điều hướng sau khi xóa
    $this->fn->transfer(
      $deleted ? "Xóa ảnh thành công!" : "Xóa ảnh thất bại!",
      $this->fn->getRedirectPath([
        'table' => $table,
        'id_parent' => isset($row['id_parent']) ? (int)$row['id_parent'] : 0
      ]),
      $deleted
    );
  }

  public function deleteMultiple_gallery(string $listid)
  {
    $ids = array_filter(array_map('intval', explode(',', $listid)));
    $table = 'tbl_gallery';

    if (empty($ids)) {
      $this->fn->transfer("Danh sách ID không hợp lệ!", $this->fn->getRedirectPath(['table' => $table]), false);
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $rows = $this->db->rawQuery("SELECT id, file, id_parent FROM `$table` WHERE id IN ($placeholders)", $ids);

    $id_parent = 0;
    foreach ($rows as $row) {
      if (!empty($row['file'])) {
        $this->fn->deleteFile(UPLOADS . $row['file']);
      }
      $this->db->execute("DELETE FROM tbl_seo WHERE id_parent = ?", [$row['id']]);
      $id_parent = $row['id_parent'] ?? $id_parent;
    }

    $deleted = $this->db->execute("DELETE FROM `$table` WHERE id IN ($placeholders)", $ids);

    $this->fn->transfer(
      $deleted ? "Xóa ảnh thành công!" : "Xóa ảnh thất bại!",
      $this->fn->getRedirectPath(['table' => $table, 'id_parent' => $id_parent]),
      $deleted
    );
  }

  public function upload_gallery($data, $files, $id, $id_parent)
  {
    $id = (int)$id;
    $id_parent = (int)$id_parent;
    $table = 'tbl_gallery';
    $parent = $this->db->rawQueryOne("SELECT namevi FROM tbl_product WHERE id = ? LIMIT 1", [$id_parent]);
    $parent_name = $parent['namevi'] ?? '';
    $data_prepared = [
      'numb' => $data['numb'] ?? 0,
      'status' => !empty($data['hienthi']) ? 'hienthi' : ''
    ];
    $thumb_filename = '';
    if (!empty($files['file']['name']) && !empty($files['file']['tmp_name'])) {
      $old_file_path = '';
      $old = $this->db->rawQueryOne("SELECT file FROM `$table` WHERE id = ?", [$id]);
      if (!empty($old['file'])) {
        $old_file_path = UPLOADS . $old['file'];
      }
      $width = (int)($data['thumb_width'] ?? 0);
      $height = (int)($data['thumb_height'] ?? 0);
      $thumb_size = $width . 'x' . $height;
      $thumb_filename = $this->fn->Upload([
        'file' => $files['file'],
        'custom_name' => $parent_name,
        'thumb' => $thumb_size,
        'old_file_path' => $old_file_path,
        'watermark' => true,
        'convert_webp' => true
      ]);
      if (empty($thumb_filename)) {
        return "Lỗi upload file!";
      }
      $data_prepared['thumb'] = json_encode(['w' => $width, 'h' => $height]);
    }
    $fields = [];
    $params = [];
    foreach ($data_prepared as $key => $val) {
      $fields[] = "`$key` = ?";
      $params[] = $val;
    }
    if ($thumb_filename) {
      $fields[] = "`file` = ?";
      $params[] = $thumb_filename;
    }
    $params[] = $id;
    $result = $this->db->execute("UPDATE `$table` SET " . implode(', ', $fields) . " WHERE id = ?", $params);
    $this->fn->transfer(
      $result ? "Cập nhật hình ảnh thành công" : "Cập nhật hình ảnh thất bại!",
      $this->fn->getRedirectPath(['table' => $table, 'id_parent' => $id_parent]),
      $result
    );
  }

  public function them_gallery($data, $files, $id_parent)
  {
    $id_parent = (int)$id_parent;
    $table = 'tbl_gallery';
    $result = false;

    // Lấy tên sản phẩm cha để đặt tên ảnh
    $parent = $this->db->rawQueryOne("SELECT namevi FROM tbl_product WHERE id = ? LIMIT 1", [$id_parent]);
    $parent_name = $parent['namevi'] ?? 'gallery';

    // Kích thước thumbnail (dạng 600x400)
    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $thumb_size = "{$width}x{$height}";
    $thumb = json_encode(['w' => $width, 'h' => $height]);

    // Tổng số ảnh
    $total = count($files['files']['name']);

    for ($i = 0; $i < $total; $i++) {
      if (!empty($files['files']['name'][$i]) && $files['files']['error'][$i] == 0) {
        $file = [
          'name' => $files['files']['name'][$i],
          'type' => $files['files']['type'][$i],
          'tmp_name' => $files['files']['tmp_name'][$i],
          'error' => $files['files']['error'][$i],
          'size' => $files['files']['size'][$i]
        ];

        $thumb_filename = $this->fn->Upload([
          'file' => $file,
          'custom_name' => $parent_name,
          'thumb' => $thumb_size,
          'old_file_path' => '',
          'watermark' => true,
          'convert_webp' => true
        ]);

        if (!empty($thumb_filename)) {
          $status = !empty($data['hienthi_all']) ? 'hienthi' : '';
          $params = [
            $id_parent,
            $thumb_filename,
            $thumb,
            (int)($data['numb'][$i] ?? 0),
            $status
          ];

          $sql = "INSERT INTO `$table` (`id_parent`, `file`, `thumb`, `numb`, `status`) VALUES (?, ?, ?, ?, ?)";
          $result = $this->db->execute($sql, $params);
        }
      }
    }

    // Chuyển hướng
    $this->fn->transfer(
      $result ? "Cập nhật hình ảnh thành công" : "Cập nhật hình ảnh thất bại!",
      $this->fn->getRedirectPath(['table' => $table, 'id_parent' => $id_parent]),
      $result
    );
  }



  // public function them_gallery($data, $files, $id_parent)
  // {
  //   $id_parent = (int)$id_parent;
  //   $table = 'tbl_gallery';
  //   $parent = $this->db->rawQueryOne("SELECT namevi FROM tbl_product WHERE id = ? LIMIT 1", [$id_parent]);
  //   $parent_name = $parent['namevi'] ?? '';
  //   $result = false;

  //   for ($i = 0; $i < 6; $i++) {
  //     $file_key = "file$i";
  //     if (!empty($files[$file_key]['name']) && $files[$file_key]['error'] == 0) {
  //       $width = (int)($data['thumb_width'] ?? 0);
  //       $height = (int)($data['thumb_height'] ?? 0);
  //       $thumb_size = $width . 'x' . $height;

  //       $thumb_filename = $this->fn->Upload([
  //         'file' => $files[$file_key],
  //         'custom_name' => $parent_name,
  //         'thumb' => $thumb_size,
  //         'old_file_path' => '',
  //         'watermark' => true,
  //         'convert_webp' => true
  //       ]);

  //       if (!empty($thumb_filename)) {
  //         $thumb = json_encode(['w' => $width, 'h' => $height]);
  //         $status_flags = ['hienthi'];
  //         $status_values = [];

  //         foreach ($status_flags as $flag) {
  //           $flag_key = $flag . $i;
  //           if (!empty($data[$flag_key])) {
  //             $status_values[] = $flag;
  //           }
  //         }

  //         $fields = ['id_parent', 'file', 'thumb', 'numb', 'status'];
  //         $placeholders = array_fill(0, count($fields), '?');
  //         $params = [
  //           $id_parent,
  //           $thumb_filename,
  //           $thumb,
  //           (int)($data["numb$i"] ?? 0),
  //           implode(',', $status_values)
  //         ];

  //         $result = $this->db->execute(
  //           "INSERT INTO `$table` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")",
  //           $params
  //         );
  //       }
  //     }
  //   }

  //   $this->fn->transfer(
  //     $result ? "Cập nhật hình ảnh thành công" : "Cập nhật hình ảnh thất bại!",
  //     $this->fn->getRedirectPath(['table' => $table, 'id_parent' => $id_parent]),
  //     $result
  //   );
  // }

  public function update_views($slug)
  {
    $product = $this->db->rawQueryOne("SELECT * FROM tbl_product WHERE slugvi = ?", [$slug]);
    if (!empty($product)) {
      $new_views = $product['views'] + 1;
      $this->db->rawQuery("UPDATE tbl_product SET views = ? WHERE slugvi = ?", [$new_views, $slug]);
      return $product;
    }
    return false;
  }
  public function deleteMultiple_product(string $listid)
  {
    $ids = array_filter(array_map('intval', explode(',', $listid)));
    if (empty($ids)) {
      $this->fn->transfer("Danh sách ID không hợp lệ!", "index.php?page=product_man", false);
    }

    $table = 'tbl_product';
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // Lấy dữ liệu sản phẩm
    $rows = $this->db->rawQuery("SELECT id, file FROM $table WHERE id IN ($placeholders)", $ids);
    if (empty($rows)) {
      $this->fn->transfer("Không tìm thấy sản phẩm để xóa!", "index.php?page=product_man", false);
    }
    // Xoá ảnh sản phẩm + gallery + seo
    foreach ($rows as $row) {
      $id = (int)$row['id'];

      // Xoá ảnh chính
      if (!empty($row['file'])) {
        $this->fn->deleteFile(UPLOADS . $row['file']);
      }

      // Xoá gallery
      $gallery = $this->db->rawQuery("SELECT file FROM tbl_gallery WHERE id_parent = ?", [$id]);
      foreach ($gallery as $g) {
        if (!empty($g['file'])) {
          $this->fn->deleteFile(UPLOADS . $g['file']);
        }
      }
      $this->db->execute("DELETE FROM tbl_gallery WHERE id_parent = ?", [$id]);

      // Xoá SEO
      $this->db->execute("DELETE FROM tbl_seo WHERE id_parent = ? AND `type` = ?", [$id, 'product']);
    }
    $result = $this->db->execute("DELETE FROM $table WHERE id IN ($placeholders)", $ids);
    $this->fn->transfer(
      $result ? "Xóa sản phẩm thành công!" : "Xóa sản phẩm thất bại!",
      "index.php?page=product_man",
      $result
    );
  }
  public function delete_product($id)
  {
    $id = (int)$id;
    $table = 'tbl_product';

    // Lấy dữ liệu sản phẩm
    $row = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ? LIMIT 1", [$id]);
    if (!$row) {
      $this->fn->transfer("Sản phẩm không tồn tại", "index.php?page=product_man", false);
    }

    // Xoá ảnh chính
    if (!empty($row['file'])) {
      $this->fn->deleteFile(UPLOADS . $row['file']);
    }

    // Xoá ảnh con (gallery)
    $gallery = $this->db->rawQuery("SELECT file FROM tbl_gallery WHERE id_parent = ?", [$id]);
    foreach ($gallery as $g) {
      if (!empty($g['file'])) {
        $this->fn->deleteFile(UPLOADS . $g['file']);
      }
    }
    $this->db->execute("DELETE FROM tbl_gallery WHERE id_parent = ?", [$id]);

    // Xoá SEO
    $this->db->execute("DELETE FROM tbl_seo WHERE id_parent = ? AND `type` = ?", [$id, 'product']);

    // Xoá bản ghi sản phẩm
    $deleted = $this->db->execute("DELETE FROM $table WHERE id = ?", [$id]);

    $this->fn->transfer(
      $deleted ? "Xóa sản phẩm thành công!" : "Xóa sản phẩm thất bại!",
      "index.php?page=product_man",
      $deleted
    );
  }


  public function save_product($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $fields_multi = ['slug', 'name', 'desc', 'content'];
    $fields_common = ['id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code', 'numb', 'type'];
    $table = 'tbl_product';
    $data_prepared = [];
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_prepared[$key] = $data[$key] ?? "";
      }
    }
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? "";
    }
    $status_flags = ['hienthi', 'noibat', 'banchay'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) $status_values[] = $flag;
    }
    $data_prepared['status'] = implode(',', $status_values);
    foreach ($langs as $lang) {
      $slug_key = 'slug' . $lang;
      $checkSlugData = [];
      $checkSlugData['slug'] = $data_prepared[$slug_key];
      $checkSlugData['table'] = $table;
      $checkSlugData['exclude_id'] = $id ?? '';
      $checkSlugData['lang'] = $lang;
      $checkSlug = $this->fn->checkSlug($checkSlugData);
      if ($checkSlug) return $checkSlug;
    }
    $thumb_filename = $old_file_path = '';
    if (!empty($id)) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
      if ($old && !empty($old['file'])) {
        $old_file_path = UPLOADS . $old['file'];
      }
    }
    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $thumb_size = $width . 'x' . $height;
    $thumb_filename = $this->fn->Upload([
      'file' => $files['file'],
      'custom_name' => $data_prepared['namevi'],
      'thumb' => $thumb_size,
      'old_file_path' => $old_file_path,
      'watermark' => true,
      'convert_webp' => true
    ]);
    $thumb = ['w' => $width, 'h' => $height];
    $data_prepared['thumb'] = json_encode($thumb);
    if (!empty($id)) {
      $fields = $params = [];
      foreach ($data_prepared as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }
      if (!empty($thumb_filename)) {
        $fields[] = "`file` = ?";
        $params[] = $thumb_filename;
      }
      $params[] = (int)$id;
      $result = $this->db->execute("UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ?", $params);
      if ($result) {
        $this->seo->save_seo($data_prepared['type'], (int)$id, $data, $langs);
      }
      $msg = $result ? "Cập nhật sản phẩm thành công" : "Cập nhật sản phẩm thất bại";
    } else {
      $columns = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_prepared);
      if (!empty($thumb_filename)) {
        $columns[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }
      $inserted = $this->db->execute("INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")", $params);
      $insert_id = $inserted ? $this->db->getInsertId() : 0;
      if ($insert_id) {
        $this->seo->save_seo($data_prepared['type'], $insert_id, $data, $langs);
      }
      $msg = $inserted ? "Thêm sản phẩm thành công" : "Thêm sản phẩm thất bại";
    }
    $this->fn->transfer($msg, "index.php?page=product_man", !empty($id) ? $result : $inserted);
  }
  public function save_product_list($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $fields_multi = ['slug', 'name', 'desc', 'content'];
    $fields_common = ['numb', 'type'];
    $table = 'tbl_product_list';
    $data_prepared = [];
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_prepared[$key] = $data[$key] ?? '';
      }
    }
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? '';
    }
    $status_flags = ['hienthi', 'noibat'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) $status_values[] = $flag;
    }
    $data_prepared['status'] = implode(',', $status_values);
    foreach ($langs as $lang) {
      $slug_key = 'slug' . $lang;
      $checkSlugData = [];
      $checkSlugData['slug'] = $data_prepared[$slug_key];
      $checkSlugData['table'] = $table;
      $checkSlugData['exclude_id'] = $id ?? '';
      $checkSlugData['lang'] = $lang;
      $checkSlug = $this->fn->checkSlug($checkSlugData);
      if ($checkSlug) return $checkSlug;
    }
    $thumb_filename = $old_file_path = '';
    if (!empty($id)) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
      if ($old && !empty($old['file'])) {
        $old_file_path = UPLOADS . $old['file'];
      }
    }
    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $thumb_size = $width . 'x' . $height;
    $thumb_filename = $this->fn->Upload([
      'file' => $files['file'],
      'custom_name' => $data_prepared['namevi'],
      'thumb' => $thumb_size,
      'old_file_path' => $old_file_path,
      'watermark' => false,
      'convert_webp' => false
    ]);
    $thumb = ['w' => $width, 'h' => $height];
    $data_prepared['thumb'] = json_encode($thumb);
    if (!empty($id)) {
      $fields = $params = [];
      foreach ($data_prepared as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }
      if (!empty($thumb_filename)) {
        $fields[] = "`file` = ?";
        $params[] = $thumb_filename;
      }
      $params[] = (int)$id;
      $result = $this->db->execute("UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ?", $params);
      if ($result) {
        $this->seo->save_seo($data_prepared['type'], (int)$id, $data, $langs);
      }
      $msg = $result ? "Cập nhật danh mục thành công" : "Cập nhật danh mục thất bại";
    } else {
      $columns = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_prepared);
      if (!empty($thumb_filename)) {
        $columns[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }
      $inserted = $this->db->execute("INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")", $params);
      $insert_id = $inserted ? $this->db->getInsertId() : 0;
      if ($insert_id) {
        $this->seo->save_seo($data_prepared['type'], $insert_id, $data, $langs);
      }
      $msg = $inserted ? "Thêm danh mục thành công" : "Thêm danh mục thất bại";
    }
    $this->fn->transfer($msg, "index.php?page=product_list_man", !empty($id) ? $result : $inserted);
  }

  public function save_product_cat($data, $files, $id = null)
  {
    global $config;
    $langs = array_keys($config['website']['lang']);

    $fields_multi = ['slug', 'name', 'desc', 'content'];
    $fields_common = ['id_list', 'numb', 'type'];
    $table = 'tbl_product_cat';
    $data_prepared = [];
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $data_prepared[$key] = $data[$key] ?? '';
      }
    }
    foreach ($fields_common as $field) {
      $data_prepared[$field] = $data[$field] ?? '';
    }
    $status_flags = ['hienthi', 'noibat'];
    $status_values = [];
    foreach ($status_flags as $flag) {
      if (!empty($data[$flag])) $status_values[] = $flag;
    }
    $data_prepared['status'] = implode(',', $status_values);
    foreach ($langs as $lang) {
      $slug_key = 'slug' . $lang;
      $checkSlugData = [];
      $checkSlugData['slug'] = $data_prepared[$slug_key];
      $checkSlugData['table'] = $table;
      $checkSlugData['exclude_id'] = $id ?? '';
      $checkSlugData['lang'] = $lang;
      $checkSlug = $this->fn->checkSlug($checkSlugData);
      if ($checkSlug) return $checkSlug;
    }
    $thumb_filename = '';
    $old_file_path = '';
    if (!empty($id)) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
      if ($old && !empty($old['file'])) {
        $old_file_path = UPLOADS . $old['file'];
      }
    }
    $width = (int)($data['thumb_width'] ?? 0);
    $height = (int)($data['thumb_height'] ?? 0);
    $thumb_size = $width . 'x' . $height;
    $thumb_filename = $this->fn->Upload([
      'file' => $files['file'],
      'custom_name' =>  $data_prepared['namevi'],
      'thumb' => $thumb_size,
      'old_file_path' => $old_file_path,
      'watermark' => false,
      'convert_webp' => false
    ]);
    $thumb = ['w' => $width, 'h' => $height];
    $data_prepared['thumb'] = json_encode($thumb);
    if (!empty($id)) {
      $fields = [];
      $params = [];
      foreach ($data_prepared as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }
      if (!empty($thumb_filename)) {
        $fields[] = "`file` = ?";
        $params[] = $thumb_filename;
      }
      $params[] = (int)$id;
      $result = $this->db->execute("UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ?", $params);
      if ($result) {
        $this->seo->save_seo($data_prepared['type'], (int)$id, $data, $langs);
      }
      $msg = $result ? "Cập nhật danh mục thành công" : "Cập nhật danh mục thất bại";
    } else {
      $columns = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_prepared);
      if (!empty($thumb_filename)) {
        $columns[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }
      $inserted = $this->db->execute("INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")", $params);
      $insert_id = $inserted ? $this->db->getInsertId() : 0;
      if ($insert_id) {
        $this->seo->save_seo($data_prepared['type'], $insert_id, $data, $langs);
      }
      $msg = $inserted ? "Thêm danh mục thành công" : "Thêm danh mục thất bại";
    }
    $this->fn->transfer($msg, "index.php?page=product_cat_man", !empty($id) ? $result : $inserted);
  }
}
?>
