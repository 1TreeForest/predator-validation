<?php
/**
 * Side Box Template
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2021 Jan 21 Modified in v1.5.8-alpha $
 */
  $content ="";

  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">';
  if ($_SESSION['cart']->count_contents() > 0) {
  $content .= '<div id="cartBoxListWrapper">' . "\n" . '<ul class="list-links">' . "\n";
    $products = $_SESSION['cart']->get_products();
    foreach ($products as $product) {
      $content .= '<li>';

      $css_class = 'cartOldItem';
      if (isset($_SESSION['new_products_id_in_cart']) && ($_SESSION['new_products_id_in_cart'] == $product['id'])) {
        $css_class = 'cartNewItem';
        $_SESSION['new_products_id_in_cart'] = '';
      }

      $content .= '<span class="' . $css_class . '">' . $product['quantity'] . CART_QUANTITY_SUFFIX . '</span>';

      $content .= '<a href="' . zen_href_link(zen_get_info_page($product['id']), 'products_id=' . $product['id']) . '">';
      $content .= '<span class="' . $css_class . '">' . $product['name'] . '</span></a>';

      $content .= '</li>' . "\n";
    }
    $content .= '</ul>' . "\n" . '</div>';
  } else {
    $content .= '<div id="cartBoxEmpty">' . BOX_SHOPPING_CART_EMPTY . '</div>';
  }

  if ($_SESSION['cart']->count_contents() > 0) {
    $content .= '<hr>';
    $content .= '<div class="cartBoxTotal">' . $currencies->format($_SESSION['cart']->show_total()) . '</div>';
    $content .= '<br class="clearBoth">';
  }

  if (zen_is_logged_in() && !zen_in_guest_checkout()) {
    $gv_query = "select amount
                 from " . TABLE_COUPON_GV_CUSTOMER . "
                 where customer_id = '" . $_SESSION['customer_id'] . "'";
   $gv_result = $db->Execute($gv_query);

    if ($gv_result->RecordCount() && $gv_result->fields['amount'] > 0 ) {
      $content .= '<div id="cartBoxGVButton"><a href="' . zen_href_link(FILENAME_GV_SEND, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_SEND_A_GIFT_CERT , BUTTON_SEND_A_GIFT_CERT_ALT) . '</a></div>';
      $content .= '<div id="cartBoxVoucherBalance">' . VOUCHER_BALANCE . $currencies->format($gv_result->fields['amount']) . '</div>';
    }
  }
  $content .= '</div>';
