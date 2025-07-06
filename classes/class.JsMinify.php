<?php
class JsMinify
{
  private $path = [];
  private $file = [];
  private $access = [];
  private $debug = false;
  private $cacheDir = 'caches/';
  private $cacheTime = 3600 * 24 * 7; // 7 ngày
  private $lock = array(
    'status' => false,
    'char' => ''
  );
  public function __construct($debug = false, $fn = null)
  {
    $this->debug = $debug;

    // Đường dẫn mặc định
    $this->access = [
      'server' => ROOT . '/assets/',
      'asset'  => BASE . 'assets/'
    ];

    // Dùng $fn nếu cần xử lý thêm sau này
    if ($fn instanceof Functions) {
      // ví dụ: $this->version = $fn->generateHash();
    }
  }

  public function set($path)
  {
    $this->path[] = [
      'server' => $this->access['server'] . $path,
      'asset'  => $this->access['asset'] . $path
    ];
    $this->file[] = $path;
  }

  public function enableDebug($bool = true)
  {
    $this->debug = $bool;
  }

  public function get()
  {
    if (empty($this->file)) return '';

    return $this->debug ? $this->scripts() : $this->minify();
  }

  private function scripts()
  {
    $html = '';
    foreach ($this->path as $file) {
      $html .= '<script src="' . $file['asset'] . '?v=' . $this->randomVersion() . '"></script>' . PHP_EOL;
    }
    return $html;
  }

  private function minify()
  {
    $hash = md5(implode(",", $this->file));
    $cacheFile = $this->access['server'] . $this->cacheDir . "cache_$hash.js";
    $cacheLink = $this->access['asset'] . $this->cacheDir . "cache_$hash.js";

    if (!is_dir(dirname($cacheFile))) {
      mkdir(dirname($cacheFile), 0777, true);
    }

    if (!file_exists($cacheFile) || (time() - filemtime($cacheFile) > $this->cacheTime)) {
      $js = '';
      foreach ($this->path as $file) {
        if (!file_exists($file['server'])) continue;
        $content = file_get_contents($file['server']);
        $js .= $this->compress($content);
      }
      file_put_contents($cacheFile, $js);
    }

    return '<script src="' . $cacheLink . '?v=' . filemtime($cacheFile) . '"></script>';
  }
  private function compress($js)
  {
    $js = preg_replace('/^[\t ]*?\/\/.*\s?/m', '', $js);
    $js = preg_replace('/([\s;})]+)\/\/.*/m', '\\1', $js);
    $js = preg_replace('/(\s+)\/\*([^\/]*)\*\/(\s+)/s', "\n", $js);
    $js = preg_replace('/^\s*/m', '', $js);
    $js = preg_replace('/\t+/m', ' ', $js);
    $js = preg_replace('/[\r\n]+/', '', $js);
    $js_substrings = preg_split('/([\'"])/', $js, -1, PREG_SPLIT_DELIM_CAPTURE);
    $js = '';
    foreach ($js_substrings as $substring) {
      if ($substring === '\'' || $substring === '"') {
        if ($this->lock['status'] === false) {
          $this->lock['status'] = true;
          $this->lock['char'] = $substring;
        } else {
          if ($substring === $this->lock['char']) {
            $this->lock['status'] = false;
            $this->lock['char'] = '';
          }
        }
        $js .= $substring;
        continue;
      }
      if ($this->lock['status'] === false) {
        $substring = str_replace(';}', '}', $substring);
        $substring = preg_replace('/ *([<>=+\-!\|{(},;&:?]+) */', '\\1', $substring);
      }
      $js .= $substring;
    }
    return trim($js);
  }

  private function randomVersion($length = 10)
  {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
  }

  public function clearCache()
  {
    $folder = $this->access['server'] . $this->cacheDir;
    if (is_dir($folder)) {
      foreach (glob($folder . "cache_*.js") as $file) {
        unlink($file);
      }
    }
  }
}
