<?php
/** FAQ page (template "faq"): FAQ groups from the dashboard. @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [['FAQs', $page['slug'] . '/']]]);
$groups = array_filter([
    'general' => ['General questions', Content::faqs('general')],
    'new-patients' => ['New patients', Content::faqs('new-patients')],
    'billing' => ['Billing & insurance', Content::faqs('billing')],
    'myths' => ['Common myths', Content::faqs('myths')],
], fn($g) => $g[1]);
// One FAQPage schema for the whole page
$all = array_merge(...array_values(array_map(fn($g) => $g[1], $groups)) ?: [[]]);
if ($all && ($schema = Seo::faqSchema($all))) Seo::addSchema($schema);
?>
<section class="section">
  <div class="container faq-split">
    <div>
      <div class="mini-card" data-reveal>
        <h4>Topics</h4>
        <ul><?php foreach ($groups as $k => $g): ?><li><a href="#<?= e($k) ?>"><?= e($g[0]) ?><?= icon('chevron-right') ?></a></li><?php endforeach; ?></ul>
      </div>
      <div style="margin-top:14px"><?php partial('book-card', ['title' => 'Still have *questions?*', 'text' => 'Give us a call so we can set up a time to meet you.']); ?></div>
    </div>
    <div>
      <?php foreach ($groups as $k => [$label, $faqs]): ?>
      <div class="block" id="<?= e($k) ?>" data-reveal>
        <p class="eyebrow"><?= e($label) ?></p>
        <?php partial('faq', ['faqs' => $faqs, 'id' => 'faq-' . $k, 'noSchema' => true]); ?>
      </div>
      <?php endforeach; ?>
      <?php if (has_html($page['content'])): ?><div class="prose"><?= Html::clean($page['content']) ?></div><?php endif; ?>
    </div>
  </div>
</section>
<?php if ((int)$page['show_cta'] === 1) partial('cta'); ?>
