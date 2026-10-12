<?php
/**
 * Homepage. Text comes from Settings → Homepage, images from Settings → Site Images (or Media
 * Library → Assign to…). Sections with no data are hidden.
 */
Seo::set([
    'title' => 'Columbia Basin Chiropractic | Pain Relief & Wellness Care',
    'description' => setting('seo_default_description'),
    'canonical' => abs_url(''),
]);
$phone = setting('phone');
$sms = setting('sms_phone') ?: $phone;
$loc = main_location();
$featured = Content::explorerServices('featured') ?: Content::treatmentsOnly();
$conditions = Content::conditions();
$decomp = Content::service('spinal-decompression');
$doctors = array_values(array_filter(Content::providers(), 'is_clinician'));
$testimonials = Content::testimonials(true);
$faqs = Content::faqs('home');
$videos = youtube_videos();
$rating = $testimonials ? array_sum(array_map(fn($t) => (int)$t['rating'], $testimonials)) / count($testimonials) : 0;
$line1 = array_filter(array_map('trim', preg_split('/(?<=\.)\s+/', (string)setting('hero_line1'))));
$stats = json_list(setting('stats'));
?>
<section class="hero dark">
  <div class="aurora"></div><div class="grain"></div><div class="lines-bg"></div>
  <div class="container">
    <div class="hero__grid">
      <div>
        <p class="hero__pill" data-reveal>
          <?php if ($loc): ?><span class="status" data-status data-hours='<?= hours_json($loc) ?>'><span class="status__dot"></span><span data-status-text>Richland, WA</span></span><?php endif; ?>
          <span class="hero__pill-text"><?= e(setting('hero_eyebrow') ?: 'Personalized chiropractic care') ?></span>
        </p>
        <h1 class="hero__title" data-reveal style="--d:.05s">
          <?php foreach ($line1 ?: [setting('site_name')] as $l): ?><span class="line"><?= hl($l) ?></span><?php endforeach; ?>
          <?php if (setting('hero_line2')): ?><span class="line"><em><?= e(setting('hero_line2')) ?></em></span><?php endif; ?>
        </h1>
        <?php if (setting('hero_copy')): ?><p class="hero__copy" data-reveal style="--d:.12s"><?= e(setting('hero_copy')) ?></p><?php endif; ?>
        <div class="btn-row" data-reveal style="--d:.18s">
          <a class="btn btn--gold" href="<?= e(book_url()) ?>"<?= book_attrs() ?>><span>Schedule an appointment</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
          <?php if ($phone): ?><a class="btn btn--glass" href="<?= e(tel_href($phone)) ?>"><span>Call <?= e($phone) ?></span><span class="btn__ic"><?= icon('phone') ?></span></a><?php endif; ?>
        </div>
        <?php if ($badges = lines(setting('hero_badges'))): ?>
        <ul class="hero__badges" data-reveal style="--d:.24s"><?php foreach ($badges as $b): ?><li><?= icon('check') ?><?= e($b) ?></li><?php endforeach; ?></ul>
        <?php endif; ?>
      </div>
      <div class="hero__media" data-reveal style="--d:.1s">
        <?= site_pic('hero_image', 'Chiropractic care at ' . setting('site_short_name'), ['class' => 'hero__arch', 'eager' => true]) ?>
        <?= site_pic('hero_image_2', 'Our Richland clinic', ['class' => 'hero__inset']) ?>
        <div class="spin-badge" aria-hidden="true">
          <svg class="ring" viewBox="0 0 132 132"><defs><path id="ring-path" d="M66 66 m-50 0 a50 50 0 1 1 100 0 a50 50 0 1 1 -100 0"/></defs><text><textPath href="#ring-path">Personalized care · Richland, WA · </textPath></text></svg>
          <?= brand_mark('mark') ?>
        </div>
        <?php if ($loc): ?>
        <div class="hero__card glass">
          <small>Visit our clinic</small>
          <strong><?= e($loc['address']) ?></strong>
          <span><?= e($loc['city'] . ', ' . $loc['state'] . ' ' . $loc['zip']) ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="hero__strip">
      <div class="hero__tile glass" data-reveal><span class="ti"><?= icon('smile') ?></span><span><b>Fast, friendly &amp; affordable</b><span>Personal attention from a team that cares.</span></span></div>
      <?php if ($loc): ?>
      <a class="hero__tile glass" href="<?= e(Content::mapsDirections($loc)) ?>" target="_blank" rel="noopener" data-reveal style="--d:.06s"><span class="ti"><?= icon('map-pin') ?></span><span><b>Richland clinic</b><span><?= e($loc['address']) ?></span></span></a>
      <div class="hero__tile glass" data-reveal style="--d:.12s"><span class="ti"><?= icon('clock') ?></span><span><b>Office hours</b><span><?= e(hours_short($loc)) ?></span></span></div>
      <?php endif; ?>
      <?php if ($phone): ?><a class="hero__tile glass" href="<?= e(tel_href($phone)) ?>" data-reveal style="--d:.18s"><span class="ti"><?= icon('phone') ?></span><span><b>Call or text</b><span><?= e($phone) ?></span></span></a><?php endif; ?>
    </div>
  </div>
</section>

<?php if ($conditions): ?>
<div class="marquee" aria-label="Conditions we care for">
  <div class="marquee__track">
    <?php for ($r = 0; $r < 2; $r++): foreach ($conditions as $c): ?><a href="<?= e(Content::serviceUrl($c)) ?>"<?= $r ? ' aria-hidden="true" tabindex="-1"' : '' ?>><?= e($c['menu_label'] ?: $c['title']) ?></a><?php endforeach; endfor; ?>
  </div>
</div>
<?php endif; ?>

<section class="section">
  <div class="container split">
    <div class="split__media" data-reveal>
      <?= site_pic('about_image', 'Patient care at ' . setting('site_short_name'), ['class' => 'ph--tall']) ?>
      <div class="float-note"><strong>Local care, centered on people.</strong>Families, athletes and lasting function in Richland and the Tri-Cities.</div>
    </div>
    <div>
      <?= section_head(setting('site_short_name'), setting('about_heading') ?: 'Care built around the way you live.', setting('about_copy') ?: '', ['num' => '01']) ?>
      <div class="pillars">
        <?php foreach ([
            ['target', 'Personalized care', 'Recommendations based on your needs—not a one-size-fits-all plan.'],
            ['users', 'Experienced team', 'Multiple chiropractors serving patients and families in Richland.'],
            ['heart-pulse', 'Whole-person focus', 'Care designed to support comfort, movement, and function.'],
            ['calendar-check', 'Convenient scheduling', 'Book online or contact our office for help.'],
        ] as $i => [$ic, $t, $d]): ?>
        <div class="pillar" data-reveal style="--d:<?= $i * .06 ?>s"><span class="ti"><?= icon($ic) ?></span><h3><?= e($t) ?></h3><p><?= e($d) ?></p></div>
        <?php endforeach; ?>
      </div>
      <a class="btn btn--ghost" href="<?= e(url('about-us/')) ?>"><span>Meet our clinic</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
    </div>
  </div>
</section>

<?php if ($featured): ?>
<section class="section section--alt">
  <div class="container">
    <div class="shead shead--row">
      <?= section_head('Our services', 'Focused care for pain, recovery, and *better function.*', 'Explore care options designed to help you recover from injuries, manage discomfort, and stay active.', ['num' => '02', 'class' => 'mt-0']) ?>
      <a class="btn btn--ink" href="<?= e(url('services/')) ?>" data-reveal><span>All services</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
    </div>
    <div class="bento"><?php foreach ($featured as $i => $s) partial('service-card', ['s' => $s, 'n' => $i + 1]); ?></div>
  </div>
</section>
<?php endif; ?>

<?php if ($decomp): ?>
<section class="section feature dark">
  <div class="aurora"></div><div class="grain"></div>
  <div class="container feature__grid">
    <div class="feature__media" data-reveal>
      <div class="ring-deco"></div>
      <?= site_pic('feature_image', $decomp['title']) ?>
    </div>
    <div>
      <?= section_head('Featured treatment · ' . ($decomp['menu_label'] ?: $decomp['title']), 'A non-surgical approach to *disc-related pain.*', 'Spinal decompression gently reduces pressure on spinal discs and affected nerves. It may be an option for people experiencing ongoing pain linked to discs, stenosis, sciatica, or nerve irritation.', ['num' => '03']) ?>
      <?php if ($c = array_slice(lines($decomp['conditions']), 0, 6)): ?>
      <ul class="chips" data-reveal><?php foreach ($c as $x): ?><li><?= icon('check') ?><?= e($x) ?></li><?php endforeach; ?></ul>
      <?php endif; ?>
      <div class="btn-row" data-reveal>
        <a class="btn btn--gold" href="<?= e(Content::serviceUrl($decomp)) ?>"><span>Learn more</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
        <?php if (setting('financing_url')): ?><a class="btn btn--glass" href="<?= e(setting('financing_url')) ?>" target="_blank" rel="noopener"><span>Financing with Affirm</span><span class="btn__ic"><?= icon('credit-card') ?></span></a><?php endif; ?>
      </div>
      <div class="stat-pill glass" data-reveal><b>20–30</b><span>approximate minutes per session</span></div>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container">
    <?= section_head('Conditions we address', 'Find care for what is *holding you back.*', 'Select an area to see the conditions we evaluate and the care options that may help.', ['num' => '04', 'center' => true]) ?>
    <?php partial('spine-explorer'); ?>
  </div>
</section>

<section class="section section--alt">
  <div class="container">
    <?= section_head('Getting started', 'A clearer path forward *begins here.*', '', ['num' => '05', 'center' => true]) ?>
    <div class="process">
      <?php foreach ([
          ['Schedule your visit', 'Book online or call our office and our team will help you get started.'],
          ['Meet your chiropractor', 'Discuss your concerns, goals, and the ways pain or limited movement affect your life.'],
          ['Begin your care plan', 'Receive recommendations designed around your condition, comfort, and goals.'],
      ] as $i => [$t, $d]): ?>
      <div class="process__item" data-reveal style="--d:<?= $i * .08 ?>s"><h3><?= e($t) ?></h3><p><?= e($d) ?></p></div>
      <?php endforeach; ?>
    </div>
    <p class="center" style="margin-top:36px" data-reveal><a class="btn" href="<?= e(book_url()) ?>"<?= book_attrs() ?>><span>Book your first visit</span><span class="btn__ic"><?= icon('calendar') ?></span></a></p>
  </div>
</section>

<section class="section dark">
  <div class="aurora"></div><div class="grain"></div>
  <div class="container split split--rev">
    <div class="split__media" data-reveal><?= site_pic('purpose_image', 'The Columbia Basin Chiropractic team', ['class' => 'ph--tall']) ?></div>
    <div>
      <?= section_head('Our purpose', setting('purpose_heading') ?: 'Love God. Love people. Serve with excellence.', setting('purpose_copy') ?: '', ['num' => '06']) ?>
      <div class="values">
        <?php foreach ([['Faith', 'Keeping God at the center of all we do.'], ['Family', 'Treating patients with genuine care and respect.'], ['Focus', 'Remaining intentional and committed to excellence.']] as $i => [$t, $d]): ?>
        <div class="value" data-reveal style="--d:<?= $i * .07 ?>s"><b><?= e($t) ?></b><p><?= e($d) ?></p></div>
        <?php endforeach; ?>
      </div>
      <a class="btn btn--glass" href="<?= e(url('about-us/')) ?>"><span>Our story</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
    </div>
  </div>
</section>

<?php if ($doctors): ?>
<section class="section">
  <div class="container">
    <div class="shead shead--row">
      <?= section_head('Meet your chiropractors', 'Experienced doctors. *Genuine care.*', 'A local team committed to helping you move forward with confidence.', ['num' => '07', 'class' => 'mt-0']) ?>
      <a class="btn btn--ghost" href="<?= e(url('about-us/')) ?>#team" data-reveal><span>Meet the whole team</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
    </div>
    <div class="team">
      <?php foreach ($doctors as $i => $d): ?>
      <a class="doc" href="<?= e(Content::providerUrl($d)) ?>" data-reveal style="--d:<?= $i * .08 ?>s">
        <?= pic($d['photo'], $d['name'], ['slot' => 'providers:' . $d['id'], 'label' => $d['name'] . ' photo', 'pos' => $d['photo_position'] ?: '50% 20%', 'class' => 'ph--light']) ?>
        <span class="doc__info"><span><h3><?= e($d['name']) ?></h3><p><?= e($d['title']) ?></p></span><span class="doc__go"><?= icon('arrow-up-right') ?></span></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($testimonials): ?>
<section class="section section--alt">
  <div class="container">
    <div class="reviews-head">
      <?= section_head('Patient reviews', 'Trusted by patients throughout the *Tri-Cities.*', 'Read real experiences from people who have chosen Columbia Basin Chiropractic for their care.', ['num' => '08', 'class' => 'mt-0']) ?>
      <div class="btn-row" data-reveal>
        <a class="gbadge" href="<?= e(url('testimonials/')) ?>"><span class="gbadge__g"><span>G</span></span><span><strong><?= number_format($rating, 1) ?> <span class="stars"><?php for ($i = 0; $i < 5; $i++) echo icon('star'); ?></span></strong><span class="muted"><?= e(plural(count($testimonials), 'Google review', 'Google reviews')) ?> shown</span></span></a>
        <div class="carousel__nav"><button class="icon-btn" type="button" data-carousel-prev aria-label="Previous reviews"><?= icon('arrow-left') ?></button><button class="icon-btn" type="button" data-carousel-next aria-label="Next reviews"><?= icon('arrow-right') ?></button></div>
      </div>
    </div>
    <div class="reviews-split">
      <?= site_pic('testimonials_image', 'Happy patients at ' . setting('site_short_name')) ?>
      <div class="carousel" data-carousel>
        <div class="carousel__track" tabindex="0" aria-label="Patient reviews">
          <?php foreach ($testimonials as $t): ?>
          <article class="review">
            <span class="stars" aria-label="<?= (int)$t['rating'] ?> out of 5 stars"><?php for ($i = 0; $i < (int)$t['rating']; $i++) echo icon('star'); ?></span>
            <blockquote><?= e($t['content']) ?></blockquote>
            <footer><span class="avatar"><?= e(mb_substr($t['name'], 0, 1)) ?></span><span><b><?= e($t['name']) ?></b><span><?= e($t['label'] ?: 'Patient') ?></span></span></footer>
          </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($videos): ?>
<section class="section section--tight">
  <div class="container">
    <a class="video-teaser" href="<?= e(url('our-videos/')) ?>" data-reveal>
      <?= site_pic('videos_image', 'Patient education videos') ?>
      <div>
        <span class="play"><?= icon('play') ?></span>
        <p class="eyebrow" style="margin-top:28px;justify-content:center;color:var(--mint)">Patient education</p>
        <h2>Understand your care. Feel confident in your <em>next step.</em></h2>
        <p>Explore helpful videos from our team about chiropractic care, common conditions, and better movement.</p>
      </div>
    </a>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container">
    <?= section_head('Inside our practice', 'Professional care in a *welcoming environment.*', 'Our Richland clinic is designed to feel comfortable, personal, and focused on helping you move forward with confidence.', ['num' => '09']) ?>
    <div class="gallery" data-reveal>
      <?php for ($g = 1; $g <= 5; $g++) echo site_pic('gallery_' . $g, 'Inside ' . setting('site_short_name'), ['class' => $g % 2 ? '' : 'ph--light']); ?>
    </div>
  </div>
</section>

<?php if ($loc): ?>
<section class="section section--alt" id="visit">
  <div class="container visit">
    <div class="visit__card dark" data-reveal>
      <div class="aurora"></div><div class="grain"></div>
      <p class="eyebrow">Visit our Richland clinic</p>
      <h2 style="font-size:clamp(2rem,4vw,3.2rem)">Ready to take the <em>next step?</em></h2>
      <p>Schedule online or contact our team with questions about your first visit, appointment availability, or the care that may be right for you.</p>
      <ul class="info-list">
        <li><span class="ti"><?= icon('map-pin') ?></span><span><small>Address</small><a href="<?= e(Content::mapsDirections($loc)) ?>" target="_blank" rel="noopener"><?= e(Content::fullAddress($loc)) ?></a></span></li>
        <?php if ($phone): ?><li><span class="ti"><?= icon('phone') ?></span><span><small>Call or text</small><a href="<?= e(tel_href($phone)) ?>"><?= e($phone) ?></a></span></li><?php endif; ?>
        <li><span class="ti"><?= icon('clock') ?></span><span><small>Office hours</small><table class="hours" data-hours-table><?php foreach (Content::hours($loc) as $h): ?><tr data-days="<?= e($h['days']) ?>"><td><?= e(days_short($h['days'])) ?></td><td><?= e(time_short($h['time'])) ?></td></tr><?php endforeach; ?></table></span></li>
      </ul>
      <div class="btn-row">
        <a class="btn btn--gold" href="<?= e(book_url()) ?>"<?= book_attrs() ?>><span>Schedule an appointment</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
        <a class="btn btn--glass" href="<?= e(Content::mapsDirections($loc)) ?>" target="_blank" rel="noopener"><span>Get directions</span><span class="btn__ic"><?= icon('navigation') ?></span></a>
      </div>
    </div>
    <div class="visit__map" data-reveal style="--d:.08s">
      <iframe src="<?= e(Content::mapEmbed($loc)) ?>" title="Map to <?= e(setting('site_name')) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($faqs): ?>
<section class="section">
  <div class="container faq-split">
    <div>
      <?= section_head('Questions', 'Good to know before *your first visit.*', 'Can’t find your answer? Call or text our team.', ['num' => '10']) ?>
      <p data-reveal><a class="link-arrow" href="<?= e(url('faqs/')) ?>">Read all FAQs <?= icon('arrow-right') ?></a></p>
    </div>
    <div data-reveal><?php partial('faq', ['faqs' => $faqs, 'id' => 'home-faq']); ?></div>
  </div>
</section>
<?php endif; ?>

<?php partial('cta'); ?>
