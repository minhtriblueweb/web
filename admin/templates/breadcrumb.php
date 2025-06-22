<?php
if (!empty($breadcrumb) && is_array($breadcrumb)): ?>
  <section class="content-header text-sm">
    <div class="container-fluid">
      <div class="row">
        <ol class="breadcrumb float-sm-left">
          <?php foreach ($breadcrumb as $index => $item): ?>
            <?php if (isset($item['link'])): ?>
              <li class="breadcrumb-item"><a href="<?= $item['link']; ?>" title="<?= $item['label']; ?>"><?= $item['label']; ?></a></li>
            <?php else: ?>
              <li class="breadcrumb-item active"><?= $item['label']; ?></li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ol>
      </div>
    </div>
  </section>
<?php endif; ?>
