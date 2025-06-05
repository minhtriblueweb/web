<?php
    $filepath = realpath(dirname(__FILE__));
    include_once ($filepath. '/../lib/database.php');
    include_once ($filepath. '/../helpers/format.php');
?>
<?php
    class slideshow {
        private $db;
        private $fm;

        public function __construct() {
            $this->db = new Database();
            $this->fm = new Format();
        }

        public function xoanhieu_slideshow($listid) {
            $query = "DELETE FROM tbl_slideshow WHERE id IN ($listid)";
            $result = $this->db->delete($query);
            if($result){
                $stt = "success";
                $url="slideshow";
                header('Location: transfer.php?stt=' . urlencode($stt) . '&url=' . urlencode($url));
            }else{
                $alert= "Lỗi thao tác !" ;
                return $alert;
            }
        }

        public function update_slideshow($data,$files,$id) {
            $name = mysqli_real_escape_string($this->db->link, $data['name']);
            $link = mysqli_real_escape_string($this->db->link, $data['link']);
            $hienthi = mysqli_real_escape_string($this->db->link, $data['hienthi']);
            $numb = mysqli_real_escape_string($this->db->link, $data['numb']);

            $file_name = $_FILES["file"]["name"];
            $file_tmp_name = $_FILES["file"]["tmp_name"];
            $div = explode('.', $file_name);
            $file_ext = strtolower(end($div));
            $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
            $uploaded_image = "uploads/" . $unique_image;
            move_uploaded_file($file_tmp_name, $uploaded_image);
            if(!empty($file_name)){
                // chọn ảnh
                $del_file_name="SELECT file FROM tbl_slideshow WHERE id='$id'";
                $delta=$this->db->select($del_file_name);
                $string=""; 
                while($rowData=$delta->fetch_assoc()){
                    $string .= $rowData['file_name'];
                }
                $delLink = "uploads/" . $string;
                unlink("$delLink");
                $query = "UPDATE tbl_slideshow SET 
                file = '$unique_image',
                link = '$link',
                hienthi = '$hienthi',
                numb = '$numb',
                name = '$name' WHERE id = '$id'";
                $result = $this->db->update($query);
            }elseif(empty($file_name)){
                $query = "UPDATE tbl_slideshow SET 
                link = '$link',
                hienthi = '$hienthi',
                numb = '$numb',
                name = '$name' WHERE id = '$id'";
                $result = $this->db->update($query);
            }
            
            if($result){
                $stt = "success";
                $url="slideshow";
                header('Location: transfer.php?stt=' . urlencode($stt) . '&url=' . urlencode($url));
            }else{
                $alert= "Lỗi thao tác !" ;
                return $alert;
            }
                
        }

        public function get_id_slideshow($id){
            $id = mysqli_real_escape_string($this->db->link,$id);
            $query = "SELECT * FROM tbl_slideshow WHERE id = '$id' LIMIT 1";
            $result = $this->db->select($query);
            return $result;
        }

        public function del_slideshow($id) {
            $del_file_name="SELECT file FROM tbl_slideshow WHERE id='$id'";
            $delta=$this->db->select($del_file_name);
            $string=""; 
            while($rowData=$delta->fetch_assoc()){
                $string .= $rowData['file'];
            }
            $delLink = "uploads/" . $string;
            unlink("$delLink");
            $query = "DELETE FROM tbl_slideshow WHERE id = '$id'";
            $result = $this->db->delete($query);
            if($result){
                $stt = "success";
                $url="slideshow";
                header('Location: transfer.php?stt=' . urlencode($stt) . '&url=' . urlencode($url));
            }else{
                $alert= "Lỗi thao tác !" ;
                return $alert;
            }
        }

        public function show_slideshow(){
            $query = "SELECT * FROM tbl_slideshow ORDER BY numb ASC";
            $result = $this->db->select($query);
            return $result;
        }
        
        public function insert_slideshow($data,$files){
            $name = mysqli_real_escape_string($this->db->link, $data['name']);
            $link = mysqli_real_escape_string($this->db->link, $data['link']);
            $hienthi = mysqli_real_escape_string($this->db->link, $data['hienthi']);
            $numb = mysqli_real_escape_string($this->db->link, $data['numb']);
            if($_FILES["file"]["name"] != NULL){
                $file_name = $_FILES["file"]["name"];
                $file_tmp_name = $_FILES["file"]["tmp_name"];
                $div = explode('.', $file_name);
                $file_ext = strtolower(end($div));
                $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
                $uploaded_image = "uploads/" . $unique_image;
                move_uploaded_file($file_tmp_name, $uploaded_image);
            }
            $query = "INSERT INTO tbl_slideshow(name,link,hienthi,numb,file)VALUES('$name','$link','$hienthi','$numb','$unique_image')";
            $result = $this->db->insert($query);
            if($result){
                $stt = "success";
                $url="slideshow";
                header('Location: transfer.php?stt=' . urlencode($stt) . '&url=' . urlencode($url));
            }else{
                $alert= "Lỗi thao tác !" ;
                return $alert;
            }
        }
    }

?>