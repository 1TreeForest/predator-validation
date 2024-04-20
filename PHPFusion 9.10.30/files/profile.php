<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: profile.php
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
require_once __DIR__.'/maincore.php';
require_once THEMES.'templates/header.php';
$locale = fusion_get_locale('', LOCALE.LOCALESET."user_fields.php");
$settings = fusion_get_settings();
if (isset($_GET['lookup']) && isnum($_GET['lookup'])) {
    require_once THEMES."templates/global/profile.tpl.php";

    if (!iMEMBER && $settings['hide_userprofiles'] == 1 || user_blacklisted($_GET['lookup'])) {
        redirect(BASEDIR."index.php");
    }
    $user_status = " AND (user_status='0' OR user_status='3' OR user_status='7')";
    if (iADMIN) {
        $user_status = "";
    }
    $user_data = [];
    $result = dbquery("SELECT u.*, s.suspend_reason
        FROM ".DB_USERS." u
        LEFT JOIN ".DB_SUSPENDS." s ON u.user_id=s.suspended_user
        WHERE user_id=:uid".$user_status."
        ORDER BY suspend_date DESC
        LIMIT 1", [':uid' => intval($_GET['lookup'])]);
    if (dbrows($result)) {
        $user_data = dbarray($result);
    } else {
        redirect(BASEDIR."index.php");
    }
    set_title($locale['u103'].$locale['global_201'].$user_data['user_name']);
    if (iADMIN && checkrights("UG") && $_GET['lookup'] != $user_data['user_id']) {
        if ((isset($_POST['add_to_group'])) && (isset($_POST['user_group']) && isnum($_POST['user_group']))) {
            if (!preg_match("(^\.{$_POST['user_group']}$|\.{$_POST['user_group']}\.|\.{$_POST['user_group']}$)", $user_data['user_groups'])) {
                $result = dbquery("UPDATE ".DB_USERS." SET user_groups='".$user_data['user_groups'].".".$_POST['user_group']."'
                    WHERE user_id='".$_GET['lookup']."'
                ");
            }
            redirect(FUSION_SELF."?lookup=".$_GET['lookup']);
        }
    }
    $userFields = new PHPFusion\UserFields();
    $userFields->userData = $user_data;
    $userFields->showAdminOptions = TRUE;
    $userFields->method = 'display';
    $userFields->plugin_folder = [INCLUDES."user_fields/", INFUSIONS];
    $userFields->plugin_locale_folder = LOCALE.LOCALESET."user_fields/";
    $userFields->displayProfileOutput();

    PHPFusion\OpenGraph::ogUserProfile($_GET['lookup']);

} else if (isset($_GET['group_id']) && isnum($_GET['group_id'])) {
    /*
     * Show group
     */
    \PHPFusion\UserGroups::getInstance()->setGroup($_GET['group_id'])->showGroup();
} else {
    redirect(BASEDIR."index.php");
}
require_once THEMES.'templates/footer.php';
