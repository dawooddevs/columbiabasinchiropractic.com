<?php
/** Videos page (template "videos"): YouTube videos from Settings → Videos, loaded on click. @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
$videos = youtube_videos();
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [['Videos', $page['slug'] . '/']]]);
?>
<section class="section">
  <div class="container">
    <?php if ($videos): ?>
    <div class="yt-grid">
      <?php foreach ($videos as $i => $v): ?>
      <div data-reveal style="--d:<?= ($i % 3) * .06 ?>s">
        <div class="yt" data-yt="<?= e($v['id']) ?>">
          <button type="button" aria-label="Play video<?= $v['title'] ? ': ' . e($v['title']) : '' ?>">
            <img src="https://i.ytimg.com/vi/<?= e($v['id']) ?>/hqdefault.jpg" alt="" loading="lazy" width="480" height="360">
            <span class="play"><?= icon('play') ?></span>
          </button>
        </div>
        <?php if ($v['title']): ?><p class="yt-title"><?= e($v['title']) ?></p><?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?><p class="center muted">Videos are coming soon.</p><?php endif; ?>
    <?php if (has_html($page['content'])): ?><div class="container--narrow prose" style="margin:48px auto 0"><?= Html::clean($page['content']) ?></div><?php endif; ?>
  </div>
</section>
<?php partial('cta'); ?>
