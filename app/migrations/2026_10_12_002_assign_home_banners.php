<?php
/**
 * Assign the two homepage banner photos uploaded with their section headings as file names:
 *   "Move toward better comfort and function…"            → Site Images → Call-to-action background (cta_image)
 *   "Understand your care. Feel confident in your next step…" → Site Images → Patient education cover (videos_image)
 * Requested by the practice, so an existing image in these slots is replaced. Retries on the next
 * deploy while either file is missing.
 */
return function () {
    $images = DB::all("SELECT * FROM media WHERE kind = 'image' ORDER BY id DESC");
    $find = function (string $needle) use ($images) {
        foreach ($images as $m) {
            $name = slugify(pathinfo((string)($m['original_name'] ?: $m['path']), PATHINFO_FILENAME) . ' ' . pathinfo((string)$m['path'], PATHINFO_FILENAME) . ' ' . (string)$m['title']);
            if (str_contains($name, $needle) && !Media::isMissing($m['path'])) return $m;
        }
        return null;
    };
    $slots = [
        'cta_image' => ['move-toward-better-comfort', 'Move toward better comfort and function — call-to-action background'],
        'videos_image' => ['understand-your-care', 'Understand your care — patient education cover'],
    ];
    $log = [];
    $missing = false;
    foreach ($slots as $key => [$needle, $alt]) {
        $m = $find($needle);
        if (!$m) {
            $log[] = "$key: no upload matching '$needle' yet";
            $missing = true;
            continue;
        }
        Settings::set($key, $m['path']);
        if (trim((string)$m['alt']) === '') {
            DB::update('media', ['alt' => mb_substr($alt, 0, 250)], 'id = ?', [$m['id']]);
        }
        $log[] = "$key set to {$m['path']}";
    }
    return $missing ? false : $log;
};
