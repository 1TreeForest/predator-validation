<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=search.
 * Displays options fields upon which a product search will be run
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Dec 25 New in v1.5.8-alpha $
 */
?>
<div class="centerColumn" id="searchDefault">

<?php echo zen_draw_form('search', zen_href_link(FILENAME_SEARCH_RESULT, '', $request_type, false), 'get', 'onsubmit="return check_form(this);"') . zen_hide_session_id(); ?>
<?php echo zen_draw_hidden_field('main_page', FILENAME_SEARCH_RESULT); ?>

<h1 id="searchDefaultHeading"><?php echo HEADING_TITLE_1; ?></h1>

<?php if ($messageStack->size('search') > 0) echo $messageStack->output('search'); ?>

<fieldset>
<legend><?php echo HEADING_SEARCH_CRITERIA; ?></legend>
<div class="forward"><?php echo '<a href="javascript:popupWindow(\'' . zen_href_link(FILENAME_POPUP_SEARCH_HELP) . '\')">' . TEXT_SEARCH_HELP_LINK . '</a>'; ?></div>
<br class="clearBoth">
    <div class="centeredContent"><?php echo zen_draw_input_field('keyword', $sData['keyword'], 'placeholder="' . KEYWORD_FORMAT_STRING . '" autofocus aria-label="' . KEYWORD_FORMAT_STRING . '"', 'search'); ?>
        &nbsp;&nbsp;&nbsp;<?php echo zen_draw_checkbox_field('search_in_description', '1', $sData['search_in_description'], 'id="search-in-description"'); ?>
        <label class="checkboxLabel" for="search-in-description"><?php echo TEXT_SEARCH_IN_DESCRIPTION; ?></label></div>
<br class="clearBoth">
</fieldset>

<fieldset class="floatingBox back">
    <legend><?php echo ENTRY_CATEGORIES; ?></legend>
    <div class="floatLeft"><?php echo zen_draw_pull_down_menu('categories_id', zen_get_categories(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES)), '0' ,'', '1'), $sData['categories_id'], 'id="searchCategoryId" aria-label="' . PLEASE_SELECT . '"'); ?></div>
<?php echo zen_draw_checkbox_field('inc_subcat', '1', $sData['inc_subcat'], 'id="inc-subcat"'); ?><label class="checkboxLabel" for="inc-subcat"><?php echo ENTRY_INCLUDE_SUBCATEGORIES; ?></label>
<br class="clearBoth">
</fieldset>

<?php if (empty($skip_manufacturers)) { ?>
<fieldset class="floatingBox forward">
    <legend><?php echo ENTRY_MANUFACTURERS; ?></legend>
    <?php echo zen_draw_pull_down_menu('manufacturers_id', zen_get_manufacturers(array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS)), PRODUCTS_MANUFACTURERS_STATUS), $sData['manufacturers_id'], 'id="searchMfgId" aria-label="' . PLEASE_SELECT . '"'); ?>
<br class="clearBoth">
</fieldset>
<?php } ?>
<br class="clearBoth">

<fieldset class="floatingBox back">
<legend><?php echo ENTRY_PRICE_RANGE; ?></legend>
<fieldset class="floatLeft">
    <label for="pfrom"><?php echo ENTRY_PRICE_FROM; ?></label>
    <?php echo zen_draw_input_field('pfrom', $sData['pfrom'], 'id="pfrom" inputmode="decimal"'); ?>
</fieldset>
<fieldset class="floatLeft">
    <label for="pto"><?php echo ENTRY_PRICE_TO; ?></label>
    <?php echo zen_draw_input_field('pto', $sData['pto'], 'id="pto" inputmode="decimal"'); ?>
</fieldset>
</fieldset>

<fieldset class="floatingBox forward">
<legend><?php echo ENTRY_DATE_RANGE; ?></legend>
<fieldset class="floatLeft">
    <label for="dfrom"><?php echo ENTRY_DATE_FROM; ?></label>
    <?php echo zen_draw_input_field('dfrom', $sData['dfrom'], 'id="dfrom" placeholder="' . DOB_FORMAT_STRING . '" onfocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?>
</fieldset>
<fieldset class="floatLeft">
    <label for="dto"><?php echo ENTRY_DATE_TO; ?></label>
    <?php echo zen_draw_input_field('dto', $sData['dto'], 'id="dto" placeholder="' . DOB_FORMAT_STRING . '" onfocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"'); ?>
</fieldset>
</fieldset>
<br class="clearBoth">


<div class="buttonRow forward"><?php echo zen_image_submit(BUTTON_IMAGE_SEARCH, BUTTON_SEARCH_ALT); ?></div>
<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>

</form>
</div>
