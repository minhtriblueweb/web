<?php
require LIBRARIES . 'WebpConvert/vendor/autoload.php';

use WebPConvert\WebPConvert;

class Functions
{
  private $d;
  private $cache;

  function __construct($d, $cache)
  {
    $this->d = $d;
    $this->cache = $cache;
  }
  /* Database maintenance */
  public function databaseMaintenance($action = '', $tables = array())
  {
    $result = array();
    $row = array();

    if (!empty($action) && !empty($tables)) {
      foreach ($tables as $k => $v) {
        foreach ($v as $table) {
          $result = $this->d->rawQuery("$action TABLE $table");

          if (!empty($result)) {
            $row[$k]['table'] = $result[0]['Table'];
            $row[$k]['action'] = $result[0]['Op'];
            $row[$k]['type'] = $result[0]['Msg_type'];
            $row[$k]['text'] = $result[0]['Msg_text'];
          }
        }
      }
    }

    return $row;
  }
  public function buildSchemaProduct($id_pro, $name, $image, $description, $code_pro, $name_brand, $name_author, $url, $price)
  {

    $str = '{';
    $str .= '"@context": "https://schema.org/",';
    $str .= '"@type": "Product",';
    $str .= '"name": "' . $name . '",';
    $str .= '"image":';
    $str .= '[';
    foreach ($image as $k => $v) {
      $str .= '{';
      $str .= '"@context": "https://schema.org/",';
      $str .= '"@type": "ImageObject",';
      $str .= '"contentUrl": "' . $v . '",';
      $str .= '"url": "' . $v . '",';
      $str .= '"license": "' . $url . '",';
      $str .= '"acquireLicensePage": "' . $url . '",';
      $str .= '"creditText": "' . $name . '",';
      $str .= '"copyrightNotice": "' . $name_author . '",';
      $str .= '"creator":';
      $str .= '{';
      $str .= '"@type": "Organization",';
      $str .= '"name": "' . $name_author . '"';
      $str .= '}';
      $str .= '}' . (($k < count($image) - 1) ? ',' : '') . '';
    }
    $str .= '],';
    $str .= '"description": "' . $description . '",';
    $str .= '"sku":"SP0' . $id_pro . '",';
    $str .= '"mpn": "' . $code_pro . '",';
    $str .= '"brand":';
    $str .= '{';
    $str .= '"@type": "Brand",';
    $str .= '"name": "' . $name_brand . '"';
    $str .= '},';
    $str .= '"review":';
    $str .= '{';
    $str .= '"@type": "Review",';
    $str .= '"reviewRating":';
    $str .= '{';
    $str .= '"@type": "Rating",';
    $str .= '"ratingValue": "5",';
    $str .= '"bestRating": "5"';
    $str .= '},';
    $str .= '"author":';
    $str .= '{';
    $str .= '"@type": "Person",';
    $str .= '"name": "' . $name_author . '"';
    $str .= '}';
    $str .= '},';
    $str .= '"aggregateRating":';
    $str .= '{';
    $str .= '"@type": "AggregateRating",';
    $str .= '"ratingValue": "4.4",';
    $str .= '"reviewCount": "89"';
    $str .= '},';
    $str .= '"offers":';
    $str .= '{';
    $str .= '"@type": "Offer",';
    $str .= '"url": "' . $url . '",';
    $str .= '"priceCurrency": "VND",';
    $str .= '"priceValidUntil": "2099-11-20",';
    $str .= '"price": "' . $price . '",';
    $str .= '"itemCondition": "https://schema.org/NewCondition",';
    $str .= '"availability": "https://schema.org/InStock"';
    $str .= '}';
    $str .= '}';

    $str = json_encode(json_decode($str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    return $str;
  }
  /* Build Schema */
  public function buildSchemaArticle($id_news, $name, $image, $ngaytao, $ngaysua, $name_author, $url, $logo, $url_author)
  {
    $str = '{';
    $str .= '"@context": "https://schema.org",';
    $str .= '"@type": "NewsArticle",';
    $str .= '"mainEntityOfPage": ';
    $str .= '{';
    $str .= '"@type": "WebPage",';
    $str .= '"@id": "' . $url . '"';
    $str .= '},';
    $str .= '"headline": "' . $name . '",';
    $str .= '"image":';
    $str .= '{';
    $str .= '"@context": "https://schema.org/",';
    $str .= '"@type": "ImageObject",';
    $str .= '"contentUrl": "' . $image . '",';
    $str .= '"url": "' . $image . '",';
    $str .= '"license": "' . $url . '",';
    $str .= '"acquireLicensePage": "' . $url . '",';
    $str .= '"creditText": "' . $name . '",';
    $str .= '"copyrightNotice": "' . $name_author . '",';
    $str .= '"creator":';
    $str .= '{';
    $str .= '"@type": "Organization",';
    $str .= '"name": "' . $name_author . '"';
    $str .= '}';
    $str .= '},';
    $str .= '"datePublished": "' . date('c', $ngaytao) . '",';
    $str .= '"dateModified": "' . date('c', $ngaysua) . '",';
    $str .= '"author":';
    $str .= '{';
    $str .= '"@type": "Organization",';
    $str .= '"name": "' . $name_author . '",';
    $str .= '"url": "' . $url_author . '"';
    $str .= '},';
    $str .= '"publisher": ';
    $str .= '{';
    $str .= '"@type": "Organization",';
    $str .= '"name": "' . $name_author . '",';
    $str .= '"logo": ';
    $str .= '{';
    $str .= '"@type": "ImageObject",';
    $str .= '"url": "' . $logo . '"';
    $str .= '}';
    $str .= '}';
    $str .= '}';

    $str = json_encode(json_decode($str), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return $str;
  }
  /* Has file */
  public function hasFile($file)
  {
    if (isset($_FILES[$file])) {
      if ($_FILES[$file]['error'] == 4) {
        return false;
      } else if ($_FILES[$file]['error'] == 0) {
        return true;
      }
    } else {
      return false;
    }
  }

  /* Size file */
  public function sizeFile($file)
  {
    if ($this->hasFile($file)) {
      if ($_FILES[$file]['error'] == 0) {
        return $_FILES[$file]['size'];
      }
    } else {
      return 0;
    }
  }

  /* Check file */
  public function checkFile($file)
  {
    global $config;
    $result = true;
    if ($this->hasFile($file)) {
      if ($this->sizeFile($file) > $config['website']['video']['max-size']) {
        $result = false;
      }
    }
    return $result;
  }
  public function webpinfo($file)
  {
    if (!is_file($file)) {
      return false;
    } else {
      $file = realpath($file);
    }
    $fp = fopen($file, 'rb');
    if (!$fp) return false;
    $data = fread($fp, 90);
    fclose($fp);
    unset($fp);
    $header_format = 'A4Riff/' .
      'I1Filesize/' .
      'A4Webp/' .
      'A4Vp/' .
      'A74Chunk';
    $header = unpack($header_format, $data);
    unset($data, $header_format);
    if (!isset($header['Riff']) || strtoupper($header['Riff']) !== 'RIFF') return false;
    if (!isset($header['Webp']) || strtoupper($header['Webp']) !== 'WEBP') return false;
    if (!isset($header['Vp']) || strpos(strtoupper($header['Vp']), 'VP8') === false) return false;
    if (strpos(strtoupper($header['Chunk']), 'ANIM') !== false || strpos(strtoupper($header['Chunk']), 'ANMF') !== false) {
      $header['Animation'] = true;
    } else {
      $header['Animation'] = false;
    }
    if (strpos(strtoupper($header['Chunk']), 'ALPH') !== false) {
      $header['Alpha'] = true;
    } else {
      if (strpos(strtoupper($header['Vp']), 'VP8L') !== false) {
        $header['Alpha'] = true;
      } else {
        $header['Alpha'] = false;
      }
    }
    unset($header['Chunk']);
    return $header;
  }

  public function writeJson($data = array())
  {
    global $configBase;
    if ($data['type'] == 'city') {
      $citys = $this->d->rawQuery("select id, name from #_city where find_in_set('hienthi',status) order by numb,id desc");

      $data = array();
      if (!empty($citys)) {
        foreach ($citys as $k_city => $v_city) {
          $data['citysCentral'][] = $v_city;
          unset($citys[$k_city]);
        }
      }
      /* Put data */
      $this->putJson('city-group.json', $data);
    } else if ($data['type'] == 'district') {
      /* Update */
      if (!empty($data['idCity'])) {
        $district = $this->d->rawQuery("select id, id_city, name from #_district where id_city = ? and find_in_set('hienthi',status) order by numb,id desc", array($data['idCity']));
        /* Put data */
        $this->putJson('district-' . $data['idCity'] . '.json', $district);
      } else /* Create */ {
        $citys = $this->d->rawQuery("select id from #_city where find_in_set('hienthi',status)");
        if (!empty($citys)) {
          $result = array();
          foreach ($citys as $v_city) {
            $district = $this->d->rawQuery("select id, id_city, name from #_district where id_city = ? and find_in_set('hienthi',status) order by numb,id desc", array($v_city['id']));
            /* Put data */
            if (!empty($district)) {
              $this->putJson('district-' . $v_city['id'] . '.json', $district);
            }
          }
        }
      }
    } else if ($data['type'] == 'wards') {
      /* Update */
      if (!empty($data['idCity']) && !empty($data['idDistrict'])) {
        $ward = $this->d->rawQuery("select id, id_city, id_district, name from #_ward where id_city = ? and id_district = ? and find_in_set('hienthi',status) order by numb,id desc", array($data['idCity'], $data['idDistrict']));
        /* Put data */
        $this->putJson('wards-' . $data['idCity'] . '-' . $data['idDistrict'] . '.json', $ward);
      } else /* Create */ {
        $districts = $this->d->rawQuery("select distinct id_city, id from #_district where find_in_set('hienthi',status)");

        if (!empty($districts)) {
          $result = array();

          foreach ($districts as $v_district) {
            $ward = $this->d->rawQuery("select id, id_city, id_district, name from #_ward where id_city = ? and id_district = ? and find_in_set('hienthi',status) order by numb,id desc", array($v_district['id_city'], $v_district['id']));

            /* Put data */
            if (!empty($ward)) {
              $this->putJson('wards-' . $v_district['id_city'] . '-' . $v_district['id'] . '.json', $ward);
            }
          }
        }
      }
    }
    return true;
  }

  /* Put json */
  public function putJson($filename = '', $data = array())
  {

    if (!empty($data)) {
      $data = json_encode($data);
      $file = fopen(JSONS . $filename, "w+");

      if (!empty($file)) {
        fwrite($file, $data);
        fclose($file);
      }
    } else if (file_exists(JSONS . $filename)) {
      $this->deleteFile(JSONS . $filename);
    }
    return true;
  }

  /* Check URL */
  public function checkURL($index = false)
  {
    global $configBase;

    $url = '';
    $urls = array('index', 'index.html', 'trang-chu', 'trang-chu.html');

    if (array_key_exists('REDIRECT_URL', $_SERVER)) {
      $url = explode("/", $_SERVER['REDIRECT_URL']);
    } else {
      $url = explode("/", $_SERVER['REQUEST_URI']);
    }

    if (is_array($url)) {
      $url = $url[count($url) - 1];
      if (strpos($url, "?")) {
        $url = explode("?", $url);
        $url = $url[0];
      }
    }

    if ($index) array_push($urls, "index.php");
    else if (array_search('index.php', $urls)) $urls = array_diff($urls, ["index.php"]);
    if (in_array($url, $urls)) $this->redirect($configBase, 301);
  }

  /* Check Is Ajax Request */
  public function isAjax()
  {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'));
  }

  /* Check HTTP */
  public function checkHTTP($http, $arrayDomain, &$configBase, $configUrl)
  {
    if (count($arrayDomain) == 0 && $http == 'https://') {
      $configBase = 'http://' . $configUrl;
    }
  }

  /* Create sitemap */
  public function createSitemap($com = '', $type = '', $field = '', $table = '', $time = '', $changefreq = '', $priority = '', $lang = 'vi', $orderby = '', $menu = true)
  {
    global $configBase;

    $urlSm = '';
    $sitemap = null;

    if (!empty($type) && !in_array($table, ['photo', 'static'])) {
      $where = "type = ? and find_in_set('hienthi',status)";
      $where .= ($table != 'static') ? 'order by ' . $orderby . ' desc' : '';
      $sitemap = $this->d->rawQuery("select slug$lang, date_created from $table where $where", array($type));
    }

    if ($menu == true && $field == 'id') {
      $urlSm = $configBase . $com;
      echo '<url>';
      echo '<loc>' . $urlSm . '</loc>';
      echo '<lastmod>' . date('c', time()) . '</lastmod>';
      echo '<changefreq>' . $changefreq . '</changefreq>';
      echo '<priority>' . $priority . '</priority>';
      echo '</url>';
    }

    if (!empty($sitemap)) {
      foreach ($sitemap as $value) {
        if (!empty($value['slug' . $lang])) {
          $urlSm = $configBase . $value['slug' . $lang];
          echo '<url>';
          echo '<loc>' . $urlSm . '</loc>';
          echo '<lastmod>' . date('c', $value['date_created']) . '</lastmod>';
          echo '<changefreq>' . $changefreq . '</changefreq>';
          echo '<priority>' . $priority . '</priority>';
          echo '</url>';
        }
      }
    }
  }

  public function validation($data)
  {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  /* Copy image */
  public function copyImg($photo = '')
  {
    $str = '';
    if ($photo != '') {
      $rand = rand(1000, 9999);
      $name = pathinfo($photo, PATHINFO_FILENAME);
      $ext = pathinfo($photo, PATHINFO_EXTENSION);
      $photo_new = $name . '-' . $rand . '.' . $ext;
      $oldpath = UPLOADS . $photo;
      $newpath = UPLOADS . $photo_new;
      if (file_exists($oldpath)) {
        if (copy($oldpath, $newpath)) {
          $str = $photo_new;
        }
      }
    }
    return $str;
  }

  /* Redirect */
  public function redirect($url = '', $response = null)
  {
    header("location:$url", true, $response);
    exit();
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

  /* Lấy IP */
  public function getRealIPAddress()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
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

  /* Kiểm tra đăng nhập */
  public function checkLoginAdmin()
  {
    global $loginAdmin;

    $token = (!empty($_SESSION[$loginAdmin]['token'])) ? $_SESSION[$loginAdmin]['token'] : '';
    $row = $this->d->rawQuery("select secret_key from #_user where secret_key = ? and find_in_set('hienthi',status)", array($token));

    if (count($row) == 1 && $row[0]['secret_key'] != '') {
      return true;
    } else {
      if (!empty($_SESSION[TOKEN])) unset($_SESSION[TOKEN]);
      unset($_SESSION[$loginAdmin]);
      return false;
    }
  }

  /* Mã hóa mật khẩu admin */
  public function encryptPassword($secret = '', $str = '', $salt = '')
  {
    return md5($secret . $str . $salt);
  }

  public function update_views(string $table, string $slug, string $lang = 'vi'): array|false
  {
    $row = $this->d->rawQueryOne("SELECT * FROM `$table` WHERE slug{$lang} = ? LIMIT 1", [$slug]);
    if (!$row) return false;
    $this->d->rawQuery("UPDATE `$table` SET views = views + 1 WHERE slug{$lang} = ?", [$slug]);
    return $row;
  }

  private function buildWhere(array $options): array
  {
    $where = $params = [];
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
      'id_item'    => '=',
      'id_sub'     => '=',
      'id_brand'   => '=',
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
    $result = $this->d->rawQueryArray($sql, $whereData['params']);
    if (!empty($options['limit']) && $options['limit'] == 1) {
      return $result[0] ?? [];
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
    $row = $this->d->rawQueryOne($sql, $whereData['params']);
    return (int)($row['total'] ?? 0);
  }

  public function save_seo(string $type, int $id_parent, array $data, string $act): void
  {
    $seo_table = 'table_seo';
    $columns = $this->getColumnNames($seo_table);
    $data_sql = ['id_parent' => $id_parent, 'type' => $type, 'act' => $act];
    $has_data = false;
    if (!empty($data['seo']) && is_array($data['seo'])) {
      foreach ($data['seo'] as $key => $value) {
        if (!in_array($key, $columns)) continue;
        if (in_array($key, ['slug', 'url', 'canonical'])) {
          $clean = trim($value);
        } else {
          $clean = $this->sanitize($value);
        }
        $data_sql[$key] = $clean;
        if (!$has_data && trim((string)$clean) !== '') {
          $has_data = true;
        }
      }
    }
    if (!$has_data) return;
    $existing = $this->d->rawQueryOne("SELECT id FROM `$seo_table` WHERE id_parent = ? AND `type` = ? AND `act` = ?", [$id_parent, $type, $act]);
    if ($existing) {
      $fields = $params = [];
      foreach ($data_sql as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }
      $params[] = $existing['id'];
      $this->d->execute("UPDATE `$seo_table` SET " . implode(', ', $fields) . " WHERE id = ?", $params);
    } else {
      $cols = array_map(fn($col) => "`$col`", array_keys($data_sql));
      $placeholders = array_fill(0, count($cols), '?');
      $params = array_values($data_sql);
      $this->d->execute("INSERT INTO `$seo_table` (" . implode(',', $cols) . ") VALUES (" . implode(',', $placeholders) . ")", $params);
    }
  }

  public function save_gallery($data, $files, $id_parent, $type = '', $gallery_id = 0, $options = [])
  {
    $id_parent  = (int)$id_parent;
    $gallery_id = (int)$gallery_id;
    $table = 'table_gallery';
    $result = false;
    $convert_webp = $options['convert_webp'] ?? false;
    $background   = $options['background'] ?? [255, 255, 255, 0];
    $parent = $this->d->rawQueryOne("SELECT namevi FROM `table_product` WHERE id = ? LIMIT 1", [$id_parent]);
    $parent_name = $parent['namevi'] ?? 'gallery';
    /* EDIT – 1 HÌNH */
    if ($gallery_id > 0) {
      $fields = [
        'numb'   => (int)($data['numb'] ?? 0),
        'status' => !empty($data['hienthi']) ? 'hienthi' : ''
      ];
      if (!empty($files['file']['name'])) {
        $old = $this->d->rawQueryOne("SELECT file FROM `$table` WHERE id = ?", [$gallery_id]);
        $old_path = !empty($old['file']) ? UPLOADS . $old['file'] : '';
        $thumb = $this->uploadImage([
          'file'          => $files['file'],
          'custom_name'   => $parent_name,
          'old_file_path' => $old_path,
          'convert_webp'  => $convert_webp,
          'background'    => $background
        ]);
        if (!$thumb) return false;
        $fields['file'] = $thumb;
      }
      $sql = [];
      $params = [];
      foreach ($fields as $k => $v) {
        $sql[] = "`$k` = ?";
        $params[] = $v;
      }
      $params[] = $gallery_id;
      return $this->d->execute("UPDATE `$table` SET " . implode(', ', $sql) . " WHERE id = ?", $params);
    }

    /* ADD – NHIỀU HÌNH  */
    if (empty($files['files']['name'])) return false;
    foreach ($files['files']['name'] as $i => $name) {
      if (empty($name) || $files['files']['error'][$i] !== 0) continue;
      $file = [
        'name'     => $files['files']['name'][$i],
        'type'     => $files['files']['type'][$i],
        'tmp_name' => $files['files']['tmp_name'][$i],
        'error'    => $files['files']['error'][$i],
        'size'     => $files['files']['size'][$i]
      ];
      $thumb = $this->uploadImage([
        'file'         => $file,
        'custom_name'  => $parent_name,
        'convert_webp' => $convert_webp,
        'background'   => $background
      ]);
      if (!$thumb) continue;
      $result = $this->d->execute(
        "INSERT INTO `$table` (id_parent, type, file, numb, name, status) VALUES (?, ?, ?, ?, ?, ?)",
        [$id_parent, $type, $thumb, (int)($data['numb-filer'][$i] ?? 0), trim($data['name-filer'][$i] ?? ''), !empty($data['hienthi_all']) ? 'hienthi' : '']
      );
    }
    return $result;
  }

  public function save_data($data, $files = null, $id = null, $options = [])
  {
    global $config;
    $langs = array_keys($config['website']['lang']);
    $table = $options['table'] ?? '';
    $enable_gallery = $options['enable_gallery'] ?? false;
    $enable_seo = $options['enable_seo'] ?? false;
    $enable_slug = $options['enable_slug'] ?? false;
    $convert_webp = $options['convert_webp'] ?? false;
    if (empty($table)) {
      return [
        'success' => false,
        'id' => 0,
        'message' => 'Table không hợp lệ'
      ];
    }
    $columns = $this->getColumnNames($table);
    $data_prepared = [];
    foreach ($data as $key => $val) {
      if ($key === 'options') continue;
      if (!in_array($key, $columns)) continue;
      $data_prepared[$key] = $this->sanitize($val);
    }
    if (!empty($options['type']) && in_array('type', $columns)) {
      $data_prepared['type'] = $options['type'];
    }
    if (!empty($data['options']) && is_array($data['options']) && in_array('options', $columns)) {
      $data_prepared['options'] = json_encode($this->sanitize($data['options']), JSON_UNESCAPED_UNICODE);
    }
    if (in_array('status', $columns)) {
      if (!empty($data['status']) && is_array($data['status'])) {
        $data_prepared['status'] = implode(',', array_keys(array_filter($data['status'])));
      } else {
        $data_prepared['status'] = '';
      }
    }
    if (!empty($id)) {
      if (in_array('date_updated', $columns)) {
        $data_prepared['date_updated'] = time();
      }
    } else {
      if (in_array('date_created', $columns)) {
        $data_prepared['date_created'] = time();
      }
    }
    if ($enable_slug) {
      foreach ($langs as $lang) {
        $slug_key = 'slug' . $lang;
        $slug = $data_prepared[$slug_key] ?? '';
        $result = $this->checkSlug(['slug' => $slug, 'table' => $table, 'exclude_id' => $id ?? '', 'lang' => $lang]);
        if ($result) {
          return $result;
        }
      }
    }
    $thumb_filename = $old_filename = '';
    $icon_filename = $old_icon = '';
    $has_file_main = is_array($files) && isset($files['file']) && is_uploaded_file($files['file']['tmp_name']);
    $has_file_icon = is_array($files) && isset($files['icon']) && is_uploaded_file($files['icon']['tmp_name']);
    if (!empty($id)) {
      if ($has_file_main || (!empty($data['photo_deleted']) && $data['photo_deleted'] == '1')) {
        $old = $this->d->rawQueryOne("SELECT file FROM $table WHERE id = ?", [(int)$id]);
        $old_filename = $old['file'] ?? '';
      }
      if ($has_file_icon || (!empty($data['photoicon_deleted']) && $data['photoicon_deleted'] == '1')) {
        $old = $this->d->rawQueryOne("SELECT icon FROM $table WHERE id = ?", [(int)$id]);
        $old_icon = $old['icon'] ?? '';
      }
    }
    if ($has_file_main) {
      $thumb_filename = $this->uploadImage([
        'file' => $files['file'],
        'custom_name' => $data_prepared['namevi'] ?? ($data_prepared['type'] ?? ''),
        'old_file_path' => UPLOADS . $old_filename,
        'convert_webp' => $convert_webp,
        'background' => $options['background'] ?? [255, 255, 255, 0]
      ]);
    } elseif (!empty($data['photo_deleted']) && $data['photo_deleted'] == '1') {
      if (!empty($old_filename)) {
        $this->deleteFile($old_filename);
      }
      $thumb_filename = '';
    }
    if ($has_file_icon) {
      $icon_filename = $this->uploadImage([
        'file' => $files['icon'],
        'custom_name' => $data_prepared['namevi'] ?? ($data_prepared['type'] ?? ''),
        'old_file_path' => UPLOADS . $old_icon,
        'convert_webp' => $convert_webp,
        'background' => $options['background'] ?? [255, 255, 255, 0]
      ]);
    } elseif (!empty($data['photoicon_deleted']) && $data['photoicon_deleted'] == '1') {
      if (!empty($old_icon)) {
        $this->deleteFile($old_icon);
      }
      $icon_filename = '';
    }
    if ($id) {
      $fields = $params = [];
      foreach ($data_prepared as $key => $val) {
        $fields[] = "`$key` = ?";
        $params[] = $val;
      }
      if ($has_file_main || (!empty($data['photo_deleted']) && $data['photo_deleted'] == '1')) {
        if (in_array('file', $columns)) {
          $fields[] = "`file` = ?";
          $params[] = $thumb_filename;
        }
      }
      if ($has_file_icon || (!empty($data['photoicon_deleted']) && $data['photoicon_deleted'] == '1')) {
        if (in_array('icon', $columns)) {
          $fields[] = "`icon` = ?";
          $params[] = $icon_filename;
        }
      }
      $params[] = (int)$id;

      $result = $this->d->execute("UPDATE $table SET " . implode(', ', $fields) . " WHERE id = ?", $params);
      if ($enable_seo && $result) {
        $this->save_seo($data_prepared['type'] ?? '', (int)$id, $data, $options['act'] ?? '');
      }
      if ($enable_gallery && !empty($data['deleted_images'])) {
        foreach (explode('|', $data['deleted_images']) as $gid) {
          $gid = (int)$gid;
          if ($gid > 0) {
            $gallery = $this->d->rawQueryOne("SELECT `file` FROM table_gallery WHERE id = ?", [$gid]);
            if ($gallery && !empty($gallery['file'])) {
              @unlink(UPLOADS . $gallery['file']);
            }
            $this->d->execute("DELETE FROM table_gallery WHERE id = ?", [$gid]);
          }
        }
      }
      if ($enable_gallery && !empty($data['id-filer'])) {
        foreach ($data['id-filer'] as $k => $gid) {
          $gid = (int)$gid;
          $numb = (int)($data['numb-filer'][$k] ?? 0);
          $name = trim($data['name-filer'][$k] ?? '');
          if ($gid > 0) {
            $this->d->execute("UPDATE `table_gallery` SET numb = ?, name = ? WHERE id = ?", [$numb, $name, $gid]);
          }
        }
      }
      if ($enable_gallery && !empty($files['files']['name'][0])) {
        $this->save_gallery($data, $files, $id, $data_prepared['type'] ?? '');
      }
      return [
        'success' => (bool)$result,
        'id' => (int)$id,
        'message' => $result ? capnhatdulieuthanhcong : capnhatdulieubiloi
      ];
    } else {
      $columns_insert = array_keys($data_prepared);
      $placeholders = array_fill(0, count($columns_insert), '?');
      $params = array_values($data_prepared);
      if ($has_file_main && in_array('file', $columns)) {
        $columns_insert[] = 'file';
        $placeholders[] = '?';
        $params[] = $thumb_filename;
      }
      if ($has_file_icon && in_array('icon', $columns)) {
        $columns_insert[] = 'icon';
        $placeholders[] = '?';
        $params[] = $icon_filename;
      }
      $inserted = $this->d->execute("INSERT INTO $table (" . implode(', ', $columns_insert) . ") VALUES (" . implode(', ', $placeholders) . ")", $params);
      $insert_id = $inserted ? $this->d->getInsertId() : 0;
      if ($enable_seo && $insert_id) {
        $this->save_seo($data_prepared['type'] ?? '', $insert_id, $data, $options['act'] ?? '');
      }
      if ($enable_gallery && !empty($files['files']['name'][0])) {
        $this->save_gallery($data, $files, $insert_id, $data_prepared['type'] ?? '');
      }
    }
    return [
      'success' => (bool)$inserted,
      'id' => (int)$insert_id,
      'message' => $inserted ? capnhatdulieuthanhcong : capnhatdulieubiloi
    ];
  }

  // Hàm lấy tên cột bảng
  public function getColumnNames($table)
  {
    $result = $this->d->rawQueryArray("SHOW COLUMNS FROM `$table`");
    return array_column($result, 'Field');
  }
  private function ensureColumnExists($table, $column, $type = "TEXT")
  {
    $columns = $this->getColumnNames($table);
    if (!in_array($column, $columns)) {
      $this->d->execute("ALTER TABLE `$table` ADD `$column` $type NULL");
    }
  }

  // public function deleteFile($file = '')
  // {
  //   if (!$file) return true;
  //   $filename = basename($file);
  //   $filenameNoExt = pathinfo($filename, PATHINFO_FILENAME);
  //   $iterator = new RecursiveIteratorIterator(
  //     new RecursiveDirectoryIterator(UPLOADS, RecursiveDirectoryIterator::SKIP_DOTS),
  //     RecursiveIteratorIterator::CHILD_FIRST
  //   );
  //   foreach ($iterator as $entry) {
  //     if (!$entry->isFile()) continue;
  //     $current = $entry->getPathname();
  //     $currentBase = basename($current);
  //     $currentNoExt = pathinfo($currentBase, PATHINFO_FILENAME);
  //     $currentExt = strtolower(pathinfo($currentBase, PATHINFO_EXTENSION));
  //     if (
  //       $currentBase === $filename || preg_match('/^' . preg_quote($filenameNoExt, '/') . '-[a-z0-9]{8}$/i', $currentNoExt) || ($currentNoExt === $filenameNoExt && in_array($currentExt, ['webp', 'json']))
  //     ) {
  //       @unlink($current);
  //     }
  //   }
  //   return true;
  // }
  public function delete_data(array $options = []): void
  {
    $id      = (int)($options['id'] ?? 0);
    $table   = $options['table'] ?? '';
    $type    = $options['type'] ?? '';
    $redirect = $options['redirect'] ?? '';
    $delete_seo     = !empty($options['delete_seo']);
    $delete_gallery = !empty($options['delete_gallery']);
    $delete_file    = $options['delete_file'] ?? true;
    if (!$id || !$table) {
      $this->transfer(dulieukhonghople, $redirect, false);
      return;
    }
    $row = $this->d->rawQueryOne("SELECT file FROM `$table` WHERE id = ? LIMIT 1", [$id]);
    if (!$row) {
      $this->transfer(dulieukhonghople, $redirect, false);
      return;
    }
    if ($table === 'table_gallery') {
      if (!empty($row['file'])) {
        $this->deleteFile($row['file']);
      }
      $deleted = $this->d->execute("DELETE FROM `table_gallery` WHERE id = ?", [$id]);
      $this->transfer($deleted ? xoadulieuthanhcong : xoadulieubiloi, $redirect, $deleted);
      return;
    }
    if ($delete_file && !empty($row['file'])) {
      $this->deleteFile($row['file']);
    }
    if ($delete_gallery) {
      $gallery = $this->d->rawQuery("SELECT file FROM `table_gallery` WHERE id_parent = ?", [$id]);
      if (!empty($gallery)) {
        foreach ($gallery as $g) {
          if (!empty($g['file'])) {
            $this->deleteFile($g['file']);
          }
        }
      }
      $this->d->execute("DELETE FROM `table_gallery` WHERE id_parent = ?", [$id]);
    }
    if ($delete_seo) {
      $sql = "DELETE FROM `table_seo` WHERE id_parent = ?";
      $params = [$id];
      if ($type) {
        $sql .= " AND `type` = ?";
        $params[] = $type;
      }
      $this->d->execute($sql, $params);
    }
    $sql = "DELETE FROM `$table` WHERE id = ?";
    $params = [$id];
    if (!empty($type)) {
      $sql .= " AND `type` = ?";
      $params[] = $type;
    }
    $deleted = $this->d->execute($sql, $params);
    $this->transfer($deleted ? xoadulieuthanhcong : xoadulieubiloi, $redirect, $deleted);
  }
  public function deleteMultiple_data(array $options = [])
  {
    $listid = $options['listid'] ?? '';
    $table = $options['table'] ?? '';
    $type = $options['type'] ?? '';
    $redirect = $options['redirect'] ?? '';
    $delete_seo = $options['delete_seo'] ?? false;
    $delete_gallery = $options['delete_gallery'] ?? false;
    $delete_file = $options['delete_file'] ?? true;
    $ids = array_filter(array_map('intval', explode(',', $listid)));
    if (empty($ids) || !$table) {
      $this->transfer("Danh sách ID không hợp lệ!", $redirect, false);
    }
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $rows = $this->d->rawQuery("SELECT id, file FROM `$table` WHERE id IN ($placeholders)", $ids);
    foreach ($rows as $row) {
      $id = (int)$row['id'];
      if ($delete_file && !empty($row['file'])) {
        $this->deleteFile($row['file']);
      }
      if ($delete_gallery) {
        $gallery = $this->d->rawQuery("SELECT file FROM `table_gallery` WHERE id_parent = ?", [$id]);
        if (!empty($gallery) && is_array($gallery)) {
          foreach ($gallery as $g) {
            if (!empty($g['file'])) {
              $this->deleteFile($g['file']);
            }
          }
        }
        $this->d->execute("DELETE FROM table_gallery WHERE id_parent = ?", [$id]);
      }
      if ($delete_seo) {
        if ($type) {
          $this->d->execute("DELETE FROM table_seo WHERE id_parent = ? AND `type` = ?", [$id, $type]);
        } else {
          $this->d->execute("DELETE FROM table_seo WHERE id_parent = ?", [$id]);
        }
      }
    }
    $sql = "DELETE FROM `$table` WHERE id IN ($placeholders)";
    $params = $ids;
    if (!empty($type)) {
      $sql .= " AND `type` = ?";
      $params[] = $type;
    }
    $delete_result = $this->d->execute($sql, $params);
    $this->transfer($delete_result ? xoadulieuthanhcong : xoadulieubiloi, $redirect, $delete_result);
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
  public function deleteGallery()
  {
    $row = $this->d->rawQuery("select id, com, photo from #_gallery where hash != '' and date_created < " . (time() - 3 * 3600));
    $array = array("product" => UPLOAD_PRODUCT, "news" => UPLOAD_NEWS);

    if ($row) {
      foreach ($row as $item) {
        @unlink($array[$item['com']] . $item['photo']);
        $this->d->rawQuery("delete from #_gallery where id = " . $item['id']);
      }
    }
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

  /* Get color */
  public function getColor($id = 0)
  {
    global $type;

    if ($id) {
      $temps = $this->d->rawQuery("select id_color from #_product_sale where id_parent = ?", array($id));
      $temps = (!empty($temps)) ? $this->joinCols($temps, 'id_color') : array();
      $temps = (!empty($temps)) ? explode(",", $temps) : array();
    }

    $row_color = $this->d->rawQuery("select namevi, id from #_color where type = ? order by numb,id desc", array($type));

    $str = '<select id="dataColor" name="dataColor[]" class="select multiselect" multiple="multiple" >';
    for ($i = 0; $i < count($row_color); $i++) {
      if (!empty($temps)) {
        if (in_array($row_color[$i]['id'], $temps)) $selected = 'selected="selected"';
        else $selected = '';
      } else {
        $selected = '';
      }
      $str .= '<option value="' . $row_color[$i]["id"] . '" ' . $selected . ' /> ' . $row_color[$i]["namevi"] . '</option>';
    }
    $str .= '</select>';

    return $str;
  }

  /* Get size */
  public function getSize($id = 0)
  {
    global $type;

    if ($id) {
      $temps = $this->d->rawQuery("select id_size from #_product_sale where id_parent = ?", array($id));
      $temps = (!empty($temps)) ? $this->joinCols($temps, 'id_size') : array();
      $temps = (!empty($temps)) ? explode(",", $temps) : array();
    }

    $row_size = $this->d->rawQuery("select namevi, id from #_size where type = ? order by numb,id desc", array($type));

    $str = '<select id="dataSize" name="dataSize[]" class="select multiselect" multiple="multiple" >';
    for ($i = 0; $i < count($row_size); $i++) {
      if (!empty($temps)) {
        if (in_array($row_size[$i]['id'], $temps)) $selected = 'selected="selected"';
        else $selected = '';
      } else {
        $selected = '';
      }
      $str .= '<option value="' . $row_size[$i]["id"] . '" ' . $selected . ' /> ' . $row_size[$i]["namevi"] . '</option>';
    }
    $str .= '</select>';

    return $str;
  }

  /* Get tags */
  public function getTags($id = 0, $element = '', $table = '', $type = '')
  {
    if ($id) {
      $temps = $this->d->rawQuery("select id_tags from #_" . $table . " where id_parent = ?", array($id));
      $temps = (!empty($temps)) ? $this->joinCols($temps, 'id_tags') : array();
      $temps = (!empty($temps)) ? explode(",", $temps) : array();
    }

    $row_tags = $this->cache->get("select namevi, id from #_tags where type = ? order by numb,id desc", array($type), "result", 7200);

    $str = '<select id="' . $element . '" name="' . $element . '[]" class="select multiselect" multiple="multiple" >';
    for ($i = 0; $i < count($row_tags); $i++) {
      if (!empty($temps)) {
        if (in_array($row_tags[$i]['id'], $temps)) $selected = 'selected="selected"';
        else $selected = '';
      } else {
        $selected = '';
      }
      $str .= '<option value="' . $row_tags[$i]["id"] . '" ' . $selected . ' /> ' . $row_tags[$i]["namevi"] . '</option>';
    }
    $str .= '</select>';

    return $str;
  }

  /* Get category by ajax */
  public function getAjaxCategory($table = '', $level = '', $type = '', $title_select = chondanhmuc, $class_select = 'select-category')
  {
    $where = '';
    $params = array($type);
    $id_parent = 'id_' . $level;
    $data_level = '';
    $data_type = 'data-type="' . $type . '"';
    $data_table = '';
    $data_child = '';

    if ($level == 'list') {
      $data_level = 'data-level="0"';
      $data_table = 'data-table="#_' . $table . '_cat"';
      $data_child = 'data-child="id_cat"';
    } else if ($level == 'cat') {
      $data_level = 'data-level="1"';
      $data_table = 'data-table="#_' . $table . '_item"';
      $data_child = 'data-child="id_item"';

      $idlist = (isset($_REQUEST['id_list'])) ? htmlspecialchars($_REQUEST['id_list']) : 0;
      $where .= ' and id_list = ?';
      array_push($params, $idlist);
    } else if ($level == 'item') {
      $data_level = 'data-level="2"';
      $data_table = 'data-table="#_' . $table . '_sub"';
      $data_child = 'data-child="id_sub"';

      $idlist = (isset($_REQUEST['id_list'])) ? htmlspecialchars($_REQUEST['id_list']) : 0;
      $where .= ' and id_list = ?';
      array_push($params, $idlist);

      $idcat = (isset($_REQUEST['id_cat'])) ? htmlspecialchars($_REQUEST['id_cat']) : 0;
      $where .= ' and id_cat = ?';
      array_push($params, $idcat);
    } else if ($level == 'sub') {
      $data_level = '';
      $data_type = '';
      $class_select = '';

      $idlist = (isset($_REQUEST['id_list'])) ? htmlspecialchars($_REQUEST['id_list']) : 0;
      $where .= ' and id_list = ?';
      array_push($params, $idlist);

      $idcat = (isset($_REQUEST['id_cat'])) ? htmlspecialchars($_REQUEST['id_cat']) : 0;
      $where .= ' and id_cat = ?';
      array_push($params, $idcat);

      $iditem = (isset($_REQUEST['id_item'])) ? htmlspecialchars($_REQUEST['id_item']) : 0;
      $where .= ' and id_item = ?';
      array_push($params, $iditem);
    } else if ($level == 'brand') {
      $data_level = '';
      $data_type = '';
      $class_select = '';
    }

    $rows = $this->d->rawQuery("select namevi, id from #_" . $table . "_" . $level . " where type = ? " . $where . " order by numb,id desc", $params);

    $str = '<select id="' . $id_parent . '" name="data[' . $id_parent . ']" ' . $data_level . ' ' . $data_type . ' ' . $data_table . ' ' . $data_child . ' class="form-control select2 ' . $class_select . '"><option value="0">' . $title_select . '</option>';
    foreach ($rows as $v) {
      if (isset($_REQUEST[$id_parent]) && ($v["id"] == (int)$_REQUEST[$id_parent])) $selected = "selected";
      else $selected = "";

      $str .= '<option value=' . $v["id"] . ' ' . $selected . '>' . $v["namevi"] . '</option>';
    }
    $str .= '</select>';

    return $str;
  }

  /* Get category by link */
  public function getLinkCategory(string $table = '', string $level = '', string $type = '', string $title_select = chondanhmuc): string
  {
    $prefix    = $this->d->prefix;
    $id_parent = 'id_' . $level;
    $parentMap = [
      'cat'  => ['list'],
      'item' => ['list', 'cat'],
      'sub'  => ['list', 'cat', 'item'],
      'vari' => ['list', 'cat', 'item'],
      'brand' => []
    ];
    $where  = '';
    $params = [$type];
    if (!empty($parentMap[$level])) {
      foreach ($parentMap[$level] as $parent) {
        $key = 'id_' . $parent;
        $val = isset($_REQUEST[$key]) ? (int)$_REQUEST[$key] : 0;
        $where   .= " AND {$key} = ?";
        $params[] = $val;
      }
    }
    $rows = $this->d->rawQuery("SELECT id, namevi FROM {$prefix}{$table}_{$level} WHERE type = ? {$where} ORDER BY numb, id DESC", $params);
    $str = '<select id="' . $id_parent . '" name="' . $id_parent . '" onchange="onchangeCategory($(this))" class="form-control filter-category select2"><option value="0">' . $title_select . '</option>';
    if (!empty($rows)) {
      $current = isset($_REQUEST[$id_parent]) ? (int)$_REQUEST[$id_parent] : 0;
      foreach ($rows as $v) {
        $selected = ($v['id'] == $current) ? 'selected' : '';
        $str .= '<option value="' . $v['id'] . '" ' . $selected . '>' . $v['namevi'] . '</option>';
      }
    }
    $str .= '</select>';
    return $str;
  }

  public function transfer($msg = '', $com = '', $numb = true)
  {
    global $configBase;
    $basehref = $configBase;
    $showtext = $msg;
    $page_transfer = $com;
    $numb = $numb;
    include("./templates/layout/transfer.php");
    exit();
  }

  public function Notify($msg, $page, $status, $title = thongbao)
  {
    if (!isset($_SESSION['notify']) || !is_array($_SESSION['notify'])) {
      $_SESSION['notify'] = [];
    }
    if (is_array($msg) && !isset($msg['msg'])) {
      foreach ($msg as $m) {
        $_SESSION['notify'][] = [
          'status' => $status,
          'title'  => $title,
          'msg'    => $m
        ];
      }
    } else {
      $_SESSION['notify'][] = [
        'status' => $status,
        'title'  => $title,
        'msg'    => is_array($msg) && isset($msg['msg']) ? $msg['msg'] : $msg
      ];
    }
    if (!empty($page)) {
      header("Location: $page");
      exit();
    }
  }

  public function convert_type(string $type)
  {
    $type = trim($type);
    if ($type === '') return '';
    $row = $this->d->rawQueryOne(
      "SELECT langvi FROM table_type WHERE lang_define = ? LIMIT 1",
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
  public function render_check($key, $label, $status_array = [], $item_id = null)
  {
    $checked = (empty($status_array) && empty($item_id))
      || in_array($key, $status_array)
      ? 'checked'
      : '';
    return '
    <div class="form-group d-inline-block mb-2 mr-4">
        <label for="' . $key . '-checkbox" class="d-inline-block align-middle mb-0 mr-3">
            ' . $label . ':
        </label>
        <label class="switch switch-success">
            <input type="checkbox"
                class="switch-input custom-control-input ' . $key . '-checkbox"
                name="status[' . $key . ']"
                id="' . $key . '-checkbox"
                value="' . $key . '"
                ' . $checked . '>
        </label>
    </div>';
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
  public function formatMoney($price = 0, $unit = '₫', $html = false)
  {
    if ($price === null || $price === '') return '';
    $price = (float) preg_replace('/[^0-9]/', '', (string)$price);
    if ($price <= 0) return '';
    $str = number_format($price, 0, ',', '.');
    if ($unit !== '') {
      $str .= $html ? '<span>' . $unit . '</span>' : $unit;
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
      $row = $this->d->rawQueryOne("select id from {$this->d->prefix}$tbl where $type = ? $where limit 0,1", array($data));
      if (!empty($row)) {
        $result = true;
      }
    }
    return $result;
  }

  /* Check slug */
  public function checkSlug($data = array())
  {
    $result = 'valid';
    if (isset($data['slug'])) {
      $slug = trim($data['slug']);

      if (!empty($slug)) {
        $table = array(
          "{$this->d->prefix}product_list",
          "{$this->d->prefix}product_cat",
          "{$this->d->prefix}product_item",
          "{$this->d->prefix}product_sub",
          "{$this->d->prefix}product_brand",
          "{$this->d->prefix}product",
          "{$this->d->prefix}news_list",
          "{$this->d->prefix}news",
          // "{$this->d->prefix}tags"
        );
        $where = (!empty($data['id']) && empty($data['copy'])) ? "id != " . $data['id'] . " and " : "";
        foreach ($table as $v) {
          $check = $this->d->rawQueryOne("select id from $v where $where (slugvi = ? or slugen = ?) limit 0,1", array($data['slug'], $data['slug']));
          if (!empty($check['id'])) {
            $result = 'exist';
            break;
          }
        }
      } else {
        $result = 'empty';
      }
    }
    return $result;
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
    $row = $this->d->rawQueryOne("select file, options from table_photo where type = ? and act = ? limit 0,1", array('watermark', 'photo_static'));
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
  // public function createThumb(
  //   string $source_path,
  //   string $thumb_name,
  //   bool $background = false,
  //   bool $add_watermark = false,
  //   bool $convert_webp = false
  // ): string|false {
  //   if (
  //     !file_exists($source_path) ||
  //     !preg_match('/^(\d+)x(\d+)(x(\d+))?$/', $thumb_name, $m)
  //   ) {
  //     return false;
  //   }
  //   $thumb_width  = (int) $m[1];
  //   $thumb_height = (int) $m[2];
  //   $zoom_crop    = isset($m[4]) ? (int) $m[4] : 1;
  //   $image_type = exif_imagetype($source_path);
  //   $ext_map = [
  //     IMAGETYPE_JPEG => 'jpg',
  //     IMAGETYPE_PNG  => 'png',
  //     IMAGETYPE_WEBP => 'webp'
  //   ];
  //   $create_func = [
  //     IMAGETYPE_JPEG => 'imagecreatefromjpeg',
  //     IMAGETYPE_PNG  => 'imagecreatefrompng',
  //     IMAGETYPE_WEBP => 'imagecreatefromwebp'
  //   ];
  //   if (!isset($ext_map[$image_type])) return false;
  //   $src_ext   = $ext_map[$image_type];
  //   $thumb_ext = ($convert_webp || $src_ext === 'webp') ? 'webp' : $src_ext;
  //   $filename = pathinfo($source_path, PATHINFO_FILENAME);
  //   $base_dir = rtrim(UPLOADS . THUMB . "{$thumb_width}x{$thumb_height}x{$zoom_crop}/", '/');
  //   if (!is_dir($base_dir)) {
  //     mkdir($base_dir, 0755, true);
  //   }
  //   $wm_enabled = false;
  //   $wm_path = null;
  //   $wm_options = [];

  //   if ($add_watermark && method_exists($this, 'addWatermark')) {
  //     $wm_data = $this->d->rawQueryOne("SELECT file, options, date_updated, status FROM `table_photo` WHERE type = 'watermark'LIMIT 1");
  //     if (!empty($wm_data)) {
  //       $status = explode(',', $wm_data['status'] ?? '');
  //       $wm_file = UPLOADS . ($wm_data['file'] ?? '');
  //       if (
  //         in_array('hienthi', $status, true) &&
  //         !empty($wm_data['file']) &&
  //         file_exists($wm_file)
  //       ) {
  //         $wm_enabled = true;
  //         $wm_options = json_decode($wm_data['options'] ?? '', true) ?: [];
  //       }
  //     }
  //   }
  //   if ($wm_enabled) {
  //     $updated = strtotime($wm_data['date_updated'] ?? '') ?: time();
  //     $wm_hash = substr(md5(json_encode($wm_options) . '_' . $updated), 0, 8);
  //     $wm_dir = $base_dir . '/' . trim(WATERMARK, '/');
  //     if (!is_dir($wm_dir)) {
  //       mkdir($wm_dir, 0755, true);
  //     }
  //     foreach (glob($wm_dir . '/' . $filename . '-*.' . $thumb_ext) as $old) {
  //       if (strpos($old, "-{$wm_hash}.") === false) {
  //         @unlink($old);
  //       }
  //     }
  //     $wm_thumb = "{$wm_dir}/{$filename}-{$wm_hash}.{$thumb_ext}";
  //     if (file_exists($wm_thumb)) {
  //       return $wm_thumb;
  //     }
  //     $tmp = tempnam(sys_get_temp_dir(), 'thumb_');
  //     if (
  //       !$this->generateThumbImage(
  //         $source_path,
  //         $tmp,
  //         $thumb_width,
  //         $thumb_height,
  //         $zoom_crop,
  //         $create_func[$image_type],
  //         $image_type,
  //         $thumb_ext,
  //         $background
  //       )
  //     ) {
  //       @unlink($tmp);
  //       return false;
  //     }
  //     if (!$this->addWatermark($tmp, $wm_thumb, $wm_options)) {
  //       @unlink($tmp);
  //       return false;
  //     }
  //     @unlink($tmp);
  //     return $wm_thumb;
  //   }
  //   $thumb_path = "{$base_dir}/{$filename}.{$thumb_ext}";
  //   if (file_exists($thumb_path)) {
  //     return $thumb_path;
  //   }
  //   $created = $this->generateThumbImage(
  //     $source_path,
  //     $thumb_path,
  //     $thumb_width,
  //     $thumb_height,
  //     $zoom_crop,
  //     $create_func[$image_type],
  //     $image_type,
  //     $thumb_ext,
  //     $background
  //   );

  //   return $created ? $thumb_path : false;
  // }
  // public function uploadImage(array $options): string
  // {
  //   $file = $options['file'] ?? null;
  //   if (empty($file['name']) || empty($file['tmp_name'])) return '';
  //   if (!is_dir(UPLOADS)) mkdir(UPLOADS, 0777, true);
  //   $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  //   $custom = !empty($options['custom_name']) ? $this->to_slug($options['custom_name']) . '_' . substr(md5(uniqid()), 0, 4) : substr(md5(time() . rand()), 0, 10);
  //   $filename = $this->generateUniqueFilename(UPLOADS, $custom, $ext);
  //   $target_path = UPLOADS . $filename;
  //   if (!empty($options['old_file_path']) && file_exists($options['old_file_path'])) {
  //     $this->deleteFile($options['old_file_path']);
  //   }
  //   if (!move_uploaded_file($file['tmp_name'], $target_path)) return '';
  //   $convert_webp = $options['convert_webp'] ?? false;
  //   $background = $options['background'] ?? [255, 255, 255, 0];
  //   while (count($background) < 4) $background[] = 0;
  //   if ($convert_webp && in_array($ext, ['jpg', 'jpeg', 'png'])) {
  //     $img_type = exif_imagetype($target_path);
  //     $img = match ($img_type) {
  //       IMAGETYPE_JPEG => imagecreatefromjpeg($target_path),
  //       IMAGETYPE_PNG  => imagecreatefrompng($target_path),
  //       default => null
  //     };
  //     if ($img) {
  //       imagepalettetotruecolor($img);
  //       imagealphablending($img, true);
  //       imagesavealpha($img, true);
  //       $w = imagesx($img);
  //       $h = imagesy($img);
  //       $canvas = imagecreatetruecolor($w, $h);
  //       imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, ...$background));
  //       imagecopy($canvas, $img, 0, 0, 0, 0, $w, $h);
  //       imagedestroy($img);
  //       $webp_path = UPLOADS . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
  //       imagewebp($canvas, $webp_path, 100);
  //       imagedestroy($canvas);
  //       @unlink($target_path);
  //       return basename($webp_path);
  //     }
  //   }
  //   return basename($target_path);
  // }

  // public function getImage(array $data = []): string
  // {
  //   $defaults = [
  //     'file' => '',
  //     'alt' => '',
  //     'title' => '',
  //     'class' => 'lazy',
  //     'id' => '',
  //     'width' => '',
  //     'height' => '',
  //     'zc' => 1,
  //     'thumb' => true,
  //     'lazy' => true,
  //     'style' => '',
  //     'src_only' => false,
  //     'attr' => '',
  //   ];
  //   $opt = array_merge($defaults, $data);
  //   $filename = ltrim(str_replace(UPLOADS, '', (string)$opt['file']), '/');
  //   if (empty($filename)) {
  //     $src = NO_IMG;
  //   } else {
  //     if ($opt['thumb'] && $opt['width'] && $opt['height']) {
  //       $src = BASE . "thumb/{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$filename}";
  //       $absPath = rtrim(UPLOADS, '/') . "/thumb/{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$filename}";
  //     } else {
  //       $src = BASE_ADMIN . UPLOADS . $filename;
  //       $absPath = UPLOADS . $filename;
  //     }
  //     if (file_exists($absPath)) {
  //       $src .= '?v=' . filemtime($absPath);
  //     } else {
  //       $src .= '?v=' . time();
  //     }
  //   }
  //   if ($opt['src_only']) return $src;
  //   $html = '<img src="' . htmlspecialchars($src) . '"';
  //   $html .= $opt['class'] ? ' class="' . htmlspecialchars($opt['class']) . '"' : '';
  //   $html .= $opt['id'] ? ' id="' . htmlspecialchars($opt['id']) . '"' : '';
  //   $html .= $opt['style'] ? ' style="' . htmlspecialchars($opt['style']) . '"' : '';
  //   $html .= $opt['width'] ? ' width="' . (int)$opt['width'] . '"' : '';
  //   $html .= $opt['height'] ? ' height="' . (int)$opt['height'] . '"' : '';
  //   $html .= $opt['attr'] ? ' ' . $opt['attr'] : '';
  //   $alt = htmlspecialchars($opt['alt'] ?: pathinfo($filename, PATHINFO_FILENAME));
  //   $title = htmlspecialchars($opt['title'] ?: $alt);
  //   $html .= ' alt="' . $alt . '" title="' . $title . '"';
  //   $html .= $opt['lazy'] ? ' loading="lazy"' : '';
  //   $html .= ' onerror="this.src=\'' . NO_IMG . '\'"';
  //   $html .= '>';
  //   return $html;
  // }


  /* Correct images orientation */
  public function correctImageOrientation($filename)
  {
    ini_set('memory_limit', '1024M');
    if (function_exists('exif_read_data')) {
      $exif = @exif_read_data($filename);
      if ($exif && isset($exif['Orientation'])) {
        $orientation = $exif['Orientation'];
        if ($orientation != 1) {
          $img = imagecreatefromjpeg($filename);
          $deg = 0;

          switch ($orientation) {
            case 3:
              $image = imagerotate($img, 180, 0);
              break;

            case 6:
              $image = imagerotate($img, -90, 0);
              break;

            case 8:
              $image = imagerotate($img, 90, 0);
              break;
          }

          imagejpeg($image, $filename, 90);
        }
      }
    }
  }

  private function parseSize($size)
  {
    $unit = strtoupper(substr($size, -1));
    $value = (int) $size;
    switch ($unit) {
      case 'G':
        return $value * 1024 * 1024 * 1024;
      case 'M':
        return $value * 1024 * 1024;
      case 'K':
        return $value * 1024;
      default:
        return (int) $size;
    }
  }
  /* Delete file */
  public function deleteFile($file = '')
  {
    return @unlink($file);
  }

  /* Upload images */
  public function uploadImage($file = '', $extension = '', $folder = '', $newname = '')
  {
    global $config;

    if (isset($_FILES[$file]) && !$_FILES[$file]['error']) {
      $postMaxSize = ini_get('post_max_size');
      $maxBytes = $this->parseSize($postMaxSize);
      if ($_FILES[$file]['size'] > $maxBytes) {
        $this->alert('Dung lượng file không được vượt quá ' . $postMaxSize);
        return false;
      }

      $ext = explode('.', $_FILES[$file]['name']);
      $ext = strtolower($ext[count($ext) - 1]);
      $name = basename($_FILES[$file]['name'], '.' . $ext);

      if (strpos($extension, $ext) === false) {
        $this->alert('Chỉ hỗ trợ upload file dạng ' . $extension);
        return false;
      }

      if ($newname == '' && file_exists($folder . $_FILES[$file]['name']))
        for ($i = 0; $i < 100; $i++) {
          if (!file_exists($folder . $name . $i . '.' . $ext)) {
            $_FILES[$file]['name'] = $name . $i . '.' . $ext;
            break;
          }
        }
      else {
        $_FILES[$file]['name'] = $newname . '.' . $ext;
      }

      if (!copy($_FILES[$file]["tmp_name"], $folder . $_FILES[$file]['name'])) {
        if (!move_uploaded_file($_FILES[$file]["tmp_name"], $folder . $_FILES[$file]['name'])) {
          return false;
        }
      }

      /* Fix correct Image Orientation */
      $this->correctImageOrientation($folder . $_FILES[$file]['name']);

      /* Resize image if width origin > config max width */
      $array = getimagesize($folder . $_FILES[$file]['name']);
      list($image_w, $image_h) = $array;
      $maxWidth = $config['website']['upload']['max-width'];
      $maxHeight = $config['website']['upload']['max-height'];
      if ($image_w > $maxWidth) $this->smartResizeImage($folder . $_FILES[$file]['name'], null, $maxWidth, $maxHeight, true);

      return $_FILES[$file]['name'];
    }
    return false;
  }

  /* Delete folder */
  public function removeDir($dirname = '')
  {
    if (is_dir($dirname)) $dir_handle = opendir($dirname);
    if (!isset($dir_handle) || $dir_handle == false) return false;
    while ($file = readdir($dir_handle)) {
      if ($file != "." && $file != "..") {
        if (!is_dir($dirname . "/" . $file)) unlink($dirname . "/" . $file);
        else $this->removeDir($dirname . '/' . $file);
      }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
  }

  /* Get image */
  public function getImage($data = array())
  {
    global $config;

    /* Defaults */
    $defaults = [
      'class' => 'lazy',
      'id' => '',
      'isLazy' => true,
      'thumbs' => THUMBS,
      'isWatermark' => false,
      'watermark' => (defined('WATERMARK')) ? WATERMARK : '',
      'prefix' => '',
      'size-error' => '',
      'size-src' => '',
      'sizes' => '',
      'url' => '',
      'upload' => '',
      'image' => '',
      'upload-error' => 'assets/images/',
      // 'image-error' => 'noimage.png',
      'image-error' => 'noimage.jpeg',
      'alt' => ''
    ];

    /* Data */
    $info = array_merge($defaults, $data);

    /* Upload - Image */
    if (empty($info['upload']) || empty($info['image'])) {
      $info['upload'] = $info['upload-error'];
      $info['image'] = $info['image-error'];
    }

    /* Size */
    if (!empty($info['sizes'])) {
      $info['size-error'] = $info['size-src'] = $info['sizes'];
    }

    /* Path origin */
    $info['pathOrigin'] = $info['upload'] . $info['image'];

    /* Path src */
    if (!empty($info['url'])) {
      $info['pathSrc'] = $info['url'];
    } else {
      if (!empty($info['size-src'])) {
        $info['pathSize'] = $info['size-src'] . "/" . $info['upload'] . $info['image'];
        $info['pathSrc'] = (!empty($info['isWatermark']) && !empty($info['prefix'])) ? ASSET . $info['watermark'] . "/" . $info['prefix'] . "/" . $info['pathSize'] : ASSET . $info['thumbs'] . "/" . $info['pathSize'];
      } else {
        $info['pathSrc'] = ASSET . $info['pathOrigin'];
      }
    }

    /* Path error */
    $info['pathError'] = ASSET . $info['thumbs'] . "/" . $info['size-error'] . "/" . $info['upload-error'] . $info['image-error'];

    /* Class */
    $info['class'] = (empty($info['isLazy'])) ? str_replace('lazy', '', $info['class']) : $info['class'];
    $info['class'] = (!empty($info['class'])) ? "class='" . $info['class'] . "'" : "";

    /* Id */
    $info['id'] = (!empty($info['id'])) ? "id='" . $info['id'] . "'" : "";

    /* Check to convert Webp */
    $info['hasURL'] = false;

    if (filter_var(str_replace(ASSET, "", $info['pathSrc']), FILTER_VALIDATE_URL)) {
      $info['hasURL'] = true;
    }

    if ($config['website']['image']['hasWebp']) {
      if (!$info['sizes']) {
        if (!$info['hasURL']) {
          $this->converWebp($info['pathSrc']);
        }
      }

      if (!$info['hasURL']) {
        $info['pathSrc'] .= '.webp';
      }
    }

    /* Src */
    $info['src'] = (!empty($info['isLazy']) && strpos($info['class'], 'lazy') !== false) ? "data-src='" . $info['pathSrc'] . "'" : "src='" . $info['pathSrc'] . "'";

    /* Image */
    /* onerror=\"this.src='" . $info['pathError'] . "';\" */
    $result = "<img " . $info['class'] . " " . $info['id'] . " onerror=\"this.src='" . $info['pathError'] . "';\" " . $info['src'] . " alt='" . $info['alt'] . "'/>";

    return $result;
  }
  // public function getImageCustom(array $data = []): string
  // {
  //   global $config;

  //   $defaults = [
  //     'file'      => '',
  //     'alt'       => '',
  //     'title'     => '',
  //     'class'     => '',
  //     'id'        => '',
  //     'width'     => 300,
  //     'height'    => 300,
  //     'zc'        => 1,
  //     'thumb'     => null,
  //     'lazy'      => true,
  //     'style'     => '',
  //     'attr'      => '',
  //     'src_only'  => false,
  //     'srcset'    => false,
  //     'sizes'     => [],
  //     'watermark' => false,
  //     'position'  => 9,
  //     'point-srcset' => $config['website']['point-srcset'] ?? []
  //   ];
  //   $opt = array_merge($defaults, $data);
  //   $file = ltrim(str_replace(UPLOADS, '', (string)$opt['file']), '/');
  //   $baseFile  = basename($file);
  //   $folder = dirname($file) !== '.' ? dirname($file) . '/' : '';
  //   if (!isset($data['thumb'])) {
  //     $opt['thumb'] = $opt['width'] && $opt['height'] && $opt['zc'];
  //   }
  //   $timestamp = time();
  //   if ($opt['watermark']) {
  //     $src = BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$folder}{$baseFile}?wm=1&v={$timestamp}";
  //   } elseif ($opt['thumb']) {
  //     $src = BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$folder}{$baseFile}?v={$timestamp}";
  //   } else {
  //     $src = BASE_ADMIN . UPLOADS . $folder . $baseFile . "?v={$timestamp}";
  //   }
  //   if ($opt['src_only']) return $src;
  //   $srcset = $sizes = '';
  //   if ($opt['srcset'] && !empty($opt['point-srcset']) && $opt['thumb'] && !$opt['watermark']) {
  //     $ratio = $opt['width'] / $opt['height'];
  //     $srcsets = [];
  //     foreach ($opt['point-srcset'] as $breakpoint => $scale) {
  //       $w = round($breakpoint / $scale);
  //       if ($w > $opt['width']) continue;
  //       $h = round($w / $ratio);
  //       $srcsets[] = BASE . THUMB . "{$w}x{$h}x{$opt['zc']}/{$folder}{$baseFile} {$w}w";
  //       $opt['sizes'][] = "(max-width:{$breakpoint}px) {$w}px";
  //     }
  //     $srcsets[] = BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/{$folder}{$baseFile} {$opt['width']}w";
  //     $opt['sizes'][] = "{$opt['width']}px";
  //     $srcset = ' srcset="' . implode(', ', $srcsets) . '"';
  //     $sizes = ' sizes="' . implode(', ', $opt['sizes']) . '"';
  //   }
  //   $alt = htmlspecialchars($opt['alt'] ?: pathinfo($baseFile, PATHINFO_FILENAME));
  //   $title = htmlspecialchars($opt['title'] ?: $alt);
  //   $style = trim($opt['style']);
  //   if (empty($opt['height']) && !str_contains($style, 'height')) {
  //     $style .= ($style ? '; ' : '') . 'height:auto';
  //   }
  //   return '<img src="' . htmlspecialchars($src) . '"'
  //     . ($opt['width'] ? ' width="' . (int)$opt['width'] . '"' : '')
  //     . (!empty($opt['height']) && $opt['height'] !== 'auto' ? ' height="' . (int)$opt['height'] . '"' : '')
  //     . ($opt['class'] ? ' class="' . htmlspecialchars($opt['class']) . '"' : '')
  //     . ($opt['id'] ? ' id="' . htmlspecialchars($opt['id']) . '"' : '')
  //     . ($style ? ' style="' . htmlspecialchars($style) . '"' : '')
  //     . ($opt['lazy'] ? ' loading="lazy"' : '')
  //     . ($opt['attr'] ? ' ' . $opt['attr'] : '')
  //     . $srcset . $sizes
  //     . ' alt="' . $alt . '" title="' . $title . '"'
  //     . ' onerror="this.src=\'' . BASE . THUMB . "{$opt['width']}x{$opt['height']}x{$opt['zc']}/noimage.jpeg" . '\'"'
  //     . '>';
  // }
  /* Pagination */
  public function pagination($totalq = 0, $perPage = 10, $page = 1, $url = '?')
  {
    $urlpos = strpos($url, "?");
    $url = ($urlpos) ? $url . "&" : $url . "?";
    $total = $totalq;
    $adjacents = "2";
    $firstlabel = "First";
    $prevlabel = "Prev";
    $nextlabel = "Next";
    $lastlabel = "Last";
    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $perPage;
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total / $perPage);
    $lpm1 = $lastpage - 1;
    $pagination = "";

    if ($lastpage > 1) {
      $pagination .= "<ul class='pagination flex-wrap justify-content-center mb-0'>";
      $pagination .= "<li class='page-item'><a class='page-link'>Page {$page} / {$lastpage}</a></li>";

      if ($page > 1) {
        $pagination .= "<li class='page-item'><a class='page-link' href='{$this->getCurrentPageURL()}'>{$firstlabel}</a></li>";
        $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$prev}'>{$prevlabel}</a></li>";
      }

      if ($lastpage < 7 + ($adjacents * 2)) {
        for ($counter = 1; $counter <= $lastpage; $counter++) {
          if ($counter == $page) $pagination .= "<li class='page-item active'><a class='page-link'>{$counter}</a></li>";
          else $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$counter}'>{$counter}</a></li>";
        }
      } elseif ($lastpage > 5 + ($adjacents * 2)) {
        if ($page < 1 + ($adjacents * 2)) {
          for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
            if ($counter == $page) $pagination .= "<li class='page-item active'><a class='page-link'>{$counter}</a></li>";
            else $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$counter}'>{$counter}</a></li>";
          }

          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p=1'>...</a></li>";
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$lpm1}'>{$lpm1}</a></li>";
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$lastpage}'>{$lastpage}</a></li>";
        } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p=1'>1</a></li>";
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p=2'>2</a></li>";
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p=1'>...</a></li>";

          for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
            if ($counter == $page) $pagination .= "<li class='page-item active'><a class='page-link'>{$counter}</a></li>";
            else $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$counter}'>{$counter}</a></li>";
          }

          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p=1'>...</a></li>";
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$lpm1}'>{$lpm1}</a></li>";
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$lastpage}'>{$lastpage}</a></li>";
        } else {
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p=1'>1</a></li>";
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p=2'>2</a></li>";
          $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p=1'>...</a></li>";

          for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
            if ($counter == $page) $pagination .= "<li class='page-item active'><a class='page-link'>{$counter}</a></li>";
            else $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$counter}'>{$counter}</a></li>";
          }
        }
      }

      if ($page < $counter - 1) {
        $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p={$next}'>{$nextlabel}</a></li>";
        $pagination .= "<li class='page-item'><a class='page-link' href='{$url}p=$lastpage'>{$lastlabel}</a></li>";
      }

      $pagination .= "</ul>";
    }

    return $pagination;
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
  /* UTF8 convert */
  public function utf8Convert($str = '')
  {
    if ($str != '') {
      $utf8 = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'd' => 'đ|Đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'i' => 'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        '' => '`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\“|\”|\:|\;|_',
      );
      foreach ($utf8 as $ascii => $uni) {
        $str = preg_replace("/($uni)/i", $ascii, $str);
      }
    }

    return $str;
  }
  /* Change title */
  public function changeTitle($text = '')
  {
    if ($text != '') {
      $text = strtolower($this->utf8Convert($text));
      $text = preg_replace("/[^a-z0-9-\s]/", "", $text);
      $text = preg_replace('/([\s]+)/', '-', $text);
      $text = str_replace(array('%20', ' '), '-', $text);
      $text = preg_replace("/\-\-\-\-\-/", "-", $text);
      $text = preg_replace("/\-\-\-\-/", "-", $text);
      $text = preg_replace("/\-\-\-/", "-", $text);
      $text = preg_replace("/\-\-/", "-", $text);
      $text = '@' . $text . '@';
      $text = preg_replace('/\@\-|\-\@|\@/', '', $text);
    }

    return $text;
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
  /* Lấy date */
  public function makeDate($time = 0, $dot = '.', $lang = 'vi', $f = false)
  {
    $str = ($lang == 'vi') ? date("d{$dot}m{$dot}Y", $time) : date("m{$dot}d{$dot}Y", $time);
    if ($f == true) {
      $thu['vi'] = array('Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy');
      $thu['en'] = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
      $str = $thu[$lang][date('w', $time)] . ', ' . $str;
    }
    return $str;
  }

  /* Lấy tình trạng nhận tin */
  public function getStatusNewsletter($confirm_status = 0, $type = '')
  {
    global $config;
    $loai = '';
    if (!empty($config['newsletter'][$type]['confirm_status'])) {
      foreach ($config['newsletter'][$type]['confirm_status'] as $key => $value) {
        if ($key == $confirm_status) {
          $loai = $value;
          break;
        }
      }
    }
    if ($loai == '') $loai = "Đang chờ duyệt...";
    return $loai;
  }
  /* Lấy thông tin chi tiết */
  public function getInfoDetail($cols = '', $table = '', $id = 0)
  {
    $row = array();
    if (!empty($cols) && !empty($table) && !empty($id)) {
      $row = $this->d->rawQueryOne("SELECT `$cols` FROM `$table` WHERE id = ? LIMIT 1", [$id]);
    }
    return $row;
  }

  /* Join column */
  public function joinCols($array = null, $column = null)
  {
    $str = '';
    $arrayTemp = array();
    if ($array && $column) {
      foreach ($array as $k => $v) {
        if (!empty($v[$column])) {
          $arrayTemp[] = $v[$column];
        }
      }
      if (!empty($arrayTemp)) {
        $arrayTemp = array_unique($arrayTemp);
        $str = implode(",", $arrayTemp);
      }
    }
    return $str;
  }

  public function orderStatus($status = 0)
  {
    $rows = $this->d->rawQuery("SELECT * FROM `table_order_status` ORDER BY id");
    $str = '<select id="order_status" name="data[order_status]" class="form-control custom-select text-sm">';
    $str .= '<option value="0">' . chontinhtrang . '</option>';
    if (!empty($rows)) {
      foreach ($rows as $v) {
        $selected = '';
        if (
          (isset($_REQUEST['order_status']) && (int)$_REQUEST['order_status'] === (int)$v['id']) ||
          ((int)$status === (int)$v['id'])
        ) {
          $selected = 'selected';
        }
        $str .= '<option value="' . $v['id'] . '" ' . $selected . '>' . $v['namevi'] . '</option>';
      }
    }
    $str .= '</select>';
    return $str;
  }
  /* Get payments order */
  public function orderPayments($payment = 0)
  {
    $rows = $this->d->rawQuery("SELECT * FROM `table_news` WHERE type = ? ORDER BY numb, id DESC", ["hinh-thuc-thanh-toan"]);
    $str  = '<select id="order_payment" name="order_payment" class="form-control custom-select text-sm">';
    $str .= '<option value="0">' . chonhinhthucthanhtoan . '</option>';
    if (!empty($rows)) {
      foreach ($rows as $v) {
        $selected = '';
        if ((isset($_REQUEST['order_payment']) && (int)$_REQUEST['order_payment'] === (int)$v['id']) || ((int)$payment === (int)$v['id'])
        ) {
          $selected = 'selected';
        }
        $str .= '<option value="' . $v['id'] . '" ' . $selected . '>' . $v['namevi'] . '</option>';
      }
    }
    $str .= '</select>';
    return $str;
  }
  public function getAjaxPlace($table = '', $title_select = chondanhmuc)
  {
    if (empty($table)) return '';
    $allowTable = ['table_city', 'table_district', 'table_ward'];
    if (!in_array($table, $allowTable)) {
      return '';
    }
    $where  = '';
    $params = [0];
    switch ($table) {
      case 'table_city':
        $id_parent  = 'id_city';
        $data_level = 'data-level="0"';
        $data_table = 'data-table="table_district"';
        $data_child = 'data-child="id_district"';
        break;
      case 'table_district':
        $id_parent  = 'id_district';
        $data_level = 'data-level="1"';
        $data_table = 'data-table="table_ward"';
        $data_child = 'data-child="id_ward"';
        $idcity = !empty($_REQUEST['id_city']) ? (int)$_REQUEST['id_city'] : 0;
        $where .= ' AND id_city = ?';
        $params[] = $idcity;
        break;
      case 'table_ward':
        $id_parent = 'id_ward';
        $idcity = !empty($_REQUEST['id_city']) ? (int)$_REQUEST['id_city'] : 0;
        $iddistrict = !empty($_REQUEST['id_district']) ? (int)$_REQUEST['id_district'] : 0;
        $where .= ' AND id_city = ? AND id_district = ?';
        $params[] = $idcity;
        $params[] = $iddistrict;
        $data_level = '';
        $data_table = '';
        $data_child = '';
        break;
    }
    $rows = $this->d->rawQuery("SELECT id, name FROM $table WHERE id <> ? $where ORDER BY id ASC", $params);
    $str  = '<select id="' . $id_parent . '" name="data[' . $id_parent . ']" ';
    $str .= $data_level . ' ' . $data_table . ' ' . $data_child;
    $str .= ' class="form-control select2 select-place">';
    $str .= '<option value="0">' . $title_select . '</option>';
    if (!empty($rows)) {
      foreach ($rows as $v) {
        $selected = '';
        if (isset($_REQUEST[$id_parent]) && (int)$_REQUEST[$id_parent] === (int)$v['id']) {
          $selected = 'selected';
        }
        $str .= '<option value="' . $v['id'] . '" ' . $selected . '>' . $v['name'] . '</option>';
      }
    }
    $str .= '</select>';
    return $str;
  }
  /* Kiểm tra dữ liệu nhập vào */
  public function cleanInput($input = '', $type = '')
  {
    $output = '';
    if ($input != '') {
      $search = array(
        'script' => '@<script[^>]*?>.*?</script>@si',
        'style' => '@<style[^>]*?>.*?</style>@siU',
        'blank' => '@
        <![\s\S]*?--[ \t\n\r]*>@',
        'iframe' => '/<iframe(.*?)<\/iframe>/is',
        'title' => '/<title(.*?)<\/title>/is',
        'pre' => '/<pre(.*?)<\/pre>/is',
        'frame' => '/<frame(.*?)<\/frame>/is',
        'frameset' => '/<frameset(.*?)<\/frameset>/is',
        'object' => '/<object(.*?)<\/object>/is',
        'embed' => '/<embed(.*?)<\/embed>/is',
        'applet' => '/<applet(.*?)<\/applet>/is',
        'meta' => '/<meta(.*?)<\/meta>/is',
        'doctype' => '/<!doctype(.*?)>/is',
        'link' => '/<link(.*?)>/is',
        'body' => '/<body(.*?)<\/body>/is',
        'html' => '/<html(.*?)<\/html>/is',
        'head' => '/<head(.*?)<\/head>/is',
        'onclick' => '/onclick="(.*?)"/is',
        'ondbclick' => '/ondbclick="(.*?)"/is',
        'onchange' => '/onchange="(.*?)"/is',
        'onmouseover' => '/onmouseover="(.*?)"/is',
        'onmouseout' => '/onmouseout="(.*?)"/is',
        'onmouseenter' => '/onmouseenter="(.*?)"/is',
        'onmouseleave' => '/onmouseleave="(.*?)"/is',
        'onmousemove' => '/onmousemove="(.*?)"/is',
        'onkeydown' => '/onkeydown="(.*?)"/is',
        'onload' => '/onload="(.*?)"/is',
        'onunload' => '/onunload="(.*?)"/is',
        'onkeyup' => '/onkeyup="(.*?)"/is',
        'onkeypress' => '/onkeypress="(.*?)"/is',
        'onblur' => '/onblur="(.*?)"/is',
        'oncopy' => '/oncopy="(.*?)"/is',
        'oncut' => '/oncut="(.*?)"/is',
        'onpaste' => '/onpaste="(.*?)"/is',
        'php-tag' => '/<(\?|\%)\=?(php)?/',
        'php-short-tag' => '/(\%|\?)>/'
      );
      if (!empty($type)) {
        unset($search[$type]);
      }
      $output = preg_replace($search, '', $input);
    }
    return $output;
  }

  /* Kiểm tra dữ liệu nhập vào */
  public function sanitize($input = '', $type = '')
  {
    if (is_array($input)) {
      foreach ($input as $var => $val) {
        $output[$var] = $this->sanitize($val, $type);
      }
    } else {
      $output  = $this->cleanInput($input, $type);
    }
    return $output;
  }

  /* Kiểm tra phân quyền menu */
  public function checkPermission($com = '', $act = '', $type = '', $array = null, $case = '')
  {
    global $is_logged_in;
    $str = $com;
    if ($act) $str .= '_' . $act;
    if ($case == 'phrase-1') {
      if ($type != '') $str .= '_' . $type;
      if (!in_array($str, $_SESSION[$is_logged_in]['permissions'])) return true;
      else return false;
    } else if ($case == 'phrase-2') {
      $count = 0;
      if ($array) {
        foreach ($array as $key => $value) {
          if (!empty($value['dropdown'])) {
            unset($array[$key]);
          }
        }
        foreach ($array as $key => $value) {
          if (!in_array($str . "_" . $key, $_SESSION[$is_logged_in]['permissions'])) $count++;
        }
        if ($count == count($array)) return true;
      } else return false;
    }
    return false;
  }

  /* Kiểm tra phân quyền */
  public function checkRole()
  {
    global $config, $loginAdmin;
    if ((!empty($_SESSION[$loginAdmin]['role']) && $_SESSION[$loginAdmin]['role'] == 3) || !empty($config['website']['debug-developer'])) return false;
    else return true;
  }

  /* Get Img size */
  public function getImgSize($photo = '', $patch = '')
  {
    $array = array();
    if ($photo != '') {
      $x = (file_exists($patch)) ? getimagesize($patch) : null;
      $array = (!empty($x)) ? array("p" => $photo, "w" => $x[0], "h" => $x[1], "m" => $x['mime']) : null;
    }
    return $array;
  }

  /* Upload name */
  public function uploadName($name = '')
  {
    $result = '';

    if ($name != '') {
      $rand = rand(1000, 9999);
      $ten_anh = pathinfo($name, PATHINFO_FILENAME);
      $result = $this->changeTitle($ten_anh) . "-" . $rand;
    }

    return $result;
  }

  /* Resize images */
  public function smartResizeImage($file = '', $string = null, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false, $quality = 100, $grayscale = false)
  {
    if ($height <= 0 && $width <= 0) return false;
    if ($file === null && $string === null) return false;
    $info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
    $image = '';
    $final_width = 0;
    $final_height = 0;
    list($width_old, $height_old) = $info;
    $cropHeight = $cropWidth = 0;
    if ($proportional) {
      if ($width == 0) $factor = $height / $height_old;
      elseif ($height == 0) $factor = $width / $width_old;
      else $factor = min($width / $width_old, $height / $height_old);
      $final_width = round($width_old * $factor);
      $final_height = round($height_old * $factor);
    } else {
      $final_width = ($width <= 0) ? $width_old : $width;
      $final_height = ($height <= 0) ? $height_old : $height;
      $widthX = $width_old / $width;
      $heightX = $height_old / $height;
      $x = min($widthX, $heightX);
      $cropWidth = ($width_old - $width * $x) / 2;
      $cropHeight = ($height_old - $height * $x) / 2;
    }
    switch ($info[2]) {
      case IMAGETYPE_JPEG:
        $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);
        break;
      case IMAGETYPE_GIF:
        $file !== null ? $image = imagecreatefromgif($file) : $image = imagecreatefromstring($string);
        break;
      case IMAGETYPE_PNG:
        $file !== null ? $image = imagecreatefrompng($file) : $image = imagecreatefromstring($string);
        break;
      default:
        return false;
    }
    if ($grayscale) {
      imagefilter($image, IMG_FILTER_GRAYSCALE);
    }
    $image_resized = imagecreatetruecolor($final_width, $final_height);
    if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
      $transparency = imagecolortransparent($image);
      $palletsize = imagecolorstotal($image);
      if ($transparency >= 0 && $transparency < $palletsize) {
        $transparent_color = imagecolorsforindex($image, $transparency);
        $transparency = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
        imagefill($image_resized, 0, 0, $transparency);
        imagecolortransparent($image_resized, $transparency);
      } elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
      }
    }
    imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
    if ($delete_original) {
      if ($use_linux_commands) exec('rm ' . $file);
      else @unlink($file);
    }
    switch (strtolower($output)) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
        break;
      case 'file':
        $output = $file;
        break;
      case 'return':
        return $image_resized;
        break;
      default:
        break;
    }
    switch ($info[2]) {
      case IMAGETYPE_GIF:
        imagegif($image_resized, $output);
        break;
      case IMAGETYPE_JPEG:
        imagejpeg($image_resized, $output, $quality);
        break;
      case IMAGETYPE_PNG:
        $quality = 9 - (int)((0.9 * $quality) / 10.0);
        imagepng($image_resized, $output, $quality);
        break;
      default:
        return false;
    }
    return true;
  }


  /* Remove Sub folder */
  public function RemoveEmptySubFolders($path = '')
  {
    $empty = true;

    foreach (glob($path . DIRECTORY_SEPARATOR . "*") as $file) {
      if (is_dir($file)) {
        if (!$this->RemoveEmptySubFolders($file)) $empty = false;
      } else {
        $empty = false;
      }
    }

    if ($empty) {
      if (is_dir($path)) {
        rmdir($path);
      }
    }

    return $empty;
  }

  /* Remove files from dir in x seconds */
  public function RemoveFilesFromDirInXSeconds($dir = '', $seconds = 3600)
  {
    $files = glob(rtrim($dir, '/') . "/*");
    $now = time();

    if ($files) {
      foreach ($files as $file) {
        $filename = basename($file);
        if (is_file($file) && $filename != 'index.txt') {
          if ($now - filemtime($file) >= $seconds) {
            unlink($file);
          }
        } else {
          $this->RemoveFilesFromDirInXSeconds($file, $seconds);
        }
      }
    }
  }

  /* Remove zero bytes */
  public function removeZeroByte($dir)
  {
    $files = glob(rtrim($dir, '/') . "/*");
    if ($files) {
      foreach ($files as $file) {
        $filename = basename($file);
        if (is_file($file) && $filename != 'index.txt') {
          if (!filesize($file)) {
            unlink($file);
          }
        } else {
          $this->removeZeroByte($file);
        }
      }
    }
  }

  /* Filter opacity */
  public function filterOpacity($img = '', $opacity = 80)
  {
    return true;
    /*
			if(!isset($opacity) || $img == '') return false;

			$opacity /= 100;
			$w = imagesx($img);
			$h = imagesy($img);
			imagealphablending($img, false);
			$minalpha = 127;

			for($x = 0; $x < $w; $x++)
			{
				for($y = 0; $y < $h; $y++)
				{
					$alpha = (imagecolorat($img, $x, $y) >> 24) & 0xFF;
					if($alpha < $minalpha) $minalpha = $alpha;
				}
			}

			for($x = 0; $x < $w; $x++)
			{
				for($y = 0; $y < $h; $y++)
				{
					$colorxy = imagecolorat($img, $x, $y);
					$alpha = ($colorxy >> 24) & 0xFF;
					if($minalpha !== 127) $alpha = 127 + 127 * $opacity * ($alpha - 127) / (127 - $minalpha);
					else $alpha += 127 * $opacity;
					$alphacolorxy = imagecolorallocatealpha($img, ($colorxy >> 16) & 0xFF, ($colorxy >> 8) & 0xFF, $colorxy & 0xFF, $alpha);
					if(!imagesetpixel($img, $x, $y, $alphacolorxy)) return false;
				}
			}

			return true;
			*/
  }

  /* Convert Webp */
  public function converWebp($in)
  {
    global $config;
    $in = $_SERVER['DOCUMENT_ROOT'] . $config['database']['url'] . str_replace(ASSET, "", $in);
    if (!extension_loaded('imagick')) {
      ob_start();
      WebPConvert::serveConverted($in, $in . ".webp", [
        'fail' => 'original',
        //'show-report' => true,
        'serve-image' => [
          'headers' => [
            'cache-control' => true,
            'vary-accept' => true,
          ],
          'cache-control-header' => 'max-age=2',
        ],
        'convert' => [
          "quality" => 100
        ]
      ]);
      file_put_contents($in . ".webp", ob_get_contents());
      ob_end_clean();
    } else {
      WebPConvert::convert($in, $in . ".webp", [
        'fail' => 'original',
        'convert' => [
          'quality' => 100,
          'max-quality' => 100,
        ]
      ]);
    }
  }

  public function createThumb($width_thumb = 0, $height_thumb = 0, $zoom_crop = '1', $src = '', $watermark = null, $path = THUMBS, $preview = false, $args = array(), $quality = 100)
  {
    $t = 3600 * 24 * 3;
    $this->RemoveFilesFromDirInXSeconds(UPLOAD_TEMP_L, 1);
    if ($watermark != null) {
      $this->RemoveFilesFromDirInXSeconds(WATERMARK . '/' . $path . "/", $t);
      $this->RemoveEmptySubFolders(WATERMARK . '/' . $path . "/");
    } else {
      $this->RemoveFilesFromDirInXSeconds($path . "/", $t);
      $this->RemoveEmptySubFolders($path . "/");
    }
    $src = str_replace("%20", " ", $src);
    if (!file_exists($src)) die("NO IMAGE $src");
    $image_url = $src;
    $origin_x = 0;
    $origin_y = 0;
    $new_width = $width_thumb;
    $new_height = $height_thumb;
    if ($new_width < 10 && $new_height < 10) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
      die("Width and height larger than 10px");
    }
    if ($new_width > 2000 || $new_height > 2000) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
      die("Width and height less than 2000px");
    }
    $array = getimagesize($image_url);
    if ($array) list($image_w, $image_h) = $array;
    else die("NO IMAGE $image_url");
    $width = $image_w;
    $height = $image_h;
    if ($new_height && !$new_width) $new_width = $width * ($new_height / $height);
    else if ($new_width && !$new_height) $new_height = $height * ($new_width / $width);
    $image_ext = explode('.', $image_url);
    $image_ext = trim(strtolower(end($image_ext)));
    $image_name = explode('/', $image_url);
    $image_name = trim(strtolower(end($image_name)));
    switch (strtoupper($image_ext)) {
      case 'WEBP':
        $image = imagecreatefromwebp($image_url);
        $func = 'imagejpeg';
        $mime_type = 'webp';
        break;
      case 'JPG':
      case 'JPEG':
        $image = imagecreatefromjpeg($image_url);
        $func = 'imagejpeg';
        $mime_type = 'jpeg';
        break;
      case 'PNG':
        $image = imagecreatefrompng($image_url);
        $func = 'imagepng';
        $mime_type = 'png';
        break;
      case 'GIF':
        $image = imagecreatefromgif($image_url);
        $func = 'imagegif';
        $mime_type = 'png';
        break;
      default:
        die("UNKNOWN IMAGE TYPE: $image_url");
    }
    $_new_width = $new_width;
    $_new_height = $new_height;
    if ($zoom_crop == 3) {
      $final_height = $height * ($new_width / $width);
      if ($final_height > $new_height) $new_width = $width * ($new_height / $height);
      else $new_height = $final_height;
    }
    $canvas = imagecreatetruecolor($new_width, $new_height);
    imagealphablending($canvas, false);
    $color = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
    imagefill($canvas, 0, 0, $color);
    if ($zoom_crop == 2) {
      $final_height = $height * ($new_width / $width);
      if ($final_height > $new_height) {
        $origin_x = $new_width / 2;
        $new_width = $width * ($new_height / $height);
        $origin_x = round($origin_x - ($new_width / 2));
      } else {
        $origin_y = $new_height / 2;
        $new_height = $final_height;
        $origin_y = round($origin_y - ($new_height / 2));
      }
    }
    imagesavealpha($canvas, true);
    if ($zoom_crop > 0) {
      $align = '';
      $src_x = $src_y = 0;
      $src_w = $width;
      $src_h = $height;
      $cmp_x = $width / $new_width;
      $cmp_y = $height / $new_height;
      if ($cmp_x > $cmp_y) {
        $src_w = round($width / $cmp_x * $cmp_y);
        $src_x = round(($width - ($width / $cmp_x * $cmp_y)) / 2);
      } else if ($cmp_y > $cmp_x) {
        $src_h = round($height / $cmp_y * $cmp_x);
        $src_y = round(($height - ($height / $cmp_y * $cmp_x)) / 2);
      }
      if ($align) {
        if (strpos($align, 't') !== false) {
          $src_y = 0;
        }
        if (strpos($align, 'b') !== false) {
          $src_y = $height - $src_h;
        }
        if (strpos($align, 'l') !== false) {
          $src_x = 0;
        }
        if (strpos($align, 'r') !== false) {
          $src_x = $width - $src_w;
        }
      }
      imagecopyresampled($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);
    } else {
      imagecopyresampled($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    }
    if ($preview) {
      $watermark = array();
      $watermark['status'] = 'hienthi';
      $options = $args;
      $overlay_url = $args['watermark'];
    }
    $upload_dir = '';
    $folder_old = str_replace($image_name, '', $image_url);
    if (!empty($watermark['status']) && strpos('hienthi', $watermark['status']) !== false) {
      $upload_dir = WATERMARK . '/' . $path . '/' . $width_thumb . 'x' . $height_thumb . 'x' . $zoom_crop . '/' . $folder_old;
    } else {
      if ($watermark != null) $upload_dir = WATERMARK . '/' . $path . '/' . $width_thumb . 'x' . $height_thumb . 'x' . $zoom_crop . '/' . $folder_old;
      else $upload_dir = $path . '/' . $width_thumb . 'x' . $height_thumb . 'x' . $zoom_crop . '/' . $folder_old;
    }
    if (!file_exists($upload_dir)) if (!mkdir($upload_dir, 0777, true)) die('Failed to create folders...');
    if (!empty($watermark['status']) && strpos('hienthi', $watermark['status']) !== false) {
      $options = (isset($options)) ? $options : json_decode($watermark['options'], true)['watermark'];
      $per_scale = $options['per'];
      $per_small_scale = $options['small_per'];
      $max_width_w = $options['max'];
      $min_width_w = $options['min'];
      $opacity = @$options['opacity'];
      $overlay_url = (isset($overlay_url)) ? $overlay_url : UPLOAD_PHOTO_L . $watermark['photo'];
      $overlay_ext = explode('.', $overlay_url);
      $overlay_ext = trim(strtolower(end($overlay_ext)));
      switch (strtoupper($overlay_ext)) {
        case 'JPG':
        case 'JPEG':
          $overlay_image = imagecreatefromjpeg($overlay_url);
          break;
        case 'PNG':
          $overlay_image = imagecreatefrompng($overlay_url);
          break;
        case 'GIF':
          $overlay_image = imagecreatefromgif($overlay_url);
          break;
        default:
          die("UNKNOWN IMAGE TYPE: $overlay_url");
      }
      // $this->filterOpacity($overlay_image,$opacity);
      $overlay_width = imagesx($overlay_image);
      $overlay_height = imagesy($overlay_image);
      $overlay_padding = 5;
      imagealphablending($canvas, true);
      if (min($_new_width, $_new_height) <= 300) $per_scale = $per_small_scale;
      $oz = max($overlay_width, $overlay_height);
      if ($overlay_width > $overlay_height) {
        $scale = $_new_width / $oz;
      } else {
        $scale = $_new_height / $oz;
      }
      if ($_new_height > $_new_width) {
        $scale = $_new_height / $oz;
      }
      $new_overlay_width = (floor($overlay_width * $scale) - $overlay_padding * 2) / $per_scale;
      $new_overlay_height = (floor($overlay_height * $scale) - $overlay_padding * 2) / $per_scale;
      $scale_w = $new_overlay_width / $new_overlay_height;
      $scale_h = $new_overlay_height / $new_overlay_width;
      $new_overlay_height = $new_overlay_width / $scale_w;
      if ($new_overlay_height > $_new_height) {
        $new_overlay_height = $_new_height / $per_scale;
        $new_overlay_width = $new_overlay_height * $scale_w;
      }
      if ($new_overlay_width > $_new_width) {
        $new_overlay_width = $_new_width / $per_scale;
        $new_overlay_height = $new_overlay_width * $scale_h;
      }
      if (($_new_width / $new_overlay_width) < $per_scale) {
        $new_overlay_width = $_new_width / $per_scale;
        $new_overlay_height = $new_overlay_width * $scale_h;
      }
      if ($_new_height < $_new_width && ($_new_height / $new_overlay_height) < $per_scale) {
        $new_overlay_height = $_new_height / $per_scale;
        $new_overlay_width = $new_overlay_height / $scale_h;
      }
      if ($new_overlay_width > $max_width_w && $new_overlay_width) {
        $new_overlay_width = $max_width_w;
        $new_overlay_height = $new_overlay_width * $scale_h;
      }
      if ($new_overlay_width < $min_width_w && $_new_width <= $min_width_w * 3) {
        $new_overlay_width = $min_width_w;
        $new_overlay_height = $new_overlay_width * $scale_h;
      }
      $new_overlay_width = round($new_overlay_width);
      $new_overlay_height = round($new_overlay_height);
      switch ($options['position']) {
        case 1:
          $khoancachx = $overlay_padding;
          $khoancachy = $overlay_padding;
          break;
        case 2:
          $khoancachx = abs($_new_width - $new_overlay_width) / 2;
          $khoancachy = $overlay_padding;
          break;
        case 3:
          $khoancachx = abs($_new_width - $new_overlay_width) - $overlay_padding;
          $khoancachy = $overlay_padding;
          break;
        case 4:
          $khoancachx = abs($_new_width - $new_overlay_width) - $overlay_padding;
          $khoancachy = abs($_new_height - $new_overlay_height) / 2;
          break;
        case 5:
          $khoancachx = abs($_new_width - $new_overlay_width) - $overlay_padding;
          $khoancachy = abs($_new_height - $new_overlay_height) - $overlay_padding;
          break;
        case 6:
          $khoancachx = abs($_new_width - $new_overlay_width) / 2;
          $khoancachy = abs($_new_height - $new_overlay_height) - $overlay_padding;
          break;
        case 7:
          $khoancachx = $overlay_padding;
          $khoancachy = abs($_new_height - $new_overlay_height) - $overlay_padding;
          break;
        case 8:
          $khoancachx = $overlay_padding;
          $khoancachy = abs($_new_height - $new_overlay_height) / 2;
          break;
        case 9:
          $khoancachx = abs($_new_width - $new_overlay_width) / 2;
          $khoancachy = abs($_new_height - $new_overlay_height) / 2;
          break;
        default:
          $khoancachx = $overlay_padding;
          $khoancachy = $overlay_padding;
          break;
      }
      $overlay_new_image = imagecreatetruecolor($new_overlay_width, $new_overlay_height);
      imagealphablending($overlay_new_image, false);
      imagesavealpha($overlay_new_image, true);
      imagecopyresampled($overlay_new_image, $overlay_image, 0, 0, 0, 0, $new_overlay_width, $new_overlay_height, $overlay_width, $overlay_height);
      imagecopy($canvas, $overlay_new_image, $khoancachx, $khoancachy, 0, 0, $new_overlay_width, $new_overlay_height);
      imagealphablending($canvas, false);
      imagesavealpha($canvas, true);
    }
    if ($preview) {
      $upload_dir = '';
      $this->RemoveEmptySubFolders(WATERMARK . '/' . $path . "/");
    }
    if ($upload_dir) {
      if ($func == 'imagejpeg') $func($canvas, $upload_dir . $image_name, 100);
      else $func($canvas, $upload_dir . $image_name, floor($quality * 0.09));
    }
    header('Content-Type: image/' . $mime_type);
    if ($func == 'imagejpeg') $func($canvas, NULL, 100);
    else $func($canvas, NULL, floor($quality * 0.09));
    imagedestroy($canvas);
  }
}
