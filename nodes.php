<?php
/**
 * SvxLink Dashboard by CN8VX and SP2ONG © 2026
 * Connected Nodes display page
 */
require_once __DIR__ . '/include/infosvx.php';
//require_once __DIR__ . '/include/hardware_info.php';

//$hw             = getAllHardwareInfo();
$repeaterData   = getRepeaterStatus();
$repeaterStatus = $repeaterData['status'];
$rsDesc         = $repeaterData['description'];
$hasLogo        = (LOGO_PATH !== '' && file_exists(__DIR__ . '/' . LOGO_PATH));

$connectedNodes = getSVXReflectorNodes();
$activeTalkers  = getActiveTalkerCallsigns();
$totalNodes     = count($connectedNodes);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connected Nodes - SvxLink <?php echo htmlspecialchars($repeaterType ?? ''); ?> Repeater Dashboard - <?php echo htmlspecialchars($CALLSIGN); ?></title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" href="css/style.css">
    <script src="scripts/main.js"></script>
</head>
<body>
<?php $activeNav = 'nodes'; include __DIR__ . '/include/navbar.php'; ?>
<?php include __DIR__ . '/include/header.php'; ?>

<div class="module-panel" style="margin: 10px;">
     <div class="panel-label panel-bar"><span class="block-icon">🌐</span>Connected Nodes to SVXReflector (<span id="nodes-count"><?php echo $totalNodes; ?></span>)</div>
    <div class="module-list" id="nodes-live" style="padding: 20px 10px;justify-content: center;">
        <?php if (!empty($connectedNodes)): ?>
            <?php foreach ($connectedNodes as $node): ?>
                <span class="node-badge<?php echo in_array($node, $activeTalkers, true) ? ' transmitting' : ''; ?>">
                    <?php echo htmlspecialchars($node); ?>
                </span>
            <?php endforeach; ?>
        <?php else: ?>
            <span class="module-empty">No nodes connected</span>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/include/footer.php'; ?>
</body>
</html>
