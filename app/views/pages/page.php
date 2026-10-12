<?php
/** Standard content page (template "default"). @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
if (in_array($page['slug'], ['thank-you'], true)) Seo::$noindex = true;
$faqGroup = ['new-patient' => 'new-patients', 'faqs' => 'general'][$page['slug']] ?? null;
$faqs = $faqGroup ? Content::faqs($faqGroup) : [];
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [[plain($page['title']), $page['slug'] . '/']],
    'media' => pic($page['image'], plain($page['title']), ['slot' => 'pages:' . $page['id'], 'label' => plain($page['title']) . ' page hero', 'eager' => true])]);
?>
<div class="container detail">
  <article>
    <?php if (has_html($page['content'])): ?><div class="block prose" data-reveal><?= Html::clean((string)$page['content']) ?></div><?php endif; ?>
    <?php if ($page['slug'] === 'new-patient' && ($groups = Content::formGroups())): ?>
    <section class="block" id="patient-forms" data-reveal>
      <p class="eyebrow">New patient paperwork</p>
      <h2>Patient forms.</h2>
      <div class="pforms">
        <?php foreach ($groups as $g): ?>
        <div class="pform"><h3><?= icon($g['icon']) ?><?= e($g['title']) ?></h3><p class="muted"><?= e($g['text']) ?></p><?php foreach ($g['items'] as $f): ?><a href="<?= e($f['url']) ?>" target="_blank" rel="noopener"><?= e($f['label']) ?> <span class="muted">PDF <?= icon('download') ?></span></a><?php endforeach; ?></div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>
    <?php if ($faqs): ?>
    <section class="block" data-reveal><p class="eyebrow">Questions</p><h2>Good to know.</h2><?php partial('faq', ['faqs' => $faqs, 'id' => 'page-faq']); ?></section>
    <?php endif; ?>
  </article>
  <aside class="aside"><?php partial('book-card'); ?></aside>
</div>
<?php if ((int)$page['show_cta'] === 1) partial('cta'); ?>
