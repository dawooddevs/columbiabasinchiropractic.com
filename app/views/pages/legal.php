<?php
/** Legal page (template "legal"). @var array $page */
Seo::set(['title' => $page['meta_title'] ?: plain($page['title']), 'description' => $page['meta_description'] ?: $page['intro'], 'canonical' => abs_url($page['slug'] . '/'), 'image' => $page['image']]);
partial('page-head', ['title' => $page['title'], 'intro' => $page['intro'], 'eyebrow' => $page['eyebrow'], 'crumbs' => [[plain($page['title']), $page['slug'] . '/']], 'actions' => false]);
?>
<section class="section">
  <div class="container legal prose"><?= Html::clean((string)$page['content']) ?></div>
</section>
