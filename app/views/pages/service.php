<?php
/**
 * Service or condition page. Every section is hidden when its field is empty.
 * Conditions (category "conditions") use the same fields with condition wording: see app/seed/services.php.
 * @var array $service
 */
$s = $service;
$isCond = Content::isCondition($s);
$path = Content::servicePath($s);
$conditions = lines($s['conditions']);
$benefits = lines($s['benefits']);
$faqs = json_list($s['faqs']);
$related = Content::related($s, 3);
$relatedServices = Content::servicesBySlugs(csv_list($s['related'] ?? ''));
$providers = array_values(array_filter(Content::providers(), fn($p) => is_clinician($p) && in_array($s['slug'], csv_list($p['services']), true)));
$name = $s['menu_label'] ?: $s['title'];

// Condition pages: a leading warning callout in how_it_works is shown under "Symptoms".
$how = (string)$s['how_it_works'];
$warn = '';
if (preg_match('~^\s*(<div class="callout callout--warn">.*?</div>)~s', $how, $m)) {
    $warn = $m[1];
    $how = substr($how, strlen($m[0]));
}

Seo::set(['title' => $s['meta_title'] ?: $s['title'], 'description' => $s['meta_description'] ?: $s['excerpt'], 'canonical' => abs_url($path), 'image' => $s['image']]);
Seo::addSchema($isCond ? [
    '@context' => 'https://schema.org', '@type' => 'MedicalWebPage', 'name' => $s['title'], 'url' => abs_url($path),
    'description' => strip_tags((string)$s['excerpt']),
    'about' => ['@type' => 'MedicalCondition', 'name' => $s['title']] + ($conditions ? ['possibleTreatment' => array_map(fn($r) => ['@type' => 'MedicalTherapy', 'name' => $r['title']], array_values(array_filter($relatedServices, fn($r) => !Content::isCondition($r))))] : []),
    'publisher' => ['@id' => abs_url('') . '#organization'],
] : [
    '@context' => 'https://schema.org', '@type' => 'MedicalWebPage', 'name' => $s['title'], 'url' => abs_url($path),
    'description' => strip_tags((string)$s['excerpt']), 'about' => ['@type' => 'MedicalTherapy', 'name' => $s['title']],
    'publisher' => ['@id' => abs_url('') . '#organization'],
]);

$crumbs = $isCond
    ? [['Conditions', 'conditions/'], [$s['title'], $path]]
    : [['Services', 'services/'], [$s['title'], $path]];
$eyebrow = $isCond ? $s['title'] . ' treatment in Richland, WA' : Content::categoryLabel($s['category']) . ' · Richland, WA';
$badges = $isCond ? ['Detailed evaluation', 'Personalized treatment', 'Non-invasive care'] : array_slice($benefits, 0, 3);

$sections = [];
if (has_html($s['what_is'])) $sections['overview'] = 'Overview';
if ($conditions) $sections['conditions'] = $isCond ? 'Causes' : 'Conditions';
if ($isCond && ($benefits || $warn)) $sections['symptoms'] = 'Symptoms';
if (has_html($how)) $sections['treatment'] = $isCond ? 'Treatment' : 'How it works';
if (!$isCond && $benefits) $sections['benefits'] = 'Benefits';
if (has_html($s['what_to_expect'])) $sections['visit'] = 'Your visit';
if ($faqs) $sections['faq'] = 'FAQ';
$n = 0;
$num = function () use (&$n) { return str_pad((string)++$n, 2, '0', STR_PAD_LEFT); };
$financing = setting('financing_url') && in_array($s['slug'], ['spinal-decompression', 'herniated-slipped-discs', 'sciatica'], true);

partial('page-head', [
    'title' => $s['title'], 'intro' => $s['hero_text'] ?: $s['excerpt'], 'crumbs' => $crumbs, 'eyebrow' => $eyebrow, 'badges' => $badges,
    'media' => pic($s['image'], $s['title'] . ' at ' . setting('site_short_name'), ['slot' => 'services:' . $s['id'], 'label' => $s['title'], 'eager' => true]),
    'note' => $isCond ? ['Find the cause', 'Not only the symptom: your care starts with a thorough evaluation.'] : ['Built around you', 'Care based on your symptoms, examination, health history, and goals.'],
]);
?>
<?php if (count($sections) > 1): ?>
<nav class="toc" aria-label="On this page" data-toc>
  <div class="toc__in"><?php foreach ($sections as $id => $label): ?><a href="#<?= e($id) ?>"><?= e($label) ?></a><?php endforeach; ?></div>
</nav>
<?php endif; ?>

<div class="container detail">
  <article>
    <?php if (isset($sections['overview'])): ?>
    <section class="block" id="overview" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= $num() ?></span><?= $isCond ? 'Understanding ' . e($name) : 'Clinical overview' ?></p>
      <h2><?= $isCond ? 'The cause is not always simple.' : 'What is ' . e($name) . '?' ?></h2>
      <div class="prose"><?= Html::clean($s['what_is']) ?></div>
    </section>
    <?php endif; ?>

    <?php if ($conditions): ?>
    <section class="block" id="conditions" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= $num() ?></span><?= $isCond ? 'Common causes' : 'Conditions & symptoms' ?></p>
      <h2><?= $isCond ? 'What may be contributing.' : 'What it may help with.' ?></h2>
      <p class="muted"><?= $isCond ? 'The same symptom can come from different causes. Your history, pain pattern, movement, examination, and available imaging help determine which factors may be involved.' : 'Similar symptoms can have different causes. Your provider will evaluate your condition before recommending a treatment plan.' ?></p>
      <?= check_items($conditions) ?>
    </section>
    <?php endif; ?>

    <?php if (isset($sections['symptoms'])): ?>
    <section class="block" id="symptoms" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= $num() ?></span>Symptoms &amp; warning signs</p>
      <h2>How it may feel.</h2>
      <?= check_items($benefits, 'checks checks--warm') ?>
      <?php if ($warn): ?><div class="prose"><?= Html::clean($warn) ?></div><?php endif; ?>
    </section>
    <?php endif; ?>

    <?php if (isset($sections['treatment'])): ?>
    <section class="block" id="treatment" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= $num() ?></span><?= $isCond ? 'Non-invasive treatment options' : 'Treatment approach' ?></p>
      <h2><?= $isCond ? 'Care built around the source.' : 'How it works.' ?></h2>
      <div class="prose"><?= Html::clean($how) ?></div>
      <?php if ($financing): ?>
      <div class="financing">
        <span class="ti"><?= icon('credit-card') ?></span>
        <div><h3>Financing available with Affirm</h3><p>Qualified patients may be able to divide the cost of a recommended spinal decompression treatment plan into monthly payments. Approval and terms are determined by Affirm.</p></div>
        <a class="btn btn--ink btn--sm" href="<?= e(setting('financing_url')) ?>" target="_blank" rel="noopener"><span>View options</span><span class="btn__ic"><?= icon('arrow-up-right') ?></span></a>
      </div>
      <?php endif; ?>
    </section>
    <?php endif; ?>

    <?php if (isset($sections['benefits'])): ?>
    <section class="block" id="benefits" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= $num() ?></span>Why patients choose it</p>
      <h2>Benefits &amp; goals.</h2>
      <?= check_items($benefits) ?>
    </section>
    <?php endif; ?>

    <?php if (isset($sections['visit'])): ?>
    <section class="block" id="visit" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= $num() ?></span>Your first visit</p>
      <h2>What to expect.</h2>
      <div class="prose"><?= Html::clean($s['what_to_expect']) ?></div>
    </section>
    <?php endif; ?>

    <?php if ($faqs): ?>
    <section class="block" id="faq" data-reveal>
      <p class="eyebrow"><span class="eyebrow__num"><?= $num() ?></span>Patient questions</p>
      <h2><?= e($name) ?> FAQ.</h2>
      <?php partial('faq', ['faqs' => $faqs, 'id' => 'svc-faq']); ?>
    </section>
    <?php endif; ?>
  </article>

  <aside class="aside">
    <?php partial('book-card', ['title' => 'Schedule a ' . mb_strtolower($isCond ? $name . ' evaluation' : $name . ' visit') . '.']); ?>
    <?php if ($isCond && ($others = Content::conditions())): ?>
    <div class="mini-card"><h4>Conditions</h4><ul><?php foreach ($others as $o): ?><li><a href="<?= e(Content::serviceUrl($o)) ?>"<?= $o['slug'] === $s['slug'] ? ' aria-current="page"' : '' ?>><?= e($o['menu_label'] ?: $o['title']) ?><?= icon('chevron-right') ?></a></li><?php endforeach; ?></ul></div>
    <?php elseif (!$isCond && ($others = Content::treatmentsOnly())): ?>
    <div class="mini-card"><h4>Our services</h4><ul><?php foreach ($others as $o): ?><li><a href="<?= e(Content::serviceUrl($o)) ?>"<?= $o['slug'] === $s['slug'] ? ' aria-current="page"' : '' ?>><?= e($o['menu_label'] ?: $o['title']) ?><?= icon('chevron-right') ?></a></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
  </aside>
</div>

<?php if ($providers): ?>
<section class="section section--alt section--tight">
  <div class="container">
    <?= section_head('Your care team', 'Chiropractors who offer *' . $name . '.*') ?>
    <div class="team">
      <?php foreach ($providers as $p): ?>
      <a class="doc" href="<?= e(Content::providerUrl($p)) ?>" data-reveal>
        <?= pic($p['photo'], $p['name'], ['slot' => 'providers:' . $p['id'], 'label' => $p['name'] . ' photo', 'pos' => $p['photo_position'] ?: '50% 20%', 'class' => 'ph--light']) ?>
        <span class="doc__info"><span><h3><?= e($p['name']) ?></h3><p><?= e($p['title']) ?></p></span><span class="doc__go"><?= icon('arrow-up-right') ?></span></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($related): ?>
<section class="section section--tight">
  <div class="container">
    <div class="shead shead--row">
      <?= section_head('Related care', 'Additional care that may *support your recovery.*', '', ['class' => 'mt-0']) ?>
      <a class="btn btn--ghost" href="<?= e(url($isCond ? 'conditions/' : 'services/')) ?>" data-reveal><span>View all</span><span class="btn__ic"><?= icon('arrow-right') ?></span></a>
    </div>
    <div class="grid"><?php foreach ($related as $i => $r) partial('service-card', ['s' => $r, 'n' => $i + 1]); ?></div>
  </div>
</section>
<?php endif; ?>

<?php partial('cta'); ?>
