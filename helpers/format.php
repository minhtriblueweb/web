<?php
class Format
{
  public function validation($data)
  {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  public function format_seo_text($text, $limit = 160)
  {
    $text = trim(strip_tags($text));
    return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit - 3) . '...' : $text;
  }
}
