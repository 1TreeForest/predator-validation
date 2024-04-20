<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: user_twitter_include.php
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

$icon = "<img src='".IMAGES."user_fields/social/twitter.svg' title='Twitter' alt='Twitter'/>";
// Display user field input
if ($profile_method == "input") {
    $options = [
            'inline'           => TRUE,
            'max_length'       => 16,
            'regex'            => '[a-z](?=[\w.]{3,31}$)\w*\.?\w*',
            'regex_error_text' => $locale['uf_twitter_error_1'],
            'error_text'       => $locale['uf_twitter_error'],
            'placeholder'      => $locale['uf_twitter_id'],
            'label_icon'       => $icon,
        ] + $options;
    $user_fields = form_text('user_twitter', $locale['uf_twitter'], $field_value, $options);
    // Display in profile
} else if ($profile_method == "display") {
    $link = '';
    if ($field_value) {
        $link = !preg_match("@^http(s)?\:\/\/@i", $field_value) ? "https://www.twitter.com/".$field_value : $field_value;
        $field_value = (fusion_get_settings('index_url_userweb') ? "" : "<!--noindex-->")."<a href='".$link."' title='".$field_value."' ".(fusion_get_settings('index_url_userweb') ? "" : "rel='nofollow noopener noreferrer' ")."target='_blank'>".$locale['uf_twitter_desc']."</a>".(fusion_get_settings('index_url_userweb') ? "" : "<!--/noindex-->");
    }
    $user_fields = [
        'icon'  => $icon,
        'link'  => $link,
        'type'  => 'social',
        'title' => $locale['uf_twitter'],
        'value' => $field_value ?: ''
    ];
}
