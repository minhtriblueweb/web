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

    public function convert_webp_from_path($source_path, $original_name)
    {
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $filename_no_ext = pathinfo($original_name, PATHINFO_FILENAME);
        $destination_path = 'uploads/' . $filename_no_ext . '.webp';

        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($source_path);
                break;
            case 'png':
                $image = imagecreatefrompng($source_path);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'gif':
                $image = imagecreatefromgif($source_path);
                break;
            default:
                return false;
        }

        // ✅ Ghi file webp
        if (imagewebp($image, $destination_path, 80)) {
            imagedestroy($image); // ⚠️ Quan trọng
            return $filename_no_ext . '.webp';
        }

        imagedestroy($image);
        return false;
    }


    public function resizeImage($source_path, $destination_path, $max_width)
    {
        list($width_orig, $height_orig, $image_type) = getimagesize($source_path);
        if ($width_orig <= $max_width) return;

        $ratio = $height_orig / $width_orig;
        $new_width = $max_width;
        $new_height = $max_width * $ratio;

        switch ($image_type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($source_path);
                break;
            default:
                return; // Không hỗ trợ
        }

        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($new_image, $destination_path, 85);
        } else {
            imagepng($new_image, $destination_path, 8);
        }

        imagedestroy($image);
        imagedestroy($new_image);
    }

    public function addWatermark($source_path, $destination_path, $position, $opacity, $offset_x, $offset_y)
    {
        $result = $this->db->select("SELECT logo FROM tbl_setting LIMIT 1");
        $row = $result ? $result->fetch_assoc() : null;
        if (!$row || empty($row['logo'])) return;

        $watermark_path = 'uploads/' . $row['logo'];
        if (!file_exists($watermark_path)) return;

        list($img_width, $img_height, $img_type) = getimagesize($source_path);

        switch ($img_type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($source_path);
                break;
            default:
                return;
        }

        $watermark = imagecreatefrompng($watermark_path);
        imagesavealpha($watermark, true);

        $wm_width = imagesx($watermark);
        $wm_height = imagesy($watermark);

        // Padding
        $padding = 10;

        // Tính vị trí theo $position
        switch ($position) {
            case 1: // Top-left
                $x = $padding;
                $y = $padding;
                break;
            case 2: // Top-center
                $x = ($img_width - $wm_width) / 2;
                $y = $padding;
                break;
            case 3: // Top-right
                $x = $img_width - $wm_width - $padding;
                $y = $padding;
                break;
            case 4: // Middle-left
                $x = $padding;
                $y = ($img_height - $wm_height) / 2;
                break;
            case 5: // Center
                $x = ($img_width - $wm_width) / 2;
                $y = ($img_height - $wm_height) / 2;
                break;
            case 6: // Middle-right
                $x = $img_width - $wm_width - $padding;
                $y = ($img_height - $wm_height) / 2;
                break;
            case 7: // Bottom-left
                $x = $padding;
                $y = $img_height - $wm_height - $padding;
                break;
            case 8: // Bottom-center
                $x = ($img_width - $wm_width) / 2;
                $y = $img_height - $wm_height - $padding;
                break;
            case 9: // Bottom-right
            default:
                $x = $img_width - $wm_width - $padding;
                $y = $img_height - $wm_height - $padding;
                break;
        }

        // Áp dụng offset
        $x += intval($offset_x);
        $y += intval($offset_y);

        // Clamp opacity to 0-100
        $opacity = max(0, min(100, $opacity));

        // Convert opacity to alpha blending (0 fully opaque, 127 fully transparent)
        $alpha = 127 - intval(($opacity / 100) * 127);

        // Tạo watermark mới với alpha
        $new_watermark = imagecreatetruecolor($wm_width, $wm_height);
        imagealphablending($new_watermark, false);
        imagesavealpha($new_watermark, true);

        // Copy original watermark vào new_watermark với alpha mới
        for ($y_pos = 0; $y_pos < $wm_height; $y_pos++) {
            for ($x_pos = 0; $x_pos < $wm_width; $x_pos++) {
                $rgba = imagecolorat($watermark, $x_pos, $y_pos);
                $alpha_channel = ($rgba & 0x7F000000) >> 24;

                // Kết hợp alpha của watermark gốc + alpha người dùng set
                $final_alpha = max($alpha, $alpha_channel);

                $color = imagecolorsforindex($watermark, $rgba);
                $new_color = imagecolorallocatealpha(
                    $new_watermark,
                    $color['red'],
                    $color['green'],
                    $color['blue'],
                    $final_alpha
                );
                imagesetpixel($new_watermark, $x_pos, $y_pos, $new_color);
            }
        }

        // Dán watermark mới lên ảnh
        imagecopy($image, $new_watermark, $x, $y, 0, 0, $wm_width, $wm_height);

        // Lưu ảnh kết quả
        if ($img_type == IMAGETYPE_JPEG) {
            imagejpeg($image, $destination_path, 85);
        } else {
            imagepng($image, $destination_path, 8);
        }

        // Dọn bộ nhớ
        imagedestroy($image);
        imagedestroy($watermark);
        imagedestroy($new_watermark);
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

    function renderPagination_tc($current_page, $total_pages, $base_url)
    {
      // Nếu chỉ có 1 trang, không cần hiển thị phân trang
    if ($total_pages <= 1) {
        return '';
    }

    // Bắt đầu HTML phân trang
    $pagination_html = '<ul class="pagination flex-wrap justify-content-center mb-0">';

    // Nút "Trước" với icon
    if ($current_page > 1) {
        $pagination_html .= '<li class="page-item">';
        $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page - 1) . '"><i class="fas fa-chevron-left"></i></a>';
        $pagination_html .= '</li>';
    } else {
        $pagination_html .= '<li class="page-item disabled">';
        $pagination_html .= '<a class="page-link"><i class="fas fa-chevron-left"></i></a>';
        $pagination_html .= '</li>';
    }

    // Các trang cụ thể
    for ($i = 1; $i <= $total_pages; $i++) {
        $active_class = ($i === $current_page) ? 'active' : '';
        $pagination_html .= '<li class="page-item ' . $active_class . '">';
        $pagination_html .= '<a class="page-link" href="' . $base_url . $i . '">' . $i . '</a>';
        $pagination_html .= '</li>';
    }

    // Nút "Tiếp" với icon
    if ($current_page < $total_pages) {
        $pagination_html .= '<li class="page-item">';
        $pagination_html .= '<a class="page-link" href="' . $base_url . ($current_page + 1) . '"><i class="fas fa-chevron-right"></i></a>';
        $pagination_html .= '</li>';
    } else {
        $pagination_html .= '<li class="page-item disabled">';
        $pagination_html .= '<a class="page-link"><i class="fas fa-chevron-right"></i></a>';
        $pagination_html .= '</li>';
    }

    // Nút "Cuối" (giữ nguyên)
    $pagination_html .= '<li class="page-item">';
    $pagination_html .= '<a class="page-link" href="' . $base_url . $total_pages . '">Cuối</a>';
    $pagination_html .= '</li>';

    // Kết thúc HTML
    $pagination_html .= '</ul>';

    return $pagination_html;
    }
}
