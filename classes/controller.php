<?php
    $filepath = realpath(dirname(__FILE__));
    include_once ($filepath. '/../lib/database.php');
    include_once ($filepath. '/../helpers/format.php');
?>
<?php
    class contract {
        private $db;
        private $fm;

        public function __construct() {
            $this->db = new Database();
            $this->fm = new Format();
        }
        public function del_user($id) {
            $query = "DELETE FROM admin WHERE id = '$id'";
            $result = $this->db->delete($query);
            if($result){
                header('location:danh-sach-thanh-vien');
            }else{
                $alert= alert_danger("Lỗi thao tác !");
                return $alert;
            }
        }
        public function edit_pass($data,$code) {
            $pass = mysqli_real_escape_string($this->db->link, $data['pass']);
            $new_pass = mysqli_real_escape_string($this->db->link, $data['new_pass']);
            $check_pass = "SELECT * FROM admin WHERE code = '$code' LIMIT 1";
            $result_check_pass = $this->db->select($check_pass);
            $result = $result_check_pass -> fetch_assoc();
            $get_pass = $result['pass'];
            if($pass == $get_pass){
                if($new_pass == "") {
                    $alert = alert_danger("Không được để trống mật khẩu mới !");
                    return $alert;
                }else{
                    $pattern = '/^[A-Za-z0-9_\.!@#$%^&*()]{6,32}$/';
                    if(!preg_match($pattern,$new_pass)){
                        $alert = alert_danger("Đặt mật khẩu phức tạp hơn !!!");
                        return $alert;
                    }else{
                        $md5_new_pass = md5($new_pass);
                        $query = "UPDATE admin SET pass = '$md5_new_pass' WHERE code = '$code'";
                        $result = $this->db->update($query);
                        if($result){
                            header('location:./tb/logout.php');
                        }else{
                            $alert= alert_danger("Lỗi thao tác !!!") ;
                            return $alert;
                        }
                    }
                }
            }else{
                $alert = alert_danger("Mật khẩu cũ chưa đúng !!!");
                return $alert;
            }
        }
        public function edit_user($data,$code_md5) {
            $code = mysqli_real_escape_string($this->db->link, $data['code']);
            $name = mysqli_real_escape_string($this->db->link, $data['name']);
            $room = mysqli_real_escape_string($this->db->link, $data['room']);
            $query = "UPDATE admin SET 
                name = '$name',
                code = '$code',
                room = '$room'
                WHERE md5(code) = '$code_md5'";
            $result = $this->db->update($query);
            if($result){
                $alert= alert_success("Đổi thông tin thành công &#x2713") ;
                return $alert;
            }else{
                $alert= alert_danger("Lỗi thao tác !") ;
                return $alert;
            }
        }
        public function get_user($code_md5){
            $code_md5 = mysqli_real_escape_string($this->db->link,$code_md5);
            $query = "SELECT * FROM admin WHERE md5(code) = '$code_md5' LIMIT 1";
            $result = $this->db->select($query);
            return $result;
        }

        public function update_room($data,$files,$id) {
            $id = mysqli_real_escape_string($this->db->link, $id);
            $name = mysqli_real_escape_string($this->db->link, $data['name']);
            $code = mysqli_real_escape_string($this->db->link, $data['code']);
            if ($name == ''){
                $alert= sweet_error("Không được để trống tên !") ;
                return $alert;
            }else{
                $query = "UPDATE tbl_room SET name = '$name',code='$code' WHERE id = '$id'";
                $result = $this->db->update($query);
                if($result){
                    header('location:../tb/thongbaophong.php');
                }else{
                    $alert= alert_danger("Lỗi thao tác !") ;
                    return $alert;
                }
            }
        }

        public function get_id_room($id){
            $id = mysqli_real_escape_string($this->db->link,$id);
            $query = "SELECT * FROM tbl_room WHERE id = '$id' LIMIT 1";
            $result = $this->db->select($query);
            return $result;
        }

        public function show_user($room){
            $room = mysqli_real_escape_string($this->db->link,$room);
            if (!empty($room)){
                $query = "SELECT * FROM admin WHERE room = '$room'";
                $result = $this->db->select($query);
                return $result;
            }else{
                $query = "SELECT * FROM admin ORDER BY id ASC";
                $result = $this->db->select($query);
                return $result;
            }
        }
        
        public function update($data,$files,$code,$id) {
            $code = mysqli_real_escape_string($this->db->link, $code);
            $nameNv = mysqli_real_escape_string($this->db->link, $data['nameNv']);
            $msnv = mysqli_real_escape_string($this->db->link, $data['msnv']);
            $nameKh = mysqli_real_escape_string($this->db->link, $data['nameKh']);
            $mshd = mysqli_real_escape_string($this->db->link, $data['mshd']);
            $email = mysqli_real_escape_string($this->db->link, $data['email']);
            $phone = mysqli_real_escape_string($this->db->link, $data['phone']);
            $domain = mysqli_real_escape_string($this->db->link, $data['domain']);
            $address = mysqli_real_escape_string($this->db->link, $data['address']);
            $giaWeb = mysqli_real_escape_string($this->db->link, $data['giaWeb']);
            $giaHost = mysqli_real_escape_string($this->db->link, $data['giaHost']);
            $ngayKy = mysqli_real_escape_string($this->db->link, $data['ngayKy']);
            $ngayHet = mysqli_real_escape_string($this->db->link, $data['ngayHet']);
            $month = mysqli_real_escape_string($this->db->link, $data['month']);
            $properties = mysqli_real_escape_string($this->db->link, $data['properties']);
            $note = mysqli_real_escape_string($this->db->link, $data['note']);
            $query = "UPDATE tbl_contract SET 
                nameNv = '$nameNv',
                code = '$code',
                msnv = '$msnv',
                nameKh = '$nameKh',
                mshd = '$mshd',
                email = '$email',
                phone = '$phone',
                domain = '$domain',
                address = '$address',
                giaWeb = '$giaWeb',
                giaHost = '$giaHost',
                ngayKy = '$ngayKy',
                ngayHet = '$ngayHet',
                month = '$month',
                properties = '$properties',
                note = '$note'
                WHERE id = '$id' AND code = '$code' ";
            $result = $this->db->update($query);
            if($result){
                header('location:../tb/thongbaochinhsua.php');
            }else{
                $alert= alert_danger("Lỗi thao tác !") ;
                return $alert;
            }
        }

        public function del_adm($id) {
            $query = "DELETE FROM tbl_room WHERE id = '$id'";
            $result = $this->db->delete($query);
            if($result){
                header('location:danh-sach-phong');
            }else{
                $alert= alert_danger("Lỗi thao tác !");
                return $alert;
            }
        }

        public function add_room($name_ADM,$code_ADM){
            $name_ADM = mysqli_real_escape_string($this->db->link, $name_ADM);
            $code_ADM = mysqli_real_escape_string($this->db->link, $code_ADM);
            $query = "INSERT INTO tbl_room(name,code) VALUES('$name_ADM','$code_ADM')";
            $result = $this->db->insert($query);
            if($result){
                header('location:danh-sach-phong');
            }else{
                $alert= alert_danger("Lỗi thao tác !") ;
                return $alert;
            }
        }

        public function add_user($data){
            $code = mysqli_real_escape_string($this->db->link, $data['code']);
            $name = mysqli_real_escape_string($this->db->link, $data['name']);
            $room = mysqli_real_escape_string($this->db->link, $data['room']);
            $avatar = mysqli_real_escape_string($this->db->link, $data['avatar']);
            $pass = mysqli_real_escape_string($this->db->link, $data['pass']);
            $check_code = "SELECT * FROM admin WHERE code='$code' LIMIT 1";
            $result_check = $this->db->select($check_code);
            $check_permission = "SELECT * FROM tbl_room WHERE code='$code'";
            $result_permission = $this->db->select($check_permission);
            if($result_permission){
                $permission = '0' ;
            }else{
                $permission = '1' ;
            }
            if($result_check){
                $get_resule = $result_check -> fetch_assoc();
                $alert = "<div class='alert alert-info' role='alert'>Mã số này đã được ";
                $alert .= $get_resule['name'] ." phòng ".$get_resule['room'];
                $alert .= " đăng ký rồi, mời bạn dùng mã số khác !</div>";
                return $alert;
            }else{
                $query = "INSERT INTO admin(pass,code,name,room,permission,avatar) VALUE('$pass','$code','$name','$room','$permission','$avatar')";
                $result = $this->db->insert($query);
                if($result) {
                    $alert = sweetalert2("Thêm tài khoản thành công &#x2713");
                    echo "<meta http-equiv='refresh' content='1;url=them-nguoi'>";
                    return $alert;
                }else {
                    $alert = alert_danger("Đăng ký không thành công, vui lòng thử lại !");
                    return $alert;
                }
            }

        }

        public function update_info($data,$files,$code_info_id) {
            $name = mysqli_real_escape_string($this->db->link, $data['name']);
            $room = mysqli_real_escape_string($this->db->link, $data['room']);
            $avatar = mysqli_real_escape_string($this->db->link, $data['avatar']);
            if ($name == ''){
                $alert= sweet_error("Không được để trống tên !") ;
                return $alert;
            }else{
                $query = "UPDATE admin SET 
                    name = '$name',
                    room = '$room',
                    avatar = '$avatar'
                    WHERE id = '$code_info_id'";
                $result = $this->db->update($query);
                if($result){
                    header('location:./tb/thongbaothongtin.php');
                }else{
                    $alert= alert_danger("Lỗi thao tác !") ;
                    return $alert;
                }
            }      
        }

        public function show_room(){
            $query = "SELECT * FROM tbl_room ORDER BY id DESC";
            $result = $this->db->select($query);
            return $result;
        }

        public function get_info($code){
            $code = mysqli_real_escape_string($this->db->link,$code);
            $query = "SELECT * FROM admin WHERE code = '$code' LIMIT 1";
            $result = $this->db->select($query);
            return $result;
        }

        public function get_id_view($view){
            $view = mysqli_real_escape_string($this->db->link,$view);
            $query = "SELECT * FROM tbl_contract WHERE id = '$view' LIMIT 1";
            $result = $this->db->select($query);
            return $result;
        }

        public function get_id($id,$code){
            $id = mysqli_real_escape_string($this->db->link,$id);
            $code = mysqli_real_escape_string($this->db->link,$code);
            $check_permission = "SELECT * FROM tbl_room WHERE code='$code'";
            $result_permission = $this->db->select($check_permission);
            if($result_permission){
                $query = "SELECT * FROM tbl_contract WHERE id = '$id' LIMIT 1";
                $result = $this->db->select($query);
                return $result;
            }else{
                $query = "SELECT * FROM tbl_contract WHERE id = '$id' AND code = '$code' LIMIT 1";
                $result = $this->db->select($query);
                return $result;
            }
        }

        public function del($id,$code) {
            $query = "DELETE FROM tbl_contract WHERE id = '$id' AND code ='$code'";
            $result = $this->db->delete($query);
            if($result){
                header('location:./tb/thongbaochinhsua.php');
            }else{
                $alert= alert_danger("Lỗi thao tác !");
                return $alert;
            }
        }
        
        public function show($code,$room_filter){
            $room_filter = mysqli_real_escape_string($this->db->link,$room_filter);
            $check_permission = "SELECT * FROM tbl_room WHERE code='$code'";
            $result_permission = $this->db->select($check_permission);
            if(!empty($room_filter)){
                $choose = "SELECT * FROM admin WHERE room = '$room_filter'";
                $result_choose = $this->db->select($choose);
                $list_contract = $result_choose -> fetch_assoc();
                $get_list_contract_code = $list_contract['code'];
                $query = "SELECT * FROM tbl_contract WHERE code ='$get_list_contract_code'";
                $result = $this->db->select($query);
                return $result;
            }else{
                if($result_permission){
                    $query = "SELECT * FROM tbl_contract ORDER BY update_day DESC, id DESC";
                    $result = $this->db->select($query);
                    return $result;
                }else{
                    $query = "SELECT * FROM tbl_contract WHERE code = '$code' ORDER BY update_day DESC, id DESC";
                    $result = $this->db->select($query);
                    return $result;
                }
            }
        }
        public function insert($data,$files,$code){
            $code = mysqli_real_escape_string($this->db->link, $code);
            $nameNv = mysqli_real_escape_string($this->db->link, $data['nameNv']);
            $msnv = mysqli_real_escape_string($this->db->link, $data['msnv']);
            $nameKh = mysqli_real_escape_string($this->db->link, $data['nameKh']);
            $mshd = mysqli_real_escape_string($this->db->link, $data['mshd']);
            $email = mysqli_real_escape_string($this->db->link, $data['email']);
            $phone = mysqli_real_escape_string($this->db->link, $data['phone']);
            $domain = mysqli_real_escape_string($this->db->link, $data['domain']);
            $address = mysqli_real_escape_string($this->db->link, $data['address']);
            $giaWeb = mysqli_real_escape_string($this->db->link, $data['giaWeb']);
            $giaHost = mysqli_real_escape_string($this->db->link, $data['giaHost']);
            $ngayKy = mysqli_real_escape_string($this->db->link, $data['ngayKy']);
            $ngayHet = mysqli_real_escape_string($this->db->link, $data['ngayHet']);
            $month = mysqli_real_escape_string($this->db->link, $data['month']);
            $properties = mysqli_real_escape_string($this->db->link, $data['properties']);
            $note = mysqli_real_escape_string($this->db->link, $data['note']);
            $query = "INSERT INTO tbl_contract(code,nameNv,msnv,nameKh,mshd,email,phone,domain,address,giaWeb,giaHost,ngayKy,ngayHet,month,properties,note) VALUES('$code','$nameNv','$msnv','$nameKh','$mshd','$email','$phone','$domain','$address','$giaWeb','$giaHost','$ngayKy','$ngayHet','$month','$properties','$note')";
            $result = $this->db->insert($query);
            if($result){
                header('location:./tb/thongbaohopdong.php');
            }else{
                $alert= alert_danger("Lỗi thao tác !") ;
                return $alert;
            }
        }
    }
?>