<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: footer.php
| Author: Core Development Team
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

use PHPFusion\Panels;

defined('IN_FUSION') || exit;

if (file_exists(INCLUDES."footer_includes.php")) {
    require_once INCLUDES."footer_includes.php";
}

Panels::getInstance()->getSitePanel();

define("CONTENT", ob_get_clean()); //ob_start() called in header.php

require_once __DIR__.'/cron.php';

// Load layout
if (defined('ADMIN_PANEL')) {
    require_once __DIR__.'/admin_layout.php';
} else {
    require_once __DIR__.'/layout.php';
}
// Catch the output
$output = ob_get_contents();
if (ob_get_length() !== FALSE) {
    ob_end_clean();
}
// Do the final output manipulation
$output = handle_output($output);
// Search in output and replace normal links with SEF links
if (!isset($_GET['aid'])) {
    if (fusion_get_settings('site_seo')) {
        \PHPFusion\Rewrite\Permalinks::getPermalinkInstance()->handleUrlRouting($output);
        $output = \PHPFusion\Rewrite\Permalinks::getPermalinkInstance()->getOutput($output);
    }
}
if (isset($permalink)) {
    unset($permalink);
}
// Check all loaded locale files
//print_p(\PHPFusion\Locale::get_loaded_files());
// Output the final complete page content
echo $output;
remove_notice();
if ((ob_get_length() > 0)) { // length is a number
    ob_end_flush();
}
