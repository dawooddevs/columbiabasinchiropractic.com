<?php
/**
 * Page shell. Keep the marked hooks (SEO head, colours, favicon, analytics, admin-managed scripts
 * and chat widget).
 * @var string $content rendered page view  @var string $path request path
 */
$hex = fn($v, $d) => preg_match('/^#[0-9a-f]{6}$/i', (string)$v) ? $v : $d;
$primary = $hex(setting('color_primary'), '#0b3b2a');
$accent = $hex(setting('color_accent'), '#00804a');
$highlight = $hex(setting('color_highlight'), '#f6c453');
$gaId = preg_replace('/[^A-Za-z0-9\-]/', '', (string)setting('ga_id'));
$css = is_file(ROOT . '/assets/css/site.min.css') ? 'css/site.min.css' : 'css/site.css';
?><!doctype html>
<html lang="en" class="no-js">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <script>document.documentElement.className='js'</script>
  <?= Seo::head() /* KEEP: title, meta description, canonical, Open Graph, robots, JSON-LD schema */ ?>
  <meta name="theme-color" content="<?= e($primary) ?>">
  <link rel="preload" href="<?= e(url('assets/fonts/fraunces-latin-opsz-normal.woff2')) ?>" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="<?= e(url('assets/fonts/plus-jakarta-sans-latin-wght-normal.woff2')) ?>" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="<?= e(asset($css)) ?>">
  <style>:root{--primary:<?= $primary ?>;--accent:<?= $accent ?>;--highlight:<?= $highlight ?>}</style>
  <?php if (setting('favicon')): ?><link rel="icon" href="<?= e(media_url(setting('favicon'))) ?>"><?php else: ?><link rel="icon" href="<?= e(url('assets/img/favicon.svg')) ?>" type="image/svg+xml"><?php endif; ?>
  <?php if ($gaId): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($gaId) ?>"></script>
  <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config','<?= e($gaId) ?>');</script>
  <?php endif; ?>
  <?= setting('head_scripts') /* KEEP: trusted, admin-only setting */ ?>
</head>
<body data-base="<?= e(url('')) ?>">
  <a class="skip-link" href="#main">Skip to content</a>
  <?php partial('header', ['path' => $path]); ?>
  <main id="main" tabindex="-1"><?= $content ?></main>
  <?php partial('footer'); ?>
  <script src="<?= e(asset(is_file(ROOT . '/assets/js/site.min.js') ? 'js/site.min.js' : 'js/site.js')) ?>" defer></script>
  <?= setting('chat_widget') /* KEEP: trusted, admin-only setting */ ?>
  <?= setting('body_scripts') /* KEEP: trusted, admin-only setting */ ?>
</body>
</html>
