<?php
/* Sản phẩm */
$type = "san-pham";
$config['product'][$type]['title_main'] = $config['size-img'][$type]['man']['title'] = "Sản Phẩm";
$config['product'][$type]['dropdown'] = true;
$config['product'][$type]['list'] = true;
$config['product'][$type]['cat'] = true;
$config['product'][$type]['item'] = true;
$config['product'][$type]['sub'] = false;
$config['product'][$type]['brand'] = false;
$config['product'][$type]['color'] = false;
$config['product'][$type]['size'] = false;
$config['product'][$type]['tags'] = false;
$config['product'][$type]['import'] = false;
$config['product'][$type]['export'] = false;
$config['product'][$type]['view'] = true;
$config['product'][$type]['copy'] = true;
$config['product'][$type]['convert_webp'] = true;
$config['product'][$type]['copy_image'] = true;
$config['product'][$type]['comment'] = false;
$config['product'][$type]['slug'] = true;
$config['product'][$type]['check'] = array("hienthi" => "Hiển thị", "noibat" => "Nổi bật", "banchay" => "Bán chạy",);
$config['product'][$type]['images'] = true;
$config['product'][$type]['show_images'] = true;
$config['product'][$type]['gallery'] = array(
  $type => array(
    "title_main_photo" => "Hình ảnh sản phẩm",
    "title_sub_photo" => "Hình ảnh",
    "check_photo" => array("hienthi" => "Hiển thị"),
    "number_photo" => 3,
    "images_photo" => true,
    "cart_photo" => true,
    "avatar_photo" => true,
    "name_photo" => true,
    "width_photo" => 540,
    "height_photo" => 540,
    "thumb_photo" => '100x100x1',
    "img_type_photo" => '.jpg|.gif|.png|.jpeg|.gif'
  )
);
$config['product'][$type]['code'] = true;
$config['product'][$type]['regular_price'] = true;
$config['product'][$type]['sale_price'] = true;
$config['product'][$type]['discount'] = true;
$config['product'][$type]['desc'] = false;
$config['product'][$type]['desc_cke'] = true;
$config['product'][$type]['content'] = true;
$config['product'][$type]['content_cke'] = true;
$config['product'][$type]['seo'] = true;
$config['product'][$type]['schema'] = true;
$config['product'][$type]['width'] = 500;
$config['product'][$type]['height'] = 500;
$config['size-img'][$type]['man']['width'] = 300;
$config['size-img'][$type]['man']['height'] = 290;
$config['product'][$type]['thumb'] = '100x' . round(100 / ($config['product'][$type]['width'] / $config['product'][$type]['height'])) . 'x1';
$config['product'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['man']['active'] = true;

/* Sản phẩm (Size) */
/*$config['product'][$type]['check_size'] = array("hienthi" => "Hiển thị");*/

/* Sản phẩm (Color) */
/*$config['product'][$type]['check_color'] = array("hienthi" => "Hiển thị");
$config['product'][$type]['color_images'] = true;
$config['product'][$type]['color_code'] = true;
$config['product'][$type]['color_type'] = true;
$config['product'][$type]['width_color'] = 30;
$config['product'][$type]['height_color'] = 30;
$config['product'][$type]['thumb_color'] = '100x100x1';
$config['product'][$type]['img_type_color'] = '.jpg|.gif|.png|.jpeg|.gif';*/

/* Sản phẩm (List) */
$config['product'][$type]['title_main_list'] = $config['size-img'][$type]['list']['title'] = "Sản phẩm cấp 1";
$config['product'][$type]['images_list'] = true;
$config['product'][$type]['show_images_list'] = true;
$config['product'][$type]['slug_list'] = true;
$config['product'][$type]['check_list'] = array("hienthi" => "Hiển thị", "noibat" => "Nổi bật");
$config['product'][$type]['desc_list'] = true;
$config['product'][$type]['desc_cke_list'] = false;
$config['product'][$type]['content_list'] = false;
$config['product'][$type]['content_cke_list'] = true;
$config['product'][$type]['seo_list'] = true;
$config['product'][$type]['convert_webp_list'] = false;
$config['product'][$type]['width_list'] = $config['size-img'][$type]['list']['width'] = 50;
$config['product'][$type]['height_list'] = $config['size-img'][$type]['list']['height'] = 50;
$config['product'][$type]['thumb_list'] = '100x' . round(100 / ($config['product'][$type]['width_list'] / $config['product'][$type]['height_list'])) . 'x1';
$config['product'][$type]['img_type_list'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['list']['active'] = false;
/* Sản phẩm (Cat) */
$config['product'][$type]['title_main_cat'] = $config['size-img'][$type]['cat']['title'] = "Sản phẩm cấp 2";
$config['product'][$type]['images_cat'] = true;
$config['product'][$type]['show_images_cat'] = true;
$config['product'][$type]['slug_cat'] = true;
$config['product'][$type]['check_cat'] = array("hienthi" => "Hiển thị", "noibat" => "Nổi bật");
$config['product'][$type]['desc_cat'] = true;
$config['product'][$type]['desc_cke_cat'] = false;
$config['product'][$type]['content_cat'] = false;
$config['product'][$type]['content_cke_cat'] = true;
$config['product'][$type]['seo_cat'] = true;
$config['product'][$type]['convert_webp_cat'] = true;
$config['product'][$type]['width_cat'] = $config['size-img'][$type]['cat']['width'] = 100;
$config['product'][$type]['height_cat'] = $config['size-img'][$type]['cat']['height'] = 100;
$config['product'][$type]['thumb_cat'] = '100x' . round(100 / ($config['product'][$type]['width_cat'] / $config['product'][$type]['height_cat'])) . 'x1';
$config['product'][$type]['img_type_cat'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['cat']['active'] = false;
/* Sản phẩm (Item) */
$config['product'][$type]['title_main_item'] = $config['size-img'][$type]['item']['title'] = "Sản phẩm cấp 3";
$config['product'][$type]['images_item'] = true;
$config['product'][$type]['show_images_item'] = true;
$config['product'][$type]['slug_item'] = true;
$config['product'][$type]['check_item'] = array("hienthi" => "Hiển thị");
$config['product'][$type]['desc_item'] = false;
$config['product'][$type]['seo_item'] = true;
$config['product'][$type]['width_item'] = $config['size-img'][$type]['item']['width'] = 100;
$config['product'][$type]['height_item'] = $config['size-img'][$type]['item']['height'] = 100;
$config['product'][$type]['thumb_item'] = '100x' . round(100 / ($config['product'][$type]['width_item'] / $config['product'][$type]['height_item'])) . 'x1';
$config['product'][$type]['img_type_item'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['item']['active'] = false;
/* Sản phẩm (Sub) */
$config['product'][$type]['title_main_sub'] = $config['size-img'][$type]['sub']['title'] = "Sản phẩm cấp 4";
$config['product'][$type]['images_sub'] = true;
$config['product'][$type]['show_images_sub'] = true;
$config['product'][$type]['slug_sub'] = true;
$config['product'][$type]['check_sub'] = array("hienthi" => "Hiển thị", "noibat" => "Nổi bật");
$config['product'][$type]['desc_sub'] = false;
$config['product'][$type]['seo_sub'] = true;
$config['product'][$type]['width_sub'] = $config['size-img'][$type]['sub']['width'] = 300;
$config['product'][$type]['height_sub'] = $config['size-img'][$type]['sub']['height'] = 200;
$config['product'][$type]['thumb_sub'] = '100x' . round(100 / ($config['product'][$type]['width_sub'] / $config['product'][$type]['height_sub'])) . 'x1';
$config['product'][$type]['img_type_sub'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['sub']['active'] = false;
/* Sản phẩm (Hãng) */
/*$config['product'][$type]['title_main_brand'] = $config['size-img'][$type]['brand']['title'] = "Hãng sản phẩm";
$config['product'][$type]['images_brand'] = true;
$config['product'][$type]['show_images_brand'] = true;
$config['product'][$type]['slug_brand'] = true;
$config['product'][$type]['check_brand'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['product'][$type]['seo_brand'] = true;
$config['product'][$type]['width_brand'] = $config['size-img'][$type]['brand']['width'] = 150;
$config['product'][$type]['height_brand'] = $config['size-img'][$type]['brand']['height'] = 150;
$config['product'][$type]['thumb_brand'] = '100x100x1';
$config['product'][$type]['img_type_brand'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$type]['brand']['active'] = false;*/
/* Thư viện ảnh */
// $type = "album";
// $config['product'][$type]['title_main'] = $config['size-img'][$type]['man']['title'] = "Album";
// $config['product'][$type]['check'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
// $config['product'][$type]['view'] = true;
// $config['product'][$type]['copy'] = true;
// $config['product'][$type]['slug'] = true;
// $config['product'][$type]['images'] = true;
// $config['product'][$type]['show_images'] = true;
// $config['product'][$type]['gallery'] = array(
//   $type => array(
//     "title_main_photo" => "Album",
//     "title_sub_photo" => "Hình ảnh",
//     "check_photo" => array("hienthi" => "Hiển thị"),
//     "number_photo" => 2,
//     "images_photo" => true,
//     "avatar_photo" => true,
//     "name_photo" => true,
//     "width_photo" => 600,
//     "height_photo" => 520,
//     "thumb_photo" => '100x100x1',
//     "img_type_photo" => '.jpg|.gif|.png|.jpeg|.gif'
//   )
// );
// $config['product'][$type]['seo'] = true;
// $config['product'][$type]['width'] = 600;
// $config['product'][$type]['height'] = 520;
// $config['size-img'][$type]['man']['width'] = 300;
// $config['size-img'][$type]['man']['height'] = 260;
// $config['product'][$type]['thumb'] = '100x100x1';
// $config['product'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
// $config['product'][$type]['thumb'] = '100x' . round(100 / ($config['product'][$type]['width'] / $config['product'][$type]['height'])) . 'x1';
