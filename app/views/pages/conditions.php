<?php
/** Conditions index (page template "conditions"). @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
$conditions = Content::conditions();
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [['Conditions', $page['slug'] . '/']],
    'media' => pic($page['image'], plain($page['title']), ['slot' => 'pages:' . $page['id'], 'label' => 'Conditions page hero', 'eager' => true]),
    'note' => ['Find the cause', 'Similar symptoms can have different causes—care starts with a thorough evaluation.']]);
?>
<section class="section">
  <div class="container">
    <?= section_head('Where does it hurt?', 'Choose an area to *get started.*', '', ['center' => true]) ?>
    <?php partial('spine-explorer'); ?>
  </div>
</section>
<?php if ($conditions): ?>
<section class="section section--alt">
  <div class="container">
    <?= section_head('All conditions', 'Common concerns we *evaluate and care for.*') ?>
    <div class="cond-grid">
      <?php foreach ($conditions as $c): ?>
      <a class="cond" href="<?= e(Content::serviceUrl($c)) ?>" data-reveal>
        <?= pic($c['image'], $c['title'], ['slot' => 'services:' . $c['id'], 'label' => $c['title'], 'class' => 'ph--light']) ?>
        <span class="cond__b"><h3><?= e($c['title']) ?></h3><?php if ($c['excerpt']): ?><p><?= e($c['excerpt']) ?></p><?php endif; ?><span class="link-arrow">Learn more <?= icon('arrow-right') ?></span></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php if (has_html($page['content'])): ?><section class="section"><div class="container container--narrow prose"><?= Html::clean($page['content']) ?></div></section><?php endif; ?>
<?php partial('cta'); ?>
