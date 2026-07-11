<?php  
/**
 * page_header — SvxLink Dashboard by CN8VX © 2026
 * En-tête principal du dashboard (logo, titre, état et horloge).
 * Main dashboard header (logo, title, status and clock).
 */
?>

<!-- ══ TOP BAR ══════════════════════════════════════════════ -->
<div id="root" class="dark-bg">
  <div class="top-bar">
    <div>
      <div class="header-main">
          
          <?php if ($hasLogo): ?>
              <img src="<?php echo htmlspecialchars(LOGO_PATH); ?>"
                    alt="Logo <?php echo htmlspecialchars($CALLSIGN); ?>"
                    class="header-logo">
          <?php else: ?>
              <div class="header-logo-text"><?php echo htmlspecialchars($CALLSIGN); ?></div>
          <?php endif; ?>

          <div>
          <div class="header-titles"><span data-i18n="header.dashboard_for">SvxLink Dashboard for</span> 
                  <span class="repeater-type"><?php echo htmlspecialchars($repeaterType ?? ''); ?></span> <span data-i18n="header.node">Node :</span>
                  <span class="header-callsign"><?php echo htmlspecialchars($CALLSIGN); ?></span>
              </div>
              <div class="header-subtitle">
                  <span class="block-icon">🗼</span><?php echo htmlspecialchars(DASHBOARD_SUBTITLE); ?>. 
                  <span class="qth-icon">📍</span><span data-i18n="header.qth">QTH:</span> <?php echo htmlspecialchars(HEADER_QTH); ?>.
              </div>
          </div>

      </div>
    </div>

    <div class="status-row">
        <span>
            <div class="header-clock" id="header-clock">--:--</div>
        </span>
        <div id="statusLabel" class="status-label">
          <span id="rxdot" class="status-dot"></span>
          <span id="statusText">--</span>
        </div>
        <button class="theme-btn" id="theme-toggle" title="Change Theme" aria-label="Toggle dark/light theme">
          <span class="theme-icon">🌙</span>
        </button>
    </div>
</div>
