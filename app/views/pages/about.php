<?php
/** About / team page (page template "about"). @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
$providers = Content::providers();
$doctors = array_values(array_filter($providers, 'is_clinician'));
$staff = array_values(array_filter($providers, fn($p) => !is_clinician($p)));
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [['About us', $page['slug'] . '/']],
    'badges' => ['Friendly team you can call friends', 'Most insurance plans accepted', 'Energy-saving, waste-reducing practice'],
    'media' => pic($page['image'], plain($page['title']), ['slot' => 'pages:' . $page['id'], 'label' => 'About page hero', 'eager' => true]),
    'note' => ['Welcome to Columbia Basin Chiropractic', 'Working together for a healthy life.']]);
?>
<?php if (has_html($page['content'])): ?>
<section class="section">
  <div class="container split">
    <div class="prose lead" data-reveal><?= Html::clean($page['content']) ?></div>
    <div class="split__media" data-reveal style="--d:.08s"><?= site_pic('about_image', 'Care at ' . setting('site_short_name'), ['class' => 'ph--tall']) ?></div>
  </div>
</section>
<?php endif; ?>

<section class="section dark">
  <div class="aurora"></div><div class="grain"></div>
  <div class="container">
    <?= section_head('Vision, mission & values', 'Love God. *Love people.*', 'At the heart of everything we do is a commitment to honoring God and serving our community with compassion and integrity.', ['center' => true]) ?>
    <div class="process">
      <div class="process__item" data-reveal><h3>Our vision</h3><p><b style="color:#fff">Love God, Love People!</b> At the heart of everything we do is a commitment to honoring God and serving our community with compassion and integrity.</p></div>
      <div class="process__item" data-reveal style="--d:.06s"><h3>Our mission</h3><p><b style="color:#fff">Serve – “the giving of oneself”.</b> We are dedicated to serving others through chiropractic care, focusing on improving health, restoring function, and enriching lives by giving of ourselves fully to our patients and their families.</p></div>
      <div class="process__item" data-reveal style="--d:.12s"><h3>Our core values</h3><p><b style="color:#fff">Faith</b> – We put our trust in God and keep Him at the center of all we do. <b style="color:#fff">Family</b> – We treat every patient as part of our extended family. <b style="color:#fff">Focus</b> – We are intentional and committed to excellence in the care we provide.</p></div>
    </div>
  </div>
</section>

<?php if ($doctors): ?>
<section class="section" id="team">
  <div class="container">
    <?= section_head('Meet our doctors', 'The chiropractors behind *your care.*', 'We use an adjusting style commonly referred to as “Diversified.” First, an analysis of your spine is performed—this can initially involve a case history and X-ray pictures of your spine.') ?>
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

<?php if ($staff): ?>
<section class="section section--alt">
  <div class="container">
    <?= section_head('Our office team', 'A friendly team you can *call friends.*', '', ['center' => true]) ?>
    <div class="staff">
      <?php foreach ($staff as $i => $p): ?>
      <div class="staff__m" data-reveal style="--d:<?= ($i % 6) * .05 ?>s">
        <?= pic($p['photo'], $p['name'], ['slot' => 'providers:' . $p['id'], 'label' => $p['name'] . ' photo', 'pos' => $p['photo_position'] ?: '50% 25%', 'class' => 'ph--light']) ?>
        <b><?= e($p['name']) ?></b><span><?= e($p['title']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="margin-top:clamp(40px,6vw,72px)" data-reveal><?= site_pic('team_image', 'The Columbia Basin Chiropractic team', ['class' => 'ph--light ph--wide', 'pos' => '50% 40%']) ?></div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container">
    <?= section_head('Inside our practice', 'Professional care in a *welcoming environment.*') ?>
    <div class="gallery" data-reveal><?php for ($g = 1; $g <= 5; $g++) echo site_pic('gallery_' . $g, 'Inside ' . setting('site_short_name'), ['class' => $g % 2 ? '' : 'ph--light']); ?></div>
  </div>
</section>
<?php if ((int)$page['show_cta'] === 1) partial('cta'); ?>
