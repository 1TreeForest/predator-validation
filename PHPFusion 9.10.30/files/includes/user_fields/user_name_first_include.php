<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: user_name_first_include.php
| Author: Chubatyj Vitalij (Rizado)
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

if ($profile_method == "input") {
    $options += ['inline' => TRUE, 'max_length' => 20];
    $user_fields = form_text('user_name_first', $locale['uf_name_first'], $field_value, $options);
} else if ($profile_method == "display") {
    $user_fields = [
        'title' => $locale['uf_name_first'],
        'value' => $field_value ?: ''
    ];
}
