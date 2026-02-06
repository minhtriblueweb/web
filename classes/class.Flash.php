<?php
// $filepath = realpath(dirname(__FILE__));
// include_once($filepath . '/../lib/database.php');
?>
<?php
class Flash
{
  public function __construct()
  {
    $this->startSession();
  }

  /* ================= SESSION ================= */

  private function startSession()
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
  }

  /* ================= BASIC ================= */

  public function set($key, $value)
  {
    if ($key !== '') {
      $_SESSION['flash'][$key] = $value;
    }
  }

  public function get($key, $clear = true)
  {
    if (!isset($_SESSION['flash'][$key])) {
      return null;
    }

    $data = $_SESSION['flash'][$key];

    if ($clear) {
      unset($_SESSION['flash'][$key]);
    }

    return $data;
  }

  public function has($key)
  {
    return isset($_SESSION['flash'][$key]);
  }

  /* ================= MESSAGE ================= */

  public function getMessages($type = 'frontend')
  {
    $message = $this->get('message');
    if (empty($message)) return '';

    $data = json_decode(base64_decode($message), true);
    if (empty($data) || empty($data['messages'])) return '';

    $class = $this->mapStatus($data['status'] ?? 'danger');

    return $this->renderMessages($data['messages'], $class, $type);
  }

  /* ================= HELPER ================= */

  private function mapStatus($status)
  {
    $map = [
      'danger'  => 'danger',
      'error'   => 'danger',
      'warning' => 'warning',
      'info'    => 'info',
      'success' => 'success'
    ];

    return $map[$status] ?? 'success';
  }

  private function renderMessages(array $messages, $class, $type)
  {
    if ($type === 'admin') {
      return $this->adminHtml($messages, $class);
    }

    return $this->frontendHtml($messages, $class);
  }

  /* ================= HTML ================= */

  private function frontendHtml(array $messages, $class)
  {
    $html  = '<div class="alert alert-' . $class . '">';
    foreach ($messages as $msg) {
      $html .= '<p class="mb-1">- ' . htmlspecialchars($msg) . '</p>';
    }
    $html .= '</div>';

    return $html;
  }

  private function adminHtml(array $messages, $class)
  {
    $html  = '<div class="card bg-gradient-' . $class . '">';
    $html .= '<div class="card-header">';
    $html .= '<h3 class="card-title">Thông báo</h3>';
    $html .= '<div class="card-tools">';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>';
    $html .= '<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>';
    $html .= '</div></div>';
    $html .= '<div class="card-body">';

    foreach ($messages as $msg) {
      $html .= '<p class="mb-1">- ' . htmlspecialchars($msg) . '</p>';
    }

    $html .= '</div></div>';

    return $html;
  }
}
