<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class setting
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function update_watermark($data, $files)
    {
        // $position = mysqli_real_escape_string($this->db->link, $data['position']);
        // $offset_x = mysqli_real_escape_string($this->db->link, $data['offset_x']);
        // $offset_y = mysqli_real_escape_string($this->db->link, $data['offset_y']);

        $opacity = intval($data['opacity']);
        $position = intval($data['position']);
        $offset_x = intval($data['offset_x']);
        $offset_y = intval($data['offset_y']);

        $unique_image = '';
        $update_image = false;

        // 👉 Lấy ảnh hiện tại trong DB
        $current_query = "SELECT watermark FROM tbl_setting WHERE id = 1";
        $current_result = $this->db->select($current_query);
        $current_image = '';
        if ($current_result && $current_result->num_rows > 0) {
            $row = $current_result->fetch_assoc();
            $current_image = $row['watermark'];
        }

        // 👉 Nếu có ảnh mới
        if (!empty($files['watermark']['name'])) {
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($files['watermark']['name'], PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_extensions)) {
                $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;

                if (move_uploaded_file($files['watermark']['tmp_name'], "uploads/" . $unique_image)) {
                    $update_image = true;

                    // 👉 Xóa ảnh cũ nếu khác ảnh mới
                    if (!empty($current_image) && file_exists("uploads/" . $current_image)) {
                        unlink("uploads/" . $current_image);
                    }
                } else {
                    return "Lỗi trong quá trình tải file lên!";
                }
            } else {
                return "Loại file không hợp lệ! Chỉ chấp nhận JPG, JPEG, PNG, GIF.";
            }
        }

        // 👉 Xây dựng câu lệnh UPDATE
        $set_fields = "position = $position, opacity = $opacity, offset_x = $offset_x, offset_y = $offset_y";
        if ($update_image) {
            $set_fields .= ", watermark = '$unique_image'";
        }

        $query = "UPDATE tbl_setting SET $set_fields WHERE id = 1";
        $result = $this->db->update($query);

        if ($result) {
            header('Location: transfer.php?stt=success&url=watermark');
            exit();
        } else {
            return "Cập nhật cơ sở dữ liệu thất bại!";
        }
    }


    public function update_setting_item($item, $data, $files)
    {
        $unique_image = '';
        if (!empty($files[$item]['name'])) {
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];  // Các loại file được phép
            $file_ext = strtolower(pathinfo($files[$item]['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed_extensions)) {
                $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
                if (move_uploaded_file($files[$item]['tmp_name'], "uploads/" . $unique_image)) {
                    $del_file_query = "SELECT `$item` FROM tbl_setting WHERE id=1";
                    $old_file = $this->db->select($del_file_query);
                    if ($old_file && $old_file->num_rows > 0) {
                        $rowData = $old_file->fetch_assoc();
                        $old_file_path = "uploads/" . $rowData[$item];
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path);
                        }
                    }
                } else {
                    return "Lỗi trong quá trình tải file lên!";
                }
            } else {
                return "Loại file không hợp lệ! Chỉ chấp nhận các file JPG, JPEG, PNG và GIF.";
            }
        }
        $query = "UPDATE tbl_setting SET `$item` = '$unique_image' WHERE id = 1";
        $result = $this->db->update($query);
        if ($result) {
            header('Location: transfer.php?stt=success&url=' . $item);
        } else {
            return "Cập nhật cơ sở dữ liệu thất bại!";
        }
    }

    public function get_setting_item($item)
    {
        $query = "SELECT `$item` FROM tbl_setting WHERE id = '1' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_setting()
    {
        $query = "SELECT * FROM tbl_setting WHERE id = '1' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_setting($data)
    {
        $fields = [
            'email',
            'hotline',
            'web_name',
            'link_googlemaps',
            'fanpage',
            'copyright',
            'introduction',
            'worktime',
            'descvi',
            'coords_iframe',
            'coords',
            'analytics',
            'headjs',
            'bodyjs',
            'support',
            'client_support',
            'position'
        ];

        $updates = [];
        foreach ($fields as $field) {
            $value = mysqli_real_escape_string($this->db->link, $data[$field]);
            $updates[] = "$field = '$value'";
        }

        $query = "UPDATE tbl_setting SET " . implode(', ', $updates) . " WHERE id =1";

        $result = $this->db->update($query);
        if ($result) {
            header('Location: transfer.php?stt=success&url=setting');
        } else {
            return "Lỗi thao tác!";
        }
    }
}

?>