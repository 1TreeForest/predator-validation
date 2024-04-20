<?php declare(strict_types=1);
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Scott C Wilson 2022 Oct 10 Modified in v1.5.8 $
 */
require 'includes/application_top.php';
$languages = zen_get_languages();
require DIR_WS_CLASSES . 'currencies.php';
$currencies = new currencies();

if (isset($_POST['products_id'])) {
    $product_type = zen_get_products_type($_POST['products_id']);
} elseif (isset($_GET['product_type'])) {
    $product_type = $_GET['product_type'];
} else {
    $product_type = 1;
}

$action = $_GET['action'] ?? '';
$search_result = isset($_GET['search']) && zen_not_null($_GET['search']);

$search_parameter = $search_result ? '&search=' . $_GET['search'] : '';
$keywords = $search_result ? zen_db_input(zen_db_prepare_input($_GET['search'])) : '';
$max_results = MAX_DISPLAY_RESULTS_CATEGORIES;
if (!empty($search_parameter)) {
   $max_results = MAX_DISPLAY_SEARCH_RESULTS;
}
if (isset($_GET['page'])) {
  $_GET['page'] = (int)$_GET['page'];
}
if (isset($_GET['product_type'])) {
  $_GET['product_type'] = (int)$_GET['product_type'];
}
if (isset($_GET['cID'])) {
  $_GET['cID'] = (int)$_GET['cID'];
}

if (!isset($_SESSION['categories_products_sort_order'])) {
  $_SESSION['categories_products_sort_order'] = CATEGORIES_PRODUCTS_SORT_ORDER;
}

if (!isset($_GET['reset_categories_products_sort_order'])) {
  $reset_categories_products_sort_order = $_SESSION['categories_products_sort_order'];
}

if (!empty($action)) {
  switch ($action) {
    case 'set_categories_products_sort_order':
      $_SESSION['categories_products_sort_order'] = $_GET['reset_categories_products_sort_order'];
      $action = '';
      zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $_GET['cPath'] . ((isset($_GET['pID']) && !empty($_GET['pID'])) ? '&pID=' . $_GET['pID'] : '') .  $search_parameter . ((isset($_GET['page']) && !empty($_GET['page'])) ? '&page=' . $_GET['page'] : '')));
      break;
    case 'set_editor':
      // Reset will be done by init_html_editor.php. Now we simply redirect to refresh page properly.
      $action = '';
      zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $_GET['cPath'] . ((isset($_GET['pID']) && !empty($_GET['pID'])) ? '&pID=' . $_GET['pID'] : '') . ((isset($_GET['page']) && !empty($_GET['page'])) ? '&page=' . $_GET['page'] : '')));
      break;
    case 'update_category_status':
      // disable category and products including subcategories
      if (isset($_POST['categories_id'])) {
        $categories_id = zen_db_prepare_input($_POST['categories_id']);

        $categories = zen_get_category_tree($categories_id, '', '0', '', true);

        // change the status of categories and products
        zen_set_time_limit(600);
        if ($_POST['categories_status'] === '1') {//form is coming from an Enabled category which is to be changed to Disabled
          $category_status = '0'; //Disable this category
          $subcategories_status = isset($_POST['set_subcategories_status']) && $_POST['set_subcategories_status'] === 'set_subcategories_status_off' ? '0' : ''; //Disable subcategories or no change?
          $products_status = isset($_POST['set_products_status']) && $_POST['set_products_status'] === 'set_products_status_off' ? '0' : ''; //Disable products or no change?
        } else {//form is coming from a Disabled category which is to be changed to Enabled
          $category_status = '1'; //Enable this category
          $subcategories_status = isset($_POST['set_subcategories_status']) && $_POST['set_subcategories_status'] === 'set_subcategories_status_on' ? '1' : ''; //also Enable subcategories or no change?
          $products_status = isset($_POST['set_products_status']) && $_POST['set_products_status'] === 'set_products_status_on' ? '1' : ''; //Disable products or no change?
        }

        for ($i = 0, $n = count($categories); $i < $n; $i++) {

          //set categories_status
          if ($categories[$i]['id'] === $categories_id) {//always update THIS category
              zen_set_category_status($categories[$i]['id'], $category_status);
          } elseif ($subcategories_status !== '') {//optionally update subcategories if a change was selected
              zen_set_category_status($categories[$i]['id'], $subcategories_status);
          }

          //set products_status
          if ($products_status === '') {
            continue;
          }

          //only execute if a change was selected
          $category_products = zen_get_linked_products_for_category($categories[$i]['id']);
          foreach ($category_products as $category_product) {
              zen_set_product_status($category_product, $products_status);
          }
        }
      }
      zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $_GET['cPath'] . '&cID=' . $_GET['cID'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter));
      break;
    case 'remove_type':
      if (isset($_POST['type_id'])) {
        zen_remove_restrict_sub_categories($_GET['cID'], (int)$_POST['type_id']);
        $action = 'edit';
        zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'action=edit_category&cPath=' . $_GET['cPath'] . '&cID=' . zen_db_prepare_input($_GET['cID'])));
      }
      break;
    case 'setflag':

      if (isset($_POST['flag']) && ($_POST['flag'] === '0' || $_POST['flag'] === '1')) {
        if (isset($_GET['pID'])) {
          zen_set_product_status($_GET['pID'], $_POST['flag']);
        }
      }

      zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter));
      break;
    case 'delete_category_confirm':

      // future cat specific deletion
      $delete_linked = 'true';
      if (isset($_POST['delete_linked']) && $_POST['delete_linked'] !== '') {
        $delete_linked = $_POST['delete_linked'];
      }

      // delete category and products
      if (!empty($_POST['categories_id']) && $_POST['categories_id'] !== (int)TOPMOST_CATEGORY_PARENT_ID) {
        $categories_id = zen_db_prepare_input($_POST['categories_id']);

        // create list of any subcategories in the selected category,
        $categories = zen_get_category_tree($categories_id, '', TOPMOST_CATEGORY_PARENT_ID, [], true);

        zen_set_time_limit(600);

        // loop through this cat and subcats for delete-processing.
        for ($i = 0, $n = count($categories); $i < $n; $i++) {
          $category_products = zen_get_linked_products_for_category($categories[$i]['id']);

          foreach ($category_products as $category_product) {
            $cascaded_prod_id_for_delete = $category_product;
            $cascaded_prod_cat_for_delete = [];
            $cascaded_prod_cat_for_delete[] = $categories[$i]['id'];
            // determine product-type-specific override script for this product
            $product_type = zen_get_products_type($category_product);
            // now loop thru the delete_product_confirm script for each product in the current category
            // NOTE: Debug code left in to help with creating additional product type delete-scripts

            $do_delete_flag = false;
            if (isset($_POST['products_id'], $_POST['product_categories']) && is_array($_POST['product_categories'])) {
              $product_id = (int)$_POST['products_id'];
              $product_categories = $_POST['product_categories'];
              $do_delete_flag = true;
            }

            if (!empty($cascaded_prod_id_for_delete) && !empty($cascaded_prod_cat_for_delete)) {
              $product_id = $cascaded_prod_id_for_delete;
              $product_categories = $cascaded_prod_cat_for_delete;
              $do_delete_flag = true;
            }

            if ($do_delete_flag) {
              //--------------PRODUCT_TYPE_SPECIFIC_INSTRUCTIONS_GO__BELOW_HERE--------------------------------------------------------
              if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/delete_product_confirm.php')) {
                require DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/delete_product_confirm.php';
              }
              //--------------PRODUCT_TYPE_SPECIFIC_INSTRUCTIONS_GO__ABOVE__HERE--------------------------------------------------------
              // now do regular non-type-specific delete:
              // remove product from all its categories:
              for ($k = 0, $m = count($product_categories); $k < $m; $k++) {
                  zen_unlink_product_from_category($product_id, $product_categories[$k]);
              }
              // confirm that product is no longer linked to any categories
              $count_categories = zen_get_linked_categories_for_product($product_id);
              // if not linked to any categories, do delete:
              if (empty($count_categories)) {
                zen_remove_product($product_id, $delete_linked);
              }
            } // endif $do_delete_flag
            // if this is a single-product delete, redirect to categories page
            // if not, then this file was called by the cascading delete initiated by the category-delete process
            if ($action === 'delete_product_confirm') {
              zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath));
            }
          }

          zen_remove_category($categories[$i]['id']);
        } // end for loop
      }
      zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath));
      break;
    case 'move_category_confirm':
      if (isset($_POST['categories_id']) && ($_POST['categories_id'] !== (int)$_POST['move_to_category_id'])) {
        $categories_id = zen_db_prepare_input($_POST['categories_id']);
        $new_parent_id = zen_db_prepare_input($_POST['move_to_category_id']);

        $path = explode('_', zen_get_generated_category_path_ids($new_parent_id));

        if (in_array($categories_id, $path)) {
          $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

          zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath));
        } else {

          $zc_count_products = zen_get_linked_products_for_category($new_parent_id);

          if (!empty($zc_count_products)) {
            $messageStack->add_session(ERROR_CATEGORY_HAS_PRODUCTS, 'error');
          } else {
            $messageStack->add_session(SUCCESS_CATEGORY_MOVED, 'success');
          }

          $db->Execute("UPDATE " . TABLE_CATEGORIES . "
                        SET parent_id = " . (int)$new_parent_id . ", last_modified = now()
                        WHERE categories_id = " . (int)$categories_id);

          // fix here - if this is a category with subcats it needs to know to loop through
          // reset all products_price_sorter for moved category products
          $reset_price_sorter = zen_get_linked_products_for_category((int)$categories_id);
          foreach ($reset_price_sorter as $product_id) {
            zen_update_products_price_sorter($product_id);
          }

          zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $new_parent_id));
        }
      } else {
        $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_CATEGORY_SELF . $cPath, 'error');
        zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath));
      }
      break;
    case 'delete_product_confirm':
      $delete_linked = 'true';
      if (isset($_POST['delete_linked']) && $_POST['delete_linked'] === 'delete_linked_no') {
        $delete_linked = 'false';
      }
      if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/delete_product_confirm.php')) {
        require DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/delete_product_confirm.php';
      } else {
        require DIR_WS_MODULES . 'delete_product_confirm.php';
      }
      break;
    case 'move_product_confirm':
      if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/move_product_confirm.php')) {
        require DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/move_product_confirm.php';
      } else {
        require DIR_WS_MODULES . 'move_product_confirm.php';
      }
      break;
    case 'copy_product_confirm':
      if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/copy_product_confirm.php')) {
        require DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/copy_product_confirm.php';
      } else {
        require DIR_WS_MODULES . 'copy_product_confirm.php';
      }
      break;
    case 'delete_attributes':
      zen_delete_products_attributes($_GET['products_id']);
      $messageStack->add_session(SUCCESS_ATTRIBUTES_DELETED . ' ID#' . $_GET['products_id'], 'success');
      $action = '';

      // reset products_price_sorter for searches etc.
      zen_update_products_price_sorter($_GET['products_id']);

      zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $_GET['products_id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')));
      break;
    case 'update_attributes_sort_order':
      zen_update_attributes_products_option_values_sort_order($_GET['products_id']);
      $messageStack->add_session(SUCCESS_ATTRIBUTES_UPDATE . ' ID#' . $_GET['products_id'], 'success');
      $action = '';
      zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $_GET['products_id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')));
      break;

    // attributes copy to product
    case 'update_attributes_copy_to_product':
      $copy_attributes_delete_first = ($_POST['copy_attributes'] === 'copy_attributes_delete' ? '1' : '0');
      $copy_attributes_duplicates_skipped = ($_POST['copy_attributes'] === 'copy_attributes_ignore' ? '1' : '0');
      $copy_attributes_duplicates_overwrite = ($_POST['copy_attributes'] === 'copy_attributes_update' ? '1' : '0');
      zen_copy_products_attributes($_POST['products_id'], $_POST['products_update_id']);
      //      die('I would copy Product ID#' . $_POST['products_id'] . ' to a Product ID#' . $_POST['products_update_id'] . ' - Existing attributes ' . $_POST['copy_attributes']);
      $_GET['action'] = '';
      zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $_GET['products_id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')));
      break;

    // attributes copy to category
    case 'update_attributes_copy_to_category':
      $copy_attributes_delete_first = ($_POST['copy_attributes'] === 'copy_attributes_delete' ? '1' : '0');
      $copy_attributes_duplicates_skipped = ($_POST['copy_attributes'] === 'copy_attributes_ignore' ? '1' : '0');
      $copy_attributes_duplicates_overwrite = ($_POST['copy_attributes'] === 'copy_attributes_update' ? '1' : '0');
      $copy_to_category = zen_get_linked_products_for_category((int)$_POST['categories_update_id']);
      foreach ($copy_to_category as $item) {
        zen_copy_products_attributes($_POST['products_id'], $item);
      }
      //      die('CATEGORIES - I would copy Product ID#' . $_POST['products_id'] . ' to a Category ID#' . $_POST['categories_update_id']  . ' - Existing attributes ' . $_POST['copy_attributes'] . ' Total Products ' . $copy_to_category->RecordCount());

      $_GET['action'] = '';
      zen_redirect(zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $_GET['products_id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')));
      break;
    case 'setflag_categories':
    case 'delete_category':
    case 'move_category':
    case 'delete_product':
    case 'move_product':
    case 'copy_product':
    case 'attribute_features':
    case 'attribute_features_copy_to_product':
    case 'attribute_features_copy_to_category':
      break;
    default:
      $zco_notifier->notify('NOTIFY_ADMIN_PROD_LISTING_DEFAULT_ACTION');
      $action = $_GET['action'] = '';
      break;
  }
}

// check if the catalog image directory exists
if (is_dir(DIR_FS_CATALOG_IMAGES)) {
  if (!is_writable(DIR_FS_CATALOG_IMAGES)) {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  }
} else {
  $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
}
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
  </head>
  <body>
    <!-- header //-->
    <?php require DIR_WS_INCLUDES . 'header.php'; ?>
    <!-- header_eof //-->
    <!-- body //-->
    <div class="container-fluid">
      <h1>
        <a href="<?php echo zen_catalog_href_link('index', zen_get_path($current_category_id)); ?>" rel="noopener" target="_blank" title="<?php echo BOX_HEADING_CATALOG; ?>"><?php echo zen_image(DIR_WS_IMAGES . 'icon_popup.gif', BOX_HEADING_CATALOG); ?></a>
        <?php echo HEADING_TITLE; ?>&nbsp;-&nbsp;<?php echo zen_output_generated_category_path($current_category_id); ?>
      </h1>
      <?php if ($action === '') { ?>
        <div class="row">
          <div class="col-md-4">
            <table class="table-condensed">
              <thead>
                <tr>
                  <th class="smallText"><?php echo TEXT_LEGEND; ?></th>
                  <th class="text-center smallText"><?php echo TEXT_LEGEND_STATUS_OFF; ?></th>
                  <th class="text-center smallText"><?php echo TEXT_LEGEND_STATUS_ON; ?></th>
                  <th class="text-center smallText"><?php echo TEXT_LEGEND_LINKED; ?></th>
                  <th class="text-center smallText"><?php echo TEXT_LEGEND_META_TAGS . '<br>' . TEXT_YES . '&nbsp;' . TEXT_NO; ?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td class="text-center">
                    <i class="fa fa-square fa-lg txt-status-off" aria-hidden="true"></i>
                  </td>
                  <td class="text-center">
                    <i class="fa fa-square fa-lg txt-status-on" aria-hidden="true"></i>
                  </td>
                  <td class="text-center">
                    <i class="fa fa-square fa-lg txt-linked" aria-hidden="true"></i>
                  </td>
                  <td class="text-center">
                    <div class="fa fa-stack">
                      <i class="fa fa-square fa-stack-2x txt-black"></i>
                      <i class="fa fa-asterisk fa-stack-1x txt-orange" aria-hidden="true"></i>
                    </div>
                    &nbsp;
                    <div class="fa fa-stack">
                      <i class="fa fa-square fa-stack-2x txt-black"></i>
                      <i class="fa fa-asterisk fa-stack-1x txt-white" aria-hidden="true"></i>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-4">
            <?php echo zen_draw_form('set_editor_form', FILENAME_CATEGORY_PRODUCT_LISTING, '', 'get', 'class="form-horizontal"'); ?>
            <div class="form-group">
              <?php echo zen_draw_label(TEXT_EDITOR_INFO, 'reset_editor', 'class="col-sm-6 col-md-4 control-label"'); ?>
              <div class="col-sm-6 col-md-8">
                <?php echo zen_draw_pull_down_menu('reset_editor', $editors_pulldown, $current_editor_key, 'onchange="this.form.submit();" class="form-control" id="reset_editor"'); ?>
              </div>
              <?php
              echo zen_hide_session_id();
              echo zen_draw_hidden_field('cID', $cPath);
              echo zen_draw_hidden_field('cPath', $cPath);
              echo (isset($_GET['pID']) ? zen_draw_hidden_field('pID', $_GET['pID']) : '');
              echo (isset($_GET['page']) ? zen_draw_hidden_field('page', $_GET['page']) : '');
              echo zen_draw_hidden_field('action', 'set_editor');
              ?>
            </div>
            <?php echo '</form>'; ?>
            <?php
            // check for which buttons to show for categories and products
            $check_categories = zen_has_category_subcategories($current_category_id);
            $check_products = zen_products_in_category_count($current_category_id, true, false, 1);

            $zc_skip_products = false;
            $zc_skip_categories = false;

            if ($check_products === 0) {
              $zc_skip_products = false;
              $zc_skip_categories = false;
            }
            if ($check_categories) {
              $zc_skip_products = true;
              $zc_skip_categories = false;
            }
            if ($check_products > 0) {
              $zc_skip_products = false;
              $zc_skip_categories = true;
            }

            if (isset($_GET['search']) && !empty($_GET['search'])) {
              	$zc_skip_products = false;
            }

            if ($zc_skip_products === true) {
                // toggle switch for category display sort order
                $categories_products_sort_order_array = [
                    ['id' => '0', 'text' => TEXT_SORT_CATEGORIES_SORT_ORDER_CATEGORIES_NAME],
                    ['id' => '1', 'text' => TEXT_SORT_CATEGORIES_NAME],
                    ['id' => '2', 'text' => TEXT_SORT_CATEGORIES_ID],
                    ['id' => '3', 'text' => TEXT_SORT_CATEGORIES_ID_DESC],
                    ['id' => '4', 'text' => TEXT_SORT_CATEGORIES_STATUS],
                    ['id' => '5', 'text' => TEXT_SORT_CATEGORIES_STATUS_DESC]
                ];
            } else {
                // toggle switch for product display sort order
                $categories_products_sort_order_array = [
                    ['id' => '0', 'text' => TEXT_SORT_PRODUCTS_SORT_ORDER_PRODUCTS_NAME],
                    ['id' => '1', 'text' => TEXT_SORT_PRODUCTS_NAME],
                    ['id' => '2', 'text' => TEXT_SORT_PRODUCTS_MODEL],
                    ['id' => '3', 'text' => TEXT_SORT_PRODUCTS_QUANTITY],
                    ['id' => '4', 'text' => TEXT_SORT_PRODUCTS_QUANTITY_DESC],
                    ['id' => '5', 'text' => TEXT_SORT_PRODUCTS_PRICE],
                    ['id' => '6', 'text' => TEXT_SORT_PRODUCTS_PRICE_DESC],
                    ['id' => '7', 'text' => TEXT_SORT_PRODUCTS_MODEL_DESC],
                    ['id' => '8', 'text' => TEXT_SORT_PRODUCTS_STATUS],
                    ['id' => '9', 'text' => TEXT_SORT_PRODUCTS_STATUS_DESC],
                    ['id' => '10', 'text' => TEXT_SORT_PRODUCTS_ID],
                    ['id' => '11', 'text' => TEXT_SORT_PRODUCTS_WEIGHT]
                ];
            }
            echo zen_draw_form('set_categories_products_sort_order_form', FILENAME_CATEGORY_PRODUCT_LISTING, '', 'get', 'class="form-horizontal"');
            ?>
            <div class="form-group">
              <?php echo zen_draw_label(TEXT_CATEGORIES_PRODUCTS_SORT_ORDER_INFO, 'reset_categories_products_sort_order', 'class="col-sm-6 col-md-4 control-label"'); ?>
              <div class="col-sm-6 col-md-8">
                <?php echo zen_draw_pull_down_menu('reset_categories_products_sort_order', $categories_products_sort_order_array, $reset_categories_products_sort_order, 'onchange="this.form.submit();" class="form-control" id="reset_categories_products_sort_order"'); ?>
              </div>
              <?php
              echo zen_hide_session_id();
              echo zen_draw_hidden_field('cID', $cPath);
              echo zen_draw_hidden_field('cPath', $cPath);
              echo (isset($_GET['pID']) ? zen_draw_hidden_field('pID', $_GET['pID']) : '');
              echo (isset($_GET['page']) ? zen_draw_hidden_field('page', $_GET['page']) : '');
              echo (isset($_GET['search']) ? zen_draw_hidden_field('search', $_GET['search']) : '');
              echo zen_draw_hidden_field('action', 'set_categories_products_sort_order');
              ?>
            </div>
            <?php
            echo '</form>';

            if (!isset($_GET['page'])) {
              $_GET['page'] = '';
            }
            if (isset($_GET['set_display_categories_dropdown'])) {
              $_SESSION['display_categories_dropdown'] = (int)$_GET['set_display_categories_dropdown'];
            }
            if (!isset($_SESSION['display_categories_dropdown'])) {
              $_SESSION['display_categories_dropdown'] = 0;
            }
            ?>
          </div>
          <div class="col-md-4">
            <?php echo zen_draw_form('searchForm', FILENAME_CATEGORY_PRODUCT_LISTING, '', 'get', 'class="form-horizontal"'); ?>
            <?php echo zen_hide_session_id(); ?>
            <div class="form-group">
              <?php echo zen_draw_label(HEADING_TITLE_SEARCH_DETAIL, 'search', 'class="col-sm-6 col-md-4 control-label"'); ?>
              <div class="col-sm-6 col-md-8">
                <?php echo zen_draw_input_field('search', '', 'autofocus="autofocus" class="form-control" id="search"'); ?>
              </div>
            </div>
            <?php
            if ($search_result) {
              ?>
              <div class="form-group">
                <div class="col-sm-6 col-md-4 control-label"><?php echo TEXT_INFO_SEARCH_DETAIL_FILTER; ?></div>
                <div class="col-sm-6 col-md-8">
                  <strong>"<?php echo zen_output_string_protected($_GET['search']); ?>"</strong>
                  <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING); ?>" class="btn btn-default" role="button"><?php echo IMAGE_RESET; ?></a>
                </div>
              </div>
            <?php } ?>
            <?php echo '</form>'; ?>
            <?php echo zen_draw_form('goto', FILENAME_CATEGORY_PRODUCT_LISTING, '', 'get', 'class="form-horizontal"'); ?>
            <?php echo zen_hide_session_id(); ?>
            <div class="form-group">
              <?php if ($_SESSION['display_categories_dropdown'] === 0) { ?>
                <div class="col-sm-6 col-md-4 control-label">
                  <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'set_display_categories_dropdown=1' . (isset($_GET['cID']) ? '&cID=' . (int)$_GET['cID'] : '') . '&cPath=' . $cPath . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')); ?>" title="<?php echo IMAGE_ICON_STATUS_OFF; ?>"><i class="fa fa-times fa-lg txt-status-off"></i></a>
                  <?php echo zen_draw_label(HEADING_TITLE_GOTO, 'cPath'); ?>
                </div>
                <div class="col-sm-6 col-md-8">
                  <?php echo zen_draw_pull_down_menu('cPath', zen_get_category_tree(), $current_category_id, 'onchange="this.form.submit();" class="form-control" id="cPath"'); ?>
                </div>
              <?php } else { ?>
                <div class="col-sm-6 col-md-4 control-label">
                  <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'set_display_categories_dropdown=0' . (isset($_GET['cID']) ? '&cID=' . (int)$_GET['cID'] : '') . '&cPath=' . $cPath . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')); ?>" title="<?php echo IMAGE_ICON_STATUS_ON; ?>"><i class="fa fa-check fa-lg txt-status-on"></i></a>
                  <strong><?php echo HEADING_TITLE_GOTO; ?></strong>
                </div>
              <?php } ?>
            </div>
            <?php echo '</form>'; ?>
          </div>
        </div>
      <?php } ?>
      <div class="row"><?php echo zen_draw_separator('pixel_black.gif'); ?></div>
      <div class="row">
        <div<?php echo (empty($action)) ? '' : ' class="col-xs-12 col-sm-12 col-md-9 col-lg-9 configurationColumnLeft"'; ?>>
          <?php
          switch ($_SESSION['categories_products_sort_order']) {
              case (0):
                  $order_by = " ORDER BY c.sort_order, cd.categories_name";
                  break;
              case (1):
                  $order_by = " ORDER BY cd.categories_name";
                  break;
              case (2):
                  $order_by = " ORDER BY cd.categories_id";
                  break;
              case (3):
                  $order_by = " ORDER BY cd.categories_id DESC";
                  break;
              case (4):
                  $order_by = " ORDER BY c.categories_status, cd.categories_name";
                  break;
              case (5):
                  $order_by = " ORDER BY c.categories_status, cd.categories_name DESC";
                  break;
              default:
                  $order_by = " ";
          }

          $categories_count = 0;
          $sql = "SELECT c.categories_id, c.categories_image, cd.categories_name, c.parent_id, c.sort_order, c.categories_status
                  FROM " . TABLE_CATEGORIES . " c
                  LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON c.categories_id = cd.categories_id
                    AND cd.language_id = " . (int)$_SESSION['languages_id'];

            if (isset($_GET['search'])) {
              $keyword_search_fields = [
                'cd.categories_name',
                'cd.categories_description',
              ];
              $sql .= zen_build_keyword_where_clause($keyword_search_fields, trim($keywords), true);
          } else {
              $sql .= " WHERE c.parent_id = :category";
              $sql = $db->bindVars($sql, ':category', $current_category_id, 'integer');
          }

          $sql .= $order_by;

          $categories = $db->Execute($sql);

          $show_prod_labels = ($search_result || $categories->EOF);
          ?>
          <table id="categories-products-table" class="table table-striped">
            <thead>
              <tr>
                <th class="text-right shrink"><?php echo TABLE_HEADING_ID; ?></th>
                <th><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></th>
                <th class="hidden-sm hidden-xs"><?php echo TABLE_HEADING_IMAGE; ?></th>
                <?php if ($show_prod_labels) { ?>
                  <th class="hidden-sm hidden-xs"><?php echo TABLE_HEADING_MODEL; ?></th>
                  <th class="text-right hidden-sm hidden-xs"><?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></th>
                <?php } ?>
<?php
          // -----
          // Additional column-headings can be added before the Quantity column.
          //
          // A watching observer can provide an associative array in the following format (for the products' listing ONLY):
          //
          // $extra_headings = array(
          //     array(
          //       'align' => $alignment,    // One of 'center', 'right', or 'left' (optional)
          //       'text' => $value
          //     ),
          // );
          //
          // Observer notes:
          // - Be sure to check that the $p2/$extra_headings value is specifically (bool)false before initializing, since
          //   multiple observers might be injecting content!
          // - If heading-columns are added, be sure to add the associated data columns, too, via the
          //   'NOTIFY_ADMIN_PROD_LISTING_DATA_B4_QTY' notification.
          //
          if ($show_prod_labels) {
              $extra_headings = false;
              $zco_notifier->notify('NOTIFY_ADMIN_PROD_LISTING_HEADERS_B4_QTY', '', $extra_headings);
              if (is_array($extra_headings)) {
                  foreach ($extra_headings as $heading_info) {
                      $align = (isset($heading_info['align'])) ? (' text-' . $heading_info['align']) : '';
?>
                <th class="hidden-sm hidden-xs<?php echo $align; ?>"><?php echo $heading_info['text']; ?></th>
<?php
                  }
              }
          }
?>
                <?php if ($show_prod_labels || SHOW_COUNTS_ADMIN === 'true') { ?>
                  <th class="text-right hidden-sm hidden-xs"><?php echo TABLE_HEADING_QUANTITY; ?></th>
                <?php } ?>
<?php
          // -----
          // Additional column-headings can be added after the Quantity column.
          //
          // A watching observer can provide an associative array in the following format (for the products' listing ONLY):
          //
          // $extra_headings = array(
          //     array(
          //       'align' => $alignment,    // One of 'center', 'right', or 'left' (optional)
          //       'text' => $value
          //     ),
          // );
          //
          // Observer notes:
          // - Be sure to check that the $p2/$extra_headings value is specifically (bool)false before initializing, since
          //   multiple observers might be injecting content!
          // - If heading-columns are added, be sure to add the associated data columns, too, via the
          //   'NOTIFY_ADMIN_PROD_LISTING_DATA_AFTER_QTY' notification.
          //
          if ($show_prod_labels) {
              $extra_headings = false;
              $zco_notifier->notify('NOTIFY_ADMIN_PROD_LISTING_HEADERS_AFTER_QTY', '', $extra_headings);
              if (is_array($extra_headings)) {
                  foreach ($extra_headings as $heading_info) {
                      $align = (isset($heading_info['align'])) ? (' text-' . $heading_info['align']) : '';
?>
                <th class="hidden-sm hidden-xs<?php echo $align; ?>"><?php echo $heading_info['text']; ?></th>
<?php
                  }
              }
          }
?>
                <th class="text-right"><?php echo TABLE_HEADING_STATUS; ?></th>
                <?php
                if ($action === '') {
                  ?>
                  <th class="text-right hidden-sm hidden-xs"><?php echo TABLE_HEADING_CATEGORIES_SORT_ORDER; ?></th>
                  <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?></th>
                  <?php
                }
                ?>
              </tr>
            </thead>
            <?php
            foreach ($categories as $category) {
              $categories_count++;
// Get parent_id for subcategories if search
              if (isset($_GET['search'])) {
                $cPath = $category['parent_id'];
              }

              if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $category['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                //$category_childs = array('childs_count' => zen_childs_in_category_count($category['categories_id']));
                //$category_products = array('products_count' => zen_products_in_category_count($category['categories_id']));
                //$cInfo_array = array_merge($category, $category_childs, $category_products);
                $cInfo = new objectInfo($category);
              }
              ?>
              <tr class="category-listing-row" data-cid="<?php echo $category['categories_id']; ?>">
                <td class="text-right"><?php echo $category['categories_id']; ?></td>
                  <td class="dataTableButtonCell">
                      <a href="<?php echo zen_catalog_href_link('index', zen_get_path($category['categories_id'])); ?>" rel="noopener" target="_blank" title="<?php echo BOX_HEADING_CATALOG; ?>"><?php echo zen_image(DIR_WS_IMAGES . 'icon_popup.gif', BOX_HEADING_CATALOG); ?></a>
                  <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, zen_get_path($category['categories_id'])); ?>" class="folder"><i class="fa fa-lg fa-folder"></i>&nbsp;<strong><?php echo $category['categories_name']; ?></strong></a>
                </td>
                  <td class="hidden-sm hidden-xs"><?php echo zen_image(DIR_WS_CATALOG_IMAGES . $category['categories_image'], $category['categories_name'], IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT); ?></td>
                <?php if ($show_prod_labels) { ?>
                  <td class="hidden-sm hidden-xs"><!-- no model for categories --></td>
                  <td class="hidden-sm hidden-xs"><!-- no price for categories --></td>
                <?php } ?>
                <?php if (SHOW_COUNTS_ADMIN === 'true') { ?>
                  <td class="text-right hidden-sm hidden-xs">
                    <?php
                      // show counts
                      $total_products = zen_get_products_to_categories($category['categories_id'], true);
                      $total_products_on = zen_get_products_to_categories($category['categories_id']);
                      echo $total_products_on . TEXT_PRODUCTS_STATUS_ON_OF . $total_products . TEXT_PRODUCTS_STATUS_ACTIVE;
                    ?>
                  </td>
                <?php } ?>
                <td class="text-right dataTableButtonCell">
                  <?php if (SHOW_CATEGORY_PRODUCTS_LINKED_STATUS === 'true' && zen_get_products_to_categories($category['categories_id'], true, 'products_active') === 'true') { ?>
                    <i class="fa fa-square fa-lg txt-linked" aria-hidden="true" title="<?php echo IMAGE_ICON_LINKED; ?>"></i>
                  <?php } ?>
                  <?php if ($category['categories_status'] === '1') { ?>
                    <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'action=setflag_categories&flag=0&cID=' . $category['categories_id'] . '&cPath=' . $cPath . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter); ?>"><i class="fa fa-square fa-lg txt-status-on" title="<?php echo IMAGE_ICON_STATUS_ON; ?>"></i></a>
                  <?php } else { ?>
                    <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'action=setflag_categories&flag=1&cID=' . $category['categories_id'] . '&cPath=' . $cPath . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter); ?>"><i class="fa fa-square fa-lg txt-status-off" title="<?php echo IMAGE_ICON_STATUS_OFF; ?>"></i></a>
                  <?php } ?>
                </td>
                <?php
                if ($action === '') {
                  ?>
                  <td class="text-right hidden-sm hidden-xs"><?php echo $category['sort_order']; ?></td>
                  <td class="text-right dataTableButtonCell">
                    <div class="btn-group">
                      <a href="<?php echo zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $category['categories_id'] . '&action=edit_category' . $search_parameter); ?>" class="btn btn-sm btn-default btn-edit" role="button" title="<?php echo ICON_EDIT; ?>">
                        <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                      </a>
                      <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&cID=' . $category['categories_id'] . '&action=delete_category' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')); ?>" class="btn btn-sm btn-default btn-delete" role="button" title="<?php echo ICON_DELETE; ?>">
                        <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                      </a>
                      <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&cID=' . $category['categories_id'] . '&action=move_category' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')); ?>" class="btn btn-sm btn-default btn-move" role="button" title="<?php echo ICON_MOVE; ?>"><strong>M</strong></a>
                        <?php
                        $additional_icons = '';
                        $zco_notifier->notify('NOTIFY_ADMIN_PROD_LISTING_ADD_ICON_CATEGORY', $category, $additional_icons);
                        if (!empty($additional_icons)) {
                            echo $additional_icons;
                        }
                        if (zen_get_category_metatag_fields($category['categories_id'], (int)$_SESSION['languages_id'], 'metatags_keywords') || zen_get_category_metatag_fields($category['categories_id'], (int)$_SESSION['languages_id'], 'metatags_description')) { ?>
                        <a href="<?php echo zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $category['categories_id'] . '&action=edit_category_meta_tags'); ?>" class="btn btn-sm btn-default btn-metatags-on" role="button" title="<?php echo ICON_EDIT_METATAGS; ?>">
                          <i class="fa fa-asterisk fa-lg" aria-hidden="true"></i>
                        </a>
                      <?php } else { ?>
                        <a href="<?php echo zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $category['categories_id'] . '&action=edit_category_meta_tags'); ?>" class="btn btn-sm btn-default btn-metatags-off" role="button" title="<?php echo ICON_EDIT_METATAGS; ?>">
                          <i class="fa fa-asterisk fa-lg" aria-hidden="true"></i>
                        </a>
                      <?php } ?>
                    </div>
                  </td>
                <?php } ?>
              </tr>
              <?php
            }

            switch ($_SESSION['categories_products_sort_order']) {
                case (0):
                    $order_by = " ORDER BY p.products_sort_order, pd.products_name";
                    break;
                case (1):
                    $order_by = " ORDER BY pd.products_name";
                    break;
                case (2):
                    $order_by = " ORDER BY p.products_model";
                    break;
                case (3):
                    $order_by = " ORDER BY p.products_quantity, pd.products_name";
                    break;
                case (4):
                    $order_by = " ORDER BY p.products_quantity DESC, pd.products_name";
                    break;
                case (5):
                    $order_by = " ORDER BY p.products_price_sorter, pd.products_name";
                    break;
                case (6):
                    $order_by = " ORDER BY p.products_price_sorter DESC, pd.products_name";
                    break;
                case (7):
                    $order_by = " ORDER BY p.products_model DESC";
                    break;
                case (8):
                    $order_by = " ORDER BY p.products_status";
                    break;
                case (9):
                    $order_by = " ORDER BY p.products_status DESC";
                    break;
                case (10):
                    $order_by = " ORDER BY p.products_id";
                    break;
                case (11):
                    $order_by = " ORDER BY p.products_weight";
                    break;
                default:
                    $order_by = " ";
            }

            $products_count = 0;
            // -----
            // Give a watching observer the chance to modify the products' query to gather additional fields for the display.
            //
            // Note the leading space requirements!
            //
            // 1. Any modification of the $extra_select must include a leading ' ,'.
            // 2. Any modification of the $extra_from must include a leading ' ,'.
            // 3. Any modification of the $extra_ands must include a leading ' AND'
            //
            $extra_select = $extra_from = $extra_joins = $extra_ands = '';
            $zco_notifier->notify('NOTIFY_ADMIN_PROD_LISTING_PRODUCTS_QUERY', '', $extra_select, $extra_from, $extra_joins, $extra_ands, $order_by);

            $products_query_raw = "SELECT p.products_type, p.products_id, pd.products_name, p.products_quantity,
                                          p.products_price, p.products_status, p.products_model, p.products_sort_order,
                                          p.master_categories_id";
            $products_query_raw .= $extra_select;

            $products_query_raw .= " FROM " . TABLE_PRODUCTS . " p";
            $products_query_raw .= $extra_from;

            $products_query_raw .= " LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON (pd.products_id = p.products_id)";
            $products_query_raw .= $extra_joins;

            $where = " WHERE pd.language_id = " . (int)$_SESSION['languages_id'];
            $where .= $extra_ands;

            if ($search_result && $action !== 'edit_category') {
                $keyword_search_fields = [
                    'pd.products_name',
                    'p.products_model',
                    'pd.products_description',
                    'p.products_id',
                ];
                $where .= zen_build_keyword_where_clause($keyword_search_fields, trim($keywords));
            } else {
                $products_query_raw.= " LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c ON (p2c.products_id = p.products_id) ";
                $where .= " AND p2c.categories_id=" . (int)$current_category_id;
            }

            $products_query_raw .= $where . $order_by;

// Split Page

// reset page when page is unknown
            if ((empty($_GET['page']) || $_GET['page'] === 1) && !empty($_GET['pID'])) {
              $check_page = $db->Execute($products_query_raw);
              if ($check_page->RecordCount() > $max_results) {
                $check_count = 0;
                foreach ($check_page as $item) {
                  if ((int)$item['products_id'] === (int)$_GET['pID']) {
                    break;
                  }
                  $check_count++;
                }
                $_GET['page'] = round((($check_count / $max_results) + (fmod_round($check_count, $max_results) != 0 ? .5 : 0)));
                $page = $_GET['page'];
              } else {
                $_GET['page'] = 1;
              }
            }
            $products_split = new splitPageResults($_GET['page'], $max_results, $products_query_raw, $products_query_numrows);
            $products = $db->Execute($products_query_raw);
// Split Page

            foreach ($products as $product) {
              $products_count++;
// Get categories_id for product if search
              if (isset($_GET['search'])) {
                $cPath = $product['master_categories_id'];
              }

              if ((!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] === $product['products_id']))) && !isset($pInfo) && !isset($cInfo) && (strpos($action, 'new')
                      !== 0)) {
                $pInfo = new objectInfo($product);
              }

              $type_handler = $zc_products->get_handler($product['products_type']);
              ?>
              <tr class="product-listing-row" data-pid="<?php echo $product['products_id']; ?>">
                <td class="text-right"><?php echo $product['products_id']; ?></td>
                <td class="dataTableButtonCell"><a href="<?php echo zen_catalog_href_link($type_handler . '_info', 'cPath=' . $cPath . '&products_id=' . $product['products_id'] . '&language=' . $_SESSION['languages_code'] . '&product_type=' . $product['products_type']); ?>" rel="noopener" target="_blank">
                        <?php echo zen_image(DIR_WS_IMAGES . 'icon_popup.gif', BOX_HEADING_CATALOG); ?>
                    </a>
                    <a href="<?php echo zen_href_link(FILENAME_PRODUCT, 'cPath=' . $cPath . '&product_type=' . $product['products_type'] . '&pID=' . $product['products_id'] . '&action=new_product' . $search_parameter); ?>" title="<?php echo IMAGE_EDIT; ?>" style="text-decoration: none">
                        <?php echo $product['products_name']; ?>
                    </a>
                </td>
                <td class="hidden-sm hidden-xs"><?php echo zen_image(DIR_WS_CATALOG_IMAGES . zen_get_products_image($product['products_id']),'', IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT); ?></td>
                <td class="hidden-sm hidden-xs"><?php echo $product['products_model']; ?></td>
                <td class="text-right hidden-sm hidden-xs"><?php echo zen_get_products_display_price($product['products_id']); ?></td>
<?php
              // -----
              // Additional fields can be added into columns before the Quantity column.
              //
              // A watching observer can provide an associative array in the following format:
              //
              // $extra_data = array(
              //     array(
              //       'align' => $alignment,    // One of 'center', 'right', or 'left' (optional)
              //       'text' => $value
              //     ),
              // );
              //
              // Observer notes:
              // - Be sure to check that the $p2/$extra_data value is specifically (bool)false before initializing, since
              //   multiple observers might be injecting content!
              // - If heading-columns are added, be sure to add the associated header columns, too, via the
              //   'NOTIFY_ADMIN_PROD_LISTING_HEADERS_B4_QTY' notification.
              //
              $extra_data = false;
              $zco_notifier->notify('NOTIFY_ADMIN_PROD_LISTING_DATA_B4_QTY', $product, $extra_data);
              if (is_array($extra_data)) {
                  foreach ($extra_data as $data_info) {
                      $align = (isset($data_info['align'])) ? (' text-' . $data_info['align']) : '';
?>
                <td class="hidden-sm hidden-xs<?php echo $align; ?>"><?php echo $data_info['text']; ?></td>
<?php
                  }
              }
?>
                <td class="text-right hidden-sm hidden-xs"><?php echo $product['products_quantity']; ?></td>
<?php
              // -----
              // Additional fields can be added into columns after the Quantity column.
              //
              // A watching observer can provide an associative array in the following format:
              //
              // $extra_data = array(
              //     array(
              //       'align' => $alignment,    // One of 'center', 'right', or 'left' (optional)
              //       'text' => $value
              //     ),
              // );
              //
              // Observer notes:
              // - Be sure to check that the $p2/$extra_data value is specifically (bool)false before initializing, since
              //   multiple observers might be injecting content!
              // - If heading-columns are added, be sure to add the associated header columns, too, via the
              //   'NOTIFY_ADMIN_PROD_LISTING_HEADERS_AFTER_QTY' notification.
              //
              $extra_data = false;
              $zco_notifier->notify('NOTIFY_ADMIN_PROD_LISTING_DATA_AFTER_QTY', $product, $extra_data);
              if (is_array($extra_data)) {
                  foreach ($extra_data as $data_info) {
                      $align = (isset($data_info['align'])) ? (' text-' . $data_info['align']) : '';
?>
                <td class="hidden-sm hidden-xs<?php echo $align; ?>"><?php echo $data_info['text']; ?></td>
<?php
                  }
              }
?>
                <td class="text-right text-nowrap dataTableButtonCell">
                  <?php
                  $additional_icons = '';
                  $zco_notifier->notify('NOTIFY_ADMIN_PROD_LISTING_ADD_ICON', $product, $additional_icons);
                  echo $additional_icons;
                  ?>
                  <?php if (zen_get_product_is_linked($product['products_id']) === 'true') { ?>
                    <i class="fa fa-square fa-lg txt-linked" aria-hidden="true" title="<?php echo IMAGE_ICON_LINKED; ?>"></i>
                  <?php } else { ?>
                    <i class="fa fa-square fa-lg txt-transparent"></i> <!-- blank icon to preserve vertical alignment with additional icons -->
                    <?php
                  }
                  echo zen_draw_form('setflag_products' . $product['products_id'], FILENAME_CATEGORY_PRODUCT_LISTING, 'action=setflag&pID=' . $product['products_id'] . '&cPath=' . $cPath . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter);
                  if ($product['products_status'] === '1') {
                    ?>
                    <i class="fa fa-square fa-lg txt-status-on" title="<?php echo IMAGE_ICON_STATUS_ON; ?>" onclick="document.forms.setflag_products<?php echo $product['products_id']; ?>.submit();" role="button"></i>
                    <?php echo zen_draw_hidden_field('flag', '0'); ?>
                  <?php } else { ?>
                    <i class="fa fa-square fa-lg txt-status-off" title="<?php echo IMAGE_ICON_STATUS_OFF; ?>" onclick="document.forms.setflag_products<?php echo $product['products_id']; ?>.submit();" role="button"></i>
                    <?php echo zen_draw_hidden_field('flag', '1'); ?>
                  <?php } ?>
                  <?php echo '</form>'; ?>
                </td>
                <?php if ($action === '') { ?>
                  <td class="text-right hidden-sm hidden-xs"><?php echo $product['products_sort_order']; ?></td>
                  <td class="text-right dataTableButtonCell">
                    <div class="btn-group">
                      <a href="<?php echo zen_href_link(FILENAME_PRODUCT, 'cPath=' . $cPath . '&product_type=' . $product['products_type'] . '&pID=' . $product['products_id'] . '&action=new_product' . $search_parameter); ?>" class="btn btn-sm btn-default btn-edit" role="button" title="<?php echo IMAGE_EDIT_PRODUCT; ?>">
                        <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                      </a>
                      <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&product_type=' . $product['products_type'] . '&pID=' . $product['products_id'] . '&action=delete_product' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter); ?>" class="btn btn-sm btn-default btn-delete" role="button" title="<?php echo ICON_DELETE; ?>">
                        <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                      </a>
                      <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&product_type=' . $product['products_type'] . '&pID=' . $product['products_id'] . '&action=move_product' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter); ?>" class="btn btn-sm btn-default btn-move" role="button" title="<?php echo ICON_MOVE; ?>"><strong>M</strong></a>
                      <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&product_type=' . $product['products_type'] . '&pID=' . $product['products_id'] . '&action=copy_product' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter); ?>" class="btn btn-sm btn-default btn-copy" role="button" title="<?php echo ICON_COPY_TO; ?>"><strong>C</strong></a>

                      <?php if (defined('FILENAME_IMAGE_HANDLER') && file_exists(DIR_FS_ADMIN . FILENAME_IMAGE_HANDLER . '.php')) { ?>
                        <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'products_filter=' . $product['products_id'] . '&current_category_id=' . $current_category_id); ?>" class="btn btn-sm btn-default btn-imagehandler" role="button" title="Image Handler">
                          <i class="fa fa-image fa-lg" aria-hidden="true"></i>
                        </a>
                      <?php } ?>
                      <?php if (zen_has_product_attributes($product['products_id'], false)) { ?>
                        <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $product['products_id'] . '&action=attribute_features' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')); ?>" class="btn btn-sm btn-default btn-attributes-on" role="button" title="<?php echo BOX_CATALOG_CATEGORIES_ATTRIBUTES_CONTROLLER; ?>"><strong>A</strong></a>
                      <?php } else { ?>
                        <a href="<?php echo zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $product['products_id'] . '&current_category_id=' . $current_category_id); ?>" class="btn btn-sm btn-default btn-attributes-off" role="button" title="<?php echo BOX_CATALOG_CATEGORIES_ATTRIBUTES_CONTROLLER; ?>"><strong>A</strong></a>
                      <?php } ?>
                      <?php if ($zc_products->get_allow_add_to_cart($product['products_id']) === "Y") { ?>
<?php
                     $ppm_color = 'btn-pricemanager-on';
                     if (zen_has_product_discounts($product['products_id']) === 'true') {
                        $ppm_color = 'btn-pricemanager-on-enabled';
                     }
?>
                        <a href="<?php echo zen_href_link(FILENAME_PRODUCTS_PRICE_MANAGER, 'products_filter=' . $product['products_id'] . '&current_category_id=' . $current_category_id); ?>" class="btn btn-sm btn-default <?php echo $ppm_color; ?>" role="button" title="<?php echo BOX_CATALOG_PRODUCTS_PRICE_MANAGER; ?>">
                          <i class="fa fa-dollar fa-lg" aria-hidden="true"></i>
                        </a>
                      <?php } else { ?>
                        <a class="btn btn-sm btn-default btn-pricemanager-off" role="button" disabled title="<?php echo BOX_CATALOG_PRODUCTS_PRICE_MANAGER; ?>">
                          <i class="fa fa-dollar fa-lg" aria-hidden="true"></i>
                        </a>
                        <?php
                      }
// meta tags
                      if (zen_get_product_metatag_fields($product['products_id'], (int)$_SESSION['languages_id'], 'metatags_keywords') || zen_get_product_metatag_fields($product['products_id'], (int)$_SESSION['languages_id'], 'metatags_description')) {
                        ?>
                        <a href="<?php echo zen_href_link(FILENAME_PRODUCT, 'page=' . $_GET['page'] . '&product_type=' . $product['products_type'] . '&cPath=' . $cPath . '&pID=' . $product['products_id'] . '&action=new_product_meta_tags'); ?>" class="btn btn-sm btn-default btn-metatags-on" role="button" title="<?php echo ICON_EDIT_METATAGS; ?>">
                          <i class="fa fa-asterisk fa-lg" aria-hidden="true"></i>
                        </a>
                      <?php } else { ?>
                        <a href="<?php echo zen_href_link(FILENAME_PRODUCT, 'page=' . $_GET['page'] . '&product_type=' . $product['products_type'] . '&cPath=' . $cPath . '&pID=' . $product['products_id'] . '&action=new_product_meta_tags'); ?>" class="btn btn-sm btn-default btn-metatags-off" role="button" title="<?php echo ICON_EDIT_METATAGS; ?>">
                          <i class="fa fa-asterisk fa-lg" aria-hidden="true"></i>
                        </a>
                      <?php } ?>
                    </div>
                  </td>
                <?php } ?>
              </tr>
            <?php } ?>
          </table>
        </div>
        <?php
        $heading = [];
        $contents = [];
        switch ($action) {
          case 'setflag_categories':
            $heading[] = ['text' => '<h5>' . TEXT_INFO_HEADING_STATUS_CATEGORY . '</h5>' . '<h4>' . zen_output_generated_category_path($current_category_id) . ' > ' . zen_get_category_name($cInfo->categories_id, $_SESSION['languages_id']) . '</h4>'];
            $contents = ['form' => zen_draw_form('categories', FILENAME_CATEGORY_PRODUCT_LISTING, 'action=update_category_status&cPath=' . $_GET['cPath'] . '&cID=' . $_GET['cID'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter, 'post', 'enctype="multipart/form-data"') . zen_draw_hidden_field('categories_id', $cInfo->categories_id) . zen_draw_hidden_field('categories_status', $cInfo->categories_status)];

            $contents[] = ['text' => TEXT_CATEGORIES_STATUS_INTRO . ' <strong>' . ($cInfo->categories_status === '1' ? TEXT_CATEGORIES_STATUS_OFF : TEXT_CATEGORIES_STATUS_ON) . '</strong>'];
            $contents[] = ['text' => TEXT_CATEGORIES_STATUS_WARNING];

            if ($cInfo->categories_status === '1') {//category is currently Enabled, so Disable it
              $contents[] = [
                'text' => (
                //hide subcategory selection if no subcategories
                zen_has_category_subcategories($_GET['cID']) ?
                '<fieldset><legend>' . TEXT_SUBCATEGORIES_STATUS_INFO . '</legend>' .
                '<div class="radio"><label>' . zen_draw_radio_field('set_subcategories_status', 'set_subcategories_status_off', true) . TEXT_SUBCATEGORIES_STATUS_OFF . '</label></div>' .
                '<div class="radio"><label>' . zen_draw_radio_field('set_subcategories_status', 'set_subcategories_status_nochange') . TEXT_SUBCATEGORIES_STATUS_NOCHANGE . '</label></div></fieldset>' : '') .
                //hide products selection if no products
                (zen_get_products_to_categories($_GET['cID']) > 0 ?
                '<fieldset><legend>' . TEXT_PRODUCTS_STATUS_INFO . '</legend>' .
                '<div class="radio"><label>' . zen_draw_radio_field('set_products_status', 'set_products_status_off', true) . TEXT_PRODUCTS_STATUS_OFF . '</label></div>' .
                '<div class="radio"><label>' . zen_draw_radio_field('set_products_status', 'set_products_status_nochange') . TEXT_PRODUCTS_STATUS_NOCHANGE . '</label></div></fieldset>' : '')
              ];
            } else {//category is currently Disabled, so Enable it
              $contents[] = [
                'text' => (
                //hide subcategory selection if no subcategories
                zen_has_category_subcategories($_GET['cID']) ?
                '<fieldset><legend>' . TEXT_SUBCATEGORIES_STATUS_INFO . '</legend>' .
                '<div class="radio"><label>' . zen_draw_radio_field('set_subcategories_status', 'set_subcategories_status_on', true) . TEXT_SUBCATEGORIES_STATUS_ON . '</label></div>' .
                '<div class="radio"><label>' . zen_draw_radio_field('set_subcategories_status', 'set_subcategories_status_nochange') . TEXT_SUBCATEGORIES_STATUS_NOCHANGE . '</label></div></fieldset>' : '') .
                //hide products selection if no enabled nor disabled products
                (zen_get_products_to_categories($_GET['cID'], true) > 0 ?
                '<fieldset><legend>' . TEXT_PRODUCTS_STATUS_INFO . '</legend>' .
                '<div class="radio"><label>' . zen_draw_radio_field('set_products_status', 'set_products_status_on', true) . TEXT_PRODUCTS_STATUS_ON . '</label></div>' .
                '<div class="radio"><label>' . zen_draw_radio_field('set_products_status', 'set_products_status_nochange') . TEXT_PRODUCTS_STATUS_NOCHANGE . '</label></div></fieldset>' : '')
              ];
            }

            $contents[] = [
              'align' => 'center',
              'text' => '<button type="submit" class="btn btn-primary">' . IMAGE_UPDATE . '</button> <a href="' . zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING,
                      'cPath=' . $cPath . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . $search_parameter) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'
            ];
            break;
          case 'delete_category':
            $childs_count = zen_childs_in_category_count($cInfo->categories_id);
            $products_count = zen_products_in_category_count($cInfo->categories_id);
            $has_linked = false;
            $prod_list = zen_get_linked_products_for_category($cInfo->categories_id);

            foreach ($prod_list as $prod) {
               if (zen_get_linked_categories_for_product($prod, [$cInfo->categories_id])) {
                  $has_linked = true;
                  break;
               }
            }
            $heading[] = ['text' => '<h4>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</h4>'];

            $contents = ['form' => zen_draw_form('categories', FILENAME_CATEGORY_PRODUCT_LISTING, 'action=delete_category_confirm&cPath=' . $cPath) . zen_draw_hidden_field('categories_id', $cInfo->categories_id)];
            $contents[] = ['text' => TEXT_DELETE_CATEGORY_INTRO];
            if ($has_linked) {
              $contents[] = ['text' => TEXT_DELETE_CATEGORY_INTRO_LINKED_PRODUCTS];
            }

            $contents[] = ['text' => '<strong>' . $cInfo->categories_name . '</strong>'];
            if ($childs_count > 0) {
              $contents[] = ['text' => sprintf(TEXT_DELETE_WARNING_CHILDS, $childs_count)];
            }
            if ($products_count > 0) {
              $contents[] = ['text' => sprintf(TEXT_DELETE_WARNING_PRODUCTS, $products_count)];
            }
            $contents[] = ['align' => 'center', 'text' => '<button type="submit" class="btn btn-danger">' . IMAGE_DELETE . '</button> <a href="' . zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'];
            break;
          case 'move_category':
            $heading[] = ['text' => '<h4>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</h4>'];
            $contents = ['form' => zen_draw_form('move_category', FILENAME_CATEGORY_PRODUCT_LISTING, 'action=move_category_confirm&cPath=' . $cPath, 'post', 'class="form-horizontal"') . zen_draw_hidden_field('categories_id', $cInfo->categories_id)];
            $contents[] = ['text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name)];
            $contents[] = ['text' => zen_draw_pull_down_menu('move_to_category_id', zen_get_category_tree(), $current_category_id, 'class="form-control"')];
            $contents[] = ['align' => 'center', 'text' => '<button type="submit" class="btn btn-primary">' . IMAGE_MOVE . '</button> <a href="' . zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'];
            break;
          case 'delete_product':
            if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/delete_product.php')) {
              require DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/delete_product.php';
            } else {
              require DIR_WS_MODULES . 'delete_product.php';
            }
            break;
          case 'move_product':
            if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/move_product.php')) {
              require DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/move_product.php';
            } else {
              require DIR_WS_MODULES . 'move_product.php';
            }
            break;
          case 'copy_product':
            if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/copy_product.php')) {
              require DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/copy_product.php';
            } else {
              require DIR_WS_MODULES . 'copy_product.php';
            }
            break;
          // attribute features
          case 'attribute_features':
            $copy_attributes_delete_first = '0';
            $copy_attributes_duplicates_skipped = '0';
            $copy_attributes_duplicates_overwrite = '0';
            $copy_attributes_include_downloads = '1';
            $copy_attributes_include_filename = '1';
            $heading[] = ['text' => '<h4>' . TEXT_INFO_HEADING_ATTRIBUTE_FEATURES . $pInfo->products_id . '</h4>'];

            $contents[] = ['align' => 'center', 'text' => '<strong>' . TEXT_PRODUCTS_ATTRIBUTES_INFO . '</strong>'];

            $contents[] = ['align' => 'center', 'text' => '<strong>' . zen_get_products_name($pInfo->products_id, $_SESSION['languages_id']) . ' ID# ' . $pInfo->products_id . '</strong>'];
            $contents[] = ['align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, '&action=attributes_preview' . '&products_filter=' . $pInfo->products_id . '&current_category_id=' . $current_category_id) . '" class="btn btn-info" role="button">' . IMAGE_PREVIEW . '</a> <a href="' . zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $pInfo->products_id . '&current_category_id=' . $current_category_id) . '" class="btn btn-primary" role="button">' . IMAGE_EDIT_ATTRIBUTES . '</a>'];
            $contents[] = ['align' => 'left', 'text' => '<strong>' . TEXT_PRODUCT_ATTRIBUTES_DOWNLOADS . '</strong>' . zen_has_product_attributes_downloads($pInfo->products_id) . zen_has_product_attributes_downloads($pInfo->products_id, true)];
            $contents[] = ['align' => 'left', 'text' => TEXT_INFO_ATTRIBUTES_FEATURES_DELETE . '<strong>' . zen_get_products_name($pInfo->products_id) . ' ID# ' . $pInfo->products_id . '</strong> <a href="' . zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_attributes' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '&products_id=' . $pInfo->products_id) . '" class="btn btn-danger" role="button">' . IMAGE_DELETE . '</a>'];
            $contents[] = ['align' => 'left', 'text' => TEXT_INFO_ATTRIBUTES_FEATURES_UPDATES . '<strong>' . zen_get_products_name($pInfo->products_id, $_SESSION['languages_id']) . ' ID# ' . $pInfo->products_id . '</strong> <a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=update_attributes_sort_order' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '&products_id=' . $pInfo->products_id) . '" class="btn btn-primary" role="button">' . IMAGE_UPDATE . '</a>'];
            $contents[] = ['align' => 'left', 'text' => TEXT_INFO_ATTRIBUTES_FEATURES_COPY_TO_PRODUCT . '<strong>' . zen_get_products_name($pInfo->products_id, $_SESSION['languages_id']) . ' ID# ' . $pInfo->products_id . '</strong><a href="' . zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=attribute_features_copy_to_product' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '&products_id=' . $pInfo->products_id) . '" class="btn btn-primary" role="button">' . IMAGE_COPY_TO . '</a>'];
            $contents[] = ['align' => 'left', 'text' => '<br>' . TEXT_INFO_ATTRIBUTES_FEATURES_COPY_TO_CATEGORY . '<strong>' . zen_get_products_name($pInfo->products_id, $_SESSION['languages_id']) . ' ID# ' . $pInfo->products_id . '</strong> <a href="' . zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=attribute_features_copy_to_category' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . '&products_id=' . $pInfo->products_id) . '" class="btn btn-primary" role="button">' . IMAGE_COPY_TO . '</a>'];
            $contents[] = ['align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'];
            break;

          // attribute copier to product
          case 'attribute_features_copy_to_product':
            $_GET['products_update_id'] = '';
            // excluded current product from the pull down menu of products
            $products_exclude_array = [];
            $products_exclude_array[] = $pInfo->products_id;

            $heading[] = ['text' => '<h4>' . TEXT_INFO_HEADING_ATTRIBUTE_FEATURES . $pInfo->products_id . '</h4>'];
            $contents = ['form' => zen_draw_form('products', FILENAME_CATEGORY_PRODUCT_LISTING, 'action=update_attributes_copy_to_product&cPath=' . $cPath . (isset($_GET['page']) ? '&page=' . $_GET['page'] : ''), 'post', 'class="form-horizontal"') . zen_draw_hidden_field('products_id', $pInfo->products_id) . zen_draw_hidden_field('products_update_id', $_GET['products_update_id']) . zen_draw_hidden_field('copy_attributes', $_GET['copy_attributes'])];
            $contents[] = ['text' => zen_draw_label(TEXT_COPY_ATTRIBUTES_CONDITIONS, 'copy_attributes', 'class="control-label"') . '<div class="radio"><label>' . zen_draw_radio_field('copy_attributes', 'copy_attributes_delete', true) . TEXT_COPY_ATTRIBUTES_DELETE . '</label></div><div class="radio"><label>' . zen_draw_radio_field('copy_attributes', 'copy_attributes_update') . TEXT_COPY_ATTRIBUTES_UPDATE . '</label></div><div class="radio"><label>' . zen_draw_radio_field('copy_attributes', 'copy_attributes_ignore') . TEXT_COPY_ATTRIBUTES_IGNORE . '</label></div>'];
            $contents[] = ['text' => zen_draw_pulldown_products('products_update_id', 'class="form-control"', $products_exclude_array, true)];
            $contents[] = ['align' => 'center', 'text' => '<button type="submit" class="btn btn-primary">' . IMAGE_COPY_TO . '</button> <a href="' . zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'];
            break;

          // attribute copier to product
          case 'attribute_features_copy_to_category':
            $_GET['categories_update_id'] = '';

            $heading[] = ['text' => '<h4>' . TEXT_INFO_HEADING_ATTRIBUTE_FEATURES . $pInfo->products_id . '</h4>'];
            $contents = ['form' => zen_draw_form('products', FILENAME_CATEGORY_PRODUCT_LISTING, 'action=update_attributes_copy_to_category&cPath=' . $cPath . (isset($_GET['page']) ? '&page=' . $_GET['page'] : ''), 'post', 'class="form-horizontal"') . zen_draw_hidden_field('products_id', $pInfo->products_id) . zen_draw_hidden_field('categories_update_id', $_GET['categories_update_id']) . zen_draw_hidden_field('copy_attributes', $_GET['copy_attributes'])];
            $contents[] = ['text' => zen_draw_label(TEXT_COPY_ATTRIBUTES_CONDITIONS, 'copy_attributes', 'class="control-label"') . '<div class="radio"><label>' . zen_draw_radio_field('copy_attributes', 'copy_attributes_delete', true) . TEXT_COPY_ATTRIBUTES_DELETE . '</label></div><div class="radio"><label>' . zen_draw_radio_field('copy_attributes', 'copy_attributes_update') . TEXT_COPY_ATTRIBUTES_UPDATE . '</label></div><div class="radio"><label>' . zen_draw_radio_field('copy_attributes', 'copy_attributes_ignore') . TEXT_COPY_ATTRIBUTES_IGNORE . '</label></div>'];
            $contents[] = ['text' => zen_draw_pulldown_categories_having_products('categories_update_id', 'class="form-control"', '', true)];
            $contents[] = ['align' => 'center', 'text' => '<button type="submit" class="btn btn-primary">' . IMAGE_COPY_TO . '</button> <a href="' . zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '')) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>'];
            break;
        }
        if (!empty($heading) && !empty($contents)) {
          $box = new box;
          echo '<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 configurationColumnRight">';
          echo $box->infoBox($heading, $contents);
          echo '</div>';
        }
        ?>
      </div>
      <?php
      if ($action === '') {
        $cPath_back = '';
        if (count($cPath_array) > 0) {
          for ($i = 0, $n = count($cPath_array) - 1; $i < $n; $i++) {
            if (empty($cPath_back)) {
              $cPath_back .= $cPath_array[$i];
            } else {
              $cPath_back .= '_' . $cPath_array[$i];
            }
          }
        }

        $cPath_back = (zen_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
        ?>
        <div class="row">
          <div class="col-md-3"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></div>
          <div class="col-md-9 text-right">
            <?php if (count($cPath_array) > 0) { ?>
              <div class="col-sm-3">
                <a href="<?php echo zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, $cPath_back . 'cID=' . $current_category_id); ?>" class="btn btn-default" role="button"><?php echo IMAGE_BACK; ?></a>
              </div>
              <?php
            }
            if (!isset($_GET['search']) && !$zc_skip_categories) {
              ?>
              <div class="col-sm-3">
                <a href="<?php echo zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category'); ?>" class="btn btn-primary" role="button"><?php echo IMAGE_NEW_CATEGORY; ?></a>
              </div>
            <?php } ?>

            <?php if ($zc_skip_products === false) { ?>
              <?php echo zen_draw_form('newproduct', FILENAME_PRODUCT, 'action=new_product', 'post', 'class="form-horizontal"'); ?>
              <?php echo (empty($_GET['search']) ? '<div class="col-xs-6 col-sm-2"><button type="submit" class="btn btn-primary">' . IMAGE_NEW_PRODUCT . '</button></div>' : ''); ?>
              <?php
              $product_types = zen_get_category_restricted_product_types($current_category_id);

              if (empty($product_types)) {
                // There are no restricted product types so offer all types instead
                $sql = "SELECT * FROM " . TABLE_PRODUCT_TYPES;
                $product_types = $db->Execute($sql);
              }

              $product_restrict_types_array = [];

              foreach ($product_types as $restrict_type) {
                $product_restrict_types_array[] = [
                  'id' => $restrict_type['type_id'],
                  'text' => $restrict_type['type_name'],
                ];
              }
              ?>
              <?php
              echo '<div class="col-xs-6 col-sm-4 col-md-3">' . zen_draw_pull_down_menu('product_type', $product_restrict_types_array, '', 'class="form-control"') . '</div>';
              echo zen_hide_session_id();
              echo zen_draw_hidden_field('cPath', $cPath);
              echo zen_draw_hidden_field('action', 'new_product');
              echo '</form>';
              ?>
              <?php
            } else {
              echo CATEGORY_HAS_SUBCATEGORIES;
              ?>
              <?php
            } // hide has cats
            ?>
          </div>
        </div>
        <div class="row text-center alert">
          <?php
          // warning if products are in top level categories
          $check_products_top_categories = zen_get_linked_products_for_category(TOPMOST_CATEGORY_PARENT_ID);
          if (!empty($check_products_top_categories)) {
            echo WARNING_PRODUCTS_IN_TOP_INFO . count($check_products_top_categories) . '<br>';
          }
          ?>
        </div>
        <div class="row text-center">
          <?php
// Split Page
          if ($products_query_numrows > 0) {
            echo $products_split->display_count($products_query_numrows, $max_results, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS) . '<br>' . $products_split->display_links($products_query_numrows, $max_results, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], zen_get_all_get_params(['page', 'info', 'x', 'y', 'pID']));
          }
          ?>
        </div>
      <?php } ?>
    </div>
    <!--  enable on-page script tools -->
    <script>
        <?php
        $categorySelectLink = str_replace('&amp;', '&', zen_href_link(FILENAME_CATEGORY_PRODUCT_LISTING, zen_get_all_get_params(['cPath', 'action']) . "cPath=[*]"));
        $productEditLink = str_replace('&amp;', '&', zen_href_link(FILENAME_PRODUCT, zen_get_all_get_params(['pID', 'action']) . "pID=[*]&action=new_product"));
        ?>
        jQuery(function () {
            const categorySelectlink = '<?php echo $categorySelectLink; ?>';
            const productEditLink = '<?php echo $productEditLink; ?>';
            jQuery("tr.category-listing-row td").not('.dataTableButtonCell').on('click', (function() {
                window.location.href = categorySelectlink.replace('[*]', jQuery(this).parent().attr('data-cid'));
            })).css('cursor', 'pointer');
            jQuery("tr.product-listing-row td").not('.dataTableButtonCell').on('click', (function() {
                window.location.href = productEditLink.replace('[*]', jQuery(this).parent().attr('data-pid'));
            })).css('cursor', 'pointer');
        })
    </script>
    <!-- footer //-->
    <?php require DIR_WS_INCLUDES . 'footer.php'; ?>
    <!-- footer_eof //-->
  </body>
</html>
<?php
require DIR_WS_INCLUDES . 'application_bottom.php';
