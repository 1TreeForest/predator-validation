<?php
/**
 * Initializes non-database constants that were previously set in language modules,
 * overridable via site-specific /init_includes processing.  See
 * /includes/init_includes/dist-init_site_specific_non_db_settings.php.
 *
 * Note: These settings apply to both the storefront and the admin!
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: lat9 2022 Jul 23 New in v1.5.8-alpha2 $
 */
// -----
// If the site has provided a set of overrides for these base values, they will
// be used.
//
$site_specific_non_db_settings = [];
if (is_file(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'init_includes/init_site_specific_non_db_settings.php')) {
    require DIR_FS_CATALOG . DIR_WS_INCLUDES . 'init_includes/init_site_specific_non_db_settings.php';
}

$non_db_settings = [
    // -----
    // Storefront settings.
    //
    'CART_SHIPPING_METHOD_ZIP_REQUIRED' => 'true',  //- Either 'true' or 'false'.  Used by tpl_modules_shipping_estimator.php

    // -----
    // Admin settings.
    //
    'MAX_DISPLAY_RESTRICT_ENTRIES' => 10,           //- Note, an integer value!.  Used by /admin/coupon_restrict.php
    'WARN_DATABASE_VERSION_PROBLEM' => 'true',      //- Either 'true' or 'false'.  Used by /admin/init_includes/init_errors.php

];
$non_db_settings = array_merge($non_db_settings, $site_specific_non_db_settings);

foreach ($non_db_settings as $key => $value) {
    zen_define_default($key, $value);
}
