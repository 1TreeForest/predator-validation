<?php
/**
 * Page Template
 *
 * Template used to collect/display details of sending a GV to a friend from own GV balance.
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: lat9 2022 Aug 02 Modified in v1.5.8-alpha2 $
 */
?>
<div class="centerColumn" id="gvSendDefault">
    <div id="sendSpendWrapper" class="forward">
        <h2><?php echo TEXT_AVAILABLE_BALANCE;?></h2>
        <p id="gvSendDefaultBalance"><?php echo TEXT_BALANCE_IS . $gv_current_balance; ?></p>
<?php
$action = $_GET['action'] ?? '';
$to_name = (isset($_POST['to_name'])) ? zen_output_string_protected($_POST['to_name']) : '';
$error = $error ?? false;
if ($gv_result->fields['amount'] > 0 && $action === 'doneprocess') {
?>
        <p><?php echo TEXT_SEND_ANOTHER; ?></p>
        <div class="buttonRow forward">
            <a href="<?php echo zen_href_link(FILENAME_GV_SEND, '', 'SSL', false); ?>"><?php echo zen_image_button(BUTTON_IMAGE_SEND_ANOTHER, BUTTON_SEND_ANOTHER_ALT); ?></a>
        </div>
<?php
}
?>
    </div>
<?php
if ($action === 'doneprocess') {
?>
<!--BOF GV sent success-->
    <h1 id="gvSendDefaultHeadingDone"><?php echo HEADING_TITLE_COMPLETED; ?></h1>

    <div id="gvSendDefaultContentSuccess" class="content"><?php echo TEXT_SUCCESS; ?></div>

    <div class="buttonRow forward">
        <a href="<?php echo zen_href_link(FILENAME_DEFAULT, '', 'SSL', false); ?>"><?php echo zen_image_button(BUTTON_IMAGE_CONTINUE, BUTTON_CONTINUE_ALT); ?></a>
    </div>
<!--EOF GV sent success -->
<?php
} elseif ($action === 'send' && $error === false) {
?>
<!--BOF GV send confirm -->
    <h1 id="gvSendDefaultHeadingConfirm"><?php echo HEADING_TITLE_CONFIRM_SEND; ?></h1>

    <?php echo zen_draw_form('gv_send_process', zen_href_link(FILENAME_GV_SEND, 'action=process', 'SSL', false)); ?>
        <div id="gvSendDefaultMainMessage" class="content">
            <?php echo sprintf(MAIN_MESSAGE, $currencies->format($currencies->normalizeValue($_POST['amount']), false), $to_name, $_POST['email']); ?>
        </div>

        <div id="gvSendDefaultMessageSecondary" class="content">
            <?php echo sprintf(SECONDARY_MESSAGE, $to_name, $currencies->format($currencies->normalizeValue($_POST['amount']), false), $send_name); ?>
        </div>
<?php
    if (!empty($_POST['message'])) {
?>
        <div id="gvSendDefaultMessagePersonal" class="content"><?php echo sprintf(PERSONAL_MESSAGE, $send_firstname); ?></div>

        <div id="gvSendDefaultMessage" class="content"><?php echo stripslashes($_POST['message']); ?></div>
<?php
    }

    echo zen_draw_hidden_field('to_name', stripslashes($to_name)) .
         zen_draw_hidden_field('email', $_POST['email']) .
         zen_draw_hidden_field('amount', $gv_amount) .
         zen_draw_hidden_field('message', stripslashes($_POST['message']));
?>
        <div class="buttonRow forward"><?php echo zen_image_submit(BUTTON_IMAGE_CONFIRM_SEND, BUTTON_CONFIRM_SEND_ALT); ?></div>
        <div class="buttonRow back"><?php echo zen_image_submit(BUTTON_IMAGE_EDIT_SMALL, BUTTON_EDIT_SMALL_ALT, 'name="edit" value="edit"'); ?></div>

    <?php echo '</form>'; ?>
    <br class="clearBoth">

    <div class="advisory"><?php echo EMAIL_ADVISORY_INCLUDED_WARNING . str_replace('-----', '', EMAIL_ADVISORY); ?></div>
<!--EOF GV send confirm -->
<?php
 } else {
?>
<!--BOF GV send-->
    <h1 id="gvSendDefaultHeadingSend"><?php echo HEADING_TITLE; ?></h1>

    <div id="gvSendDefaultMainContent" class="content"><?php echo HEADING_TEXT; ?></div>
    <br class="clearBoth">
<?php
    if ($messageStack->size('gv_send') > 0) {
        echo $messageStack->output('gv_send');
    }
?>
    <?php echo zen_draw_form('gv_send_send', zen_href_link(FILENAME_GV_SEND, 'action=send', 'SSL', false)); ?>
        <fieldset>
            <legend><?php echo HEADING_TITLE; ?></legend>

            <label class="inputLabel" for="to-name"><?php echo ENTRY_RECIPIENT_NAME; ?></label>
            <?php echo zen_draw_input_field('to_name', $to_name, 'size="40" id="to-name"') . '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?>
            <br class="clearBoth">

            <label class="inputLabel" for="email-address"><?php echo ENTRY_EMAIL; ?></label>
            <?php echo zen_draw_input_field('email', (!empty($_POST['email'])? $_POST['email'] : ''), 'size="40" id="email-address"', 'email') . '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?>
            <br class="clearBoth">

            <label class="inputLabel" for="amount"><?php echo ENTRY_AMOUNT; ?></label>
            <?php echo zen_draw_input_field('amount', (!empty($_POST['amount']) ? $_POST['amount'] : ''), 'id="amount"', 'text', false) . '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>'; ?>
            <br class="clearBoth">

            <label for="message-area"><?php echo ENTRY_MESSAGE; ?></label>
            <?php echo zen_draw_textarea_field('message', 50, 10, (!empty($_POST['message']) ? stripslashes($_POST['message']) : ''), 'id="message-area"'); ?>
        </fieldset>

        <div class="buttonRow forward"><?php echo zen_image_submit(BUTTON_IMAGE_SEND, BUTTON_SEND_ALT); ?></div>
        <div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
        <br class="clearBoth">
    <?php echo '</form>'; ?>

    <div class="advisory"><?php echo EMAIL_ADVISORY_INCLUDED_WARNING . str_replace('-----', '', EMAIL_ADVISORY); ?></div>
<?php
}
?>
<!--EOF GV send-->
</div>
