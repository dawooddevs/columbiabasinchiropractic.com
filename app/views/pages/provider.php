<?php
/** Provider profile. @var array $provider */
$p = $provider;
$focus = lines($p['focus']);
$services = Content::servicesBySlugs(csv_list($p['services']));
$locs = array_values(array_filter(Content::locations(), fn($l) => in_array($l['slug'], csv_list($p['locations']), true)));
$others = array_values(array_filter(Content::providers(), fn($o) => is_clinician($o) && $o['slug'] !== $p['slug']));
Seo::set(['title' => $p['meta_title'] ?: ($p['name'] . ' — ' . $p['title']), 'description' => $p['meta_description'] ?: str_limit(strip_tags((string)($p['short_bio'] ?: $p['bio'])), 160), 'canonical' => abs_url(Content::PROVIDER_BASE . '/' . $p['slug'] . '/'), 'image' => $p['photo'], 'type' => 'profile']);
$schema = ['@context' => 'https://schema.org', '@type' => ['Physician', 'Person'], 'name' => $p['name'], 'jobTitle' => $p['title'], 'medicalSpecialty' => 'Chiropractic', 'worksFor' => ['@id' => abs_url('') . '#organization']];
if ($p['photo']) $schema['image'] = abs_url($p['photo']);
if ($p['credentials']) $schema['honorificSuffix'] = $p['credentials'];
Seo::addSchema($schema);
if (!is_clinician($p)) Seo::$noindex = true; // office team members have no public profile page
partial('page-head', ['title' => $p['name'], 'intro' => $p['short_bio'], 'eyebrow' => $p['title'], 'crumbs' => [['About us', 'about-us/'], [$p['name'], Content::PROVIDER_BASE . '/' . $p['slug'] . '/']],
    'badges' => array_filter([$p['credentials'] ? 'Credentials: ' . $p['credentials'] : '', $locs ? $locs[0]['name'] : '']),
    'media' => pic($p['photo'], $p['name'], ['slot' => 'providers:' . $p['id'], 'label' => $p['name'] . ' photo', 'pos' => $p['photo_position'] ?: '50% 20%', 'eager' => true, 'class' => 'ph--light'])]);
$blocks = array_filter(['Biography' => $p['bio'], 'Education' => $p['education'], 'Experience' => $p['experience'], 'Philosophy of care' => $p['philosophy']], 'has_html');
?>
<div class="container detail">
  <article>
    <?php $i = 0; foreach ($blocks as $h => $html): ?>
    <section class="block" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= str_pad((string)++$i, 2, '0', STR_PAD_LEFT) ?></span><?= e($h) ?></p>
      <div class="prose"><?= Html::clean($html) ?></div>
    </section>
    <?php endforeach; ?>
    <?php if ($focus): ?>
    <section class="block" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= str_pad((string)++$i, 2, '0', STR_PAD_LEFT) ?></span>Areas of focus</p>
      <?= check_items($focus) ?>
    </section>
    <?php endif; ?>
    <?php if ($services): ?>
    <section class="block" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= str_pad((string)++$i, 2, '0', STR_PAD_LEFT) ?></span>Services</p>
      <div class="chips"><?php foreach ($services as $s): ?><a class="chip" href="<?= e(Content::serviceUrl($s)) ?>"><?= icon(Content::serviceIcon($s)) ?><?= e($s['menu_label'] ?: $s['title']) ?></a><?php endforeach; ?></div>
    </section>
    <?php endif; ?>
  </article>
  <aside class="aside"><?php partial('book-card', ['title' => 'Book with *' . $p['name'] . '.*', 'text' => 'Choose an available appointment online, or call or text our Richland office and we’ll help you schedule.']); ?></aside>
</div>
<?php if ($others): ?>
<section class="section section--alt section--tight">
  <div class="container">
    <?= section_head('Our doctors', 'Meet the rest of *the team.*') ?>
    <div class="team">
      <?php foreach ($others as $d): ?>
      <a class="doc" href="<?= e(Content::providerUrl($d)) ?>" data-reveal>
        <?= pic($d['photo'], $d['name'], ['slot' => 'providers:' . $d['id'], 'label' => $d['name'] . ' photo', 'pos' => $d['photo_position'] ?: '50% 20%', 'class' => 'ph--light']) ?>
        <span class="doc__info"><span><h3><?= e($d['name']) ?></h3><p><?= e($d['title']) ?></p></span><span class="doc__go"><?= icon('arrow-up-right') ?></span></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php partial('cta'); ?>
