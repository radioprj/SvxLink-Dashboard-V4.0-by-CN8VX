<?php
/**
* SNouvelle version de SvxLink-Dashboard entièrement conçue et développée par CN8VX © 2026.
* SvxLink-Dashboard by CN8VX est conçu pour les répéteurs et hotspots SvxLink. 
* 
* New version of SvxLink-Dashboard fully designed and developed by CN8VX © 2026.
* SvxLink-Dashboard by CN8VX is designed for SvxLink repeaters and hotspots.
*/
require_once __DIR__ . '/include/infosvx.php';
require_once __DIR__ . '/include/hardware_info.php';
$hw             = getAllHardwareInfo();
$repeaterData   = getRepeaterStatus();
$repeaterStatus = $repeaterData['status'];
$rsDesc         = $repeaterData['description'];
$activeMods     = getActiveModules();

$moduleLabels = !empty($MODULES)
    ? array_map(function ($m) {
        return trim(preg_replace('/^Module/i', '', trim($m)));
      }, explode(',', $MODULES))
    : [];

$hasLogo        = (LOGO_PATH !== '' && file_exists(__DIR__ . '/' . LOGO_PATH));


// ── Seuils couleur CPU / Temp ─────────────────────────────────
$cpuPct   = $hw['cpu_usage'] ?? 0;
$cpuClass = $cpuPct >= 85 ? 'crit' : ($cpuPct >= 65 ? 'warn' : '');
$cpuVal   = $cpuPct >= 85 ? 'val-crit' : ($cpuPct >= 65 ? 'val-warn' : 'val-ok');

$temp         = isset($hw['cpu_temp']) && $hw['cpu_temp'] !== '' ? (float)$hw['cpu_temp'] : 0;
$tempVal      = $temp >= 70 ? 'val-crit'  : ($temp >= 55 ? 'val-warn'  : 'val-ok');
$tempPanelCls = $temp >= 70 ? 'red'       : ($temp >= 55 ? 'amber'     : 'green');

$ramPct   = $hw['ram']['percent']  ?? 0;
$ramClass = $ramPct  >= 85 ? 'crit' : ($ramPct  >= 65 ? 'warn' : '');

$diskPct   = $hw['disk']['percent'] ?? 0;
$diskClass = $diskPct >= 85 ? 'crit' : ($diskPct >= 65 ? 'warn' : '');

// ── Données pour les barres de progression ─────────────────────
$hw_cpu_usage    = $hw['cpu_usage'] ?? 0;
$cpu_bar_class   = $hw_cpu_usage  > 85 ? 'progress-danger' : ($hw_cpu_usage  > 65 ? 'progress-warning' : 'progress-ok');

$hw_ram_percent  = $hw['ram']['percent']  ?? 0;
$ram_bar_class   = $hw_ram_percent  > 85 ? 'progress-danger' : ($hw_ram_percent  > 65 ? 'progress-warning' : 'progress-ok');

$hw_disk_percent = $hw['disk']['percent'] ?? 0;
$disk_bar_class  = $hw_disk_percent > 85 ? 'progress-danger' : ($hw_disk_percent > 65 ? 'progress-warning' : 'progress-ok');

// ── Uptime SvxLink (valeur initiale, rafraîchi toutes les 30 s par AJAX) ──
$svxUptime = getSvxlinkUptime();

// ── Reflector Activity (remplace "Derniers appelants") ────────
$rfConf     = parse_svxlink_config(SVXLINK_CONFIG);
$rfActive   = isSVXReflectorActive($rfConf);
$rfActivity = $rfActive ? getReflectorActivity(50) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SvxLink <?php echo htmlspecialchars($repeaterType ?? ''); ?> SVX Node Dashboard - <?php echo htmlspecialchars($CALLSIGN); ?></title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include __DIR__ . '/include/navbar.php'; ?>
<?php include __DIR__ . '/include/header.php'; ?>

<!-- ══ FREQUENCY Repeater ══════════════════════════════════ -->
  <div class="grid-top fr-pn">

    <div class="freq-panel">
      <div class="freq-block">
        <div class="panel-label panel-bar"><span class="block-icon">🛜</span><span data-i18n="index.freq_title">Frequency RX / TX</span></div>
        <div class="freq-main" id="freqEl"><?php echo htmlspecialchars(FREQ_RX); ?> MHz</div>
        <div class="panel-sub">
          <?php if (!empty($CTCSS)): ?>
            CTCSS <?php echo htmlspecialchars($CTCSS); ?> Hz |
          <?php endif; ?>
          <?php if (FREQ_OFFSET !== ''): ?>
            <span data-i18n="index.offset">Offset</span> <?php echo htmlspecialchars(FREQ_OFFSET); ?> MHz
          <?php endif; ?>
        </div>
      </div>
    </div>

<!-- ══ Repeater Status ═════════════════════ -->
    <div class="repeater-status-panel">
      <div class="panel-label panel-bar"><span class="block-icon">🗼</span><span data-i18n="index.trx_status">TRX Status</span></div>
      <div class="rs-bar">
        <div class="rs-state <?php echo $repeaterStatus === 'tx' ? 'tx' : ($repeaterStatus === 'rx' ? 'rx' : 'listening'); ?> active" id="rs-main">
          <?php if ($repeaterStatus !== 'listening'): ?>
            <span class="rs-dot" id="rs-dot"></span>
          <?php else: ?>
            <span class="rs-dot" id="rs-dot" style="display: none;"></span>
          <?php endif; ?>
          <span id="rs-text"><?php
            if      ($repeaterStatus === 'tx') echo 'TX';
            elseif  ($repeaterStatus === 'rx') echo 'RX';
            else    echo 'LISTENING';
          ?></span>
        </div>
      </div>
     <div class="rs-desc" id="rs-desc" data-i18n="<?php echo htmlspecialchars($repeaterData['description_key'] ?? ''); ?>"><?php echo htmlspecialchars($rsDesc); ?></div>
    </div>

<!-- ══ GRID LEFT : Modules actifs ═══════════════════════════ -->
   <div class="module-panel">
      <div class="panel-label panel-bar"><span class="block-icon">🔓</span><span data-i18n="index.modules_title">Modules</span></div>
      <div class="module-list" id="modules-live">
        <?php if (!empty($moduleLabels)): ?>
          <?php foreach ($moduleLabels as $mod): ?>
            <span class="module-badge<?php echo in_array($mod, $activeMods, true) ? ' active' : ''; ?>"><?php echo htmlspecialchars($mod); ?></span>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="module-empty" data-i18n="index.no_modules">No loaded modules</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
<!-- ══ GRID MAIN : Uptime / QSO / CPU Temp ══════════════════ -->
  <div class="grid-main">

    <div class="panel">
      <div class="panel-label panel-bar"><span class="block-icon">🕒</span><span data-i18n="index.uptime_title">Uptime</span></div>
      <div class="panel-value">
        <span class="info-value mono" id="hw-uptime"><?php echo htmlspecialchars($hw['system_uptime']); ?></span>
      </div>
      <div class="panel-sub" data-i18n="index.last_reboot">Last Reboot</div>
    </div>

    <!-- CPU Temperature -->
    <div class="panel <?php echo $tempPanelCls; ?>" id="cpu-temp-panel">
      <div class="panel-label panel-bar"><span class="block-icon">🌡️</span><span data-i18n="index.cpu_temp_title">CPU Temperature</span></div>
      <div class="panel-value <?php echo $tempVal; ?>" id="live-cpu-temp">
        <?php echo htmlspecialchars($hw['cpu_temp']); ?>°C
      </div>
      <div class="panel-sub" data-i18n="index.cpu_for_sbc">CPU for SBC</div>
    </div>

<!-- Horloge avec Date, Heure et Timezone -->
    <div class="panel clock-panel" id="clock-panel">
      <div class="panel-label panel-bar"><span class="block-icon">⌚</span><span id="clock-date">-- --- ----</span></div>
      <div class="panel-value clock-value" id="clock-time">--:--:--</div>
      <div class="panel-sub">
        <span class="clock-tz" id="clock-tz"><?php echo htmlspecialchars(TIMEZONE); ?></span>
      </div>
    </div>

  </div>

<!-- ══ GRID BOTTOM ══════════════════════════════════════════ -->
  <div class="grid-bottom">

      <!-- ══ Reflector Activity ══ -->
      <div class="panel" style="grid-row:span 2" id="reflector-panel">
        <div class="panel-label panel-bar">
          <span class="activity-icon">📋</span><span data-i18n="index.activity_title">Activity from SVXReflector</span>
        </div>

        <?php if (!$rfActive): ?>
          <div class="module-empty"><span data-i18n="index.reflector_not_configured">SVXReflector not configured in</span> <code>svxlink.conf</code>.</div>
        <?php elseif (empty($rfActivity)): ?>
          <div class="module-empty" data-i18n="index.no_activity">No activity found in the log.</div>
        <?php else: ?>
          <div class="el-log-wrap" style="overflow-x:auto;">
            <table class="rf-table" id="reflector-activity-table">
              <thead>
                <tr>
                  <th data-i18n="index.col_time">Time</th>
                  <th data-i18n="log.col_callsign">Callsign</th>
                  <th data-i18n="index.col_tg">TG #</th>
                  <th data-i18n="index.col_tgname">TG Name</th>
                  <th data-i18n="index.col_duration">Duration</th>
                </tr>
              </thead>
              <tbody id="reflector-activity-body">
                <?php foreach ($rfActivity as $entry): ?>
                  <tr class="<?php echo $entry['active'] ? 'rf-row-tx' : ''; ?>">

                    <td class="rf-td-time">
                      <?php if ($entry['active']): ?>
                        <span class="tx-indicator-dot" title="In transmission"></span>
                      <?php endif; ?>
                      <?php echo htmlspecialchars($entry['datetime']); ?>
                    </td>

                    <td class="rf-td-cs">
                      <?php if (!empty($entry['callsign'])): ?>
                        <?php if (isQrzCandidate($entry['callsign'])): ?>
                          <a href="https://www.qrz.com/db/<?php echo urlencode($entry['callsign_qrz']); ?>"
                             target="_blank" rel="noopener" class="callsign-link">
                            <?php echo htmlspecialchars($entry['callsign']); ?>
                          </a>
                        <?php else: ?>
                          <span class="<?php echo !empty($entry['is_gateway']) ? 'gateway-name' : ''; ?>">
                            <?php echo htmlspecialchars($entry['callsign']); ?>
                          </span>
                        <?php endif; ?>
                      <?php else: ?>
                        <span class="no-data">&mdash;</span>
                      <?php endif; ?>
                    </td>

                    <td class="rf-td-tg">
                      <?php echo $entry['tg'] !== '' ? htmlspecialchars($entry['tg']) : '<span class="no-data">&mdash;</span>'; ?>
                    </td>

                    <td class="rf-td-name">
                      <?php echo $entry['tg_name'] !== '' ? htmlspecialchars($entry['tg_name']) : '<span class="no-data">&mdash;</span>'; ?>
                    </td>

                    <td class="rf-td-dur"
                        data-start-ts="<?php echo $entry['active'] ? htmlspecialchars((string)$entry['timestamp']) : '0'; ?>"
                        data-active="<?php echo $entry['active'] ? '1' : '0'; ?>">
                      <?php echo $entry['active'] ? formatDuration((int)$entry['duration']) : formatDuration((int)$entry['duration']); ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

<!-- ══ Reflector & Talk Groups ═════════════════════════════════════ -->
      <div class="right-block">
      <div class="panel">
        <div class="panel-label panel-bar"><span class="block-icon">🌐</span><span data-i18n="index.reflector_tg_title">Reflector &amp; Talk Groups (TG)</span></div>
        <div class="node-list">
          <div class="node-row">
            <span class="node-name" data-i18n="index.callsign_on_reflector">Callsign on Reflector</span>
            <span class="node-ping callsign-reflector" id="callsign-reflector"><?php echo htmlspecialchars($callsignR); ?></span>
          </div>
          <div class="node-row" id="tg-default">
            <span class="node-name" data-i18n="index.tg_default_label">TG Default</span>
            <span class="node-ping tg-default"><?php if ($tgdefault): ?><?php echo htmlspecialchars($tgdefault); ?><?php else: ?><span data-i18n="index.tg_not_defined">Not Defined</span><?php endif; ?></span>
          </div>
          <div class="node-row" id="tg-monitor">
            <span class="node-name" data-i18n="index.tg_monitor_label">TG Monitor</span>
            <span class="node-ping tg-monitor">
              <?php if (!empty($tgmon)): ?>
                <?php echo htmlspecialchars(implode(', ', array_map('trim', $tgmon))); ?>
              <?php else: ?>
                <span data-i18n="index.no_monitored_tg">No monitored TG</span>
              <?php endif; ?>
              <?php if ($tgtmp): ?>
                [tmp: <?php echo htmlspecialchars($tgtmp); ?>]
              <?php endif; ?>
            </span>
          </div>
          <div class="node-row" id="tg-active">
            <span class="node-name" data-i18n="index.last_active_tg_label">Last Active TG</span>
            <span class="node-ping tg-active"><?php if ($tgselect): ?><?php echo htmlspecialchars($tgselect); ?><?php else: ?><span data-i18n="tg.no_active">No Active TG</span><?php endif; ?></span>
          </div>
          <div class="node-row" id="link-status">
            <span class="node-name" data-i18n="common.link_status">Link Status</span>
            <span class="node-ping link-status-value <?php echo ($linkStatus === 'Connected') ? 'status-connected' : 'status-disconnected'; ?>" id="link-status-value"><?php echo htmlspecialchars($linkStatus); ?></span>
          </div>
        </div>
      </div>

<!-- ══ EchoLink — affiché uniquement si ModuleEchoLink est actif ══ -->
      <?php if ($elModActive): ?>
      <div class="panel" id="echolink-panel">
        <?php if (!empty($elConfError)): ?>
          <div class="error-msg"><?php echo $elConfError; ?></div>
        <?php endif; ?>
        <div class="panel-label panel-bar"><span class="block-icon">🔗</span><span data-i18n="echolink.title">EchoLink NODE Information</span></div>
        <div class="node-list">

          <div class="node-row">
            <span class="node-name" data-i18n="index.node_callsign_label">Node Callsign</span>
            <span class="node-ping">
              <?php $elBase = preg_replace('/-[LR]$/i', '', $elCallsign); ?>
              <span id="el-callsign">
                <a href="https://www.qrz.com/db/<?php echo urlencode($elBase); ?>"
                   target="_blank" class="callsign-link">
                  <?php echo str_replace('0', '&Oslash;', htmlspecialchars($elCallsign)); ?>
                </a>
              </span>
            </span>
          </div>

          <div class="node-row">
            <span class="node-name" data-i18n="index.node_location_label">Node Localisation</span>
            <span class="node-ping" id="el-location"><?php echo htmlspecialchars($elLocation); ?></span>
          </div>

          <div class="node-row">
            <span class="node-name" data-i18n="index.node_sysop_label">Node Sysop</span>
            <span class="node-ping" id="el-sysop"><?php echo htmlspecialchars($elSysopName); ?></span>
          </div>

          <div class="node-row">
            <span class="node-name" data-i18n="index.nodes_connected_label">Nodes Connected</span>
            <span class="node-ping" id="el-nodes">
              <?php if (!empty($elUsers)): ?>
                <?php foreach ($elUsers as $ru):
                  $ruBase = preg_replace('/-[LR]$/i', '', $ru); ?>
                  <a href="https://www.qrz.com/db/<?php echo urlencode($ruBase); ?>"
                     target="_blank" class="callsign-link">
                    <?php echo str_replace('0', '&Oslash;', htmlspecialchars($ru)) . ' '; ?>
                  </a>
                <?php endforeach; ?>
              <?php else: ?>
                <span data-i18n="index.no_node_connected">NO NODE CONNECTED</span>
              <?php endif; ?>
            </span>
          </div>
          <div class="node-row">
            <span class="node-name" data-i18n="index.connections_count_label">Number of Connections</span>
            <span class="node-ping" id="el-count"><?php echo count($elUsers); ?></span>
          </div>

          <div class="node-row">
            <span class="node-name" data-i18n="index.connected_mode_label">Connected</span>
            <span class="node-ping el-connect-mode" id="el-proxy">
              <?php echo $elProxy !== '' ? 'via PROXY ' . htmlspecialchars($elProxy) : 'Direct'; ?>
            </span>
          </div>

          <div class="node-row">
            <span class="node-name" data-i18n="common.link_status">Link Status</span>
            <?php
                $elLinkClass = ($elStatusLink === 'Connected') ? 'status-connected'
                             : (($elStatusLink === 'Banned') ? 'status-banned' : 'status-disconnected');
            ?>
            <span class="node-ping link-status-value <?php echo $elLinkClass; ?>" id="el-link-status">
              <?php echo htmlspecialchars($elStatusLink); ?>
            </span>
          </div>

        </div>
      </div>
      <?php endif; ?>
<!-- ══ Hardware Info ════════════════════════════ -->
      <div class="panel">
        <div class="panel-label panel-bar"><span class="block-icon">📟</span><span data-i18n="index.hw_title">Hardware Info</span></div>

        <div class="stat-row">
          <span class="stat-key" data-i18n="index.hostname">Hostname</span>
          <span class="stat-val" id="hw-hostname"><?php echo htmlspecialchars($hw['hostname']); ?></span>
        </div>

        <div class="stat-row">
          <span class="stat-key" data-i18n="index.local_ip">Local IP</span>
          <span class="stat-val" id="hw-local-ip"><?php echo htmlspecialchars($hw['local_ip']); ?></span>
        </div>

        <div class="stat-row">
          <span class="stat-key" data-i18n="index.architecture">Architecture</span>
          <span class="stat-val" id="hw-cpu-arch"><?php echo htmlspecialchars($hw['cpu_arch']); ?></span>
        </div>

        <div class="stat-row">
          <span class="stat-key" data-i18n="index.kernel">Kernel</span>
          <span class="stat-val" id="hw-kernel"><?php echo htmlspecialchars($hw['kernel']); ?></span>
        </div>

        <div class="stat-row">
          <span class="stat-key" data-i18n="index.linux">OS Linux</span>
          <span class="stat-val" id="hw-linux"><?php echo htmlspecialchars($hw['linux_version']); ?></span>
        </div>

        <div class="stat-row">
          <span class="stat-key" data-i18n="index.svxlink_label">SvxLink version</span>
          <span class="stat-val" id="hw-svxlink"><?php echo htmlspecialchars($hw['svxlink_version']); ?></span>
        </div>

        <div class="stat-row">
          <span class="stat-key" data-i18n="index.last_svx_restart">Last SvxLink Restart</span>
          <span class="stat-val" id="svx-uptime"><?php echo htmlspecialchars($svxUptime !== '' ? $svxUptime : '--'); ?></span>
        </div>

        <div class="stat-row">
          <span class="stat-key" data-i18n="index.cpu_cores">CPU Cores</span>
          <span class="stat-val" id="hw-cpu-cores"><?php echo htmlspecialchars($hw['cpu_cores'] ?? 'N/A'); ?> <span data-i18n="index.cores_suffix">Cores</span></span>
        </div>

        <div class="stat-row">
          <span class="stat-key" data-i18n="index.cpu_temp_title">CPU Temperature</span>
          <span class="stat-val <?php echo $tempVal; ?>" id="hw-cpu-temp">
            <?php echo htmlspecialchars($hw['cpu_temp']); ?>°C
          </span>
        </div>

        <!-- Barre CPU -->
        <div class="progress-wrap">
          <div class="progress-item">
            <div class="progress-label-row">
              <span data-i18n="index.cpu_usage_label">CPU Usage</span>
              <span id="hw-cpu-usage-bar"><?php echo htmlspecialchars($hw['cpu_usage']); ?>%</span>
            </div>
            <div class="progress-track">
              <div class="progress-fill <?php echo $cpu_bar_class; ?>"
                   id="cpu-bar"
                   style="width:<?php echo min(100, $hw_cpu_usage); ?>%"
                   role="progressbar"></div>
            </div>
          </div>
        </div>

        <!-- Barre RAM -->
        <div class="progress-wrap">
          <div class="progress-item">
            <div class="progress-label-row">
              <span data-i18n="index.mem_usage_label">Memory Usage</span>
              <span id="hw-ram-bar-label"><?php echo htmlspecialchars($hw['ram']['used'] ?? '0 MB'); ?> / <?php echo htmlspecialchars($hw['ram']['total'] ?? '0 MB'); ?></span>
            </div>
            <div class="progress-track">
              <div class="progress-fill <?php echo $ram_bar_class; ?>"
                   id="ram-bar"
                   style="width:<?php echo min(100, $hw_ram_percent); ?>%"
                   role="progressbar"></div>
            </div>
          </div>
        </div>

        <!-- Barre Disk -->
        <div class="progress-wrap">
          <div class="progress-item">
            <div class="progress-label-row">
              <span data-i18n="index.disk_usage_label">Disk Usage</span>
              <span id="hw-disk-bar-label"><?php echo htmlspecialchars($hw['disk']['used'] ?? '0 GB'); ?> / <?php echo htmlspecialchars($hw['disk']['total'] ?? '0 GB'); ?></span>
            </div>
            <div class="progress-track">
              <div class="progress-fill <?php echo $disk_bar_class; ?>"
                   id="disk-bar"
                   style="width:<?php echo min(100, $hw_disk_percent); ?>%"
                   role="progressbar"></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . '/include/footer.php'; ?>

<?php include __DIR__ . '/include/dash_config.php'; ?>
<script src="scripts/i18n.js"></script>
<script src="scripts/main.js"></script>
</body>
</html>
