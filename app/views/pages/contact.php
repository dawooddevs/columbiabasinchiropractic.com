<?php
/** Contact page: contact cards, office map and hours, contact form (or GHL embed). @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [['Contact', $page['slug'] . '/']]]);
$phone = setting('phone');
$sms = setting('sms_phone') ?: $phone;
$email = setting('email');
$loc = main_location();
?>
<section class="section">
  <div class="container">
    <div class="contact-cards">
      <?php if ($phone): ?><a class="ccard" href="<?= e(tel_href($phone)) ?>" data-reveal><span class="ti"><?= icon('phone') ?></span><small>Call</small><b><?= e($phone) ?></b></a><?php endif; ?>
      <?php if ($sms): ?><a class="ccard" href="<?= e(sms_href($sms)) ?>" data-reveal style="--d:.05s"><span class="ti"><?= icon('message') ?></span><small>Text</small><b><?= e($sms) ?></b></a><?php endif; ?>
      <?php if ($email): ?><a class="ccard" href="mailto:<?= e($email) ?>" data-reveal style="--d:.1s"><span class="ti"><?= icon('mail') ?></span><small>Email</small><b><?= e($email) ?></b></a><?php endif; ?>
      <a class="ccard" href="<?= e(book_url()) ?>"<?= book_attrs() ?> data-reveal style="--d:.15s"><span class="ti"><?= icon('calendar') ?></span><small>Book</small><b>Schedule online</b></a>
    </div>
    <?php foreach (Content::locations() as $l): ?>
    <div class="visit" id="<?= e($l['slug']) ?>" style="margin-bottom:18px">
      <div class="visit__card dark" data-reveal>
        <div class="aurora"></div><div class="grain"></div>
        <p class="eyebrow">Visit us</p>
        <h2 style="font-size:clamp(2rem,4vw,3rem)"><?= e($l['name']) ?></h2>
        <?php if ($l['description']): ?><p><?= e($l['description']) ?></p><?php endif; ?>
        <ul class="info-list">
          <li><span class="ti"><?= icon('map-pin') ?></span><span><small>Address</small><a href="<?= e(Content::mapsDirections($l)) ?>" target="_blank" rel="noopener"><?= e(Content::fullAddress($l)) ?></a></span></li>
          <?php if ($hours = Content::hours($l)): ?><li><span class="ti"><?= icon('clock') ?></span><span><small>Office hours</small><table class="hours" data-hours-table><?php foreach ($hours as $h): ?><tr data-days="<?= e($h['days']) ?>"><td><?= e(days_short($h['days'])) ?></td><td><?= e(time_short($h['time'])) ?></td></tr><?php endforeach; ?></table></span></li><?php endif; ?>
        </ul>
        <a class="btn btn--gold" href="<?= e(Content::mapsDirections($l)) ?>" target="_blank" rel="noopener"><span>Get directions</span><span class="btn__ic"><?= icon('navigation') ?></span></a>
      </div>
      <div class="visit__map" data-reveal style="--d:.08s"><iframe src="<?= e(Content::mapEmbed($l)) ?>" title="Map to <?= e($l['name']) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<section class="section section--alt">
  <div class="container faq-split">
    <div>
      <?= section_head('Send us a message', 'How can we *help?*', 'Questions about your first visit, insurance, or which care may be right for you? We’ll get back to you shortly.') ?>
      <?php if ($l = main_location()): ?><div data-reveal><?= pic($l['image'], $l['name'], ['slot' => 'locations:' . $l['id'], 'label' => $l['name'] . ' photo', 'class' => 'ph--light ph--photo', 'pos' => '50% 50%']) ?></div><?php endif; ?>
    </div>
    <div class="form-shell" data-reveal><?php partial('form', ['type' => 'contact']); ?></div>
  </div>
</section>
<?php if (has_html($page['content'])): ?><section class="section"><div class="container container--narrow prose"><?= Html::clean($page['content']) ?></div></section><?php endif; ?>
