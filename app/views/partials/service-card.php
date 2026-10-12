<?php
/**
 * Service / condition card (also returned by /api/treatments for "Show more").
 * @var array $s  Optional: $light (bool)
 */
$isCond = Content::isCondition($s);
$label = $isCond ? 'Condition' : (Content::CATEGORIES[$s['category']]['short'] ?? '');
?>
<a class="svc<?= !empty($light) ? ' svc--light' : '' ?>" href="<?= e(Content::serviceUrl($s)) ?>" data-spot data-reveal>
  <?= empty($light) ? pic($s['image'], $s['title'], ['slot' => 'services:' . $s['id'], 'label' => $s['title']]) : '' ?>
  <?php if ((int)$s['coming_soon'] === 1): ?><span class="coming">Coming soon</span><?php endif; ?>
  <div class="svc__top">
    <span class="svc__ic"><?= icon(Content::serviceIcon($s)) ?></span>
  </div>
  <div class="svc__body">
    <span class="svc__cat"><?= e($label) ?></span>
    <h3><?= e($s['menu_label'] ?: $s['title']) ?></h3>
    <?php if ($s['excerpt']): ?><p><?= e($s['excerpt']) ?></p><?php endif; ?>
    <span class="svc__go"><i><?= icon('arrow-up-right') ?></i>Explore <?= $isCond ? 'condition' : 'care' ?></span>
  </div>
</a>
