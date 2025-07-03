<?php
class Cache
{
  private $db;
  private $path;

  public function __construct($db)
  {
    $this->db = $db;
    $this->path = ROOT . '/cache';

    // Tạo thư mục nếu chưa có
    if (!is_dir($this->path)) {
      mkdir($this->path, 0777, true);
    }

    // Tạo file .htaccess để chặn truy cập nếu chưa có
    $htaccess = $this->path . '/.htaccess';
    if (!file_exists($htaccess)) {
      file_put_contents($htaccess, "Deny from all\n");
    }
  }

  private function getFileName($key)
  {
    return $this->path . '/cache_' . md5($key);
  }

  private function store($key, $data, $ttl)
  {
    $filename = $this->getFileName($key);
    $data = serialize([time() + $ttl, $data]);
    file_put_contents($filename, $data);
  }

  private function read($key)
  {
    $filename = $this->getFileName($key);

    if (!file_exists($filename)) return false;

    $data = file_get_contents($filename);
    $data = @unserialize($data);

    if (!$data || time() > $data[0]) {
      @unlink($filename);
      return false;
    }

    return $data[1];
  }

  public function get($sql, $params = [], $type = 'one', $ttl = 600)
  {
    $key = $sql . '|' . serialize($params);

    if (!$data = $this->read($key)) {
      if ($type == 'all') {
        $data = $this->db->rawQueryArray($sql, $params);
      } else {
        $data = $this->db->rawQueryOne($sql, $params);
      }

      $this->store($key, $data, $ttl);
    }

    return $data;
  }


  public function deleteAll()
  {
    if (!is_dir($this->path)) return false;
    $files = glob($this->path . '/cache_*');

    foreach ($files as $file) {
      if (is_file($file)) @unlink($file);
    }

    return true;
  }

  public function deleteKey($sql, $params = [])
  {
    $key = $sql . '|' . serialize($params);
    $filename = $this->getFileName($key);
    return file_exists($filename) ? unlink($filename) : false;
  }
}
