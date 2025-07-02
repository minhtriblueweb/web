<div class="wrap-main wrap-home w-clear">
  <div class="wrap-content mt-3 p-2">
    <div class="row">
      <div class="col-lg-9 mb-3">
        <div class="title-list-hot p-2">
          <h2 class="text-start"><?= $baiviet['name' . $lang] ?></h2>
        </div>
        <div class="wrap-main wrap-template w-clear" style="margin: 0 auto !important;">
          <div class="wrap-toc">
            <div class="meta-toc2">
              <a class="mucluc-dropdown-list_button">Mục Lục</a>
              <div class="box-readmore">
                <ul class="toc-list" data-toc="article" data-toc-headings="h1, h2, h3"></ul>
              </div>
            </div>
          </div>
          <div class="content-main" id="toc-content">
            <?= $baiviet['content' . $lang] ?>
          </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="share othernews mb-3">
          <b>Tin liên quan:</b>
          <div class="fix__row__news">
            <div class="row">
              <?php if (!empty($relatedNews)): ?>
                <?php foreach ($relatedNews as $kg_relatedNews): ?>
                  <?php
                  $slug = BASE . $kg_relatedNews['slug' . $lang];
                  $name = $kg_relatedNews['name' . $lang];
                  $img = !empty($kg_relatedNews['file']) ? BASE_ADMIN . UPLOADS . $kg_relatedNews['file'] : NO_IMG;
                  ?>
                  <div class="col-lg-12 col-md-6 col-12">
                    <div class="news-other d-flex flex-wrap">
                      <a class="scale-img text-decoration-none pic-news-other" href="<?= $slug ?>" title="<?= $name ?>">
                        <?= $fn->getImage([
                          'file' => $kg_relatedNews['file'],
                          'class' => 'w-100',
                          'alt' => $name,
                          'title' => $name,
                          'lazy' => true
                        ]) ?>

                      </a>
                      <div class="info-news-other">
                        <a class="name-news-other text-decoration-none" href="<?= $slug ?>" title="<?= $name ?>">
                          <?= $name ?>
                        </a>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
