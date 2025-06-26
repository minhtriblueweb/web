<?php
class JsAssets
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
      'server' => rtrim($this->access['server'], '/') . '/' . ltrim($path, '/'),
      'asset' => rtrim($this->access['asset'], '/') . '/' . ltrim($path, '/')
    ];
    $this->file[] = $path;
  }

  private function links()
  {
    $output = '';
    foreach ($this->path as $js) {
      $output .= '<script src="' . $js['asset'] . '"></script>' . PHP_EOL;
    }
    return $output;
  }

  private function minify()
  {
    return $this->links();
  }

  public function get()
  {
    if (empty($this->path)) die("No JS files to include");
    return $this->debug ? $this->links() : $this->minify();
  }

  public function enableDebug()
  {
    $this->debug = true;
  }

  public function disableDebug()
  {
    $this->debug = false;
  }
}
