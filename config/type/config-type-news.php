<?php
/* Tin tức */
$nametype = "tintuc";
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Tin tức";
$config['news'][$nametype]['view'] = true;
$config['news'][$nametype]['copy'] = true;
$config['news'][$nametype]['copy_image'] = true;
$config['news'][$nametype]['slug'] = true;
$config['news'][$nametype]['check'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['desc'] = true;
$config['news'][$nametype]['desc_cke'] = false;
$config['news'][$nametype]['content'] = true;
$config['news'][$nametype]['content_cke'] = true;
$config['news'][$nametype]['seo'] = true;
$config['news'][$nametype]['schema'] = true;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] = 540;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 360;
$config['news'][$nametype]['thumb'] = '100x' . round(100 / ($config['news'][$nametype]['width'] / $config['news'][$nametype]['height'])) . 'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = true;

//tiêu chí
$nametype = "tieuchi";
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Tiêu chí";
$config['news'][$nametype]['dropdown'] = false;
$config['news'][$nametype]['list'] = false;
$config['news'][$nametype]['cat'] = false;
$config['news'][$nametype]['item'] = false;
$config['news'][$nametype]['sub'] = false;
$config['news'][$nametype]['tags'] = false;
$config['news'][$nametype]['view'] = false;
$config['news'][$nametype]['slug'] = false;
$config['news'][$nametype]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['desc'] = true;
$config['news'][$nametype]['desc_cke'] = false;
$config['news'][$nametype]['content'] = false;
$config['news'][$nametype]['content_cke'] = false;
$config['news'][$nametype]['seo'] = false;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] = 40;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 40;
$config['news'][$nametype]['thumb'] = '100x' . round(100 / ($config['news'][$nametype]['width'] / $config['news'][$nametype]['height'])) . 'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = false;

//chính sách
$nametype = "chinhsach";
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Chính Sách";
$config['news'][$nametype]['dropdown'] = false;
$config['news'][$nametype]['list'] = false;
$config['news'][$nametype]['cat'] = false;
$config['news'][$nametype]['item'] = false;
$config['news'][$nametype]['sub'] = false;
$config['news'][$nametype]['tags'] = false;
$config['news'][$nametype]['view'] = true;
$config['news'][$nametype]['copy'] = true;
$config['news'][$nametype]['copy_image'] = true;
$config['news'][$nametype]['comment'] = false;
$config['news'][$nametype]['slug'] = false;
$config['news'][$nametype]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['show_images'] = true;
$config['news'][$nametype]['gallery'] = array();
$config['news'][$nametype]['desc'] = true;
$config['news'][$nametype]['content'] = false;
$config['news'][$nametype]['content_cke'] = true;
$config['news'][$nametype]['seo'] = true;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] = 540;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 360;
$config['news'][$nametype]['thumb'] = '100x' . round(100 / ($config['news'][$nametype]['width'] / $config['news'][$nametype]['height'])) . 'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = false;

// đánh giá khách hàng
$nametype = "danhgia";
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Đánh giá khách hàng";
$config['news'][$nametype]['dropdown'] = false;
$config['news'][$nametype]['list'] = false;
$config['news'][$nametype]['cat'] = false;
$config['news'][$nametype]['item'] = false;
$config['news'][$nametype]['sub'] = false;
$config['news'][$nametype]['tags'] = false;
$config['news'][$nametype]['view'] = false;
$config['news'][$nametype]['copy'] = true;
$config['news'][$nametype]['copy_image'] = true;
$config['news'][$nametype]['comment'] = false;
$config['news'][$nametype]['slug'] = false;
$config['news'][$nametype]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['show_images'] = true;
$config['news'][$nametype]['gallery'] = array();
$config['news'][$nametype]['desc'] = true;
$config['news'][$nametype]['content'] = false;
$config['news'][$nametype]['content_cke'] = false;
$config['news'][$nametype]['seo'] = false;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] = 100;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 100;
$config['news'][$nametype]['thumb'] = '100x' . round(100 / ($config['news'][$nametype]['width'] / $config['news'][$nametype]['height'])) . 'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = false;

// hướng dẫn chơi
$nametype = "huongdanchoi";
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Hướng dẫn chơi";
$config['news'][$nametype]['dropdown'] = false;
$config['news'][$nametype]['list'] = false;
$config['news'][$nametype]['cat'] = false;
$config['news'][$nametype]['item'] = false;
$config['news'][$nametype]['sub'] = false;
$config['news'][$nametype]['tags'] = false;
$config['news'][$nametype]['view'] = true;
$config['news'][$nametype]['copy'] = true;
$config['news'][$nametype]['copy_image'] = true;
$config['news'][$nametype]['comment'] = false;
$config['news'][$nametype]['slug'] = false;
$config['news'][$nametype]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['show_images'] = true;
$config['news'][$nametype]['gallery'] = array();
$config['news'][$nametype]['desc'] = true;
$config['news'][$nametype]['content'] = false;
$config['news'][$nametype]['content_cke'] = true;
$config['news'][$nametype]['seo'] = true;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] = 540;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 360;
$config['news'][$nametype]['thumb'] = '100x' . round(100 / ($config['news'][$nametype]['width'] / $config['news'][$nametype]['height'])) . 'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = false;

/* Quản lý mục (Không cấp) */
if (isset($config['news'])) {
  foreach ($config['news'] as $key => $value) {
    if (!isset($value['dropdown']) || (isset($value['dropdown']) && $value['dropdown'] == false)) {
      $config['shownews'] = 1;
      break;
    }
  }
}
