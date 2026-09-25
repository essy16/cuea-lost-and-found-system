<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
if (isAdminLoggedIn()) redirect(appUrl('report-item.php?type=lost'));
requireUser();
redirect(appUrl('report-item.php?type=lost'));
