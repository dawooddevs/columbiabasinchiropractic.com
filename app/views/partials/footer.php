<?php
/** Footer: brand, explore/care links, visit details, social, legal. */
$phone = setting('phone');
$email = setting('email');
$loc = main_location();
$social = array_filter(['facebook' => setting('social_facebook'), 'instagram' => setting('social_instagram'), 'x-social' => setting('social_x')]);
$popular = Content::servicesBySlugs(csv_list(setting('featured_services'))) ?: Content::treatmentsOnly();
$logoLight = setting('logo_light');
?>
<footer class="site-footer dark">
  <div class="aurora"></div><div class="grain"></div>
  <div class="container">
    <div class="footer__grid">
      <div>
        <a class="brand" href="<?= e(url('')) ?>" aria-label="<?= e(setting('site_name')) ?> home">
          <?php if ($logoLight): ?><?= img($logoLight, setting('site_name'), ['class' => 'brand__img']) ?><?php else: ?><?= brand_mark('mark') ?><span class="brand__text"><span class="brand__name">Columbia Basin</span><span class="brand__sub">Chiropractic Center</span></span><?php endif; ?>
        </a>
        <?php if (setting('brand_description')): ?><p><?= e(setting('brand_description')) ?></p><?php endif; ?>
        <?php if ($phone): ?><a class="footer__big" href="<?= e(tel_href($phone)) ?>"><?= e($phone) ?></a><?php endif; ?>
        <?php if ($email): ?><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a><?php endif; ?>
        <?php if ($social): ?>
        <div class="socials"><?php foreach ($social as $ic => $href): ?><a href="<?= e($href) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst(str_replace('-social', '', $ic))) ?>"><?= icon($ic) ?></a><?php endforeach; ?></div>
        <?php endif; ?>
      </div>
      <div>
        <h3>Explore</h3>
        <ul>
          <li><a href="<?= e(url('about-us/')) ?>">About us</a></li>
          <li><a href="<?= e(url('services/')) ?>">Services</a></li>
          <li><a href="<?= e(url('conditions/')) ?>">Conditions</a></li>
          <li><a href="<?= e(url('new-patient/')) ?>">New patients</a></li>
          <li><a href="<?= e(url('testimonials/')) ?>">Reviews</a></li>
          <li><a href="<?= e(url('our-videos/')) ?>">Videos</a></li>
          <li><a href="<?= e(url('faqs/')) ?>">FAQs</a></li>
          <li><a href="<?= e(url('contact-us/')) ?>">Contact</a></li>
        </ul>
      </div>
      <div>
        <h3>Popular care</h3>
        <ul>
          <?php foreach (array_slice($popular, 0, 6) as $s): ?><li><a href="<?= e(Content::serviceUrl($s)) ?>"><?= e($s['menu_label'] ?: $s['title']) ?></a></li><?php endforeach; ?>
        </ul>
      </div>
      <?php if ($loc): ?>
      <div>
        <h3>Visit us</h3>
        <p><?= e($loc['address']) ?><br><?= e($loc['city'] . ', ' . $loc['state'] . ' ' . $loc['zip']) ?></p>
        <table class="hours" data-hours-table>
          <?php foreach (Content::hours($loc) as $h): ?><tr data-days="<?= e($h['days']) ?>"><td><?= e(days_short($h['days'])) ?></td><td><?= e(time_short($h['time'])) ?></td></tr><?php endforeach; ?>
        </table>
        <p style="margin-top:18px"><a class="link-arrow" href="<?= e(Content::mapsDirections($loc)) ?>" target="_blank" rel="noopener">Get directions <?= icon('arrow-right') ?></a></p>
      </div>
      <?php endif; ?>
    </div>
    <p class="footer__word" aria-hidden="true">Move <em>better.</em></p>
    <div class="footer__bar">
      <span>&copy; <?= date('Y') ?> <?= e(setting('site_name')) ?> · Designed &amp; developed by <a href="https://theattitudemarketing.com/" target="_blank" rel="noopener">Attitude Marketing</a></span>
      <nav aria-label="Legal">
        <a href="<?= e(url('privacy-policy/')) ?>">Privacy Policy</a>
        <a href="<?= e(url('1-on-1-sms-privacy-policy/')) ?>">SMS Privacy Policy</a>
        <a href="<?= e(url('terms-and-conditions/')) ?>">Terms</a>
        <a href="<?= e(url('medical-disclaimer/')) ?>">Medical Disclaimer</a>
        <a href="<?= e(url('sitemap/')) ?>">Sitemap</a>
      </nav>
    </div>
  </div>
</footer>
