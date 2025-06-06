<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class news
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function show_news_by_type($type, $hienthi = '', $noibat = '')
    {
        $conditions = ["type = '$type'"];

        if ($hienthi !== '') {
            $conditions[] = "hienthi = '$hienthi'";
        }

        if ($noibat !== '') {
            $conditions[] = "noibat = '$noibat'";
        }

        $query = "SELECT * FROM tbl_news";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $query .= " ORDER BY numb ASC";

        return $this->db->select($query);
    }


    public function show_chinhsach_noibat($hienthi = '', $noibat = '')
    {
        $conditions = ["type = 'chinh-sach'"];

        if ($hienthi !== '') {
            $conditions[] = "hienthi = '$hienthi'";
        }

        if ($noibat !== '') {
            $conditions[] = "noibat = '$noibat'";
        }

        $query = "SELECT * FROM tbl_news";

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY numb ASC";
        return $this->db->select($query);
    }


    public function total_pages_tintuc_lienquan($id, $id_cat, $limit)
    {
        $id_cat = mysqli_real_escape_string($this->db->link, $id_cat);
        $query = "SELECT COUNT(*) as total FROM tbl_news WHERE id_cat = '$id_cat' AND id != '$id'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            $total_products = $row['total'];
            $total_pages = ceil($total_products / $limit);
            return $total_pages;
        } else {
            return 0;
        }
    }


    public function relatedNews($id, $type)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $type = mysqli_real_escape_string($this->db->link, $type);
        $query = "SELECT * FROM tbl_news WHERE type = '$type' AND id != '$id' AND hienthi='hienthi' ORDER BY numb ASC";
        $result = $this->db->select($query);
        return $result;
    }


    public function update_views_by_slug($slug)
    {
        $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug'";
        $result = $this->db->select($query);
        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $new_views = $product['views'] + 1;
            $update_query = "UPDATE tbl_news SET views = '$new_views' WHERE slugvi = '$slug'";
            $this->db->update($update_query);
            return $product;
        }
        return false;
    }


    public function get_danhmuc_by_tintuc($id)
    {
        $query = "SELECT tbl_news.*, 
        tbl_danhmuc.namevi AS danhmuc, 
        tbl_danhmuc.slugvi AS danhmuc_slugvi, 
        tbl_danhmuc_c2.namevi AS danhmuc_c2, 
        tbl_danhmuc_c2.slugvi AS danhmuc_c2_slugvi
        FROM tbl_news 
        INNER JOIN tbl_danhmuc ON tbl_news.id_list = tbl_danhmuc.id 
        LEFT JOIN tbl_danhmuc_c2 ON tbl_news.id_cat = tbl_danhmuc_c2.id
        WHERE tbl_news.id = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_news($slug)
    {
        $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug' AND hienthi = 'hienthi' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_chinhsach($slug)
    {
        $query = "SELECT * FROM tbl_news WHERE slugvi = '$slug' AND hienthi = 'hienthi' AND type = 'chinh-sach' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_news($data, $files, $id)
    {
        $fields = ['slugvi', 'namevi', 'descvi', 'contentvi', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'noibat', 'numb', 'type'];
        foreach ($fields as $field) {
            $$field = mysqli_real_escape_string($this->db->link, $data[$field]);
        }
        $file_name = $_FILES["file"]["name"];
        $unique_image = '';
        if (!empty($file_name)) {
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $unique_image);
            $del_file_query = "SELECT file FROM tbl_news WHERE id='$id'";
            $old_file = $this->db->select($del_file_query);
            if ($old_file && $old_file->num_rows > 0) {
                $rowData = $old_file->fetch_assoc();
                $old_file_path = "uploads/" . $rowData['file'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        }
        $check_slug = "SELECT slugvi FROM tbl_news WHERE slugvi = '$slugvi' AND id != '$id'";
        $result_check_slug = $this->db->select($check_slug);
        if ($result_check_slug && $result_check_slug->num_rows > 0) {
            return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
        }
        $query = "UPDATE tbl_news SET 
                slugvi = '$slugvi',
                namevi = '$namevi',
                descvi = '$descvi',
                contentvi = '$contentvi',
                file = '" . ($unique_image ?: $this->db->select("SELECT file FROM tbl_news WHERE id='$id'")->fetch_assoc()['file']) . "',
                titlevi = '$titlevi',
                keywordsvi = '$keywordsvi',
                descriptionvi = '$descriptionvi',
                hienthi = '$hienthi',
                noibat = '$noibat',
                numb = '$numb' ,
                type = '$type' 
                WHERE id = '$id'";
        $result = $this->db->update($query);
        if ($result) {
            header("Location: transfer.php?stt=success&url=$type");
            exit();
        } else {
            return "Lỗi thao tác!";
        }
    }


    public function get_id_news($id)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT * FROM tbl_news WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function delete_multiple_news($listid = [], $type)
    {
        if (empty($listid)) {
            return "Không có bản ghi nào được chọn.";
        }
        $type = mysqli_real_escape_string($this->db->link, $type);
        $del_file_query = "SELECT file FROM tbl_news WHERE id IN ($listid)";
        $old_files = $this->db->select($del_file_query);
        if ($old_files) {
            while ($rowData = $old_files->fetch_assoc()) {
                $old_file_path = "uploads/" . $rowData['file'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        }
        $query = "DELETE FROM tbl_news WHERE id IN ($listid)";
        $result = $this->db->delete($query);
        if ($result) {
            header("Location: transfer.php?stt=success&url=$type");
            exit();
        } else {
            return "Lỗi thao tác!";
        }
    }

    public function del_news($id, $type)
    {
        $id = (int)$id;
        $type = mysqli_real_escape_string($this->db->link, $type);
        $del_file_name = "SELECT file FROM tbl_news WHERE id='$id' AND type='$type'";
        $delta = $this->db->select($del_file_name);
        if ($delta && $delta->num_rows > 0) {
            $rowData = $delta->fetch_assoc();
            $file_name = $rowData['file'];
            if (!empty($file_name)) {
                $file_path = "uploads/$file_name";
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }
        $query = "DELETE FROM tbl_news WHERE id = '$id' AND type='$type'";
        $result = $this->db->delete($query);
        if ($result) {
            header("Location: transfer.php?stt=success&url=$type");
            exit();
        } else {
            return "Lỗi thao tác!";
        }
    }

    public function show_news($records_per_page, $current_page, $hienthi = '', $type = '')
    {
        $type = mysqli_real_escape_string($this->db->link, $type);
        $hienthi = mysqli_real_escape_string($this->db->link, $hienthi);
        $records_per_page = (int)$records_per_page;
        $current_page = (int)$current_page;
        $offset = ($current_page - 1) * $records_per_page;

        if (!empty($hienthi)) {
            $query = "SELECT * FROM tbl_news WHERE hienthi = '$hienthi' AND type = '$type' ORDER BY numb ASC";
        } else {
            $query = "SELECT * FROM tbl_news WHERE type = '$type' ORDER BY numb, id DESC LIMIT $records_per_page OFFSET $offset";
        }

        $result = $this->db->select($query);
        return $result;
    }


    public function add_tbl_news($data, $files)
    {
        $fields = ['slugvi', 'namevi', 'descvi', 'contentvi', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'noibat', 'numb', 'type'];
        $tintuc_data = [];
        foreach ($fields as $field) {
            $tintuc_data[$field] = mysqli_real_escape_string($this->db->link, $data[$field]);
        }

        $file_name = $_FILES["file"]["name"];
        $unique_image = '';
        if (!empty($file_name)) {
            if (!is_dir("uploads")) {
                mkdir("uploads", 0755, true);
            }
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $unique_image);
        }

        $check_slug = "SELECT COUNT(*) as count FROM tbl_news WHERE slugvi = '{$tintuc_data['slugvi']}' LIMIT 1";
        $result_check_slug = $this->db->select($check_slug);
        if ($result_check_slug && $result_check_slug->fetch_assoc()['count'] > 0) {
            return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
        }

        $query = "INSERT INTO tbl_news (slugvi, namevi, descvi, contentvi, file, titlevi, keywordsvi, descriptionvi, hienthi, noibat, numb, type) VALUES ('{$tintuc_data['slugvi']}', '{$tintuc_data['namevi']}', '{$tintuc_data['descvi']}', '{$tintuc_data['contentvi']}', '$unique_image','{$tintuc_data['titlevi']}', '{$tintuc_data['keywordsvi']}', '{$tintuc_data['descriptionvi']}', '{$tintuc_data['hienthi']}','{$tintuc_data['noibat']}', '{$tintuc_data['numb']}', '{$tintuc_data['type']}')";

        $result = $this->db->insert($query);
        if ($result) {
            $product_id = $this->db->link->insert_id;
            header("Location: transfer.php?stt=success&url={$tintuc_data['type']}");
            exit();
        } else {
            return "Lỗi thao tác!";
        }
    }
}
?>