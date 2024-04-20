<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: torvista 2022 Feb 14 New in v1.5.8-alpha $
*/

$define = [
    'EMAIL_LOGO_FILENAME' => 'header.jpg',
    'EMAIL_LOGO_WIDTH' => '550',
    'EMAIL_LOGO_HEIGHT' => '110',
    'EMAIL_LOGO_ALT_TITLE_TEXT' => 'Zen Cart! The Art of E-commerce',
    'EMAIL_EXTRA_HEADER_INFO' => '',
    'EMAIL_ORDER_UPDATE_MESSAGE' => '',
    'OFFICE_FROM' => 'From:',
    'OFFICE_EMAIL' => 'E-mail:',
    'OFFICE_USE' => 'Office Use Only:',
    'OFFICE_LOGIN_NAME' => 'Login Name:',
    'OFFICE_LOGIN_EMAIL' => 'Login e-mail:',
    'OFFICE_LOGIN_PHONE' => 'Telephone:',
    'OFFICE_IP_ADDRESS' => 'IP Address:',
    'OFFICE_HOST_ADDRESS' => 'Host Address:',
    'OFFICE_DATE_TIME' => 'Date and Time:',
    'EMAIL_DISCLAIMER' => "\n" . 'This email address was given to us by you or by one of our customers. If you feel that you have received this email in error, please send an email to %s',
    'EMAIL_SPAM_DISCLAIMER' => '',
    'EMAIL_FOOTER_COPYRIGHT' => 'Copyright (c) ' . date('Y') . ' <a href="https://www.zen-cart.com">Zen Cart</a>. Powered by <a href="https://www.zen-cart.com">Zen Cart</a>',
    'SEND_EXTRA_GV_ADMIN_EMAILS_TO_SUBJECT' => '[GV ADMIN SENT]',
    'SEND_EXTRA_DISCOUNT_COUPON_ADMIN_EMAILS_TO_SUBJECT' => '[DISCOUNT COUPONS]',
    'SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO_SUBJECT' => '[ORDERS STATUS]',
    'TEXT_UNSUBSCRIBE' => "\n\nTo unsubscribe from future newsletter and promotional mailings, simply click on the following link: \n",
    'OFFICE_IP_TO_HOST_ADDRESS' => 'Disabled',
    'TEXT_EMAIL_SUBJECT_ADMIN_USER_ADDED' => 'Admin Alert: New admin user added.',
    'TEXT_EMAIL_MESSAGE_ADMIN_USER_ADDED' => 'Administrative alert: A new admin user (%s) has been ADDED to your store by %s.' . "\n\n" . 'If you or an authorized administrator did not initiate this change, it is advised that you verify your site security immediately.',
    'TEXT_EMAIL_SUBJECT_ADMIN_USER_DELETED' => 'Admin Alert: An admin user has been deleted.',
    'TEXT_EMAIL_MESSAGE_ADMIN_USER_DELETED' => 'Administrative alert: An admin user (%s) has been DELETED from your store by %s.' . "\n\n" . 'If you or an authorized administrator did not initiate this change, it is advised that you verify your site security immediately.',
    'TEXT_EMAIL_SUBJECT_ADMIN_USER_CHANGED' => 'Admin Alert: Admin user details have been changed.',
    'TEXT_EMAIL_ALERT_ADM_EMAIL_CHANGED' => 'Admin alert: Admin user (%s) email address has been changed from (%s) to (%s) by (%s)',
    'TEXT_EMAIL_ALERT_ADM_NAME_CHANGED' => 'Admin alert: Admin user (%s) username has been changed from (%s) to (%s) by (%s)',
    'TEXT_EMAIL_ALERT_ADM_PROFILE_CHANGED' => 'Admin alert: Admin user (%s) security profile has been changed from (%s) to (%s) by (%s)',
];

return $define;
