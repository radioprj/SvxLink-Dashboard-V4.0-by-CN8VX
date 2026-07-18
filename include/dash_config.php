<?php
/**
 * dash_config.php — SvxLink Dashboard by CN8VX © 2026
 * Wspólny blok window.DASH_CONFIG, dołączany identycznie na
 * WSZYSTKICH stronach (index.php, nodes.php, talkgroup.php,
 * logsvx.php) — musi być PRZED <script src="scripts/i18n.js">
 * i <script src="scripts/main.js">.
 *
 * Wymaga wcześniejszego załadowania include/config.php (stałe
 * DEFAULT_THEME i DEFAULT_LANG) — na każdej z 4 stron jest to już
 * zapewnione przez require_once infosvx.php / config.php na górze.
 */
?>
<script>
window.DASH_CONFIG = {
    refresh:       8,
    qrz_enabled:   true,
    qrz_url:       'https://www.qrz.com/db/',
    default_theme: '<?php echo htmlspecialchars(DEFAULT_THEME); ?>',
    default_lang:  '<?php echo htmlspecialchars(DEFAULT_LANG); ?>',
    timezone:      '<?php echo htmlspecialchars(TIMEZONE); ?>'
};
</script>
