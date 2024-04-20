<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: user_facebook_include.php
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

$icon = "<img src='".IMAGES."user_fields/social/facebook.svg' title='Facebook' alt='Facebook'/>";
// Display user field input
if ($profile_method == "input") {
    $user_fields = form_text('user_facebook', $locale['uf_facebook'], $field_value, [
            'inline'      => TRUE,
            'placeholder' => $locale['uf_facebook_placeholder'],
            'error_text'  => $locale['uf_facebook_error'],
            'label_icon'  => $icon,
        ] + $options);
    // Display in profile
} else if ($profile_method == "display") {
    $link = '';
    if ($field_value) {
        $link = !preg_match("@^http(s)?\:\/\/@i", $field_value) ? "https://www.facebook.com/".$field_value : $field_value;
        $field_value = (fusion_get_settings('index_url_userweb') ? "" : "<!--noindex-->")."<a href='".$link."' title='".$field_value."' ".(fusion_get_settings('index_url_userweb') ? "" : "rel='nofollow noopener noreferrer' ")."target='_blank'>".$locale['uf_facebook_desc']."</a>".(fusion_get_settings('index_url_userweb') ? "" : "<!--/noindex-->");
    }
    $user_fields = [
        'icon'  => $icon,
        'link'  => $link,
        'type'  => 'social',
        'title' => $locale['uf_facebook'],
        'value' => $field_value ?: ''
    ];
}
