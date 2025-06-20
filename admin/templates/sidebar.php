<?php
$sidebarMenu = [
  [
    'title' => 'Bảng điều khiển',
    'icon' => 'fas fa-tachometer-alt',
    'link' => '?page=dashboard',
    'active' => ['?page=dashboard', 'index.php'],
  ],
  [
    'title' => 'Sản phẩm',
    'icon' => 'fas fa-boxes',
    'children' => [
      [
        'title' => 'Danh mục cấp 1',
        'link' => '?page=category_lv1_list',
        'active' => ['?page=category_lv1_list', '?page=category_lv1_form']
      ],
      [
        'title' => 'Danh mục cấp 2',
        'link' => '?page=category_lv2_list',
        'active' => ['?page=category_lv2_list', '?page=category_lv2_form']
      ],
      [
        'title' => 'Sản phẩm',
        'link' => '?page=product_list',
        'active' => ['?page=product_form', '?page=product_list', '?page=gallery_list', '?page=gallery_save']
      ],
    ]
  ],
  [
    'title' => 'Danh sách bài viết',
    'icon' => 'far fa-newspaper',
    'children' => [
      [
        'title' => 'Tin tức',
        'link' => '?page=news_list&type=tintuc',
        'active' => ['?page=news_list&type=tintuc', '?page=news_form&type=tintuc']
      ],
      [
        'title' => 'Chính sách',
        'link' => '?page=news_list&type=chinhsach',
        'active' => ['?page=news_list&type=chinhsach', '?page=news_form&type=chinhsach']
      ],
      [
        'title' => 'Tiêu chí',
        'link' => '?page=tieuchi_list',
        'active' => ['?page=tieuchi_list', '?page=tieuchi_form']
      ],
      [
        'title' => 'Đánh giá khách hàng',
        'link' => '?page=danhgia_list',
        'active' => ['?page=danhgia_list', '?page=danhgia_form']
      ],
      [
        'title' => 'Hướng dẫn chơi',
        'link' => '?page=news_list&type=huongdanchoi',
        'active' => ['?page=news_list&type=huongdanchoi', '?page=news_form&type=huongdanchoi']
      ],
    ]
  ],
  [
    'title' => 'Quản lý trang tĩnh',
    'icon' => 'fas fa-bookmark',
    'children' => [
      [
        'title' => 'Giới thiệu',
        'link' => '?page=gioithieu',
        'active' => ['?page=gioithieu']
      ],
      [
        'title' => 'Liên hệ',
        'link' => '?page=lienhe',
        'active' => ['?page=lienhe']
      ],
      [
        'title' => 'Mua hàng',
        'link' => '?page=muahang',
        'active' => ['?page=muahang']
      ]
    ]
  ],
  [
    'title' => 'Quản lý hình ảnh - video',
    'icon' => 'fas fa-photo-video',
    'children' => [
      [
        'title' => 'Logo',
        'link' => '?page=logo',
        'active' => ['?page=logo']
      ],
      [
        'title' => 'Watermark',
        'link' => '?page=watermark',
        'active' => ['?page=watermark']
      ],
      [
        'title' => 'Favicon',
        'link' => '?page=favicon',
        'active' => ['?page=favicon']
      ],
      [
        'title' => 'Slideshow',
        'link' => '?page=slideshow',
        'active' => ['?page=slideshow', '?page=themslideshow', '?page=suaslideshow']
      ],
      [
        'title' => 'Social',
        'link' => '?page=social',
        'active' => ['?page=social', '?page=themsocial', '?page=suasocial']
      ],
      [
        'title' => 'Phương thức thanh toán',
        'link' => '?page=phuongthuctt',
        'active' => ['?page=phuongthuctt', '?page=themphuongthuctt', '?page=suaphuongthuctt']
      ]
    ]
  ],
  [
    'title' => 'Thiết lập thông tin',
    'icon' => 'fas fa-cogs',
    'link' => '?page=setting',
    'active' => ['?page=setting']
  ]
];
$currentPage = $_GET['page'] ?? '';
$currentType = $_GET['type'] ?? '';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
  <a class="brand-link" href="?page=dashboard">
    <img class="brand-image" src="./assets/img/chuky.png" />
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-3">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <?php foreach ($sidebarMenu as $menu): ?>
          <?php
          $isActiveGroup = false;

          if (!empty($menu['children'])) {
            foreach ($menu['children'] as $child) {
              foreach ($child['active'] as $activeItem) {
                parse_str(ltrim($activeItem, '?'), $activeParams);
                if (($activeParams['page'] ?? '') === $currentPage && ($activeParams['type'] ?? '') === $currentType) {
                  $isActiveGroup = true;
                  break 2;
                }
              }
            }
          } else {
            foreach ($menu['active'] as $activeItem) {
              parse_str(ltrim($activeItem, '?'), $activeParams);
              if (($activeParams['page'] ?? '') === $currentPage && ($activeParams['type'] ?? '') === $currentType) {
                $isActiveGroup = true;
                break;
              }
            }
          }
          ?>
          <li class="nav-item <?= !empty($menu['children']) ? 'has-treeview' : '' ?> <?= $isActiveGroup ? 'menu-open' : '' ?>">
            <a class="nav-link <?= $isActiveGroup ? 'active' : '' ?>" href="<?= $menu['link'] ?? '#' ?>" title="<?= $menu['title'] ?>">
              <i class="nav-icon text-sm <?= $menu['icon'] ?>"></i>
              <p>
                <?= $menu['title'] ?>
                <?php if (!empty($menu['children'])): ?><i class="right fas fa-angle-left"></i><?php endif; ?>
              </p>
            </a>

            <?php if (!empty($menu['children'])): ?>
              <ul class="nav nav-treeview">
                <?php foreach ($menu['children'] as $child): ?>
                  <?php
                  $isChildActive = false;
                  foreach ($child['active'] as $activeItem) {
                    parse_str(ltrim($activeItem, '?'), $activeParams);
                    if (($activeParams['page'] ?? '') === $currentPage && ($activeParams['type'] ?? '') === $currentType) {
                      $isChildActive = true;
                      break;
                    }
                  }
                  ?>
                  <li class="nav-item">
                    <a class="nav-link <?= $isChildActive ? 'active' : '' ?>" href="<?= $child['link'] ?>" title="<?= $child['title'] ?>">
                      <i class="nav-icon text-sm far fa-caret-square-right"></i>
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
<div class="content-wrapper">
