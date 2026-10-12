<?php
/** Sticky booking card for detail pages. Optional: $title, $text */
$phone = setting('phone');
$sms = setting('sms_phone') ?: $phone;
$loc = main_location();
?>
<div class="book-card dark">
  <div class="aurora"></div><div class="grain"></div>
  <p class="eyebrow">Appointments</p>
  <h3><?= hl($title ?? 'Schedule your *evaluation.*') ?></h3>
  <p><?= e($text ?? 'Choose an available appointment online or call our Richland office for assistance.') ?></p>
  <a class="btn btn--gold" href="<?= e(book_url()) ?>"<?= book_attrs() ?>><span>Book online</span><span class="btn__ic"><?= icon('calendar') ?></span></a>
  <?php if ($phone): ?><a class="btn btn--glass" href="<?= e(tel_href($phone)) ?>"><span><?= e($phone) ?></span><span class="btn__ic"><?= icon('phone') ?></span></a><?php endif; ?>
  <?php if ($loc): ?>
  <dl>
    <div><dt>Richland office</dt><dd><a href="<?= e(Content::mapsDirections($loc)) ?>" target="_blank" rel="noopener"><?= e($loc['address']) ?>, <?= e($loc['city'] . ', ' . $loc['state'] . ' ' . $loc['zip']) ?></a></dd></div>
    <div><dt>Office hours</dt><dd><?= e(hours_short($loc)) ?></dd></div>
    <?php if ($sms): ?><div><dt>Questions?</dt><dd>Call or <a href="<?= e(sms_href($sms)) ?>">text our team</a> for help with scheduling.</dd></div><?php endif; ?>
  </dl>
  <?php endif; ?>
</div>
