<?php
/**
 * "Where does it hurt?" — interactive spine. Regions follow Content::BODY_AREAS; each panel lists the
 * conditions and services tagged with that area (Dashboard → service → "Where does it hurt?" areas).
 */
$map = Content::bodyAreaMap();
$map = array_filter($map);
if (!$map) return;
$intros = [
    'head' => 'Headaches, migraines and tension that often begin in the neck and upper back.',
    'neck' => 'Stiffness, whiplash, disc irritation and pain that travels into the shoulders or arms.',
    'shoulder' => 'Shoulder pain can start in the joint, the surrounding soft tissue, or nerves in the neck.',
    'upper-back' => 'Tension between the shoulder blades, posture strain and changes in the spine’s curves.',
    'lower-back' => 'Disc problems, sciatica, strains, and back pain that keeps coming back.',
    'hip-leg' => 'Radiating leg pain, hip discomfort, and strain from sports, work or pregnancy.',
    'whole-body' => 'Stress, recovery, nutrition and whole-person wellness.',
];
$first = array_key_first($map);
// Geometry (viewBox 0 0 340 520). Gentle S-curve; x offset by height.
$cx = fn(float $y) => 170 + 7 * sin(($y - 90) / 430 * 2 * M_PI);
$verts = [];
foreach (range(0, 6) as $i) $verts['neck'][] = [104 + $i * 11.6, 40 + $i * 2.6, 8.5];
foreach (range(0, 11) as $i) $verts['upper-back'][] = [192 + $i * 13.6, 58 + $i * 1.8, 10.5];
foreach (range(0, 4) as $i) $verts['lower-back'][] = [358 + $i * 17, 82 + $i * 3, 14];
$tags = [
    'head' => [46, 'r'], 'neck' => [130, 'l'], 'shoulder' => [186, 'r'], 'upper-back' => [262, 'l'],
    'lower-back' => [392, 'r'], 'hip-leg' => [474, 'l'], 'whole-body' => [330, 'r'],
];
$region = function (string $key, string $inner) use ($map) {
    if (!isset($map[$key])) return '<g>' . $inner . '</g>';
    return '<g data-region="' . e($key) . '" tabindex="-1">' . $inner . '</g>';
};
?>
<div class="explorer" data-explorer>
  <div class="spine">
    <svg viewBox="0 0 340 520" role="img" aria-label="Spine diagram: choose an area">
      <path class="cord" d="M<?= $cx(90) ?> 90 <?php for ($y = 100; $y <= 450; $y += 10) echo 'L' . round($cx($y), 1) . ' ' . $y . ' '; ?>"/>
      <ellipse class="halo" cx="170" cy="280" rx="150" ry="250"/>
      <?= $region('head', '<ellipse class="head" cx="170" cy="50" rx="30" ry="36"/>') ?>
      <?= $region('shoulder', '<path class="shoulder" d="M150 184 Q105 172 52 196"/><path class="shoulder" d="M190 184 Q235 172 288 196"/><circle class="joint" cx="52" cy="196" r="9"/><circle class="joint" cx="288" cy="196" r="9"/>') ?>
      <?php foreach (['neck', 'upper-back', 'lower-back'] as $key):
          $g = '';
          foreach ($verts[$key] as [$y, $w, $h]) {
              $x = $cx($y);
              $g .= '<rect class="vert" x="' . round($x - $w / 2, 1) . '" y="' . round($y - $h / 2, 1) . '" width="' . $w . '" height="' . $h . '" rx="' . round($h / 2.4, 1) . '"/>';
              $g .= '<rect class="disc" x="' . round($x - $w / 2.6, 1) . '" y="' . round($y + $h / 2 + .6, 1) . '" width="' . round($w / 1.3, 1) . '" height="2" rx="1"/>';
          }
          echo $region($key, $g);
      endforeach; ?>
      <?= $region('hip-leg', '<path class="pelvis" d="M170 452 C205 450 236 456 250 476 C258 492 246 510 226 506 C208 502 196 492 170 494 C144 492 132 502 114 506 C94 510 82 492 90 476 C104 456 135 450 170 452 Z"/>') ?>
    </svg>
    <?php foreach ($tags as $key => [$y, $side]): if (!isset($map[$key])) continue; ?>
    <button type="button" class="spine__tag spine__tag--<?= $side ?><?= $key === $first ? ' is-active' : '' ?>" data-area="<?= e($key) ?>" aria-controls="area-<?= e($key) ?>" aria-pressed="<?= $key === $first ? 'true' : 'false' ?>" style="top:<?= round($y / 520 * 100, 2) ?>%;left:<?= $side === 'l' ? '14' : '86' ?>%"><?= e(Content::BODY_AREAS[$key]) ?></button>
    <?php endforeach; ?>
  </div>
  <div class="explorer__panels">
    <?php foreach ($map as $key => $items): ?>
    <div class="explorer__panel<?= $key === $first ? ' is-active' : '' ?>" id="area-<?= e($key) ?>" data-panel="<?= e($key) ?>" aria-live="polite">
      <p class="eyebrow">Where does it hurt?</p>
      <h3><?= e(Content::BODY_AREAS[$key]) ?></h3>
      <p class="muted lead"><?= e($intros[$key] ?? '') ?></p>
      <ul class="explorer__list">
        <?php foreach ($items as $it): ?>
        <li><a href="<?= e($it['url']) ?>"><span><small><?= e($it['category']) ?></small><?= e($it['title']) ?></span><?= icon('arrow-up-right') ?></a></li>
        <?php endforeach; ?>
      </ul>
      <a class="link-arrow" href="<?= e(url('conditions/')) ?>">See every condition we care for <?= icon('arrow-right') ?></a>
    </div>
    <?php endforeach; ?>
  </div>
</div>
