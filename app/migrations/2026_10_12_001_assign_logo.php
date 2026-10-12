<?php
/**
 * Point the Branding settings at the logo uploaded to the Media Library (file name containing "logo").
 * Only fills settings that are still empty; never replaces a choice made in the dashboard.
 *   logo        newest image named *logo* (not *light* / *white*)
 *   logo_light  newest image named *logo*light* or *logo*white*
 *   favicon     newest image named *favicon* or *icon*
 * Retries on the next deploy while no logo has been uploaded yet.
 */
return function () {
    $images = DB::all("SELECT * FROM media WHERE kind = 'image' ORDER BY id DESC");
    $name = fn(array $m) => strtolower(pathinfo((string)($m['original_name'] ?: $m['path']), PATHINFO_FILENAME) . ' ' . basename((string)$m['path']));
    $find = function (callable $test) use ($images, $name) {
        foreach ($images as $m) {
            if (!Media::isMissing($m['path']) && $test($name($m))) return $m;
        }
        return null;
    };
    $isLight = fn(string $n) => (bool)preg_match('/light|white|reverse|dark-bg/', $n);
    $picks = [
        'logo' => $find(fn($n) => str_contains($n, 'logo') && !$isLight($n)),
        'logo_light' => $find(fn($n) => str_contains($n, 'logo') && $isLight($n)),
        'favicon' => $find(fn($n) => (bool)preg_match('/favicon|(^|[^a-z])icon([^a-z]|$)/', $n)),
    ];
    if (!$picks['logo'] && !$picks['logo_light']) {
        return false; // no logo uploaded yet: try again on the next deploy
    }
    $log = [];
    foreach ($picks as $key => $m) {
        $current = (string)Settings::get($key, '');
        if (!$m) continue;
        if ($current !== '' && !Media::isMissing($current)) {
            $log[] = "$key already set ($current), left unchanged";
            continue;
        }
        Settings::set($key, $m['path']);
        if (trim((string)$m['alt']) === '') {
            DB::update('media', ['alt' => mb_substr(Settings::get('site_name') . ' logo', 0, 250)], 'id = ?', [$m['id']]);
        }
        $log[] = "$key set to {$m['path']}";
    }
    return $log ?: ['nothing to change'];
};
