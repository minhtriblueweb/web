<?php
class CssMinify
{
  private $path = [];
  private $file = [];
  private $access = [];
  private $debug = false;
  private $cacheDir = 'caches/';
  private $cacheTime = 3600 * 24 * 7; // 7 ngày

  public function __construct($debug = false, $fn = null)
  {
    $this->debug = $debug;

    // Gán access mặc định
    $this->access = [
      'server' => ROOT . '/assets/',
      'asset'  => BASE . 'assets/'
    ];

    // Có thể mở rộng dùng $fn nếu cần tạo hash hoặc path thông minh
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

    return $this->debug ? $this->links() : $this->minify();
  }

  private function links()
  {
    $html = '';
    foreach ($this->path as $file) {
      $html .= '<link href="' . $file['asset'] . '?v=' . $this->randomVersion() . '" rel="stylesheet" />' . PHP_EOL;
    }
    return $html;
  }
  private function minify()
  {
    $hash = md5(implode(",", $this->file));
    $cacheFile = $this->access['server'] . $this->cacheDir . "cache_$hash.css";
    $cacheLink = $this->access['asset'] . $this->cacheDir . "cache_$hash.css";

    if (!is_dir(dirname($cacheFile))) {
      mkdir(dirname($cacheFile), 0777, true);
    }

    if (!file_exists($cacheFile) || (time() - filemtime($cacheFile) > $this->cacheTime)) {
      $css = '';
      foreach ($this->path as $index => $file) {
        $serverPath = $file['server'];
        if (!file_exists($serverPath)) continue;

        $content = file_get_contents($serverPath);
        $isMinified = preg_match('/\.min\.css$/', $this->file[$index]);

        $css .= $isMinified ? $content : $this->compress($content);
      }
      file_put_contents($cacheFile, $css);
    }

    return '<link href="' . $cacheLink . '?v=' . filemtime($cacheFile) . '" rel="stylesheet" />';
  }

  private function compress($buffer)
  {
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    $buffer = str_replace(': ', ':', $buffer);
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
  }

  private function isExpire($file)
  {
    $fileTime = filemtime($file);
    $isExpire = false;

    if ((time() - $fileTime) > $this->cacheTime) {
      $isExpire = true;
    }

    return $isExpire;
  }

  private function randomVersion($length = 10)
  {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
  }

  public function clearCache()
  {
    $folder = $this->access['server'] . $this->cacheDir;
    if (is_dir($folder)) {
      foreach (glob($folder . "cache_*.css") as $file) {
        unlink($file);
      }
    }
  }
}
