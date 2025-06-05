<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class sanpham
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function total_pages_sanpham_lienquan($id, $id_cat, $limit)
    {
        $id_cat = mysqli_real_escape_string($this->db->link, $id_cat);
        $query = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE id_cat = '$id_cat' AND id != '$id'";
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


    public function sanpham_lienquan($id, $id_cat, $records_per_page, $current_page)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $id_cat = mysqli_real_escape_string($this->db->link, $id_cat);
        $offset = ((int)$current_page - 1) * (int)$records_per_page;
        $query = "SELECT * FROM tbl_sanpham WHERE id_cat = '$id_cat' AND id != '$id' AND hienthi='hienthi' ORDER BY numb ASC LIMIT $records_per_page OFFSET $offset";
        $result = $this->db->select($query);
        return $result;
    }

    public function xoanhieu_gallery($listid)
    {
        $id_array = explode(',', $listid);
        foreach ($id_array as $id) {
            $id = mysqli_real_escape_string($this->db->link, trim($id));
            $del_file_name = "SELECT * FROM tbl_gallery WHERE id='$id'";
            $delta = $this->db->select($del_file_name);
            if ($delta) {
                $get_id_parent = $delta->fetch_assoc();
                $photo = $get_id_parent['photo'];
                $file_path = "uploads/" . $photo;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                $query = "DELETE FROM tbl_gallery WHERE id = '$id'";
                $result = $this->db->delete($query);
                if (!$result) {
                    return "Lỗi thao tác khi xóa ID: $id";
                }
            } else {
                return "Không tìm thấy ảnh để xoá cho ID: $id";
            }
        }
        header('Location: transfer.php?stt=success&url=gallery&id=' . $get_id_parent['id_parent']);
    }


    public function them_gallery($data, $files, $id)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $uploaded_images = [];

        for ($i = 0; $i < 5; $i++) {
            if (!empty($files["file$i"]['name']) && $files["file$i"]['error'] == 0) {
                $file_name = $files["file$i"]['name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $unique_image = substr(md5(time() . $i), 0, 10) . '.' . $file_ext;
                move_uploaded_file($files["file$i"]['tmp_name'], "uploads/" . $unique_image);
                $uploaded_images[] = $unique_image;

                $hienthi = mysqli_real_escape_string($this->db->link, $data["hienthi$i"]);
                $numb = mysqli_real_escape_string($this->db->link, $data["numb$i"]);

                $gallery_query = "INSERT INTO tbl_gallery (id_parent, photo, numb, hienthi) VALUES ('$id', '$unique_image', '$numb', '$hienthi')";
                $this->db->insert($gallery_query);
            }
        }
        header('Location: transfer.php?stt=success&url=gallery&id=' . $id);
    }

    public function del_gallery($id)
    {
        $del_file_name = "SELECT * FROM tbl_gallery WHERE id='$id'";
        $delta = $this->db->select($del_file_name);
        if ($delta) {
            $get_id_parent = $delta->fetch_assoc();
            $photo = $get_id_parent['photo'];
            $file_path = "uploads/" . $photo;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            $query = "DELETE FROM tbl_gallery WHERE id = '$id'";
            $result = $this->db->delete($query);
            if ($result) {
                header('Location: transfer.php?stt=success&url=gallery&id=' . $get_id_parent['id_parent']);
            } else {
                return "Lỗi thao tác!";
            }
        } else {
            return "Không tìm thấy ảnh để xoá!";
        }
    }


    public function get_gallery($id)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT * FROM tbl_gallery WHERE id_parent = '$id' ORDER BY numb ASC";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_views_by_slug($slug)
    {
        $query = "SELECT * FROM tbl_sanpham WHERE slugvi = '$slug'";
        $result = $this->db->select($query);
        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $new_views = $product['views'] + 1;
            $update_query = "UPDATE tbl_sanpham SET views = '$new_views' WHERE slugvi = '$slug'";
            $this->db->update($update_query);
            return $product;
        }
        return false;
    }


    public function get_danhmuc_by_sanpham($id)
    {
        $query = "SELECT tbl_sanpham.*, 
        tbl_danhmuc.namevi AS danhmuc, 
        tbl_danhmuc.slugvi AS danhmuc_slugvi, 
        tbl_danhmuc_c2.namevi AS danhmuc_c2, 
        tbl_danhmuc_c2.slugvi AS danhmuc_c2_slugvi
        FROM tbl_sanpham 
        INNER JOIN tbl_danhmuc ON tbl_sanpham.id_list = tbl_danhmuc.id 
        LEFT JOIN tbl_danhmuc_c2 ON tbl_sanpham.id_cat = tbl_danhmuc_c2.id
        WHERE tbl_sanpham.id = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_sanpham($slug)
    {
        $query = "SELECT * FROM tbl_sanpham WHERE slugvi = '$slug' AND hienthi = 'hienthi' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_sanpham($data, $files, $id)
    {
        $fields = ['slugvi', 'namevi', 'id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'banchay', 'numb'];
        foreach ($fields as $field) {
            $$field = mysqli_real_escape_string($this->db->link, $data[$field]);
        }
        $descvi = $data['descvi'];
        $contentvi = $data['contentvi'];
        $file_name = $_FILES["file"]["name"];
        $unique_image = '';
        if (!empty($file_name)) {
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $unique_image);
            $del_file_query = "SELECT file FROM tbl_sanpham WHERE id='$id'";
            $old_file = $this->db->select($del_file_query);
            if ($old_file && $old_file->num_rows > 0) {
                $rowData = $old_file->fetch_assoc();
                $old_file_path = "uploads/" . $rowData['file'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        }
        $check_slug = "SELECT slugvi FROM tbl_sanpham WHERE slugvi = '$slugvi' AND id != '$id'";
        $result_check_slug = $this->db->select($check_slug);
        if ($result_check_slug && $result_check_slug->num_rows > 0) {
            return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
        }
        $query = "UPDATE tbl_sanpham SET 
                slugvi = '$slugvi',
                namevi = '$namevi',
                descvi = '$descvi',
                contentvi = '$contentvi',
                file = '" . ($unique_image ?: $this->db->select("SELECT file FROM tbl_sanpham WHERE id='$id'")->fetch_assoc()['file']) . "',
                id_list = '$id_list',
                id_cat = '$id_cat',
                regular_price = '$regular_price',
                sale_price = '$sale_price',
                discount = '$discount',
                code = '$code',
                titlevi = '$titlevi',
                keywordsvi = '$keywordsvi',
                descriptionvi = '$descriptionvi',
                hienthi = '$hienthi',
                banchay = '$banchay',
                numb = '$numb' 
                WHERE id = '$id'";
        $result = $this->db->update($query);
        if (!empty($files['files']['name'][0])) {
            foreach ($files['files']['name'] as $key => $file_name) {
                if ($files['files']['error'][$key] == 0) { // Kiểm tra không có lỗi
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $unique_image = substr(md5(time() . $key), 0, 10) . '.' . $file_ext;
                    move_uploaded_file($files['files']['tmp_name'][$key], "uploads/" . $unique_image);
                    $gallery_query = "INSERT INTO tbl_gallery (id_parent, photo) VALUES ('$id', '$unique_image')";
                    $this->db->insert($gallery_query);
                }
            }
        }
        if ($result) {
            header('Location: transfer.php?stt=success&url=sanpham');
        } else {
            return "Lỗi thao tác!";
        }
    }


    public function get_id_sanpham($id)
    {
        $id = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT * FROM tbl_sanpham WHERE id = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function xoanhieu_sanpham($listid)
    {
        // Lấy danh sách các file cần xóa trước khi xóa bản ghi
        $del_file_query = "SELECT file FROM tbl_sanpham WHERE id IN ($listid)";
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
        $query = "DELETE FROM tbl_sanpham WHERE id IN ($listid)";
        $result = $this->db->delete($query);
        if ($result) {
            header('Location: transfer.php?stt=success&url=sanpham');
        } else {
            return "Lỗi thao tác!";
        }
    }

    public function del_sanpham($id)
    {
        $del_file_name = "SELECT file FROM tbl_sanpham WHERE id='$id'";
        $delta = $this->db->select($del_file_name);
        $string = "";
        while ($rowData = $delta->fetch_assoc()) {
            $string .= $rowData['file'];
        }

        $query = "DELETE FROM tbl_sanpham WHERE id = '$id'";
        $result = $this->db->delete($query);
        if ($result) {
            header('Location: transfer.php?stt=success&url=sanpham');
        } else {
            return "Lỗi thao tác!";
        }
    }

    public function get_name_danhmuc($id)
    {
        $id_1 = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT namevi FROM tbl_danhmuc WHERE id = '$id_1' LIMIT 1";
        $result = $this->db->select($query);
        if ($result) {
            $resulted = $result->fetch_assoc();
            $get_name = $resulted['namevi'];
        }
        return $get_name;
    }

    public function get_name_danhmuc_2($id)
    {
        $id_2 = mysqli_real_escape_string($this->db->link, $id);
        $query = "SELECT namevi FROM tbl_danhmuc_c2 WHERE id = '$id_2' LIMIT 1";
        $result = $this->db->select($query);
        if ($result) {
            $resulted = $result->fetch_assoc();
            $get_name = $resulted['namevi'];
        }
        return $get_name;
    }

    public function show_sanpham($records_per_page, $current_page, $hienthi = '')
    {
        if (!empty($hienthi)) {
            $query = "SELECT * FROM tbl_sanpham WHERE hienthi = '$hienthi' ORDER BY numb ASC";
            $result = $this->db->select($query);
            return $result;
        } else {
            $offset = ((int)$current_page - 1) * (int)$records_per_page;
            $query = "SELECT * FROM tbl_sanpham ORDER BY numb,id DESC LIMIT $records_per_page OFFSET $offset";
            $result = $this->db->select($query);
            return $result;
        }
    }

    public function sanpham_banchay()
    {
        $query = "SELECT * FROM tbl_sanpham WHERE banchay = 'banchay' AND hienthi = 'hienthi' ORDER BY numb ASC";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_sanpham_tc($id_list = '')
    {
        $query = "SELECT * FROM tbl_sanpham WHERE id_list = '$id_list' AND hienthi = 'hienthi' ORDER BY numb ASC LIMIT 10";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_sanpham_cap_1($id_list = '')
    {
        $query = "SELECT * FROM tbl_sanpham WHERE id_list = '$id_list' AND hienthi = 'hienthi' ORDER BY numb DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function count_sanpham_cap_1($id_list = '')
    {
        $query = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE id_list = '$id_list' AND hienthi = 'hienthi'";
        $result = $this->db->select($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    public function show_sanpham_c2_tc($id_cat = '')
    {
        $query = "SELECT * FROM tbl_sanpham WHERE id_cat = '$id_cat' AND hienthi = 'hienthi' ORDER BY numb ASC";
        $result = $this->db->select($query);
        return $result;
    }

    public function count_sanpham_cap_2($id_cat = '')
    {
        $query = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE id_cat = '$id_cat' AND hienthi = 'hienthi'";
        $result = $this->db->select($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }

    public function them_sanpham($data, $files)
    {
        $fields = ['slugvi', 'namevi', 'id_list', 'id_cat', 'regular_price', 'sale_price', 'discount', 'code', 'titlevi', 'keywordsvi', 'descriptionvi', 'hienthi', 'banchay', 'numb'];
        $sanpham_data = [];
        foreach ($fields as $field) {
            $sanpham_data[$field] = mysqli_real_escape_string($this->db->link, $data[$field]);
        }
        $sanpham_data['descvi'] = $data['descvi'];
        $sanpham_data['contentvi'] = $data['contentvi'];
        $file_name = $_FILES["file"]["name"];
        $unique_image = '';
        if (!empty($file_name)) {
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext; // Tạo tên file duy nhất
            move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $unique_image);
        }
        $check_slug = "SELECT COUNT(*) as count FROM tbl_sanpham WHERE slugvi = '{$sanpham_data['slugvi']}' LIMIT 1";
        $result_check_slug = $this->db->select($check_slug);
        if ($result_check_slug && $result_check_slug->fetch_assoc()['count'] > 0) {
            return "Đường dẫn đã tồn tại. Đường dẫn truy cập mục này có thể bị trùng lặp";
        }
        $query = "INSERT INTO tbl_sanpham (slugvi, namevi, descvi, contentvi, file, id_list, id_cat, regular_price, sale_price, discount, code, titlevi, keywordsvi, descriptionvi, hienthi, banchay, numb) VALUES ('{$sanpham_data['slugvi']}', '{$sanpham_data['namevi']}', '{$sanpham_data['descvi']}', '{$sanpham_data['contentvi']}', '$unique_image', '{$sanpham_data['id_list']}', '{$sanpham_data['id_cat']}', '{$sanpham_data['regular_price']}', '{$sanpham_data['sale_price']}', '{$sanpham_data['discount']}', '{$sanpham_data['code']}', '{$sanpham_data['titlevi']}', '{$sanpham_data['keywordsvi']}', '{$sanpham_data['descriptionvi']}', '{$sanpham_data['hienthi']}', '{$sanpham_data['banchay']}', '{$sanpham_data['numb']}')";

        $result = $this->db->insert($query);

        if ($result) {
            $last_id_query = "SELECT id FROM tbl_sanpham ORDER BY id DESC LIMIT 1";
            $last_id_result = $this->db->select($last_id_query);
            $product_id = $last_id_result->fetch_assoc()['id'];
            $uploaded_images = [];
            if (!empty($files['files']['name'][0])) {
                foreach ($files['files']['name'] as $key => $file_name) {
                    if ($files['files']['error'][$key] == 0) {
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        $unique_image = substr(md5(time() . $key), 0, 10) . '.' . $file_ext;
                        move_uploaded_file($files['files']['tmp_name'][$key], "uploads/" . $unique_image);
                        $uploaded_images[] = $unique_image;
                    }
                }
            }
            foreach ($uploaded_images as $image) {
                $gallery_query = "INSERT INTO tbl_gallery (id_parent, photo) VALUES ('$product_id', '$image')";
                $this->db->insert($gallery_query);
            }

            header('Location: transfer.php?stt=success&url=sanpham');
        } else {
            return "Lỗi thao tác!";
        }
    }
}
?>