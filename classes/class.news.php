<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class news
{
  private $db;
  private $fn;
  private $seo;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
    $this->seo = new seo();
  }

  public function update_views_by_slug($slug)
  {
    $query = "SELECT * FROM tbl_news WHERE slug = '$slug'";
    $result = $this->db->select($query);
    if ($result && $result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $new_views = $product['views'] + 1;
      $update_query = "UPDATE tbl_nviews SET views = '$new_views' WHERE slug = '$slug'";
      $this->db->update($update_query);
      return $product;
    }
    return false;
  }
}
?>
