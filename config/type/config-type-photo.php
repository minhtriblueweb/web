<?php
/* Background */
// $nametype = "background";
// $config['photo']['photo_static'][$nametype]['title_main'] = "Background";
// $config['photo']['photo_static'][$nametype]['status'] = array("hienthi" => "Hiển thị");
// $config['photo']['photo_static'][$nametype]['images'] = true;
// $config['photo']['photo_static'][$nametype]['background'] = true;
// $config['photo']['photo_static'][$nametype]['width'] = 900;
// $config['photo']['photo_static'][$nametype]['height'] = 300;
// $config['photo']['photo_static'][$nametype]['thumb'] = '900x300x1';
// $config['photo']['photo_static'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Banner */
// $nametype = "banner";
// $config['photo']['photo_static'][$nametype]['title_main'] = "Banner";
// $config['photo']['photo_static'][$nametype]['status'] = array("hienthi" => "Hiển thị");
// $config['photo']['photo_static'][$nametype]['images'] = true;
// $config['photo']['photo_static'][$nametype]['width'] = 730;
// $config['photo']['photo_static'][$nametype]['height'] = 120;
// $config['photo']['photo_static'][$nametype]['thumb'] = '730x120x1';
// $config['photo']['photo_static'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Logo */
$nametype = "logo";
$config['photo']['photo_static'][$nametype]['title_main'] = "Logo";
$config['photo']['photo_static'][$nametype]['status'] = array("hienthi" => "Hiển thị");
$config['photo']['photo_static'][$nametype]['images'] = true;
$config['photo']['photo_static'][$nametype]['width'] = 300;
$config['photo']['photo_static'][$nametype]['height'] = 120;
$config['photo']['photo_static'][$nametype]['thumb'] = '300x120x1';
$config['photo']['photo_static'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Favicon */
$nametype = "favicon";
$config['photo']['photo_static'][$nametype]['title_main'] = "Favicon";
$config['photo']['photo_static'][$nametype]['status'] = array("hienthi" => "Hiển thị");
$config['photo']['photo_static'][$nametype]['images'] = true;
$config['photo']['photo_static'][$nametype]['width'] = 48;
$config['photo']['photo_static'][$nametype]['height'] = 48;
$config['photo']['photo_static'][$nametype]['thumb'] = '48x48x1';
$config['photo']['photo_static'][$nametype]['img_type'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Watermark */
$nametype = "watermark";
$config['photo']['photo_static'][$nametype]['title_main'] = "Watermark";
$config['photo']['photo_static'][$nametype]['status'] = array("hienthi" => "Hiển thị");
$config['photo']['photo_static'][$nametype]['images'] = true;
$config['photo']['photo_static'][$nametype]['watermark'] = true;
$config['photo']['photo_static'][$nametype]['watermark-advanced'] = true;
$config['photo']['photo_static'][$nametype]['width'] = 50;
$config['photo']['photo_static'][$nametype]['height'] = 50;
$config['photo']['photo_static'][$nametype]['thumb'] = '50x50x1';
$config['photo']['photo_static'][$nametype]['img_type'] = '.png|.PNG|.Png';

/* Slideshow */
$nametype = "slideshow";
$config['photo']['man_photo'][$nametype]['title_main_photo'] = "Slideshow";
$config['photo']['man_photo'][$nametype]['status_photo'] = array("hienthi" => "Hiển thị");
$config['photo']['man_photo'][$nametype]['images_photo'] = true;
$config['photo']['man_photo'][$nametype]['link_photo'] = true;
$config['photo']['man_photo'][$nametype]['name_photo'] = true;
$config['photo']['man_photo'][$nametype]['width_photo'] = 1366;
$config['photo']['man_photo'][$nametype]['height_photo'] = 600;
$config['photo']['man_photo'][$nametype]['thumb_photo'] = '100x' . round(100 / ($config['photo']['man_photo'][$nametype]['width_photo'] / $config['photo']['man_photo'][$nametype]['height_photo'])) . 'x1';
$config['photo']['man_photo'][$nametype]['img_type_photo'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Social */
$nametype = "social";
$config['photo']['man_photo'][$nametype]['title_main_photo'] = "Social";
$config['photo']['man_photo'][$nametype]['status_photo'] = array("hienthi" => "Hiển thị");
$config['photo']['man_photo'][$nametype]['number_photo'] = 1;
$config['photo']['man_photo'][$nametype]['images_photo'] = true;
$config['photo']['man_photo'][$nametype]['avatar_photo'] = true;
$config['photo']['man_photo'][$nametype]['link_photo'] = true;
$config['photo']['man_photo'][$nametype]['name_photo'] = true;
$config['photo']['man_photo'][$nametype]['desc_photo'] = true;
$config['photo']['man_photo'][$nametype]['width_photo'] = 50;
$config['photo']['man_photo'][$nametype]['height_photo'] = 50;
$config['photo']['man_photo'][$nametype]['thumb_photo'] = '50x50x1';
$config['photo']['man_photo'][$nametype]['img_type_photo'] = '.jpg|.gif|.png|.jpeg|.gif';

/* Payment */
$nametype = "payment";
$config['photo']['man_photo'][$nametype]['title_main_photo'] = "Phương thức thanh toán";
$config['photo']['man_photo'][$nametype]['status_photo'] = array("hienthi" => "Hiển thị");
$config['photo']['man_photo'][$nametype]['number_photo'] = 1;
$config['photo']['man_photo'][$nametype]['images_photo'] = true;
$config['photo']['man_photo'][$nametype]['avatar_photo'] = true;
$config['photo']['man_photo'][$nametype]['link_photo'] = true;
$config['photo']['man_photo'][$nametype]['name_photo'] = true;
$config['photo']['man_photo'][$nametype]['width_photo'] = 70;
$config['photo']['man_photo'][$nametype]['height_photo'] = 42;
$config['photo']['man_photo'][$nametype]['thumb_photo'] = '70x42x2';
$config['photo']['man_photo'][$nametype]['img_type_photo'] = '.jpg|.gif|.png|.jpeg|.gif';
/* Video */
$nametype = "video";
$config['photo']['man_photo'][$nametype]['title_main_photo'] = "Video";
$config['photo']['man_photo'][$nametype]['status_photo'] = array("hienthi" => "Hiển thị");
$config['photo']['man_photo'][$nametype]['number_photo'] = 2;
$config['photo']['man_photo'][$nametype]['video_photo'] = true;
$config['photo']['man_photo'][$nametype]['name_photo'] = true;
