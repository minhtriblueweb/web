<?php
$sidebarMenu = [
  [
    'title' => dashboard,
    'icon' => 'fas fa-tachometer-alt',
    'active' => [''],
  ],
  [
    'title' => sanpham,
    'icon' => 'fas fa-boxes',
    'children' => (function () use ($config) {
      $children = [];
      if (!empty($config['product']) && is_array($config['product'])) {
        foreach ($config['product'] as $type => $item) {
          foreach (['list', 'cat', 'item', 'sub', 'brand'] as $key) {
            if (!empty($item[$key])) {
              $children[] = [
                'title'  => $item["title_main_$key"],
                'active' => [
                  "?page=product&type=$type&act=man_$key",
                  "?page=product&type=$type&act=form_$key"
                ]
              ];
            }
          }
          $linkMan = "?page=product&type=$type&act=man";
          $linkForm = "?page=product&type=$type&act=form";
          $linkGalleryMan = "?page=gallery&type=$type&act=man";
          $linkGalleryForm = "?page=gallery&type=$type&act=form";
          $children[] = [
            'title'  => $item['title_main'],
            'active' => [$linkMan, $linkForm, $linkGalleryMan, $linkGalleryForm]
          ];
        }
      }
      return $children;
    })()
  ],
  [
    'title' => danhsachbaiviet,
    'icon' => 'far fa-newspaper',
    'children' => (function () use ($config) {
      $children = [];
      if (!empty($config['news']) && is_array($config['news'])) {
        foreach ($config['news'] as $type => $item) {
          if (!empty($item['title_main'])) {
            $children[] = [
              'title'  => $item['title_main'],
              'active' => ["?page=news&act=man&type=$type", "?page=news&act=form&type=$type"]
            ];
          }
        }
      }
      return $children;
    })()
  ],
  [
    'title' => 'Quản lý trang tĩnh',
    'icon' => 'fas fa-bookmark',
    'children' => (function () use ($config) {
      $children = [];
      if (!empty($config['static']) && is_array($config['static'])) {
        foreach ($config['static'] as $type => $item) {
          if (!empty($item['title_main'])) {
            $children[] = [
              'title'  => $item['title_main'],
              'active' => ["?page=static&type=$type"]
            ];
          }
        }
      }
      return $children;
    })()
  ],
  [
    'title' => quanlyhinhanhvideo,
    'icon'  => 'fas fa-photo-video',
    'children' => array_merge(
      (function () use ($config) {
        $children = [];
        foreach ($config['photo']['photo_static'] as $type => $item) {
          $children[] = [
            'title' => $item['title_main_photo'] ?? ucfirst($type),
            'active' => [
              "?page=photo&act=photo_static&type={$type}",
              "?page=photo&act=photo_form&type={$type}"
            ]
          ];
        }
        return $children;
      })(),
      (function () use ($config) {
        $children = [];
        foreach ($config['photo']['photo_man'] as $type => $item) {
          $children[] = [
            'title' => $item['title_main_photo'] ?? ucfirst($type),
            'active' => [
              "?page=photo&act=photo_man&type={$type}",
              "?page=photo&act=photo_form&type={$type}"
            ]
          ];
        }
        return $children;
      })()
    )
  ],
  [
    'title' => quanlyseopage,
    'icon'  => 'fas fa-share-alt',
    'children' => (function () use ($config) {
      $children = [];
      if (!empty($config['seopage']['page'])) {
        foreach ($config['seopage']['page'] as $type => $title) {
          $children[] = [
            'title'  => $title,
            'active' => ["?page=seopage&act=update&type=$type"]
          ];
        }
      }
      return $children;
    })()
  ],
  [
    'title' => thietlapthongtin,
    'icon' => 'fas fa-cogs',
    'active' => ['?page=setting&act=update']
  ]
];
$page = $_GET['page'] ?? '';
$type = $_GET['type'] ?? '';
$act  = $_GET['act'] ?? '';
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4 text-sm">
  <a class="brand-link" href="index.php">
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
              if ($fn->isItemActive($child['active'], $page, $type)) {
                $isActiveGroup = true;
                break;
              }
            }
          } else {
            $isActiveGroup = $fn->isItemActive($menu['active'], $page, $type);
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
                  $isChildActive = $fn->isItemActive($child['active'], $page, $type);
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
