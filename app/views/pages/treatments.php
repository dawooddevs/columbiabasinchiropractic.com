<?php
/** Services index (page template "treatments"). Supports ?category=<key>. @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
$cats = array_filter(Content::servicesByCategory(), fn($items, $k) => $items && $k !== Content::CONDITION_CATEGORY, ARRAY_FILTER_USE_BOTH);
$active = isset($cats[$_GET['category'] ?? '']) ? $_GET['category'] : '';
$conditions = Content::conditions();
$bySlug = [];
foreach (Content::treatmentsOnly() as $s) $bySlug[$s['slug']] = $s;
$finder = array_filter([
    ['Back, neck, or joint pain', 'Start with chiropractic care or an evaluation for spinal decompression.', 'chiropractic-care'],
    ['Disc, sciatica, or nerve symptoms', 'Learn about non-surgical spinal decompression and related care.', 'spinal-decompression'],
    ['Pain after an accident or injury', 'Explore evaluation, treatment, rehabilitation, and documentation.', 'accident-injury-care'],
    ['Pregnancy-related discomfort', 'Review gentle, modified care for comfort, posture, and mobility.', 'pregnancy-care'],
    ['Sports injury or performance limits', 'Explore sports evaluation, rehabilitation, prevention, and return-to-play care.', 'sports-medicine'],
    ['Nutrition, habits, or wellness goals', 'Start with personalized nutrition and lifestyle support.', 'nutrition-lifestyle-support'],
], fn($f) => isset($bySlug[$f[2]]));
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [['Services', $page['slug'] . '/']],
    'badges' => ['Individualized evaluations', 'Multiple treatment options', 'Clear recovery planning'],
    'media' => pic($page['image'], plain($page['title']), ['slot' => 'pages:' . $page['id'], 'label' => 'Services page hero', 'eager' => true]),
    'note' => ['One clinic. Multiple paths forward.', 'Your provider helps determine which service—or combination—fits your goals.']]);
?>
<section class="section">
  <div class="container">
    <?php if (has_html($page['content'])): ?><div class="shead" data-reveal><p class="eyebrow">How we approach care</p><div class="lead prose"><?= Html::clean($page['content']) ?></div></div><?php endif; ?>
    <div class="process" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));margin-bottom:clamp(56px,7vw,96px)">
      <?php foreach ([['Listen', 'Understand your symptoms, history, concerns, limitations, and goals.'], ['Evaluate', 'Assess movement, function, pain patterns, and relevant clinical findings.'], ['Explain', 'Clearly discuss what may be contributing and which options may be appropriate.'], ['Personalize', 'Build a practical plan around your needs rather than a one-size-fits-all routine.']] as $i => [$t, $d]): ?>
      <div class="process__item" data-reveal style="--d:<?= $i * .06 ?>s"><h3><?= e($t) ?></h3><p><?= e($d) ?></p></div>
      <?php endforeach; ?>
    </div>
    <?php if (count($cats) > 1): ?>
    <nav class="filters" aria-label="Filter services">
      <a href="<?= e(url($page['slug'] . '/')) ?>"<?= $active === '' ? ' aria-current="true"' : '' ?>>All services</a>
      <?php foreach ($cats as $k => $items): ?><a href="<?= e(url($page['slug'] . '/?category=' . $k)) ?>"<?= $active === $k ? ' aria-current="true"' : '' ?>><?= e(Content::categoryLabel($k)) ?></a><?php endforeach; ?>
    </nav>
    <?php endif; ?>
    <?php foreach ($cats as $cat => $items): if ($active && $active !== $cat) continue; ?>
    <div class="cat-block">
      <div class="cat-block__head" data-reveal><h2><?= e(Content::categoryLabel($cat)) ?></h2><p><?= e(Content::CATEGORIES[$cat]['blurb'] ?? '') ?></p></div>
      <div class="grid"><?php foreach ($items as $s) partial('service-card', ['s' => $s]); ?></div>
    </div>
    <?php endforeach; ?>
    <?php if (!$cats): ?><p>No services have been published yet.</p><?php endif; ?>
  </div>
</section>

<?php if ($finder): ?>
<section class="section dark">
  <div class="aurora"></div><div class="grain"></div>
  <div class="container">
    <?= section_head('Find the right starting point', 'Not sure which service *fits?*', 'You do not need to diagnose yourself before scheduling. Start with the concern affecting you most, and the provider can determine which care options may be appropriate.') ?>
    <div class="finder">
      <?php foreach ($finder as $i => [$t, $d, $slug]): ?>
      <a href="<?= e(Content::serviceUrl($bySlug[$slug])) ?>" data-reveal style="--d:<?= $i * .05 ?>s"><i><?= icon('arrow-up-right') ?></i><b><?= e($t) ?></b><span><?= e($d) ?></span></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($conditions): ?>
<section class="section">
  <div class="container">
    <?= section_head('Conditions & concerns', 'Care for pain, movement problems, *and everyday function.*', 'The appropriate service depends on the cause of your symptoms, your examination, your health history, and the results you want to achieve.', ['center' => true]) ?>
    <div class="chips" style="justify-content:center" data-reveal><?php foreach ($conditions as $c): ?><a class="chip" href="<?= e(Content::serviceUrl($c)) ?>"><?= icon('target') ?><?= e($c['menu_label'] ?: $c['title']) ?></a><?php endforeach; ?></div>
  </div>
</section>
<?php endif; ?>

<?php if ($faqs = Content::faqs('general')): ?>
<section class="section section--alt">
  <div class="container faq-split">
    <div><?= section_head('Common questions', 'Choosing and *beginning care.*', 'These answers provide general guidance. Your provider can give more specific recommendations after evaluating your situation.') ?></div>
    <div data-reveal><?php partial('faq', ['faqs' => array_slice($faqs, 0, 6), 'id' => 'svc-index-faq']); ?></div>
  </div>
</section>
<?php endif; ?>
<?php partial('cta'); ?>
