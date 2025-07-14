<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class Functions
{
  private $db;
  private $fm;

  public function __construct()
  {
    $this->db = new Database();
    $this->fm = new Format();
  }
  private function buildWhere(array $options): array
  {
    $where = [];
    $params = [];
    if (!empty($options['status'])) {
      $statuses = is_array($options['status']) ? $options['status'] : explode(',', $options['status']);
      foreach ($statuses as $status) {
        $where[] = "FIND_IN_SET(?, status)";
        $params[] = trim($status);
      }
    }
    $fields = ['id_list', 'id_cat', 'id_parent', 'exclude_id', 'type'];
    foreach ($fields as $field) {
      if (isset($options[$field]) && $options[$field] !== '') {
        $operator = ($field == 'exclude_id') ? '!=' : '=';
        $column = ($field == 'exclude_id') ? 'id' : $field;
        $where[] = "`$column` $operator ?";
        $params[] = $field === 'type' ? $options[$field] : (int)$options[$field];
      }
    }
    if (!empty($options['keyword'])) {
      $where[] = "`namevi` LIKE ?";
      $params[] = '%' . $options['keyword'] . '%';
    }
    $sql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
    return ['sql' => $sql, 'params' => $params];
  }
  public function show_data(array $options = [])
  {
    if (empty($options['table'])) return [];
    $table = $options['table'];
    $select = $options['select'] ?? '*';
    $order = $options['order'] ?? ' ORDER BY numb, id DESC';
    $whereData = $this->buildWhere($options);
    $sql = "SELECT $select FROM `$table`" . $whereData['sql'] . $order;
    if (!empty($options['records_per_page']) && !empty($options['current_page'])) {
      $limit = (int)$options['records_per_page'];
      $offset = ((int)$options['current_page'] - 1) * $limit;
      $sql .= " LIMIT $limit OFFSET $offset";
    } elseif (!empty($options['limit'])) {
      $limit = (int)$options['limit'];
      $offset = isset($options['offset']) ? (int)$options['offset'] : 0;
      $sql .= " LIMIT $limit OFFSET $offset";
    }
    $result = $this->db->rawQuery($sql, $whereData['params']);
    $data = [];
    if ($result instanceof mysqli_result) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    return $data;
  }
  public function show_data_join(array $options = []): array
  {
    $select = $options['select'] ?? '*';
    $table = $options['table'] ?? '';
    $join = $options['join'] ?? '';
    $alias = $options['alias'] ?? '';
    $order_by = $options['order_by'] ?? ($alias ? "$alias.id DESC" : "id DESC");
    $where = [];
    $bindings = [];
    if (empty($table)) return [];
    if (!empty($options['where'])) {
      foreach ($options['where'] as $key => $val) {
        $where[] = "$key = ?";
        $bindings[] = $val;
      }
    }
    if (!empty($options['keyword'])) {
      $keyword = trim($options['keyword']);
      if ($keyword !== '') {
        $where[] = "(p.namevi LIKE ? OR p.nameen LIKE ?)";
        $bindings[] = "%$keyword%";
        $bindings[] = "%$keyword%";
      }
    }
    $sql = "SELECT $select FROM `$table`";
    if (!empty($alias)) $sql .= " $alias";
    if (!empty($join)) $sql .= " $join";
    if (!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY $order_by";
    if (!empty($options['records_per_page']) && !empty($options['current_page'])) {
      $limit = (int)$options['records_per_page'];
      $offset = ((int)$options['current_page'] - 1) * $limit;
      $sql .= " LIMIT $limit OFFSET $offset";
    } elseif (!empty($options['limit'])) {
      $limit = (int)$options['limit'];
      $offset = isset($options['offset']) ? (int)$options['offset'] : 0;
      $sql .= " LIMIT $limit OFFSET $offset";
    }
    $result = $this->db->rawQuery($sql, $bindings);
    $data = [];
    if ($result instanceof mysqli_result) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    return $data;
  }
  public function count_data(array $options = []): int
  {
    if (empty($options['table'])) return 0;
    $table = $options['table'];
    $whereData = $this->buildWhere($options);
    $sql = "SELECT COUNT(*) FROM `$table`" . $whereData['sql'];
    $count = $this->db->rawQueryValue($sql, $whereData['params']);
    return is_numeric($count) ? (int)$count : 0;
  }
  public function count_data_join(array $options = []): int
  {
    $table = $options['table'] ?? '';
    if (empty($table)) return 0;
    $alias = $options['alias'] ?? '';
    $join = $options['join'] ?? '';
    $where = [];
    $bindings = [];
    if (!empty($options['where'])) {
      foreach ($options['where'] as $key => $val) {
        $where[] = "$key = ?";
        $bindings[] = $val;
      }
    }
    if (!empty($options['keyword'])) {
      $keyword = trim($options['keyword']);
      if ($keyword !== '') {
        $where[] = "(p.namevi LIKE ? OR p.nameen LIKE ?)";
        $bindings[] = "%$keyword%";
        $bindings[] = "%$keyword%";
      }
    }
    $idColumn = !empty($alias) ? "$alias.id" : "id";
    $sql = "SELECT COUNT(DISTINCT $idColumn) AS total FROM `$table`";
    if (!empty($alias)) $sql .= " $alias";
    if (!empty($join)) $sql .= " $join";
    if (!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);

    $row = $this->db->rawQueryOne($sql, $bindings);
    return (int)($row['total'] ?? 0);
  }
  public function save_gallery($data, $files, $id_parent, $type = '', $redirect_url = null)
  {
    $id_parent = (int)$id_parent;
    $table = 'tbl_gallery';
    $result = false;
    $parent = $this->db->rawQueryOne("SELECT namevi FROM tbl_product WHERE id = ? LIMIT 1", [$id_parent]);
    $parent_name = $parent['namevi'] ?? 'gallery';
    if (!empty($data['id-filer'])) {
      foreach ($data['id-filer'] as $i => $gid) {
        $gid = (int)$gid;
        $numb = (int)($data['numb-filer'][$i] ?? 0);
        $name = trim($data['name-filer'][$i] ?? '');
        $this->db->execute("UPDATE $table SET numb = ?, name = ? WHERE id = ?", [$numb, $name, $gid]);
      }
    }
    $total = count($files['files']['name'] ?? []);
    for ($i = 0; $i < $total; $i++) {
      if (!empty($files['files']['name'][$i]) && $files['files']['error'][$i] === 0) {
        $file = [
          'name' => $files['files']['name'][$i],
          'type' => $files['files']['type'][$i],
          'tmp_name' => $files['files']['tmp_name'][$i],
          'error' => $files['files']['error'][$i],
          'size' => $files['files']['size'][$i]
        ];
        $thumb_filename = $this->uploadImage([
          'file' => $file,
          'custom_name' => $parent_name,
          'old_file_path' => '',
          'watermark' => true,
          'convert_webp' => true
        ]);
        if (!empty($thumb_filename)) {
          $numb = (int)($data['numb-filer'][$i] ?? 0);
          $name = trim($data['name-filer'][$i] ?? '');
          $fields = ['id_parent', 'type', 'file', 'numb', 'name', 'status'];
          $params = [$id_parent, $type, $thumb_filename, $numb, $name, !empty($data['hienthi_all']) ? 'hienthi' : ''];
          $result = $this->db->execute("INSERT INTO `$table` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', array_fill(0, count($fields), '?')) . ")", $params);
        }
      }
    }
    if (!empty($redirect_url)) {
      $this->transfer($result ? "Cập nhật hình ảnh thành công" : "Cập nhật hình ảnh thất bại!", $redirect_url, $result);
    }
    return $result;
  }
  public function save_seo(string $type, int $id_parent, array $data, array $langs): void
  {
    $seo_table = 'tbl_seo';
    $fields_multi = ['title', 'keywords', 'description', 'schema'];
    $data_sql = ['id_parent' => $id_parent, 'type' => $type];
    $has_data = false;
    foreach ($langs as $lang) {
      foreach ($fields_multi as $field) {
        $key = $field . $lang;
        $value = $data[$key] ?? '';
        $data_sql[$key] = $value;
        if (!$has_data && trim($value) !== '') {
          $has_data = true;
        }
      }
    }
    if (!$has_data) return;
    $existing = $this->db->rawQueryOne("SELECT id FROM `$seo_table` WHERE id_parent = ? AND `type` = ?", [$id_parent, $type]);
    if ($existing) {
      $fields =  $params = [];
      foreach ($data_sql as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }
      $params[] = $existing['id'];
      $this->db->execute("UPDATE `$seo_table` SET " . implode(', ', $fields) . " WHERE id = ?", $params);
    } else {
      $columns = array_map(fn($col) => "`$col`", array_keys($data_sql));
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_sql);
      $this->db->execute("INSERT INTO `$seo_table` (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")", $params);
    }
  }
  public function save_data($data, $files, $id = null, $options = [])
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $table = $options['table'] ?? '';
    $fields_multi = $options['fields_multi'] ?? [];
    $fields_common = $options['fields_common'] ?? [];
    $status_flags = $options['status_flags'] ?? [];
    $redirect_page = $options['redirect_page'] ?? 'index.php';
    $enable_gallery = $options['enable_gallery'] ?? false;
    $enable_seo = $options['enable_seo'] ?? false;
    $enable_slug = $options['enable_slug'] ?? false;
    $convert_webp = $options['convert_webp'] ?? false;

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

    $data_prepared['status'] = implode(',', array_filter($status_flags, fn($f) => !empty($data[$f])));
    $type = $data_prepared['type'] ?? '';

    $errors = $this->checkTitle($data);
    if (!empty($errors)) {
      $this->transfer($errors[0], $_SERVER['HTTP_REFERER'], false);
    }

    if ($enable_slug) {
      foreach ($langs as $lang) {
        $slug = $data_prepared['slug' . $lang] ?? '';
        if ($this->checkSlug(['slug' => $slug, 'table' => $table, 'exclude_id' => $id ?? '', 'lang' => $lang])) {
          return $this->checkSlug(['slug' => $slug, 'table' => $table, 'exclude_id' => $id ?? '', 'lang' => $lang]);
        }
      }
    }

    $thumb_filename = $old_filename = '';
    if (!empty($id)) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
      $old_filename = $old['file'] ?? '';
    }

    if (!empty($files['file']['tmp_name'])) {
      $thumb_filename = $this->uploadImage([
        'file' => $files['file'],
        'custom_name' => $data_prepared['namevi'] ?? '',
        'old_file_path' => UPLOADS . $old_filename,
        'convert_webp' => $convert_webp,
        'background' => $options['background'] ?? [255, 255, 255]
      ]);
    } elseif (!empty($data['photo_deleted']) && $data['photo_deleted'] == '1' && $old_filename) {
      $this->deleteFile($old_filename);
      $thumb_filename = '';
    }

    if (!empty($id)) {
      $fields = $params = [];
      foreach ($data_prepared as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }

      if ($thumb_filename !== '' || (!empty($data['photo_deleted']) && $data['photo_deleted'] == '1')) {
        $fields[] = "`file` = ?";
        $params[] = $thumb_filename;
      }

      $params[] = (int)$id;
      $result = $this->db->execute("UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ?", $params);

      if ($enable_seo && $result) {
        $this->save_seo($type, (int)$id, $data, $langs);
      }

      if (!empty($enable_gallery) && !empty($data['deleted_images']) && !empty($id)) {
        $deletedImages = explode('|', $data['deleted_images']);
        foreach ($deletedImages as $gid) {
          $gid = (int)trim($gid);
          if ($gid > 0) {
            $gallery = $this->db->rawQueryOne("SELECT `file` FROM tbl_gallery WHERE id = ?", [$gid]);
            if ($gallery && !empty($gallery['file'])) {
              @unlink(UPLOADS . $gallery['file']);
            }
            $this->db->execute("DELETE FROM tbl_gallery WHERE id = ?", [$gid]);
          }
        }
      }

      if ($enable_gallery && !empty($data['id-filer']) && is_array($data['id-filer'])) {
        foreach ($data['id-filer'] as $k => $gid) {
          $gid = (int)$gid;
          $numb = (int)($data['numb-filer'][$k] ?? 0);
          $name = trim($data['name-filer'][$k] ?? '');
          if ($gid > 0) {
            $this->db->execute("UPDATE tbl_gallery SET numb = ?, name = ? WHERE id = ?", [$numb, $name, $gid]);
          }
        }
      }

      if ($enable_gallery && !empty($files['files']['name'][0])) {
        $this->save_gallery($data, $files, $id, $type, false);
      }

      $msg = $result ? "Cập nhật dữ liệu thành công" : "Cập nhật dữ liệu thất bại";
    } else {
      $columns = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_prepared);

      if (!empty($thumb_filename)) {
        $columns[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }

      $inserted = $this->db->execute(
        "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")",
        $params
      );

      $insert_id = $inserted ? $this->db->getInsertId() : 0;

      if ($enable_seo && $insert_id) {
        $this->save_seo($type, $insert_id, $data, $langs);
      }

      if ($enable_gallery && !empty($files['files']['name'][0])) {
        $this->save_gallery($data, $files, $insert_id, $type, false);
      }

      $msg = $inserted ? "Thêm dữ liệu thành công" : "Thêm dữ liệu thất bại";
    }

    $this->transfer($msg, $redirect_page, !empty($id) ? $result : $inserted);
  }
  public function deleteFile($file = '')
  {
    if (!$file) return true;

    $filename = basename($file);
    $filenameNoExt = pathinfo($filename, PATHINFO_FILENAME);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $baseDir = rtrim(UPLOADS, '/');
    $iterator = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
      RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($iterator as $entry) {
      if (!$entry->isFile()) continue;
      $current = $entry->getPathname();
      $currentBase = basename($current);
      $currentNoExt = pathinfo($currentBase, PATHINFO_FILENAME);
      $currentExt = strtolower(pathinfo($currentBase, PATHINFO_EXTENSION));
      if (
        $currentBase === $filename ||  // Chính xác tên gốc
        ($currentNoExt === $filenameNoExt && $currentExt === 'webp') ||
        ($currentNoExt === $filenameNoExt && $currentExt === 'json')
      ) {
        @unlink($current);
      }
    }
    return true;
  }
  public function delete_data(array $options = []): void
  {
    $id = (int)($options['id'] ?? 0);
    $table = $options['table'] ?? '';
    $type = $options['type'] ?? '';
    $redirect_page = $options['redirect_page'] ?? $this->getRedirectPath(['table' => $table, 'type' => $type]);
    $delete_seo = $options['delete_seo'] ?? false;
    $delete_gallery = $options['delete_gallery'] ?? false;
    $delete_file = $options['delete_file'] ?? true;

    if (!$id || !$table) {
      $this->transfer("Thiếu thông tin xóa!", $redirect_page, false);
    }

    $row = $this->db->rawQueryOne("SELECT file FROM `$table` WHERE id = ?", [$id]);
    if (!$row) {
      $this->transfer("Dữ liệu không tồn tại!", $redirect_page, false);
    }

    // Trường hợp xoá bản ghi gallery (ảnh con)
    if ($table === 'tbl_gallery') {
      if (!empty($row['file'])) {
        $this->deleteFile(UPLOADS . $row['file']);
      }

      $deleted = $this->db->execute("DELETE FROM `$table` WHERE id = ?", [$id]);

      $this->transfer(
        $deleted ? "Xóa ảnh thành công!" : "Xóa ảnh thất bại!",
        $redirect_page,
        $deleted
      );
      return;
    }

    // Xử lý xoá file chính nếu có
    if ($delete_file && !empty($row['file'])) {
      $this->deleteFile(UPLOADS . $row['file']);
    }

    // Xoá gallery con nếu được yêu cầu
    if ($delete_gallery) {
      $gallery = $this->db->rawQuery("SELECT file FROM tbl_gallery WHERE id_parent = ?", [$id]);
      foreach ($gallery as $g) {
        if (!empty($g['file'])) {
          $this->deleteFile(UPLOADS . $g['file']);
        }
      }
      $this->db->execute("DELETE FROM tbl_gallery WHERE id_parent = ?", [$id]);
    }

    // Xoá SEO nếu có
    if ($delete_seo) {
      if ($type) {
        $this->db->execute("DELETE FROM tbl_seo WHERE id_parent = ? AND `type` = ?", [$id, $type]);
      } else {
        $this->db->execute("DELETE FROM tbl_seo WHERE id_parent = ?", [$id]);
      }
    }

    $deleted = $this->db->execute("DELETE FROM `$table` WHERE id = ?", [$id]);

    $this->transfer(
      $deleted ? "Xóa dữ liệu thành công!" : "Xóa dữ liệu thất bại!",
      $redirect_page,
      $deleted
    );
  }
  public function deleteMultiple_data(array $options = [])
  {
    $listid = $options['listid'] ?? '';
    $table = $options['table'] ?? '';
    $type = $options['type'] ?? '';
    $redirect_page = $options['redirect_page'] ?? $this->getRedirectPath(['table' => $table, 'type' => $type]);
    $delete_seo = $options['delete_seo'] ?? false;
    $delete_gallery = $options['delete_gallery'] ?? false;
    $delete_file = $options['delete_file'] ?? true;
    $ids = array_filter(array_map('intval', explode(',', $listid)));
    if (empty($ids) || !$table) {
      $this->transfer("Danh sách ID không hợp lệ!", $redirect_page, false);
    }
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $rows = $this->db->rawQuery("SELECT id, file FROM `$table` WHERE id IN ($placeholders)", $ids);
    foreach ($rows as $row) {
      $id = (int)$row['id'];
      if ($delete_file && !empty($row['file'])) {
        $this->deleteFile($row['file']);
      }
      if ($delete_gallery) {
        $gallery = $this->db->rawQuery("SELECT file FROM tbl_gallery WHERE id_parent = ?", [$id]);
        foreach ($gallery as $g) {
          if (!empty($g['file'])) {
            $this->deleteFile($g['file']);
          }
        }
        $this->db->execute("DELETE FROM tbl_gallery WHERE id_parent = ?", [$id]);
      }
      if ($delete_seo) {
        if ($type) {
          $this->db->execute("DELETE FROM tbl_seo WHERE id_parent = ? AND `type` = ?", [$id, $type]);
        } else {
          $this->db->execute("DELETE FROM tbl_seo WHERE id_parent = ?", [$id]);
        }
      }
    }
    $delete_result = $this->db->execute("DELETE FROM `$table` WHERE id IN ($placeholders)", $ids);
    $this->transfer(
      $delete_result ? "Xóa dữ liệu thành công!" : "Xóa dữ liệu thất bại!",
      $redirect_page,
      $delete_result
    );
  }
  public function galleryFiler($numb = 1, $id = 0, $photo = '', $name = '', $folder = '', $col = '')
  {
    $image = $this->getImage(['file' => $photo, 'width' => 150, 'height' => 150, 'thumb' => false, 'class' => 'img-fluid rounded mx-auto d-block']);
    $html = '<li class="jFiler-item ' . $col . '"><div class="jFiler-item-container"><div class="jFiler-item-inner"><div class="jFiler-item-thumb"><div class="jFiler-item-status"></div><div class="jFiler-item-thumb-overlay"><div class="jFiler-item-info"><div style="display: table-cell; vertical-align: middle;"><span class="jFiler-item-title"><b title="' . htmlspecialchars($name) . '">' . htmlspecialchars($name) . '</b></span></div></div></div>' . $image . '</div><div class="jFiler-item-assets jFiler-row"><ul class="list-inline pull-right d-flex align-items-center justify-content-between w-100"><li class="ml-1"><a class="icon-jfi-trash jFiler-item-trash-action my-jFiler-item-trash" data-id="' . (int)$id . '" data-folder="' . htmlspecialchars($folder) . '" data-photo="' . htmlspecialchars($photo) . '"></a></li><li class="mr-1"><div class="custom-control custom-checkbox d-inline-block align-middle text-md"><input type="checkbox" class="custom-control-input filer-checkbox" id="filer-checkbox-' . (int)$id . '" value="' . (int)$id . '"><label for="filer-checkbox-' . (int)$id . '" class="custom-control-label font-weight-normal" data-label="Chọn">Chọn</label></div></li></ul></div><input type="number" class="form-control form-control-sm mb-1" name="numb-filer[]" placeholder="Số thứ tự" value="' . (int)$numb . '"><input type="text" class="form-control form-control-sm" name="name-filer[]" placeholder="Tiêu đề" value="' . htmlspecialchars($name) . '"><input type="hidden" name="id-filer[]" value="' . (int)$id . '"><input type="hidden" name="photo-filer[]" value="' . htmlspecialchars($photo) . '"><input type="hidden" name="folder-filer[]" value="' . htmlspecialchars($folder) . '"></div></div></li>';
    return $html;
  }
  function isItemActive(array $activeList, string $currentPage, string $currentType): bool
  {
    foreach ($activeList as $activeItem) {
      parse_str(ltrim($activeItem, '?'), $activeParams);
      if (($activeParams['page'] ?? '') === $currentPage && ($activeParams['type'] ?? '') === $currentType) {
        return true;
      }
    }
    return false;
  }

  /* Alert */
  public function alert($notify = '')
  {
    echo '<script language="javascript">alert("' . $notify . '")</script>';
  }
  /* Decode html characters */
  public function decodeHtmlChars($htmlChars)
  {
    return htmlspecialchars_decode($htmlChars ?: '');
  }

  public function abort_404()
  {
    http_response_code(404);
    include '404.php';
    exit();
  }
  public function getLinkCategory($table = '', $selectedId = 0, $title_select = 'Chọn danh mục')
  {
    $params = [];
    $where = ' WHERE 1';
    $name = $id = '';
    if (str_contains($table, '_list')) {
      $name = $id = 'id_list'; // cấp 1
    } elseif (str_contains($table, '_cat')) {
      $name = $id = 'id_cat';
      $id_list = $_REQUEST['id_list'] ?? 0;
      if ((int)$id_list > 0) {
        $where .= ' AND id_list = ?';
        $params[] = (int)$id_list;
      }
    } elseif (str_contains($table, '_item')) {
      $name = $id = 'id_item';
      $id_cat = $_REQUEST['id_cat'] ?? 0;
      if ((int)$id_cat > 0) {
        $where .= ' AND id_cat = ?';
        $params[] = (int)$id_cat;
      }
    } else {
      $name = $id = 'id';
    }
    $rows = $this->db->rawQuery("SELECT id, namevi FROM `$table` $where ORDER BY numb, id DESC", $params);
    $str = '<select id="' . $id . '" name="' . $name . '" onchange="onchangeCategory($(this))" class="form-control filter-category select2">';
    $str .= '<option value="0">' . htmlspecialchars($title_select) . '</option>';
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $selected = ($selectedId == $row['id']) ? 'selected' : '';
        $str .= '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['namevi']) . '</option>';
      }
    } else {
      $str .= '<option disabled>Không có dữ liệu</option>';
    }
    $str .= '</select>';
    return $str;
  }
  public function getAjaxCategory($table = '', $selectedId = 0, $id_list = null, $id_cat = null, $title_select = 'Chọn danh mục')
  {
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    $params = [];
    $where = ' WHERE 1';
    $class = 'form-control select2 select-category';
    $levels = ['_list' => ['field' => 'id_list', 'data_level' => '0', 'data_table' => 'tbl_product_cat', 'data_child' => 'id_cat', 'filters' => []], '_cat' => ['field' => 'id_cat', 'data_level' => '1', 'data_table' => 'tbl_product_item', 'data_child' => 'id_item', 'filters' => ['id_list' => $id_list]], '_item' => ['field' => 'id_item', 'data_level' => '2', 'data_table' => '', 'data_child' => '', 'filters' => ['id_list' => $id_list, 'id_cat' => $id_cat]]];
    $matched = null;
    foreach ($levels as $key => $conf) {
      if (str_contains($table, $key)) {
        $matched = $conf;
        break;
      }
    }
    if (!$matched) {
      $field = $name = 'id';
      $data_level = '';
      $data_table = '';
      $data_child = '';
    } else {
      $field = $name = $matched['field'];
      $data_level = 'data-level="' . $matched['data_level'] . '"';
      $data_table = 'data-table="' . $matched['data_table'] . '"';
      $data_child = 'data-child="' . $matched['data_child'] . '"';
      foreach ($matched['filters'] as $filterField => $filterValue) {
        if ($filterValue > 0) {
          $where .= " AND {$filterField} = ?";
          $params[] = $filterValue;
        }
      }
    }
    $rows = $this->db->rawQuery("SELECT id, namevi FROM `$table` $where ORDER BY numb, id DESC", $params);
    $str = '<select id="' . $field . '" name="' . $name . '" ' . $data_level . ' ' . $data_table . ' ' . $data_child . ' class="' . $class . '">';
    $str .= '<option value="0">' . htmlspecialchars($title_select) . '</option>';
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $selected = ($selectedId == $row['id']) ? 'selected' : '';
        $str .= '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['namevi']) . '</option>';
      }
    } else {
      $str .= '<option disabled>Không có dữ liệu</option>';
    }
    $str .= '</select>';
    return $str;
  }

  public function getRedirectPath($params = [])
  {
    $map = [];
    $tables = ['news', 'product', 'gallery', 'product_list', 'product_cat', 'tieuchi', 'danhgia', 'slideshow', 'setting', 'payment', 'static', 'social', 'seopage'];
    foreach ($tables as $tbl) {
      $map["tbl_{$tbl}"] = "{$tbl}_man";
    }
    $tableKey = $params['table'] ?? '';
    $page = $map[$tableKey] ?? 'dashboard';
    $query = "index.php?page={$page}";
    if (!empty($params['type'])) {
      $query .= "&type=" . urlencode($params['type']);
    }
    if (!empty($params['id_parent'])) {
      $query .= "&id=" . (int)$params['id_parent'];
    }
    return $query;
  }

  function transfer($msg, $page = 'index.php?page=', $numb = true)
  {
    $_SESSION['transfer_data'] = ['msg' => $msg, 'page' => $page, 'numb' => $numb];
    define('IS_TRANSFER', true);
    header("Location: index.php?page=transfer");
    exit();
  }
  public function convert_type(string $type)
  {
    $type = trim($type);
    if ($type === '') return '';

    $row = $this->db->rawQueryOne(
      "SELECT langvi FROM tbl_type WHERE lang_define = ? LIMIT 1",
      [$type]
    );

    if ($row && !empty($row['langvi'])) {
      $lang = $row['langvi'];
      return [
        'vi' => $lang,
        'slug' => $this->to_slug($lang)
      ];
    }

    return $type;
  }

  function is_selected($name, $result, $id, $value)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return (isset($_POST[$name]) && $_POST[$name] == $value) ? 'selected' : '';
    }
    if (!empty($id) && isset($result[$name])) {
      return ($result[$name] == $value) ? 'selected' : '';
    }
    return '';
  }
  function is_checked($name, $status = '', $id = null, $default = true)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['status'][$name])) {
        return $_POST['status'][$name] ? 'checked' : '';
      }
      if (isset($_POST[$name])) {
        return $_POST[$name] ? 'checked' : '';
      }
      return '';
    }
    if (empty($id)) {
      return $default ? 'checked' : '';
    }
    $statuses = array_filter(array_map('trim', explode(',', $status)));
    return in_array($name, $statuses) ? 'checked' : '';
  }

  /* Dump */
  public function dump($value = '', $exit = false)
  {
    echo "<pre>";
    print_r($value);
    echo "</pre>";
    if ($exit) exit();
  }
  public function checkTitle($data = array())
  {
    $result = [];
    if (empty(trim($data['namevi'] ?? ''))) {
      $result[] = 'Tiêu đề (Tiếng Việt) không được trống';
    }
    return $result;
  }

  public function checkSlug(array $data = []): string|false
  {
    $slug = trim($data['slug'] ?? '');
    $table = trim($data['table'] ?? '');
    $exclude_id = isset($data['exclude_id']) ? (int)$data['exclude_id'] : 0;
    $lang = $data['lang'] ?? 'vi';
    if ($slug === '' || $table === '') return false;
    $slugCol = 'slug' . $lang;
    $errorMsg = "Đường dẫn đã tồn tại. Vui lòng chọn đường dẫn khác để tránh trùng lặp.";
    $reservedSlugs = [
      'lien-he',
      'tin-tuc',
      'huong-dan-choi',
      'san-pham',
      'gioi-thieu',
      'chinh-sach',
      'mua-hang',
      'dang-nhap',
      'dang-ky'
    ];
    if (in_array($slug, $reservedSlugs)) {
      return $errorMsg;
    }
    $tablesToCheck = [
      "tbl_product_list",
      "tbl_product_cat",
      "tbl_product_item",
      "tbl_product_sub",
      "tbl_product_brand",
      "tbl_product",
      "tbl_news_list",
      "tbl_news_cat",
      "tbl_news_item",
      "tbl_news_sub",
      "tbl_news",
      "tbl_tags"
    ];
    foreach ($tablesToCheck as $tbl) {
      $tblEsc = mysqli_real_escape_string($this->db->link, $tbl);
      $slugColEsc = mysqli_real_escape_string($this->db->link, $slugCol);
      $checkTable = $this->db->rawQueryOne("SHOW TABLES LIKE '$tblEsc'");
      if (!$checkTable) continue;
      $checkCol = $this->db->rawQueryOne("SHOW COLUMNS FROM `$tblEsc` LIKE '$slugColEsc'");
      if (!$checkCol) continue;
      $sql = "SELECT `$slugColEsc` FROM `$tblEsc` WHERE `$slugColEsc` = ?";
      $params = [$slug];
      if (strtolower($tbl) === strtolower($table) && $exclude_id > 0) {
        $sql .= " AND id != ?";
        $params[] = $exclude_id;
      }
      $sql .= " LIMIT 1";
      $exist = $this->db->rawQueryOne($sql, $params);
      if ($exist) {
        return $errorMsg;
      }
    }
    return false;
  }
  private function generateUniqueFilename($upload_dir, $slug_name, $ext)
  {
    $i = 0;
    do {
      $suffix = $i > 0 ? '-' . $i : '';
      $filename = $slug_name . $suffix . '.' . $ext;
      $file_path = rtrim($upload_dir, '/') . '/' . $filename;
      $i++;
    } while (file_exists($file_path));

    return $filename;
  }
  private function cropTransparentOrWhiteBorder($image)
  {
    $w = imagesx($image);
    $h = imagesy($image);

    $min_x = $w;
    $min_y = $h;
    $max_x = 0;
    $max_y = 0;

    for ($y = 0; $y < $h; $y++) {
      for ($x = 0; $x < $w; $x++) {
        $rgba = imagecolorat($image, $x, $y);
        $a = ($rgba & 0x7F000000) >> 24;
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;

        // Nếu không phải trắng hoặc không trong suốt
        if (!($r > 240 && $g > 240 && $b > 240) && $a < 120) {
          if ($x < $min_x) $min_x = $x;
          if ($y < $min_y) $min_y = $y;
          if ($x > $max_x) $max_x = $x;
          if ($y > $max_y) $max_y = $y;
        }
      }
    }

    // Nếu không tìm được vùng nội dung
    if ($max_x <= $min_x || $max_y <= $min_y) {
      return false;
    }

    $crop_width = $max_x - $min_x + 1;
    $crop_height = $max_y - $min_y + 1;

    $new_img = imagecreatetruecolor($crop_width, $crop_height);
    imagealphablending($new_img, false);
    imagesavealpha($new_img, true);

    $transparent = imagecolorallocatealpha($new_img, 0, 0, 0, 127);
    imagefill($new_img, 0, 0, $transparent);

    imagecopy($new_img, $image, 0, 0, $min_x, $min_y, $crop_width, $crop_height);

    return $new_img;
  }
  private function applyOpacity($image, $opacity)
  {
    $opacity = max(0, min(100, $opacity));
    $w = imagesx($image);
    $h = imagesy($image);
    $tmp = imagecreatetruecolor($w, $h);
    imagealphablending($tmp, false);
    imagesavealpha($tmp, true);
    for ($y = 0; $y < $h; ++$y) {
      for ($x = 0; $x < $w; ++$x) {
        $rgba = imagecolorsforindex($image, imagecolorat($image, $x, $y));
        $alpha = 127 - ((127 - $rgba['alpha']) * ($opacity / 100));
        $color = imagecolorallocatealpha($tmp, $rgba['red'], $rgba['green'], $rgba['blue'], (int)round($alpha));
        imagesetpixel($tmp, $x, $y, $color);
      }
    }
    return $tmp;
  }
  public function addWatermark($source_path, $destination_path)
  {
    $row = $this->db->rawQueryOne("SELECT * FROM tbl_watermark LIMIT 1");
    if (empty($row['watermark'])) return false;
    $img_type = exif_imagetype($source_path);
    $image = match ($img_type) {
      IMAGETYPE_JPEG => imagecreatefromjpeg($source_path),
      IMAGETYPE_PNG  => imagecreatefrompng($source_path),
      IMAGETYPE_WEBP => imagecreatefromwebp($source_path),
      default        => false,
    };
    if (!$image) return false;
    $img_width = imagesx($image);
    $img_height = imagesy($image);
    $watermark_path = UPLOADS . $row['watermark'];
    if (!file_exists($watermark_path)) return false;
    $wm_src = imagecreatefrompng($watermark_path);
    if (!$wm_src) return false;
    imagesavealpha($wm_src, true);
    $wm_width = imagesx($wm_src);
    $wm_height = imagesy($wm_src);
    $per       = floatval($row['per'] ?? 2);
    $small_per = floatval($row['small_per'] ?? 3);
    $max       = intval($row['max'] ?? 120);
    $min       = intval($row['min'] ?? 120);
    $opacity   = floatval($row['opacity'] ?? 100);
    $position  = intval($row['position'] ?? 9);
    $offset_x  = intval($row['offset_x'] ?? 0);
    $offset_y  = intval($row['offset_y'] ?? 0);
    $scale_percent = ($img_width < 300) ? $small_per : $per;
    $target_wm_width = $img_width * $scale_percent / 100;
    $target_wm_width = max($min, min($max > 0 ? $max : $img_width, $target_wm_width));
    $target_wm_height = intval($wm_height * ($target_wm_width / $wm_width));
    $scaled_wm = imagecreatetruecolor($target_wm_width, $target_wm_height);
    imagealphablending($scaled_wm, false);
    imagesavealpha($scaled_wm, true);
    imagecopyresampled($scaled_wm, $wm_src, 0, 0, 0, 0, $target_wm_width, $target_wm_height, $wm_width, $wm_height);
    imagedestroy($wm_src);
    $watermark = $this->applyOpacity($scaled_wm, $opacity);
    imagedestroy($scaled_wm);
    $padding = 10;
    $positions = [
      1 => [$padding, $padding],
      2 => [($img_width - $target_wm_width) / 2, $padding],
      3 => [$img_width - $target_wm_width - $padding, $padding],
      4 => [$img_width - $target_wm_width - $padding, ($img_height - $target_wm_height) / 2],
      5 => [$img_width - $target_wm_width - $padding, $img_height - $target_wm_height - $padding],
      6 => [($img_width - $target_wm_width) / 2, $img_height - $target_wm_height - $padding],
      7 => [$padding, $img_height - $target_wm_height - $padding],
      8 => [$padding, ($img_height - $target_wm_height) / 2],
      9 => [($img_width - $target_wm_width) / 2, ($img_height - $target_wm_height) / 2],
    ];
    [$x, $y] = $positions[$position] ?? $positions[9];
    $x += $offset_x;
    $y += $offset_y;
    imagecopy($image, $watermark, $x, $y, 0, 0, $target_wm_width, $target_wm_height);
    match ($img_type) {
      IMAGETYPE_JPEG => imagejpeg($image, $destination_path, 85),
      IMAGETYPE_PNG  => imagepng($image, $destination_path, 8),
      IMAGETYPE_WEBP => imagewebp($image, $destination_path, 80),
      default        => null,
    };
    imagedestroy($image);
    imagedestroy($watermark);
    return true;
  }
  public function createFixedThumbnail($source_path, $thumb_name, $background = false, $add_watermark = false, $convert_webp = false): string|false
  {
    if (!file_exists($source_path) || !preg_match('/^(\d+)x(\d+)(x(\d+))?$/', $thumb_name, $m)) return false;

    [$width_orig, $height_orig, $image_type] = getimagesize($source_path);
    $thumb_width = (int)$m[1];
    $thumb_height = (int)$m[2];
    $zoom_crop = isset($m[4]) ? (int)$m[4] : 1;

    $ext_map = [
      IMAGETYPE_JPEG => 'jpg',
      IMAGETYPE_PNG => 'png',
      IMAGETYPE_WEBP => 'webp'
    ];
    $create_func = [
      IMAGETYPE_JPEG => 'imagecreatefromjpeg',
      IMAGETYPE_PNG => 'imagecreatefrompng',
      IMAGETYPE_WEBP => 'imagecreatefromwebp'
    ];
    if (!isset($ext_map[$image_type])) return false;

    $ext = $ext_map[$image_type];
    $image = @$create_func[$image_type]($source_path);
    if (!$image) return false;

    $is_transparent = in_array($image_type, [IMAGETYPE_PNG, IMAGETYPE_WEBP]);
    if ($is_transparent) {
      imagepalettetotruecolor($image);
      imagealphablending($image, true);
      imagesavealpha($image, true);
    }

    $canvas = imagecreatetruecolor($thumb_width, $thumb_height);
    if ($is_transparent && !$background) {
      imagealphablending($canvas, false);
      imagesavealpha($canvas, true);
      imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
    } elseif (is_array($background) && count($background) === 4) {
      imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, ...$background));
    } else {
      imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
    }

    $src_ratio = $width_orig / $height_orig;
    $dst_ratio = $thumb_width / $thumb_height;

    if (in_array($zoom_crop, [2, 3, 4])) {
      $resize_w = ($src_ratio > $dst_ratio) ? $thumb_width : intval($thumb_height * $src_ratio);
      $resize_h = ($src_ratio > $dst_ratio) ? intval($thumb_width / $src_ratio) : $thumb_height;

      if ($zoom_crop === 2) {
        $dst_x = intval(($thumb_width - $resize_w) / 2);
        $dst_y = intval(($thumb_height - $resize_h) / 2);
        imagecopyresampled($canvas, $image, $dst_x, $dst_y, 0, 0, $resize_w, $resize_h, $width_orig, $height_orig);
      } elseif ($zoom_crop === 3) {
        imagedestroy($canvas);
        $canvas = imagecreatetruecolor($resize_w, $resize_h);
        if ($is_transparent && !$background) {
          imagealphablending($canvas, false);
          imagesavealpha($canvas, true);
          imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        } else {
          imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        }
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $resize_w, $resize_h, $width_orig, $height_orig);
      } elseif ($zoom_crop === 4 && method_exists($this, 'cropTransparentOrWhiteBorder')) {
        $temp = imagecreatetruecolor($resize_w, $resize_h);
        imagealphablending($temp, false);
        imagesavealpha($temp, true);
        imagefill($temp, 0, 0, imagecolorallocatealpha($temp, 0, 0, 0, 127));
        imagecopyresampled($temp, $image, 0, 0, 0, 0, $resize_w, $resize_h, $width_orig, $height_orig);
        $cropped = $this->cropTransparentOrWhiteBorder($temp);
        imagedestroy($canvas);
        $canvas = $cropped ?: $temp;
      }
    } else {
      $src_w = ($src_ratio > $dst_ratio) ? intval($height_orig * $dst_ratio) : $width_orig;
      $src_h = ($src_ratio > $dst_ratio) ? $height_orig : intval($width_orig / $dst_ratio);
      $src_x = intval(($width_orig - $src_w) / 2);
      $src_y = intval(($height_orig - $src_h) / 2);
      imagecopyresampled($canvas, $image, 0, 0, $src_x, $src_y, $thumb_width, $thumb_height, $src_w, $src_h);
    }

    $upload_dir = UPLOADS . THUMB . "{$thumb_width}x{$thumb_height}x{$zoom_crop}/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $filename = pathinfo($source_path, PATHINFO_FILENAME);
    $thumb_ext = ($convert_webp || $ext === 'webp') ? 'webp' : $ext;
    $thumb_path = $upload_dir . $filename . '.' . $thumb_ext;

    $success = match ($thumb_ext) {
      'webp' => imagewebp($canvas, $thumb_path, 100),
      'jpg'  => imagejpeg($canvas, $thumb_path, 90),
      'png'  => imagepng($canvas, $thumb_path),
      default => false
    };
    $return_path = $thumb_path;
    if ($success && $add_watermark && method_exists($this, 'addWatermark')) {
      $row = $this->db->rawQueryOne("SELECT * FROM tbl_watermark LIMIT 1");
      $position = isset($_GET['position']) ? (int)$_GET['position'] : (int)($row['position'] ?? 9);
      $wm_dir = $upload_dir . WATERMARK;
      if (!is_dir($wm_dir)) mkdir($wm_dir, 0755, true);
      $wm_path = $wm_dir . $filename . '.' . $thumb_ext;
      $wm_updated_at = strtotime($row['updated_at'] ?? '');
      $need_regenerate = true;
      if (file_exists($wm_path) && $wm_updated_at > 0 && filemtime($wm_path) >= $wm_updated_at) {
        $need_regenerate = false;
      }
      if ($need_regenerate) {
        @unlink($wm_path);
        if (!$this->addWatermark($thumb_path, $wm_path, $position)) {
          return false;
        }
      }
      $return_path = $wm_path;
    }


    imagedestroy($image);
    imagedestroy($canvas);

    return $success ? $return_path : false;
  }

  public function uploadImage(array $options): string
  {
    $file = $options['file'] ?? null;
    if (empty($file['name']) || empty($file['tmp_name'])) return '';

    $upload_dir = UPLOADS;
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $custom = !empty($options['custom_name']) ? $this->to_slug($options['custom_name']) . '_' . substr(md5(uniqid()), 0, 4) : substr(md5(time() . rand()), 0, 10);
    $filename = $this->generateUniqueFilename($upload_dir, $custom, $ext);
    $target_path = $upload_dir . $filename;

    if (!empty($options['old_file_path']) && file_exists($options['old_file_path'])) {
      $this->deleteFile($options['old_file_path']);
    }

    if (!move_uploaded_file($file['tmp_name'], $target_path)) return '';

    $convert_webp = $options['convert_webp'] ?? false;
    $background = $options['background'] ?? [255, 255, 255, 0];
    while (count($background) < 4) $background[] = 0;

    // Nếu có yêu cầu convert WebP
    if ($convert_webp && in_array($ext, ['jpg', 'jpeg', 'png'])) {
      $img_type = exif_imagetype($target_path);
      $img = match ($img_type) {
        IMAGETYPE_JPEG => imagecreatefromjpeg($target_path),
        IMAGETYPE_PNG  => imagecreatefrompng($target_path),
        default => null
      };
      if ($img) {
        imagepalettetotruecolor($img);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        $w = imagesx($img);
        $h = imagesy($img);
        $canvas = imagecreatetruecolor($w, $h);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, ...$background));
        imagecopy($canvas, $img, 0, 0, 0, 0, $w, $h);
        imagedestroy($img);
        $webp_path = $upload_dir . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
        imagewebp($canvas, $webp_path, 100);
        imagedestroy($canvas);
        @unlink($target_path); // Xoá ảnh gốc (jpg/png) sau khi chuyển webp
        return basename($webp_path);
      }
    }

    // Không nên chèn watermark vào ảnh gốc nữa — watermark sẽ dùng khi render thumb
    return basename($target_path);
  }
  public function getImage(array $data = []): string
  {
    $defaults = [
      'file' => '',
      'alt' => '',
      'title' => '',
      'class' => 'lazy',
      'id' => '',
      'width' => '',
      'height' => '',
      'zc' => 1,
      'thumb' => true,
      'lazy' => true,
      'style' => '',
      'src_only' => false,
      'attr' => '',
    ];
    $opt = array_merge($defaults, $data);
    $filename = ltrim(str_replace(UPLOADS, '', $opt['file']), '/');
    if (empty($filename)) {
      $src = NO_IMG;
    } else {
      if ($opt['thumb'] && $opt['width'] && $opt['height']) {
        $src = BASE . "thumb/{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$filename}";
        $absPath = rtrim(UPLOADS, '/') . "/thumb/{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$filename}";
      } else {
        $src = BASE_ADMIN . UPLOADS . $filename;
        $absPath = UPLOADS . $filename;
      }
      if (file_exists($absPath)) {
        $src .= '?v=' . filemtime($absPath);
      } else {
        $src .= '?v=' . time();
      }
    }
    if ($opt['src_only']) return $src;
    $html = '<img src="' . htmlspecialchars($src) . '"';
    $html .= $opt['class'] ? ' class="' . htmlspecialchars($opt['class']) . '"' : '';
    $html .= $opt['id'] ? ' id="' . htmlspecialchars($opt['id']) . '"' : '';
    $html .= $opt['style'] ? ' style="' . htmlspecialchars($opt['style']) . '"' : '';
    $html .= $opt['width'] ? ' width="' . (int)$opt['width'] . '"' : '';
    $html .= $opt['height'] ? ' height="' . (int)$opt['height'] . '"' : '';
    $html .= $opt['attr'] ? ' ' . $opt['attr'] : '';
    $alt = htmlspecialchars($opt['alt'] ?: pathinfo($filename, PATHINFO_FILENAME));
    $title = htmlspecialchars($opt['title'] ?: $alt);
    $html .= ' alt="' . $alt . '" title="' . $title . '"';
    $html .= $opt['lazy'] ? ' loading="lazy"' : '';
    $html .= ' onerror="this.src=\'' . NO_IMG . '\'"';
    $html .= '>';
    return $html;
  }
  public function getImageCustom(array $data = []): string
  {
    global $config;

    $defaults = [
      'file'      => '',
      'alt'       => '',
      'title'     => '',
      'class'     => '',
      'id'        => '',
      'width'     => 300,
      'height'    => 300,
      'zc'        => 1,
      'thumb'     => null,
      'lazy'      => true,
      'style'     => '',
      'attr'      => '',
      'src_only'  => false,
      'srcset'    => false,
      'sizes'     => [],
      'watermark' => false,
      'position'  => 9,
      'point-srcset' => $config['website']['point-srcset'] ?? []
    ];

    $opt = array_merge($defaults, $data);

    $file = ltrim(str_replace(UPLOADS, '', $opt['file']), '/');
    if (empty($file)) {
      $src = NO_IMG;
    } else {
      $timestamp = time();
      $baseFile  = basename($file);
      $folder    = dirname($file) !== '.' ? dirname($file) . '/' : '';

      // Xác định đường dẫn ảnh
      if (!isset($data['thumb'])) {
        $opt['thumb'] = $opt['width'] && $opt['height'] && $opt['zc'];
      }

      if ($opt['watermark']) {
        $src = BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$folder}{$baseFile}?wm=1&v={$timestamp}";
      } elseif ($opt['thumb']) {
        $src = BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$folder}{$baseFile}?v={$timestamp}";
      } else {
        $src = BASE_ADMIN . UPLOADS . $folder . $baseFile . "?v={$timestamp}";
      }
    }

    if ($opt['src_only']) {
      return $src;
    }
    $srcset = $sizes = '';
    if (
      $opt['srcset'] &&
      !empty($opt['point-srcset']) &&
      $opt['thumb'] &&
      !$opt['watermark']
    ) {
      $ratio = $opt['width'] / $opt['height'];
      $srcsets = [];

      foreach ($opt['point-srcset'] as $breakpoint => $scale) {
        $w = round($breakpoint / $scale);
        if ($w > $opt['width']) continue;
        $h = round($w / $ratio);
        $srcsets[] = BASE . THUMB . "{$w}x{$h}x{$opt['zc']}/{$file} {$w}w";
        $opt['sizes'][] = "(max-width:{$breakpoint}px) {$w}px";
      }

      $srcsets[] = BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$file} {$opt['width']}w";
      $opt['sizes'][] = "{$opt['width']}px";

      $srcset = ' srcset="' . implode(', ', $srcsets) . '"';
      $sizes = ' sizes="' . implode(', ', $opt['sizes']) . '"';
    }

    // Gán alt/title/style
    $alt = htmlspecialchars($opt['alt'] ?: pathinfo($file, PATHINFO_FILENAME));
    $title = htmlspecialchars($opt['title'] ?: $alt);
    $style = trim($opt['style']);

    // Tự động thêm height nếu thiếu
    if (empty($opt['height']) && !str_contains($style, 'height')) {
      $style .= ($style ? '; ' : '') . 'height:auto';
    }

    return '<img src="' . htmlspecialchars($src) . '"'
      . ($opt['width'] ? ' width="' . (int)$opt['width'] . '"' : '')
      . (!empty($opt['height']) && $opt['height'] !== 'auto' ? ' height="' . (int)$opt['height'] . '"' : '')
      . ($opt['class'] ? ' class="' . htmlspecialchars($opt['class']) . '"' : '')
      . ($opt['id'] ? ' id="' . htmlspecialchars($opt['id']) . '"' : '')
      . ($style ? ' style="' . htmlspecialchars($style) . '"' : '')
      . ($opt['lazy'] ? ' loading="lazy"' : '')
      . ($opt['attr'] ? ' ' . $opt['attr'] : '')
      . $srcset . $sizes
      . ' alt="' . $alt . '" title="' . $title . '"'
      . ' onerror="this.src=\'' . NO_IMG . '\'"'
      . '>';
  }
  function renderPagination($current_page, $total_pages, $base_url = 'index.php')
  {
    if ($total_pages <= 1) return '';
    $queryParams = $_GET;
    unset($queryParams['p']);
    $queryString = http_build_query($queryParams);
    $queryString = $queryString ? $queryString . '&' : '';

    $html = '<ul class="pagination flex-wrap justify-content-center mb-0">';
    $html .= '<li class="page-item"><a class="page-link">Trang ' . $current_page . ' / ' . $total_pages . '</a></li>';

    if ($current_page > 1) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?' . $queryString . 'p=' . ($current_page - 1) . '">Trước</a></li>';
    }

    $range = 2;
    for ($i = 1; $i <= $total_pages; $i++) {
      if (
        $i == 1 || $i == 2 || $i == $total_pages || $i == $total_pages - 1 ||
        ($i >= $current_page - $range && $i <= $current_page + $range)
      ) {
        $active_class = ($i == $current_page) ? 'active' : '';
        $html .= '<li class="page-item ' . $active_class . '">';
        $html .= '<a class="page-link" href="' . $base_url . '?' . $queryString . 'p=' . $i . '">' . $i . '</a>';
        $html .= '</li>';
      } elseif (
        ($i == 3 && $current_page - $range > 4) ||
        ($i == $total_pages - 2 && $current_page + $range < $total_pages - 3)
      ) {
        $html .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
      }
    }

    if ($current_page < $total_pages) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?' . $queryString . 'p=' . ($current_page + 1) . '">Tiếp</a></li>';
      $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?' . $queryString . 'p=' . $total_pages . '">Cuối</a></li>';
    }

    $html .= '</ul>';
    return $html;
  }
  function renderPagination_tc($current_page, $total_pages, $base_url)
  {
    if ($total_pages <= 1) return '';
    $pagination_html = '<ul class="pagination flex-wrap justify-content-center mb-0">';
    if ($current_page > 1) {
      $pagination_html .= '<li class="page-item">';
      $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page - 1) . '"><i class="fas fa-angle-left"></i></a>';
      $pagination_html .= '</li>';
    } else {
      $pagination_html .= '<li class="page-item disabled">';
      $pagination_html .= '<a class="page-link"><i class="fas fa-angle-left"></i></a>';
      $pagination_html .= '</li>';
    }
    $range = 2;
    $show_dots = false;
    for ($i = 1; $i <= $total_pages; $i++) {
      if (
        $i == 1 || $i == $total_pages || ($i >= $current_page - $range && $i <= $current_page + $range)
      ) {
        if ($show_dots) {
          $pagination_html .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
          $show_dots = false;
        }
        $active_class = ($i === $current_page) ? 'active' : '';
        $pagination_html .= '<li class="page-item ' . $active_class . '">';
        $pagination_html .= '<a class="page-link" href="' . $base_url . $i . '">' . $i . '</a>';
        $pagination_html .= '</li>';
      } else {
        $show_dots = true;
      }
    }
    if ($current_page < $total_pages) {
      $pagination_html .= '<li class="page-item">';
      $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page + 1) . '"><i class="fas fa-angle-right"></i></a>';
      $pagination_html .= '</li>';
    } else {
      $pagination_html .= '<li class="page-item disabled">';
      $pagination_html .= '<a class="page-link"><i class="fas fa-angle-right"></i></a>';
      $pagination_html .= '</li>';
    }
    $pagination_html .= '<li class="page-item">';
    $pagination_html .= '<a class="page-link" href="' . $base_url . $total_pages . '"><i class="fa-solid fa-angles-right"></i></a>';
    $pagination_html .= '</li>';
    $pagination_html .= '</ul>';
    return $pagination_html;
  }
  public function to_slug($string)
  {
    $search = array(
      '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
      '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
      '#(ì|í|ị|ỉ|ĩ)#',
      '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
      '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
      '#(ỳ|ý|ỵ|ỷ|ỹ)#',
      '#(đ)#',
      '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
      '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
      '#(Ì|Í|Ị|Ỉ|Ĩ)#',
      '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
      '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
      '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
      '#(Đ)#',
      "/[^a-zA-Z0-9\-\_]/",
    );
    $replace = array(
      'a',
      'e',
      'i',
      'o',
      'u',
      'y',
      'd',
      'A',
      'E',
      'I',
      'O',
      'U',
      'Y',
      'D',
      '-',
    );
    $string = preg_replace($search, $replace, $string);
    $string = preg_replace('/(-)+/', '-', $string);
    $string = strtolower($string);
    return $string;
  }
  public function isGoogleSpeed()
  {
    if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') === false) {
      return false;
    }
    return true;
  }
  public function stringRandom($sokytu = 10)
  {
    $str = '';
    $chuoi = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $max = strlen($chuoi) - 1;
    for ($i = 0; $i < $sokytu; $i++) {
      $str .= $chuoi[mt_rand(0, $max)];
    }
    return $str;
  }
  public function generateHash($length = 10)
  {
    return $this->stringRandom($length);
  }
  function darkenColor($hex, $percent = 10)
  {
    $hex = ltrim($hex, '#');
    if (strlen($hex) == 3) {
      $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $r = max(0, min(255, $r - ($r * $percent / 100)));
    $g = max(0, min(255, $g - ($g * $percent / 100)));
    $b = max(0, min(255, $b - ($b * $percent / 100)));
    return sprintf("%02x%02x%02x", $r, $g, $b);
  }
}
