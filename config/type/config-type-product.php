<?php
/* Sản phẩm */
$nametype = "san-pham";
$config['product'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title'] = "Sản Phẩm";
$config['product'][$nametype]['dropdown'] = true;
$config['product'][$nametype]['list'] = true;
$config['product'][$nametype]['cat'] = true;
$config['product'][$nametype]['item'] = true;
$config['product'][$nametype]['sub'] = false;
$config['product'][$nametype]['brand'] = false;
$config['product'][$nametype]['color'] = false;
$config['product'][$nametype]['size'] = false;
$config['product'][$nametype]['tags'] = false;
$config['product'][$nametype]['import'] = false;
$config['product'][$nametype]['export'] = false;
$config['product'][$nametype]['view'] = true;
$config['product'][$nametype]['copy'] = true;
$config['product'][$nametype]['copy_image'] = true;
$config['product'][$nametype]['comment'] = false;
$config['product'][$nametype]['slug'] = true;
$config['product'][$nametype]['check'] = array("khuyenmai" => "Khuyến mãi", "banchay" => "Bán chạy", "hienthi" => "Hiển thị");
$config['product'][$nametype]['images'] = true;
$config['product'][$nametype]['show_images'] = true;
$config['product'][$nametype]['gallery'] = array(
    $nametype => array(
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
$config['product'][$nametype]['code'] = true;
$config['product'][$nametype]['regular_price'] = true;
$config['product'][$nametype]['sale_price'] = true;
$config['product'][$nametype]['discount'] = true;
$config['product'][$nametype]['desc'] = true;
$config['product'][$nametype]['desc_cke'] = false;
$config['product'][$nametype]['content'] = true;
$config['product'][$nametype]['content_cke'] = true;
$config['product'][$nametype]['seo'] = true;
$config['product'][$nametype]['schema'] = true;
$config['product'][$nametype]['width'] = 300*2;
$config['product'][$nametype]['height'] = 290*2;
$config['size-img'][$nametype]['man']['width'] =300;
$config['size-img'][$nametype]['man']['height'] = 290;
$config['product'][$nametype]['thumb'] = '100x'.round(100/($config['product'][$nametype]['width']/ $config['product'][$nametype]['height'])).'x1';
$config['product'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['man']['active'] = true;

/* Sản phẩm (Size) */
/*$config['product'][$nametype]['check_size'] = array("hienthi" => "Hiển thị");*/

/* Sản phẩm (Color) */
/*$config['product'][$nametype]['check_color'] = array("hienthi" => "Hiển thị");
$config['product'][$nametype]['color_images'] = true;
$config['product'][$nametype]['color_code'] = true;
$config['product'][$nametype]['color_type'] = true;
$config['product'][$nametype]['width_color'] = 30;
$config['product'][$nametype]['height_color'] = 30;
$config['product'][$nametype]['thumb_color'] = '100x100x1';
$config['product'][$nametype]['img_type_color'] = '.jpg|.gif|.png|.jpeg|.gif';*/

/* Sản phẩm (List) */
$config['product'][$nametype]['title_main_list'] = $config['size-img'][$nametype]['list']['title'] = "Sản phẩm cấp 1";
$config['product'][$nametype]['images_list'] = true;
$config['product'][$nametype]['show_images_list'] = true;
$config['product'][$nametype]['slug_list'] = true;
$config['product'][$nametype]['check_list'] = array("hienthi" => "Hiển thị");
$config['product'][$nametype]['desc_list'] = false;
$config['product'][$nametype]['seo_list'] = true;
$config['product'][$nametype]['width_list'] = $config['size-img'][$nametype]['list']['width'] = 150;
$config['product'][$nametype]['height_list'] = $config['size-img'][$nametype]['list']['height'] = 150;
$config['product'][$nametype]['thumb_list'] = '100x'.round(100/($config['product'][$nametype]['width_list']/ $config['product'][$nametype]['height_list'])).'x1';
$config['product'][$nametype]['img_type_list'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['list']['active'] = false;
/* Sản phẩm (Cat) */
$config['product'][$nametype]['title_main_cat'] = $config['size-img'][$nametype]['cat']['title'] = "Sản phẩm cấp 2";
$config['product'][$nametype]['images_cat'] = true;
$config['product'][$nametype]['show_images_cat'] = true;
$config['product'][$nametype]['slug_cat'] = true;
$config['product'][$nametype]['check_cat'] = array("hienthi" => "Hiển thị");
$config['product'][$nametype]['desc_cat'] = false;
$config['product'][$nametype]['seo_cat'] = true;
$config['product'][$nametype]['width_cat'] = $config['size-img'][$nametype]['cat']['width'] = 300;
$config['product'][$nametype]['height_cat'] = $config['size-img'][$nametype]['cat']['height'] = 200;
$config['product'][$nametype]['thumb_cat'] = '100x'.round(100/($config['product'][$nametype]['width_cat']/ $config['product'][$nametype]['height_cat'])).'x1';
$config['product'][$nametype]['img_type_cat'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['cat']['active'] = false;
/* Sản phẩm (Item) */
$config['product'][$nametype]['title_main_item'] = $config['size-img'][$nametype]['item']['title'] = "Sản phẩm cấp 3";
$config['product'][$nametype]['images_item'] = true;
$config['product'][$nametype]['show_images_item'] = true;
$config['product'][$nametype]['slug_item'] = true;
$config['product'][$nametype]['check_item'] = array("hienthi" => "Hiển thị");
$config['product'][$nametype]['desc_item'] = false;
$config['product'][$nametype]['seo_item'] = true;
$config['product'][$nametype]['width_item'] = $config['size-img'][$nametype]['item']['width'] = 300;
$config['product'][$nametype]['height_item'] = $config['size-img'][$nametype]['item']['height'] = 200;
$config['product'][$nametype]['thumb_item'] = '100x'.round(100/($config['product'][$nametype]['width_item']/ $config['product'][$nametype]['height_item'])).'x1';
$config['product'][$nametype]['img_type_item'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['item']['active'] = false;
/* Sản phẩm (Sub) */
$config['product'][$nametype]['title_main_sub'] = $config['size-img'][$nametype]['sub']['title'] = "Sản phẩm cấp 4";
$config['product'][$nametype]['images_sub'] = true;
$config['product'][$nametype]['show_images_sub'] = true;
$config['product'][$nametype]['slug_sub'] = true;
$config['product'][$nametype]['check_sub'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['product'][$nametype]['desc_sub'] = false;
$config['product'][$nametype]['seo_sub'] = true;
$config['product'][$nametype]['width_sub'] = $config['size-img'][$nametype]['sub']['width'] = 300;
$config['product'][$nametype]['height_sub'] = $config['size-img'][$nametype]['sub']['height'] = 200;
$config['product'][$nametype]['thumb_sub'] = '100x'.round(100/($config['product'][$nametype]['width_sub']/ $config['product'][$nametype]['height_sub'])).'x1';
$config['product'][$nametype]['img_type_sub'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['sub']['active'] = false;
/* Sản phẩm (Hãng) */
/*$config['product'][$nametype]['title_main_brand'] = $config['size-img'][$nametype]['brand']['title'] = "Hãng sản phẩm";
$config['product'][$nametype]['images_brand'] = true;
$config['product'][$nametype]['show_images_brand'] = true;
$config['product'][$nametype]['slug_brand'] = true;
$config['product'][$nametype]['check_brand'] = array("noibat" => "Nổi bật", "hienthi" => "Hiển thị");
$config['product'][$nametype]['seo_brand'] = true;
$config['product'][$nametype]['width_brand'] = $config['size-img'][$nametype]['brand']['width'] = 150;
$config['product'][$nametype]['height_brand'] = $config['size-img'][$nametype]['brand']['height'] = 150;
$config['product'][$nametype]['thumb_brand'] = '100x100x1';
$config['product'][$nametype]['img_type_brand'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['size-img'][$nametype]['brand']['active'] = false;*/
/* Thư viện ảnh */
$nametype = "album";
$config['product'][$nametype]['title_main'] = $config['size-img'][$nametype]['man']['title'] = "Album";
$config['product'][$nametype]['check'] = array("noibat" => "Nổi bật","hienthi" => "Hiển thị");
$config['product'][$nametype]['view'] = true;
$config['product'][$nametype]['copy'] = true;
$config['product'][$nametype]['slug'] = true;
$config['product'][$nametype]['images'] = true;
$config['product'][$nametype]['show_images'] = true;
$config['product'][$nametype]['gallery'] = array(
    $nametype => array(
        "title_main_photo" => "Album",
        "title_sub_photo" => "Hình ảnh",
        "check_photo" => array("hienthi" => "Hiển thị"),
        "number_photo" => 2,
        "images_photo" => true,
        "avatar_photo" => true,
        "name_photo" => true,
        "width_photo" => 600,
        "height_photo" => 520,
        "thumb_photo" => '100x100x1',
        "img_type_photo" => '.jpg|.gif|.png|.jpeg|.gif'
    )
);
$config['product'][$nametype]['seo'] = true;
$config['product'][$nametype]['width'] = 600;
$config['product'][$nametype]['height'] = 520;
$config['size-img'][$nametype]['man']['width'] = 300;
$config['size-img'][$nametype]['man']['height'] = 260;
$config['product'][$nametype]['thumb'] = '100x100x1';
$config['product'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';
$config['product'][$nametype]['thumb'] = '100x'.round(100/($config['product'][$nametype]['width']/ $config['product'][$nametype]['height'])).'x1';