<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class danhgia
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function xoanhieu_danhgia($listid)
    {
        $del_file_query = "SELECT file FROM tbl_danhgia WHERE id IN ($listid)";
        $old_files = $this->db->select($del_file_query);
        if ($old_files) {
            while ($rowData = $old_files->fetch_assoc()) {
                $old_file_path = "uploads/" . $rowData['file'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);  // Xóa file nếu tồn tại
                }
            }
        }
        $query = "DELETE FROM tbl_danhgia WHERE id IN ($listid)";
        $result = $this->db->delete($query);
        if ($result) {
            header('Location: transfer.php?stt=success&url=danhgia');
        } else {
            return "Lỗi thao tác!";
        }
    }


    public function update_danhgia($data, $files, $id)
    {
        $name = mysqli_real_escape_string($this->db->link, $data['name']);
        $desc = mysqli_real_escape_string($this->db->link, $data['desc']);
        $address = mysqli_real_escape_string($this->db->link, $data['address']);
        $hienthi = mysqli_real_escape_string($this->db->link, $data['hienthi']);
        $numb = mysqli_real_escape_string($this->db->link, $data['numb']);
        $unique_image = '';
        if (!empty($files['file']['name'])) {
            $file_ext = strtolower(pathinfo($files['file']['name'], PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            move_uploaded_file($files['file']['tmp_name'], "uploads/" . $unique_image);

            // Xóa file cũ nếu có
            $del_file_query = "SELECT file FROM tbl_danhgia WHERE id='$id'";
            $old_file = $this->db->select($del_file_query);
            if ($old_file && $old_file->num_rows > 0) {
                $rowData = $old_file->fetch_assoc();
                $old_file_path = "uploads/" . $rowData['file'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        }
        $query = "UPDATE tbl_danhgia SET name = '$name',address = '$address', `desc` = '$desc', hienthi = '$hienthi', numb = '$numb'";
        if (!empty($unique_image)) {
            $query .= ", file = '$unique_image'";
        }
        $query .= " WHERE id = '$id'";
        $result = $this->db->update($query);
        if ($result) {
            header('Location: transfer.php?stt=success&url=danhgia');
        } else {
            return "Lỗi thao tác!";
        }
    }


    public function get_id_danhgia($id)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT * FROM tbl_danhgia WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function del_danhgia($id)
    {
        $query = "SELECT file FROM tbl_danhgia WHERE id='$id'";
        $delta = $this->db->select($query);
        $file = $delta ? $delta->fetch_assoc()['file'] : null;

        if ($file) {
            $filePath = "uploads/" . $file;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $result = $this->db->delete("DELETE FROM tbl_danhgia WHERE id = '$id'");
        if ($result) {
            header('Location: transfer.php?stt=success&url=danhgia');
        } else {
            return "Lỗi thao tác !";
        }
    }


    public function show_danhgia($hienthi = '')
    {
        $query = "SELECT * FROM tbl_danhgia ";
        if (!empty($hienthi)) {
            $query .= "WHERE hienthi = '$hienthi' ";
        }
        $query .= "ORDER BY numb ASC";
        $result = $this->db->select($query);
        return $result;
    }


    public function insert_danhgia($data, $files)
    {
        $name = mysqli_real_escape_string($this->db->link, $data['name']);
        $address = mysqli_real_escape_string($this->db->link, $data['address']);
        $desc = mysqli_real_escape_string($this->db->link, $data['desc']);
        $hienthi = mysqli_real_escape_string($this->db->link, $data['hienthi']);
        $numb = mysqli_real_escape_string($this->db->link, $data['numb']);
        $unique_image = '';
        if (!empty($files['file']['name'])) {
            $file_ext = strtolower(pathinfo($files['file']['name'], PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            move_uploaded_file($files['file']['tmp_name'], "uploads/" . $unique_image);
        }
        $query = "INSERT INTO tbl_danhgia(name,address, `desc`, hienthi, numb, file) VALUES('$name','$address', '$desc', '$hienthi', '$numb', '$unique_image')";
        // Kiểm tra kết quả
        if ($this->db->insert($query)) {
            header('Location: transfer.php?stt=success&url=danhgia');
        } else {
            return "Lỗi thao tác!";
        }
    }
}

?>