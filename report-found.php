<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
requireUser();
$lostId = (int)($_GET['lost_id'] ?? 0);
redirect(appUrl('report-item.php?type=found' . ($lostId ? '&lost_id=' . $lostId : '')));
