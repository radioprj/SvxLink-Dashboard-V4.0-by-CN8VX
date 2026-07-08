<?php
/**
 * navbar.php by CN8VX © 2026
 * Barre de navigation.
 *
 * Usage dans index.php :
 *   <?php include __DIR__ . '/include/navbar.php'; ?>
 *
 * Pour marquer une page active, définir avant l'include :
 *   $activeNav = 'dashboard'; // 'dashboard' | 'activity' | 'talkgroups' | 'echolink'
 */

$activeNav = $activeNav ?? 'dashboard';
?>
<nav class="dash-nav">
    <div class="nav-inner">
        <ul class="nav-menu">

            <li>
                <a href="index.php"
                   class="nav-link<?php echo $activeNav === 'dashboard' ? ' active' : ''; ?>">
                   📡 <span data-i18n="nav.dashboard">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="nodes.php" target="_blank" 
                   class="nav-link<?php echo $activeNav === 'nodes' ? ' active' : ''; ?>">
                    🌐 Nodes
                </a>
            </li>

            <li>
                <a href="talkgroup.php" target="_blank" 
                   class="nav-link<?php echo $activeNav === 'talkgroups' ? ' active' : ''; ?>">
                    🔊 Talk Groups
                </a>
            </li>
            <li>
                <a href="logsvx.php" target="_blank" rel="noopener noreferrer"
                   class="nav-link<?php echo $activeNav === 'activity' ? ' active' : ''; ?>">
                    📋 Logs
                </a>
            </li>
<!--            <?php if (!empty($elModActive)): ?>
            <li>
                <a href="echolinksvx/index.php" target="_blank" rel="noopener noreferrer"
                   class="nav-link<?php echo $activeNav === 'echolink' ? ' active' : ''; ?>">
                    🔗 EchoLink
                </a>
            </li>
            <?php endif; ?>
-->
            <!-- =================================================================
                    Liens externes — modifier selon votre installation
                    External links — customize according to your installation
            ====================================================================== --> 
	    <li>
                <a href="http://dashboard.fm-poland.pl/" target="_blank" rel="noopener"
                   class="nav-link nav-link-ext">SVXReflector</a>
            </li>


        </ul>

        <div class="lang-switch">
            <button type="button" class="lang-flag" data-lang="en" onclick="setLang('en')" title="English">🇬🇧</button>
            <button type="button" class="lang-flag" data-lang="pl" onclick="setLang('pl')" title="Polski">🇵🇱</button>
        </div>
    </div>
</nav>