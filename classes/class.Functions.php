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

  /* Markdown */
  public function markdown($path = '', $params = array())
  {
    $content = '';

    if (!empty($path)) {
      ob_start();
      include dirname(__DIR__) . "/sample/" . $path . ".php";
      $content = ob_get_contents();
      ob_clean();
    }

    return $content;
  }
  /* Lấy getPageURL */
  public function getPageURL()
  {
    $pageURL = 'http';
    if (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") $pageURL .= "s";
    $pageURL .= "://";
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    return $pageURL;
  }
  /* Lấy getCurrentPageURL */
  public function getCurrentPageURL()
  {
    $pageURL = 'http';
    if (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") $pageURL .= "s";
    $pageURL .= "://";
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    $urlpos = strpos($pageURL, "?p");
    $pageURL = ($urlpos) ? explode("?p=", $pageURL) : explode("&p=", $pageURL);
    return $pageURL[0];
  }

  /* Lấy getCurrentPageURL Cano */
  public function getCurrentPageURL_CANO()
  {
    $pageURL = 'http';
    if (array_key_exists('HTTPS', $_SERVER) && $_SERVER["HTTPS"] == "on") $pageURL .= "s";
    $pageURL .= "://";
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    $pageURL = str_replace("amp/", "", $pageURL);
    $urlpos = strpos($pageURL, "?p");
    $pageURL = ($urlpos) ? explode("?p=", $pageURL) : explode("&p=", $pageURL);
    $pageURL = explode("?", $pageURL[0]);
    $pageURL = explode("#", $pageURL[0]);
    $pageURL = explode("index", $pageURL[0]);
    return $pageURL[0];
  }
  public function update_views(string $table, string $slug, string $lang = 'vi'): array|false
  {
    $row = $this->db->rawQueryOne("SELECT * FROM `$table` WHERE slug{$lang} = ? LIMIT 1", [$slug]);
    if (!$row) return false;
    $this->db->rawQuery("UPDATE `$table` SET views = views + 1 WHERE slug{$lang} = ?", [$slug]);
    return $row;
  }

  private function buildWhere(array $options): array
  {
    $where = [];
    $params = [];
    $prefix = !empty($options['alias']) ? $options['alias'] . '.' : '';
    if (!empty($options['status'])) {
      $statuses = is_array($options['status']) ? $options['status'] : explode(',', $options['status']);
      foreach ($statuses as $status) {
        $where[] = "FIND_IN_SET(?, {$prefix}status)";
        $params[] = trim($status);
      }
    }
    $filters = [
      'id'         => '=',
      'id_list'    => '=',
      'id_cat'     => '=',
      'id_parent'  => '=',
      'type'       => '=',
      'exclude_id' => '!='
    ];
    foreach ($filters as $field => $operator) {
      if (isset($options[$field]) && $options[$field] !== '') {
        $column = ($field === 'exclude_id') ? 'id' : $field;
        $where[] = "{$prefix}`$column` $operator ?";
        $params[] = $options[$field];
      }
    }
    if (!empty($options['keyword'])) {
      $where[] = "({$prefix}`namevi` LIKE ? OR {$prefix}`nameen` LIKE ?)";
      $params[] = '%' . $options['keyword'] . '%';
      $params[] = '%' . $options['keyword'] . '%';
    }
    $sql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
    return ['sql' => $sql, 'params' => $params];
  }
  public function show_data(array $options = []): array
  {
    if (empty($options['table'])) return [];
    $table   = $options['table'];
    $alias   = $options['alias'] ?? '';
    $select  = $options['select'] ?? '*';
    $join    = $options['join'] ?? '';
    $order   = $options['order_by'] ?? $options['order'] ?? (($alias ? "$alias." : "") . "numb ASC, " . ($alias ? "$alias." : "") . "id DESC");
    $whereData = $this->buildWhere($options);
    $sql = "SELECT $select FROM `$table`" . ($alias ? " $alias" : '') . ($join ? " $join" : '') . $whereData['sql'] . " ORDER BY $order";
    if (!empty($options['pagination']) && is_array($options['pagination'])) {
      [$limit, $curPage] = $options['pagination'];
      $limit = (int)$limit;
      $curPage = max(1, (int)$curPage);
      $offset = ($curPage - 1) * $limit;
      $sql .= " LIMIT $limit OFFSET $offset";
    } elseif (!empty($options['limit'])) {
      $limit = (int)$options['limit'];
      $offset = isset($options['offset']) ? (int)$options['offset'] : 0;
      $sql .= " LIMIT $limit OFFSET $offset";
    }
    $result = $this->db->rawQueryArray($sql, $whereData['params']);
    if (!empty($options['limit']) && $options['limit'] == 1) {
      return $result[0] ?? null;
    }
    return $result;
  }
  public function count_data(array $options = []): int
  {
    if (empty($options['table'])) return 0;
    $table  = $options['table'];
    $alias  = $options['alias'] ?? '';
    $join   = $options['join'] ?? '';
    $idCol  = ($alias ? "$alias." : "") . "id";
    $whereData = $this->buildWhere($options);
    $sql = "SELECT COUNT(DISTINCT $idCol) AS total FROM `$table`" . ($alias ? " $alias" : '') . ($join ? " $join" : '') . $whereData['sql'];
    $row = $this->db->rawQueryOne($sql, $whereData['params']);
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
      $this->transfer($result ? capnhathinhanhthanhcong : capnhathinhanhthatbai, $redirect_url, $result);
    }
    return $result;
  }
  public function save_seo(string $type, int $id_parent, array $data, array $langs, string $act): void
  {
    $seo_table = 'tbl_seo';
    $fields_multi = ['title', 'keywords', 'description', 'schema'];
    $data_sql = ['id_parent' => $id_parent, 'type' => $type, 'act' => $act];
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
    $existing = $this->db->rawQueryOne("SELECT id FROM `$seo_table` WHERE id_parent = ? AND `type` = ? AND `act` = ?", [$id_parent, $type, $act]);
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
  public function save_data($data, $files = null, $id = null, $options = [])
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $table = $options['table'] ?? '';
    $fields_multi = $options['fields_multi'] ?? [];
    $fields_common = $options['fields_common'] ?? [];
    $fields_options = $options['fields_options'] ?? [];
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
    $type = $data_prepared['type'] ?? '';
    if (!empty($fields_options)) {
      $options_data = [];
      foreach ($fields_options as $field) {
        $options_data[$field] = $data[$field] ?? '';
      }
      $data_prepared['options'] = json_encode($options_data, JSON_UNESCAPED_UNICODE);
    }
    if (!empty($status_flags)) {
      $data_prepared['status'] = implode(',', array_filter(array_keys($status_flags ?? []), fn($f) => !empty($data[$f])));
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
    $has_file_column = is_array($files) && isset($files['file']) && is_uploaded_file($files['file']['tmp_name']);
    if ($has_file_column && !empty($id)) {
      $old = $this->db->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
      $old_filename = $old['file'] ?? '';
    }
    if ($has_file_column) {
      $thumb_filename = $this->uploadImage([
        'file' => $files['file'],
        'custom_name' => $data_prepared['namevi'] ?? $type,
        'old_file_path' => UPLOADS . $old_filename,
        'convert_webp' => $convert_webp,
        'background' => $options['background'] ?? [255, 255, 255, 0]
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
      if ($has_file_column || (!empty($data['photo_deleted']) && $data['photo_deleted'] == '1')) {
        $fields[] = "`file` = ?";
        $params[] = $thumb_filename;
      }
      $params[] = (int)$id;
      $result = $this->db->execute("UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ?", $params);
      if ($enable_seo && $result) {
        $this->save_seo($type, (int)$id, $data, $langs, $options['act'] ?? '');
      }
      if ($enable_gallery && !empty($data['deleted_images'])) {
        foreach (explode('|', $data['deleted_images']) as $gid) {
          $gid = (int)$gid;
          if ($gid > 0) {
            $gallery = $this->db->rawQueryOne("SELECT `file` FROM tbl_gallery WHERE id = ?", [$gid]);
            if ($gallery && !empty($gallery['file'])) {
              @unlink(UPLOADS . $gallery['file']);
            }
            $this->db->execute("DELETE FROM tbl_gallery WHERE id = ?", [$gid]);
          }
        }
      }
      if ($enable_gallery && !empty($data['id-filer'])) {
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
      $msg = $result ? capnhatdulieuthanhcong : capnhatdulieubiloi;
    } else {
      $columns = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns), '?');
      $params = array_values($data_prepared);
      if ($has_file_column) {
        $columns[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }
      $inserted = $this->db->execute("INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")", $params);
      $insert_id = $inserted ? $this->db->getInsertId() : 0;
      if ($enable_seo && $insert_id) {
        $this->save_seo($type, $insert_id, $data, $langs, $options['act'] ?? '');
      }
      if ($enable_gallery && !empty($files['files']['name'][0])) {
        $this->save_gallery($data, $files, $insert_id, $type, false);
      }
      $msg = $inserted ? capnhatdulieuthanhcong : capnhatdulieubiloi;
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
        $currentBase === $filename || preg_match('/^' . preg_quote($filenameNoExt, '/') . '-[a-z0-9]{8}$/i', $currentNoExt) || ($currentNoExt === $filenameNoExt && in_array($currentExt, ['webp', 'json']))
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
    $redirect_page = $options['redirect_page'] ?? '';
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
    if ($table === 'tbl_gallery') {
      if (!empty($row['file'])) {
        $this->deleteFile(UPLOADS . $row['file']);
      }

      $deleted = $this->db->execute("DELETE FROM tbl_gallery WHERE id = ?", [$id]);

      $this->transfer(
        $deleted ? "Xóa ảnh thành công!" : "Xóa ảnh thất bại!",
        $redirect_page,
        $deleted
      );
      return;
    }
    if ($delete_file && !empty($row['file'])) {
      $this->deleteFile(UPLOADS . $row['file']);
    }
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

    $this->transfer($deleted ? xoadulieuthanhcong : xoadulieubiloi, $redirect_page, $deleted);
  }
  public function deleteMultiple_data(array $options = [])
  {
    $listid = $options['listid'] ?? '';
    $table = $options['table'] ?? '';
    $type = $options['type'] ?? '';
    $redirect_page = $options['redirect_page'] ?? '';
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
    $this->transfer($delete_result ? xoadulieuthanhcong : xoadulieubiloi, $redirect_page, $delete_result);
  }
  public function galleryFiler($numb = 1, $id = 0, $photo = '', $name = '', $col = '')
  {
    $params = array();
    $params['numb'] = $numb;
    $params['id'] = $id;
    $params['photo'] = $photo;
    $params['name'] = $name;
    $params['col'] = $col;
    $str = $this->markdown('gallery/admin', $params);

    return $str;
  }

  function isItemActive(array $activeList, string $currentPage, string $currentType): bool
  {
    $currentAct = $_GET['act'] ?? '';

    foreach ($activeList as $activeItem) {
      parse_str(ltrim($activeItem, '?'), $activeParams);

      if (
        ($activeParams['page'] ?? '') === $currentPage &&
        ($activeParams['type'] ?? '') === $currentType &&
        ($activeParams['act'] ?? '') === $currentAct
      ) {
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
  public function getLinkCategory($table = '', $selectedId = 0, $title_select = chondanhmuc)
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
      $str .= '<option disabled>' . khongcodulieu . '</option>';
    }
    $str .= '</select>';
    return $str;
  }
  public function getAjaxCategory($table = '', $selectedId = 0, $id_list = null, $id_cat = null, $title_select = chondanhmuc)
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
      $data_level = $data_table =  $data_child = '';
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
  /* Format money */
  public function formatMoney($price = 0, $unit = 'đ', $html = false)
  {
    $str = '';

    if ($price) {
      $str .= number_format($price, 0, ',', '.');
      if ($unit != '') {
        if ($html) {
          $str .= '<span>' . $unit . '</span>';
        } else {
          $str .= $unit;
        }
      }
    }

    return $str;
  }

  /* Is phone */
  public function isPhone($number)
  {
    $number = trim($number);
    if (preg_match_all('/^(0|84)(2(0[3-9]|1[0-6|8|9]|2[0-2|5-9]|3[2-9]|4[0-9]|5[1|2|4-9]|6[0-3|9]|7[0-7]|8[0-9]|9[0-4|6|7|9])|3[2-9]|5[5|6|8|9]|7[0|6-9]|8[0-6|8|9]|9[0-4|6-9])([0-9]{7})$/m', $number, $matches, PREG_SET_ORDER, 0)) {
      return true;
    } else {
      return false;
    }
  }

  /* Format phone */
  public function formatPhone($number, $dash = ' ')
  {
    if (preg_match('/^(\d{4})(\d{3})(\d{3})$/', $number, $matches) || preg_match('/^(\d{3})(\d{4})(\d{4})$/', $number, $matches)) {
      return $matches[1] . $dash . $matches[2] . $dash . $matches[3];
    }
  }

  /* Parse phone */
  public function parsePhone($number)
  {
    return (!empty($number)) ? preg_replace('/[^0-9]/', '', $number) : '';
  }

  /* Check letters and nums */
  public function isAlphaNum($str)
  {
    if (preg_match('/^[a-z0-9]+$/', $str)) {
      return true;
    } else {
      return false;
    }
  }

  /* Is email */
  public function isEmail($email)
  {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    } else {
      return false;
    }
  }

  /* Is match */
  public function isMatch($value1, $value2)
  {
    if ($value1 == $value2) {
      return true;
    } else {
      return false;
    }
  }

  /* Is decimal */
  public function isDecimal($number)
  {
    if (preg_match('/^\d{1,10}(\.\d{1,4})?$/', $number)) {
      return true;
    } else {
      return false;
    }
  }

  /* Is coordinates */
  public function isCoords($str)
  {
    if (preg_match('/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $str)) {
      return true;
    } else {
      return false;
    }
  }

  /* Is url */
  public function isUrl($str)
  {
    if (preg_match('/^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/', $str)) {
      return true;
    } else {
      return false;
    }
  }

  /* Is url youtube */
  public function isYoutube($str)
  {
    if (preg_match('/https?:\/\/(?:[a-zA_Z]{2,3}.)?(?:youtube\.com\/watch\?)((?:[\w\d\-\_\=]+&amp;(?:amp;)?)*v(?:&lt;[A-Z]+&gt;)?=([0-9a-zA-Z\-\_]+))/i', $str)) {
      return true;
    } else {
      return false;
    }
  }

  /* Is fanpage */
  public function isFanpage($str)
  {
    if (preg_match('/^(https?:\/\/)?(?:www\.)?facebook\.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.]*)/', $str)) {
      return true;
    } else {
      return false;
    }
  }

  /* Is date */
  public function isDate($str)
  {
    if (preg_match('/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/', $str)) {
      return true;
    } else {
      return false;
    }
  }

  /* Is date by format */
  public function isDateByFormat($str, $format = 'd/m/Y')
  {
    $dt = DateTime::createFromFormat($format, $str);
    return $dt && $dt->format($format) == $str;
  }

  /* Is number */
  public function isNumber($numbs)
  {
    if (preg_match('/^[0-9]+$/', $numbs)) {
      return true;
    } else {
      return false;
    }
  }

  /* Check account */
  public function checkAccount($data = '', $type = '', $tbl = '', $id = 0)
  {
    $result = false;
    $row = array();

    if (!empty($data) && !empty($type) && !empty($tbl)) {
      $where = (!empty($id)) ? ' and id != ' . $id : '';
      $row = $this->db->rawQueryOne("select id from #_$tbl where $type = ? $where limit 0,1", array($data));

      if (!empty($row)) {
        $result = true;
      }
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
    $errorMsg = duongdandatontaiduongdantruycapmucnaycothebitrunglap;
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
        if (!($r > 240 && $g > 240 && $b > 240) && $a < 120) {
          if ($x < $min_x) $min_x = $x;
          if ($y < $min_y) $min_y = $y;
          if ($x > $max_x) $max_x = $x;
          if ($y > $max_y) $max_y = $y;
        }
      }
    }
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
  private function createCanvas(int $w, int $h, int $image_type, bool|array $background): GdImage|false
  {
    $canvas = imagecreatetruecolor($w, $h);
    $is_transparent = in_array($image_type, [IMAGETYPE_PNG, IMAGETYPE_WEBP]);

    if ($is_transparent && $background === false) {
      imagealphablending($canvas, false);
      imagesavealpha($canvas, true);
      imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
    } elseif (is_array($background) && count($background) === 4) {
      imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, ...$background));
    } else {
      imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
    }

    return $canvas;
  }
  private function generateThumbImage(string $source, string $dest, int $w, int $h, int $zc, callable $create_func, int $type, string $ext, bool|array $background): bool
  {
    $image = @$create_func($source);
    if (!$image) return false;
    [$width_orig, $height_orig] = getimagesize($source);
    $src_ratio = $width_orig / $height_orig;
    $dst_ratio = $w / $h;
    $is_transparent = in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP]);
    if ($is_transparent) {
      imagepalettetotruecolor($image);
      imagealphablending($image, true);
      imagesavealpha($image, true);
    }
    if ($zc === 4 && method_exists($this, 'cropTransparentOrWhiteBorder')) {
      $resize_w = ($src_ratio > $dst_ratio) ? $w : intval($h * $src_ratio);
      $resize_h = ($src_ratio > $dst_ratio) ? intval($w / $src_ratio) : $h;
      $temp = $this->createCanvas($resize_w, $resize_h, $type, $background);
      imagecopyresampled($temp, $image, 0, 0, 0, 0, $resize_w, $resize_h, $width_orig, $height_orig);
      $canvas = $this->cropTransparentOrWhiteBorder($temp) ?: $temp;
    } elseif (in_array($zc, [2, 3])) {
      $resize_w = ($src_ratio > $dst_ratio) ? $w : intval($h * $src_ratio);
      $resize_h = ($src_ratio > $dst_ratio) ? intval($w / $src_ratio) : $h;
      $canvas = $this->createCanvas($w, $h, $type, $background);
      $dst_x = intval(($w - $resize_w) / 2);
      $dst_y = intval(($h - $resize_h) / 2);
      imagecopyresampled($canvas, $image, $dst_x, $dst_y, 0, 0, $resize_w, $resize_h, $width_orig, $height_orig);
    } else {
      $canvas = $this->createCanvas($w, $h, $type, $background);
      $src_w = ($src_ratio > $dst_ratio) ? intval($height_orig * $dst_ratio) : $width_orig;
      $src_h = ($src_ratio > $dst_ratio) ? $height_orig : intval($width_orig / $dst_ratio);
      $src_x = intval(($width_orig - $src_w) / 2);
      $src_y = intval(($height_orig - $src_h) / 2);
      imagecopyresampled($canvas, $image, 0, 0, $src_x, $src_y, $w, $h, $src_w, $src_h);
    }
    $saved = match ($ext) {
      'webp' => imagewebp($canvas, $dest, 100),
      'jpg'  => imagejpeg($canvas, $dest, 90),
      'png'  => imagepng($canvas, $dest),
      default => false
    };
    imagedestroy($image);
    imagedestroy($canvas);
    return $saved;
  }
  public function addWatermark($source_path, $destination_path)
  {
    $row = $this->db->rawQueryOne("SELECT file, options FROM tbl_photo WHERE type = 'watermark' LIMIT 1");
    if (empty($row['file'])) return false;
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
    $watermark_path = UPLOADS . $row['file'];
    if (!file_exists($watermark_path)) return false;
    $wm_src = imagecreatefrompng($watermark_path);
    if (!$wm_src) return false;
    imagesavealpha($wm_src, true);
    $wm_width = imagesx($wm_src);
    $wm_height = imagesy($wm_src);
    $options = json_decode($row['options'] ?? '', true);
    $position   = (int)($options['position'] ?? 9);
    $per        = floatval($options['per'] ?? 2);
    $small_per  = floatval($options['small_per'] ?? 3);
    $max        = intval($options['max'] ?? 120);
    $min        = intval($options['min'] ?? 120);
    $opacity    = floatval($options['opacity'] ?? 100);
    $offset_x   = intval($options['offset_x'] ?? 0);
    $offset_y   = intval($options['offset_y'] ?? 0);
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
  public function createThumb(string $source_path, string $thumb_name, bool $background = false, bool $add_watermark = false, bool $convert_webp = false): string|false
  {
    if (!file_exists($source_path) || !preg_match('/^(\d+)x(\d+)(x(\d+))?$/', $thumb_name, $m)) return false;
    $thumb_width  = (int)$m[1];
    $thumb_height = (int)$m[2];
    $zoom_crop    = isset($m[4]) ? (int)$m[4] : 1;
    $image_type = exif_imagetype($source_path);
    $ext_map = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    $create_func = [IMAGETYPE_JPEG => 'imagecreatefromjpeg', IMAGETYPE_PNG => 'imagecreatefrompng', IMAGETYPE_WEBP => 'imagecreatefromwebp'];
    if (!isset($ext_map[$image_type])) return false;
    $ext = $ext_map[$image_type];
    $thumb_ext = ($convert_webp || $ext === 'webp') ? 'webp' : $ext;
    $filename = pathinfo($source_path, PATHINFO_FILENAME);
    $base_dir = UPLOADS . THUMB . "{$thumb_width}x{$thumb_height}x{$zoom_crop}/";
    if ($add_watermark && method_exists($this, 'addWatermark')) {
      $wm_data = $this->db->rawQueryOne("SELECT file, options, date_updated, status FROM tbl_photo WHERE type = 'watermark' LIMIT 1");

      $use_watermark = (!empty($wm_data) && in_array('hienthi', explode(',', $wm_data['status'] ?? '')));

      if ($use_watermark) {
        $options = json_decode($wm_data['options'] ?? '', true);
        $updated = strtotime($wm_data['date_updated'] ?? '') ?: time();
        $wm_hash = substr(md5(json_encode($options ?? []) . '_' . $updated), 0, 8);

        $wm_dir = rtrim($base_dir, '/') . '/' . trim(WATERMARK, '/');
        if (!is_dir($wm_dir)) mkdir($wm_dir, 0755, true);

        $pattern = $wm_dir . '/' . $filename . '-*.' . $thumb_ext;
        foreach (glob($pattern) as $old_file) {
          if (strpos($old_file, "-$wm_hash.$thumb_ext") === false) {
            @unlink($old_file);
          }
        }

        $wm_path = $wm_dir . '/' . $filename . "-$wm_hash." . $thumb_ext;
        if (file_exists($wm_path)) return $wm_path;

        $thumb_temp = tempnam(sys_get_temp_dir(), 'thumb_');
        $thumb_created = $this->generateThumbImage($source_path, $thumb_temp, $thumb_width, $thumb_height, $zoom_crop, $create_func[$image_type], $image_type, $thumb_ext, $background);
        if (!$thumb_created) return false;

        if (!$this->addWatermark($thumb_temp, $wm_path, $options)) {
          @unlink($thumb_temp);
          return false;
        }

        @unlink($thumb_temp);
        return $wm_path;
      }
      // Nếu không dùng watermark thì tiếp tục tạo ảnh thường
    }

    $thumb_path = $base_dir . $filename . '.' . $thumb_ext;
    if (file_exists($thumb_path)) return $thumb_path;
    if (!is_dir($base_dir)) mkdir($base_dir, 0755, true);
    $created = $this->generateThumbImage($source_path, $thumb_path, $thumb_width, $thumb_height, $zoom_crop, $create_func[$image_type], $image_type, $thumb_ext, $background);
    return $created ? $thumb_path : false;
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
        @unlink($target_path);
        return basename($webp_path);
      }
    }
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
    $filename = ltrim(str_replace(UPLOADS, '', (string)$opt['file']), '/');
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
    $file = ltrim(str_replace(UPLOADS, '', (string)$opt['file']), '/');
    $baseFile  = basename($file);
    $folder = dirname($file) !== '.' ? dirname($file) . '/' : '';
    if (!isset($data['thumb'])) {
      $opt['thumb'] = $opt['width'] && $opt['height'] && $opt['zc'];
    }
    $timestamp = time();
    if ($opt['watermark']) {
      $src = BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$folder}{$baseFile}?wm=1&v={$timestamp}";
    } elseif ($opt['thumb']) {
      $src = BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$folder}{$baseFile}?v={$timestamp}";
    } else {
      $src = BASE_ADMIN . UPLOADS . $folder . $baseFile . "?v={$timestamp}";
    }
    if ($opt['src_only']) return $src;
    $srcset = $sizes = '';
    if ($opt['srcset'] && !empty($opt['point-srcset']) && $opt['thumb'] && !$opt['watermark']) {
      $ratio = $opt['width'] / $opt['height'];
      $srcsets = [];
      foreach ($opt['point-srcset'] as $breakpoint => $scale) {
        $w = round($breakpoint / $scale);
        if ($w > $opt['width']) continue;
        $h = round($w / $ratio);
        $srcsets[] = BASE . THUMB . "{$w}x{$h}x{$opt['zc']}/{$folder}{$baseFile} {$w}w";
        $opt['sizes'][] = "(max-width:{$breakpoint}px) {$w}px";
      }
      $srcsets[] = BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$folder}{$baseFile} {$opt['width']}w";
      $opt['sizes'][] = "{$opt['width']}px";
      $srcset = ' srcset="' . implode(', ', $srcsets) . '"';
      $sizes = ' sizes="' . implode(', ', $opt['sizes']) . '"';
    }
    $alt = htmlspecialchars($opt['alt'] ?: pathinfo($baseFile, PATHINFO_FILENAME));
    $title = htmlspecialchars($opt['title'] ?: $alt);
    $style = trim($opt['style']);
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
      . ' onerror="this.src=\'' . BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/noimage.jpeg" . '\'"'
      . '>';
  }
  function pagination(int $total = 0, int $perPage = 10, int $page = 1, string $baseUrl = 'index.php'): string
  {
    $total_pages = (int)ceil($total / $perPage);
    if ($total_pages <= 1) return '';
    $queryParams = $_GET;
    unset($queryParams['p']);
    $queryString = http_build_query($queryParams);
    $queryString = $queryString ? $queryString . '&' : '';
    $baseUrl .= (strpos($baseUrl, '?') !== false ? '&' : '?') . $queryString;
    $html = '<ul class="pagination flex-wrap justify-content-center mb-0">';
    $html .= '<li class="page-item"><a class="page-link">' . trang . ' ' . $page . ' / ' . $total_pages . '</a></li>';

    if ($page > 1) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'p=' . ($page - 1) . '">' . truoc . '</a></li>';
    }

    $range = 2;
    for ($i = 1; $i <= $total_pages; $i++) {
      if (
        $i == 1 || $i == 2 || $i == $total_pages || $i == $total_pages - 1 ||
        ($i >= $page - $range && $i <= $page + $range)
      ) {
        $active_class = ($i == $page) ? 'active' : '';
        $html .= '<li class="page-item ' . $active_class . '">';
        $html .= '<a class="page-link" href="' . $baseUrl . 'p=' . $i . '">' . $i . '</a>';
        $html .= '</li>';
      } elseif (
        ($i == 3 && $page - $range > 4) ||
        ($i == $total_pages - 2 && $page + $range < $total_pages - 3)
      ) {
        $html .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
      }
    }

    if ($page < $total_pages) {
      $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'p=' . ($page + 1) . '">' . tiep . '</a></li>';
      $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . 'p=' . $total_pages . '">' . cuoi . '</a></li>';
    }

    $html .= '</ul>';
    return $html;
  }
  function pagination_tc(int $total = 0, int $perPage = 10, int $page = 1): string
  {
    $total_pages = (int)ceil($total / $perPage);
    if ($total_pages <= 1) return '';

    $fullUrl = $this->getPageURL();
    $parts = explode('?', $fullUrl, 2);
    $path = rtrim(preg_replace('#/page-\d+#', '', $parts[0]), '/');
    $query = isset($parts[1]) ? '?' . $parts[1] : '';

    $html = '<ul class="pagination flex-wrap justify-content-center mb-0">';

    // Previous
    if ($page > 1) {
      $html .= '<li class="page-item">';
      $html .= '<a class="page-link" href="' . $path . '/page-' . ($page - 1) . $query . '"><i class="fas fa-angle-left"></i></a>';
      $html .= '</li>';
    } else {
      $html .= '<li class="page-item disabled">';
      $html .= '<a class="page-link"><i class="fas fa-angle-left"></i></a>';
      $html .= '</li>';
    }

    // Page numbers with dots
    $range = 2;
    $show_dots = false;
    for ($i = 1; $i <= $total_pages; $i++) {
      if ($i == 1 || $i == $total_pages || ($i >= $page - $range && $i <= $page + $range)) {
        if ($show_dots) {
          $html .= '<li class="page-item disabled"><a class="page-link">...</a></li>';
          $show_dots = false;
        }
        $active_class = ($i === $page) ? 'active' : '';
        $html .= '<li class="page-item ' . $active_class . '">';
        $html .= '<a class="page-link" href="' . $path . '/page-' . $i . $query . '">' . $i . '</a>';
        $html .= '</li>';
      } else {
        $show_dots = true;
      }
    }

    // Next
    if ($page < $total_pages) {
      $html .= '<li class="page-item">';
      $html .= '<a class="page-link" href="' . $path . '/page-' . ($page + 1) . $query . '"><i class="fas fa-angle-right"></i></a>';
      $html .= '</li>';
    } else {
      $html .= '<li class="page-item disabled">';
      $html .= '<a class="page-link"><i class="fas fa-angle-right"></i></a>';
      $html .= '</li>';
    }

    $html .= '</ul>';
    return $html;
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
