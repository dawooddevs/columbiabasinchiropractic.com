<?php
/**
 * Frontend template helpers (design layer). Loaded by app/bootstrap.php.
 */

/**
 * Image slot: the image when one is set, otherwise a designed placeholder. Slots carry data attributes
 * so signed-in editors see an "Assign image" chip (site.js); visitors only see the image or the artwork.
 *   $o: class, slot (slot id, e.g. "setting:hero_image" / "services:12"), label, eager, pos (object-position)
 */
function pic(?string $path, string $alt, array $o = []): string
{
    $cls = 'ph' . (!empty($o['class']) ? ' ' . $o['class'] : '');
    $attr = !empty($o['slot']) ? SiteImages::attr($o['slot'], $o['label'] ?? $alt) : '';
    $path = (string)$path;
    if ($path !== '' && !Media::isMissing($path)) {
        $a = ['loading' => !empty($o['eager']) ? 'eager' : 'lazy'];
        if (!empty($o['eager'])) $a['fetchpriority'] = 'high';
        if (!empty($o['pos'])) $a['style'] = 'object-position:' . $o['pos'];
        return '<figure class="' . e($cls) . ' ph--filled"' . $attr . '>' . img($path, $alt, $a) . '</figure>';
    }
    return '<figure class="' . e($cls) . ' ph--empty"' . $attr . '><span class="ph__art" aria-hidden="true">' . brand_mark('ph__mark') . '</span></figure>';
}

/** Image slot for a Settings → Site Images key. */
function site_pic(string $key, string $alt, array $o = []): string
{
    $label = SiteImages::SLOTS[$key]['label'] ?? $key;
    return pic((string)setting($key, ''), $alt, $o + ['slot' => 'setting:' . $key, 'label' => $label]);
}

/** Escaped text where *words* become <em>words</em> (accent styling in headlines). */
function hl(?string $s): string
{
    return preg_replace('/\*(.+?)\*/u', '<em>$1</em>', e($s));
}

/** Text without the *emphasis* markers (titles, meta, breadcrumbs). */
function plain(?string $s): string
{
    return trim(str_replace('*', '', (string)$s));
}

/** Where "Book online" goes: the Jane booking link, else the built-in request form. */
function book_url(): string
{
    $u = trim((string)setting('booking_url'));
    return $u !== '' ? $u : url('book-appointment/');
}

function book_attrs(): string
{
    return preg_match('~^https?://~', book_url()) ? ' target="_blank" rel="noopener"' : '';
}

/** Brand mark: a stack of vertebrae in a rounded tile. */
function brand_mark(string $class = 'mark'): string
{
    static $n = 0;
    $id = 'bm-g' . (++$n);
    return '<svg class="' . e($class) . '" viewBox="0 0 48 48" aria-hidden="true" focusable="false"><defs><linearGradient id="' . $id . '" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#1fb57a"/><stop offset="1" stop-color="#0b3b2a"/></linearGradient></defs>'
        . '<rect width="48" height="48" rx="15" fill="url(#' . $id . ')"/><g fill="#fff"><rect x="16" y="8" width="16" height="6" rx="3" opacity=".95"/><rect x="14" y="16.5" width="20" height="6" rx="3"/><rect x="15" y="25" width="18" height="6" rx="3" opacity=".9"/><rect x="18" y="33.5" width="12" height="6" rx="3" opacity=".8"/></g><circle cx="38" cy="10" r="3" fill="#f6c453"/></svg>';
}

/** Section heading block. */
function section_head(string $eyebrow, string $title, string $intro = '', array $o = []): string
{
    $tag = $o['tag'] ?? 'h2';
    $h = '<div class="shead' . (!empty($o['center']) ? ' shead--center' : '') . (!empty($o['class']) ? ' ' . $o['class'] : '') . '" data-reveal>';
    if ($eyebrow !== '') $h .= '<p class="eyebrow">' . (isset($o['num']) ? '<span class="eyebrow__num">' . e($o['num']) . '</span>' : '') . e($eyebrow) . '</p>';
    $h .= '<' . $tag . ' class="shead__title">' . hl($title) . '</' . $tag . '>';
    if ($intro !== '') $h .= '<p class="shead__intro">' . e($intro) . '</p>';
    return $h . '</div>';
}

/** The first published office (the practice has one). */
function main_location(): ?array
{
    return Content::locations()[0] ?? null;
}

/** Opening hours as JSON for the live "Open now" badge: {"1":[["09:00","18:00"]], …} ISO weekdays. */
function hours_json(?array $loc): string
{
    if (!$loc) return '{}';
    $codes = ['Mo' => 1, 'Tu' => 2, 'We' => 3, 'Th' => 4, 'Fr' => 5, 'Sa' => 6, 'Su' => 7];
    $out = [];
    foreach (Content::hours($loc) as $h) {
        foreach ((array)($h['schema'] ?? []) as $spec) {
            if (!preg_match('/^([A-Za-z,\-]+)\s+(\d{1,2}:\d{2})-(\d{1,2}:\d{2})$/', trim($spec), $m)) continue;
            foreach (explode(',', $m[1]) as $part) {
                $r = explode('-', $part);
                $a = $codes[$r[0]] ?? null;
                $b = $codes[$r[1] ?? $r[0]] ?? null;
                if (!$a || !$b) continue;
                for ($d = $a; $d <= $b; $d++) $out[$d][] = [$m[2], $m[3]];
            }
        }
    }
    return json_encode($out, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}

/** "9:00 AM – 6:00 PM" → "9 AM–6 PM". */
function time_short(string $t): string
{
    return trim(str_replace([':00', ' – ', ' - '], ['', '–', '–'], $t));
}

/** "Wednesday – Friday" → "Wed–Fri". */
function days_short(string $d): string
{
    return preg_replace('/\b(Mon|Tue|Wed|Thu|Fri|Sat|Sun)[a-z]*\b/i', '$1', str_replace([' – ', ' - '], '–', $d));
}

/** Compact hours summary grouped by time, e.g. "Mon, Wed–Fri 9 AM–6 PM · Tue 10 AM–6 PM". */
function hours_short(?array $loc): string
{
    if (!$loc) return '';
    $groups = [];
    foreach (Content::hours($loc) as $h) {
        if (stripos((string)$h['time'], 'closed') !== false) continue;
        $groups[time_short((string)$h['time'])][] = days_short((string)$h['days']);
    }
    $parts = [];
    foreach ($groups as $t => $days) $parts[] = implode(', ', $days) . ' ' . $t;
    return implode(' · ', $parts);
}

/** YouTube video id from a URL ("…watch?v=ID", "youtu.be/ID", "/embed/ID"). */
function yt_id(string $url): string
{
    return preg_match('~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{11})~', $url, $m) ? $m[1] : '';
}

/** Videos from Settings → Videos: [['id' => …, 'title' => …], …]. */
function youtube_videos(): array
{
    $out = [];
    foreach (lines(setting('youtube_videos')) as $line) {
        [$u, $t] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
        if ($id = yt_id($u)) $out[] = ['id' => $id, 'title' => $t];
    }
    return $out;
}

/** Check-mark list items. */
function check_items(array $items, string $class = 'checks'): string
{
    if (!$items) return '';
    $h = '<ul class="' . e($class) . '">';
    foreach ($items as $i) $h .= '<li>' . icon('check', 'checks__ic') . '<span>' . e($i) . '</span></li>';
    return $h . '</ul>';
}

/** Has rich text that isn't empty. */
function has_html(?string $html): bool
{
    return trim(strip_tags((string)$html, '<img><iframe>')) !== '';
}

/** Provider display name with credentials. */
function provider_name(array $p): string
{
    return $p['name'] . ($p['credentials'] ? ', ' . $p['credentials'] : '');
}

/** Providers whose profile pages are linked (doctors and clinicians; office team appear as cards only). */
function is_clinician(array $p): bool
{
    return $p['type'] !== 'staff';
}
