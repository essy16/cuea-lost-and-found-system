<?php
/*
 * Basic email notification settings.
 * Set MAIL_ENABLED to true on a live server that supports PHP mail().
 */
if (!defined('MAIL_ENABLED')) define('MAIL_ENABLED', false);
if (!defined('MAIL_FROM')) define('MAIL_FROM', 'no-reply@cuea.edu');
if (!defined('MAIL_FROM_NAME')) define('MAIL_FROM_NAME', 'CUEA Lost & Found');
?>