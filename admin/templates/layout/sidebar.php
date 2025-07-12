<?php
$sidebarMenu = [
  [
    'title' => 'Bảng điều khiển',
    'icon' => 'fas fa-tachometer-alt',
    'active' => ['?page=dashboard', 'index.php'],
  ],
  [
    'title' => 'Sản phẩm',
    'icon' => 'fas fa-boxes',
    'children' => [
      [
        'title' => 'Danh mục cấp 1',
        'active' => ['?page=product_list&act=man', '?page=product_list&act=form']
      ],
      [
        'title' => 'Danh mục cấp 2',
        'active' => ['?page=product_cat&act=man', '?page=product_cat&act=form']
      ],
      [
        'title' => 'Sản phẩm',
        'active' => ['?page=product&act=man', '?page=product&act=form', '?page=gallery&act=man', '?page=gallery&act=form']
      ],
    ]
  ],
  [
    'title' => 'Danh sách bài viết',
    'icon' => 'far fa-newspaper',
    'children' => [
      [
        'title' => 'Tin tức',
        'active' => ['?page=news_man&type=tintuc', '?page=news_form&type=tintuc']
      ],
      [
        'title' => 'Chính sách',
        'active' => ['?page=news_man&type=chinhsach', '?page=news_form&type=chinhsach']
      ],
      [
        'title' => 'Tiêu chí',
        'active' => ['?page=tieuchi_list', '?page=tieuchi_form']
      ],
      [
        'title' => 'Đánh giá khách hàng',
        'active' => ['?page=danhgia_list', '?page=danhgia_form']
      ],
      [
        'title' => 'Hướng dẫn chơi',
        'active' => ['?page=news_man&type=huongdanchoi', '?page=news_form&type=huongdanchoi']
      ],
    ]
  ],
  [
    'title' => 'Quản lý trang tĩnh',
    'icon' => 'fas fa-bookmark',
    'children' => [
      [
        'title' => 'Giới thiệu',
        'active' => ['?page=static&type=gioithieu']
      ],
      [
        'title' => 'Liên hệ',
        'active' => ['?page=static&type=lienhe']
      ],
      [
        'title' => 'Mua hàng',
        'active' => ['?page=static&type=muahang']
      ],
      [
        'title' => 'Footer',
        'active' => ['?page=static&type=footer']
      ]
    ]
  ],
  [
    'title' => 'Quản lý hình ảnh - video',
    'icon' => 'fas fa-photo-video',
    'children' => [
      [
        'title' => 'Logo',
        'active' => ['?page=setting_list&type=logo']
      ],
      [
        'title' => 'Watermark',
        'active' => ['?page=watermark']
      ],
      [
        'title' => 'Favicon',
        'active' => ['?page=setting_list&type=favicon']
      ],
      [
        'title' => 'Slideshow',
        'active' => ['?page=slideshow_list', '?page=slideshow_form']
      ],
      [
        'title' => 'Social',
        'active' => ['?page=social_list', '?page=social_form']
      ],
      [
        'title' => 'Phương thức thanh toán',
        'active' => ['?page=payment_list', '?page=payment_porm']
      ]
    ]
  ],
  [
    'title' => 'Thiết lập thông tin',
    'icon' => 'fas fa-cogs',
    'active' => ['?page=setting']
  ]
];
$currentPage = $_GET['page'] ?? '';
$currentType = $_GET['type'] ?? '';
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
  <a class="brand-link" href="index.php?page=dashboard">
    <img class="brand-image" src="./assets/img/chuky.png" />
  </a>
  <div class="sidebar">
    <nav class="mt-3">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
        <?php foreach ($sidebarMenu as $menu): ?>
          <?php
          $isActiveGroup = false;
          $link = $menu['active'][0] ?? '#';

          if (!empty($menu['children'])) {
            foreach ($menu['children'] as $child) {
              if ($fn->isItemActive($child['active'], $currentPage, $currentType)) {
                $isActiveGroup = true;
                break;
              }
            }
          } else {
            $isActiveGroup = $fn->isItemActive($menu['active'], $currentPage, $currentType);
          }
          ?>
          <li class="nav-item <?= !empty($menu['children']) ? 'has-treeview' : '' ?> <?= $isActiveGroup ? 'menu-open' : '' ?>">
            <a class="nav-link <?= $isActiveGroup ? 'active' : '' ?>" href="index.php<?= $link ?>" title="<?= $menu['title'] ?>">
              <i class="nav-icon text-sm <?= $menu['icon'] ?>"></i>
              <p>
                <?= $menu['title'] ?>
                <?php if (!empty($menu['children'])): ?>
                  <i class="right fas fa-angle-left"></i>
                <?php endif; ?>
              </p>
            </a>
            <?php if (!empty($menu['children'])): ?>
              <ul class="nav nav-treeview">
                <?php foreach ($menu['children'] as $child): ?>
                  <?php
                  $childLink = $child['active'][0] ?? '#';
                  $isChildActive = $fn->isItemActive($child['active'], $currentPage, $currentType);
                  ?>
                  <li class="nav-item">
                    <a class="nav-link <?= $isChildActive ? 'active' : '' ?>" href="<?= $childLink ?>" title="<?= $child['title'] ?>">
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
