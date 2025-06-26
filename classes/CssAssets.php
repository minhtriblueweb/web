<?php
class CssAssets
{
  private $path = [];
  private $file = [];
  private $access = [];
  private $debug = false;

  public function __construct($access = [])
  {
    $this->access = $access;
  }

  public function set($path)
  {
    $this->path[] = [
      'server' => $this->access['server'] . $path,
      'asset' => $this->access['asset'] . $path
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

    if ($this->debug) {
      return $this->links();
    }

    return $this->minify(); // Có thể viết sau
  }

  private function links()
  {
    $html = '';
    foreach ($this->path as $file) {
      $html .= '<link href="' . $file['asset'] . '" rel="stylesheet" />' . PHP_EOL;
    }
    return $html;
  }

  private function minify()
  {
    // Nếu chưa làm hệ thống minify, có thể fallback sang links()
    return $this->links();
  }
}
