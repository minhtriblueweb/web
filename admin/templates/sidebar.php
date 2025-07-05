<?php
// current page info
$currentQuery = $_SERVER['QUERY_STRING'] ?? '';

$sidebarMenu = [
  [
    'title' => 'Bảng điều khiển',
    'icon' => 'fas fa-tachometer-alt',
    'link' => '?page=dashboard',
  ],
  [
    'title' => 'Sản phẩm',
    'icon' => 'fas fa-boxes',
    'children' => [
      ['title' => 'Danh mục cấp 1', 'link' => '?page=product_list_man'],
      ['title' => 'Danh mục cấp 2', 'link' => '?page=product_cat_man'],
      ['title' => 'Sản phẩm', 'link' => '?page=product_man']
    ]
  ],
  [
    'title' => 'Danh sách bài viết',
    'icon' => 'far fa-newspaper',
    'children' => [
      ['title' => 'Tin tức', 'link' => '?page=news_list&type=tintuc', 'forms' => ['news_form&type=tintuc']],
      ['title' => 'Chính sách', 'link' => '?page=news_list&type=chinhsach', 'forms' => ['news_form&type=chinhsach']],
      ['title' => 'Tiêu chí', 'link' => '?page=tieuchi_list', 'forms' => ['tieuchi_form']],
      ['title' => 'Đánh giá khách hàng', 'link' => '?page=danhgia_list', 'forms' => ['danhgia_form']],
      ['title' => 'Hướng dẫn chơi', 'link' => '?page=news_list&type=huongdanchoi', 'forms' => ['news_form&type=huongdanchoi']]
    ]
  ],
  [
    'title' => 'Quản lý trang tĩnh',
    'icon' => 'fas fa-bookmark',
    'children' => [
      ['title' => 'Giới thiệu', 'link' => '?page=static_list&type=gioithieu'],
      ['title' => 'Liên hệ', 'link' => '?page=static_list&type=lienhe'],
      ['title' => 'Mua hàng', 'link' => '?page=static_list&type=muahang'],
      ['title' => 'Footer', 'link' => '?page=static_list&type=footer'],
      ['title' => 'Hỗ trợ khách hàng', 'link' => '?page=static_list&type=hotrokhachhang'],
      ['title' => 'Hỗ trợ 24/7', 'link' => '?page=static_list&type=hotro247']
    ]
  ],
  [
    'title' => 'Quản lý hình ảnh - video',
    'icon' => 'fas fa-photo-video',
    'children' => [
      ['title' => 'Logo', 'link' => '?page=setting_list&type=logo'],
      ['title' => 'Watermark', 'link' => '?page=watermark'],
      ['title' => 'Favicon', 'link' => '?page=setting_list&type=favicon'],
      ['title' => 'Slideshow', 'link' => '?page=slideshow_list', 'forms' => ['slideshow_form']],
      ['title' => 'Social', 'link' => '?page=social_list', 'forms' => ['social_form']],
      ['title' => 'Phương thức thanh toán', 'link' => '?page=payment_list', 'forms' => ['payment_form']]
    ]
  ],
  [
    'title' => 'Quản lý SEO page',
    'icon' => 'fas fa-share-alt',
    'children' => [
      ['title' => 'Trang chủ', 'link' => '?page=seopage_list&type=trangchu'],
      ['title' => 'Giới thiệu', 'link' => '?page=seopage_list&type=gioithieu'],
      ['title' => 'Sản phẩm', 'link' => '?page=seopage_list&type=sanpham'],
      ['title' => 'Tin tức', 'link' => '?page=seopage_list&type=tintuc'],
      ['title' => 'Mua hàng', 'link' => '?page=seopage_list&type=muahang'],
      ['title' => 'Hướng dẫn chơi', 'link' => '?page=seopage_list&type=huongdanchoi'],
      ['title' => 'Liên Hệ', 'link' => '?page=seopage_list&type=lienhe']
    ]
  ],
  [
    'title' => 'Thiết lập thông tin',
    'icon' => 'fas fa-cogs',
    'link' => '?page=setting_list'
  ]
];
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
  <a class="brand-link" href="?page=dashboard"><img class="brand-image" src="./assets/img/chuky.png" /></a>
  <div class="sidebar">
    <nav class="mt-3">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <?php foreach ($sidebarMenu as $menu):
          $hasChildren = !empty($menu['children']);
          $link = $menu['link'] ?? '#';
          $activeLinks = $fn->buildActiveLinks($link);
        ?>
          <li class="nav-item <?= $hasChildren ? 'has-treeview menu-group' : '' ?>">
            <a class="nav-link" href="<?= $link ?>" data-active="<?= implode(',', $activeLinks) ?>">
              <i class="nav-icon <?= $menu['icon'] ?>"></i>
              <p>
                <?= $menu['title'] ?>
                <?php if ($hasChildren): ?>
                  <i class="right fas fa-angle-left"></i>
                <?php endif; ?>
              </p>
            </a>

            <?php if ($hasChildren): ?>
              <ul class="nav nav-treeview">
                <?php foreach ($menu['children'] as $child):
                  $childLink = $child['link'] ?? '#';
                  $childActiveLinks = $fn->buildActiveLinks($childLink);
                ?>
                  <li class="nav-item">
                    <a class="nav-link" href="<?= $childLink ?>" data-active="<?= implode(',', $childActiveLinks) ?>">
                      <i class="nav-icon far fa-caret-square-right"></i>
                      <p><?= $child['title'] ?></p>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>

      </ul>
    </nav>
  </div>
</aside>

<!-- Active Highlight Script -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const currentQuery = window.location.search;

    document.querySelectorAll(".sidebar .nav-link[data-active]").forEach(function(el) {
      const activeList = el.getAttribute("data-active").split(",").map(s => s.trim());
      if (activeList.includes(currentQuery)) {
        el.classList.add("active");
      }
    });

    document.querySelectorAll(".menu-group").forEach(function(group) {
      if (group.querySelector(".nav-link.active")) {
        group.classList.add("menu-open");
        group.querySelector(".nav-link").classList.add("active");
      }
    });
  });
</script>

<div class="content-wrapper">
