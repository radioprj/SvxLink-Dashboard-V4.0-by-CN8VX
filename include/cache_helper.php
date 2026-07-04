<?php
/**
 * cache_helper.php — proste cache'owanie plikowe z TTL i ochroną przed
 * "cache stampede", dla SvxLink-Dashboard-V4.0 (CN8VX).
 *
 */

if (!defined('DASHBOARD_CACHE_DIR')) {
    define('DASHBOARD_CACHE_DIR', sys_get_temp_dir() . '/svxdash_cache');
}

/**
 * Zwraca wynik $producer(), ale co najwyżej raz na $ttl sekund.
 * W międzyczasie serwuje wynik zapisany na dysku — wspólny dla
 * WSZYSTKICH requestów PHP (wszystkich klientów, wszystkich workerów
 * Apache/php-fpm).
 */
function dashboard_cached(string $key, int $ttl, callable $producer) {
    if (!is_dir(DASHBOARD_CACHE_DIR)) {
        @mkdir(DASHBOARD_CACHE_DIR, 0700, true);
    }

    $safeKey  = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key);
    $file     = DASHBOARD_CACHE_DIR . '/' . $safeKey . '.cache';
    $lockFile = DASHBOARD_CACHE_DIR . '/' . $safeKey . '.lock';

    // 1) Cache świeży? -> nie dotykamy shell_exec w ogóle.
    if (is_file($file) && (time() - filemtime($file)) < $ttl) {
        $decoded = @json_decode((string) file_get_contents($file), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
    }

    // 2) Cache wygasł / brak. Tylko JEDEN proces go odświeża (flock),
    //    reszta w tym samym momencie dostaje stary wynik zamiast
    //    dokładać kolejne shell_exec obok siebie.
    $fp = @fopen($lockFile, 'c');
    if ($fp && flock($fp, LOCK_EX | LOCK_NB)) {
        try {
            $result = $producer();
            @file_put_contents(
                $file,
                json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                LOCK_EX
            );
        } finally {
            flock($fp, LOCK_UN);
            fclose($fp);
        }
        return $result;
    }
    if ($fp) {
        fclose($fp);
    }

    // 3) Ktoś inny właśnie odświeża -> oddaj to, co jest (nawet nieco
    //    nieaktualne), zamiast czekać / dokładać kolejny shell_exec.
    if (is_file($file)) {
        $decoded = @json_decode((string) file_get_contents($file), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }
    }

    // 4) Zimny start, brak cache — jednorazowo policz bezpośrednio.
    return $producer();
}
