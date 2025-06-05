<?php
$filepath = realpath(dirname(__FILE__));
include($filepath . '/../lib/session.php');
Session::checkLogin();
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class adminlogin
{
    private $db;
    private $fm;
    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }
    public function login($username, $password)
    {
        $username = $this->fm->validation($username);
        $password = $this->fm->validation($password);
        $username = mysqli_real_escape_string($this->db->link, $username);
        $password = mysqli_real_escape_string($this->db->link, $password);
        if (empty($username) || empty($password)) {
            $alert = "Không được để trống !";
            return $alert;
        } else {
            $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password' LIMIT 1";
            $result = $this->db->select($query);
            if ($result) {
                $value = $result->fetch_assoc();
                Session::set('adminlogin', true);
                header('location:index.php');
            } else {
                $alert = "Tài khoản hoặc mật khẩu không đúng !";
            }
            return $alert;
        }
    }
}
?>