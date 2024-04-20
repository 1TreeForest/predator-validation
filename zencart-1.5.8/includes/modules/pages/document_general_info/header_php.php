<?php
/**
 * document_general header_php.php
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Jul 10 Modified in v1.5.8-alpha $
 */

// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_DOCUMENT_GENERAL_INFO');

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));

$product_info = zen_get_product_details($products_id_current = (!empty($_GET['products_id']) ? (int)$_GET['products_id'] : 0));

zen_product_set_header_response($products_id_current, $product_info);

// This should be last line of the script:
$zco_notifier->notify('NOTIFY_HEADER_END_DOCUMENT_GENERAL_INFO');
