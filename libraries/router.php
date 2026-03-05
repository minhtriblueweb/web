<?php
/* Router */
$router->setBasePath($config['database']['url']);
$router->map('GET', ADMIN . '/', function () {
  global $func, $config;
  $func->redirect($config['database']['url'] . ADMIN . "/index.php");
  exit;
});
$router->map('GET', ADMIN, function () {
  global $func, $config;
  $func->redirect($config['database']['url'] . ADMIN . "/index.php");
  exit;
});
$router->map('GET|POST', '', 'index', 'home');
$router->map('GET|POST', 'index.php', 'index', 'index');
$router->map('GET|POST', 'sitemap.xml', 'sitemap', 'sitemap');
$router->map('GET|POST', '[a:com]', 'allpage', 'show');
$router->map('GET|POST', '[a:com]/[a:lang]/', 'allpagelang', 'lang');
$router->map('GET|POST', '[a:com]/[a:action]', 'account', 'account');
$router->map('GET', THUMBS . '/[i:w]x[i:h]x[i:z]/[**:src]', function ($w, $h, $z, $src) {
  global $func;
  $infoWebp = $func->webpinfo($src);
  if (isset($infoWebp['Animation']) && $infoWebp['Animation'] === true) {
    $contentWebp = file_get_contents($src);
    header('Content-Type: image/webp');
    echo $contentWebp;
  } else {
    $func->createThumb($w, $h, $z, $src, null, THUMBS);
  }
}, 'thumb');
$router->map('GET', WATERMARK . '/product/[i:w]x[i:h]x[i:z]/[**:src]', function ($w, $h, $z, $src) {
  global $func, $d;
  $wtm = $d->rawQueryOne("select status, photo, options from #_photo where type = ? and act = ? limit 0,1", array('watermark', 'photo_static'));
  $func->createThumb($w, $h, $z, $src, $wtm, "product");
}, 'watermark');

$router->map('GET', WATERMARK . '/news/[i:w]x[i:h]x[i:z]/[**:src]', function ($w, $h, $z, $src) {
  global $func, $d;
  $wtm = $d->rawQueryOne("select status, photo, options from #_photo where type = ? and act = ? limit 0,1", array('watermark-news', 'photo_static'));
  $func->createThumb($w, $h, $z, $src, $wtm, "news");
}, 'watermarkNews');

/* Router match */
$match = $router->match();
/* Router check */
if (is_array($match)) {
  if (is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
  } else {
    $com = (!empty($match['params']['com'])) ? htmlspecialchars($match['params']['com']) : htmlspecialchars($match['target']);
    $getPage = !empty($_GET['p']) ? htmlspecialchars($_GET['p']) : 1;
  }
} else {
  header('HTTP/1.0 404 Not Found', true, 404);
  include("404.php");
  exit;
}
/* Setting */
$setting = $d->rawQueryOne("select * from tbl_setting");
$optsetting = (!empty($setting['options'])) ? json_decode($setting['options'], true) : null;

/* Check lang */
$lang = $optsetting['lang_default'] ?? array_key_first($config['website']['lang']);

/* Slug lang */
$sluglang = 'slug' . $lang;

/* SEO Lang */
$seolang = $lang;

require_once LIBRARIES . "lang/web/$lang.php";

// $request_uri = trim($_GET['page'] ?? '', '/');
// $requestParts = explode('/', $request_uri);
// $current_page = 1;
// // Phân trang dạng page-x
// if (preg_match('/^page-(\d+)$/', end($requestParts), $matches)) {
//   $current_page = (int)$matches[1];
//   array_pop($requestParts);
// }
// $slug = implode('/', $requestParts);
// $_GET['page'] = $current_page;


// $source = $com = '';
$requick = array(
  /* Sản phẩm */
  array("tbl" => "product_list", "field" => "idl", "source" => "product", "com" => "san-pham", "type" => "san-pham"),
  array("tbl" => "product_cat", "field" => "idc", "source" => "product", "com" => "san-pham", "type" => "san-pham"),
  array("tbl" => "product_item", "field" => "idi", "source" => "product", "com" => "san-pham", "type" => "san-pham"),
  array("tbl" => "product_sub", "field" => "ids", "source" => "product", "com" => "san-pham", "type" => "san-pham"),
  array("tbl" => "product_brand", "field" => "idb", "source" => "product", "com" => "san-pham", "type" => "san-pham"),
  array("tbl" => "product", "field" => "id", "source" => "product", "com" => "san-pham", "type" => "san-pham", "menu" => true),

  // Static pages
  array("tbl" => "static", "field" => "id", "source" => "static", "com" => "gioi-thieu", "type" => "gioi-thieu", "menu" => true),
  // array("tbl" => "", "source" => "index", "com" => "index", "slug" => "trang-chu"),
  array("tbl" => "", "source" => "static", "com" => "mua-hang", "slug" => "mua-hang"),
  array("tbl" => "", "source" => "static", "com" => "thanh-toan", "slug" => "thanh-toan"),
  array("tbl" => "", "source" => "search", "com" => "tim-kiem", "slug" => "tim-kiem"),
  array("tbl" => "", "source" => "contact", "com" => "lien-he", "slug" => "lien-he"),

  // News routes
  array("tbl" => "news", "source" => "news", "com" => "tin-tuc", "field" => "id", "slug" => "blog", "titleMain" => "BLOG"),
  array("tbl" => "news_list", "source" => "news", "com" => "tin-tuc", "field" => "idl"),
  array("tbl" => "news", "field" => "id", "source" => "news", "com" => "chinh-sach", "com" => "chinh-sach", "menu" => false),
  // array("tbl" => "news", "source" => "news", "com" => "huong-dan-choi", "field" => "id", "slug" => "huong-dan-choi", "titleMain" => "Hướng Dẫn Chơi"),

  /* Order */
  array("tbl" => "", "source" => "order", "com" => "gio-hang", "slug" => "gio-hang", "titleMain" => "Giỏ hàng"),
);

/* Find data */
if (!empty($com) && !in_array($com, ['tim-kiem', 'account', 'sitemap'])) {
  foreach ($requick as $k => $v) {
    $urlTbl = (!empty($v['tbl'])) ? $v['tbl'] : '';
    $urlTblTag = (!empty($v['tbltag'])) ? $v['tbltag'] : '';
    $urlType = (!empty($v['type'])) ? $v['type'] : '';
    $urlField = (!empty($v['field'])) ? $v['field'] : '';
    $urlCom = (!empty($v['com'])) ? $v['com'] : '';

    if (!empty($urlTbl) && !in_array($urlTbl, ['static', 'photo'])) {
      $row = $d->rawQueryOne("select id from {$d->prefix}$urlTbl where slug$lang = ? and type = ? and find_in_set('hienthi',status) limit 0,1", array($com, $urlType));

      if (!empty($row['id'])) {
        $_GET[$urlField] = $row['id'];
        $com = $urlCom;
        break;
      }
    }
  }
}

switch ($com) {

  case 'lien-he':
    $template = "contact/contact";
    $titleMain = lienhe;
    break;

  case 'san-pham':
    $source = "product";
    $template = isset($_GET['id']) ? "product/product_detail" : "product/product";
    $seo->set('type', isset($_GET['id']) ? "article" : "object");
    $type = $com;
    $titleMain = sanpham;
    break;

  case 'gioi-thieu':
    $source = "static";
    $template = "static/static";
    $type = $com;
    $seo->set('type', 'article');
    $titleMain = gioithieu;
    break;

  case 'mua-hang':
    $source = "static";
    $template = "static/static";
    $type = $com;
    $seo->set('type', 'article');
    $titleMain = "Hướng dẫn mua hàng";
    break;

  case 'thanh-toan':
    $source = "static";
    $template = "static/static";
    $type = $com;
    $seo->set('type', 'article');
    $titleMain = "Phương thức thanh toán";
    break;

  case 'chinh-sach':
    $template = isset($_GET['id']) ? "news/news_detail" : "news/news";
    $seo->set('type', 'article');
    $titleMain = "Chính Sách";
    break;

  case 'tin-tuc':
    if (isset($_GET['id'])) {
      $template = "news/news_detail";
    } elseif (isset($_GET['idl'])) {
      $template = "news/news_list";
    } else {
      $template = "news/news";
    }
    $seo->set('type', 'article');
    $titleMain = $titleMain ?: tintuc;
    break;

  case 'tim-kiem':
    $template = "search/search";
    $titleMain = "Tìm kiếm";
    break;

  case 'gio-hang':
    $source = "order";
    $template = 'order/order';
    $titleMain = giohang;
    $seo->set('type', 'object');
    break;

  case 'sitemap':
    include_once LIBRARIES . "sitemap.php";
    exit();

  case '':
  case 'index':
    $source = "index";
    $template = "index/index";
    $seo->set('type', 'website');
    break;

  default:
    header('HTTP/1.0 404 Not Found', true, 404);
    include("404.php");
    exit();

    // default:
    //   $template = $source . "/" . $source;
    //   $func->abort_404();
    //   break;
}

/* Require datas for all page */
require_once SOURCES . "allpage.php";

/* Include sources */
if (!empty($source)) {
  include SOURCES . $source . ".php";
}

/* Include sources */
if (empty($template)) {
  header('HTTP/1.0 404 Not Found', true, 404);
  include("404.php");
  exit();
}
