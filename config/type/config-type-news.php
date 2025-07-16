<?php
$nametype = "tieu-chi";
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Tiêu chí";
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
$config['news'][$nametype]['seo'] = false;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] =140;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 140;
$config['news'][$nametype]['thumb'] = '100x'.round(100/($config['news'][$nametype]['width']/ $config['news'][$nametype]['height'])).'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = false;
/**/
$nametype = 'ho-tro-khach-hang';
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Hỗ trợ khách hàng";
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
$config['news'][$nametype]['slug'] = true;
$config['news'][$nametype]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['show_images'] = true;
$config['news'][$nametype]['gallery'] = array();
$config['news'][$nametype]['desc'] = false;
$config['news'][$nametype]['content'] = true;
$config['news'][$nametype]['content_cke'] = true;
$config['news'][$nametype]['seo'] = true;
$config['news'][$nametype]['schema'] = true;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] = 300;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 240;
$config['news'][$nametype]['thumb'] = '100x'.round(100/($config['news'][$nametype]['width']/ $config['news'][$nametype]['height'])).'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = false;
/**/
$nametype = "du-an";
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Dự án";
$config['news'][$nametype]['dropdown'] = true;
$config['news'][$nametype]['list'] = false;
$config['news'][$nametype]['cat'] = false;
$config['news'][$nametype]['item'] = false;
$config['news'][$nametype]['sub'] = false;
$config['news'][$nametype]['tags'] = false;
$config['news'][$nametype]['view'] = true;
$config['news'][$nametype]['copy'] = true;
$config['news'][$nametype]['copy_image'] = true;
$config['news'][$nametype]['comment'] = false;
$config['news'][$nametype]['slug'] = true;
$config['news'][$nametype]['check'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['show_images'] = true;
$config['news'][$nametype]['gallery'] = array();
$config['news'][$nametype]['desc'] = true;
$config['news'][$nametype]['content'] = true;
$config['news'][$nametype]['content_cke'] = true;
$config['news'][$nametype]['seo'] = true;
$config['news'][$nametype]['schema'] = true;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] = 300;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 335;
$config['news'][$nametype]['thumb'] = '100x'.round(100/($config['news'][$nametype]['width']/ $config['news'][$nametype]['height'])).'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = true;
/**/
$nametype = "dich-vu";
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Dịch vụ";
$config['news'][$nametype]['dropdown'] = true;
$config['news'][$nametype]['list'] = true;
$config['news'][$nametype]['cat'] = false;
$config['news'][$nametype]['item'] = false;
$config['news'][$nametype]['sub'] = false;
$config['news'][$nametype]['tags'] = false;
$config['news'][$nametype]['view'] = true;
$config['news'][$nametype]['copy'] = true;
$config['news'][$nametype]['copy_image'] = true;
$config['news'][$nametype]['comment'] = false;
$config['news'][$nametype]['slug'] = true;
$config['news'][$nametype]['check'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['show_images'] = true;
$config['news'][$nametype]['gallery'] = array();
$config['news'][$nametype]['desc'] = true;
$config['news'][$nametype]['content'] = true;
$config['news'][$nametype]['content_cke'] = true;
$config['news'][$nametype]['seo'] = true;
$config['news'][$nametype]['schema'] = true;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] = 400;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 470;
$config['news'][$nametype]['thumb'] = '100x'.round(100/($config['news'][$nametype]['width']/ $config['news'][$nametype]['height'])).'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = true;
/* Tin tức (List) */
$config['news'][$nametype]['title_main_list'] = $config['size-img'][$nametype]['list']['title'] = "Tin tức cấp 1";
$config['news'][$nametype]['images_list'] = true;
$config['news'][$nametype]['show_images_list'] = true;
$config['news'][$nametype]['slug_list'] = true;
$config['news'][$nametype]['check_list'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['news'][$nametype]['gallery_list'] = array();
$config['news'][$nametype]['desc_list'] = false;
$config['news'][$nametype]['desc_cke_list'] = true;
$config['news'][$nametype]['content_list'] = true;
$config['news'][$nametype]['content_cke_list'] = true;
$config['news'][$nametype]['seo_list'] = true;
$config['news'][$nametype]['width_list'] = $config['size-img'][$nametype]['list']['width'] = 320;
$config['news'][$nametype]['height_list'] = $config['size-img'][$nametype]['list']['height'] = 240;
$config['news'][$nametype]['thumb_list'] = '100x'.round(100/($config['news'][$nametype]['width_list']/ $config['news'][$nametype]['height_list'])).'x1';
$config['news'][$nametype]['img_type_list'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['list']['active'] = false;
/* Tin tức */
$nametype = "tin-tuc";
$config['news'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title']  = "Tin tức";
$config['news'][$nametype]['dropdown'] = true;
$config['news'][$nametype]['list'] = false;
$config['news'][$nametype]['cat'] = false;
$config['news'][$nametype]['item'] = false;
$config['news'][$nametype]['sub'] = false;
$config['news'][$nametype]['tags'] = false;
$config['news'][$nametype]['view'] = true;
$config['news'][$nametype]['copy'] = true;
$config['news'][$nametype]['copy_image'] = true;
$config['news'][$nametype]['comment'] = false;
$config['news'][$nametype]['slug'] = true;
$config['news'][$nametype]['check'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['show_images'] = true;
$config['news'][$nametype]['gallery'] = array();
$config['news'][$nametype]['desc'] = true;
$config['news'][$nametype]['content'] = true;
$config['news'][$nametype]['content_cke'] = true;
$config['news'][$nametype]['seo'] = true;
$config['news'][$nametype]['schema'] = true;
$config['news'][$nametype]['width'] = $config['size-img'][$nametype]['man']['width'] = 300;
$config['news'][$nametype]['height'] = $config['size-img'][$nametype]['man']['height'] = 250;
$config['news'][$nametype]['thumb'] = '100x'.round(100/($config['news'][$nametype]['width']/ $config['news'][$nametype]['height'])).'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = true;
/* Tin tức (List) */
$config['news'][$nametype]['title_main_list'] = $config['size-img'][$nametype]['list']['title'] = "Tin tức cấp 1";
$config['news'][$nametype]['images_list'] = true;
$config['news'][$nametype]['show_images_list'] = true;
$config['news'][$nametype]['slug_list'] = true;
$config['news'][$nametype]['check_list'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['news'][$nametype]['gallery_list'] = array(
    $nametype => array(
        "title_main_photo" => "Hình ảnh Tin tức cấp 1",
        "title_sub_photo" => "Hình ảnh",
        "check_photo" => array("hienthi" => "Hiển thị"),
        "number_photo" => 2,
        "images_photo" => true,
        "avatar_photo" => true,
        "name_photo" => true,
        "width_photo" => 320,
        "height_photo" => 240,
        "thumb_photo" => '100x100x1',
        "img_type_photo" => '.jpg|.gif|.png|.jpeg|.gif',
    ),
    "video" => array(
        "title_main_photo" => "Video Tin tức cấp 1",
        "title_sub_photo" => "Video",
        "check_photo" => array("hienthi" => "Hiển thị"),
        "number_photo" => 2,
        "video_photo" => true,
        "name_photo" => true
    )
);
$config['news'][$nametype]['desc_list'] = true;
$config['news'][$nametype]['desc_cke_list'] = true;
$config['news'][$nametype]['content_list'] = true;
$config['news'][$nametype]['content_cke_list'] = true;
$config['news'][$nametype]['seo_list'] = true;
$config['news'][$nametype]['width_list'] = $config['size-img'][$nametype]['list']['width'] = 320;
$config['news'][$nametype]['height_list'] = $config['size-img'][$nametype]['list']['height'] = 240;
$config['news'][$nametype]['thumb_list'] = '100x'.round(100/($config['news'][$nametype]['width_list']/ $config['news'][$nametype]['height_list'])).'x1';
$config['news'][$nametype]['img_type_list'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['list']['active'] = false;
/* Tin tức (Cat) */
$config['news'][$nametype]['title_main_cat'] = $config['size-img'][$nametype]['cat']['title'] = "Tin tức cấp 2";
$config['news'][$nametype]['images_cat'] = true;
$config['news'][$nametype]['show_images_cat'] = true;
$config['news'][$nametype]['slug_cat'] = true;
$config['news'][$nametype]['check_cat'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['news'][$nametype]['desc_cat'] = true;
$config['news'][$nametype]['desc_cke_cat'] = true;
$config['news'][$nametype]['content_cat'] = true;
$config['news'][$nametype]['content_cke_cat'] = true;
$config['news'][$nametype]['seo_cat'] = true;
$config['news'][$nametype]['width_cat'] = $config['size-img'][$nametype]['cat']['width'] = 320;
$config['news'][$nametype]['height_cat'] = $config['size-img'][$nametype]['cat']['height'] = 240;
$config['news'][$nametype]['thumb_cat'] = '100x'.round(100/($config['news'][$nametype]['width_cat']/ $config['news'][$nametype]['height_cat'])).'x1';
$config['news'][$nametype]['img_type_cat'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['cat']['active'] = false;
/* Tin tức (Item) */
$config['news'][$nametype]['title_main_item'] = $config['size-img'][$nametype]['item']['title'] = "Tin tức cấp 3";
$config['news'][$nametype]['images_item'] = true;
$config['news'][$nametype]['show_images_item'] = true;
$config['news'][$nametype]['slug_item'] = true;
$config['news'][$nametype]['check_item'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['news'][$nametype]['desc_item'] = true;
$config['news'][$nametype]['desc_cke_item'] = true;
$config['news'][$nametype]['content_item'] = true;
$config['news'][$nametype]['content_cke_item'] = true;
$config['news'][$nametype]['seo_item'] = true;
$config['news'][$nametype]['width_item'] =  $config['size-img'][$nametype]['item']['width'] = 320;
$config['news'][$nametype]['height_item'] = $config['size-img'][$nametype]['item']['height'] = 240;
$config['news'][$nametype]['thumb_item'] = '100x'.round(100/($config['news'][$nametype]['width_item']/ $config['news'][$nametype]['height_item'])).'x1';
$config['news'][$nametype]['img_type_item'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['item']['active'] = false;
/* Tin tức (Sub) */
$config['news'][$nametype]['title_main_sub'] = $config['size-img'][$nametype]['sub']['title'] = "Tin tức cấp 4";
$config['news'][$nametype]['images_sub'] = true;
$config['news'][$nametype]['show_images_sub'] = true;
$config['news'][$nametype]['slug_sub'] = true;
$config['news'][$nametype]['check_sub'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['news'][$nametype]['desc_sub'] = true;
$config['news'][$nametype]['desc_cke_sub'] = true;
$config['news'][$nametype]['content_sub'] = true;
$config['news'][$nametype]['content_cke_sub'] = true;
$config['news'][$nametype]['seo_sub'] = true;
$config['news'][$nametype]['width_sub'] = $config['size-img'][$nametype]['sub']['width_sub'] = 320;
$config['news'][$nametype]['height_sub'] = $config['size-img'][$nametype]['sub']['height_sub'] = 240;
$config['news'][$nametype]['thumb_sub'] = '100x'.round(100/($config['news'][$nametype]['width_sub']/ $config['news'][$nametype]['height_sub'])).'x1';
$config['news'][$nametype]['img_type_sub'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['sub']['active'] = false;
/* Chính sách */
$nametype = "chinh-sach";
$config['news'][$nametype]['title_main'] = "Chính sách";
$config['news'][$nametype]['check'] = array("hienthi" => "Hiển thị");
$config['news'][$nametype]['view'] = true;
$config['news'][$nametype]['slug'] = true;
$config['news'][$nametype]['copy'] = true;
$config['news'][$nametype]['images'] = true;
$config['news'][$nametype]['show_images'] = true;
$config['news'][$nametype]['content'] = true;
$config['news'][$nametype]['content_cke'] = true;
$config['news'][$nametype]['seo'] = true;
$config['news'][$nametype]['width'] = 300;
$config['news'][$nametype]['height'] = 240;
$config['news'][$nametype]['thumb'] = '100x'.round(100/($config['news'][$nametype]['width']/ $config['news'][$nametype]['height'])).'x1';
$config['news'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';


/* Quản lý mục (Không cấp) */
if (isset($config['news'])) {
    foreach ($config['news'] as $key => $value) {
        if (!isset($value['dropdown']) || (isset($value['dropdown']) && $value['dropdown'] == false)) {
            $config['shownews'] = 1;
            break;
        }
    }
}
