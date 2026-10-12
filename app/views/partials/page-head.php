<?php
/**
 * Inner page hero (dark, aurora) with breadcrumbs (also emitted as BreadcrumbList schema by Seo::head()).
 * @var string $title (may use *emphasis*)  @var string $intro  @var array $crumbs [[label, path], …] after Home
 * Optional: $eyebrow, $media (HTML, e.g. pic()), $badges (list), $actions (bool, default true), $note ([title, text])
 */
Seo::$breadcrumbs = array_merge([['Home', '']], array_map(fn($c) => [plain($c[0]), $c[1]], $crumbs ?? []));
$hasMedia = !empty($media);
$phone = setting('phone');
?>
<section class="phero dark<?= $hasMedia ? '' : ' phero--solo' ?>">
  <div class="aurora"></div><div class="grain"></div><div class="lines-bg"></div>
  <div class="container phero__grid">
    <div>
      <ol class="crumbs" aria-label="Breadcrumb" data-reveal>
        <?php foreach (Seo::$breadcrumbs as $i => [$label, $p]): ?>
        <li><?php if ($i < count(Seo::$breadcrumbs) - 1): ?><a href="<?= e(url($p)) ?>"><?= e($label) ?></a><?php else: ?><span aria-current="page"><?= e($label) ?></span><?php endif; ?></li>
        <?php endforeach; ?>
      </ol>
      <?php if (!empty($eyebrow)): ?><p class="eyebrow" data-reveal><?= e($eyebrow) ?></p><?php endif; ?>
      <h1 data-reveal style="--d:.05s"><?= hl($title) ?></h1>
      <?php if (!empty($intro)): ?><p class="lead" data-reveal style="--d:.1s"><?= e($intro) ?></p><?php endif; ?>
      <?php if ($actions ?? true): ?>
      <div class="btn-row" data-reveal style="--d:.15s">
        <a class="btn btn--gold" href="<?= e(book_url()) ?>"<?= book_attrs() ?>><span>Schedule an appointment</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
        <?php if ($phone): ?><a class="btn btn--glass" href="<?= e(tel_href($phone)) ?>"><span>Call <?= e($phone) ?></span><span class="btn__ic"><?= icon('phone') ?></span></a><?php endif; ?>
      </div>
      <?php endif; ?>
      <?php if (!empty($badges)): ?>
      <ul class="badges" data-reveal style="--d:.2s"><?php foreach ($badges as $b): ?><li><?= icon('check') ?><?= e($b) ?></li><?php endforeach; ?></ul>
      <?php endif; ?>
    </div>
    <?php if ($hasMedia): ?>
    <div class="phero__media" data-reveal style="--d:.1s">
      <?= $media ?>
      <?php if (!empty($note)): ?><div class="glass"><strong><?= e($note[0]) ?></strong><?= e($note[1]) ?></div><?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
