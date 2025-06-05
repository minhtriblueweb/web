<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class danhmuc
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function get_name_danhmuc($id_list)
    {
        $id_list = mysqli_real_escape_string($this->db->link, $id_list);
        $query = "SELECT * FROM tbl_danhmuc WHERE id = '$id_list' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_danhmuc($slug)
    {
        $slug = mysqli_real_escape_string($this->db->link, $slug);
        $query = "SELECT * FROM tbl_danhmuc WHERE slugvi = '$slug' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_danhmuc_c2($slug)
    {
        $slug = mysqli_real_escape_string($this->db->link, $slug);
        $query = "SELECT * FROM tbl_danhmuc_c2 WHERE slugvi = '$slug' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function xoanhieu_danhmuc($listid)
    {
        $query = "DELETE FROM tbl_danhmuc WHERE id IN ($listid)";
        $result = $this->db->delete($query);
        if ($result) {
            $stt = "success";
            $url = "danhmuc";
            header('Location: transfer.php?stt=' . urlencode($stt) . '&url=' . urlencode($url));
        } else {
            $alert = "Lỗi thao tác !";
            return $alert;
        }
    }

    public function xoanhieu_danhmuc_c2($listid)
    {
        $query = "DELETE FROM tbl_danhmuc_c2 WHERE id IN ($listid)";
        $result = $this->db->delete($query);
        if ($result) {
            $stt = "success";
            $url = "danhmuccap2";
            header('Location: transfer.php?stt=' . urlencode($stt) . '&url=' . urlencode($url));
        } else {
            $alert = "Lỗi thao tác !";
            return $alert;
        }
    }

    public function update_danhmuc_c2($data, $files, $id)
    {
        // Danh sách các trường cần xử lý
        $fields = ['slugvi', 'namevi', 'id_list', 'titlevi', 'keywordsvi', 'descriptionvi', 'numb', 'hienthi'];
        $data_escaped = [];

        // Escape các trường dữ liệu
        foreach ($fields as $field) {
            $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
        }

        $slugvi = $data_escaped['slugvi'];

        // Kiểm tra slug có tồn tại không
        $check_slug = "SELECT slugvi FROM tbl_danhmuc_c2 WHERE slugvi = '$slugvi' AND id != '$id'";
        $result_check_slug = $this->db->select($check_slug);

        if ($result_check_slug && $result_check_slug->num_rows > 0) {
            $existing_slug = $result_check_slug->fetch_assoc()['slugvi'];
            if ($existing_slug === $slugvi) {
                return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
            }
        }

        // Xử lý file hình ảnh
        $file_name = $_FILES["file"]["name"];
        $unique_image = "";

        if (!empty($file_name)) {
            $file_tmp_name = $_FILES["file"]["tmp_name"];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            $uploaded_image = "uploads/" . $unique_image;
            move_uploaded_file($file_tmp_name, $uploaded_image);

            // Xóa file cũ
            $del_file_name = "SELECT file FROM tbl_danhmuc_c2 WHERE id='$id'";
            $delta = $this->db->select($del_file_name);

            if ($delta && $delta->num_rows > 0) {
                $old_file_name = $delta->fetch_assoc()['file'];
                $delLink = "uploads/" . $old_file_name;
                if (file_exists($delLink)) {
                    unlink($delLink); // Xóa file hình ảnh cũ
                }
            }
        }

        // Tạo câu truy vấn UPDATE
        $query = "UPDATE tbl_danhmuc_c2 SET 
            slugvi = '$slugvi',
            namevi = '{$data_escaped['namevi']}',
            id_list = '{$data_escaped['id_list']}',"
            . (!empty($unique_image) ? " file = '$unique_image'," : "") .
            " titlevi = '{$data_escaped['titlevi']}',
            keywordsvi = '{$data_escaped['keywordsvi']}',
            descriptionvi = '{$data_escaped['descriptionvi']}',
            hienthi = '{$data_escaped['hienthi']}',
            numb = '{$data_escaped['numb']}'
            WHERE id = '$id'";

        // Thực thi câu truy vấn
        $result = $this->db->update($query);

        // Kiểm tra kết quả
        if ($result) {
            header('Location: transfer.php?stt=' . urlencode("success") . '&url=' . urlencode("danhmuccap2"));
        } else {
            return "Lỗi thao tác!";
        }
    }


    public function update_danhmuc($data, $files, $id)
    {
        $fields = ['slugvi', 'namevi', 'descvi', 'contentvi', 'titlevi', 'keywordsvi', 'descriptionvi', 'numb', 'hienthi'];
        $data_escaped = [];
        foreach ($fields as $field) {
            $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
        }
        $check_slug_query = "SELECT slugvi FROM tbl_danhmuc WHERE slugvi = '{$data_escaped['slugvi']}' AND id != '$id'";
        $result_check_slug = $this->db->select($check_slug_query);
        if ($result_check_slug && $result_check_slug->num_rows > 0) {
            return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
        }
        $file_name = $_FILES["file"]["name"];
        $unique_image = "";

        if (!empty($file_name)) {
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            $uploaded_image = "uploads/" . $unique_image;
            move_uploaded_file($_FILES["file"]["tmp_name"], $uploaded_image);
            $del_file_query = "SELECT file_name FROM tbl_danhmuc WHERE id = '$id'";
            $old_file = $this->db->select($del_file_query);
            if ($old_file && $old_file->num_rows > 0) {
                $row = $old_file->fetch_assoc();
                $old_file_path = "uploads/" . $row['file_name'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        }
        $query = "UPDATE tbl_danhmuc SET 
                slugvi = '{$data_escaped['slugvi']}',
                namevi = '{$data_escaped['namevi']}',
                descvi = '{$data_escaped['descvi']}',
                contentvi = '{$data_escaped['contentvi']}',
                titlevi = '{$data_escaped['titlevi']}',
                keywordsvi = '{$data_escaped['keywordsvi']}',
                descriptionvi = '{$data_escaped['descriptionvi']}',
                hienthi = '{$data_escaped['hienthi']}',
                numb = '{$data_escaped['numb']}'";
        if (!empty($unique_image)) {
            $query .= ", file_name = '$unique_image'";
        }
        $query .= " WHERE id = '$id'";
        $result = $this->db->update($query);
        if ($result) {
            header('Location: transfer.php?stt=success&url=danhmuc');
        } else {
            return "Lỗi thao tác!";
        }
    }

    public function get_id_danhmuc($id)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT * FROM tbl_danhmuc WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_id_danhmuc_c2($id)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT * FROM tbl_danhmuc_c2 WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function del_danhmuc($id)
    {
        $del_file_name = "SELECT file_name FROM tbl_danhmuc WHERE id='$id'";
        $delta = $this->db->select($del_file_name);
        $string = "";
        while ($rowData = $delta->fetch_assoc()) {
            $string .= $rowData['file_name'];
        }

        $query = "DELETE FROM tbl_danhmuc WHERE id = '$id'";
        $result = $this->db->delete($query);
        if ($result) {
            $stt = "success";
            $url = "danhmuc";
            header('Location: transfer.php?stt=' . urlencode($stt) . '&url=' . urlencode($url));
        } else {
            $alert = "Lỗi thao tác !";
            return $alert;
        }
    }

    public function del_danhmuc_c2($id)
    {
        // $del_file_name="SELECT file FROM tbl_danhmuc_c2 WHERE id='$id'";
        // $delta=$this->db->select($del_file_name);
        // $string=""; 
        // while($rowData=$delta->fetch_assoc()){
        //     $string .= $rowData['file'];
        // }
        // $delLink = "uploads/" . $string;
        // unlink("$delLink");
        $query = "DELETE FROM tbl_danhmuc_c2 WHERE id = '$id'";
        $result = $this->db->delete($query);
        if ($result) {
            $stt = "success";
            $url = "danhmuccap2";
            header('Location: transfer.php?stt=' . urlencode($stt) . '&url=' . urlencode($url));
        } else {
            $alert = "Lỗi thao tác !";
            return $alert;
        }
    }

    public function show_danhmuc_index($hienthi = '')
    {
        $query = "SELECT * FROM tbl_danhmuc";
        if (!empty($hienthi)) {
            $query .= " WHERE hienthi = '$hienthi'";
        }
        $query .= " ORDER BY numb ASC";
        return $this->db->select($query);
    }

    public function show_danhmuc_noibat($hienthi = '', $noibat = '')
    {
        $query = "SELECT * FROM tbl_danhmuc";
        if (!empty($hienthi)) {
            $query .= " WHERE hienthi = '$hienthi' AND noibat = '$noibat'";
        }
        $query .= " ORDER BY numb ASC";
        return $this->db->select($query);
    }

    public function show_danhmuc()
    {
        $query = "SELECT * FROM tbl_danhmuc ORDER BY numb ASC";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_danhmuc_pt($records_per_page, $current_page)
    {
        $offset = ((int)$current_page - 1) * (int)$records_per_page;
        $query = "SELECT * FROM tbl_danhmuc ORDER BY numb ASC LIMIT $records_per_page OFFSET $offset";
        $result = $this->db->select($query);
        return $result;
    }
    public function insert_danhmuc($data, $files)
    {
        // Danh sách các trường cần escape
        $fields = ['slugvi', 'namevi', 'descvi', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'numb'];
        $data_escaped = [];

        // Escape các trường dữ liệu
        foreach ($fields as $field) {
            $data_escaped[$field] = !empty($data[$field]) ? mysqli_real_escape_string($this->db->link, $data[$field]) : "";
        }

        // Lấy các giá trị cần thiết
        $contentvi = $data['contentvi'];
        $slugvi = $data_escaped['slugvi'];

        // Xử lý file hình ảnh
        $file_name = $_FILES["file"]["name"];
        $file_tmp_name = $_FILES["file"]["tmp_name"];
        $unique_image = "";

        if (!empty($file_name)) {
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            $uploaded_image = "uploads/" . $unique_image;

            // Di chuyển file hình ảnh
            move_uploaded_file($file_tmp_name, $uploaded_image);
        }

        // Kiểm tra slug đã tồn tại
        $check_slug = "SELECT slugvi FROM tbl_danhmuc WHERE slugvi = '$slugvi' LIMIT 1";
        $result_check_slug = $this->db->select($check_slug);

        if ($result_check_slug && $result_check_slug->num_rows > 0) {
            return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
        }
        // Tạo câu truy vấn INSERT
        $query = "INSERT INTO tbl_danhmuc(slugvi, namevi, descvi, contentvi, file_name, titlevi, keywordsvi, descriptionvi, hienthi, numb) VALUES('$slugvi', '{$data_escaped['namevi']}', '{$data_escaped['descvi']}', '$contentvi', '$unique_image', '{$data_escaped['titlevi']}', '{$data_escaped['keywordsvi']}', '{$data_escaped['descriptionvi']}', '{$data_escaped['hienthi']}', '{$data_escaped['numb']}')";
        // Thực hiện câu truy vấn
        $result = $this->db->insert($query);
        if ($result) {
            header('Location: transfer.php?stt=' . urlencode("success") . '&url=' . urlencode("danhmuc"));
            exit();
        } else {
            return "Lỗi thao tác !";
        }
    }


    public function insert_danhmuc_c2($data, $files)
    {
        $slugvi = mysqli_real_escape_string($this->db->link, $data['slugvi']);
        $namevi = mysqli_real_escape_string($this->db->link, $data['namevi']);
        $id_list = mysqli_real_escape_string($this->db->link, $data['id_list']);
        $titlevi = mysqli_real_escape_string($this->db->link, $data['titlevi']);
        $keywordsvi = mysqli_real_escape_string($this->db->link, $data['keywordsvi']);
        $descriptionvi = mysqli_real_escape_string($this->db->link, $data['descriptionvi']);
        $hienthi = mysqli_real_escape_string($this->db->link, $data['hienthi']);
        $numb = mysqli_real_escape_string($this->db->link, $data['numb']);
        $file_name = $_FILES["file"]["name"];
        $file_tmp_name = $_FILES["file"]["tmp_name"];
        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        if ($file_ext != NULL) {
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
        } else {
            $unique_image = "";
        }
        $uploaded_image = "uploads/" . $unique_image;
        move_uploaded_file($file_tmp_name, $uploaded_image);
        $get_slug = "";
        $check_slug = "SELECT slugvi FROM tbl_danhmuc_c2 WHERE slugvi = '$slugvi' LIMIT 1";
        $result_check_slug = $this->db->select($check_slug);
        if ($result_check_slug) {
            $resulted = $result_check_slug->fetch_assoc();
            $get_slug = $resulted['slugvi'];
        }
        if ($slugvi == $get_slug) {
            $alert = "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
            return $alert;
        } else {
            $query = "INSERT INTO tbl_danhmuc_c2(slugvi,namevi,id_list,file,titlevi,keywordsvi,descriptionvi,hienthi,numb)VALUES('$slugvi','$namevi','$id_list','$unique_image','$titlevi','$keywordsvi','$descriptionvi','$hienthi','$numb')";
        }
        $result = $this->db->insert($query);
        if ($result) {
            $stt = "success";
            $url = "danhmuccap2";
            header('Location: transfer.php?stt=' . urlencode($stt) . '&url=' . urlencode($url));
        } else {
            $alert = "Lỗi thao tác !";
            return $alert;
        }
    }

    public function show_danhmuc_c2($id_list = '')
    {
        if (!empty($id_list)) {
            $query = "SELECT * FROM tbl_danhmuc_c2 WHERE id_list = '$id_list' ORDER BY numb ASC";
        } else {
            $query = "SELECT * FROM tbl_danhmuc_c2 ORDER BY numb ASC";
        }
        $result = $this->db->select($query);
        return $result;
    }

    public function show_danhmuc_c2_index($list_id)
    {
        $query = "SELECT * FROM tbl_danhmuc_c2 WHERE id_list = '$list_id' AND hienthi = 'hienthi' ORDER BY numb ASC";
        $result = $this->db->select($query);
        return $result;
    }
}

?>