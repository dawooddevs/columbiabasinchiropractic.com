<?php
/** HTML sitemap. @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'], 'canonical' => abs_url('sitemap/')]);
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'crumbs' => [['Sitemap', 'sitemap/']], 'actions' => false]);
$pages = DB::all("SELECT slug, title FROM pages WHERE status = 'published' AND slug NOT IN ('thank-you') ORDER BY sort_order, title");
?>
<section class="section">
  <div class="container sitemap-cols">
    <section><h2>Pages</h2><ul><li><a href="<?= e(url('')) ?>">Home</a></li><?php foreach ($pages as $p): ?><li><a href="<?= e(url($p['slug'] . '/')) ?>"><?= e(plain($p['title'])) ?></a></li><?php endforeach; ?></ul></section>
    <?php foreach (Content::servicesByCategory() as $cat => $items): if (!$items) continue; ?>
    <section><h2><?= e(Content::categoryLabel($cat)) ?></h2><ul><?php foreach ($items as $s): ?><li><a href="<?= e(Content::serviceUrl($s)) ?>"><?= e($s['title']) ?></a></li><?php endforeach; ?></ul></section>
    <?php endforeach; ?>
    <?php if ($providers = array_filter(Content::providers(), 'is_clinician')): ?>
    <section><h2>Our doctors</h2><ul><?php foreach ($providers as $p): ?><li><a href="<?= e(Content::providerUrl($p)) ?>"><?= e($p['name']) ?></a></li><?php endforeach; ?></ul></section>
    <?php endif; ?>
  </div>
</section>
