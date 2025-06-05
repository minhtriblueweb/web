<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class phuongthuctt
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function xoanhieu_phuongthuctt($listid)
    {
        // Lấy danh sách các file cần xóa trước khi xóa bản ghi
        $del_file_query = "SELECT file FROM tbl_phuongthuctt WHERE id IN ($listid)";
        $old_files = $this->db->select($del_file_query);

        // Xóa các file trên hệ thống
        if ($old_files) {
            while ($rowData = $old_files->fetch_assoc()) {
                $old_file_path = "uploads/" . $rowData['file'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);  // Xóa file nếu tồn tại
                }
            }
        }

        // Xóa bản ghi trong cơ sở dữ liệu
        $query = "DELETE FROM tbl_phuongthuctt WHERE id IN ($listid)";
        $result = $this->db->delete($query);

        // Kiểm tra kết quả
        if ($result) {
            header('Location: transfer.php?stt=success&url=phuongthuctt');
        } else {
            return "Lỗi thao tác!";
        }
    }


    public function update_phuongthuctt($data, $files, $id)
    {
        $name = mysqli_real_escape_string($this->db->link, $data['name']);
        $content = mysqli_real_escape_string($this->db->link, $data['content']);
        $hienthi = mysqli_real_escape_string($this->db->link, $data['hienthi']);
        $numb = mysqli_real_escape_string($this->db->link, $data['numb']);
        $unique_image = '';
        if (!empty($files['file']['name'])) {
            $file_ext = strtolower(pathinfo($files['file']['name'], PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            move_uploaded_file($files['file']['tmp_name'], "uploads/" . $unique_image);

            // Xóa file cũ nếu có
            $del_file_query = "SELECT file FROM tbl_phuongthuctt WHERE id='$id'";
            $old_file = $this->db->select($del_file_query);
            if ($old_file && $old_file->num_rows > 0) {
                $rowData = $old_file->fetch_assoc();
                $old_file_path = "uploads/" . $rowData['file'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        }
        $query = "UPDATE tbl_phuongthuctt SET name = '$name', content = '$content', hienthi = '$hienthi', numb = '$numb'";
        if (!empty($unique_image)) {
            $query .= ", file = '$unique_image'";
        }
        $query .= " WHERE id = '$id'";
        $result = $this->db->update($query);
        if ($result) {
            header('Location: transfer.php?stt=success&url=phuongthuctt');
        } else {
            return "Lỗi thao tác!";
        }
    }
    public function get_id_phuongthuctt($id)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT * FROM tbl_phuongthuctt WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function del_phuongthuctt($id)
    {
        $query = "SELECT file FROM tbl_phuongthuctt WHERE id='$id'";
        $delta = $this->db->select($query);
        $file = $delta ? $delta->fetch_assoc()['file'] : null;

        if ($file) {
            $filePath = "uploads/" . $file;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $result = $this->db->delete("DELETE FROM tbl_phuongthuctt WHERE id = '$id'");
        if ($result) {
            header('Location: transfer.php?stt=' . urlencode("success") . '&url=' . urlencode("phuongthuctt"));
        } else {
            return "Lỗi thao tác !";
        }
    }


    public function show_phuongthuctt($hienthi = '')
    {
        $query = "SELECT * FROM tbl_phuongthuctt ";
        if (!empty($hienthi)) {
            $query .= "WHERE hienthi = '$hienthi' ";
        }
        $query .= "ORDER BY numb ASC";
        $result = $this->db->select($query);
        return $result;
    }
    public function insert_phuongthuctt($data, $files)
    {
        $name = mysqli_real_escape_string($this->db->link, $data['name']);
        $content = mysqli_real_escape_string($this->db->link, $data['content']);
        $hienthi = mysqli_real_escape_string($this->db->link, $data['hienthi']);
        $numb = mysqli_real_escape_string($this->db->link, $data['numb']);
        $unique_image = '';
        if (!empty($files['file']['name'])) {
            $file_ext = strtolower(pathinfo($files['file']['name'], PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            move_uploaded_file($files['file']['tmp_name'], "uploads/" . $unique_image);
        }
        $query = "INSERT INTO tbl_phuongthuctt(name, content, hienthi, numb, file) VALUES('$name', '$content', '$hienthi', '$numb', '$unique_image')";
        if ($this->db->insert($query)) {
            header('Location: transfer.php?stt=success&url=phuongthuctt');
        } else {
            return "Lỗi thao tác!";
        }
    }
}

?>