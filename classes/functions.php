<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class functions
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function phantrang_sp($tbl)
    {
        $tbl = mysqli_real_escape_string($this->db->link, $tbl);
        $query = "SELECT COUNT(*) as total FROM `$tbl`";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function phantrang($tbl, $type)
    {
        $tbl = mysqli_real_escape_string($this->db->link, $tbl);
        $type = mysqli_real_escape_string($this->db->link, $type);
        $query = "SELECT COUNT(*) as total FROM `$tbl` WHERE type = '$type'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    function renderPagination_index($current_page, $total_pages, $slug)
    {
        // Nếu chỉ có 1 trang, không cần hiển thị phân trang
        if ($total_pages <= 1) {
            return '';
        }
        $slug = mysqli_real_escape_string($this->db->link, $slug);
        $slug = mysqli_real_escape_string($this->db->link, $slug);
        if (!empty($slug)) {
            $base_url = $slug . '?page=';
        } else {
            $base_url = '?page=';
        }

        // Bắt đầu của HTML cho phân trang
        $pagination_html = '<div class="pagination-home w-100">';
        $pagination_html .= '<ul class="pagination flex-wrap justify-content-center mb-0">';

        // Hiển thị trang hiện tại / tổng số trang
        // $pagination_html .= '<li class="page-item">';
        // $pagination_html .= '<a class="page-link">Page ' . $current_page . ' / ' . $total_pages . '</a>';
        // $pagination_html .= '</li>';

        // Nút "Trước" (nếu không phải trang đầu)
        if ($current_page > 1) {
            $pagination_html .= '<li class="page-item">';
            $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page - 1) . '">Trước</a>';
            $pagination_html .= '</li>';
        }

        // Hiển thị các trang cụ thể
        for ($i = 1; $i <= $total_pages; $i++) {
            $active_class = ($i === $current_page) ? 'active' : '';
            $pagination_html .= '<li class="page-item ' . $active_class . '">';
            $pagination_html .= '<a class="page-link" href="' . $base_url . $i . '">' . $i . '</a>';
            $pagination_html .= '</li>';
        }
        // Nút "Tiếp" (nếu không phải trang cuối)
        // if ($current_page < $total_pages) {
        //     $pagination_html .= '<li class="page-item">';
        //     $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page + 1) . '">Tiếp</a>';
        //     $pagination_html .= '</li>';
        // }
        // Kết thúc HTML
        $pagination_html .= '</ul>';
        $pagination_html .= '</div>';
        // Trả về HTML hoàn chỉnh
        return $pagination_html;
    }


    function renderPagination($current_page, $total_pages, $base_url = '?page=')
    {
        // Nếu chỉ có 1 trang, không cần hiển thị phân trang
        if ($total_pages <= 1) {
            return '';
        }

        // Bắt đầu của HTML cho phân trang
        $pagination_html = '<ul class="pagination flex-wrap justify-content-center mb-0">';

        // Hiển thị trang hiện tại / tổng số trang
        $pagination_html .= '<li class="page-item">';
        $pagination_html .= '<a class="page-link">Trang ' . $current_page . ' / ' . $total_pages . '</a>';
        $pagination_html .= '</li>';

        // Các trang cụ thể
        for ($i = 1; $i <= $total_pages; $i++) {
            $active_class = ($i === $current_page) ? 'active' : '';
            $pagination_html .= '<li class="page-item ' . $active_class . '">';
            $pagination_html .= '<a class="page-link" href="' . $base_url . $i . '">' . $i . '</a>';
            $pagination_html .= '</li>';
        }

        // Nút "Trước" (nếu không phải trang đầu)
        if ($current_page > 1) {
            $pagination_html .= '<li class="page-item">';
            $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page - 1) . '">Trước</a>';
            $pagination_html .= '</li>';
        }

        // Nút "Tiếp" (nếu không phải trang cuối)
        if ($current_page < $total_pages) {
            $pagination_html .= '<li class="page-item">';
            $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page + 1) . '">Tiếp</a>';
            $pagination_html .= '</li>';
        }

        // Nút "Cuối"
        $pagination_html .= '<li class="page-item">';
        $pagination_html .= '<a class="page-link" href="' . $base_url . $total_pages . '">Cuối</a>';
        $pagination_html .= '</li>';

        // Kết thúc HTML
        $pagination_html .= '</ul>';

        // Trả về HTML hoàn chỉnh
        return $pagination_html;
    }
}
