<?php
/**
 * GV redeem
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Oct 30 Modified in v1.5.8-alpha $
 */
$zco_notifier->notify('NOTIFY_HEADER_START_GV_REDEEM');

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$_GET['gv_no'] = zen_sanitize_string(trim($_GET['gv_no']));

// if the customer is not logged on, redirect them to the login page
if (!zen_is_logged_in() || zen_in_guest_checkout()) {
  $_SESSION['navigation']->set_snapshot();
  $messageStack->add_session('login', ERROR_GV_CREATE_ACCOUNT, 'error');
  zen_redirect(zen_href_link(FILENAME_LOGIN, (isset($_GET['gv_no']) ? 'gv_no=' . preg_replace('/[^0-9.,%]/', '', $_GET['gv_no']) : '' ), 'SSL'));
}

$message = TEXT_INVALID_GV;


// check for a voucher number in the url
if (isset($_GET['gv_no'])) {
  $error = true;
  $gv_query = "SELECT c.coupon_id, c.coupon_amount
               FROM " . TABLE_COUPONS . " c, " . TABLE_COUPON_EMAIL_TRACK . " et
               WHERE coupon_code = :couponCode
               AND c.coupon_id = et.coupon_id
               AND c.coupon_type = 'G'";

  $gv_query = $db->bindVars($gv_query, ':couponCode', $_GET['gv_no'], 'string');
  $coupon = $db->Execute($gv_query);

  if ($coupon->RecordCount() >0) {
    $redeem_query = "SELECT coupon_id
                     FROM ". TABLE_COUPON_REDEEM_TRACK . "
                     WHERE coupon_id = :couponID";

    $redeem_query = $db->bindVars($redeem_query, ':couponID', $coupon->fields['coupon_id'], 'integer');
    $redeem = $db->Execute($redeem_query);

    if ($redeem->RecordCount() == 0 ) {
      $_SESSION['gv_id'] = $coupon->fields['coupon_id'];
      $message = sprintf(TEXT_VALID_GV, $currencies->format($coupon->fields['coupon_amount']));
      $error = false;
    } else {
      $error = true;
    }
  }
} else {
  zen_redirect(zen_href_link(FILENAME_DEFAULT));
}
if (!$error) {
  // Update redeem status
  $gv_query = "INSERT INTO  " . TABLE_COUPON_REDEEM_TRACK . "(coupon_id, customer_id, redeem_date, redeem_ip)
               VALUES (:couponID, :customersID, now(), :remoteADDR)";

  $gv_query = $db->bindVars($gv_query, ':customersID', $_SESSION['customer_id'], 'integer');
  $gv_query = $db->bindVars($gv_query, ':couponID', $coupon->fields['coupon_id'], 'integer');
  $gv_query = $db->bindVars($gv_query, ':remoteADDR', zen_get_ip_address(), 'string');
  $db->Execute($gv_query);

  $gv_update = "UPDATE " . TABLE_COUPONS . "
                SET coupon_active = 'N'
                WHERE coupon_id = :couponID";

  $gv_update = $db->bindVars($gv_update, ':couponID', $coupon->fields['coupon_id'], 'integer');
  $db->Execute($gv_update);

  zen_gv_account_update($_SESSION['customer_id'], $_SESSION['gv_id']);
  $_SESSION['gv_id'] = '';
}

$breadcrumb->add(NAVBAR_TITLE);
