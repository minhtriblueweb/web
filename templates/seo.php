<ul class="h-card hidden">
  <li class="h-fn fn"><?= $web_name ?></li>
  <li class="h-org org"><?= $web_name ?></li>
  <li class="h-tel tel"><?= preg_replace('/[^0-9]/', '', $hotline); ?></li>
  <li><a class="u-url ul" href="<?= BASE ?>"><?= BASE ?></a></li>
</ul>
<h1 class="hidden-seoh"><?= $seo->get('h1') ?></h1>
