<?php
/* Tin tức */
$type = "tin-tuc";
$config['news'][$type]['title_main'] = $config['size-img'][$type]['man']['title']  = "Tin tức";
$config['news'][$type]['view'] = true;
$config['news'][$type]['copy'] = true;
$config['news'][$type]['copy_image'] = true;
$config['news'][$type]['slug'] = true;
$config['news'][$type]['check'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
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

//tiêu chí
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
$config['news'][$type]['width'] = $config['size-img'][$type]['man']['width'] = 40;
$config['news'][$type]['height'] = $config['size-img'][$type]['man']['height'] = 40;
$config['news'][$type]['thumb'] = '100x' . round(100 / ($config['news'][$type]['width'] / $config['news'][$type]['height'])) . 'x1';
$config['news'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['man']['active'] = false;
$config['news'][$type]['convert_webp'] = true;

//chính sách
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
$config['news'][$type]['slug'] = false;
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
$config['size-img'][$type]['man']['active'] = false;
$config['news'][$type]['convert_webp'] = true;

// đánh giá khách hàng
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
$config['news'][$type]['content'] = false;
$config['news'][$type]['content_cke'] = false;
$config['news'][$type]['seo'] = false;
$config['news'][$type]['width'] = $config['size-img'][$type]['man']['width'] = 100;
$config['news'][$type]['height'] = $config['size-img'][$type]['man']['height'] = 100;
$config['news'][$type]['thumb'] = '100x' . round(100 / ($config['news'][$type]['width'] / $config['news'][$type]['height'])) . 'x1';
$config['news'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['man']['active'] = false;
$config['news'][$type]['convert_webp'] = true;

// hướng dẫn chơi
$type = "huong-dan-choi";
$config['news'][$type]['title_main'] = $config['size-img'][$type]['man']['title']  = "Hướng dẫn chơi";
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
$config['news'][$type]['slug'] = false;
$config['news'][$type]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$type]['images'] = true;
$config['news'][$type]['show_images'] = true;
$config['news'][$type]['gallery'] = array();
$config['news'][$type]['desc'] = true;
$config['news'][$type]['content'] = false;
$config['news'][$type]['content_cke'] = true;
$config['news'][$type]['seo'] = true;
$config['news'][$type]['width'] = $config['size-img'][$type]['man']['width'] = 540;
$config['news'][$type]['height'] = $config['size-img'][$type]['man']['height'] = 360;
$config['news'][$type]['thumb'] = '100x' . round(100 / ($config['news'][$type]['width'] / $config['news'][$type]['height'])) . 'x1';
$config['news'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['man']['active'] = false;
$config['news'][$type]['convert_webp'] = true;

/* Quản lý mục (Không cấp) */
if (isset($config['news'])) {
  foreach ($config['news'] as $key => $value) {
    if (!isset($value['dropdown']) || (isset($value['dropdown']) && $value['dropdown'] == false)) {
      $config['shownews'] = 1;
      break;
    }
  }
}
