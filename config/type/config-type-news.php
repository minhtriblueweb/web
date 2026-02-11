<?php
/* Tin tức */
$type = "tin-tuc";
$config['news'][$type]['title_main'] = $config['size-img'][$type]['man']['title']  = "Tin tức";
$config['news'][$type]['dropdown'] = true;
$config['news'][$type]['list'] = true;
$config['news'][$type]['cat'] = false;
$config['news'][$type]['view'] = true;
$config['news'][$type]['copy'] = true;
$config['news'][$type]['show_images'] = true;
$config['news'][$type]['copy_image'] = true;
$config['news'][$type]['slug'] = true;
$config['news'][$type]['check'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị", "Footernews" => "Footernews");
$config['news'][$type]['images'] = true;
$config['news'][$type]['desc'] = true;
$config['news'][$type]['desc_cke'] = false;
$config['news'][$type]['content'] = true;
$config['news'][$type]['content_cke'] = true;
$config['news'][$type]['seo'] = true;
$config['news'][$type]['convert_webp'] = true;
$config['news'][$type]['schema'] = true;
$config['news'][$type]['width'] = $config['size-img'][$type]['man']['width'] = 540;
$config['news'][$type]['height'] = $config['size-img'][$type]['man']['height'] = 360;
$config['news'][$type]['thumb'] = '100x' . round(100 / ($config['news'][$type]['width'] / $config['news'][$type]['height'])) . 'x1';
$config['news'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['man']['active'] = true;

/* Tin tức (List) */
$config['news'][$type]['title_main_list'] = $config['size-img'][$type]['list']['title'] = "Tin tức cấp 1";
$config['news'][$type]['images_list'] = false;
$config['news'][$type]['show_images_list'] = false;
$config['news'][$type]['slug_list'] = true;
$config['news'][$type]['check_list'] = array("hienthi" => "Hiển thị", "noibat" => "Nổi bật");
$config['news'][$type]['desc_list'] = false;
$config['news'][$type]['desc_cke_list'] = false;
$config['news'][$type]['content_list'] = false;
$config['news'][$type]['content_cke_list'] = false;
$config['news'][$type]['seo_list'] = true;
$config['news'][$type]['convert_webp_list'] = false;
$config['news'][$type]['width_list'] = $config['size-img'][$type]['list']['width'] = 50;
$config['news'][$type]['height_list'] = $config['size-img'][$type]['list']['height'] = 50;
$config['news'][$type]['thumb_list'] = '100x' . round(100 / ($config['news'][$type]['width_list'] / $config['news'][$type]['height_list'])) . 'x1';
$config['news'][$type]['img_type_list'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['list']['active'] = false;

/* Tiêu chí */
$type = "tieu-chi";
$config['news'][$type]['title_main'] = $config['size-img'][$type]['man']['title']  = "Tiêu chí";
$config['news'][$type]['dropdown'] = false;
$config['news'][$type]['list'] = false;
$config['news'][$type]['cat'] = false;
$config['news'][$type]['item'] = false;
$config['news'][$type]['sub'] = false;
$config['news'][$type]['tags'] = false;
$config['news'][$type]['view'] = false;
$config['news'][$type]['slug'] = false;
$config['news'][$type]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$type]['images'] = true;
$config['news'][$type]['desc'] = true;
$config['news'][$type]['desc_cke'] = false;
$config['news'][$type]['content'] = false;
$config['news'][$type]['content_cke'] = false;
$config['news'][$type]['seo'] = false;
$config['news'][$type]['show_images'] = true;
$config['news'][$type]['width'] = $config['size-img'][$type]['man']['width'] = 24;
$config['news'][$type]['height'] = $config['size-img'][$type]['man']['height'] = 24;
$config['news'][$type]['thumb'] = '100x' . round(100 / ($config['news'][$type]['width'] / $config['news'][$type]['height'])) . 'x1';
$config['news'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['man']['active'] = true;
$config['news'][$type]['convert_webp'] = false;

/* Chính sách */
$type = "chinh-sach";
$config['news'][$type]['title_main'] = $config['size-img'][$type]['man']['title']  = "Chính Sách";
$config['news'][$type]['dropdown'] = false;
$config['news'][$type]['list'] = false;
$config['news'][$type]['cat'] = false;
$config['news'][$type]['item'] = false;
$config['news'][$type]['sub'] = false;
$config['news'][$type]['tags'] = false;
$config['news'][$type]['view'] = true;
$config['news'][$type]['copy'] = true;
$config['news'][$type]['copy_image'] = true;
$config['news'][$type]['comment'] = false;
$config['news'][$type]['slug'] = true;
$config['news'][$type]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$type]['images'] = true;
$config['news'][$type]['show_images'] = true;
$config['news'][$type]['gallery'] = array();
$config['news'][$type]['desc'] = true;
$config['news'][$type]['content'] = false;
$config['news'][$type]['content_cke'] = true;
$config['news'][$type]['seo'] = true;
$config['news'][$type]['width'] = $config['size-img'][$type]['man']['width'] = 740;
$config['news'][$type]['height'] = $config['size-img'][$type]['man']['height'] = 480;
$config['news'][$type]['thumb'] = '100x' . round(100 / ($config['news'][$type]['width'] / $config['news'][$type]['height'])) . 'x2';
$config['news'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['man']['active'] = true;
$config['news'][$type]['convert_webp'] = true;

/* Đánh giá khách hàng */
$type = "danh-gia";
$config['news'][$type]['title_main'] = $config['size-img'][$type]['man']['title']  = "Đánh giá khách hàng";
$config['news'][$type]['dropdown'] = false;
$config['news'][$type]['list'] = false;
$config['news'][$type]['cat'] = false;
$config['news'][$type]['item'] = false;
$config['news'][$type]['sub'] = false;
$config['news'][$type]['tags'] = false;
$config['news'][$type]['view'] = false;
$config['news'][$type]['copy'] = true;
$config['news'][$type]['copy_image'] = true;
$config['news'][$type]['comment'] = false;
$config['news'][$type]['slug'] = false;
$config['news'][$type]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$type]['images'] = true;
$config['news'][$type]['show_images'] = true;
$config['news'][$type]['gallery'] = array();
$config['news'][$type]['desc'] = true;
$config['news'][$type]['content'] = true;
$config['news'][$type]['content_cke'] = false;
$config['news'][$type]['seo'] = false;
$config['news'][$type]['width'] = $config['size-img'][$type]['man']['width'] = 100;
$config['news'][$type]['height'] = $config['size-img'][$type]['man']['height'] = 100;
$config['news'][$type]['thumb'] = '100x' . round(100 / ($config['news'][$type]['width'] / $config['news'][$type]['height'])) . 'x1';
$config['news'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['man']['active'] = true;
$config['news'][$type]['convert_webp'] = true;

/* Hướng dẫn chơi */
// $type = "huong-dan-choi";
// $config['news'][$type]['title_main'] = $config['size-img'][$type]['man']['title']  = "Hướng dẫn chơi";
// $config['news'][$type]['dropdown'] = false;
// $config['news'][$type]['list'] = false;
// $config['news'][$type]['cat'] = false;
// $config['news'][$type]['item'] = false;
// $config['news'][$type]['sub'] = false;
// $config['news'][$type]['tags'] = false;
// $config['news'][$type]['view'] = true;
// $config['news'][$type]['copy'] = true;
// $config['news'][$type]['copy_image'] = true;
// $config['news'][$type]['comment'] = false;
// $config['news'][$type]['slug'] = true;
// $config['news'][$type]['check'] = array("hienthi" => "Hiển thị");
// $config['news'][$type]['images'] = true;
// $config['news'][$type]['show_images'] = true;
// $config['news'][$type]['gallery'] = array();
// $config['news'][$type]['desc'] = true;
// $config['news'][$type]['content'] = false;
// $config['news'][$type]['content_cke'] = true;
// $config['news'][$type]['seo'] = true;
// $config['news'][$type]['width'] = $config['size-img'][$type]['man']['width'] = 540;
// $config['news'][$type]['height'] = $config['size-img'][$type]['man']['height'] = 360;
// $config['news'][$type]['thumb'] = '100x' . round(100 / ($config['news'][$type]['width'] / $config['news'][$type]['height'])) . 'x1';
// $config['news'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
// $config['size-img'][$type]['man']['active'] = true;
// $config['news'][$type]['convert_webp'] = true;

/* Hình thức thanh toán */
$type = "hinh-thuc-thanh-toan";
$config['news'][$type]['title_main'] = $config['size-img'][$type]['man']['title']  = "Hình thức thanh toán";
$config['news'][$type]['copy'] = true;
$config['news'][$type]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$type]['desc'] = true;
$config['news'][$type]['desc_cke'] = true;

/* Quản lý mục (Không cấp) */
if (isset($config['news'])) {
  foreach ($config['news'] as $key => $value) {
    if (!isset($value['dropdown']) || (isset($value['dropdown']) && $value['dropdown'] == false)) {
      $config['shownews'] = 1;
      break;
    }
  }
}
