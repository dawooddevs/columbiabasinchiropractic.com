<?php
/** Closing call-to-action band (Settings → Homepage → Appointment CTA, image from Site Images → cta_image). */
$phone = setting('phone');
$sms = setting('sms_phone') ?: $phone;
?>
<section class="cta">
  <div class="container">
    <div class="cta__box dark" data-reveal>
      <?= site_pic('cta_image', '', ['class' => 'cta__bg']) ?><div class="cta__veil"></div>
      <div class="aurora"></div><div class="grain"></div>
      <div>
        <p class="eyebrow">Ready to get started?</p>
        <h2><?= hl($headline ?? (setting('cta_headline') ?: 'Ready to take the next step?')) ?></h2>
        <?php if ($copy = ($copy ?? setting('cta_copy'))): ?><p><?= e($copy) ?></p><?php endif; ?>
      </div>
      <div class="cta__side">
        <a class="btn btn--gold btn--block" href="<?= e(book_url()) ?>"<?= book_attrs() ?>><span>Book online</span><span class="btn__ic"><?= icon('calendar') ?></span></a>
        <?php if ($phone): ?><a class="btn btn--glass btn--block" href="<?= e(tel_href($phone)) ?>"><span>Call <?= e($phone) ?></span><span class="btn__ic"><?= icon('phone') ?></span></a><?php endif; ?>
        <?php if ($sms): ?><a class="btn btn--glass btn--block" href="<?= e(sms_href($sms)) ?>"><span>Send a text</span><span class="btn__ic"><?= icon('message') ?></span></a><?php endif; ?>
        <?php if (setting('cta_small_print')): ?><p class="cta__small"><?= e(setting('cta_small_print')) ?></p><?php endif; ?>
      </div>
    </div>
  </div>
</section>
