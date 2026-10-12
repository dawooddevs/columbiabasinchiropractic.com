<?php
/** 404 page. */
Seo::set(['title' => 'Page Not Found']);
Seo::$noindex = true;
$phone = setting('phone');
?>
<section class="phero dark phero--solo">
  <div class="aurora"></div><div class="grain"></div><div class="lines-bg"></div>
  <div class="container">
    <p class="err-num" aria-hidden="true">404</p>
    <h1>This page took a <em>wrong turn.</em></h1>
    <p class="lead">Sorry, we couldn’t find that page. It may have moved when we refreshed our website.</p>
    <div class="btn-row">
      <a class="btn btn--gold" href="<?= e(url('')) ?>"><span>Back to home</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
      <a class="btn btn--glass" href="<?= e(url('services/')) ?>"><span>Our services</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
      <?php if ($phone): ?><a class="btn btn--glass" href="<?= e(tel_href($phone)) ?>"><span>Call <?= e($phone) ?></span><span class="btn__ic"><?= icon('phone') ?></span></a><?php endif; ?>
    </div>
  </div>
</section>
