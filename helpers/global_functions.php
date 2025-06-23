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
function get_setting_value($key, $htmlspecialchars = true, $default = '')
{
  global $row_st;
  if (isset($row_st[$key])) {
    return $htmlspecialchars ? htmlspecialchars($row_st[$key]) : $row_st[$key];
  }
  return $default;
}
