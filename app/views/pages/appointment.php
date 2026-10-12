<?php
/** Appointment page: online booking (Jane), the request form (or GoHighLevel embed) and patient forms. @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [[plain($page['title']), $page['slug'] . '/']], 'actions' => false,
    'badges' => ['Book online in minutes', 'Most insurance accepted', 'Call or text anytime during office hours']]);
$phone = setting('phone');
$sms = setting('sms_phone') ?: $phone;
$loc = main_location();
$online = trim((string)setting('booking_url'));
?>
<section class="section">
  <div class="container book-options">
    <div class="book-online dark" data-reveal>
      <div class="aurora"></div><div class="grain"></div>
      <p class="eyebrow">Fastest option</p>
      <h2><?= $online ? 'Book online <em>instantly.</em>' : 'Prefer to <em>talk?</em>' ?></h2>
      <p><?= $online ? 'Pick a time that works for you in our secure online scheduler. You’ll get a confirmation right away.' : 'Call or text our team and we’ll find a time that works for you.' ?></p>
      <?php if ($online): ?><a class="btn btn--gold btn--block" href="<?= e($online) ?>" target="_blank" rel="noopener"><span>Open online booking</span><span class="btn__ic"><?= icon('calendar') ?></span></a><?php endif; ?>
      <?php if ($phone): ?><a class="btn btn--glass btn--block" href="<?= e(tel_href($phone)) ?>"><span>Call <?= e($phone) ?></span><span class="btn__ic"><?= icon('phone') ?></span></a><?php endif; ?>
      <?php if ($sms): ?><a class="btn btn--glass btn--block" href="<?= e(sms_href($sms)) ?>"><span>Text us</span><span class="btn__ic"><?= icon('message') ?></span></a><?php endif; ?>
      <?php if ($loc): ?>
      <ul class="info-list">
        <li><span class="ti"><?= icon('map-pin') ?></span><span><small>Richland office</small><a href="<?= e(Content::mapsDirections($loc)) ?>" target="_blank" rel="noopener"><?= e(Content::fullAddress($loc)) ?></a></span></li>
        <li><span class="ti"><?= icon('clock') ?></span><span><small>Office hours</small><table class="hours" data-hours-table><?php foreach (Content::hours($loc) as $h): ?><tr data-days="<?= e($h['days']) ?>"><td><?= e(days_short($h['days'])) ?></td><td><?= e(time_short($h['time'])) ?></td></tr><?php endforeach; ?></table></span></li>
      </ul>
      <?php endif; ?>
    </div>
    <div class="form-shell" data-reveal style="--d:.08s">
      <p class="eyebrow">Request an appointment</p>
      <h2>Send us a request and <em>we’ll reach out.</em></h2>
      <p class="muted">Tell us a little about what’s going on and when you’d like to come in. Our team will contact you to confirm.</p>
      <?php partial('form', ['type' => 'appointment']); ?>
    </div>
  </div>
</section>
<?php if ($groups = Content::formGroups()): ?>
<section class="section section--alt" id="patient-forms">
  <div class="container">
    <?= section_head('Save time at check-in', 'Patient *forms.*', 'Print and complete your paperwork before your first visit.') ?>
    <div class="pforms">
      <?php foreach ($groups as $g): ?>
      <div class="pform" data-reveal>
        <h3><?= icon($g['icon']) ?><?= e($g['title']) ?></h3>
        <p class="muted"><?= e($g['text']) ?></p>
        <?php foreach ($g['items'] as $f): ?><a href="<?= e($f['url']) ?>" target="_blank" rel="noopener"><?= e($f['label']) ?> <span class="muted">PDF <?= icon('download') ?></span></a><?php endforeach; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php if (has_html($page['content'])): ?><section class="section"><div class="container container--narrow prose"><?= Html::clean($page['content']) ?></div></section><?php endif; ?>
