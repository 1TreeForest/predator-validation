<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: brittainmark 2022 Sep 17 Modified in v1.5.8 $
 */
require 'includes/application_top.php';

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$currentPage = (!empty($_GET['page']) && ctype_alpha($_GET['page'][0]) ? $_GET['page'][0] : '');

if (!empty($action)) {
  switch ($action) {
    case 'insert':
      $countries_name = zen_db_prepare_input($_POST['countries_name']);
      $countries_iso_code_2 = strtoupper(zen_db_prepare_input($_POST['countries_iso_code_2']));
      $countries_iso_code_3 = strtoupper(zen_db_prepare_input($_POST['countries_iso_code_3']));
      $address_format_id = zen_db_prepare_input($_POST['address_format_id']);
      $status = $_POST['status'] == 'on' ? 1 : 0;

      $db->Execute("INSERT INTO " . TABLE_COUNTRIES . " (countries_name, countries_iso_code_2, countries_iso_code_3, status, address_format_id)
                    VALUES ('" . zen_db_input($countries_name) . "',
                            '" . zen_db_input($countries_iso_code_2) . "',
                            '" . zen_db_input($countries_iso_code_3) . "',
                            " . (int)$status . ",
                            " . (int)$address_format_id . ")");
      zen_record_admin_activity('Country added: ' . $countries_iso_code_3, 'info');
      zen_redirect(zen_href_link(FILENAME_COUNTRIES));
      break;
    case 'save':
      $countries_id = zen_db_prepare_input($_GET['cID']);
      $countries_name = zen_db_prepare_input($_POST['countries_name']);
      $countries_iso_code_2 = strtoupper(zen_db_prepare_input($_POST['countries_iso_code_2']));
      $countries_iso_code_3 = strtoupper(zen_db_prepare_input($_POST['countries_iso_code_3']));
      $address_format_id = zen_db_prepare_input($_POST['address_format_id']);
      $status = $_POST['status'] == 'on' ? 1 : 0;

      $db->Execute("UPDATE " . TABLE_COUNTRIES . "
                    SET countries_name = '" . zen_db_input($countries_name) . "',
                        countries_iso_code_2 = '" . zen_db_input($countries_iso_code_2) . "',
                        countries_iso_code_3 = '" . zen_db_input($countries_iso_code_3) . "',
                        address_format_id = " . (int)$address_format_id . ",
                        status = " . (int)$status . "
                    WHERE countries_id = " . (int)$countries_id);
      zen_record_admin_activity('Country updated: ' . $countries_iso_code_3, 'info');
      zen_redirect(zen_href_link(FILENAME_COUNTRIES, ($currentPage !== '' ? 'page=' . $currentPage . '&' : '') . 'cID=' . $countries_id));
      break;
    case 'deleteconfirm':
      $countries_id = zen_db_prepare_input($_POST['cID']);
      $sql = "SELECT entry_country_id
              FROM " . TABLE_ADDRESS_BOOK . "
              WHERE entry_country_id = " . (int)$countries_id . "
              LIMIT 1";
      $result = $db->Execute($sql);
      if ($result->recordCount() == 0) {
        $db->Execute("DELETE FROM " . TABLE_COUNTRIES . "
                      WHERE countries_id = " . (int)$countries_id);
        zen_record_admin_activity('Country deleted: ' . $countries_id, 'warning');
      } else {
        $messageStack->add_session(ERROR_COUNTRY_IN_USE, 'error');
      }
      zen_redirect(zen_href_link(FILENAME_COUNTRIES, ($currentPage !== '' ? 'page=' . $currentPage : '')));
      break;
    case 'setstatus':
      $countries_id = (int)$_POST['current_country'];
      if (isset($_POST['current_status']) && ($_POST['current_status'] == '0' || $_POST['current_status'] == '1')) {
        $sql = "UPDATE " . TABLE_COUNTRIES . "
                SET status = " . ($_POST['current_status'] == 0 ? 1 : 0) . "
                WHERE countries_id = " . (int)$countries_id;
        $db->Execute($sql);
        zen_record_admin_activity('Country with ID number: ' . $countries_id . ' changed status to ' . ($_POST['current_status'] == 0 ? 1 : 0), 'info');
        zen_redirect(zen_href_link(FILENAME_COUNTRIES, ($currentPage !== '' ? 'page=' . $currentPage . '&' : '') . 'cID=' . (int)$countries_id));
      }
      $action = '';
      break;
  }
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
      <h1><?php echo HEADING_TITLE; ?></h1>
      <div class="text-right"><?php echo ISO_COUNTRY_CODES_LINK; ?></div>

      <!-- body_text //-->
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 configurationColumnLeft">
          <table class="table table-hover">
            <thead>
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent col-sm-6"><?php echo TABLE_HEADING_COUNTRY_NAME; ?></th>
                <th class="dataTableHeadingContent text-center" colspan="2"><?php echo TABLE_HEADING_COUNTRY_CODES; ?></th>
                <th class="dataTableHeadingContent text-center"><?php echo TABLE_HEADING_COUNTRY_STATUS; ?></th>
                <th class="dataTableHeadingContent text-right"><?php echo TABLE_HEADING_ACTION; ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $countries_query_raw = "SELECT countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id, status
                                      FROM " . TABLE_COUNTRIES . "
                                      ORDER BY countries_name";
              $countries_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $countries_query_raw, $countries_query_numrows, 'countries_name', 1);
              $countries = $db->Execute($countries_query_raw);
              foreach ($countries as $country) {
                if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $country['countries_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                  $cInfo = new objectInfo($country);
                }

                if (isset($cInfo) && is_object($cInfo) && ($country['countries_id'] == $cInfo->countries_id)) {
                  ?>
                  <tr id="defaultSelected" class="dataTableRowSelected" onclick = "document.location.href = '<?php echo zen_href_link(FILENAME_COUNTRIES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->countries_id . '&action=edit'); ?>'" role="button">
                  <?php } else { ?>
                  <tr class="dataTableRow country-listing-row" onclick="document.location.href = '<?php echo zen_href_link(FILENAME_COUNTRIES, zen_get_all_get_params(array('cID', 'action')) . 'cID=' . $country['countries_id']); ?>'" data-cid="<?php echo $country['countries_id']; ?>">
                  <?php } ?>
                  <td class="dataTableContent col-sm-6"><?php echo zen_output_string_protected($country['countries_name']); ?></td>
                  <td class="dataTableContent text-center"><?php echo $country['countries_iso_code_2']; ?></td>
                  <td class="dataTableContent text-center"><?php echo $country['countries_iso_code_3']; ?></td>
                  <td class="dataTableContent text-center dataTableButtonCell">
                    <?php echo zen_draw_form('setstatus_' . (int)$country['countries_id'], FILENAME_COUNTRIES, zen_get_all_get_params(['action']) . 'action=setstatus'); ?>
                    <button type="submit" class="btn btn-status">
                      <?php if ($country['status'] == '0') { ?>
                        <i class="fa fa-square txt-status-off" title="<?php echo IMAGE_ICON_STATUS_OFF; ?>"></i>
                      <?php } else { ?>
                        <i class="fa fa-square txt-status-on" title="<?php echo IMAGE_ICON_STATUS_ON; ?>"></i>
                      <?php } ?>
                    </button>
                    <?php
                    echo zen_draw_hidden_field('current_country', $country['countries_id']);
                    echo zen_draw_hidden_field('current_status', $country['status']);
                    echo '</form>';
                    ?>
                  </td>
                  <td class="dataTableContent text-right">
                    <?php if (isset($cInfo) && is_object($cInfo) && ($country['countries_id'] == $cInfo->countries_id)) { ?>
                      <i class="fa fa-caret-right fa-2x fa-fw txt-navy align-middle"></i>
                    <?php } else { ?>
                      <a href="<?php echo zen_href_link(FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage . '&' : '') . 'cID=' . $country['countries_id']); ?>" title="<?php echo IMAGE_ICON_INFO; ?>" role="button">
                        <i class="fa fa-info-circle fa-2x fa-fw txt-black align-middle"></i>
                      </a>
                    <?php } ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 configurationColumnRight">
          <?php
          $heading = [];
          $contents = [];

          switch ($action) {
            case 'new':
              $heading[] = array('text' => '<h4>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</h4>');
              $contents = array('form' => zen_draw_form('countries', FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage . '&' : '') . 'action=insert', 'post', 'class="form-horizontal"'));
              $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_COUNTRY_NAME, 'countries_name', 'class="control-label"') . zen_draw_input_field('countries_name', '', 'class="form-control"'));
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_COUNTRY_CODE_2, 'countries_iso_code_2', 'class="control-label"') . zen_draw_input_field('countries_iso_code_2', '', 'class="form-control"'));
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_COUNTRY_CODE_3, 'countries_iso_code_3', 'class="control-label"') . zen_draw_input_field('countries_iso_code_3', '', 'class="form-control"'));
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_ADDRESS_FORMAT, 'address_format_id', 'class="control-label"') . zen_draw_pull_down_menu('address_format_id', zen_get_address_formats(), '', 'class="form-control"'));
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_COUNTRY_STATUS, 'status', 'class="control-label"') . zen_draw_checkbox_field('status', '', true, 'class="form-control"'));
              $contents[] = array('align' => 'text-center', 'text' => '<br><button type="submit" class="btn btn-primary">' . IMAGE_INSERT . '</button> <a href="' . zen_href_link(FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage : '')) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>');
              break;
            case 'edit':
              $heading[] = array('text' => '<h4>' . TEXT_INFO_HEADING_EDIT_COUNTRY . '</h4>');
              $contents = array('form' => zen_draw_form('countries', FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage . '&' : '') . 'cID=' . $cInfo->countries_id . '&action=save', 'post', 'class="form-horizontal"'));
              $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_COUNTRY_NAME, 'countries_name', 'class="control-label"') . zen_draw_input_field('countries_name', htmlspecialchars($cInfo->countries_name, ENT_COMPAT, CHARSET, TRUE), 'class="form-control"'));
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_COUNTRY_CODE_2, 'countries_iso_code_2', 'class="control-label"') . zen_draw_input_field('countries_iso_code_2', $cInfo->countries_iso_code_2, 'class="form-control"'));
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_COUNTRY_CODE_3, 'countries_iso_code_3', 'class="control-label"') . zen_draw_input_field('countries_iso_code_3', $cInfo->countries_iso_code_3, 'class="form-control"'));
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_ADDRESS_FORMAT, 'address_format_id', 'class="control-label"') . zen_draw_pull_down_menu('address_format_id', zen_get_address_formats(), $cInfo->address_format_id, 'class="form-control"'));
              $contents[] = array('text' => '<br>' . zen_draw_label(TEXT_INFO_COUNTRY_STATUS, 'status', 'class="control-label"') . zen_draw_checkbox_field('status', '', $cInfo->status));
              $contents[] = array('align' => 'text-center', 'text' => '<br><button type="submit" class="btn btn-primary">' . IMAGE_UPDATE . '</button> <a href="' . zen_href_link(FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage . '&' : '') . 'cID=' . $cInfo->countries_id) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>');
              break;
            case 'delete':
              $heading[] = array('text' => '<h4>' . TEXT_INFO_HEADING_DELETE_COUNTRY . '</h4>');
              $contents = array('form' => zen_draw_form('countries', FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage . '&' : '') . 'action=deleteconfirm') . zen_draw_hidden_field('cID', $cInfo->countries_id));
              $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
              $contents[] = array('text' => '<br><b>' . zen_output_string_protected($cInfo->countries_name) . '</b>');
              $contents[] = array('align' => 'text-center', 'text' => '<br><button type="submit" class="btn btn-danger">' . IMAGE_UPDATE . '</button> <a href="' . zen_href_link(FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage . '&' : '') . 'cID=' . $cInfo->countries_id) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>');
              break;
            default:
              if (is_object($cInfo)) {
                $heading[] = array('text' => '<h4>' . zen_output_string_protected($cInfo->countries_name) . '</h4>');
                $contents[] = array('align' => 'text-center', 'text' => '<a href="' . zen_href_link(FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage . '&' : '') . 'cID=' . $cInfo->countries_id . '&action=edit') . '" class="btn btn-primary" role="button">' . IMAGE_EDIT . '</a> <a href="' . zen_href_link(FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage . '&' : '') . 'cID=' . $cInfo->countries_id . '&action=delete') . '" class="btn btn-warning" role="button">' . IMAGE_DELETE . '</a>');
                $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . zen_output_string_protected($cInfo->countries_name));
                $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . ' ' . $cInfo->countries_iso_code_2);
                $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . ' ' . $cInfo->countries_iso_code_3);
                $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . ' ' . $cInfo->address_format_id);
                $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_STATUS . ' ' . ($cInfo->status == 0 ? TEXT_NO : TEXT_YES));
              }
              break;
          }

          if (!empty($heading) && !empty($contents)) {
            $box = new box;
            echo $box->infoBox($heading, $contents);
          }
          ?>
        </div>
        <!-- body_text_eof //-->
      </div>

      <div class="row">
        <table class="table">
          <tr>
            <td><?php echo $countries_split->display_count($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
            <td class="text-right"><?php echo $countries_split->display_links($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
          </tr>
          <?php if (empty($action)) { ?>
            <tr>
              <td colspan="2" class="text-right"><a href="<?php echo zen_href_link(FILENAME_COUNTRIES, ($currentPage != 0 ? 'page=' . $currentPage . '&' : '') . 'action=new'); ?>" class="btn btn-primary" role="button"><?php echo IMAGE_NEW_COUNTRY; ?></a></td>
            </tr>
          <?php } ?>
        </table>
      </div>
    </div>
    <!-- body_eof //-->

    <!-- footer //-->
    <?php require DIR_WS_INCLUDES . 'footer.php'; ?>
    <!-- footer_eof //-->
  </body>
</html>
<?php
require DIR_WS_INCLUDES . 'application_bottom.php';
