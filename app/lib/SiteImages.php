<?php
/**
 * Every place on the website that shows an image ("slot"), so the Media Library can assign a file
 * to any of them with one click (Media Library → select a file → Assign to…).
 *
 * SLOTS are the site-wide images stored as settings (Settings → Site Images). Record images
 * (service, condition, provider, page and location photos) are listed from the database.
 */
final class SiteImages
{
    /** setting key => label, where it appears, and the file name Auto-assign looks for. */
    public const SLOTS = [
        'hero_image' => ['label' => 'Homepage — hero photo', 'where' => 'Large arched photo at the top of the homepage.', 'file' => 'home-hero'],
        'hero_image_2' => ['label' => 'Homepage — hero inset photo', 'where' => 'Small round photo overlapping the hero photo.', 'file' => 'home-hero-2'],
        'about_image' => ['label' => 'Homepage — "Care built around you" photo', 'where' => 'Beside the introduction under the hero.', 'file' => 'home-about'],
        'feature_image' => ['label' => 'Homepage — featured treatment photo', 'where' => 'Spinal decompression feature section.', 'file' => 'home-feature'],
        'purpose_image' => ['label' => 'Homepage — purpose / mission photo', 'where' => 'Beside "Love God. Love people."', 'file' => 'home-purpose'],
        'testimonials_image' => ['label' => 'Homepage — reviews photo', 'where' => 'Beside patient reviews on the homepage.', 'file' => 'home-testimonials'],
        'videos_image' => ['label' => 'Homepage — patient education cover', 'where' => 'Video teaser on the homepage.', 'file' => 'home-videos'],
        'gallery_1' => ['label' => 'Practice gallery — photo 1 (large)', 'where' => '"Inside our practice" gallery on the homepage and About page.', 'file' => 'home-gallery-1'],
        'gallery_2' => ['label' => 'Practice gallery — photo 2', 'where' => '"Inside our practice" gallery.', 'file' => 'home-gallery-2'],
        'gallery_3' => ['label' => 'Practice gallery — photo 3', 'where' => '"Inside our practice" gallery.', 'file' => 'home-gallery-3'],
        'gallery_4' => ['label' => 'Practice gallery — photo 4', 'where' => '"Inside our practice" gallery.', 'file' => 'home-gallery-4'],
        'gallery_5' => ['label' => 'Practice gallery — photo 5', 'where' => '"Inside our practice" gallery.', 'file' => 'home-gallery-5'],
        'team_image' => ['label' => 'About page — team photo', 'where' => 'Wide team photo on the About page.', 'file' => 'about-team'],
        'cta_image' => ['label' => 'Call-to-action background', 'where' => 'Background of the "Ready to take the next step?" band at the bottom of most pages.', 'file' => 'home-cta'],
    ];

    /** Branding / SEO image settings that are also assignable. */
    public const OTHER = [
        'logo' => ['label' => 'Logo (light backgrounds)', 'file' => 'logo', 'group' => 'branding'],
        'logo_light' => ['label' => 'Logo (dark backgrounds / footer)', 'file' => 'logo-light', 'group' => 'branding'],
        'favicon' => ['label' => 'Favicon', 'file' => 'favicon', 'group' => 'branding'],
        'seo_og_image' => ['label' => 'Social share image', 'file' => 'social-share', 'group' => 'seo'],
    ];

    /** [setting key, label, file name(s)] for Media::autoAssign(). */
    public static function autoAssignSettings(): array
    {
        $out = [];
        foreach (self::SLOTS as $k => $s) {
            $out[] = [$k, $s['label'], $k === 'about_image' ? ['home-about', 'home-integrated-care'] : $s['file']];
        }
        foreach (self::OTHER as $k => $s) {
            $out[] = [$k, $s['label'], $s['file']];
        }
        return $out;
    }

    /** Every assignable slot, grouped, with the current image. */
    public static function all(bool $withSettings): array
    {
        $thumb = function (string $path): string {
            if ($path === '') return '';
            $m = Media::lookup($path) ?: [];
            return media_url(($m['thumb'] ?? '') ?: (($m['webp'] ?? '') ?: $path));
        };
        $groups = [];
        if ($withSettings) {
            $items = [];
            foreach (self::SLOTS as $k => $s) {
                $cur = (string)setting($k, '');
                $items[] = ['id' => 'setting:' . $k, 'label' => $s['label'], 'hint' => $s['where'], 'current' => $thumb($cur), 'edit' => '#/settings/images'];
            }
            foreach (self::OTHER as $k => $s) {
                $cur = (string)setting($k, '');
                $items[] = ['id' => 'setting:' . $k, 'label' => $s['label'], 'hint' => 'Settings → ' . ucfirst($s['group']), 'current' => $thumb($cur), 'edit' => '#/settings/' . $s['group']];
            }
            $groups[] = ['label' => 'Site images', 'items' => $items];
        }
        $rows = DB::all('SELECT id, title, category, image FROM services ORDER BY sort_order, title');
        foreach ([false => 'Services', true => 'Conditions'] as $isCond => $label) {
            $items = [];
            foreach ($rows as $r) {
                if (($r['category'] === Content::CONDITION_CATEGORY) !== (bool)$isCond) continue;
                $items[] = ['id' => 'services:' . $r['id'], 'label' => $r['title'], 'hint' => 'Page hero and cards', 'current' => $thumb((string)$r['image']), 'edit' => '#/services/' . $r['id']];
            }
            if ($items) $groups[] = ['label' => $label, 'items' => $items];
        }
        $items = [];
        foreach (DB::all('SELECT id, name, title, photo FROM providers ORDER BY sort_order, name') as $r) {
            $items[] = ['id' => 'providers:' . $r['id'], 'label' => $r['name'], 'hint' => $r['title'] ?: 'Team photo', 'current' => $thumb((string)$r['photo']), 'edit' => '#/providers/' . $r['id']];
        }
        if ($items) $groups[] = ['label' => 'Team', 'items' => $items];
        $items = [];
        foreach (DB::all('SELECT id, title, slug, image FROM pages ORDER BY sort_order, title') as $r) {
            $items[] = ['id' => 'pages:' . $r['id'], 'label' => $r['title'], 'hint' => '/' . $r['slug'] . '/ — page hero', 'current' => $thumb((string)$r['image']), 'edit' => '#/pages/' . $r['id']];
        }
        if ($items) $groups[] = ['label' => 'Pages', 'items' => $items];
        $items = [];
        foreach (DB::all('SELECT id, name, image FROM locations ORDER BY sort_order, name') as $r) {
            $items[] = ['id' => 'locations:' . $r['id'], 'label' => $r['name'], 'hint' => 'Office photo', 'current' => $thumb((string)$r['image']), 'edit' => '#/locations/' . $r['id']];
        }
        if ($items) $groups[] = ['label' => 'Locations', 'items' => $items];
        return $groups;
    }

    /** Point one slot at $path. Returns a label for the activity log, or throws on an unknown slot. */
    public static function assign(string $slot, string $path, bool $canSettings): string
    {
        [$type, $id] = array_pad(explode(':', $slot, 2), 2, '');
        if ($type === 'setting') {
            if (!$canSettings) throw new RuntimeException('Only administrators can change site images.');
            if (!isset(self::SLOTS[$id]) && !isset(self::OTHER[$id])) throw new RuntimeException('Unknown image slot.');
            Settings::set($id, $path);
            return (self::SLOTS[$id] ?? self::OTHER[$id])['label'];
        }
        $map = ['services' => ['image', 'title'], 'providers' => ['photo', 'name'], 'pages' => ['image', 'title'], 'locations' => ['image', 'name']];
        if (!isset($map[$type]) || !ctype_digit($id)) throw new RuntimeException('Unknown image slot.');
        [$col, $labelCol] = $map[$type];
        $row = DB::one('SELECT * FROM ' . $type . ' WHERE id = ?', [(int)$id]);
        if (!$row) throw new RuntimeException('That item no longer exists.');
        DB::update($type, [$col => $path, 'updated_at' => now()], 'id = ?', [(int)$id]);
        return (string)$row[$labelCol];
    }

    /**
     * Editor hint for a public image slot: data attributes read by site.js, which shows an
     * "Assign image" chip only to signed-in dashboard users (the page HTML stays the same for everyone).
     * $id is a slot id as used by assign(), e.g. "setting:hero_image" or "services:12".
     */
    public static function attr(string $id, string $label): string
    {
        return ' data-slot="' . e($id) . '" data-slot-label="' . e($label) . '"';
    }
}
