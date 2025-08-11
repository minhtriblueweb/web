<?php
/* Background */
// $type = "background";
// $config['photo']['photo_static'][$type]['title_main'] = "Background";
// $config['photo']['photo_static'][$type]['status'] = array("hienthi" => "Hiển thị");
// $config['photo']['photo_static'][$type]['images'] = true;
// $config['photo']['photo_static'][$type]['background'] = true;
// $config['photo']['photo_static'][$type]['width'] = 900;
// $config['photo']['photo_static'][$type]['height'] = 300;
// $config['photo']['photo_static'][$type]['thumb'] = '900x300x1';
// $config['photo']['photo_static'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Banner */
// $type = "banner";
// $config['photo']['photo_static'][$type]['title_main'] = "Banner";
// $config['photo']['photo_static'][$type]['status'] = array("hienthi" => "Hiển thị");
// $config['photo']['photo_static'][$type]['images'] = true;
// $config['photo']['photo_static'][$type]['width'] = 730;
// $config['photo']['photo_static'][$type]['height'] = 120;
// $config['photo']['photo_static'][$type]['thumb'] = '730x120x1';
// $config['photo']['photo_static'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Logo */
$type = "logo";
$config['photo']['photo_static'][$type]['title_main'] = "Logo";
$config['photo']['photo_static'][$type]['status'] = array("hienthi" => "Hiển thị");
$config['photo']['photo_static'][$type]['images'] = true;
$config['photo']['photo_static'][$type]['width'] = 300;
$config['photo']['photo_static'][$type]['height'] = 120;
$config['photo']['photo_static'][$type]['thumb'] = '300x120x1';
$config['photo']['photo_static'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Favicon */
$type = "favicon";
$config['photo']['photo_static'][$type]['title_main'] = "Favicon";
$config['photo']['photo_static'][$type]['status'] = array("hienthi" => "Hiển thị");
$config['photo']['photo_static'][$type]['images'] = true;
$config['photo']['photo_static'][$type]['width'] = 48;
$config['photo']['photo_static'][$type]['height'] = 48;
$config['photo']['photo_static'][$type]['thumb'] = '48x48x1';
$config['photo']['photo_static'][$type]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Watermark */
$type = "watermark";
$config['photo']['photo_static'][$type]['title_main'] = "Watermark";
$config['photo']['photo_static'][$type]['status'] = array("hienthi" => "Hiển thị");
$config['photo']['photo_static'][$type]['images'] = true;
$config['photo']['photo_static'][$type]['watermark'] = true;
$config['photo']['photo_static'][$type]['watermark-advanced'] = true;
$config['photo']['photo_static'][$type]['width'] = 50;
$config['photo']['photo_static'][$type]['height'] = 50;
$config['photo']['photo_static'][$type]['thumb'] = '50x50x1';
$config['photo']['photo_static'][$type]['img_type'] = '.png|.PNG|.Png';

/* Slideshow */
$type = "slideshow";
$config['photo']['photo_man'][$type]['title_main_photo'] = $config['size-img'][$type]['man']['title'] = "Slideshow";
$config['photo']['photo_man'][$type]['status_photo'] = array("hienthi" => "Hiển thị");
$config['photo']['photo_man'][$type]['images_photo'] = true;
$config['photo']['photo_man'][$type]['link_photo'] = true;
$config['photo']['photo_man'][$type]['name_photo'] = true;
$config['photo']['photo_man'][$type]['width_photo'] = 1366;
$config['photo']['photo_man'][$type]['height_photo'] = 600;
$config['photo']['photo_man'][$type]['convert_webp'] = true;
$config['photo']['photo_man'][$type]['thumb_photo'] = '100x' . round(100 / ($config['photo']['photo_man'][$type]['width_photo'] / $config['photo']['photo_man'][$type]['height_photo'])) . 'x1';
$config['photo']['photo_man'][$type]['img_type_photo'] = '.jpg|.gif|.png|.jpeg|.gif';
// $config['size-img'][$type]['man']['active'] = true;

/* Social */
$type = "social";
$config['photo']['photo_man'][$type]['title_main_photo'] = "Social";
$config['photo']['photo_man'][$type]['status_photo'] = array("hienthi" => "Hiển thị");
$config['photo']['photo_man'][$type]['number_photo'] = 1;
$config['photo']['photo_man'][$type]['images_photo'] = true;
$config['photo']['photo_man'][$type]['convert_webp'] = true;
$config['photo']['photo_man'][$type]['avatar_photo'] = true;
$config['photo']['photo_man'][$type]['link_photo'] = true;
$config['photo']['photo_man'][$type]['name_photo'] = true;
$config['photo']['photo_man'][$type]['desc_photo'] = true;
$config['photo']['photo_man'][$type]['width_photo'] = 50;
$config['photo']['photo_man'][$type]['height_photo'] = 50;
$config['photo']['photo_man'][$type]['thumb_photo'] = '50x50x1';
$config['photo']['photo_man'][$type]['img_type_photo'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Payment */
$type = "payment";
$config['photo']['photo_man'][$type]['title_main_photo'] = "Phương thức thanh toán";
$config['photo']['photo_man'][$type]['status_photo'] = array("hienthi" => "Hiển thị");
$config['photo']['photo_man'][$type]['number_photo'] = 1;
$config['photo']['photo_man'][$type]['images_photo'] = true;
$config['photo']['photo_man'][$type]['avatar_photo'] = true;
$config['photo']['photo_man'][$type]['convert_webp'] = true;
$config['photo']['photo_man'][$type]['link_photo'] = false;
$config['photo']['photo_man'][$type]['content_photo'] = true;
$config['photo']['photo_man'][$type]['name_photo'] = true;
$config['photo']['photo_man'][$type]['width_photo'] = 70;
$config['photo']['photo_man'][$type]['height_photo'] = 42;
$config['photo']['photo_man'][$type]['thumb_photo'] = '70x42x2';
$config['photo']['photo_man'][$type]['img_type_photo'] = '.jpg|.gif|.png|.jpeg|.gif';
/* Video */
// $type = "video";
// $config['photo']['photo_man'][$type]['title_main_photo'] = "Video";
// $config['photo']['photo_man'][$type]['status_photo'] = array("hienthi" => "Hiển thị");
// $config['photo']['photo_man'][$type]['number_photo'] = 2;
// $config['photo']['photo_man'][$type]['video_photo'] = true;
// $config['photo']['photo_man'][$type]['name_photo'] = true;
