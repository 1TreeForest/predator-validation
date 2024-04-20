<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: user_facebook_include_var.php
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
defined('IN_FUSION') || exit;

// Version of the user fields api
$user_field_api_version = "1.01.00";
$user_field_name = $locale['uf_facebook'];
$user_field_desc = $locale['uf_facebook_desc'];
$user_field_dbname = "user_facebook";
$user_field_group = 3;
$user_field_dbinfo = "VARCHAR(50) NOT NULL DEFAULT ''";
$user_field_default = '';
$user_field_options = '';
$user_field_error = '';
$user_field_config = '';
