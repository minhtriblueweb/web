<?php
function current_year()
{
  return date('Y');
}

/**
 * Lấy giá trị từ bảng setting
 * @param string $key
 * @param bool $htmlspecialchars
 * @param string $default
 * @return string
 */
