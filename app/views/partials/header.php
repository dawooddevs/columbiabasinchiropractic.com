<?php
/** Utility bar, floating glass navigation with mega menus, and the full-screen mobile menu. */
$phone = setting('phone');
$sms = setting('sms_phone') ?: $phone;
$loc = main_location();
$portal = trim((string)setting('portal_url'));
$cur = fn($p) => rtrim($path ?? '', '/') === rtrim($p, '/') ? ' aria-current="page"' : '';
$byCat = Content::servicesByCategory();
$conditions = array_values(array_filter(Content::conditions(), fn($s) => (int)$s['show_in_menu'] === 1));
$serviceCats = array_filter([
    'chiropractic' => array_filter($byCat['chiropractic'] ?? [], fn($s) => (int)$s['show_in_menu'] === 1),
    'specialized' => array_filter($byCat['specialized'] ?? [], fn($s) => (int)$s['show_in_menu'] === 1),
]);
$doctors = array_values(array_filter(Content::providers(), 'is_clinician'));
$feat = Content::service('spinal-decompression');
$brand = fn(bool $dark = false) => brand_link($dark);
?>
<div class="topbar">
  <div class="container topbar__in">
    <div class="topbar__l">
      <?php if ($loc): ?>
      <span class="topbar__item status" data-status data-hours='<?= hours_json($loc) ?>'><span class="status__dot"></span><span data-status-text><?= e(hours_short($loc)) ?></span></span>
      <a class="topbar__item topbar__item--addr" href="<?= e(Content::mapsDirections($loc)) ?>" target="_blank" rel="noopener"><?= icon('map-pin') ?><?= e($loc['address'] . ', ' . $loc['city'] . ', ' . $loc['state']) ?></a>
      <?php endif; ?>
    </div>
    <div class="topbar__r">
      <?php if ($portal): ?><a class="topbar__item topbar__item--portal" href="<?= e($portal) ?>" target="_blank" rel="noopener"><?= icon('user') ?>Patient Portal</a><?php endif; ?>
      <?php if ($sms): ?><a class="topbar__item" href="<?= e(sms_href($sms)) ?>"><?= icon('message') ?>Text us</a><?php endif; ?>
      <?php if ($phone): ?><a class="topbar__item" href="<?= e(tel_href($phone)) ?>"><?= icon('phone') ?><?= e($phone) ?></a><?php endif; ?>
    </div>
  </div>
</div>

<header class="site-header" data-header>
  <div class="container">
    <div class="site-header__in">
      <?= $brand() ?>
      <nav aria-label="Main navigation">
        <ul class="menu" data-menu>
          <li>
            <button class="menu__link" type="button" aria-expanded="false">About<?= icon('chevron-down') ?></button>
            <div class="drop">
              <a href="<?= e(url('about-us/')) ?>"<?= $cur('/about-us/') ?>><span class="drop__ic"><?= icon('building') ?></span><span>About the clinic<small>Our story, mission and team</small></span></a>
              <?php foreach ($doctors as $d): ?>
              <a href="<?= e(Content::providerUrl($d)) ?>"><span class="drop__ic"><?= icon('user') ?></span><span><?= e($d['name']) ?><small><?= e($d['title']) ?></small></span></a>
              <?php endforeach; ?>
              <a href="<?= e(url('testimonials/')) ?>"><span class="drop__ic"><?= icon('star') ?></span><span>Patient reviews<small>Real experiences from patients</small></span></a>
            </div>
          </li>
          <?php if ($serviceCats): ?>
          <li>
            <button class="menu__link" type="button" aria-expanded="false">Services<?= icon('chevron-down') ?></button>
            <div class="drop mega">
              <?php foreach ($serviceCats as $cat => $items): ?>
              <div class="mega__col">
                <h4><?= e(Content::categoryLabel($cat)) ?></h4>
                <?php foreach ($items as $s): ?>
                <a href="<?= e(Content::serviceUrl($s)) ?>"><span class="drop__ic"><?= icon(Content::serviceIcon($s)) ?></span><span><?= e($s['menu_label'] ?: $s['title']) ?><small><?= e(str_limit((string)$s['excerpt'], 64)) ?></small></span></a>
                <?php endforeach; ?>
              </div>
              <?php endforeach; ?>
              <a class="mega__feat" href="<?= e(url('services/')) ?>">
                <?= pic($feat['image'] ?? '', 'Chiropractic care at Columbia Basin Chiropractic', $feat ? ['slot' => 'services:' . $feat['id'], 'label' => $feat['title']] : []) ?>
                <strong>Explore all services</strong>
                <span>Personalized care for pain relief, mobility, and better function.</span>
                <span class="link-arrow">View services <?= icon('arrow-right') ?></span>
              </a>
            </div>
          </li>
          <?php endif; ?>
          <?php if ($conditions): ?>
          <li>
            <button class="menu__link" type="button" aria-expanded="false">Conditions<?= icon('chevron-down') ?></button>
            <div class="drop mega mega--conditions">
              <?php foreach (array_chunk($conditions, (int)ceil(count($conditions) / 3)) as $i => $col): ?>
              <div class="mega__col">
                <h4><?= ['Back & spine', 'Neck & head', 'More concerns'][$i] ?? 'Conditions' ?></h4>
                <?php foreach ($col as $s): ?>
                <a href="<?= e(Content::serviceUrl($s)) ?>"><span class="drop__ic"><?= icon(Content::serviceIcon($s)) ?></span><span><?= e($s['menu_label'] ?: $s['title']) ?></span></a>
                <?php endforeach; ?>
              </div>
              <?php endforeach; ?>
              <a class="mega__feat" href="<?= e(url('conditions/')) ?>">
                <?= pic(Content::page('conditions')['image'] ?? '', 'Find your concern', ($cp = Content::page('conditions')) ? ['slot' => 'pages:' . $cp['id'], 'label' => 'Conditions page hero'] : []) ?>
                <strong>Find your concern</strong>
                <span>Learn how care may support your specific symptoms and goals.</span>
                <span class="link-arrow">All conditions <?= icon('arrow-right') ?></span>
              </a>
            </div>
          </li>
          <?php endif; ?>
          <li>
            <button class="menu__link" type="button" aria-expanded="false">New Patients<?= icon('chevron-down') ?></button>
            <div class="drop">
              <a href="<?= e(url('new-patient/')) ?>"><span class="drop__ic"><?= icon('clipboard') ?></span><span>What to expect<small>Your first visit, step by step</small></span></a>
              <a href="<?= e(url('faqs/')) ?>"><span class="drop__ic"><?= icon('message-square') ?></span><span>FAQs<small>Insurance, cost, visits</small></span></a>
              <a href="<?= e(url('our-videos/')) ?>"><span class="drop__ic"><?= icon('play') ?></span><span>Patient videos<small>Learn about your care</small></span></a>
              <?php if ($portal): ?><a href="<?= e($portal) ?>" target="_blank" rel="noopener"><span class="drop__ic"><?= icon('user') ?></span><span>Patient portal<small>Plans and appointment history</small></span></a><?php endif; ?>
            </div>
          </li>
          <li><a class="menu__link" href="<?= e(url('contact-us/')) ?>"<?= $cur('/contact-us/') ?>>Contact</a></li>
        </ul>
      </nav>
      <div class="header__actions">
        <?php if ($phone): ?><a class="icon-btn hide-md" href="<?= e(tel_href($phone)) ?>" aria-label="Call <?= e($phone) ?>"><?= icon('phone') ?></a><?php endif; ?>
        <a class="btn btn--sm" href="<?= e(book_url()) ?>"<?= book_attrs() ?>><span>Book<span class="btn__label-long"> online</span></span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
        <button class="icon-btn burger" type="button" aria-label="Open menu" aria-controls="mnav" aria-expanded="false" data-burger><?= icon('menu') ?></button>
      </div>
    </div>
  </div>
</header>

<div class="mnav" id="mnav" role="dialog" aria-modal="true" aria-label="Menu" data-mnav hidden>
  <div class="mnav__top">
    <?= $brand(true) ?>
    <button class="icon-btn" type="button" aria-label="Close menu" data-mnav-close><?= icon('x') ?></button>
  </div>
  <ul class="mnav__list">
    <li><a href="<?= e(url('')) ?>">Home</a></li>
    <li><details><summary>About<?= icon('plus') ?></summary><div class="mnav__sub">
      <a href="<?= e(url('about-us/')) ?>">About the clinic</a>
      <?php foreach ($doctors as $d): ?><a href="<?= e(Content::providerUrl($d)) ?>"><?= e($d['name']) ?></a><?php endforeach; ?>
      <a href="<?= e(url('testimonials/')) ?>">Patient reviews</a>
    </div></details></li>
    <?php if ($serviceCats): ?>
    <li><details><summary>Services<?= icon('plus') ?></summary><div class="mnav__sub">
      <a href="<?= e(url('services/')) ?>">All services</a>
      <?php foreach ($serviceCats as $items): foreach ($items as $s): ?><a href="<?= e(Content::serviceUrl($s)) ?>"><?= e($s['menu_label'] ?: $s['title']) ?></a><?php endforeach; endforeach; ?>
    </div></details></li>
    <?php endif; ?>
    <?php if ($conditions): ?>
    <li><details><summary>Conditions<?= icon('plus') ?></summary><div class="mnav__sub">
      <a href="<?= e(url('conditions/')) ?>">All conditions</a>
      <?php foreach ($conditions as $s): ?><a href="<?= e(Content::serviceUrl($s)) ?>"><?= e($s['menu_label'] ?: $s['title']) ?></a><?php endforeach; ?>
    </div></details></li>
    <?php endif; ?>
    <li><details><summary>New Patients<?= icon('plus') ?></summary><div class="mnav__sub">
      <a href="<?= e(url('new-patient/')) ?>">What to expect</a>
      <a href="<?= e(url('faqs/')) ?>">FAQs</a>
      <a href="<?= e(url('our-videos/')) ?>">Patient videos</a>
      <?php if ($portal): ?><a href="<?= e($portal) ?>" target="_blank" rel="noopener">Patient portal</a><?php endif; ?>
    </div></details></li>
    <li><a href="<?= e(url('contact-us/')) ?>">Contact</a></li>
  </ul>
  <div class="mnav__cta">
    <a class="btn btn--gold btn--block" href="<?= e(book_url()) ?>"<?= book_attrs() ?>><span>Book an appointment</span><span class="btn__ic"><?= icon('calendar') ?></span></a>
    <?php if ($phone): ?><a class="btn btn--glass btn--block" href="<?= e(tel_href($phone)) ?>"><span>Call <?= e($phone) ?></span><span class="btn__ic"><?= icon('phone') ?></span></a><?php endif; ?>
  </div>
  <?php if ($loc): ?>
  <div class="mnav__meta">
    <span><?= e(Content::fullAddress($loc)) ?></span>
    <span><?= e(hours_short($loc)) ?></span>
  </div>
  <?php endif; ?>
</div>
