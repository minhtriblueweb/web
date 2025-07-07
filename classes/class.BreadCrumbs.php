<?php
class breadcrumbs
{
  private $data = [];

  public function set($slug = '', $name = '')
  {
    if (!empty($name)) {
      $this->data[] = [
        'slug' => $slug,
        'name' => $name
      ];
    }
  }
  public function get()
  {
    global $configBase;
    $html = '<ol class="breadcrumb">';
    $html .= '<li class="breadcrumb-item"><a class="text-decoration-none text-capitalize" href="' . $configBase . '"><span>Trang chủ</span></a></li>';

    $json = [];
    foreach ($this->data as $i => $item) {
      $isLast = ($i === count($this->data) - 1);
      $class = $isLast ? 'active' : '';
      $slug = !empty($item['slug']) ? $configBase . $item['slug'] : '#';

      $html .= '<li class="text-capitalize breadcrumb-item ' . $class . '">';
      if ($isLast) {
        $html .= '<span>' . htmlspecialchars($item['name']) . '</span>';
      } else {
        $html .= '<a class="text-decoration-none text-capitalize" href="' . $slug . '"><span>' . htmlspecialchars($item['name']) . '</span></a>';
      }
      $html .= '</li>';

      $json[] = [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $item['name'],
        'item' => $slug
      ];
    }

    $html .= '</ol>';
    $html .= '<script type="application/ld+json">' . json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'BreadcrumbList',
      'itemListElement' => $json
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';

    return $html;
  }
}
