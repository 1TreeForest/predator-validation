<?php

/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: lat9 2022 Jul 31 Modified in v1.5.8-alpha2 $
 */
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}
if (isset($_POST['products_id'], $_POST['categories_id'])) {
    $products_id = (int)$_POST['products_id'];
    $categories_id = (int)$_POST['categories_id'];

    if ($_POST['copy_as'] === 'link') {
        if ($categories_id != $current_category_id) {
            zen_link_product_to_category($products_id, $categories_id);
            zen_record_admin_activity('Product ' . $products_id . ' copied as link to category ' . $categories_id . ' via admin console.', 'info');
        } else {
            $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
        }
    } elseif ($_POST['copy_as'] === 'duplicate') {

        $product = zen_get_product_details($products_id);

        // fix Product copy from if Unit is 0
        if ($product->fields['products_quantity_order_units'] == 0) {
            $sql = "UPDATE " . TABLE_PRODUCTS . "
                    SET products_quantity_order_units = 1
                    WHERE products_id = " . $products_id;
            $results = $db->Execute($sql);
            $product->fields['products_quantity_order_units'] = 1;
        }
        // fix Product copy from if Minimum is 0
        if ($product->fields['products_quantity_order_min'] == 0) {
            $sql = "UPDATE " . TABLE_PRODUCTS . "
                    SET products_quantity_order_min = 1
                    WHERE products_id = " . $products_id;
            $results = $db->Execute($sql);
            $product->fields['products_quantity_order_min'] = 1;
        }

        $sql_data_array = [];
        $separately_updated_fields = [
          'products_id',
          'products_status',
          'products_last_modified',
          'products_date_added',
          'products_date_available',
        ];
        $casted_fields = [
          'products_quantity' =>  'float',
          'products_price' =>  'float',
          'products_weight' =>  'float',
          'products_tax_class_id' =>  'int',
          'manufacturers_id' =>  'int',
          'product_is_free' =>  'int',
          'product_is_call' =>  'int',
          'products_quantity_mixed' =>  'int',
        ];

        // -----
        // Give an observer the chance to add any customized fields to the two arrays above.
        //
        $zco_notifier->notify('NOTIFY_MODULES_COPY_PRODUCT_CONFIRM_DUPLICATE_FIELDS', $product, $separately_updated_fields, $casted_fields);

        foreach ($product->fields as $key => $value) {
            if (in_array($key, $separately_updated_fields)) {
                continue;
            }

            $value = zen_db_input($value);
            if (array_key_exists($key, $casted_fields)) {
                if ($casted_fields[$key] === 'int') {
                    $sql_data_array[$key] = (int)$value;
                } elseif ($casted_fields[$key] === 'float') {
                    $sql_data_array[$key] = (float)$value;
                } else {
                    $sql_data_array[$key] = (!zen_not_null($value) || $value == '' || $value == 0) ? 0 : $value;
                }
            } else {
                $sql_data_array[$key] = $value;
            }
        }

        // separately_updated_fields - last_modified and products_id are skipped
        $sql_data_array['products_status'] = 0;
        $sql_data_array['products_date_added'] = 'now()';
        $sql_data_array['products_date_available'] = (!empty($product->fields['products_date_available']) ? zen_db_input($product->fields['products_date_available']) : 'null');

        $sql_data_array['master_categories_id'] = $categories_id;

        // skip fields that belong to TABLE_PRODUCTS_DESCRIPTION and TABLE_PRODUCT_TYPES
        $fields_to_skip = [
            'language_id',
            'products_name',
            'products_description',
            'products_url',
            'products_viewed', // old, but must be excluded if present
            'allow_add_to_cart',
            'type_handler',
        ];
        foreach ($fields_to_skip as $field) {
            unset($sql_data_array[$field]);
        }

        // store new record
        zen_db_perform(TABLE_PRODUCTS, $sql_data_array);

        $dup_products_id = (int)$db->insert_ID();

        $descriptions = $db->Execute("SELECT language_id, products_name, products_description, products_url
                                      FROM " . TABLE_PRODUCTS_DESCRIPTION . "
                                      WHERE products_id = " . $products_id);
        foreach ($descriptions as $description) {
            $name = TEXT_DUPLICATE_IDENTIFIER . " " . $description['products_name'];
            $maxlen = zen_field_length(TABLE_PRODUCTS_DESCRIPTION, 'products_name');
            if (strlen($name) > $maxlen) {
               $name = substr($name, 0, $maxlen-1);
            }
            $sql_data_array = [
                  'products_id' => $dup_products_id,
                  'language_id' => (int)$description['language_id'],
                  'products_name' => $name,
                  'products_description' => $description['products_description'],
                  'products_url' => $description['products_url'],
            ];
            zen_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
        }

        zen_link_product_to_category($dup_products_id, $categories_id);

// FIX HERE
/////////////////////////////////////////////////////////////////////////////////////////////

// copy attributes to Duplicate
        if (!empty($_POST['copy_attributes']) && $_POST['copy_attributes'] === 'copy_attributes_yes') {

            if (DOWNLOAD_ENABLED === 'true') {
                $copy_attributes_include_downloads = '1';
                $copy_attributes_include_filename = '1';
            } else {
                $copy_attributes_include_downloads = '0';
                $copy_attributes_include_filename = '0';
            }

            $copy_result = zen_copy_products_attributes($products_id, $dup_products_id);
            if ($copy_result === true) {
                $messageStack->add_session(sprintf(TEXT_COPY_AS_DUPLICATE_ATTRIBUTES, $products_id, $dup_products_id), 'success');
            }
        }

// copy meta tags to Duplicate
        if (!empty($_POST['copy_metatags']) && $_POST['copy_metatags'] === 'copy_metatags_yes') {
            $metatags_status = $db->Execute("SELECT metatags_title_status, metatags_products_name_status, metatags_model_status, metatags_price_status, metatags_title_tagline_status
                                             FROM " . TABLE_PRODUCTS . "
                                             WHERE products_id = '" . $products_id . "'");

            $db->Execute("UPDATE " . TABLE_PRODUCTS . " SET
                metatags_title_status = '" . zen_db_input($metatags_status->fields['metatags_title_status']) . "',
                metatags_products_name_status = '" . zen_db_input($metatags_status->fields['metatags_products_name_status']) . "',
                metatags_model_status = '" . zen_db_input($metatags_status->fields['metatags_model_status']) . "',
                metatags_price_status= '" . zen_db_input($metatags_status->fields['metatags_price_status']) . "',
                metatags_title_tagline_status = '" . zen_db_input($metatags_status->fields['metatags_title_tagline_status']) . "'
                WHERE products_id = " . $dup_products_id);

            $metatags_descriptions = $db->Execute("SELECT language_id, metatags_title, metatags_keywords, metatags_description
                                                   FROM " . TABLE_META_TAGS_PRODUCTS_DESCRIPTION . "
                                                   WHERE products_id = " . $products_id);

            while (!$metatags_descriptions->EOF) {//one row per language
                $db->Execute("INSERT INTO " . TABLE_META_TAGS_PRODUCTS_DESCRIPTION . " (products_id, language_id, metatags_title, metatags_keywords, metatags_description)
                        VALUES (
                        '" . $dup_products_id . "',
                        '" . (int)$metatags_descriptions->fields['language_id'] . "',
                        '" . zen_db_input($metatags_descriptions->fields['metatags_title']) . "',
                        '" . zen_db_input($metatags_descriptions->fields['metatags_keywords']) . "',
                        '" . zen_db_input($metatags_descriptions->fields['metatags_description']) . "')");

                $messageStack->add_session(sprintf(TEXT_COPY_AS_DUPLICATE_METATAGS, (int)$metatags_descriptions->fields['language_id'], $products_id, $dup_products_id), 'success');

                $metatags_descriptions->MoveNext();
            }
        }

// copy linked categories to Duplicate
        if (!empty($_POST['copy_linked_categories']) && $_POST['copy_linked_categories'] === 'copy_linked_categories_yes') {
            $categories_from = zen_get_linked_categories_for_product($products_id);

            foreach ($categories_from as $row) {
                zen_link_product_to_category($dup_products_id, (int)$row);
                $messageStack->add_session(sprintf(TEXT_COPY_AS_DUPLICATE_CATEGORIES, (int)$row, $products_id, $dup_products_id), 'success');
            }
        }

// copy product discounts to Duplicate
        if (!empty($_POST['copy_discounts']) && $_POST['copy_discounts'] === 'copy_discounts_yes') {
            zen_copy_discounts_to_product($products_id, $dup_products_id);
            $messageStack->add_session(sprintf(TEXT_COPY_AS_DUPLICATE_DISCOUNTS, $products_id, $dup_products_id), 'success');
        }

        zen_record_admin_activity('Product ' . $products_id . ' duplicated as product ' . $dup_products_id . ' via admin console.', 'info');

        $zco_notifier->notify('NOTIFY_MODULES_COPY_TO_CONFIRM_DUPLICATE', compact('products_id', 'dup_products_id'));

        $products_id = $dup_products_id;//reset for further use in price update and final redirect to new linked product or new duplicated product
    }// EOF duplication

    // reset products_price_sorter for searches etc.
    zen_update_products_price_sorter($products_id);
}
if ($_POST['copy_as'] === 'duplicate' && !empty($_POST['edit_duplicate'])) {
    zen_redirect(zen_href_link(FILENAME_PRODUCT, 'action=new_product&cPath=' . $categories_id . '&pID=' . $dup_products_id . '&products_type=' . (int)$product->fields['products_type']));
} else {
    zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $categories_id . '&pID=' . $products_id . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')));
}
