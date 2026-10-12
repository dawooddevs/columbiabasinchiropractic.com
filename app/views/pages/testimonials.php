<?php
/** Testimonials page (template "testimonials"). @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
$items = Content::testimonials();
$rating = $items ? array_sum(array_map(fn($t) => (int)$t['rating'], $items)) / count($items) : 0;
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [['Reviews', $page['slug'] . '/']],
    'badges' => $items ? [number_format($rating, 1) . ' average rating', plural(count($items), 'patient review', 'patient reviews')] : []]);
?>
<section class="section">
  <div class="container">
    <?php if ($items): ?>
    <div class="masonry">
      <?php foreach ($items as $i => $t): ?>
      <article class="review" data-reveal style="--d:<?= ($i % 3) * .06 ?>s">
        <span class="stars" aria-label="<?= (int)$t['rating'] ?> out of 5 stars"><?php for ($s = 0; $s < (int)$t['rating']; $s++) echo icon('star'); ?></span>
        <blockquote><?= e($t['content']) ?></blockquote>
        <footer><span class="avatar"><?= e(mb_substr($t['name'], 0, 1)) ?></span><span><b><?= e($t['name']) ?></b><span><?= e($t['label'] ?: 'Patient') ?></span></span></footer>
      </article>
      <?php endforeach; ?>
    </div>
    <?php else: ?><p class="center muted">Reviews will appear here soon.</p><?php endif; ?>
    <?php if (has_html($page['content'])): ?><div class="container--narrow prose" style="margin:48px auto 0"><?= Html::clean($page['content']) ?></div><?php endif; ?>
  </div>
</section>
<?php partial('cta'); ?>
