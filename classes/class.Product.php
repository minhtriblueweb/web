<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class product
{
  private $db;
  private $fn;

  public function __construct()
  {
    $this->db = new Database();
    $this->fn = new Functions();
  }
  public function upload_gallery($data, $files, $id, $type, $id_parent)
  {
    $id = (int)$id;
    $id_parent = (int)$id_parent;
    $table = 'tbl_gallery';
    $parent = $this->db->rawQueryOne("SELECT namevi FROM tbl_product WHERE id = ? LIMIT 1", [$id_parent]);
    $parent_name = $parent['namevi'] ?? '';
    $data_prepared = [
      'numb' => $data['numb'] ?? 0,
      'status' => !empty($data['hienthi']) ? 'hienthi' : ''
    ];
    $thumb_filename = '';
    if (!empty($files['file']['name']) && !empty($files['file']['tmp_name'])) {
      $old_file_path = '';
      $old = $this->db->rawQueryOne("SELECT file FROM `$table` WHERE id = ?", [$id]);
      if (!empty($old['file'])) {
        $old_file_path = UPLOADS . $old['file'];
      }
      $thumb_filename = $this->fn->uploadImage([
        'file' => $files['file'],
        'custom_name' => $parent_name,
        'old_file_path' => $old_file_path,
        'watermark' => true,
        'convert_webp' => true
      ]);
      if (empty($thumb_filename)) {
        return "Lỗi upload file!";
      }
    }
    $fields = [];
    $params = [];
    foreach ($data_prepared as $key => $val) {
      $fields[] = "`$key` = ?";
      $params[] = $val;
    }
    if ($thumb_filename) {
      $fields[] = "`file` = ?";
      $params[] = $thumb_filename;
    }
    $params[] = $id;
    $result = $this->db->execute("UPDATE `$table` SET " . implode(', ', $fields) . " WHERE id = ?", $params);
    $this->fn->transfer(
      $result ? "Cập nhật hình ảnh thành công" : "Cập nhật hình ảnh thất bại!",
      "index.php?page=gallery&act=man&id=$id_parent",
      $result
    );
  }

  public function update_views($slug)
  {
    $product = $this->db->rawQueryOne("SELECT * FROM tbl_product WHERE slugvi = ?", [$slug]);
    if (!empty($product)) {
      $new_views = $product['views'] + 1;
      $this->db->rawQuery("UPDATE tbl_product SET views = ? WHERE slugvi = ?", [$new_views, $slug]);
      return $product;
    }
    return false;
  }
}
?>
