<?php
/**
 * Page Template
 *
 * Displays the FAQ pages for the Gift-Certificate/Voucher system.
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Dec 25 Modified in v1.5.8-alpha $
 */
?>
<div class="centerColumn" id="gvFaqDefault">

<?php
// only show when there is a GV balance
  if (!empty($customer_has_gv_balance) ) {
?>
<div id="sendSpendWrapper">
<?php require($template->get_template_dir('tpl_modules_send_or_spend.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_send_or_spend.php'); ?>
</div>
<?php
  }
?>

<h1 id="gvFaqDefaultHeading"><?php echo HEADING_TITLE; ?></h1>

<div id="gvFaqDefaultMainContent" class="content"><?php echo TEXT_INFORMATION; ?></div>

<h2 id="gvFaqDefaultSubHeading"><?php echo $subHeadingTitle; ?></h2>

<div id="gvFaqDefaultContent" class="content"><?php echo $subHeadingText; ?></div>

<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
<br class="clearBoth">


<form action="<?php echo zen_href_link(FILENAME_GV_REDEEM, '', 'NONSSL', false); ?>" method="get">
<?php echo zen_draw_hidden_field('main_page',FILENAME_GV_REDEEM) . zen_draw_hidden_field('goback','true') . zen_hide_session_id(); ?>
<fieldset>
<legend><?php echo TEXT_GV_REDEEM_INFO; ?></legend>
<label class="inputLabel" for="lookup-gv-redeem"><?php echo TEXT_GV_REDEEM_ID; ?></label>
<?php echo zen_draw_input_field('gv_no', $gv_faq_item, 'size="18" id="lookup-gv-redeem"');?>
</fieldset>
<div class="buttonRow forward"><?php echo zen_image_submit(BUTTON_IMAGE_REDEEM, BUTTON_REDEEM_ALT); ?></div>
</form>

</div>
